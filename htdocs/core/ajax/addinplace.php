<?php

/* Copyright (C) 2011-2012 Regis Houssin    <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot       <herve.prot@symeos.com>
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

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';

$json = GETPOST('json', 'alpha');
$class = GETPOST('class', 'alpha');
//$id = GETPOST('id', 'alpha');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";
//print_r($_POST);
error_log(print_r($_GET, true));
error_log(print_r($_POST, true));

if (!empty($json) && !empty($class)) {
    dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

    $object = new $class($db);
    $obj = new stdClass();

    if ($json == "add") {

        foreach ($object->fk_extrafields->fields as $key => $row) {
            if ($row->enable) {
                if (isset($row->class)) {
                    $class_tmp = $row->class;
                    dol_include_once("/" . strtolower($class_tmp) . "/class/" . strtolower($class_tmp) . ".class.php");
                    $object_tmp = new $class_tmp($db);

                    $object->$key = new stdClass();
                    $obj->$key = new stdClass();

                    if (!empty($_POST[$key])) {
                        $object_tmp->fetch($_POST[$key]);
                        $object->$key->id = $object_tmp->id;
                        $object->$key->name = $object_tmp->name;

                        $obj->$key->id = $object_tmp->id;
                        $obj->$key->name = $object_tmp->name;
                    }
                } else {
                    if (!empty($_POST[$key])) {
                        $object->$key = $_POST[$key];
                        $obj->$key = $_POST[$key];
                    } else {
                        $object->$key = $row->default;
                        $obj->$key = $row->default;
                    }
                }
            }
        }
        
        if (method_exists($object, 'addInPlace'))
                $object->addInPlace($obj);

        try {
            $res = $object->record();
            $obj->_id = $res->id;
        } catch (Exception $exc) {
            error_log($exc->getMessage());
            exit;
        }
    }
}

//error_log(json_encode($res));
echo json_encode($obj);
?>
