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
//

/**
 * This file contains en_utf8 translation of the Wooflash module
 *
 * @package mod_wooflash
 * @copyright  20018 CBlue sprl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['modulename'] = 'Wooflash';
$string['modulenameplural'] = 'Wooflash';
$string['modulename_help'] = 'Cette activité permet d\'intégrer la plateforme interactive Wooflash à Moodle';
$string['pluginname'] = 'Wooflash';
$string['pluginadministration'] = 'Administration Wooflash';
$string['wooflashname'] = 'Nom de l\'activité';
$string['wooflashintro'] = 'Description de l\'activité';
$string['modulenamepluralformatted'] = 'Liste des activités Wooflash';
$string['quiz'] = 'Importer un quiz Moodle';
$string['wooflashcourseid'] = 'Dupliquer un événement Wooflash';
$string['wooflashsettings'] = 'Paramètres';
$string['testconnection'] = 'Tester la Connexion';
$string['pingOK'] = 'Connexion établie avec Wooflash';
$string['pingNOTOK'] = 'La connexion n\'a pas pu être établie avec Wooflash. Veuillez vérifier les paramètres de configuration.';
$string['secretaccesskey'] = 'Clé API (secretAccessKey)';
$string['secretaccesskey-description'] = 'Clé secrète utilisée pour communiquer avec la plateforme Wooflash. Doit commencer par \'sk.\'.';
$string['accesskeyid'] = 'Identifiant de plateforme (accessKeyId)';
$string['accesskeyid-description'] = 'Clé d\'accès utilisée pour communiquer avec la plateforme Wooflash. Doit commencer par \'ak.\'.';
$string['baseurl'] = 'URL du webservice';
$string['baseurl-description'] = 'Sert uniquement au débogage ou au test. Ne modifiez cette valeur que si demandé par le support Wooflash.';
$string['nowooflash'] = 'Il n\'y a pas d\'instance Wooflash';
$string['gradeupdateok'] = 'Mise à jour de la note effectuée avec succès';
$string['gradeupdatefailed'] = 'La mise à jour de la note a échoué';
$string['customcompletion'] = 'Suivi d\'achèvement mis à jour uniquement par Wooflash';
$string['customcompletiongroup'] = 'Conditions de suivi d\'achèvement Wooflash';
$string['wooflashredirect'] = 'Vous allez être redirigé vers Wooflash. Si cela ne se fait pas automatiquement, cliquez sur ce lien afin de continuer.';

/* Capabilities */
$string['wooflash:view'] = 'Accéder à une activité Wooflash';
$string['wooflash:addinstance'] = 'Ajouter une activité Wooflash à un cours';

$string['privacy:metadata:wooflash_server'] = 'Nous échangeons certaines données utilisateur avec Wooflash pour mieux intégrer leurs services.';
$string['privacy:metadata:wooflash_server:userid'] = 'Votre identifiant Moodle est envoyé pour que vous puissiez accéder à vos données sur Wooflash.';

$string['error-nocourseid'] = 'Impossible de déterminer l\'identifiant du cours';
$string['error-auth-nosession'] = 'Session manquante lors de la connexion';
$string['error-callback-is-not-url'] = 'L\'URL de retour (callback) n\'est pas une URL valide';
$string['error-couldnotredirect'] = 'Impossible d\'effectuer la redirection';
$string['error-couldnotloadcourses'] = 'Impossible de charger les cours Wooflash';
$string['error-couldnotupdatereport'] = 'Impossible de mettre à jour le rapport';
$string['error-couldnotauth'] = 'Impossible d\'obtenir l\'utilisateur ou le cours durant l\'authentication';
$string['error-invalidtoken'] = 'La valeur du paramètre "token" est invalide';
$string['error-invalidjoinurl'] = 'L\'URL pour rejoindre le cours est invalide';
$string['error-missingparameters'] = 'Paramètres manquants';
