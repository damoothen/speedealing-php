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
$id = GETPOST('id', 'alpha');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";
//print_r($_POST);
//error_log(print_r($_GET, true));

if (!empty($json) && !empty($id) && !empty($class)) {
    dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

    $object = new $class($db);

    if ($json == "delete") {
        try {
            $object->load($id);
            
            if (method_exists($object, 'deleteInPlace'))
                    $object->deleteInPlace();
            
            $res = $object->deleteDoc();
            exit;
        } catch (Exception $exc) {
            error_log($exc->getMessage());
            exit;
        }
    }
}

// Load original field value
else if (!empty($field) && !empty($element) && !empty($table_element) && !empty($fk_element)) {
    $ext_element = GETPOST('ext_element', 'alpha', 2);
    $field = substr($field, 8); // remove prefix val_
    $type = GETPOST('type', 'alpha', 2);
    $value = ($type == 'ckeditor' ? GETPOST('value', '', 2) : GETPOST('value', 'alpha', 2));
    $loadmethod = GETPOST('loadmethod', 'alpha', 2);
    $savemethod = GETPOST('savemethod', 'alpha', 2);
    $savemethodname = (!empty($savemethod) ? $savemethod : 'setValueFrom');

    $view = '';
    $format = 'text';
    $return = array();
    $error = 0;

    if ($element != 'order_supplier' && $element != 'invoice_supplier' && preg_match('/^([^_]+)_([^_]+)/i', $element, $regs)) {
        $element = $regs[1];
        $subelement = $regs[2];
    }

    if ($element == 'propal')
        $element = 'propale';
    else if ($element == 'fichinter')
        $element = 'ficheinter';
    else if ($element == 'product')
        $element = 'produit';
    else if ($element == 'member')
        $element = 'adherent';
    else if ($element == 'order_supplier') {
        $element = 'fournisseur';
        $subelement = 'commande';
    } else if ($element == 'invoice_supplier') {
        $element = 'fournisseur';
        $subelement = 'facture';
    }

    if (!empty($user->rights->$element->creer) || !empty($user->rights->$element->write)
            || (isset($subelement) && (!empty($user->rights->$element->$subelement->creer) || !empty($user->rights->$element->$subelement->write)))
            || ($element == 'payment' && $user->rights->facture->paiement)
            || ($element == 'payment_supplier' && $user->rights->fournisseur->facture->creer)) {
        // Clean parameters
        $newvalue = trim($value);

        if ($type == 'numeric') {
            $newvalue = price2num($newvalue);

            // Check parameters
            if (!is_numeric($newvalue)) {
                $error++;
                $return['error'] = $langs->trans('ErrorBadValue');
            }
        } else if ($type == 'datepicker') {
            $timestamp = GETPOST('timestamp', 'int', 2);
            $format = 'date';
            $newvalue = ($timestamp / 1000);
        } else if ($type == 'select') {
            $loadmethodname = 'load_cache_' . $loadmethod;
            $loadcachename = 'cache_' . $loadmethod;
            $loadviewname = 'view_' . $loadmethod;

            $form = new Form($db);
            if (method_exists($form, $loadmethodname)) {
                $ret = $form->$loadmethodname();
                if ($ret > 0) {
                    $loadcache = $form->$loadcachename;
                    $value = $loadcache[$newvalue];

                    if (!empty($form->$loadviewname)) {
                        $loadview = $form->$loadviewname;
                        $view = $loadview[$newvalue];
                    }
                } else {
                    $error++;
                    $return['error'] = $form->error;
                }
            } else {
                $module = $subelement = $ext_element;
                if (preg_match('/^([^_]+)_([^_]+)/i', $ext_element, $regs)) {
                    $module = $regs[1];
                    $subelement = $regs[2];
                }

                dol_include_once('/' . $module . '/class/actions_' . $subelement . '.class.php');
                $classname = 'Actions' . ucfirst($subelement);
                $object = new $classname($db);
                $ret = $object->$loadmethodname();
                if ($ret > 0) {
                    $loadcache = $object->$loadcachename;
                    $value = $loadcache[$newvalue];

                    if (!empty($object->$loadviewname)) {
                        $loadview = $object->$loadviewname;
                        $view = $loadview[$newvalue];
                    }
                } else {
                    $error++;
                    $return['error'] = $object->error;
                }
            }
        }

        if (!$error) {
            if ((isset($object) && !is_object($object)) || empty($savemethod))
                $object = new GenericObject($db);

            // Specific for add_object_linked()
            // TODO add a function for variable treatment
            $object->ext_fk_element = $newvalue;
            $object->ext_element = $ext_element;
            $object->fk_element = $fk_element;
            $object->element = $element;

            $ret = $object->$savemethodname($field, $newvalue, $table_element, $fk_element, $format);
            if ($ret > 0) {
                if ($type == 'numeric')
                    $value = price($newvalue);
                else if ($type == 'textarea')
                    $value = dol_nl2br($newvalue);

                $return['value'] = $value;
                $return['view'] = (!empty($view) ? $view : $value);
            }
            else {
                $return['error'] = $object->error;
            }
        }

        echo json_encode($return);
    } else {
        echo $langs->trans('NotEnoughPermissions');
    }
}
?>
