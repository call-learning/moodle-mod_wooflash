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

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot . '/mod/wooflash/lib.php';

/**
 * 'Test connection' button in the Settings page
 *
 */
class mod_wooflash_test_connection extends admin_setting_heading {
    /**
     * Returns an HTML string
     *
     * @param string $data
     * @param string $query
     * @return string Returns an HTML string
     */
    public function output_html($data, $query = '') {
        $isPingOk = wooflash_get_ping_status();

        if ($isPingOk) {
            return '<div role="alert" style="color: #306030; background-color: #def1de; padding: .75rem 1.25rem; margin-bottom: 1rem;">' .
            get_string('pingOK', 'wooflash') .
                '</div>';
        } else {
            return '<div role="alert" style="color: #712b29; background-color: #f7dddc; padding: .75rem 1.25rem; margin-bottom: 1rem;">' .
            get_string('pingNOTOK', 'wooflash') .
                '</div>';
        }
    }
}
