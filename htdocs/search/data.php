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

require('../main.inc.php');
require_once(DOL_DOCUMENT_ROOT . "/search/class/search.class.php");

$object = new Search($db);

$sParam = $_GET['q'];
if (!$sParam)
	exit;

$object->loadDatabase('societe');
$result = $object->getIndexedView("list", array('limit' => 6,
	'q' => $sParam . "*"
		));

//print_r($result);

if ($result->total_rows <= 0)
	exit;

if (isset($result->rows))
	foreach ($result->rows AS $aRow) {
		foreach ($aRow->value as $key => $aCol) {
			if ($key !="_id" && $key != "_rev" && is_string($aCol) && strpos(strtolower($aCol), strtolower($sParam)) !== false)
				echo $aCol . "\n";
		}
	}
?>