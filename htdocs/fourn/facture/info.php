<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2006 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
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
 *      \file       htdocs/fourn/facture/info.php
 *      \ingroup    facture, fournisseur
 *		\brief      Page des informations d'une facture fournisseur
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fourn.lib.php';

$langs->load('bills');

$facid = isset($_GET["facid"])?$_GET["facid"]:'';

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'fournisseur', $facid, 'facture_fourn', 'facture');



/*
 * View
 */

llxHeader();

$fac = new FactureFournisseur($db);
$fac->fetch($_GET["facid"]);
$fac->info($_GET["facid"]);
$soc = new Societe($db);
$soc->fetch($fac->socid);

$head = facturefourn_prepare_head($fac);
$titre=$langs->trans('SupplierInvoice');
dol_fiche_head($head, 'info', $langs->trans('SupplierInvoice'), 0, 'bill');

print '<table width="100%"><tr><td>';
dol_print_object_info($fac);
print '</td></tr></table>';

print '</div>';

$db->close();

llxFooter();
?>
