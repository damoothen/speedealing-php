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

$class = GETPOST('class', 'alpha');
if (empty($class))
    $class = GETPOST('element_class', 'alpha');
$key = GETPOST('key', 'alpha');
$id = GETPOST('id', 'alpha');
$value = GETPOST('value', 'alpha');

$field = GETPOST('field', 'alpha', 2);
$element = GETPOST('element', 'alpha', 2);
$table_element = GETPOST('table_element', 'alpha', 2);
$fk_element = GETPOST('fk_element', 'alpha', 2);

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url ' . $_SERVER["PHP_SELF"] . '?' . $_SERVER["QUERY_STRING"] . ' -->' . "\n";
//print_r($_POST);

if (!empty($key) && !empty($id) && !empty($class)) {
    $res = dol_include_once("/" . $class . "/class/" . strtolower($class) . ".class.php");
    if (!$res) // old dolibarr
        dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

    $object = new $class($db);    

    $langs->load('companies');

    try {

        $object->id = $id;
        $res = $object->set($key, $value);
        if ($res == $value) {
            if ($key == 'Status')
                echo $object->LibStatus($value);
            else
                echo $value;
        }
        else {
            print $res . "</br>";
            print_r($object->errors);
        }
        exit;
    } catch (Exception $exc) {
        error_log($exc->getMessage());
        exit;
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