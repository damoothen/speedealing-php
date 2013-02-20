<?php

/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2013	Herve Prot		<herve.prot@symeos.com>
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
require_once(DOL_DOCUMENT_ROOT . "/useradmin/class/useradmin.class.php");

$json = GETPOST('json', 'alpha');
$sEcho = GETPOST('sEcho');

top_httphead('json');

if ($json == "list") {
	$object = new UserAdmin($db);

	$output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array()
	);

	try {
		$result = $object->getAllUsers(true);
		$admins = $object->getUserAdmins();
	} catch (Exception $exc) {
		print $exc->getMessage();
	}

	//print_r ($result);

	$iTotal = count($result);
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;
	$i = 0;
	foreach ($result as $aRow) {
		$name = substr($aRow->doc->_id, 17);
		if (isset($admins->$name))
			$aRow->doc->admin = true;
		else
			$aRow->doc->admin = false;
		$output["aaData"][] = $aRow->doc;
	}

	echo json_encode($output);
}
?>