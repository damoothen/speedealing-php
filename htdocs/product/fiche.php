<?php

/* Copyright (C) 2001-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2006      Auguria SARL         <info@auguria.org>
 * Copyright (C) 2010-2011 Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/canvas.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/html.formproduct.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/lib/product.lib.php';
require_once DOL_DOCUMENT_ROOT . '/societe/lib/societe.lib.php';
if (!empty($conf->propal->enabled))
    require_once DOL_DOCUMENT_ROOT . '/comm/propal/class/propal.class.php';
if (!empty($conf->facture->enabled))
    require_once DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php';
if (!empty($conf->commande->enabled))
    require_once DOL_DOCUMENT_ROOT . '/commande/class/commande.class.php';

$langs->load("products");
$langs->load("other");
if (!empty($conf->stock->enabled))
    $langs->load("stocks");
if (!empty($conf->facture->enabled))
    $langs->load("bills");

$mesg = '';
$error = 0;
$errors = array();
$_error = 0;

$id = GETPOST('id', 'alpha');
$ref = GETPOST('ref', 'alpha');
$type = GETPOST('type', 'alpha');
$action = (GETPOST('action', 'alpha') ? GETPOST('action', 'alpha') : 'view');
$confirm = GETPOST('confirm', 'alpha');
$socid = GETPOST('socid', 'int');
if (!empty($user->societe_id))
    $socid = $user->societe_id;

$object = new Product($db);
$extrafields = new ExtraFields($db);

// Get object canvas (By default, this is not defined, so standard usage of dolibarr)
$object->getCanvas($id, $ref);
$canvas = $object->canvas ? $object->canvas : GETPOST("canvas");
$objcanvas = '';
if (!empty($canvas)) {
    require_once DOL_DOCUMENT_ROOT . '/core/class/canvas.class.php';
    $objcanvas = new Canvas($db, $action);
    $objcanvas->getCanvas('product', 'card', $canvas);
}

// Security check
$fieldvalue = (!empty($id) ? $id : (!empty($ref) ? $ref : ''));
$fieldtype = (!empty($ref) ? 'ref' : 'rowid');
$result = restrictedArea($user, 'produit|service', $fieldvalue, 'product&product', '', '', $fieldtype, $objcanvas);

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
include_once DOL_DOCUMENT_ROOT . '/core/class/hookmanager.class.php';
$hookmanager = new HookManager($db);
$hookmanager->initHooks(array('productcard'));



/*
 * Actions
 */

$parameters = array('id' => $id, 'ref' => $ref, 'objcanvas' => $objcanvas);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action);    // Note that $action and $object may have been modified by some hooks
$error = $hookmanager->error;
$errors = $hookmanager->errors;

if (empty($reshook)) {
    // Type
    if ($action == 'setfk_product_type' && $user->rights->produit->creer) {
        $object->fetch($id);
        $result = $object->setValueFrom('fk_product_type', GETPOST('fk_product_type'));
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id);
        exit;
    }

    // Barcode type
    if ($action == 'setfk_barcode_type' && $user->rights->barcode->creer) {
        $object->fetch($id);
        $result = $object->setValueFrom('fk_barcode_type', GETPOST('fk_barcode_type'));
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id);
        exit;
    }

    // Barcode value
    if ($action == 'setbarcode' && $user->rights->barcode->creer) {
        $object->fetch($id);
        //Todo: ajout verification de la validite du code barre en fonction du type
        $result = $object->setValueFrom('barcode', GETPOST('barcode'));
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id);
        exit;
    }

    if ($action == 'setaccountancy_code_buy') {
        $object->fetch($id, $ref);
        $result = $object->setValueFrom('accountancy_code_buy', GETPOST('accountancy_code_buy'));
        if ($result < 0) {
            $mesg = join(',', $object->errors);
        }
        $action = "";
    }

    if ($action == 'setaccountancy_code_sell') {
        $object->fetch($id, $ref);
        $result = $object->setValueFrom('accountancy_code_sell', GETPOST('accountancy_code_sell'));
        if ($result < 0) {
            $mesg = join(',', $object->errors);
        }
        $action = "";
    }

    // Add a product or service
    if ($action == 'add' && ($user->rights->produit->creer || $user->rights->service->creer)) {
        $error = 0;

        if (!GETPOST('libelle')) {
            $mesg = '<div class="error">' . $langs->trans('ErrorFieldRequired', $langs->transnoentities('Label')) . '</div>';
            $action = "create";
            $error++;
        }
        if (empty($ref)) {
            $mesg = '<div class="error">' . $langs->trans('ErrorFieldRequired', $langs->transnoentities('Ref')) . '</div>';
            $action = "create";
            $error++;
        }

        if (!$error) {
            $object->name = $ref;
            $object->label = GETPOST('libelle');

            $object->type = $type;
            $object->Status = GETPOST('statut');
            $object->description = dol_htmlcleanlastbr(GETPOST('desc'));
            $object->notes = dol_htmlcleanlastbr(GETPOST('note'));
            $object->customcode = GETPOST('customcode');
            $object->country_id = GETPOST('country_id');
            $object->duration_value = GETPOST('duration_value');
            $object->duration_unit = GETPOST('duration_unit');
            $object->seuil_stock_alerte = GETPOST('seuil_stock_alerte') ? GETPOST('seuil_stock_alerte') : 0;
            $object->canvas = GETPOST('canvas');
            $object->weight = GETPOST('weight');
            $object->weight_units = GETPOST('weight_units');
            $object->length = GETPOST('size');
            $object->length_units = GETPOST('size_units');
            $object->surface = GETPOST('surface');
            $object->surface_units = GETPOST('surface_units');
            $object->volume = GETPOST('volume');
            $object->volume_units = GETPOST('volume_units');
            $object->finished = GETPOST('finished');
            $object->hidden = GETPOST('hidden') == 'yes' ? 1 : 0;

            // Price
            $obj = new stdClass();
            $obj->tms = dol_now();
            $obj->price_base_type = GETPOST('price_base_type');
            if ($obj->price_base_type == 'TTC')
                $obj->price_ttc = GETPOST('price');
            else
                $obj->price = GETPOST('price');
            if ($obj->price_base_type == 'TTC')
                $obj->price_min_ttc = GETPOST('price_min');
            else
                $obj->price_min = GETPOST('price_min');

            $obj->tva_tx = str_replace('*', '', GETPOST('tva_tx'));
            $obj->tva_npr = preg_match('/\*/', GETPOST('tva_tx')) ? 1 : 0;

            // local taxes.
            $obj->localtax1_tx = get_localtax($obj->tva_tx, 1);
            $obj->localtax2_tx = get_localtax($obj->tva_tx, 2);

            $obj->fk_user_author = $user->login;

            $object->price = $obj;

            /* // MultiPrix
              if (!empty($conf->global->PRODUIT_MULTIPRICES)) {
              for ($i = 2; $i <= $conf->global->PRODUIT_MULTIPRICES_LIMIT; $i++) {
              if (isset($_POST["price_" . $i])) {
              $object->multiprices["$i"] = price2num($_POST["price_" . $i], 'MU');
              $object->multiprices_base_type["$i"] = $_POST["multiprices_base_type_" . $i];
              } else {
              $object->multiprices["$i"] = "";
              }
              }
              } */

            // Get extra fields
            foreach ($_POST as $key => $value) {
                if (preg_match("/^options_/", $key)) {
                    $object->array_options[$key] = $_POST[$key];
                }
            }

            $id = $object->create($user);

            if ($id < 0) {
                $mesg = '<div class="error">' . $langs->trans($object->error) . '</div>';
                $action = "create";
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
                exit;
            }
        }
    }

    // Update a product or service
    if ($action == 'update' && ($user->rights->produit->creer || $user->rights->service->creer)) {
        if (GETPOST('cancel')) {
            $action = '';
        } else {
            if ($object->fetch($id)) {
                $object->oldcopy = dol_clone($object);

                $object->name = $ref;
                $object->label = GETPOST('libelle');
                $object->description = dol_htmlcleanlastbr(GETPOST('desc'));
                $object->notes = dol_htmlcleanlastbr(GETPOST('note'));
                $object->customcode = GETPOST('customcode');
                $object->country_id = GETPOST('country_id');
                $object->Status = GETPOST('statut');
                $object->seuil_stock_alerte = GETPOST('seuil_stock_alerte');
                $object->duration_value = GETPOST('duration_value');
                $object->duration_unit = GETPOST('duration_unit');
                $object->canvas = GETPOST('canvas');
                $object->weight = GETPOST('weight');
                $object->weight_units = GETPOST('weight_units');
                $object->length = GETPOST('size');
                $object->length_units = GETPOST('size_units');
                $object->surface = GETPOST('surface');
                $object->surface_units = GETPOST('surface_units');
                $object->volume = GETPOST('volume');
                $object->volume_units = GETPOST('volume_units');
                $object->finished = GETPOST('finished');
                $object->hidden = GETPOST('hidden') == 'yes' ? 1 : 0;

                // Get extra fields
                foreach ($_POST as $key => $value) {
                    if (preg_match("/^options_/", $key)) {
                        $object->array_options[$key] = $_POST[$key];
                    }
                }

                if ($object->check()) {
                    if ($object->update($object->id, $user) > 0) {
                        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id);
                        exit;
                    } else {
                        $action = 'edit';
                        $mesg = $object->error;
                    }
                } else {
                    $action = 'edit';
                    $mesg = $langs->trans("ErrorProductBadRefOrLabel");
                }
            }
        }
    }

    // Action clone object
    if ($action == 'confirm_clone' && $confirm != 'yes') {
        $action = '';
    }
    if ($action == 'confirm_clone' && $confirm == 'yes' && ($user->rights->produit->creer || $user->rights->service->creer)) {
        if (!GETPOST('clone_content') && !GETPOST('clone_prices')) {
            $mesg = '<div class="error">' . $langs->trans("NoCloneOptionsSpecified") . '</div>';
        } else {
            $db->begin();

            $originalId = $id;
            if ($object->fetch($id, $ref) > 0) {
                $object->ref = GETPOST('clone_ref');
                $object->Status = "DISABLE";
                $object->finished = "FINISHED";
                unset($object->id);

                if ($object->check()) {
                    $id = $object->create($user);
                    if ($id > 0) {
                        // $object->clone_fournisseurs($originalId, $id);

                        header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $id);
                        exit;
                    } else {
                        $id = $originalId;

                        if ($object->error == 'ErrorProductAlreadyExists') {

                            $_error++;
                            $action = "";

                            $mesg = '<div class="error">' . $langs->trans("ErrorProductAlreadyExists", $object->ref);
                            $mesg.=' <a href="' . $_SERVER["PHP_SELF"] . '?ref=' . $object->ref . '">' . $langs->trans("ShowCardHere") . '</a>.';
                            $mesg.='</div>';
                            //dol_print_error($object->db);
                        } else {
                            $mesg = $object->error;
                            dol_print_error($db, $object->error);
                        }
                    }
                }
            } else {
                dol_print_error($db, $object->error);
            }
        }
    }

    // Delete a product
    if ($action == 'confirm_delete' && $confirm != 'yes') {
        $action = '';
    }
    if ($action == 'confirm_delete' && $confirm == 'yes') {
        $object = new Product($db);
        $object->fetch($id);

        if (($object->type == "PRODUCT" && $user->rights->produit->supprimer) || ($object->type == "SERVICE" && $user->rights->service->supprimer)) {
            $result = $object->delete($object->id);
        }

        if ($result > 0) {
            header('Location: ' . DOL_URL_ROOT . '/product/liste.php?delprod=' . urlencode($object->ref));
            exit;
        } else {
            $mesg = $object->error;
            $reload = 0;
            $action = '';
        }
    }
}

if (GETPOST("cancel") == $langs->trans("Cancel")) {
    $action = '';
    header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $id);
    exit;
}


/*
 * View
 */

$helpurl = '';
if (GETPOST("type") == 'PRODUCT')
    $helpurl = 'EN:Module_Products|FR:Module_Produits|ES:M&oacute;dulo_Productos';
if (GETPOST("type") == 'SERVICE')
    $helpurl = 'EN:Module_Services_En|FR:Module_Services|ES:M&oacute;dulo_Servicios';

if (isset($_GET['type']))
    $title = $langs->trans('CardProduct' . GETPOST('type'));
else
    $title = $langs->trans('ProductServiceCard');

llxHeader('', $title, $helpurl);

$form = new Form($db);
$formproduct = new FormProduct($db);


if (is_object($objcanvas) && $objcanvas->displayCanvasExists($action)) {
    // -----------------------------------------
    // When used with CANVAS
    // -----------------------------------------
    if (empty($object->error) && ($id || $ref)) {
        $object = new Product($db);
        $object->fetch($id, $ref);
    }
    $objcanvas->assign_values($action, $object->id, $ref); // Set value for templates
    $objcanvas->display_canvas($action);    // Show template
} else {
    // -----------------------------------------
    // When used in standard mode
    // -----------------------------------------
    if ($action == 'create' && ($user->rights->produit->creer || $user->rights->service->creer)) {
        //WYSIWYG Editor
        require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

        // Load object modCodeProduct
        $module = (!empty($conf->global->PRODUCT_CODEPRODUCT_ADDON) ? $conf->global->PRODUCT_CODEPRODUCT_ADDON : 'mod_codeproduct_leopard');
        if (substr($module, 0, 16) == 'mod_codeproduct_' && substr($module, -3) == 'php') {
            $module = substr($module, 0, dol_strlen($module) - 4);
        }
        dol_include_once('/core/modules/product/' . $module . '.php');
        $modCodeProduct = new $module;

        print '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
        print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
        print '<input type="hidden" name="action" value="add">';
        print '<input type="hidden" name="type" value="' . $type . '">' . "\n";
        if (!empty($modCodeProduct->code_auto))
            print '<input type="hidden" name="code_auto" value="1">';

        if ($type == "SERVICE")
            $title = $langs->trans("NewService");
        else
            $title = $langs->trans("NewProduct");
        print_fiche_titre($title);
        print '<div class="with-padding">';
        print '<div class="columns">';

        print start_box($title, "twelve", $object->fk_extrafields->ico, false);

        dol_htmloutput_mesg($mesg);

        print '<table class="border" width="100%">';
        print '<tr>';
        $tmpcode = GETPOST("ref");
        if (!empty($modCodeProduct->code_auto))
            $tmpcode = $modCodeProduct->getNextValue($object, $type);
        print '<td class="fieldrequired" width="20%">' . $langs->trans("Ref") . '</td><td><input name="ref" size="40" maxlength="32" value="' . $tmpcode . '">';
        if ($_error) {
            print $langs->trans("RefAlreadyExists");
        }
        print '</td></tr>';

        // Label
        print '<tr><td class="fieldrequired">' . $langs->trans("Label") . '</td><td><input name="libelle" size="40" value="' . GETPOST('libelle') . '"></td></tr>';

        // On sell
        print '<tr><td class="fieldrequired">' . $langs->trans("Status") . '</td><td>';
        print $object->select_fk_extrafields('Status', 'statut');
        print '</td></tr>';

        // Stock min level
        if ($type != "SERVICE" && !empty($conf->stock->enabled)) {
            print '<tr><td>' . $langs->trans("StockLimit") . '</td><td>';
            print '<input name="seuil_stock_alerte" size="4" value="' . GETPOST('seuil_stock_alerte') . '">';
            print '</td></tr>';
        } else {
            print '<input name="seuil_stock_alerte" type="hidden" value="0">';
        }

        // Description (used in invoice, propal...)
        print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td>';

        $doleditor = new DolEditor('desc', GETPOST('desc'), '', 160, 'dolibarr_notes', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 4, 90);
        $doleditor->Create();

        print "</td></tr>";

        // Nature
        if ($type != "SERVICE") {
            print '<tr><td>' . $langs->trans("Nature") . '</td><td>';
            print $object->select_fk_extrafields('finished', 'finished');
            print '</td></tr>';
        }

        // Duration
        if ($type == "SERVICE") {
            print '<tr><td>' . $langs->trans("Duration") . '</td><td><input name="duration_value" size="6" maxlength="5" value="' . GETPOST('duration_value') . '"> &nbsp;';
            print '<input name="duration_unit" type="radio" value="h">' . $langs->trans("Hour") . '&nbsp;';
            print '<input name="duration_unit" type="radio" value="d">' . $langs->trans("Day") . '&nbsp;';
            print '<input name="duration_unit" type="radio" value="w">' . $langs->trans("Week") . '&nbsp;';
            print '<input name="duration_unit" type="radio" value="m">' . $langs->trans("Month") . '&nbsp;';
            print '<input name="duration_unit" type="radio" value="y">' . $langs->trans("Year") . '&nbsp;';
            print '</td></tr>';
        }

        if ($type != "SERVICE") { // Le poids et le volume ne concerne que les produits et pas les services
            // Weight
            print '<tr><td>' . $langs->trans("Weight") . '</td><td>';
            print '<input name="weight" size="4" value="' . GETPOST('weight') . '">';
            print $object->select_fk_extrafields("weight_units", "weight_units");
            print '</td></tr>';
            // Length
            print '<tr><td>' . $langs->trans("Length") . '</td><td>';
            print '<input name="size" size="4" value="' . GETPOST('size') . '">';
            print $object->select_fk_extrafields("size_units", "size_units");
            print '</td></tr>';
            // Surface
            print '<tr><td>' . $langs->trans("Surface") . '</td><td>';
            print '<input name="surface" size="4" value="' . GETPOST('surface') . '">';
            print $object->select_fk_extrafields("surface_units", "surface_units");
            print '</td></tr>';
            // Volume
            print '<tr><td>' . $langs->trans("Volume") . '</td><td>';
            print '<input name="volume" size="4" value="' . GETPOST('volume') . '">';
            print $object->select_fk_extrafields("volume_units", "volume_units");
            print '</td></tr>';

            // Customs code
            print '<tr><td>' . $langs->trans("CustomCode") . '</td><td><input name="customcode" size="10" value="' . GETPOST('customcode') . '"></td></tr>';
        }
        // Origin country
        print '<tr><td>' . $langs->trans("CountryOrigin") . '</td><td>';
        print $object->select_fk_extrafields("country_id", "country_id");
        if ($user->admin)
            print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
        print '</td></tr>';

        // Other attributes
        $parameters = array('colspan' => ' colspan="2"');
        $reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
        if (empty($reshook) && !empty($extrafields->attribute_label)) {
            foreach ($extrafields->attribute_label as $key => $label) {
                $value = (GETPOST('options_' . $key) ? GETPOST('options_' . $key) : $object->array_options["options_" . $key]);
                print '<tr><td>' . $label . '</td><td colspan="3">';
                print $extrafields->showInputField($key, $value);
                print '</td></tr>' . "\n";
            }
        }

        // Note (private, no output on invoices, propales...)
        print '<tr><td valign="top">' . $langs->trans("NoteNotVisibleOnBill") . '</td><td>';

        // We use dolibarr_details as type of DolEditor here, because we must not accept images as description is included into PDF and not accepted by TCPDF.
        $doleditor = new DolEditor('note', GETPOST('note'), '', 180, 'dolibarr_details', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 8, 70);
        $doleditor->Create();

        print "</td></tr>";
        print '</table>';

        print '<br>';

        print '<table class="border" width="100%">';

        // PRIX
        print '<tr><td>' . $langs->trans("SellingPrice") . '</td>';
        print '<td><input name="price" size="10" value="' . $object->price[0]->price . '">';
        print $object->select_fk_extrafields("price_base_type", "price_base_type", $object->price[0]->price_base_type);
        print '</td></tr>';

        // MIN PRICE
        print '<tr><td>' . $langs->trans("MinPrice") . '</td>';
        print '<td><input name="price_min" size="10" value="' . $object->price[0]->price_min . '">';
        print '</td></tr>';

        // VAT
        print '<tr><td width="20%">' . $langs->trans("VATRate") . '</td><td>';
        print $object->select_fk_extrafields("tva_tx", "tva_tx", '', false);
        print '</td></tr>';

        print '</table>';

        print '<br>';

        print '<center><input type="submit" class="button" value="' . $langs->trans("Create") . '"></center>';

        print '</form>';

        print end_box();
        print '</div></div>';
    }

    /**
     * Product card
     */ else if ($id) {
        $res = $object->fetch($id);
        if ($res < 0) {
            dol_print_error($db, $object->error);
            exit;
        }

        // Fiche en mode edition
        if ($action == 'edit' && ($user->rights->produit->creer || $user->rights->service->creer)) {
            //WYSIWYG Editor
            require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

            $type = $langs->trans('Product');
            if ($object->isservice())
                $type = $langs->trans('Service');
            print_fiche_titre($langs->trans('Modify') . ' ' . $type . ' : ' . $object->name);
            print '<div class="with-padding">';
            print '<div class="columns">';

            print start_box($title, "twelve", $object->fk_extrafields->ico, false);

            dol_htmloutput_errors($mesg);

            // Main official, simple, and not duplicated code
            print '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">' . "\n";
            print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
            print '<input type="hidden" name="action" value="update">';
            print '<input type="hidden" name="id" value="' . $object->id . '">';
            print '<input type="hidden" name="canvas" value="' . $object->canvas . '">';
            print '<table class="border allwidth">';

            // Ref
            print '<tr><td width="15%" class="fieldrequired">' . $langs->trans("Ref") . '</td><td colspan="2"><input name="ref" size="40" maxlength="32" value="' . (GETPOST("ref") ? GETPOST("ref") : $object->name) . '"></td></tr>';

            // Label
            print '<tr><td class="fieldrequired">' . $langs->trans("Label") . '</td><td colspan="2"><input name="libelle" size="40" value="' . $object->label . '"></td></tr>';

            // Status
            print '<tr><td class="fieldrequired">' . $langs->trans("Status") . '</td><td colspan="2">';
            print $object->select_fk_extrafields('Status', 'statut');
            print '</td></tr>';

            // Description (used in invoice, propal...)
            print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td colspan="2">';

            // We use dolibarr_details as type of DolEditor here, because we must not accept images as description is included into PDF and not accepted by TCPDF.
            $doleditor = new DolEditor('desc', $object->description, '', 160, 'dolibarr_details', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 4, 90);
            $doleditor->Create();

            print "</td></tr>";
            print "\n";

            // Nature
            if ($object->type != "SERVICE") {
                print '<tr><td>' . $langs->trans("Nature") . '</td><td colspan="2">';
                print $object->select_fk_extrafields('finished', 'finished');
                print '</td></tr>';
            }

            if ($object->isproduct() && !empty($conf->stock->enabled)) {
                print "<tr>" . '<td>' . $langs->trans("StockLimit") . '</td><td colspan="2">';
                print '<input name="seuil_stock_alerte" size="4" value="' . $object->seuil_stock_alerte . '">';
                print '</td></tr>';
            } else {
                print '<input name="seuil_stock_alerte" type="hidden" value="' . $object->seuil_stock_alerte . '">';
            }

            if ($object->isservice()) {
                // Duration
                print '<tr><td>' . $langs->trans("Duration") . '</td><td colspan="2"><input name="duration_value" size="3" maxlength="5" value="' . $object->duration_value . '">';
                print '&nbsp; ';
                print '<input name="duration_unit" type="radio" value="h"' . ($object->duration_unit == 'h' ? ' checked' : '') . '>' . $langs->trans("Hour");
                print '&nbsp; ';
                print '<input name="duration_unit" type="radio" value="d"' . ($object->duration_unit == 'd' ? ' checked' : '') . '>' . $langs->trans("Day");
                print '&nbsp; ';
                print '<input name="duration_unit" type="radio" value="w"' . ($object->duration_unit == 'w' ? ' checked' : '') . '>' . $langs->trans("Week");
                print '&nbsp; ';
                print '<input name="duration_unit" type="radio" value="m"' . ($object->duration_unit == 'm' ? ' checked' : '') . '>' . $langs->trans("Month");
                print '&nbsp; ';
                print '<input name="duration_unit" type="radio" value="y"' . ($object->duration_unit == 'y' ? ' checked' : '') . '>' . $langs->trans("Year");

                print '</td></tr>';
            } else {
                // Weight
                print '<tr><td>' . $langs->trans("Weight") . '</td><td colspan="2">';
                print '<input name="weight" size="5" value="' . $object->weight . '"> ';
                print $object->select_fk_extrafields("weight_units", "weight_units");
                print '</td></tr>';
                // Length
                print '<tr><td>' . $langs->trans("Length") . '</td><td colspan="2">';
                print '<input name="size" size="5" value="' . $object->length . '"> ';
                print $object->select_fk_extrafields("size_units", "size_units");
                print '</td></tr>';
                // Surface
                print '<tr><td>' . $langs->trans("Surface") . '</td><td colspan="2">';
                print '<input name="surface" size="5" value="' . $object->surface . '"> ';
                print $object->select_fk_extrafields("surface_units", "surface_units");
                print '</td></tr>';
                // Volume
                print '<tr><td>' . $langs->trans("Volume") . '</td><td colspan="2">';
                print '<input name="volume" size="5" value="' . $object->volume . '"> ';
                print $object->select_fk_extrafields("volume_units", "volume_units");
                print '</td></tr>';

                // Customs code
                print '<tr><td>' . $langs->trans("CustomCode") . '</td><td colspan="2"><input name="customcode" size="10" value="' . $object->customcode . '"></td></tr>';
            }

            // Origin country
            print '<tr><td>' . $langs->trans("CountryOrigin") . '</td><td colspan="2">';
            print $object->select_fk_extrafields('country_id', 'country_id');
            //print $form->select_country($object->country_id, 'country_id');
            if ($user->admin)
                print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
            print '</td></tr>';

            // Other attributes
            $parameters = array('colspan' => ' colspan="2"');
            $reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
            if (empty($reshook) && !empty($extrafields->attribute_label)) {
                foreach ($extrafields->attribute_label as $key => $label) {
                    $value = (isset($_POST["options_" . $key]) ? $_POST["options_" . $key] : $object->array_options["options_" . $key]);
                    print '<tr><td>' . $label . '</td><td colspan="3">';
                    print $extrafields->showInputField($key, $value);
                    print '</td></tr>' . "\n";
                }
            }

            // Note
            print '<tr><td valign="top">' . $langs->trans("NoteNotVisibleOnBill") . '</td><td colspan="2">';

            $doleditor = new DolEditor('note', $object->note, '', 200, 'dolibarr_notes', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 8, 70);
            $doleditor->Create();

            print "</td></tr>";
            print '</table>';

            print '<br>';

            print '<center><input type="submit" class="button" value="' . $langs->trans("Save") . '"> &nbsp; &nbsp; ';
            print '<input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '"></center>';

            print '</form>';

            print end_box();
            print '</div><div>';
        } else {
            // Fiche en mode visu
            dol_htmloutput_mesg($mesg);

            $head = product_prepare_head($object, $user);
            $title = $langs->trans("CardProduct" . $object->type);
            $picto = ($object->type == "SERVICE" ? 'service' : 'product');

            print_fiche_titre($title);
            print '<div class="with-padding">';
            print '<div class="columns">';

            print start_box($title, "twelve", $object->fk_extrafields->ico, false);

            dol_fiche_head($head, 'card', $title, 0, $picto);

            $showphoto = $object->is_photo_available($conf->product->multidir_output[$object->entity]);
            $showbarcode = (!empty($conf->barcode->enabled) && $user->rights->barcode->lire);

            // En mode visu
            print '<table class="border" width="100%"><tr>';

            // Ref
            print '<td width="15%">' . $langs->trans("Ref") . '</td><td colspan="' . (2 + (($showphoto || $showbarcode) ? 1 : 0)) . '">';
            print $form->showrefnav($object, 'ref', '', 1, 'ref');
            print '</td>';
            print '</tr>';

            //Name
            print '<tr><td>' . $langs->trans("Name") . '</td><td colspan="2">' . $object->name . '</td>';

            // Label
            print '<tr><td>' . $form->editfieldkey("Label", 'label', $object->label, $object, $user->rights->produit->creer || $user->rights->service->creer, 'string') . '</td><td colspan="2">';
            print $form->editfieldval("Label", 'label', $object->label, $object, $user->rights->produit->creer || $user->rights->service->creer, 'string');
            print '</td>';

            $nblignes = 8;
            if (!empty($conf->produit->enabled) && !empty($conf->service->enabled))
                $nblignes++;
            if ($showbarcode)
                $nblignes+=2;
            if ($object->type != "SERVICE")
                $nblignes++;
            if ($object->isservice())
                $nblignes++;
            else
                $nblignes+=4;

            // Photo
            if ($showphoto || $showbarcode) {
                print '<td valign="middle" align="center" width="25%" rowspan="' . $nblignes . '">';
                if ($showphoto)
                    print $object->show_photos($conf->product->multidir_output[$object->entity], 1, 1, 0, 0, 0, 80);
                if ($showphoto && $showbarcode)
                    print '<br><br>';
                if ($showbarcode)
                    print $form->showbarcode($object);
                print '</td>';
            }

            print '</tr>';

            // Type
            if (!empty($conf->produit->enabled) && !empty($conf->service->enabled)) {
                print '<tr><td>' . $form->editfieldkey("Type", 'type', $object->type, $object, $user->rights->produit->creer || $user->rights->service->creer, "select") . '</td><td colspan="2">';
                print $form->editfieldval("Type", 'type', $object->type, $object, $user->rights->produit->creer || $user->rights->service->creer, "select");
                print '</td></tr>';
            }

            if ($showbarcode) {
                // Barcode type
                print '<tr><td nowrap>';
                print '<table width="100%" class="nobordernopadding"><tr><td nowrap>';
                print $langs->trans("BarcodeType");
                print '<td>';
                if (($action != 'editbarcodetype') && $user->rights->barcode->creer)
                    print '<td align="right"><a href="' . $_SERVER["PHP_SELF"] . '?action=editbarcodetype&amp;id=' . $object->id . '">' . img_edit($langs->trans('Edit'), 1) . '</a></td>';
                print '</tr></table>';
                print '</td><td colspan="2">';
                if ($action == 'editbarcodetype') {
                    require_once DOL_DOCUMENT_ROOT . '/core/class/html.formbarcode.class.php';
                    $formbarcode = new FormBarCode($db);
                    $formbarcode->form_barcode_type($_SERVER['PHP_SELF'] . '?id=' . $object->id, $object->barcode_type, 'fk_barcode_type');
                } else {
                    $object->fetch_barcode();
                    print $object->barcode_type_label ? $object->barcode_type_label : ($object->barcode ? '<div class="warning">' . $langs->trans("SetDefaultBarcodeType") . '<div>' : '');
                }
                print '</td></tr>' . "\n";

                // Barcode value
                print '<tr><td nowrap>';
                print '<table width="100%" class="nobordernopadding"><tr><td nowrap>';
                print $langs->trans("BarcodeValue");
                print '<td>';
                if (($action != 'editbarcode') && $user->rights->barcode->creer)
                    print '<td align="right"><a href="' . $_SERVER["PHP_SELF"] . '?action=editbarcode&amp;id=' . $object->id . '">' . img_edit($langs->trans('Edit'), 1) . '</a></td>';
                print '</tr></table>';
                print '</td><td colspan="2">';
                if ($action == 'editbarcode') {
                    print '<form method="post" action="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '">';
                    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
                    print '<input type="hidden" name="action" value="setbarcode">';
                    print '<input size="40" type="text" name="barcode" value="' . $object->barcode . '">';
                    print '&nbsp;<input type="submit" class="button" value="' . $langs->trans("Modify") . '">';
                } else {
                    print $object->barcode;
                }
                print '</td></tr>' . "\n";
            }

            // Accountancy sell code
            print '<tr><td>' . $form->editfieldkey("ProductAccountancySellCode", 'accountancy_code_sell', $object->accountancy_code_sell, $object, $user->rights->produit->creer || $user->rights->service->creer, 'string') . '</td><td colspan="2">';
            print $form->editfieldval("ProductAccountancySellCode", 'accountancy_code_sell', $object->accountancy_code_sell, $object, $user->rights->produit->creer || $user->rights->service->creer, 'string');
            print '</td></tr>';

            // Accountancy buy code
            print '<tr><td>' . $form->editfieldkey("ProductAccountancyBuyCode", 'accountancy_code_buy', $object->accountancy_code_buy, $object, $user->rights->produit->creer || $user->rights->service->creer, 'string') . '</td><td colspan="2">';
            print $form->editfieldval("ProductAccountancyBuyCode", 'accountancy_code_buy', $object->accountancy_code_buy, $object, $user->rights->produit->creer || $user->rights->service->creer, 'string');
            print '</td></tr>';

            // Status
            //print '<tr><td>' . $langs->trans("Status") . '</td><td colspan="2">';
            print '<tr><td>' . $form->editfieldkey("Status", 'Status', $object->Status, $object, $user->rights->produit->creer || $user->rights->service->creer, "select") . '</td><td colspan="2">';
            print $form->editfieldval("Status", 'Status', $object->Status, $object, $user->rights->produit->creer || $user->rights->service->creer, "select");
            //print $object->getLibStatus();
            print '</td></tr>';

            // Description
            print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td colspan="2">' . (dol_textishtml($object->description) ? $object->description : dol_nl2br($object->description, 1, true)) . '</td></tr>';

            // Nature
            if ($object->type != "SERVICE") {
                print '<tr><td>' . $langs->trans("Nature") . '</td><td colspan="2">';
                print $object->print_fk_extrafields("finished");
                print '</td></tr>';
            }

            if ($object->isservice()) {
                // Duration
                print '<tr><td>' . $langs->trans("Duration") . '</td><td colspan="2">' . $object->duration_value . '&nbsp;';
                if ($object->duration_value > 1) {
                    $dur = array("h" => $langs->trans("Hours"), "d" => $langs->trans("Days"), "w" => $langs->trans("Weeks"), "m" => $langs->trans("Months"), "y" => $langs->trans("Years"));
                } else if ($object->duration_value > 0) {
                    $dur = array("h" => $langs->trans("Hour"), "d" => $langs->trans("Day"), "w" => $langs->trans("Week"), "m" => $langs->trans("Month"), "y" => $langs->trans("Year"));
                }
                print (!empty($object->duration_unit) && isset($dur[$object->duration_unit]) ? $langs->trans($dur[$object->duration_unit]) : '') . "&nbsp;";

                print '</td></tr>';
            } else {
                // Weight
                print '<tr><td>' . $langs->trans("Weight") . '</td><td colspan="2">';
                if ($object->weight != '') {
                    print $object->weight . " " . $object->print_fk_extrafields("weight_units");
                } else {
                    print '&nbsp;';
                }
                print "</td></tr>\n";
                // Length
                print '<tr><td>' . $langs->trans("Length") . '</td><td colspan="2">';
                if ($object->length != '') {
                    print $object->length . " " . $object->print_fk_extrafields("length_units");
                } else {
                    print '&nbsp;';
                }
                print "</td></tr>\n";
                // Surface
                print '<tr><td>' . $langs->trans("Surface") . '</td><td colspan="2">';
                if ($object->surface != '') {
                    print $object->surface . " " . $object->print_fk_extrafields("surface_units");
                } else {
                    print '&nbsp;';
                }
                print "</td></tr>\n";
                // Volume
                print '<tr><td>' . $langs->trans("Volume") . '</td><td colspan="2">';
                if ($object->volume != '') {
                    print $object->volume . " " . $object->print_fk_extrafields("volume_units");
                } else {
                    print '&nbsp;';
                }
                print "</td></tr>\n";
            }

            // Customs code
            print '<tr><td>' . $langs->trans("CustomCode") . '</td><td colspan="2">' . $object->customcode . '</td>';

            // Origin country code
            print '<tr><td>' . $langs->trans("CountryOrigin") . '</td><td colspan="2">';
            if ($object->country_id) {
                $img = picto_from_langcode($object->country_id);
                print ($img ? $img . ' ' : '') . $object->print_fk_extrafields("country_id");
            }
            print '</td>';

            // Other attributes
            $parameters = array('colspan' => ' colspan="' . (2 + (($showphoto || $showbarcode) ? 1 : 0)) . '"');
            $reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
            if (empty($reshook) && !empty($extrafields->attribute_label)) {
                foreach ($extrafields->attribute_label as $key => $label) {
                    $value = (isset($_POST["options_" . $key]) ? $_POST["options_" . $key] : $object->array_options["options_" . $key]);
                    print '<tr><td>' . $label . '</td><td colspan="3">';
                    print $extrafields->showOutputField($key, $value);
                    print '</td></tr>' . "\n";
                }
            }

            print "</table>\n";

            dol_fiche_end();

            /*             * ************************************************************************* */
            /*                                                                            */
            /* Barre d'action                                                             */
            /*                                                                            */
            /*             * ************************************************************************* */

            print "\n" . '<div class="tabsAction">' . "\n";

            if ($action == '' || $action == 'view') {
                if ($user->rights->produit->creer || $user->rights->service->creer) {
                    if (!isset($object->no_button_edit) || $object->no_button_edit <> 1)
                        print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=edit&amp;id=' . $object->id . '">' . $langs->trans("Modify") . '</a>';

                    if (!isset($object->no_button_copy) || $object->no_button_copy <> 1) {
                        if (!empty($conf->use_javascript_ajax)) {
                            print '<span id="action-clone" class="butAction">' . $langs->trans('ToClone') . '</span>' . "\n";
                            print $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('CloneProduct'), $langs->trans('ConfirmCloneProduct', $object->ref), 'confirm_clone', $formquestionclone, 'yes', 'action-clone', 230, 600);
                        } else {
                            print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=clone&amp;id=' . $object->id . '">' . $langs->trans("ToClone") . '</a>';
                        }
                    }
                }
                $object_is_used = $object->isObjectUsed($object->id);

                if (($object->type == "PRODUCT" && $user->rights->produit->supprimer)
                        || ($object->type == "SERVICE" && $user->rights->service->supprimer)) {
                    if (empty($object_is_used) && (!isset($object->no_button_delete) || $object->no_button_delete <> 1)) {
                        if (!empty($conf->use_javascript_ajax)) {
                            print '<span id="action-delete" class="butActionDelete">' . $langs->trans('Delete') . '</span>' . "\n";
                            print $form->formconfirm("fiche.php?id=" . $object->id, $langs->trans("DeleteProduct"), $langs->trans("ConfirmDeleteProduct"), "confirm_delete", '', 0, "action-delete");
                        } else {
                            print '<a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?action=delete&amp;id=' . $object->id . '">' . $langs->trans("Delete") . '</a>';
                        }
                    } else {
                        print '<a class="butActionRefused" href="#" title="' . $langs->trans("ProductIsUsed") . '">' . $langs->trans("Delete") . '</a>';
                    }
                } else {
                    print '<a class="butActionRefused" href="#" title="' . $langs->trans("NotEnoughPermissions") . '">' . $langs->trans("Delete") . '</a>';
                }
            }

            print "</div>";

            print end_box();

            print $object->show_notes();

            print $object->show_price(10, $object->id);

            print '</div></div>';
        }
    } else if ($action != 'create') {
        header("Location: index.php");
        exit;
    }
}


// Define confirmation messages
$formquestionclone = array(
    'text' => $langs->trans("ConfirmClone"),
    array('type' => 'text', 'name' => 'clone_ref', 'label' => $langs->trans("NewRefForClone"), 'value' => $langs->trans("CopyOf") . ' ' . $object->ref, 'size' => 24),
    array('type' => 'checkbox', 'name' => 'clone_content', 'label' => $langs->trans("CloneContentProduct"), 'value' => 1),
    array('type' => 'checkbox', 'name' => 'clone_prices', 'label' => $langs->trans("ClonePricesProduct") . ' (' . $langs->trans("FeatureNotYetAvailable") . ')', 'value' => 0, 'disabled' => true)
);

// Confirm delete product
if ($action == 'delete' && empty($conf->use_javascript_ajax)) {
    print $form->formconfirm("fiche.php?id=" . $object->id, $langs->trans("DeleteProduct"), $langs->trans("ConfirmDeleteProduct"), "confirm_delete", '', 0, "action-delete");
}

// Clone confirmation
if ($action == 'clone' && empty($conf->use_javascript_ajax)) {
    print $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('CloneProduct'), $langs->trans('ConfirmCloneProduct', $object->ref), 'confirm_clone', $formquestionclone, 'yes', 'action-clone', 230, 600);
}

llxFooter();
?>
