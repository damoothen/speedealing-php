<?php

/* Copyright (C) 2004		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004		Benoit Mortier			<benoit.mortier@opensides.be>
 * Copyright (C) 2004		Sebastien DiCintio		<sdicintio@ressource-toi.org>
 * Copyright (C) 2007-2012	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2007-2013	Regis Houssin			<regis.houssin@capnetworks.com>
 * Copyright (C) 2012-2013	Herve Prot				<herve.prot@symeos.com>
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

require_once DOL_DOCUMENT_ROOT . '/core/class/translatestandalone.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

// For couchdb
if (!class_exists('couch'))
	require DOL_DOCUMENT_ROOT . '/core/db/couchdb/lib/couch.php';
if (!class_exists('couchAdmin'))
	require DOL_DOCUMENT_ROOT . '/core/db/couchdb/lib/couchAdmin.php';
if (!class_exists('couchClient'))
	require DOL_DOCUMENT_ROOT . '/core/db/couchdb/lib/couchClient.php';
if (!class_exists('User'))
	require DOL_DOCUMENT_ROOT . '/user/class/user.class.php';


// Avoid warnings with strict mode E_STRICT
$conf = new stdClass(); // instantiate $conf explicitely
$conf->global = new stdClass();
$conf->file = new stdClass();
$conf->db = new stdClass();


// Correction PHP_SELF (ex pour apache via caudium) car PHP_SELF doit valoir URL relative
// et non path absolu.
if (isset($_SERVER["DOCUMENT_URI"]) && $_SERVER["DOCUMENT_URI"]) {
    $_SERVER["PHP_SELF"] = $_SERVER["DOCUMENT_URI"];
}

// Define vars
$conffiletoshowshort = "conf.php";
// Define localization of conf file
$conffile = DOL_DOCUMENT_ROOT . '/conf/conf.php';
$conffiletoshow = "conf/conf.php";

if (! defined('DONOTLOADCONF') && file_exists($conffile))
	include_once $conffile;	// Load conf file

// Define DOL_URL_ROOT
// If defined (Ie: Apache with Linux)
if (isset($_SERVER["SCRIPT_URI"])) {
	$main_url_root = $_SERVER["SCRIPT_URI"];
}
// If defined (Ie: Apache with Caudium)
elseif (isset($_SERVER["SERVER_URL"]) && isset($_SERVER["DOCUMENT_URI"])) {
	$main_url_root = $_SERVER["SERVER_URL"] . $_SERVER["DOCUMENT_URI"];
}
// If SCRIPT_URI, SERVER_URL, DOCUMENT_URI not defined (Ie: Apache 2.0.44 for Windows)
else {
	$scheme = 'http';
	if (!empty($_SERVER["HTTP_HOST"]))
		$serverport = $_SERVER["HTTP_HOST"];
	else
		$serverport = $_SERVER["SERVER_NAME"];
	$main_url_root = $scheme . "://" . $serverport . $_SERVER["SCRIPT_NAME"];
}
// Clean proposed URL
$main_url_root = preg_replace('/\/$/', '', $main_url_root);     // Remove the /
$main_url_root = preg_replace('/\/index\.php$/', '', $main_url_root);  // Remove the /index.php
$main_url_root = preg_replace('/\/phpinfo\.php$/', '', $main_url_root);   // Remove the /phpinfo.php
$main_url_root = preg_replace('/\/prerequisite\.php$/', '', $main_url_root);   // Remove the /prerequisite.php
$main_url_root = preg_replace('/\/ajax$/', '', $main_url_root);   // Remove the /ajax
$main_url_root = preg_replace('/\/install$/', '', $main_url_root);   // Remove the /install
$uri = preg_replace('/^http(s?):\/\//i', '', $main_url_root);	// $uri contains url without http*
define('MAIN_SERVER_NAME', $uri);
$suburi = strstr($uri, '/');       // $suburi contains url without domain
if ($suburi == '/')
    $suburi = '';   // If $suburi is /, it is now ''
define('DOL_URL_ROOT', $suburi);    // URL relative root ('', '/dolibarr', ...)

// Security check
$lockfile = DOL_DOCUMENT_ROOT . '/install/install.lock';
if (file_exists($lockfile)) {
	header("Location: " . DOL_URL_ROOT . "/");
	exit;
}

// $langs object
$langs = new TranslateStandalone(DOL_DOCUMENT_ROOT);
if (GETPOST('lang'))
    $langs->setDefaultLang(GETPOST('lang', 'alpha'));
else
    $langs->setDefaultLang('auto');

if (!empty($_SESSION['db_json_files'])) {
	$jsonfiles = $_SESSION['db_json_files'];
} else {
	// Get json files list
	$jsonfiles = array();
	// Get dict files
	$fileslist = dol_dir_list(DOL_DOCUMENT_ROOT . '/install/couchdb/json', 'files');
	foreach($fileslist as $file) {
			$jsonfiles[$file['name']] = $file['fullname'];
	}
	// Stock in session for best performance
	$_SESSION['db_json_files'] = $jsonfiles;
}

// Now we load forced value from install.forced.php file.
$useforcedwizard=false;
$forcedfile = DOL_DOCUMENT_ROOT . '/install/install.forced.php';
if (@file_exists($forcedfile)) {
	$useforcedwizard=true;
	include $forcedfile;
}


/**
 * Show HTML header of install pages
 *
 * @return	void
 */
function pHeader() {
    global $conf, $langs;
    global $jsonfiles;

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
 *  Save configuration file. No particular permissions are set by installer.
 *
 *  @return	void
 */
function write_conf_file() {
	global $conf, $langs;
	global $couchdb_host, $couchdb_port, $force_https;
	global $memcached_host, $memcached_port, $useforcedwizard, $force_install_urlrewrite;
	global $conffile, $conffiletoshowshort;

	$key = md5(uniqid(mt_rand(), TRUE)); // Generate random hash

	$fp = fopen("$conffile", "w");
	if ($fp) {
		clearstatcache();

		fputs($fp, '<?php' . "\n");
		fputs($fp, '//' . "\n");
		fputs($fp, '// File generated by Speedealing installer ' . "\n");
		fputs($fp, '//' . "\n");
		fputs($fp, '// Take a look at conf.php.example file for an example of ' . $conffiletoshowshort . ' file' . "\n");
		fputs($fp, '// and explanations for all possibles parameters.' . "\n");
		fputs($fp, '//' . "\n\n");

		/* Authentication */

		fputs($fp, '// Authentication (obsolete ?)');
		fputs($fp, "\n");

		fputs($fp, '$main_authentication=\'dolibarr\';');
		fputs($fp, "\n");

		fputs($fp, '// Authentication for backward compatibility');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_main_authentication=\'dolibarr\';');
		fputs($fp, "\n\n");

		/* CouchDB */

		fputs($fp, '// Couchdb settings');
		fputs($fp, "\n");

		fputs($fp, '$main_couchdb_host=\'' . str_replace("'", "\'", $couchdb_host) . '\';');
		fputs($fp, "\n");

		fputs($fp, '$main_couchdb_port=\'' . str_replace("'", "\'", $couchdb_port) . '\';');
		fputs($fp, "\n");

		fputs($fp, '// Couchdb settings for backward compatibility');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_main_couchdb_host=\'' . str_replace("'", "\'", $couchdb_host) . '\';');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_main_couchdb_port=\'' . str_replace("'", "\'", $couchdb_port) . '\';');
		fputs($fp, "\n\n");

		/* Memcached */

		if (!empty($memcached_host) && !empty($memcached_port)) {
			fputs($fp, '// Memcached settings');
			fputs($fp, "\n");

			fputs($fp, '$main_memcached_host=\'' . str_replace("'", "\'", $memcached_host) . '\';');
			fputs($fp, "\n");

			fputs($fp, '$main_memcached_port=\'' . str_replace("'", "\'", $memcached_port) . '\';');
			fputs($fp, "\n");

			fputs($fp, '// Memcached settings for backward compatibility');
			fputs($fp, "\n");

			fputs($fp, '$dolibarr_main_memcached_host=\'' . str_replace("'", "\'", $memcached_host) . '\';');
			fputs($fp, "\n");

			fputs($fp, '$dolibarr_main_memcached_port=\'' . str_replace("'", "\'", $memcached_port) . '\';');
			fputs($fp, "\n\n");
		}

		/* URL rewriting (multicompany) */

		if ($useforcedwizard && !empty($force_install_urlrewrite)) {
			fputs($fp, '// URL rewriting');
			fputs($fp, "\n");

			fputs($fp, '$main_urlrewrite=\'1\';');
			fputs($fp, "\n");

			fputs($fp, '// URL rewriting for backward compatibility');
			fputs($fp, "\n");

			fputs($fp, '$dolibarr_urlrewrite=\'1\';');
			fputs($fp, "\n\n");
		} else {
			fputs($fp, '// URL rewriting');
			fputs($fp, "\n");

			fputs($fp, '$main_urlrewrite=\'0\';');
			fputs($fp, "\n");

			fputs($fp, '// URL rewriting for backward compatibility');
			fputs($fp, "\n");

			fputs($fp, '$dolibarr_urlrewrite=\'0\';');
			fputs($fp, "\n\n");
		}

		/* Specific setting */

		fputs($fp, '// Specific settings');
		fputs($fp, "\n");

		fputs($fp, '$main_prod=\'0\';');
		fputs($fp, "\n");

		fputs($fp, '$main_nocsrfcheck=\'0\';');
		fputs($fp, "\n");

		fputs($fp, '$main_force_https=\'' . (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?1:0) . '\';');
		fputs($fp, "\n");

		fputs($fp, '$main_cookie_cryptkey=\'' . $key . '\';');
		fputs($fp, "\n");

		fputs($fp, '$mailing_limit_sendbyweb=\'0\';');
		fputs($fp, "\n");

		fputs($fp, '// Specific settings for backward compatibility');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_main_prod=\'0\';');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_nocsrfcheck=\'0\';');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_main_force_https=\'' . (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?1:0) . '\';');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_main_cookie_cryptkey=\'' . $key . '\';');
		fputs($fp, "\n");

		fputs($fp, '$dolibarr_mailing_limit_sendbyweb=\'0\';');
		fputs($fp, "\n");

		fputs($fp, '?>');
		fclose($fp);

		if (file_exists("$conffile"))
			return 1;
		else
			return -1;
	}
	else
		return -2;
}

/**
 *	Save lock file.
 *
 *  @return	void
 */
function write_lock_file() {
	global $lockfile;

	$force_install_lockinstall = 444;
	$fp = fopen($lockfile, "w");
	if ($fp) {
		fwrite($fp, "This is a lock file to prevent use of install pages (set with permission " . $force_install_lockinstall . ")");
		fclose($fp);
		@chmod($lockfile, octdec($force_install_lockinstall));
		if (file_exists("$lockfile"))
			return 1;
		else
			return -1;
	}
	else
		return -2;
}
?>