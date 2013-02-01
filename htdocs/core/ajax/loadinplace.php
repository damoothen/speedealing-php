<?php

/* Copyright (C) 2011-2012 Regis Houssin  <regis.houssin@capnetworks.com>
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
 *       \file       htdocs/core/ajax/loadinplace.php
 *       \brief      File to load field value
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

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';

$key = GETPOST('key', 'alpha');
$class = GETPOST('element_class', 'alpha');
$id = GETPOST('id', 'alpha');
$type = GETPOST('type', 'alpha');

$field = GETPOST('field', 'alpha');
$element = GETPOST('element', 'alpha');
$table_element = GETPOST('table_element', 'alpha');
$fk_element = GETPOST('fk_element', 'alpha');

$key = substr($key, 8); // remove prefix editval_

/*
 * View
 */

top_httphead();

error_log(print_r($_GET, true));

if (!empty($key) && !empty($class) && !empty($id)) {
    dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

    $return = array();

    $object = new $class($db);
    $object->load($id);

    // Load langs files
    if (count($object->fk_extrafields->langs))
        foreach ($object->fk_extrafields->langs as $row)
            $langs->load($row);

    if ($type == "select") {
        if (!empty($object->$key))
            $return['selected'] = $object->$key;
        else
            $return['selected'] = $object->fk_extrafields->fields->$key->default;
    }

    $aRow = $object->fk_extrafields->fields->$key;
    if (isset($aRow->view)) { // Is a view
        if (isset($aRow->class)) { // Is another class
            $class_obj = $aRow->class;
            dol_include_once("/" . strtolower($class_obj) . "/class/" . strtolower($class_obj) . ".class.php");
            $object_tmp = new $class_obj($db);
        }

        $params = array();
        if (count($aRow->params))
            foreach ($aRow->params as $idx => $row) {
                eval("\$row = $row;");
                if (!empty($row))
                    $params[$idx] = $row;
            }
        try {
            if (isset($aRow->class)) // Is view is an other class
                $result = $object_tmp->getView($aRow->view, $params);
            else
                $result = $object->getView($aRow->view, $params);
        } catch (Exception $e) {
            $error = "Fetch : Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
            dol_print_error($db, $error);
            return 0;
        }

        if ($type == "select") {
            $aRow->values[0]->label = "";
            $aRow->values[0]->enable = true;
        }

        foreach ($result->rows as $row) {
            if (empty($aRow->getkey)) {
                $aRow->values[$row->value->_id]->label = $row->value->name;
                $aRow->values[$row->value->_id]->enable = true;
            } else { // For getkey
                $aRow->values[$row->key]->label = $row->key;
                $aRow->values[$row->key]->enable = true;
            }
        }

        if ($type == "select") {
            $return['selected'] = $object->$key->id; // Index of key
        }
    }

    // Value for select or autocomplete
    if (isset($object->fk_extrafields->fields->$key->values)) {
        foreach ($object->fk_extrafields->fields->$key->values as $keys => $aRow) {
            if ($aRow->enable) {
                if ($type == "select") {
                    if (isset($aRow->label))
                        $return[$keys] = $langs->trans($aRow->label);
                    else
                        $return[$keys] = $langs->trans($keys);
                    
                    //if(!empty($return[$keys]) && $object->fk_extrafields->fields->$key->class != "User")
                    //    $return[$keys] = $keys."-".$return[$keys];
                    
                } else { //autocomplete
                    echo $keys . ";";
                }
            }
        }
    }

    //if ($type == "select")
    echo json_encode($return);
}
?>
