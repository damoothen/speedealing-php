<?php

/* Copyright (C) 2012			Herve Prot	<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

if (!defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL', '1'); // Disables token renewal
if (!defined('NOREQUIREMENU'))
	define('NOREQUIREMENU', '1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (!defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX', '1');
if (!defined('NOREQUIRESOC'))
	define('NOREQUIRESOC', '1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require('../../main.inc.php');

$json = GETPOST('json', 'alpha');
$class = GETPOST('class', 'alpha');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (!empty($json) && !empty($class)) {

	dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

	$object = new $class($db);

	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array()
	);

	$result = $object->getView($json);

	//print_r($result);
	//exit;
	$iTotal = count($result->rows);
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;

	foreach ($result->rows AS $aRow) {
		unset($aRow->value->class);
		unset($aRow->value->_rev);
		$output["aaData"][] = $aRow->value;
		unset($aRow);
	}

	header('Content-type: application/json');
	echo json_encode($output);
	exit;
}
?>
