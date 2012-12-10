<?php

/* Copyright (C) 2001-2007 Rodolphe Quiedeville  <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur   <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne           <eric.seigne@ryxeo.com>
 * Copyright (C) 2005      Marc Barilley / Ocebo <marc@ocebo.com>
 * Copyright (C) 2005-2012 Regis Houssin         <regis@dolibarr.fr>
 * Copyright (C) 2006      Andre Cianfarani      <acianfa@free.fr>
 * Copyright (C) 2010-2012 Juanjo Menent         <jmenent@2byte.es>
 * Copyright (C) 2010-2011 Philippe Grand        <philippe.grand@atoo-net.com>
 * Copyright (C) 2012      Christophe Battarel   <christophe.battarel@altairis.fr>
 * Copyright (C) 2012      David Moothen   <dmoothen@websitti.fr>
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
 * 	\file       	htdocs/comm/propal.php
 * 	\ingroup    	propale
 * 	\brief      	Page of commercial proposals card and list
 */
/* Includes ***************************************************************** */


require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formpropal.class.php';
require_once DOL_DOCUMENT_ROOT . '/propal/class/propal.class.php';
require_once DOL_DOCUMENT_ROOT . '/propal/core/modules/propale/modules_propale.php';
require_once DOL_DOCUMENT_ROOT . '/propal/lib/propal.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
if (!empty($conf->projet->enabled))
    require_once DOL_DOCUMENT_ROOT . '/projet/class/project.class.php';


/* Loading langs ************************************************************ */


$langs->load('companies');
$langs->load('propal');
$langs->load('compta');
$langs->load('bills');
$langs->load('orders');
$langs->load('products');
if (!empty($conf->margin->enabled))
    $langs->load('margins');


/* Post params ************************************************************** */


$id = GETPOST('id', 'alpha');
$action = (GETPOST('action', 'alpha') ? GETPOST('action', 'alpha') : 'view');
$confirm = GETPOST('confirm');
$lineid = GETPOST('lineid', 'alpha');
$origin = GETPOST('origin', 'alpha');
$originid = (GETPOST('originid', 'alpha') ? GETPOST('originid', 'alpha') : GETPOST('origin_id', 'alpha')); // For backward compatibility
//PDF
$hidedetails = (GETPOST('hidedetails', 'int') ? GETPOST('hidedetails', 'int') : (!empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DETAILS) ? 1 : 0));
$hidedesc = (GETPOST('hidedesc', 'int') ? GETPOST('hidedesc', 'int') : (!empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_DESC) ? 1 : 0));
$hideref = (GETPOST('hideref', 'int') ? GETPOST('hideref', 'int') : (!empty($conf->global->MAIN_GENERATE_DOCUMENTS_HIDE_REF) ? 1 : 0));


$title = $langs->trans('Proposal');

$object = new Propal($db);
$soc = new Societe($db);
if (!empty($id)) {
    $object->fetch($id);
    $soc->fetch($object->socid);
    $object->getLinesArray();
}

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
include_once DOL_DOCUMENT_ROOT . '/core/class/hookmanager.class.php';
$hookmanager = new HookManager($db);
$hookmanager->initHooks(array('ordercard'));


/* Actions ****************************************************************** */


if ($action == 'add' && $user->rights->propal->creer) {

//	$object->socid=$socid;
//	$object->fetch_thirdparty();

    $datep = dol_mktime(12, 0, 0, GETPOST('remonth'), GETPOST('reday'), GETPOST('reyear'));
    $date_delivery = dol_mktime(12, 0, 0, GETPOST('liv_month'), GETPOST('liv_day'), GETPOST('liv_year'));
    $duration = GETPOST('duree_validite');

    if (empty($datep)) {
        setEventMessage($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Date")), 'errors');
        header("Location: " . DOL_URL_ROOT . '/comm/addpropal.php?socid=' . $socid . '&action=create');
        exit;
    }
    if (empty($duration)) {
        setEventMessage($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("ValidityDuration")), 'errors');
        header("Location: " . DOL_URL_ROOT . '/comm/addpropal.php?socid=' . $socid . '&action=create');
        exit;
    }
    $object->datec = $datep;
    $object->date_livraison = $date_delivery;

    if (!$error) {

        // Si on a selectionne une propal a copier, on realise la copie
        if (GETPOST('createmode') == 'copy' && GETPOST('copie_propal')) {
            if ($object->fetch(GETPOST('copie_propal')) > 0) {
                $object->ref = GETPOST('ref');
                $object->datep = $datep;
                $object->date_livraison = $date_delivery;
                $object->availability_id = GETPOST('availability_id');
                $object->demand_reason_id = GETPOST('demand_reason_id');
                $object->fk_delivery_address = GETPOST('fk_address');
                $object->duree_validite = $duration;
                $object->cond_reglement_id = GETPOST('cond_reglement_id');
                $object->mode_reglement_id = GETPOST('mode_reglement_id');
                $object->remise_percent = GETPOST('remise_percent');
                $object->remise_absolue = GETPOST('remise_absolue');
                $object->socid = GETPOST('socid');
                $object->contactid = GETPOST('contactidp');
                $object->fk_project = GETPOST('projectid');
                $object->modelpdf = GETPOST('model');
                $object->author = $user->id;   // deprecated
                $object->note = GETPOST('note');
                $object->statut = 0;

                $id = $object->create_from($user);
            } else {
                setEventMessage($langs->trans("ErrorFailedToCopyProposal", GETPOST('copie_propal')), 'errors');
            }
        } else {
            $object->socid = GETPOST('socid');
            $object->ref_client = GETPOST('ref_client');
            $object->datep = $datep;
            $object->date_livraison = $date_delivery;
            $object->availability_code = GETPOST('availability_code');
            $object->demand_reason_code = GETPOST('demand_reason_code');
            $object->fk_delivery_address = GETPOST('fk_address');
            $object->duree_validite = GETPOST('duree_validite');
            $object->cond_reglement_code = GETPOST('cond_reglement_code');
            $object->mode_reglement_code = GETPOST('mode_reglement_code');
            $object->contactid = GETPOST('contactidp');
            $object->fk_project = GETPOST('projectid');
            $object->modelpdf = GETPOST('model');
            $object->author = $user->id;  // deprecated
            $object->note = GETPOST('note');
            $object->note_public = GETPOST('note_public');
            $object->origin = GETPOST('origin');
            $object->origin_id = GETPOST('originid');

            for ($i = 1; $i <= $conf->global->PRODUCT_SHOW_WHEN_CREATE; $i++) {
                if ($_POST['idprod' . $i]) {
                    $xid = 'idprod' . $i;
                    $xqty = 'qty' . $i;
                    $xremise = 'remise' . $i;
                    $object->add_product($_POST[$xid], $_POST[$xqty], $_POST[$xremise]);
                }
            }

            $id = $object->create($user);
        }

        if (!empty($id)) {
            // Insertion contact par defaut si defini
            if (GETPOST('contactidp')) {
                $result = $object->add_contact(GETPOST('contactidp'), 'CUSTOMER', 'external');
                if ($result < 0) {
                    $error++;
                    setEventMessage($langs->trans("ErrorFailedToAddContact"), 'errors');
                }
            }

            if (!$error) {

                if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
                    // Define output language
                    $outputlangs = $langs;
                    if (!empty($conf->global->MAIN_MULTILANGS)) {
                        $outputlangs = new Translate("", $conf);
                        $newlang = (GETPOST('lang_id') ? GETPOST('lang_id') : $object->client->default_lang);
                        $outputlangs->setDefaultLang($newlang);
                    }
                    $ret = $object->fetch($id);    // Reload to get new records
                    propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
                }

                header('Location: propal.php?id=' . $id);
                exit;
            }
        } else {
            dol_print_error($db, $object->error);
            exit;
        }
    }
} 

else if ($action == 'update' && $user->rights->propal->creer) {

    $datep = dol_mktime(12, 0, 0, GETPOST('remonth'), GETPOST('reday'), GETPOST('reyear'));
    $date_delivery = dol_mktime(12, 0, 0, GETPOST('liv_month'), GETPOST('liv_day'), GETPOST('liv_year'));
    $duration = GETPOST('duree_validite');

    if (empty($datep)) {
        setEventMessage($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Date")), 'errors');
        header("Location: " . DOL_URL_ROOT . '/comm/addpropal.php?socid=' . $socid . '&action=create');
        exit;
    }
    if (empty($duration)) {
        setEventMessage($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("ValidityDuration")), 'errors');
        header("Location: " . DOL_URL_ROOT . '/comm/addpropal.php?socid=' . $socid . '&action=create');
        exit;
    }
    $object->datec = $datep;
    $object->date_livraison = $date_delivery;

    if (!$error) {
        $object->socid = GETPOST('socid');
        $object->ref_client = GETPOST('ref_client');
        $object->datep = $datep;
        $object->date_livraison = $date_delivery;
        $object->availability_code = GETPOST('availability_code');
        $object->demand_reason_code = GETPOST('demand_reason_code');
        $object->fk_delivery_address = GETPOST('fk_address');
        $object->duree_validite = GETPOST('duree_validite');
        $object->cond_reglement_code = GETPOST('cond_reglement_code');
        $object->mode_reglement_code = GETPOST('mode_reglement_code');
        $object->contactid = GETPOST('contactidp');
        $object->fk_project = GETPOST('projectid');
        $object->modelpdf = GETPOST('model');
        $object->author = $user->id;  // deprecated
        $object->note = GETPOST('note');
        $object->note_public = GETPOST('note_public');
        $object->origin = GETPOST('origin');
        $object->origin_id = GETPOST('originid');

        $result = $object->update();
        if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
            // Define output language
            $outputlangs = $langs;
            if (!empty($conf->global->MAIN_MULTILANGS)) {
                $outputlangs = new Translate("", $conf);
                $newlang = (GETPOST('lang_id') ? GETPOST('lang_id') : $object->client->default_lang);
                $outputlangs->setDefaultLang($newlang);
            }
            $ret = $object->fetch($id);    // Reload to get new records
            propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
        }

        header('Location: propal.php?id=' . $id);
        exit;
    }
} else if ($action == 'confirm_delete' && $confirm == 'yes' && $user->rights->propal->supprimer) {
    $result = $object->delete($user);
    if ($result > 0) {
        header('Location: index.php');
        exit;
    } else {
        $mesg = '<div class="error">' . $object->error . '</div>';
    }
} else if ($action == 'addline' && $user->rights->propal->creer) {

    $langs->load('errors');
    $error = false;

    $idprod = GETPOST('idprod', 'int');
    $product_desc = (GETPOST('product_desc') ? GETPOST('product_desc') : (GETPOST('np_desc') ? GETPOST('np_desc') : (GETPOST('dp_desc') ? GETPOST('dp_desc') : '')));
    $price_ht = GETPOST('price_ht');
    $tva_tx = GETPOST('tva_tx');

    if ((empty($idprod) || GETPOST('usenewaddlineform')) && ($price_ht < 0) && (GETPOST('qty') < 0)) {
        setEventMessage($langs->trans('ErrorBothFieldCantBeNegative', $langs->transnoentitiesnoconv('UnitPriceHT'), $langs->transnoentitiesnoconv('Qty')), 'errors');
        $error = true;
    }
    if (empty($idprod) && GETPOST('type') < 0) {
        setEventMessage($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Type')), 'errors');
        $error = true;
    }
    if ((empty($idprod) || GETPOST('usenewaddlineform')) && (!($price_ht >= 0) || $price_ht == '')) { // Unit price can be 0 but not ''
        setEventMessage($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("UnitPriceHT")), 'errors');
        $error++;
    }
    if (!GETPOST('qty') && GETPOST('qty') == '') {
        setEventMessage($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Qty')), 'errors');
        $error = true;
    }
    if (empty($idprod) && empty($product_desc)) {
        setEventMessage($langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv('Description')), 'errors');
        $error = true;
    }

    if (!$error && (GETPOST('qty') >= 0) && (!empty($product_desc) || !empty($idprod))) {
        // Clean parameters
        $predef = ((!empty($idprod) && $conf->global->MAIN_FEATURES_LEVEL < 2) ? '_predef' : '');
        $date_start = dol_mktime(0, 0, 0, GETPOST('date_start' . $predef . 'month'), GETPOST('date_start' . $predef . 'day'), GETPOST('date_start' . $predef . 'year'));
        $date_end = dol_mktime(0, 0, 0, GETPOST('date_end' . $predef . 'month'), GETPOST('date_end' . $predef . 'day'), GETPOST('date_end' . $predef . 'year'));
        $price_base_type = (GETPOST('price_base_type', 'alpha') ? GETPOST('price_base_type', 'alpha') : 'HT');

        // Ecrase $pu par celui du produit
        // Ecrase $desc par celui du produit
        // Ecrase $txtva par celui du produit
        // Ecrase $base_price_type par celui du produit
        if (!empty($idprod)) {
            $prod = new Product($db);
            $prod->fetch($idprod);

            $label = ((GETPOST('product_label') && GETPOST('product_label') != $prod->label) ? GETPOST('product_label') : '');

            // Update if prices fields are defined
            if (GETPOST('usenewaddlineform')) {
                $pu_ht = price2num($price_ht, 'MU');
                $pu_ttc = price2num(GETPOST('price_ttc'), 'MU');
                $tva_npr = (preg_match('/\*/', $tva_tx) ? 1 : 0);
                $tva_tx = str_replace('*', '', $tva_tx);
                $desc = $product_desc;
            } else {
                $tva_tx = get_default_tva($mysoc, $object->client, $prod->id);
                $tva_npr = get_default_npr($mysoc, $object->client, $prod->id);

                // multiprix
                if (!empty($conf->global->PRODUIT_MULTIPRICES) && !empty($object->client->price_level)) {
                    $pu_ht = $prod->multiprices[$object->client->price_level];
                    $pu_ttc = $prod->multiprices_ttc[$object->client->price_level];
                    $price_min = $prod->multiprices_min[$object->client->price_level];
                    $price_base_type = $prod->multiprices_base_type[$object->client->price_level];
                } else {
                    $pu_ht = $prod->price;
                    $pu_ttc = $prod->price_ttc;
                    $price_min = $prod->price_min;
                    $price_base_type = $prod->price_base_type;
                }

                // On reevalue prix selon taux tva car taux tva transaction peut etre different
                // de ceux du produit par defaut (par exemple si pays different entre vendeur et acheteur).
                if ($tva_tx != $prod->tva_tx) {
                    if ($price_base_type != 'HT') {
                        $pu_ht = price2num($pu_ttc / (1 + ($tva_tx / 100)), 'MU');
                    } else {
                        $pu_ttc = price2num($pu_ht * (1 + ($tva_tx / 100)), 'MU');
                    }
                }

                $desc = '';

                // Define output language
                if (!empty($conf->global->MAIN_MULTILANGS) && !empty($conf->global->PRODUIT_TEXTS_IN_THIRDPARTY_LANGUAGE)) {
                    $outputlangs = $langs;
                    $newlang = '';
                    if (empty($newlang) && GETPOST('lang_id'))
                        $newlang = GETPOST('lang_id');
                    if (empty($newlang))
                        $newlang = $object->client->default_lang;
                    if (!empty($newlang)) {
                        $outputlangs = new Translate("", $conf);
                        $outputlangs->setDefaultLang($newlang);
                    }

                    $desc = (!empty($prod->multilangs[$outputlangs->defaultlang]["description"])) ? $prod->multilangs[$outputlangs->defaultlang]["description"] : $prod->description;
                } else {
                    $desc = $prod->description;
                }

                $desc = dol_concatdesc($desc, $product_desc);
            }

            $type = $prod->type;
        } else {
            $pu_ht = price2num($price_ht, 'MU');
            $pu_ttc = price2num(GETPOST('price_ttc'), 'MU');
            $tva_npr = (preg_match('/\*/', $tva_tx) ? 1 : 0);
            $tva_tx = str_replace('*', '', $tva_tx);
            $label = (GETPOST('product_label') ? GETPOST('product_label') : '');
            $desc = $product_desc;
            $type = GETPOST('type');
        }

        // Margin
        $fournprice = (GETPOST('fournprice') ? GETPOST('fournprice') : '');
        $buyingprice = (GETPOST('buying_price') ? GETPOST('buying_price') : '');

        // Local Taxes
        $localtax1_tx = get_localtax($tva_tx, 1, $object->client);
        $localtax2_tx = get_localtax($tva_tx, 2, $object->client);

        $desc = dol_htmlcleanlastbr($desc);

        $info_bits = 0;
        if ($tva_npr)
            $info_bits |= 0x01;

        if (!empty($price_min) && (price2num($pu_ht) * (1 - price2num(GETPOST('remise_percent')) / 100) < price2num($price_min))) {
            $mesg = $langs->trans("CantBeLessThanMinPrice", price2num($price_min, 'MU') . getCurrencySymbol($conf->currency));
            setEventMessage($mesg, 'errors');
        } else {
            // Insert line
            $result = $object->addline(
                    $object->id, $desc, $pu_ht, GETPOST('qty'), $tva_tx, $localtax1_tx, $localtax2_tx, $idprod, GETPOST('remise_percent'), $info_bits, 0, $price_base_type, $pu_ttc, $date_start, $date_end, $type, -1, 0, GETPOST('fk_parent_line'), $fournprice, $buyingprice, $label
            );

            if ($result > 0) {
                if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
                    // Define output language
                    $outputlangs = $langs;
                    $newlang = GETPOST('lang_id', 'alpha');
                    if (!empty($conf->global->MAIN_MULTILANGS) && empty($newlang))
                        $newlang = $object->client->default_lang;
                    if (!empty($newlang)) {
                        $outputlangs = new Translate("", $conf);
                        $outputlangs->setDefaultLang($newlang);
                    }

                    $ret = $object->fetch($object->id);    // Reload to get new records
                    propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
                }

                unset($_POST['qty']);
                unset($_POST['type']);
                unset($_POST['idprod']);
                unset($_POST['remise_percent']);
                unset($_POST['price_ht']);
                unset($_POST['price_ttc']);
                unset($_POST['tva_tx']);
                unset($_POST['product_ref']);
                unset($_POST['product_label']);
                unset($_POST['product_desc']);
                unset($_POST['fournprice']);
                unset($_POST['buying_price']);

                // old method
                unset($_POST['np_desc']);
                unset($_POST['dp_desc']);
            } else {
                setEventMessage($object->error, 'errors');
            }
        }
    }
} else if ($action == 'updateligne' && $user->rights->propal->creer && GETPOST('save') == $langs->trans('Save')) {
    // Clean parameters
    $date_start = '';
    $date_end = '';
    $date_start = dol_mktime(0, 0, 0, GETPOST('date_startmonth'), GETPOST('date_startday'), GETPOST('date_startyear'));
    $date_end = dol_mktime(0, 0, 0, GETPOST('date_endmonth'), GETPOST('date_endday'), GETPOST('date_endyear'));
    $description = dol_htmlcleanlastbr(GETPOST('product_desc'));
    $pu_ht = GETPOST('price_ht');

    // Define info_bits
    $info_bits = 0;
    if (preg_match('/\*/', GETPOST('tva_tx')))
        $info_bits |= 0x01;

    // Define vat_rate
    $vat_rate = GETPOST('tva_tx');
    $vat_rate = str_replace('*', '', $vat_rate);
    $localtax1_rate = get_localtax($vat_rate, 1, $object->client);
    $localtax2_rate = get_localtax($vat_rate, 2, $object->client);

    // Add buying price
    $fournprice = (GETPOST('fournprice') ? GETPOST('fournprice') : '');
    $buyingprice = (GETPOST('buying_price') ? GETPOST('buying_price') : '');

    // Check minimum price
    $productid = GETPOST('productid', 'int');
    if (!empty($productid)) {
        $product = new Product($db);
        $product->fetch($productid);

        $type = $product->type;

        $price_min = $product->price_min;
        if (!empty($conf->global->PRODUIT_MULTIPRICES) && !empty($object->client->price_level))
            $price_min = $product->multiprices_min[$object->client->price_level];

        $label = ((GETPOST('update_label') && GETPOST('product_label')) ? GETPOST('product_label') : '');

        if ($price_min && (price2num($pu_ht) * (1 - price2num(GETPOST('remise_percent')) / 100) < price2num($price_min))) {
            setEventMessage($langs->trans("CantBeLessThanMinPrice", price2num($price_min, 'MU')) . getCurrencySymbol($conf->currency), 'errors');
            $error++;
        }
    } else {
        $type = GETPOST('type');
        $label = (GETPOST('product_label') ? GETPOST('product_label') : '');

        // Check parameters
        if (GETPOST('type') < 0) {
            setEventMessage($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Type")), 'errors');
            $error++;
        }
    }

    if (!$error) {
        $result = $object->updateline(
                $id, GETPOST('lineid'), $description, $pu_ht, GETPOST('qty'), GETPOST('remise_percent'), $vat_rate, $localtax1_rate, $localtax2_rate, 'HT', $info_bits, $date_start, $date_end, $type, GETPOST('fk_parent_line'), 0, $fournprice, $buyingprice, $label
        );

        if ($result >= 0) {
            if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
                // Define output language
                $outputlangs = $langs;
                $newlang = '';
                if ($conf->global->MAIN_MULTILANGS && empty($newlang) && GETPOST('lang_id'))
                    $newlang = GETPOST('lang_id');
                if ($conf->global->MAIN_MULTILANGS && empty($newlang))
                    $newlang = $object->client->default_lang;
                if (!empty($newlang)) {
                    $outputlangs = new Translate("", $conf);
                    $outputlangs->setDefaultLang($newlang);
                }

                $ret = $object->fetch($object->id);    // Reload to get new records
                propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
            }

            unset($_POST['qty']);
            unset($_POST['type']);
            unset($_POST['productid']);
            unset($_POST['remise_percent']);
            unset($_POST['price_ht']);
            unset($_POST['price_ttc']);
            unset($_POST['tva_tx']);
            unset($_POST['product_ref']);
            unset($_POST['product_label']);
            unset($_POST['product_desc']);
            unset($_POST['fournprice']);
            unset($_POST['buying_price']);
        } else {
            setEventMessage($object->error, 'errors');
        }
    }
} else if ($action == 'confirm_deleteline' && $confirm == 'yes' && $user->rights->propal->creer) {
    $result = $object->deleteline($lineid);
    if ($result > 0) {
        // Define output language
        $outputlangs = $langs;
        $newlang = '';
        if ($conf->global->MAIN_MULTILANGS && empty($newlang) && GETPOST('lang_id'))
            $newlang = GETPOST('lang_id');
        if ($conf->global->MAIN_MULTILANGS && empty($newlang))
            $newlang = $object->client->default_lang;
        if (!empty($newlang)) {
            $outputlangs = new Translate("", $conf);
            $outputlangs->setDefaultLang($newlang);
        }
        if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
            $ret = $object->fetch($object->id);    // Reload to get new records
            propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
        }

        header('Location: ' . $_SERVER["PHP_SELF"] . '?id=' . $object->id);
        exit;
    } else {
        $mesg = '<div class="error">' . $object->error . '</div>';
    }
} else if ($action == 'builddoc') { // In get or post
    /*
     * Generate order document
     * define into /core/modules/commande/modules_commande.php
     */

    // Sauvegarde le dernier modele choisi pour generer un document
    if ($_REQUEST['model']) {
        $object->setDocModel($user, $_REQUEST['model']);
    }

    // Define output language
    $outputlangs = $langs;
    $newlang = '';
    if ($conf->global->MAIN_MULTILANGS && empty($newlang) && !empty($_REQUEST['lang_id']))
        $newlang = $_REQUEST['lang_id'];
    if ($conf->global->MAIN_MULTILANGS && empty($newlang))
        $newlang = $object->client->default_lang;
    if (!empty($newlang)) {
        $outputlangs = new Translate("", $conf);
        $outputlangs->setDefaultLang($newlang);
    }
    $result = propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);

    if ($result <= 0) {
        dol_print_error($db, $result);
        exit;
    } else {
        header('Location: ' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . (empty($conf->global->MAIN_JUMP_TAG) ? '' : '#builddoc'));
        exit;
    }
} else if ($action == 'confirm_validate' && $confirm == 'yes' && $user->rights->propal->valider) {
    $idwarehouse = GETPOST('idwarehouse');

    // Check parameters
    if (!empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $object->hasProductsOrServices(1)) {
        if (!$idwarehouse || $idwarehouse == -1) {
            $error++;
            $mesgs[] = '<div class="error">' . $langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv("Warehouse")) . '</div>';
            $action = '';
        }
    }

    if (!$error) {
        $result = $object->valid($user, $idwarehouse);
        if ($result >= 0) {
            // Define output language
            $outputlangs = $langs;
            $newlang = '';
            if ($conf->global->MAIN_MULTILANGS && empty($newlang) && !empty($_REQUEST['lang_id']))
                $newlang = $_REQUEST['lang_id'];
            if ($conf->global->MAIN_MULTILANGS && empty($newlang))
                $newlang = $object->client->default_lang;
            if (!empty($newlang)) {
                $outputlangs = new Translate("", $conf);
                $outputlangs->setDefaultLang($newlang);
            }
            if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE))
                propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
        }
    }
}


else if ($action == 'confirm_modif' && $user->rights->propal->creer) {
    $idwarehouse = GETPOST('idwarehouse');

    // Check parameters
    if (!empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $object->hasProductsOrServices(1)) {
        if (!$idwarehouse || $idwarehouse == -1) {
            $error++;
            $mesgs[] = '<div class="error">' . $langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv("Warehouse")) . '</div>';
            $action = '';
        }
    }

    if (!$error) {
        $result = $object->set_draft($user, $idwarehouse);
        if ($result >= 0) {
            // Define output language
            $outputlangs = $langs;
            $newlang = '';
            if ($conf->global->MAIN_MULTILANGS && empty($newlang) && !empty($_REQUEST['lang_id']))
                $newlang = $_REQUEST['lang_id'];
            if ($conf->global->MAIN_MULTILANGS && empty($newlang))
                $newlang = $object->client->default_lang;
            if (!empty($newlang)) {
                $outputlangs = new Translate("", $conf);
                $outputlangs->setDefaultLang($newlang);
            }
            if (empty($conf->global->MAIN_DISABLE_PDF_AUTOUPDATE)) {
                $ret = $object->fetch($object->id);    // Reload to get new records
                propale_pdf_create($db, $object, $object->modelpdf, $outputlangs, $hidedetails, $hidedesc, $hideref, $hookmanager);
            }
        }
    }
} else if ($action == 'confirm_cancel' && $confirm == 'yes' && $user->rights->propal->valider) {
    $idwarehouse = GETPOST('idwarehouse');

    // Check parameters
    if (!empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $object->hasProductsOrServices(1)) {
        if (!$idwarehouse || $idwarehouse == -1) {
            $error++;
            $mesgs[] = '<div class="error">' . $langs->trans('ErrorFieldRequired', $langs->transnoentitiesnoconv("Warehouse")) . '</div>';
            $action = '';
        }
    }

    if (!$error) {
        $result = $object->cancel($idwarehouse);
    }
} else if ($action == 'confirm_shipped' && $confirm == 'yes' && $user->rights->propal->cloturer) {
    $result = $object->cloture($user);
    if ($result < 0)
        $mesgs = $object->errors;
}



// Reopen a closed order
else if ($action == 'reopen' && $user->rights->propal->creer) {
    if ($object->Status == "TO_BILL" || $object->Status == "PROCESSED") {
        $result = $object->set_reopen($user);
        if ($result > 0) {
            header('Location: ' . $_SERVER["PHP_SELF"] . '?id=' . $object->id);
            exit;
        } else {
            $mesg = '<div class="error">' . $object->error . '</div>';
        }
    }
} else if ($action == 'classifybilled' && $user->rights->propal->creer) {
    $ret = $object->classifyBilled();
}



/* View ********************************************************************* */

$form = new Form($db);
$formfile = new FormFile($db);
$formpropal = new FormPropal($db);

llxHeader('', $title, 'EN:Customers_Orders|FR:Commandes_Clients|ES:Pedidos de clientes');
print_fiche_titre($title);

$formconfirm = null;

if ($action == 'delete') {
    $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeletePropal'), $langs->trans('ConfirmDeletePropal'), 'confirm_delete', '', 0, 1);
} else if ($action == 'ask_deleteline') {
    $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id . '&lineid=' . $lineid, $langs->trans('DeleteProductLine'), $langs->trans('ConfirmDeleteProductLine'), 'confirm_deleteline', '', 0, 1);
} else if ($action == 'validate') {
    // on verifie si l'objet est en numerotation provisoire
    $numref = $object->ref;
    $text = $langs->trans('ConfirmValidatePropal', $numref);
    if (!empty($conf->notification->enabled)) {
        require_once DOL_DOCUMENT_ROOT . '/core/class/notify.class.php';
        $notify = new Notify($db);
        $text.='<br>';
        $text.=$notify->confirmMessage('NOTIFY_VAL_PROPAL', $object->socid);
    }
    $formquestion = array();
    if (!empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $object->hasProductsOrServices(1)) {
        $langs->load("stocks");
        require_once DOL_DOCUMENT_ROOT . '/product/class/html.formproduct.class.php';
        $formproduct = new FormProduct($db);
        $formquestion = array(
            //'text' => $langs->trans("ConfirmClone"),
            //array('type' => 'checkbox', 'name' => 'clone_content',   'label' => $langs->trans("CloneMainAttributes"),   'value' => 1),
            //array('type' => 'checkbox', 'name' => 'update_prices',   'label' => $langs->trans("PuttingPricesUpToDate"),   'value' => 1),
            array('type' => 'other', 'name' => 'idwarehouse', 'label' => $langs->trans("SelectWarehouseForStockDecrease"), 'value' => $formproduct->selectWarehouses(GETPOST('idwarehouse'), 'idwarehouse', '', 1)));
    }

    $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('ValidateOrder'), $text, 'confirm_validate', $formquestion, 0, 1, 220);
} else if ($action == 'modify') {
    $text = $langs->trans('ConfirmUnvalidateOrder', $object->ref);
    $formquestion = array();
    if (!empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $object->hasProductsOrServices(1)) {
        $langs->load("stocks");
        require_once DOL_DOCUMENT_ROOT . '/product/class/html.formproduct.class.php';
        $formproduct = new FormProduct($db);
        $formquestion = array(
            //'text' => $langs->trans("ConfirmClone"),
            //array('type' => 'checkbox', 'name' => 'clone_content',   'label' => $langs->trans("CloneMainAttributes"),   'value' => 1),
            //array('type' => 'checkbox', 'name' => 'update_prices',   'label' => $langs->trans("PuttingPricesUpToDate"),   'value' => 1),
            array('type' => 'other', 'name' => 'idwarehouse', 'label' => $langs->trans("SelectWarehouseForStockIncrease"), 'value' => $formproduct->selectWarehouses(GETPOST('idwarehouse'), 'idwarehouse', '', 1)));
    }

    $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('UnvalidateOrder'), $text, 'confirm_modif', $formquestion, "yes", 1, 220);
} else if ($action == 'cancel') {
    $text = $langs->trans('ConfirmCancelOrder', $object->ref);
    $formquestion = array();
    if (!empty($conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER) && $object->hasProductsOrServices(1)) {
        $langs->load("stocks");
        require_once DOL_DOCUMENT_ROOT . '/product/class/html.formproduct.class.php';
        $formproduct = new FormProduct($db);
        $formquestion = array(
            //'text' => $langs->trans("ConfirmClone"),
            //array('type' => 'checkbox', 'name' => 'clone_content',   'label' => $langs->trans("CloneMainAttributes"),   'value' => 1),
            //array('type' => 'checkbox', 'name' => 'update_prices',   'label' => $langs->trans("PuttingPricesUpToDate"),   'value' => 1),
            array('type' => 'other', 'name' => 'idwarehouse', 'label' => $langs->trans("SelectWarehouseForStockIncrease"), 'value' => $formproduct->selectWarehouses(GETPOST('idwarehouse'), 'idwarehouse', '', 1)));
    }

    $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('Cancel'), $text, 'confirm_cancel', $formquestion, 0, 1);
} else if ($action == 'shipped') {
    $formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('CloseOrder'), $langs->trans('ConfirmCloseOrder'), 'confirm_shipped', '', 0, 1);
}


print $formconfirm;

print '<div class="with-padding" >';
print '<div class="columns" >';


/* Create View */


if (($action == 'create' || $action == 'edit') && $user->rights->propal->creer) {

    print start_box($title, "twelve", $object->fk_extrafields->ico, false);
    print '<form name="crea_propal" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<input type="hidden" name="action" value="' . ($action == 'create' ? 'add' : 'update') . '">';
    print '<input type="hidden" name="id" value="' . $id . '">';
    print '<input type="hidden" name="origin" value="' . $origin . '">';
    print '<input type="hidden" name="originid" value="' . $originid . '">';

    print '<table class="border" width="100%">';

    // Reference
    print '<tr><td class="fieldrequired">' . $langs->trans('Ref') . '</td><td colspan="2">' . ($action == 'edit' ? $object->ref : $langs->trans("Draft")) . '</td></tr>';

    // Reference client
    print '<tr><td>' . $langs->trans('RefCustomer') . '</td><td colspan="2">';
    print '<input type="text" name="ref_client" value="' . $object->ref_client . '"></td>';
    print '</tr>';

    // Client
    print '<tr><td class="fieldrequired">' . $langs->trans('Customer') . '</td><td colspan="2">' . $form->select_company($object->socid, "socid") . '</td></tr>';

    // Contact de la commande
//    print "<tr><td>" . $langs->trans("DefaultContact") . '</td><td colspan="2">';
//    $form->select_contacts($soc->id, $setcontact, 'contactidp', 1, $srccontactslist);
//    print '</td></tr>';

    // Date
    print '<tr><td class="fieldrequired">' . $langs->trans('Date') . '</td><td colspan="2">';
    $form->select_date($object->date, 're', '', '', '', "crea_commande", 1, 1);
    print '</td></tr>';

    // Validaty duration
    print '<tr><td class="fieldrequired">' . $langs->trans("ValidityDuration") . '</td><td colspan="2"><input name="duree_validite" size="5" value="' . $conf->global->PROPALE_VALIDITY_DURATION . '"> ' . $langs->trans("days") . '</td></tr>';


    // Date de livraison
    print "<tr><td>" . $langs->trans("DeliveryDate") . '</td><td colspan="2">';
    if ($action == 'edit') {
        $datedelivery = $object->date_livraison;
    } else if (!empty($conf->global->DATE_LIVRAISON_WEEK_DELAY)) {
        $datedelivery = time() + ((7 * $conf->global->DATE_LIVRAISON_WEEK_DELAY) * 24 * 60 * 60);
    } else {
        $datedelivery = empty($conf->global->MAIN_AUTOFILL_DATE) ? -1 : 0;
    }
    $form->select_date($datedelivery, 'liv_', '', '', '', "crea_commande", 1, 1);
    print "</td></tr>";

    // Conditions de reglement
    print '<tr><td nowrap="nowrap">' . $langs->trans('PaymentConditionsShort') . '</td><td colspan="2">';
    print $object->select_fk_extrafields('cond_reglement_code', 'cond_reglement_code', $object->cond_reglement_code);
    print '</td></tr>';

    // Delivery delay
    print '<tr><td>' . $langs->trans('AvailabilityPeriod') . '</td><td colspan="2">';
    print $object->select_fk_extrafields('availability_code', 'availability_code', $object->availability_code);
    print '</td></tr>';

    // Mode de reglement
    print '<tr><td nowrap="nowrap">' . $langs->trans('PaymentMode') . '</td><td colspan="2">';
    print $object->select_fk_extrafields('mode_reglement_code', 'mode_reglement_code', $object->mode_reglement_code);
    print '</td></tr>';

    // What trigger creation
    print '<tr><td>' . $langs->trans('Source') . '</td><td colspan="2">';
    print $object->select_fk_extrafields('demand_reason_code', 'demand_reason_code', $object->demand_reason_code);
    print '</td></tr>';

    // Project
    if (!empty($conf->projet->enabled)) {
        $projectid = 0;
        if ($origin == 'project')
            $projectid = ($originid ? $originid : 0);
        print '<tr><td>' . $langs->trans('Project') . '</td><td colspan="2">';
        $numprojet = select_projects($soc->id, $projectid);
        if ($numprojet == 0) {
            print ' &nbsp; <a href="' . DOL_URL_ROOT . '/projet/fiche.php?socid=' . $soc->id . '&action=create">' . $langs->trans("AddProject") . '</a>';
        }
        print '</td></tr>';
    }

    // Other attributes
    $parameters = array('objectsrc' => $objectsrc, 'colspan' => ' colspan="3"');
    $reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
    if (empty($reshook) && !empty($extrafields->attribute_label)) {
        foreach ($extrafields->attribute_label as $key => $label) {
            $value = (isset($_POST["options_" . $key]) ? $_POST["options_" . $key] : $object->array_options["options_" . $key]);
            print "<tr><td>" . $label . '</td><td colspan="3">';
            print $extrafields->showInputField($key, $value);
            print '</td></tr>' . "\n";
        }
    }

    // Template to use by default
    print '<tr><td>' . $langs->trans('Model') . '</td>';
    print '<td colspan="2">';
    include_once DOL_DOCUMENT_ROOT . '/propal/core/modules/propale/modules_propale.php';
    $liste = ModelePDFPropales::liste_modeles($db);
    print $form->selectarray('model', $liste, $conf->global->COMMANDE_ADDON_PDF);
    print "</td></tr>";

    // Note publique
    print '<tr>';
    print '<td class="border" valign="top">' . $langs->trans('NotePublic') . '</td>';
    print '<td valign="top" colspan="2">';
    $doleditor = new DolEditor('note_public', $object->note, '', 80, 'dolibarr_notes', 'In', 0, false, true, ROWS_3, 70);
    print $doleditor->Create(1);
    print '</td></tr>';

    // Note privee
    if (!$user->societe_id) {
        print '<tr>';
        print '<td class="border" valign="top">' . $langs->trans('NotePrivate') . '</td>';
        print '<td valign="top" colspan="2">';
        $doleditor = new DolEditor('note', $object->note, '', 80, 'dolibarr_notes', 'In', 0, false, true, ROWS_3, 70);
        print $doleditor->Create(1);
        print '</td></tr>';
    }

    print '</table>';

    // Button "Create Draft"
    print '<br><center><input type="submit" class="button" name="bouton" value="' . ($action == 'edit' ? $langs->trans('Modify') : $langs->trans('CreateDraft')) . '"></center>';

    print '</form>';
    print end_box();
}


/* Default View */ else {

    /*
     * Boutons actions
     */
    if ($action != 'presend') {
        if ($user->societe_id == 0 && $action <> 'editline') {
            print '<div class="tabsAction">';

            // Ship
            $numshipping = 0;
            if (!empty($conf->expedition->enabled)) {
                $numshipping = $object->nb_expedition();

                if ($object->statut > 0 && $object->statut < 3 && $object->getNbOfProductsLines() > 0) {
                    if (($conf->expedition_bon->enabled && $user->rights->expedition->creer)
                            || ($conf->livraison_bon->enabled && $user->rights->expedition->livraison->creer)) {
                        if ($user->rights->expedition->creer) {
                            print '<a class="butAction" href="' . DOL_URL_ROOT . '/expedition/shipment.php?id=' . $object->id . '">' . $langs->trans('ShipProduct') . '</a>';
                        } else {
                            print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("NotAllowed")) . '">' . $langs->trans('ShipProduct') . '</a>';
                        }
                    } else {
                        $langs->load("errors");
                        print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("ErrorModuleSetupNotComplete")) . '">' . $langs->trans('ShipProduct') . '</a>';
                    }
                }
            }

            // Create bill and Classify billed

            if (!empty($conf->facture->enabled) && $object->Status == "TO_BILL") {
                if ($user->rights->facture->creer && empty($conf->global->WORKFLOW_DISABLE_CREATE_INVOICE_FROM_ORDER)) {
                    print '<a class="butAction" href="' . DOL_URL_ROOT . '/compta/facture.php?action=create&amp;origin=' . $object->element . '&amp;originid=' . $object->id . '&amp;socid=' . $object->socid . '">' . $langs->trans("CreateBill") . '</a>';
                }
                if ($user->rights->propal->creer && $object->statut > 2 && empty($conf->global->WORKFLOW_DISABLE_CLASSIFY_BILLED_FROM_ORDER) && empty($conf->global->WORsKFLOW_BILL_ON_SHIPMENT)) {
                    print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=classifybilled">' . $langs->trans("ClassifyBilled") . '</a>';
                }
            }

            // Delete order
            if ($user->rights->propal->supprimer) {
                if ($numshipping == 0) {
                    print '<p class="button-height right">';
                    print '<a class="button icon-cross" href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&action=delete">' . $langs->trans("Delete") . '</a>';
                    print "</p>";
                } else {
                    print '<a class="butActionRefused" href="#" title="' . $langs->trans("ShippingExist") . '">' . $langs->trans("Delete") . '</a>';
                }
            }

            // Cancel order
            if ($object->Status == "VALIDATED" && $user->rights->propal->annuler) {
                print '<p class="button-height right">';
                print '<a class="button icon-lightning" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=cancel">' . $langs->trans('Cancel') . '</a>';
                print "</p>";
            }

            // Clone
            if ($user->rights->propal->creer) {
                print '<p class="button-height right">';
                print '<a class="button icon-pages" href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&action=clone">' . $langs->trans("ToClone") . '</a>';
                print "</p>";
            }

            // Create bill and Classify billed
//            if (!empty($conf->facture->enabled) && $object->Status == "TO_BILL") {
//                if ($user->rights->facture->creer && empty($conf->global->WORKFLOW_DISABLE_CREATE_INVOICE_FROM_ORDER)) {
//                    print '<a class="butAction" href="' . DOL_URL_ROOT . '/compta/facture.php?action=create&amp;origin=' . $object->element . '&amp;originid=' . $object->id . '&amp;socid=' . $object->socid . '">' . $langs->trans("CreateBill") . '</a>';
//                }
//                if ($user->rights->propal->creer && $object->statut > 2 && empty($conf->global->WORKFLOW_DISABLE_CLASSIFY_BILLED_FROM_ORDER) && empty($conf->global->WORsKFLOW_BILL_ON_SHIPMENT)) {
//                    print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=classifybilled">' . $langs->trans("ClassifyBilled") . '</a>';
//                }
//            }
            // Classify billed
            if ($object->Status == "TO_BILL" && $user->rights->propal->cloturer) {
                print '<p class="button-height right">';
                print '<a class="button icon-tick" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=classifybilled">' . $langs->trans('ClassifyBilled') . '</a>';
                print "</p>";
            }

            // Set to shipped (Close)
            if (($object->Status == "VALIDATED" || $object->Status == "IN_PROCESS") && $user->rights->propal->cloturer) {
                print '<p class="button-height right">';
                print '<a class="button icon-tick" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=shipped">' . $langs->trans('ClassifyShipped') . '</a>';
                print "</p>";
            }

            // Valid
            if ($object->Status == "DRAFT" && $object->total_ttc >= 0 && count($object->lines) > 0 && $user->rights->propal->valider) {
                print '<p class="button-height right">';
                print '<a class="button icon-tick" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=validate">' . $langs->trans('Validate') . '</a>';
                print "</p>";
            }

            // Create bill and Classify billed
            if (!empty($conf->facture->enabled) && !in_array($object->Status, array("DRAFT", "CANCELED", "PROCESSED"))) {
                if ($user->rights->facture->creer && empty($conf->global->WORKFLOW_DISABLE_CREATE_INVOICE_FROM_ORDER)) {
                    print '<p class="button-height right">';
                    print '<a class="button icon-folder" href="' . DOL_URL_ROOT . '/compta/facture.php?action=create&amp;origin=' . $object->element . '&amp;originid=' . $object->id . '&amp;socid=' . $object->socid . '">' . $langs->trans("CreateBill") . '</a>';
                    print "</p>";
                }
                if ($user->rights->propal->creer && $object->statut > 2 && empty($conf->global->WORKFLOW_DISABLE_CLASSIFY_BILLED_FROM_ORDER) && empty($conf->global->WORsKFLOW_BILL_ON_SHIPMENT)) {
                    print '<p class="button-height right">';
                    print '<a class="button icon-drawer" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=classifybilled">' . $langs->trans("ClassifyBilled") . '</a>';
                    print "</p>";
                }
            }

            // Send
            if ($object->Status != "DRAFT" && $object->Status != "CANCELED") {
                if ((empty($conf->global->MAIN_USE_ADVANCED_PERMS) || $user->rights->propal->order_advance->send)) {
                    print '<p class="button-height right">';
                    print '<a class="button icon-mail" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=presend&amp;mode=init">' . $langs->trans('SendByMail') . '</a>';
                    print "</p>";
                } else {
                    print '<p class="button-height right">';
                    print '<a class="button icon-mail" href="#">' . $langs->trans('SendByMail') . '</a>';
                    print "</p>";
                }
            }

            // Reopen a closed order
            if (($object->Status == "TO_BILL" || $object->Status == "PROCESSED") && $user->rights->propal->creer) {
                print '<p class="button-height right">';
                print '<a class="button icon-reply" href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&amp;action=reopen">' . $langs->trans('ReOpen') . '</a>';
                print "</p>";
            }

            // Edit
            if ($object->Status == "VALIDATED" && $user->rights->propal->creer) {
                print '<p class="button-height right">';
                print '<a class="button icon-pencil" href="' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=modify">' . $langs->trans("Modify") . '</a>';
                print "</p>";
            }

            print '</div>';
        }
        print '<br>';
    }

    print start_box($title, "twelve", $object->fk_extrafields->ico, false);
    print '<table class="border" width="100%">';

    // Ref
    print '<tr><td width="18%">' . $langs->trans('Ref') . '</td>';
    print '<td colspan="3">';
    print $object->ref;
    print '</td>';
    print '</tr>';

    // Ref commande client
    print '<tr><td>' . $langs->trans('RefCustomer') . '</td>';
    print '<td colspan="3">' . $object->ref_client . '</td>';
    print '</tr>';

    // Societe
    print '<tr><td>' . $langs->trans('Company') . '</td>';
    print '<td colspan="3">' . $object->client->name . '</td>';
    print '</tr>';

    // Date
    print '<tr><td>' . $langs->trans('Date') . '</td>';
    print '<td colspan="3">' . ($object->date ? dol_print_date($object->date, 'daytext') : '&nbsp;') . '</td>';
    print '</tr>';

    // Durée de validité
    print '<tr><td>' . $langs->trans('ValidityDuration') . '</td>';
    print '<td colspan="3">' . $object->duree_validite . ' '. $langs->trans('days') . '</td>';
    print '</tr>';

    
    // Delivery date planed
    print '<tr><td>' . $langs->trans('DateDeliveryPlanned') . '</td>';
    print '<td colspan="3">' . ($object->date_livraison ? dol_print_date($object->date_livraison, 'daytext') : '&nbsp;') . '</td>';
    print '</tr>';

    // Terms of payment
    print '<tr><td>' . $langs->trans('PaymentConditionsShort') . '</td>';
    print '<td colspan="3">' . $object->getExtraFieldLabel('cond_reglement_code') . '</td>';
    print '</tr>';

    // Mode of payment
    print '<tr><td>' . $langs->trans('PaymentMode') . '</td>';
    print '<td colspan="3">' . $object->getExtraFieldLabel('mode_reglement_code') . '</td>';
    print '</tr>';

    // Availability
    print '<tr><td>' . $langs->trans('AvailabilityPeriod') . '</td>';
    print '<td colspan="3">' . $object->getExtraFieldLabel('availability_code') . '</td>';
    print '</tr>';

    // Source
    print '<tr><td>' . $langs->trans('Source') . '</td>';
    print '<td colspan="3">' . $object->getExtraFieldLabel('demand_reason_code') . '</td>';
    print '</tr>';

    // Total HT
    print '<tr><td>' . $langs->trans('AmountHT') . '</td>';
    print '<td align="right"><b>' . price($object->total_ht) . '</b></td>';
    print '<td>' . $langs->trans('Currency' . $conf->currency) . '</td>';

    // Margin Infos
    if (!empty($conf->margin->enabled)) {
        print '<td valign="top" width="50%" rowspan="4">';
        $object->displayMarginInfos();
        print '</td>';
    }
    print '</tr>';

    // Total TVA
    print '<tr><td>' . $langs->trans('AmountVAT') . '</td><td align="right">' . price($object->total_tva) . '</td>';
    print '<td>' . $langs->trans('Currency' . $conf->currency) . '</td></tr>';

    // Amount Local Taxes
    if ($mysoc->country_code == 'ES') {
        if ($mysoc->localtax1_assuj == "1") { //Localtax1 RE
            print '<tr><td>' . $langs->transcountry("AmountLT1", $mysoc->country_code) . '</td>';
            print '<td align="right">' . price($object->total_localtax1) . '</td>';
            print '<td>' . $langs->trans("Currency" . $conf->currency) . '</td></tr>';
        }
        if ($mysoc->localtax2_assuj == "1") { //Localtax2 IRPF
            print '<tr><td>' . $langs->transcountry("AmountLT2", $mysoc->country_code) . '</td>';
            print '<td align="right">' . price($object->total_localtax2) . '</td>';
            print '<td>' . $langs->trans("Currency" . $conf->currency) . '</td></tr>';
        }
    }

    // Total TTC
    print '<tr><td>' . $langs->trans('AmountTTC') . '</td><td align="right">' . price($object->total_ttc) . '</td>';
    print '<td>' . $langs->trans('Currency' . $conf->currency) . '</td></tr>';

    // Statut
    print '<tr><td>' . $langs->trans('Status') . '</td>';
    print '<td colspan="2">' . $object->getExtraFieldLabel('Status') . '</td>';
    print '</tr>';


    print '</table>';
    print end_box();

    if (!empty($conf->global->MAIN_DISABLE_CONTACTS_TAB)) {
        $blocname = 'contacts';
        $title = $langs->trans('ContactsAddresses');
        include DOL_DOCUMENT_ROOT . '/core/tpl/bloc_showhide.tpl.php';
    }

    if (!empty($conf->global->MAIN_DISABLE_NOTES_TAB)) {
        $blocname = 'notes';
        $title = $langs->trans('Notes');
        include DOL_DOCUMENT_ROOT . '/core/tpl/bloc_showhide.tpl.php';
    }

    // Actions
    if ($object->Status == "DRAFT" && $user->rights->propal->creer) {
        print '<p class="button-height right">';
        print '<a class="button icon-pencil" href="' . $_SERVER['PHP_SELF'] . '?id=' . $id . '&action=edit">' . $langs->trans("Edit") . '</a>';
        print "</p>";
    }

    // Lines

    print start_box($langs->trans('PropalLines'), "six", $object->fk_extrafields->ico, false);
    print '<table id="tablelines" class="noborder" width="100%">';

    $object->getLinesArray();
    $nbLines = count($object->lines);

    // Show object lines
    if (!empty($object->lines))
        $ret = $object->printObjectLines($action, $mysoc, $soc, $lineid, 1, $hookmanager);

    // Form to add new line

    if ($object->Status == "DRAFT" && $user->rights->propal->creer) {
        if ($action != 'editline') {
            $var = true;

            if ($conf->global->MAIN_FEATURES_LEVEL > 1) {
                // Add free or predefined products/services
                $object->formAddObjectLine(1, $mysoc, $soc, $hookmanager);
            } else {
                // Add free products/services
                $object->formAddFreeProduct(1, $mysoc, $soc, $hookmanager);

                // Add predefined products/services
                if (!empty($conf->product->enabled) || !empty($conf->service->enabled)) {
                    $var = !$var;
                    $object->formAddPredefinedProduct(1, $mysoc, $soc, $hookmanager);
                }
            }

            $parameters = array();
            $reshook = $hookmanager->executeHooks('formAddObjectLine', $parameters, $object, $action);    // Note that $action and $object may have been modified by hook
        }
    }
    print '</table>';
    print end_box();


    if ($action != 'presend') {
        print '<a name="builddoc"></a>'; // ancre

        /*
         * Documents generes
         *
         */
        $comref = dol_sanitizeFileName($object->ref);
        $file = $conf->propal->dir_output . '/' . $comref . '/' . $comref . '.pdf';
        $relativepath = $comref . '/' . $comref . '.pdf';
        $filedir = $conf->propal->dir_output . '/' . $comref;
        $urlsource = $_SERVER["PHP_SELF"] . "?id=" . $object->id;
        $genallowed = $user->rights->propal->creer;
        $delallowed = $user->rights->propal->supprimer;

        $somethingshown = $formfile->show_documents('propal', $comref, $filedir, $urlsource, $genallowed, $delallowed, $object->modelpdf, 1, 0, 0, 28, 0, '', '', '', $soc->default_lang, $hookmanager);

        /*
         * Linked object block
         */
        $somethingshown = $object->showLinkedObjectBlock();

        print '</td><td valign="top" width="50%">';

        // List of actions on element
//				include_once DOL_DOCUMENT_ROOT.'/core/class/html.formactions.class.php';
//				$formactions=new FormActions($db);
//				$somethingshown=$formactions->showactions($object,'order',$socid);

        print '</td></tr>';
    }
}

print '</div>';
print '</div>';
llxFooter();
?>
