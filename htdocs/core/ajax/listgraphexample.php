<?php

/* Copyright (C) 2012	Herve Prot		<herve.prot@symeos.com>
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
if (!defined('NOREQUIREHTML'))
	define('NOREQUIREHTML', '1');
if (!defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX', '1');
if (!defined('NOREQUIRESOC'))
	define('NOREQUIRESOC', '1');

require '../../main.inc.php';

$callback = GETPOST('callback', 'alpha');
$filename = GETPOST('filename', 'alpha');

top_httphead('json'); // true for json header format
//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (!empty($callback) && !empty($filename)) {

	$filepath = DOL_DOCUMENT_ROOT . "/man/example/" . $filename;
	$fp = fopen($filepath, "r");
	if ($fp) {
		$json = fread($fp, filesize($filepath));
	
		echo $callback."(\n";
		echo trim($json).");";
		
		fclose($fp);
	}
}
?>
