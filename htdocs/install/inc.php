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
/*
if (!defined('ADODB_PATH')) {
    $foundpath = DOL_DOCUMENT_ROOT . '/includes/adodbtime/';
    if (!is_dir($foundpath))
        $foundpath = '/usr/share/php/adodb/';
    define('ADODB_PATH', $foundpath);
}
*/

require_once DOL_DOCUMENT_ROOT . '/core/class/translatestandalone.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
//require_once ADODB_PATH . 'adodb-time.inc.php';

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


// Correction PHP_SELF (ex pour apache via caudium) car PHP_SELF doit valoir URL relative
// et non path absolu.
if (isset($_SERVER["DOCUMENT_URI"]) && $_SERVER["DOCUMENT_URI"]) {
    $_SERVER["PHP_SELF"] = $_SERVER["DOCUMENT_URI"];
}


// Define vars
$conffiletoshowshort = "conf.php";
// Define localization of conf file
$conffile = realpath(dirname(__FILE__)) . '/../conf/conf.php';
$conffiletoshow = "htdocs/conf/conf.php";

// Define DOL_URL_ROOT
// If defined (Ie: Apache with Linux)
if (isset($_SERVER["SCRIPT_URI"])) {
	$dolibarr_main_url_root = $_SERVER["SCRIPT_URI"];
}
// If defined (Ie: Apache with Caudium)
elseif (isset($_SERVER["SERVER_URL"]) && isset($_SERVER["DOCUMENT_URI"])) {
	$dolibarr_main_url_root = $_SERVER["SERVER_URL"] . $_SERVER["DOCUMENT_URI"];
}
// If SCRIPT_URI, SERVER_URL, DOCUMENT_URI not defined (Ie: Apache 2.0.44 for Windows)
else {
	$proto = 'http';
	if (!empty($_SERVER["HTTP_HOST"]))
		$serverport = $_SERVER["HTTP_HOST"];
	else
		$serverport = $_SERVER["SERVER_NAME"];
	$dolibarr_main_url_root = $proto . "://" . $serverport . $_SERVER["SCRIPT_NAME"];
}
// Clean proposed URL
$dolibarr_main_url_root = preg_replace('/\/$/', '', $dolibarr_main_url_root);     // Remove the /
$dolibarr_main_url_root = preg_replace('/\/index\.php$/', '', $dolibarr_main_url_root);  // Remove the /index.php
$dolibarr_main_url_root = preg_replace('/\/install$/', '', $dolibarr_main_url_root);   // Remove the /install
$uri = preg_replace('/^http(s?):\/\//i', '', $dolibarr_main_url_root);	// $uri contains url without http*
$suburi = strstr($uri, '/');       // $suburi contains url without domain
if ($suburi == '/')
    $suburi = '';   // If $suburi is /, it is now ''
define('DOL_URL_ROOT', $suburi);    // URL relative root ('', '/dolibarr', ...)

// Define array of document root directories
$conf->file->dol_document_root = array(DOL_DOCUMENT_ROOT);

// Security check
$lockfile = DOL_DOCUMENT_ROOT . '/install/install.lock';
if (file_exists($lockfile)) {
	header("Location: " . DOL_URL_ROOT . "/");
    exit;
}

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

?>