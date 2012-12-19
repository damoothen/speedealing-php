<?php
/* Copyright (C) 2005-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	\file       htdocs/product/stock/info.php
 *	\ingroup    stock
 *	\brief      Page des informations d'un entrepot
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/stock.lib.php';

$langs->load("stocks");

/*
 * View
 */

$help_url='EN:Module_Stocks_En|FR:Module_Stock|ES:M&oacute;dulo_Stocks';
llxHeader("",$langs->trans("Stocks"),$help_url);

$entrepot = new Entrepot($db);
$entrepot->fetch($_GET["id"]);
$entrepot->info($_GET["id"]);

$head = stock_prepare_head($entrepot);

dol_fiche_head($head, 'info', $langs->trans("Warehouse"), 0, 'stock');


print '<table width="100%"><tr><td>';
dol_print_object_info($entrepot);
print '</td></tr></table>';

print '</div>';

llxFooter();

$db->close();
?>
