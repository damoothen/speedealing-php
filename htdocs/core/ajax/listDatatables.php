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
$bServerSide = GETPOST('bServerSide', 'int');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (!empty($json) && !empty($class)) {

	$result = dol_include_once("/" . $class . "/class/" . strtolower($class) . ".class.php");
	if (empty($result)) {
		dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php"); // Old version
	}

	$object = new $class($db);

	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array()
	);

	if ($bServerSide && $_GET['sSearch']) {
		$result = $object->getIndexedView($json, array('limit' => intval(empty($_GET['iDisplayLength']) ? $conf->view_limit : $_GET['iDisplayLength']),
			'q' => $_GET['sSearch'] . "*",
			'skip' => intval($_GET['iDisplayStart'])
				//'sort' => $_GET['mDataProp_'.$_GET['iSortCol_0']],
				//'stale'=> "ok"
				));
	} else {
		$result = $object->getView($json, array('limit' => intval(empty($_GET['iDisplayLength']) ? $conf->view_limit : $_GET['iDisplayLength']),
			'skip' => intval($_GET['iDisplayStart'])
				//'stale'=> "update_after"
				));
		dol_setcache("total_rows", $result->total_rows);
	}

	if (empty($result->total_rows))
		$bServerSide = 0;

	//print_r($result);
	//exit;
	$output["iTotalRecords"] = dol_getcache("total_rows");
	$output["iTotalDisplayRecords"] = $result->total_rows;

	if (isset($result->rows))
		foreach ($result->rows AS $aRow) {
			unset($aRow->value->class);
			unset($aRow->value->_rev);
			$output["aaData"][] = clone $aRow->value;
			unset($aRow);
		}
	//error_log(json_encode($result));
	//sorting
	if($bServerSide)
		$object->sortDatatable($output["aaData"], $_GET['mDataProp_'.$_GET['iSortCol_0']], $_GET['sSortDir_0']);

	header('Content-type: application/json');
	echo json_encode($output);
	exit;
}
?>
