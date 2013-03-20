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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *       \file       core/ajax/core.php
 *       \brief      File to return core Ajax response
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');

include '../../main.inc.php';

$action	= GETPOST('action', 'alpha');
$string	= GETPOST('string', 'alpha');
$class	= GETPOST('element', 'alpha');

if (!empty($action) && !empty($string)) {
	if ($action == 'getTrans') {
		echo $langs->trans($string);
	} else if ($action == 'getImage') {
		$imgMethod = 'img_'.$string;
		if (function_exists($imgMethod))
			echo call_user_func($imgMethod);
	} else if ($action == 'getNameUrl' && !empty($class)) {
		$withpicto = (GETPOST('option', 'int') ? GETPOST('option', 'int') : 0);
		$result = dol_include_once("/" . $class . "/class/" . strtolower($class) . ".class.php", $class);
		if (empty($result)) {
			dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php", $class); // Old version
		}
		$object = new $class();
		$res = $object->fetch($string);
		echo $object->getNomUrl($withpicto);
	} else if ($action == 'getTrash') {
		dol_include_once('/trash/class/trash.class.php', 'trash');
		$object = new Trash();
		if ($string == 'count') {
			$result = $object->getView($string);
			echo (!empty($result->rows) ? $result->rows[0]->value : false);
		}
	}
}
?>