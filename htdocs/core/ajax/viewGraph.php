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
    $langs->load("companies");

    $params=array('group'=>true);
    $result = $object->getView($json, $params);

    //print_r($result);
    
    foreach ($result->rows as $aRow) {
        if(isset($_GET["attr"])) {
            $attr = $_GET["attr"];
            $key=$aRow->key;
            $label = $object->fk_extrafields->fields->$attr->values->$key->label;
            if(!empty($label))
                $output[] = array($label, $aRow->value);
            else
                $output[] = array($langs->trans($aRow->key), $aRow->value);
        }
        else
            $output[] = array($langs->trans($aRow->key), $aRow->value);
    }

    header('Content-type: application/json');
    echo $_GET["callback"].'('.json_encode($output).');';
    exit;
}
?>
