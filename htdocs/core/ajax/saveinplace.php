<?php

/* Copyright (C) 2011-2012 Regis Houssin  <regis.houssin@capnetworks.com>
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
        echo $object->print_fk_extrafields($key);
    } catch (Exception $exc) {
        error_log($exc->getMessage());
    }

    exit;
}
?>
