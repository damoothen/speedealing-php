<?php

/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 *       \file       htdocs/user/ajax/list.php
 *       \brief      File to return Ajax response for user list
 */
if (!defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL', '1'); // Disables token renewal
if (!defined('NOREQUIREMENU'))
	define('NOREQUIREMENU', '1');
if (!defined('NOREQUIREHTML'))
	define('NOREQUIREHTML', '1');
if (!defined('NOREQUIRESOC'))
	define('NOREQUIRESOC', '1');
if (!defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX', '1');
if (!defined('NOREQUIRETRAN'))
	define('NOREQUIRETRAN', '1');

include '../../main.inc.php';

$json = GETPOST('json', 'alpha');
$sEcho = GETPOST('sEcho');

top_httphead('json');

if ($json == "list") {
	$object = new User($db);

	$output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array()
	);

	$user_in = array();
	$var_exclude_db = array("_users", "_replicator", "mips", "system");

	$listEntity = new stdClass();

	try {
		$result = $object->getView('listAll');
		$result_all = $object->getAllUsers(true);
		$admins = $object->getUserAdmins();

		$list_db = array_diff($couch->listDatabases(), $var_exclude_db);
		//$admins = $object->getDatabaseAdminUsers();
		//$enabled = $object->getDatabaseReaderUsers();
		foreach ($list_db as $db) {
			$object->useDatabase($db);
			$listEntity->$db = $object->getDatabaseReaderUsers();
		}
	} catch (Exception $exc) {
		print $exc->getMessage();
	}

	//print_r($result_all);

	if (!empty($result->rows)) {
		foreach ($result->rows as $aRow) {
			$name = substr($aRow->value->_id, 5);
			if (isset($admins->$name))
				$aRow->value->admin = true;
			else
				$aRow->value->admin = false;

			$aRow->value->entityList = array();
			foreach ($list_db as $db) {
				if (in_array($name, $listEntity->$db, true))
					$aRow->value->entityList[] = $db;
			}

			$output["aaData"][] = $aRow->value;
			$user_in[] = $name;
		}
	}

	if ($result_all) {
		foreach ($result_all as $aRow) {
			$name = substr($aRow->doc->_id, 17);

			if (in_array($name, $user_in))
				continue;

			if (isset($admins->$name))
				$aRow->doc->admin = true;
			else
				$aRow->doc->admin = false;

			$aRow->doc->entityList = array();
			foreach ($list_db as $db) {
				if (in_array($name, $listEntity->$db, true))
					$aRow->doc->entityList[] = $db;
			}

			$output["aaData"][] = $aRow->doc;
		}
	}

	$iTotal = count($output["aaData"]);
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;

	echo json_encode($output);
}
?>