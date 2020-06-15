<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    mod_wooflash
 * @copyright  2018 Cblue sprl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_wooflash\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir . '/filelib.php';

// The Privacy API implements an interface which is not present in older versions of Moodle
// So we polyfill it.

if (interface_exists('\core_privacy\local\request\userlist')) {
    interface wooflash_userlist extends \core_privacy\local\request\userlist {}
} else {
    interface wooflash_userlist {};
}

class provider implements
\core_privacy\local\metadata\provider,
wooflash_userlist,
\core_privacy\local\request\plugin\provider {

    /**
     * Return the fields which contain personal data.
     *
     * @param collection $items a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $collection): collection{

        $collection->add_external_location_link('wooflash_server', [
            'userid' => 'privacy:metadata:wooflash_server:userid',
        ], 'privacy:metadata:wooflash_server');

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid the userid.
     * @return contextlist the list of contexts containing user info for the user.
     */
    public static function get_contexts_for_userid(int $userid): contextlist{
        $contextlist = new \core_privacy\local\request\contextlist();

        // First add wooflash activity created by the user.
        $sql = "SELECT c.id
                 FROM {context} c
           INNER JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
           INNER JOIN {modules} m ON m.id = cm.module AND m.name = :modname
           INNER JOIN {wooflash} w ON w.id = cm.instance
                WHERE w.authorid = :userid
        ";
        $params = [
            'modname' => 'wooflash',
            'contextlevel' => CONTEXT_MODULE,
            'userid' => $userid,
        ];
        $contextlist->add_from_sql($sql, $params);

        // Then add wooflash activities where the user has a grade.
        $sql = "SELECT c.id
                 FROM {context} c
           INNER JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
           INNER JOIN {modules} m ON m.id = cm.module AND m.name = :modname
           INNER JOIN {wooflash} w ON w.id = cm.instance
           INNER JOIN {wooflash_completion} wc ON wc.wooflashid = w.id
                WHERE wc.userid = :userid
        ";
        $params = [
            'modname' => 'wooflash',
            'contextlevel' => CONTEXT_MODULE,
            'userid' => $userid,
        ];
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {

        $context = $userlist->get_context();

        if (!$context instanceof \context_module) {
            return;
        }

        $params = [
            'instanceid' => $context->instanceid,
            'modulename' => 'wooflash',
        ];

        // Course authors.
        $sql = "SELECT w.authorid
              FROM {course_modules} cm
              JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
              JOIN {wooflash} w ON w.id = cm.instance
             WHERE cm.id = :instanceid";
        $userlist->add_from_sql('userid', $sql, $params);

        // Graded students.
        $sql = "SELECT wc.userid
              FROM {course_modules} cm
              JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
              JOIN {wooflash} f ON w.id = cm.instance
              JOIN {wooflash_completion} wc ON wc.id = w.id
             WHERE cm.id = :instanceid";
        $userlist->add_from_sql('userid', $sql, $params);

    }

    /**
     * Export personal data for the given approved_contextlist. User and context information is contained within the contextlist.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        $user = $contextlist->get_user();
        foreach ($contextlist as $context) {
            if ($context->contextlevel == CONTEXT_MODULE) {
                $params = [
                    'contextid' => $context->id,
                    'contextlevel' => CONTEXT_MODULE,
                    'modname' => 'wooflash',
                    'userid' => $user->id,
                ];

                // Export courses where the user is the author.

                $sql = "SELECT w.*
                          FROM {context} ctx
                    INNER JOIN {course_modules} cm
                            ON cm.id = ctx.instanceid
                           AND ctx.contextlevel = :contextlevel
                    INNER JOIN {modules} m
                            ON m.id = cm.module
                           AND m.name = :modname
                    INNER JOIN {wooflash} w
                            ON w.id = cm.instance
                         WHERE ctx.id = :contextid
                           AND w.authorid = :userid";
                if ($records = $DB->get_records_sql($sql, $params)) {
                    foreach ($records as $rec) {
                        writer::with_context($context)->export_data([], $rec);
                    }
                }

                // Export courses where the user has a grade.

                $sql = "SELECT wc.*
                          FROM {context} ctx
                    INNER JOIN {course_modules} cm
                            ON cm.id = ctx.instanceid
                           AND ctx.contextlevel = :contextlevel
                    INNER JOIN {modules} m
                            ON m.id = cm.module
                           AND m.name = :modname
                    INNER JOIN {wooflash_completion} wc
                            ON wc.wooflashid = cm.instance
                         WHERE ctx.id = :contextid
                           AND wc.userid = :userid";
                if ($records = $DB->get_records_sql($sql, $params)) {
                    foreach ($records as $rec) {
                        writer::with_context($context)->export_data([], $rec);
                    }
                }
            }
        }
        return;
    }
    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context the context to delete in.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        debugging('The Wooflash plugin does not currently support the deleting of user data. ', DEBUG_DEVELOPER);
    }
    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        debugging('The Wooflash plugin does not currently support the deleting of user data. ', DEBUG_DEVELOPER);
    }
    /**
     * Delete multiple users within a single context.
     *
     * @param   approved_userlist       $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        debugging('The Wooflash plugin does not currently support the deleting of user data. ', DEBUG_DEVELOPER);
    }

}
