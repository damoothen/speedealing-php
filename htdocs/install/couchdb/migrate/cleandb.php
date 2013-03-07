<?php

/* Copyright (C) 2012      Patrick Mary           <laube@hotmail.fr>
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
 * 	\file       htdocs/comm/serverprocess.php
 * 	\ingroup    commercial societe
 * 	\brief      load data to display
 * 	\version    $Id: serverprocess.php,v 1.6 2012/01/27 16:15:05 synry63 Exp $
 */
require_once("../../../main.inc.php");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */


$couchdb = clone $couch;

$flush = $_GET["flush"];
if ($flush) {
// reset old value
//$result = $couchdb->limit(50000)->getView('unlink','link');
	$result = $couchdb->limit(50000)->getView('unlink', 'target_id');
	$i = 0;

	if (count($result->rows) == 0) {
		print "Effacement terminé";
		exit;
	}

	foreach ($result->rows AS $aRow) {
		$obj[$i]->_id = $aRow->value->_id;
		$obj[$i]->_rev = $aRow->value->_rev;
		$i++;
	}

	try {
		$couchdb->deleteDocs($obj);
	} catch (Exception $e) {
		echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
		exit(1);
	}

	print "Effacement en cours";
	exit;
}

// Convert timestamps to ISO
$date = $_GET["date"];
if ($date) {
	$var = array("datec", "datep", "datef", "datevalid", "last_subcriptio_date", "date_commande",
		"date", "date_livraison", "date_lim_reglement", "fin_validite", "CreateDate",
		"LastConnection", "NewConnection", "birthday", "tms");

	$result = $couchdb->getAllDocs();
	$i = 0;

//print_r($result);

	if (count($result->rows) == 0) {
		print "Mise a jour terminé";
		exit;
	}

	foreach ($result->rows AS $aRow) {
		$found = false;
		$obj = $couchdb->getDoc($aRow->id);
		foreach ($var as $key) {
			if (!empty($obj->$key) && is_int($obj->$key)) {
				$obj->$key = date("c", $obj->$key);
				$found = true;
			}
		}
		if ($found) {
			$couchdb->storeDoc($obj);
		}
	}

	try {
//$couchdb->deleteDocs($obj);
	} catch (Exception $e) {
		echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
		exit(1);
	}

	print "Mise a jour en cours";
	exit;
}

// Transfert specific class to system database
$system = $_GET["system"];
if ($system) {
	$var = array("extrafields", "Dict", "system", "DolibarrModules", "UserGroup");
	$var_id = array("_design/Dict");

	$result = $couchdb->getAllDocs();
	$i = 0;

//print_r($result);

	$couch_temp = clone $couchdb;
	$couch_temp->useDatabase("system");

	$found = false;
	foreach ($result->rows AS $aRow) {
		$obj = $couchdb->getDoc($aRow->id);
		if (in_array($obj->class, $var, true) || in_array($obj->_id, $var_id, true)) {
			$obj_temp = clone $obj;
			$found = true;
			try {
				$obj_temp2 = $couch_temp->getDoc($aRow->id);
				$obj_temp->_rev = $obj_temp2->_rev;
			} catch (Exception $e) {
				unset($obj_temp->_rev); // Not exist create a new
			}

			$couch_temp->storeDoc($obj_temp);
			$couchdb->deleteDoc($obj);
		}
	}

	if (!$found) {
		print "Mise a jour terminé";
		exit;
	}

	print "Mise a jour en cours";
	exit;
}

// Modify id user example: user:admin to org.couchdb.user:admin and transfert user
$id_user = $_GET["user"];
if ($id_user) {
	$var = array("author", "userdone", "usertodo", "commercial_id");
	$var_user = array("Lastname", "Firstname", "email", "login", "Status", "class", "name",
		"PhonePro", "CreateDate", "type", "group");

	$result = $couchdb->getAllDocs();
	$i = 0;

//print_r($result);

	$couch_temp = clone $couchdb;
	$couch_temp->useDatabase("_users");

	$found = false;
	$modify = false;
	foreach ($result->rows AS $aRow) {
		$obj = $couchdb->getDoc($aRow->id);
		foreach ($var as $key) {
			if (isset($obj->$key) && !empty($obj->$key->id)) {
				$obj->$key->id = "org.couchdb.user:" . $obj->$key->name;

				$found = true;
				$modify = true;
			}
		}
		if ($obj->class == "User" && $obj->email != "admin@speedealing.com") {
			try {
				$obj_temp = $couch_temp->getDoc("org.couchdb.user:" . $obj->email);
				$couch_temp->deleteDoc($obj_temp);
			} catch (Exception $e) {
// User doesn't exist
			}

			$obj_temp->_id = "org.couchdb.user:" . $obj->name;
			unset($obj_temp->_rev);

			foreach ($var_user as $key) {
				if ($key == "group") {
					$obj_temp->roles = $obj->group;
				} elseif (!empty($obj->$key)) {
					$obj_temp->$key = $obj->$key;
				}
			}

			$couchdb->deleteDoc($obj); // Delete old user:xxxxx document
			$couch_temp->storeDoc($obj_temp); // Create new document user
		}
		if ($modify) {
			$couchdb->storeDoc($obj);
			$modify = false;
		}
	}

	if (!$found) {
		print "Mise a jour terminé";
		exit;
	}

	print "Mise a jour en cours";
	exit;
}
?>