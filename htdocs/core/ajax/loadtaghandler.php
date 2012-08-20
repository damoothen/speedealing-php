<?php
/* Copyright (C) 2012				Herve Prot  <herve.prot@symeos.com>
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

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require('../../main.inc.php');

$id = GETPOST('id','alpha');
$class = GETPOST('class','alpha');
$key = "Tag";
/*
 * View
 */
/*$error = var_export($_GET,true);

error_log($error);*/

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (! empty($id) && ! empty($class))
{	
	$res=dol_include_once("/".$class."/class/".strtolower($class).".class.php");
	if(!$res) // old dolibarr
		dol_include_once("/".strtolower($class)."/class/".strtolower($class).".class.php");
	
	$langs->load("companies");
	$langs->load("members");
	
	$return=array();
	
	$object = new $class($db);
	$object->load($id);
	
	$return = new stdClass();
	
	if(!empty($object->$key))
		$return->assignedTags = $object->$key;
	else
		$return->assignedTags = array();
		
	$return->availableTags = array();
	
	$result = $object->getView("tag",array("group"=>true));
	if(count($result->rows))
		foreach($result->rows as $aRow) {
			$return->availableTags[] = $aRow->key;
		}
		
	echo json_encode($return);
}

?>
