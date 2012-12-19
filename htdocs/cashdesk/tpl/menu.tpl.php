<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier      <jeremie.o@laposte.net>
 * Copyright (C) 2008-2010 Laurent Destailleur   <eldy@uers.sourceforge.net>
 * Copyright (C) 2009      Regis Houssin         <regis@dolibarr.fr>
 * Copyright (C) 2011      Juanjo Menent         <jmenent@2byte.es>
 * Copyright (C) 2012      Marcos García         <marcosgdf@gmail.com>
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

include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
include_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
include_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';

if (!empty($_SESSION["CASHDESK_ID_THIRDPARTY"]))
{
	$company=new Societe($db);
	$company->fetch($_SESSION["CASHDESK_ID_THIRDPARTY"]);
	$companyLink = $company->getNomUrl(1);
}
if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"]))
{
	$bankcash=new Account($db);
	$bankcash->fetch($_SESSION["CASHDESK_ID_BANKACCOUNT_CASH"]);
	$bankcash->label=$bankcash->ref;
	$bankcashLink = $bankcash->getNomUrl(1);
}
if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CB"]))
{
	$bankcb=new Account($db);
	$bankcb->fetch($_SESSION["CASHDESK_ID_BANKACCOUNT_CB"]);
	$bankcbLink = $bankcb->getNomUrl(1);
}
if (!empty($_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"]))
{
	$bankcheque=new Account($db);
	$bankcheque->fetch($_SESSION["CASHDESK_ID_BANKACCOUNT_CHEQUE"]);
	$bankchequeLink = $bankcheque->getNomUrl(1);
}
if (!empty($_SESSION["CASHDESK_ID_WAREHOUSE"]) && ! empty($conf->stock->enabled))
{
	$warehouse=new Entrepot($db);
	$warehouse->fetch($_SESSION["CASHDESK_ID_WAREHOUSE"]);
	$warehouseLink = $warehouse->getNomUrl(1);
}


$langs->load("cashdesk");
$langs->load("main");

print '<div class="menu_bloc">';
print '<ul class="menu">';
// Link to new sell
print '<li class="menu_choix1"><a href="affIndex.php?menu=facturation&id=NOUV"><span>'.$langs->trans("NewSell").'</span></a></li>';
// Open new tab on backoffice (this is not a disconnect from POS)
print '<li class="menu_choix2"><a href=".." target="backoffice"><span>'.$langs->trans("BackOffice").'</span></a></li>';
// Disconnect
print '<li class="menu_choix0">'.$langs->trans("User").': '.$_SESSION['prenom'].' '.$_SESSION['nom'];
print ' <a href="deconnexion.php">'.img_picto($langs->trans('Logout'), 'logout.png').'</a><br>';
print $langs->trans("CashDeskThirdParty").': '.$companyLink.'<br>';
/*print $langs->trans("CashDeskBankCash").': '.$bankcashLink.'<br>';
print $langs->trans("CashDeskBankCB").': '.$bankcbLink.'<br>';
print $langs->trans("CashDeskBankCheque").': '.$bankchequeLink.'<br>';*/
if (!empty($_SESSION["CASHDESK_ID_WAREHOUSE"]) && ! empty($conf->stock->enabled))
{
	print $langs->trans("CashDeskWarehouse").': '.$warehouseLink;
}
print '</li></ul>';
print '</div>';
?>