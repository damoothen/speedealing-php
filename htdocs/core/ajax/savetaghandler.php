<?php

/* Copyright (C) 2011-2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2013	Herve Prot		<herve.prot@symeos.com>
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

if (!defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL', '1'); // Disables token renewal
if (!defined('NOREQUIREMENU'))
	define('NOREQUIREMENU', '1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (!defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX', '1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require '../../main.inc.php';

$id			= GETPOST('id', 'alpha');
$key		= substr(GETPOST('key', 'alpha'), 8);	// remove prefix editval_
$classname	= GETPOST('element_class', 'alpha');	// use classname instead class for avoid conflict with php keyword class
$value		= $_POST['tags'];

/*
 * View
 */


/*$error = var_export($_POST,true);
 error_log($error);*/

top_httphead('json');

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";
//print_r($_POST);

if (!empty($key) && !empty($id) && !empty($classname)) {
	$res = dol_include_once("/" . $classname . "/class/" . strtolower($classname) . ".class.php", $classname);
	if (!$res) // old dolibarr
		dol_include_once("/" . strtolower($classname) . "/class/" . strtolower($classname) . ".class.php", $classname);

	$object = new $classname($db);

	try {
		$object->id = $id;
		$object->load($id);
		$object->$key = $value;
		$object->record();

		$return=new stdClass();
		$return->status = "ok";

	} catch (Exception $e) {
		error_log($e->getMessage());
		$return=new stdClass();
		$return->status = "error";
		$return->value = $e->getMessage();
	}

	echo json_encode($return);
}
?>
