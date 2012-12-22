<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2005 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \file       htdocs/compta/facture/info.php
 *      \ingroup    facture
 *		\brief      Page des informations d'une facture
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/discount.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/invoice.lib.php';

$langs->load("bills");


/*
 * View
 */

llxHeader();

$fac = new Facture($db);
$fac->fetch($_GET["facid"]);
$fac->info($_GET["facid"]);

$soc = new Societe($db);
$soc->fetch($fac->socid);

$head = facture_prepare_head($fac);
dol_fiche_head($head, 'info', $langs->trans("InvoiceCustomer"), 0, 'bill');


print '<table width="100%"><tr><td>';
dol_print_object_info($fac);
print '</td></tr></table>';

print '</div>';

$db->close();

llxFooter();
?>
