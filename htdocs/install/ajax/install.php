<?php
/* Copyright (C) 2013 Regis Houssin  <regis.houssin@capnetworks.com>
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

/**
 *       \file       htdocs/install/ajax/install.php
 *       \brief      File to get all status for install process
 */

require '../inc.php';

$default_lang = GETPOST('lang','alpha');
$langs->setDefaultLang($default_lang);

$langs->load("install");
$langs->load("errors");

$action	= GETPOST('action','alpha');

$out = array();

/*
 * View
 */

// This variable are loaded by inc.php
// $dolibarr_main_couchdb_name
// $dolibarr_main_couchdb_host
// $dolibarr_main_couchdb_port

if ($action == 'create_config') {
	$couchdb_name	= GETPOST('couchdb_name', 'alpha');
	$couchdb_host	= GETPOST('couchdb_host', 'alpha');
	$couchdb_port	= GETPOST('couchdb_port', 'int');
	$memcached_host	= GETPOST('memcached_host', 'alpha');
	$memcached_port	= GETPOST('memcached_port', 'int');
	// Save old conf file on disk
	if (file_exists("$conffile")) {
		// We must ignore errors as an existing old file may already exists and not be replacable or
		// the installer (like for ubuntu) may not have permission to create another file than conf.php.
		// Also no other process must be able to read file or we expose the new file, so content with password.
		@dol_copy($conffile, $conffile . '.old', '0600');
	}
	echo write_conf_file();
} else if ($action == 'create_admin') {
	$couchdb_user_root	= GETPOST('couchdb_user_root', 'alpha');
	$couchdb_pass_root	= GETPOST('couchdb_pass_root', 'alpha');
	// $dolibarr_main_couchdb_name
	// $dolibarr_main_couchdb_host
	// $dolibarr_main_couchdb_port

	// Create superadmin

	echo 1;
} else if ($action == 'create_user') {
	$couchdb_user_firstname	= GETPOST('couchdb_user_firstname', 'alpha');
	$couchdb_user_lastname	= GETPOST('couchdb_user_lastname', 'alpha');
	$couchdb_user_pseudo	= GETPOST('couchdb_user_pseudo', 'alpha');
	$couchdb_user_email		= GETPOST('couchdb_user_email', 'alpha');
	$couchdb_user_pass		= GETPOST('couchdb_user_pass', 'alpha');
	// $dolibarr_main_couchdb_name
	// $dolibarr_main_couchdb_host
	// $dolibarr_main_couchdb_port

	// Create first user

	echo 1;
} else if ($action == 'create_syncuser') {
	$couchdb_user_sync	= GETPOST('couchdb_user_sync', 'alpha');
	$couchdb_pass_sync	= GETPOST('couchdb_pass_sync', 'alpha');
	// $dolibarr_main_couchdb_name
	// $dolibarr_main_couchdb_host
	// $dolibarr_main_couchdb_port

	// Create first user

	echo 1;
} else if ($action == 'create_database') {
	// $dolibarr_main_couchdb_name
	// $dolibarr_main_couchdb_host
	// $dolibarr_main_couchdb_port

	// Create database

	echo 1;
} else if ($action == 'populate_database') {
	$filename	= GETPOST('filename', 'alpha');
	$filepath	= GETPOST('filepath');
	// $dolibarr_main_couchdb_name
	// $dolibarr_main_couchdb_host
	// $dolibarr_main_couchdb_port

	// Create database
	sleep(1); // for test

	echo 1;
}
?>