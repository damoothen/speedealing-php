<?php

/* Copyright (C) 2011-2012 Regis Houssin  <regis@dolibarr.fr>
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

/**
 *       \file       htdocs/core/ajax/saveinplace.php
 *       \brief      File to save field value
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
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';

$class = GETPOST('element_class', 'alpha');
$key = GETPOST('key', 'alpha');
$id = GETPOST('id', 'alpha');
$value = GETPOST('value', 'alpha');
$type = GETPOST('type', 'alpha', 2);

$field = GETPOST('field', 'alpha', 2);
$element = GETPOST('element', 'alpha', 2);
$table_element = GETPOST('table_element', 'alpha', 2);
$fk_element = GETPOST('fk_element', 'alpha', 2);

$key = substr($key, 8); // remove prefix editval_

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url ' . $_SERVER["PHP_SELF"] . '?' . $_SERVER["QUERY_STRING"] . ' -->' . "\n";
//print_r($_POST);
error_log(print_r($_POST, true));

if (!empty($key) && !empty($id) && !empty($class)) {
    dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

    $object = new $class($db);

    // Load langs files
    if (count($object->fk_extrafields->langs))
        foreach ($object->fk_extrafields->langs as $row)
            $langs->load($row);
    
    if($type=="select" && empty($value))
        $value=""; // remove 0

    if (isset($object->fk_extrafields->fields->$key->class) && $type == "select") {
        $class_tmp = $object->fk_extrafields->fields->$key->class;
        $old_value = $value;
        $value = new stdClass();
        dol_include_once("/" . strtolower($class_tmp) . "/class/" . strtolower($class_tmp) . ".class.php");
        $obj_tmp = new $class_tmp($db);
        try {
            $obj_tmp->load($old_value);
            $value->id = $obj_tmp->id;
            $value->name = $obj_tmp->name;
        } catch (Exception $e) {
            $value = new stdClass();
        }
    }

    if ($type == "date") {
        $res = setlocale(LC_TIME, 'fr_FR.UTF8', 'fra');
        $date = $value;
        $value = str_replace("/", '-', $value); // 01/12/2012 -> 01-12-2012
        $value = strtotime($value);
    }

    try {

        if (is_object($value) || is_array($value)) {
            $object->load($id);
            $object->$key = $value;
            $object->record();
        } else {
            $object->id = $id;
            $res = $object->set($key, $value);
        }

        if ($type == 'numeric')
            $value = price($value);
        elseif ($type == 'textarea')
            $value = dol_nl2br($value);

        error_log($object->print_fk_extrafields($key));
        if ($type == 'date')
            echo $date;
        else
            echo $object->print_fk_extrafields($key);
    } catch (Exception $exc) {
        error_log($exc->getMessage());
    }

    exit;
}
?>
