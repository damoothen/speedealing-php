<?php
/* Copyright (C) 2006      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2008-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2012      Juanjo Menent		<jmenent@2byte.es>
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
 *	\file       htdocs/admin/stock.php
 *	\ingroup    stock
 *	\brief      Page d'administration/configuration du module gestion de stock
 */
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");
$langs->load("stocks");

// Securit check
if (!$user->admin) accessforbidden();

$action = GETPOST('action','alpha');


/*
 * Actions
 */

if ($action == 'STOCK_USERSTOCK_AUTOCREATE')
{
	$db->begin();
	$res = dolibarr_set_const($db, "STOCK_USERSTOCK_AUTOCREATE", GETPOST('STOCK_USERSTOCK_AUTOCREATE','alpha'),'chaine',0,'',$conf->entity);
}
// Mode of stock decrease
if ($action == 'STOCK_CALCULATE_ON_BILL'
|| $action == 'STOCK_CALCULATE_ON_VALIDATE_ORDER'
|| $action == 'STOCK_CALCULATE_ON_SHIPMENT')
{
	$db->begin();
	$res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_BILL", '','chaine',0,'',$conf->entity);
	$res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_VALIDATE_ORDER", '','chaine',0,'',$conf->entity);
	$res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SHIPMENT", '','chaine',0,'',$conf->entity);
	if ($action == 'STOCK_CALCULATE_ON_BILL')           $res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_BILL", GETPOST('STOCK_CALCULATE_ON_BILL','alpha'),'chaine',0,'',$conf->entity);
	if ($action == 'STOCK_CALCULATE_ON_VALIDATE_ORDER') $res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_VALIDATE_ORDER", GETPOST('STOCK_CALCULATE_ON_VALIDATE_ORDER','alpha'),'chaine',0,'',$conf->entity);
	if ($action == 'STOCK_CALCULATE_ON_SHIPMENT')       $res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SHIPMENT", GETPOST('STOCK_CALCULATE_ON_SHIPMENT','alpha'),'chaine',0,'',$conf->entity);
}
// Mode of stock increase
if ($action == 'STOCK_CALCULATE_ON_SUPPLIER_BILL'
|| $action == 'STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER'
|| $action == 'STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER')
{
	$db->begin();
	$res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SUPPLIER_BILL", '','chaine',0,'',$conf->entity);
	$res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER", '','chaine',0,'',$conf->entity);
	$res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER", '','chaine',0,'',$conf->entity);
	if ($action == 'STOCK_CALCULATE_ON_SUPPLIER_BILL')           $res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SUPPLIER_BILL", GETPOST('STOCK_CALCULATE_ON_SUPPLIER_BILL','alpha'),'chaine',0,'',$conf->entity);
	if ($action == 'STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER') $res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER", GETPOST('STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER','alpha'),'chaine',0,'',$conf->entity);
	if ($action == 'STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER') $res=dolibarr_set_const($db, "STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER", GETPOST('STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER','alpha'),'chaine',0,'',$conf->entity);
}

if($action)
{
	if (! $res > 0) $error++;

 	if (! $error)
    {
    	$db->commit();
        $mesg = "<font class=\"ok\">".$langs->trans("SetupSaved")."</font>";
    }
    else
    {
    	$db->rollback();
        $mesg = "<font class=\"error\">".$langs->trans("Error")."</font>";
    }
}

/*
 * View
 */

llxHeader('',$langs->trans("StockSetup"));

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("StockSetup"),$linkback,'setup');
print '<br>';

$form=new Form($db);
$var=true;
print '<table class="noborder" width="100%">';

print '<tr class="liste_titre">';
print "  <td>".$langs->trans("Parameters")."</td>\n";
print "  <td align=\"right\" width=\"160\">&nbsp;</td>\n";
print '</tr>'."\n";

/*
 * Formulaire parametres divers
 */

$var=!$var;

print "<tr ".$bc[$var].">";
print '<td width="60%">'.$langs->trans("UserWarehouseAutoCreate").'</td>';

print '<td width="160" align="right">';
print "<form method=\"post\" action=\"stock.php\">";
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print "<input type=\"hidden\" name=\"action\" value=\"STOCK_USERSTOCK_AUTOCREATE\">";
print $form->selectyesno("STOCK_USERSTOCK_AUTOCREATE",$conf->global->STOCK_USERSTOCK_AUTOCREATE,1);
print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
print '</form>';
print "</td>\n";
print "</tr>\n";


// Title rule for stock decrease
print '<tr class="liste_titre">';
print "  <td>".$langs->trans("RuleForStockManagementDecrease")."</td>\n";
print "  <td align=\"right\" width=\"160\">&nbsp;</td>\n";
print '</tr>'."\n";
$var=true;

if (! empty($conf->facture->enabled))
{
	$var=!$var;
	print "<tr ".$bc[$var].">";
	print '<td width="60%">'.$langs->trans("DeStockOnBill").'</td>';
	print '<td width="160" align="right">';
	print "<form method=\"post\" action=\"stock.php\">";
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print "<input type=\"hidden\" name=\"action\" value=\"STOCK_CALCULATE_ON_BILL\">";
	print $form->selectyesno("STOCK_CALCULATE_ON_BILL",$conf->global->STOCK_CALCULATE_ON_BILL,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print "</form>\n</td>\n</tr>\n";
}

if (! empty($conf->commande->enabled))
{
	$var=!$var;
	print "<tr ".$bc[$var].">";
	print '<td width="60%">'.$langs->trans("DeStockOnValidateOrder").'</td>';
	print '<td width="160" align="right">';
	print "<form method=\"post\" action=\"stock.php\">";
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print "<input type=\"hidden\" name=\"action\" value=\"STOCK_CALCULATE_ON_VALIDATE_ORDER\">";
	print $form->selectyesno("STOCK_CALCULATE_ON_VALIDATE_ORDER",$conf->global->STOCK_CALCULATE_ON_VALIDATE_ORDER,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print "</form>\n</td>\n</tr>\n";
}

if (! empty($conf->expedition->enabled))
{
	$var=!$var;
	print "<tr ".$bc[$var].">";
	print '<td width="60%">'.$langs->trans("DeStockOnShipment").'</td>';
	print '<td width="160" align="right">';
	print "<form method=\"post\" action=\"stock.php\">";
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print "<input type=\"hidden\" name=\"action\" value=\"STOCK_CALCULATE_ON_SHIPMENT\">";
	print $form->selectyesno("STOCK_CALCULATE_ON_SHIPMENT",$conf->global->STOCK_CALCULATE_ON_SHIPMENT,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print "</form>\n</td>\n</tr>\n";
}


// Title rule for stock increase
print '<tr class="liste_titre">';
print "  <td>".$langs->trans("RuleForStockManagementIncrease")."</td>\n";
print "  <td align=\"right\" width=\"160\">&nbsp;</td>\n";
print '</tr>'."\n";
$var=true;

if (! empty($conf->fournisseur->enabled))
{
	$var=!$var;
	print "<tr ".$bc[$var].">";
	print '<td width="60%">'.$langs->trans("ReStockOnBill").'</td>';
	print '<td width="160" align="right">';
	print "<form method=\"post\" action=\"stock.php\">";
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print "<input type=\"hidden\" name=\"action\" value=\"STOCK_CALCULATE_ON_SUPPLIER_BILL\">";
	print $form->selectyesno("STOCK_CALCULATE_ON_SUPPLIER_BILL",$conf->global->STOCK_CALCULATE_ON_SUPPLIER_BILL,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print "</form>\n</td>\n</tr>\n";
}

if (! empty($conf->fournisseur->enabled))
{
	$var=!$var;
	print "<tr ".$bc[$var].">";
	print '<td width="60%">'.$langs->trans("ReStockOnValidateOrder").'</td>';
	print '<td width="160" align="right">';
	print "<form method=\"post\" action=\"stock.php\">";
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print "<input type=\"hidden\" name=\"action\" value=\"STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER\">";
	print $form->selectyesno("STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER",$conf->global->STOCK_CALCULATE_ON_SUPPLIER_VALIDATE_ORDER,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print "</form>\n</td>\n</tr>\n";
}
if (! empty($conf->fournisseur->enabled))
{
	$var=!$var;
	print "<tr ".$bc[$var].">";
	print '<td width="60%">'.$langs->trans("ReStockOnDispatchOrder").'</td>';
	print '<td width="160" align="right">';
	print "<form method=\"post\" action=\"stock.php\">";
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print "<input type=\"hidden\" name=\"action\" value=\"STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER\">";
	print $form->selectyesno("STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER",$conf->global->STOCK_CALCULATE_ON_SUPPLIER_DISPATCH_ORDER,1);
	print '<input type="submit" class="button" value="'.$langs->trans("Modify").'">';
	print "</form>\n</td>\n</tr>\n";
}

print '</table>';

dol_htmloutput_mesg($mesg);

$db->close();

llxFooter();
?>
