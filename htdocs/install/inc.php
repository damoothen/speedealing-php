<?php

/* Copyright (C) 2004		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004		Benoit Mortier			<benoit.mortier@opensides.be>
 * Copyright (C) 2004		Sebastien DiCintio		<sdicintio@ressource-toi.org>
 * Copyright (C) 2007-2012	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2007-2013	Regis Houssin			<regis.houssin@capnetworks.com>
 * Copyright (C) 2012		Herve Prot				<herve.prot@symeos.com>
 * Copyright (C) 2012		Marcos García
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// Just to define version DOL_VERSION
if (!defined('DOL_INC_FOR_VERSION_ERROR'))
    define('DOL_INC_FOR_VERSION_ERROR', '1');
require_once realpath(dirname(__FILE__)) . '/../filefunc.inc.php';


// Define DOL_DOCUMENT_ROOT and ADODB_PATH used for install/upgrade process
if (!defined('DOL_DOCUMENT_ROOT'))
    define('DOL_DOCUMENT_ROOT', realpath(dirname(__FILE__)) . '/..');
if (!defined('ADODB_PATH')) {
    $foundpath = DOL_DOCUMENT_ROOT . '/includes/adodbtime/';
    if (!is_dir($foundpath))
        $foundpath = '/usr/share/php/adodb/';
    define('ADODB_PATH', $foundpath);
}

require_once DOL_DOCUMENT_ROOT . '/core/class/translatestandalone.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
require_once ADODB_PATH . 'adodb-time.inc.php';

// For couchdb
if (!class_exists('couch'))
	require DOL_DOCUMENT_ROOT . '/core/db/couchdb/lib/couch.php';
if (!class_exists('couchClient'))
	require DOL_DOCUMENT_ROOT . '/core/db/couchdb/lib/couchClient.php';
if (!class_exists('nosqlDocument'))
	require DOL_DOCUMENT_ROOT . '/core/class/nosqlDocument.class.php';


// Avoid warnings with strict mode E_STRICT
$conf = new stdClass(); // instantiate $conf explicitely
$conf->global = new stdClass();
$conf->file = new stdClass();
$conf->db = new stdClass();
$conf->syslog = new stdClass();


// Correction PHP_SELF (ex pour apache via caudium) car PHP_SELF doit valoir URL relative
// et non path absolu.
if (isset($_SERVER["DOCUMENT_URI"]) && $_SERVER["DOCUMENT_URI"]) {
    $_SERVER["PHP_SELF"] = $_SERVER["DOCUMENT_URI"];
}


$includeconferror = '';


// Define vars
$conffiletoshowshort = "conf.php";
// Define localization of conf file
$conffile = realpath(dirname(__FILE__)) . '/../conf/conf.php';
$conffiletoshow = "htdocs/conf/conf.php";
// For debian/redhat like systems
//$conffile = "/etc/dolibarr/conf.php";
//$conffiletoshow = "/etc/dolibarr/conf.php";


define('DOL_CLASS_PATH', 'class/');                             // Filsystem path to class dir
define('DOL_DATA_ROOT', (isset($dolibarr_main_data_root) ? $dolibarr_main_data_root : ''));
define('DOL_MAIN_URL_ROOT', (isset($dolibarr_main_url_root) ? $dolibarr_main_url_root : ''));           // URL relative root
$uri = preg_replace('/^http(s?):\/\//i', '', constant('DOL_MAIN_URL_ROOT'));  // $uri contains url without http*
$suburi = strstr($uri, '/');       // $suburi contains url without domain
if ($suburi == '/')
    $suburi = '';   // If $suburi is /, it is now ''
define('DOL_URL_ROOT', $suburi);    // URL relative root ('', '/dolibarr', ...)


if (empty($conf->file->character_set_client))
    $conf->file->character_set_client = "UTF-8";
if (empty($conf->db->character_set))
    $conf->db->character_set = 'utf8';
if (empty($conf->db->dolibarr_main_db_encryption))
    $conf->db->dolibarr_main_db_encryption = 0;
if (empty($conf->db->dolibarr_main_db_cryptkey))
    $conf->db->dolibarr_main_db_cryptkey = '';
if (empty($conf->db->user))
    $conf->db->user = '';

// Define array of document root directories
$conf->file->dol_document_root = array(DOL_DOCUMENT_ROOT);

// Security check
if (preg_match('/install.lock/i', $_SERVER["SCRIPT_FILENAME"])) {
    print 'Install pages have been disabled for security reason (directory renamed with .lock suffix).';
    if (!empty($dolibarr_main_url_root)) {
        print 'Click on following link. ';
        print '<a href="' . $dolibarr_main_url_root . '/admin/index.php?mainmenu=home&leftmenu=setup' . (isset($_POST["login"]) ? '&username=' . urlencode($_POST["login"]) : '') . '">';
        print 'Click here to go to Dolibarr';
        print '</a>';
    }
    exit;
}
$lockfile = DOL_DATA_ROOT . '/install.lock';
if (constant('DOL_DATA_ROOT') && file_exists($lockfile)) {
    print 'Install pages have been disabled for security reason (by lock file install.lock into dolibarr root directory).<br>';
    if (!empty($dolibarr_main_url_root)) {
        print 'Click on following link. ';
        print 'If you always reach this page, you must remove install.lock file manually.<br>';
        print '<a href="' . $dolibarr_main_url_root . '/admin/index.php?mainmenu=home&leftmenu=setup' . (isset($_POST["login"]) ? '&username=' . urlencode($_POST["login"]) : '') . '">';
        print 'Click here to go to Dolibarr';
        print '</a>';
    } else {
        print 'If you always reach this page, you must remove install.lock file manually.<br>';
    }
    exit;
}


// Force usage of log file for install and upgrades
$conf->syslog->enabled = 1;
$conf->global->SYSLOG_LEVEL = constant('LOG_DEBUG');
if (!defined('SYSLOG_FILE_ON'))
    define('SYSLOG_FILE_ON', 1);
if (!defined('SYSLOG_FILE')) { // To avoid warning on systems with constant already defined
    if (@is_writable('/tmp'))
        define('SYSLOG_FILE', '/tmp/dolibarr_install.log');
    else if (!empty($_ENV["TMP"]) && @is_writable($_ENV["TMP"]))
        define('SYSLOG_FILE', $_ENV["TMP"] . '/dolibarr_install.log');
    else if (!empty($_ENV["TEMP"]) && @is_writable($_ENV["TEMP"]))
        define('SYSLOG_FILE', $_ENV["TEMP"] . '/dolibarr_install.log');
    else if (@is_writable('../../../../') && @file_exists('../../../../startdoliwamp.bat'))
        define('SYSLOG_FILE', '../../../../dolibarr_install.log'); // For DoliWamp
    else if (@is_writable('../../'))
        define('SYSLOG_FILE', '../../dolibarr_install.log');    // For others

//print 'SYSLOG_FILE='.SYSLOG_FILE;exit;
}
if (!defined('SYSLOG_FILE_NO_ERROR'))
    define('SYSLOG_FILE_NO_ERROR', 1);

// Defini objet langs
$langs = new TranslateStandalone(realpath(dirname(__FILE__)) . '/..');
if (GETPOST('lang'))
    $langs->setDefaultLang(GETPOST('lang'));
else
    $langs->setDefaultLang('auto');


/**
 * Show HTML header of install pages
 *
 * @return	void
 */
function pHeader() {
    global $conf, $langs;

    $langs->load("main");
    $langs->load("admin");

    include 'tpl/header.tpl.php';
}

/**
 * Print HTML footer of install pages
 *
 * @return	void
 */
function pFooter() {
	global $langs;

	include 'tpl/footer.tpl.php';
}

/**
 * Log function for install pages
 *
 * @param	string	$message	Message
 * @param 	int		$level		Level of log
 * @return	void
 */
function dolibarr_install_syslog($message, $level = LOG_DEBUG) {
    if (!defined('LOG_DEBUG'))
        define('LOG_DEBUG', 6);
    dol_syslog($message, $level);
}

?>