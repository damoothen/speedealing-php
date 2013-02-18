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

header('Content-type: application/json');

// This variable are loaded by inc.php
// $main_couchdb_host
// $main_couchdb_port

// Create config file
if ($action == 'create_config') {
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
	$ret = write_conf_file();
	if ($ret > 0)
		echo json_encode(array('status' => 'ok', 'value' => $langs->trans('ConfFileCreated')));
	else
		echo json_encode(array('status' => 'error', 'value' => $langs->trans('ConfFileIsNotWritable', $conffile)));

// Create sync user
} else if ($action == 'create_syncuser') {
	$couchdb_user_sync	= GETPOST('couchdb_user_sync', 'alpha');
	$couchdb_pass_sync	= GETPOST('couchdb_pass_sync', 'alpha');
	// $main_couchdb_host
	// $main_couchdb_port

	sleep(1); // for test
	echo json_encode(array('status' => 'ok'));

// Create database
} else if ($action == 'create_database') {
	$couchdb_name	= GETPOST('couchdb_name', 'alpha');

	$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', $couchdb_name);

	if (!$couch->databaseExists()) {
		try {
			$couch->createDatabase();
			echo json_encode(array('status' => 'ok', 'value' => $langs->trans('DatabaseCreated')));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error'));
		}
	} else {
		echo json_encode(array('status' => 'ok', 'value' => $langs->trans('DatabaseAlreadyExists'))); // database already exists
	}

// Populate database
} else if ($action == 'populate_database') {
	$filename	= GETPOST('filename', 'alpha');
	$filepath	= GETPOST('filepath');
	// $main_couchdb_host
	// $main_couchdb_port

	sleep(1); // for test
	echo json_encode(array('status' => 'ok'));

// Create superadmin
} else if ($action == 'create_admin') {
	$couchdb_name		= GETPOST('couchdb_name', 'alpha');
	$couchdb_user_root	= GETPOST('couchdb_user_root', 'alpha');
	$couchdb_pass_root	= GETPOST('couchdb_pass_root', 'alpha');

	$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', $couchdb_name);
	$admin = new couchAdmin($couch);

	try {
		$admin->createAdmin($couchdb_user_root, $couchdb_pass_root);
		//$admin->addDatabaseAdminUser($couchdb_user_root);

		$user = $admin->getUser($couchdb_user_root);

		$user->Status			= 'ENABLE';
		$user->entity			= $couchdb_name;
		$user->admin			= true;
		$user->superadmin		= true;
		print_r($user); exit;
		$admin->client->storeDoc($user);

		echo json_encode(array('status' => 'ok'));

	} catch (Exception $e) {
		echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
	}

// Create first user
} else if ($action == 'create_user') {
	$couchdb_user_firstname	= GETPOST('couchdb_user_firstname', 'alpha');
	$couchdb_user_lastname	= GETPOST('couchdb_user_lastname', 'alpha');
	$couchdb_user_pseudo	= GETPOST('couchdb_user_pseudo', 'alpha');
	$couchdb_user_email		= GETPOST('couchdb_user_email', 'alpha');
	$couchdb_user_pass		= GETPOST('couchdb_user_pass', 'alpha');
	// $main_couchdb_host
	// $main_couchdb_port

	sleep(1); // for test
	echo json_encode(array('status' => 'ok'));

// Install is finished, we create the lock file
} else if ($action == 'lock_install') {
	//$ret = write_lock_file();
	$ret = 1; // TODO for debug
	if ($ret > 0)
		echo json_encode(array('status' => 'ok', 'value' => $langs->trans('LockFileCreated')));
	else
		echo json_encode(array('status' => 'error', 'value' => $langs->trans('LockFileCouldNotBeCreated')));
}
?>