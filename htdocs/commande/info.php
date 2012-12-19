<?php
/* Copyright (C) 2005-2006 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \file       htdocs/commande/info.php
 *      \ingroup    commande
 *		\brief      Page des informations d'une commande
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/commande/class/commande.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/order.lib.php';

if (!$user->rights->commande->lire)	accessforbidden();

$langs->load("orders");
$langs->load("sendings");

// Security check
$socid=0;
$comid = isset($_GET["id"])?$_GET["id"]:'';
if ($user->societe_id) $socid=$user->societe_id;
$result=restrictedArea($user,'commande',$comid,'');



/*
 * View
 */

llxHeader('',$langs->trans('Order'),'EN:Customers_Orders|FR:Commandes_Clients|ES:Pedidos de clientes');

$commande = new Commande($db);
$commande->fetch($_GET["id"]);
$commande->info($_GET["id"]);
$soc = new Societe($db);
$soc->fetch($commande->socid);

$head = commande_prepare_head($commande);
dol_fiche_head($head, 'info', $langs->trans("CustomerOrder"), 0, 'order');


print '<table width="100%"><tr><td>';
dol_print_object_info($commande);
print '</td></tr></table>';

print '</div>';


$db->close();

llxFooter();
?>
