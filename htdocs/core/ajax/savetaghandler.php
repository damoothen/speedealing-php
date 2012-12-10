<?php

/* Copyright (C) 2011-2012 Regis Houssin  <regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

require('../../main.inc.php');

$key = GETPOST('key', 'alpha');
$class = GETPOST('element_class', 'alpha');
$id = GETPOST('id', 'alpha');
$value = $_POST['tags'];

$key = substr($key, 8); // remove prefix editval_

/*
 * View
 */


/*$error = var_export($_POST,true);
error_log($error);*/

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";
//print_r($_POST);

if (!empty($key) && !empty($id) && !empty($class)) {
	$res = dol_include_once("/" . $class . "/class/" . strtolower($class) . ".class.php");
	if (!$res) // old dolibarr
		dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

	$object = new $class($db);

	try {
		$object->id = $id;
		$object->load($id);
		$object->$key = $value;
		$object->record();
		
		$return=new stdClass();
		$return->status = "ok";
		
	} catch (Exception $exc) {
		error_log($exc->getMessage());
                $return=new stdClass();
		$return->status = "not found";
	}
	
	echo json_encode($return);
}
?>
