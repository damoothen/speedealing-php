<?php

/* Copyright (C) 2013 Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2013 Herve Prot		<herve.prot@symeos.com>
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

$default_lang = GETPOST('lang', 'alpha');
$langs->setDefaultLang($default_lang);

$langs->load("install");
$langs->load("errors");

$action = GETPOST('action', 'alpha');
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
	$couchdb_host = GETPOST('couchdb_host', 'alpha');
	$couchdb_port = GETPOST('couchdb_port', 'int');
	$memcached_host = GETPOST('memcached_host', 'alpha');
	$memcached_port = GETPOST('memcached_port', 'int');
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
	$couchdb_user_sync = GETPOST('couchdb_user_sync', 'alpha');
	$couchdb_pass_sync = GETPOST('couchdb_pass_sync', 'alpha');
	// $main_couchdb_host
	// $main_couchdb_port

	sleep(1); // for test
	echo json_encode(array('status' => 'ok', 'value' => $langs->trans('UserSyncCreated')));

// Create system database
} else if ($action == 'create_system_database') {
	$couchdb_name = 'system';
	$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', $couchdb_name);

	if (!$couch->databaseExists()) {
		try {
			$couch->createDatabase();

			// Add role to the system database for security
			$admin = new couchAdmin($couch);
			$admin->addDatabaseReaderRole('speedealing');

			echo json_encode(array('status' => 'ok', 'value' => $langs->trans('SystemDatabaseCreated')));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
			error_log($e->getMessage());
		}
	} else {
		echo json_encode(array('status' => 'ok', 'value' => $langs->trans('WarningSystemDatabaseAlreadyExists'))); // system database already exists
	}

// Create database
} else if ($action == 'create_database') {
	$couchdb_name = GETPOST('couchdb_name', 'alpha');
	$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', $couchdb_name);

	if (!$couch->databaseExists()) {
		try {
			$couch->createDatabase();
			echo json_encode(array('status' => 'ok', 'value' => $langs->trans('DatabaseCreated')));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
			error_log($e->getMessage());
		}
	} else {
		echo json_encode(array('status' => 'ok', 'value' => $langs->trans('WarningDatabaseAlreadyExists'))); // database already exists
	}

// Populate system database
} else if ($action == 'populate_system_database') {
	$filename = GETPOST('filename', 'alpha');
	$filepath = GETPOST('filepath');

	$fp = fopen($filepath, "r");
	if ($fp) {
		$json = fread($fp, filesize($filepath));
		$obj = json_decode($json);
		unset($obj->_rev);
		if ($obj->_id == "const")
			unset($obj->MAIN_VERSION);

		$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', 'system');

		fclose($fp);

		try {
			$couch->storeDoc($obj);
			echo json_encode(array('status' => 'ok', 'value' => $filename));
		} catch (Exception $e) {
			error_log('File:' . $filename .' '. $e->getMessage());
			echo json_encode(array('status' => 'error', 'value' => 'File:' . $filename .' '. $e->getMessage()));
		}

	} else {
		error_log("file not found : " . $filepath);
		echo json_encode(array('status' => 'error', 'value' => $filepath));
	}

// Populate entity database
} else if ($action == 'populate_entity_database') {
	$filename = GETPOST('filename', 'alpha');
	$filepath = GETPOST('filepath');

	$fp = fopen($filepath, "r");
	if ($fp) {
		$json = fread($fp, filesize($filepath));
		$obj = json_decode($json);
		unset($obj->_rev);

		$couchdb_name = GETPOST('couchdb_name', 'alpha');
		$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', $couchdb_name);

		fclose($fp);

		try {
			$couch->storeDoc($obj);
			echo json_encode(array('status' => 'ok', 'value' => $filename));
		} catch (Exception $e) {
			error_log('File:' . $filename .' '. $e->getMessage());
			echo json_encode(array('status' => 'error', 'value' => 'File:' . $filename .' '. $e->getMessage()));
		}

	} else {
		error_log("file not found : " . $filepath);
		echo json_encode(array('status' => 'error', 'value' => $filepath));
	}

// Create superadmin
} else if ($action == 'create_admin') {

	$couchdb_name = GETPOST('couchdb_name', 'alpha');
	$couchdb_user_root = GETPOST('couchdb_user_root', 'alpha');
	$couchdb_pass_root = GETPOST('couchdb_pass_root', 'alpha');

	$couch = new couchClient($main_couchdb_host . ':' . $main_couchdb_port . '/', $couchdb_name);
	$admin = new couchAdmin($couch);

	try {
		// create a temporary admin user
		$admin->createAdmin("admin_install", "admin_install");
	} catch (Exception $e) {
		// already exist or protected couchdb server
	}

	$host = substr($main_couchdb_host, 7);

	try {
		$couch = new couchClient('http://admin_install:admin_install@' . $host . ':' . $main_couchdb_port . '/', $couchdb_name, array("cookie_auth" => TRUE));
	} catch (Exception $e) {
		error_log($e->getMessage());
		echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
		exit;
	}

	// create superadmin in system and _users databases
	$useradmin = new User();

	$found = false;
	try {
		$useradmin->load("user:" . trim($couchdb_user_root));
		$found = true;
	} catch (Exception $e) {
		// user not exit
	}

	if (!$found) {
		try {
			$useradmin->Lastname = "Admin";
			$useradmin->Firstname = "Admin";
			$useradmin->name = trim($couchdb_user_root);
			$useradmin->pass = trim($couchdb_pass_root);
			$useradmin->entity = $couchdb_name;
			$useradmin->admin = true;
			$useradmin->Status = 'ENABLE';

			$id = $useradmin->update("", 0, "add");
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
			exit;
		}
	}

	echo json_encode(array('status' => 'ok', 'value' => $langs->trans('AdminCreated')));

// Create first user
} else if ($action == 'create_user') {

	$couchdb_name = GETPOST('couchdb_name', 'alpha');
	$couchdb_user_firstname = GETPOST('couchdb_user_firstname', 'alpha');
	$couchdb_user_lastname = GETPOST('couchdb_user_lastname', 'alpha');
	$couchdb_user_login = GETPOST('couchdb_user_login', 'alpha');
	$couchdb_user_pass = GETPOST('couchdb_user_pass', 'alpha');

	$host = substr($main_couchdb_host, 7);

	try {
		$couch = new couchClient('http://admin_install:admin_install@' . $host . ':' . $main_couchdb_port . '/', $couchdb_name, array("cookie_auth" => TRUE));
	} catch (Exception $e) {
		error_log($e->getMessage());
		echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
		exit;
	}

	// create first user in system and _users databases
	$firstuser = new User();
	$found = false;
	try {
		$firstuser->load("user:" . trim($couchdb_user_login));
		$found = true;
	} catch (Exception $e) {
		// user not exit
	}

	if (!$found) {
		try {
			$firstuser->Lastname = trim($couchdb_user_lastname);
			$firstuser->Firstname = trim($couchdb_user_firstname);
			$firstuser->name = trim($couchdb_user_login);
			$firstuser->pass = trim($couchdb_user_pass);
			$firstuser->entity = $couchdb_name;
			$firstuser->admin = false;
			$firstuser->Status = 'DISABLE';

			$id = $firstuser->update("", 0, "add");
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
			exit;
		}
	}

	// Add fisrt user to the database for security database
	$admin = new couchAdmin($couch);
	$admin->addDatabaseReaderUser(trim($couchdb_user_login));

	// Add specific view in _users database
	$couch->useDatabase('_users');
	$filename = array(
			DOL_DOCUMENT_ROOT . "/user/json/_auth.view.json",
			DOL_DOCUMENT_ROOT . "/user/json/User.view.json"
	);

	foreach ($filename as $filepath) {
		$fp = fopen($filepath, "r");
		if ($fp) {
			$json = fread($fp, filesize($filepath));
			$obj = json_decode($json);
			unset($obj->_rev);
			try {
				$result = $couch->getDoc($obj->_id);
				$obj->_rev = $result->_rev;
			} catch (Exception $e) {
				// not exist
			}

			$couch->storeDoc($obj);
			fclose($fp);
		}
	}

	echo json_encode(array('status' => 'ok', 'value' => $langs->trans('UserCreated')));

// Create search engine user
} else if ($action == 'create_searchengine_user') {

	$couchdb_name = GETPOST('couchdb_name', 'alpha');
	$couchdb_searchengine_login = GETPOST('couchdb_searchengine_login', 'alpha');
	$couchdb_searchengine_pass = GETPOST('couchdb_searchengine_pass', 'alpha');

	$host = substr($main_couchdb_host, 7);

	try {
		$couch = new couchClient('http://admin_install:admin_install@' . $host . ':' . $main_couchdb_port . '/', $couchdb_name, array("cookie_auth" => TRUE));
	} catch (Exception $e) {
		error_log($e->getMessage());
		echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
		exit;
	}

	// create first user in system and _users databases
	$searchengine = new User();
	$found = false;
	try {
		$searchengine->load("user:" . trim($couchdb_searchengine_login));
		$found = true;
	} catch (Exception $e) {
		// user not exit
	}

	if (!$found) {
		try {
			$searchengine->Lastname = 'Search';
			$searchengine->Firstname = 'Engine';
			$searchengine->name = trim($couchdb_searchengine_login);
			$searchengine->pass = trim($couchdb_searchengine_pass);
			$searchengine->admin = false;
			$searchengine->hide = true;
			$searchengine->Status = 'DISABLE';

			$id = $searchengine->update("", 0, "add");
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
			exit;
		}
	}

	// Add search engine user to the database reader user
	$admin = new couchAdmin($couch);
	$admin->addDatabaseReaderUser(trim($couchdb_searchengine_login));

	echo json_encode(array('status' => 'ok', 'value' => $langs->trans('UserSearchEngineCreated')));

// Install is finished, we create the lock file
} else if ($action == 'lock_install') {
	$couchdb_name = GETPOST('couchdb_name', 'alpha');
	$host = substr($main_couchdb_host, 7);

	try {
		$couch = new couchClient('http://admin_install:admin_install@' . $host . ':' . $main_couchdb_port . '/', $couchdb_name, array("cookie_auth" => TRUE));
	} catch (Exception $e) {
		error_log($e->getMessage());
		echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
		exit;
	}

	$admin = new couchAdmin($couch);

	// Increase timeout in couchdb to 3600s
	$admin->setConfig("couch_httpd_auth", "timeout", "3600");

	//remove admin_install
	try {
		// delete temporary admin user
		$admin->deleteAdmin("admin_install");
	} catch (Exception $e) {
		echo json_encode(array('status' => 'error', 'value' => $e->getMessage()));
		exit;
	}

	$ret = write_lock_file();
	if ($ret > 0)
		echo json_encode(array('status' => 'ok', 'value' => $langs->trans('LockFileCreated')));
	else
		echo json_encode(array('status' => 'error', 'value' => $langs->trans('LockFileCouldNotBeCreated')));

	// destroy couchdb cookie
	setcookie('AuthSession', '', 1, '/');
}
?>