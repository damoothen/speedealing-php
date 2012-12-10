<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copytight (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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
 *       \file       htdocs/compta/bank/index.php
 *       \ingroup    banque
 *       \brief      Home page of bank module
 */

require 'pre.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/bank.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/tva/class/tva.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/sociales/class/chargesociales.class.php';

$langs->load("banks");
$langs->load("categories");

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result=restrictedArea($user,'banque');

$statut=GETPOST('statut');



/*
 * View
 */

$help_url='EN:Module_Banks_and_Cash|FR:Module_Banques_et_Caisses|ES:M&oacute;dulo_Bancos_y_Cajas';
llxHeader('',$langs->trans('AccountsArea'),$help_url);

$link='';
if ($statut == '') $link='<a href="'.$_SERVER["PHP_SELF"].'?statut=all">'.$langs->trans("IncludeClosedAccount").'</a>';
if ($statut == 'all') $link='<a href="'.$_SERVER["PHP_SELF"].'">'.$langs->trans("OnlyOpenedAccount").'</a>';
print_fiche_titre($langs->trans("AccountsArea"),$link);
print '<br>';


// On charge tableau des comptes financiers (ouverts par defaut)
$accounts = array();

$sql  = "SELECT rowid, courant, rappro";
$sql.= " FROM ".MAIN_DB_PREFIX."bank_account";
$sql.= " WHERE entity = ".$conf->entity;
if ($statut != 'all') $sql.= " AND clos = 0";
$sql.= $db->order('label', 'ASC');

$resql = $db->query($sql);
if ($resql)
{
	$num = $db->num_rows($resql);
	$i = 0;
	while ($i < $num)
	{
		$objp = $db->fetch_object($resql);
		$accounts[$objp->rowid] = $objp->courant;
		$i++;
	}
	$db->free($resql);
}


/*
 * Comptes courants (courant = 1)
 */
print '<table class="liste" width="100%">';
print '<tr class="liste_titre"><td width="30%">'.$langs->trans("CurrentAccounts").'</td>';
print '<td width="20%">'.$langs->trans("Bank").'</td>';
print '<td align="left">'.$langs->trans("AccountIdShort").'</td>';
print '<td align="center">'.$langs->trans("TransactionsToConciliate").'</td>';
print '<td align="center" width="70">'.$langs->trans("Status").'</td>';
print '<td align="right" width="100">'.$langs->trans("BankBalance").'</td>';
print "</tr>\n";

$total = 0; $found = 0;
$var=true;
foreach ($accounts as $key=>$type)
{
	if ($type == 1)
	{
	    $found++;

		$acc = new Account($db);
		$acc->fetch($key);

		$var = !$var;
		$solde = $acc->solde(1);

		print '<tr '.$bc[$var].'>';
		print '<td width="30%">'.$acc->getNomUrl(1).'</td>';
		print '<td>'.$acc->bank.'</td>';
		print '<td>'.$acc->number.'</td>';
		print '<td align="center">';
		if ($acc->rappro)
		{
			$result=$acc->load_board($user,$acc->id);
			print $acc->nbtodo;
			if ($acc->nbtodolate) print ' ('.$acc->nbtodolate.img_warning($langs->trans("Late")).')';
		}
		else print $langs->trans("FeatureDisabled");
		print '</td>';
		print '<td align="center">'.$acc->getLibStatut(2).'</td>';
		print '<td align="right">';
		print '<a href="account.php?account='.$acc->id.'">'.price($solde).'</a>';
		print '</td>';
		print '</tr>';

		$total += $solde;
	}
}
if (! $found) print '<tr '.$bc[$var].'><td colspan="6">'.$langs->trans("None").'</td></tr>';
// Total
print '<tr class="liste_total"><td colspan="5" class="liste_total">'.$langs->trans("Total").'</td><td align="right" class="liste_total">'.price($total).'</td></tr>';


//print '<tr><td colspan="5">&nbsp;</td></tr>';


/*
 * Comptes caisse/liquide (courant = 2)
 */
print '<tr class="liste_titre"><td width="30%">'.$langs->trans("CashAccounts").'</td><td width="20%">&nbsp;</td>';
print '<td align="left">&nbsp;</td>';
print '<td align="left" width="100">&nbsp;</td>';
print '<td align="center" width="70">'.$langs->trans("Status").'</td>';
print '<td align="right" width="100">'.$langs->trans("BankBalance").'</td>';
print "</tr>\n";

$total = 0; $found = 0;
$var=true;
foreach ($accounts as $key=>$type)
{
	if ($type == 2)
	{
	    $found++;

	    $acc = new Account($db);
		$acc->fetch($key);

		$var = !$var;
		$solde = $acc->solde(1);

		print "<tr ".$bc[$var].">";
		print '<td width="30%">'.$acc->getNomUrl(1).'</td>';
		print '<td>'.$acc->bank.'</td>';
		print '<td>&nbsp;</td>';
		print '<td>&nbsp;</td>';
		print '<td align="center">'.$acc->getLibStatut(2).'</td>';
		print '<td align="right">';
		print '<a href="account.php?account='.$acc->id.'">'.price($solde).'</a>';
		print '</td>';
		print '</tr>';

		$total += $solde;
	}
}
if (! $found) print '<tr '.$bc[$var].'><td colspan="6">'.$langs->trans("None").'</td></tr>';
// Total
print '<tr class="liste_total"><td colspan="5" class="liste_total">'.$langs->trans("Total").'</td><td align="right" class="liste_total">'.price($total).'</td></tr>';



//print '<tr><td colspan="5">&nbsp;</td></tr>';


/*
 * Comptes placements (courant = 0)
 */
print '<tr class="liste_titre">';
print '<td width="30%">'.$langs->trans("SavingAccounts").'</td>';
print '<td width="20%">'.$langs->trans("Bank").'</td>';
print '<td align="left">'.$langs->trans("Numero").'</td>';
print '<td align="center" width="100">'.$langs->trans("TransactionsToConciliate").'</td>';
print '<td align="center" width="70">'.$langs->trans("Status").'</td>';
print '<td align="right" width="100">'.$langs->trans("BankBalance").'</td>';
print "</tr>\n";

$total = 0; $found = 0;
$var=true;
foreach ($accounts as $key=>$type)
{
	if ($type == 0)
	{
	    $found++;

	    $acc = new Account($db);
		$acc->fetch($key);

		$var = !$var;
		$solde = $acc->solde(1);

		print "<tr ".$bc[$var].">";
		print '<td width="30%">'.$acc->getNomUrl(1).'</td>';
		print '<td>'.$acc->bank.'</td>';
		print '<td>'.$acc->number.'</td>';
		print '<td align="center">';
		if ($acc->rappro)
		{
			$result=$acc->load_board($user,$acc->id);
			print $acc->nbtodo;
			if ($acc->nbtodolate) print ' ('.$acc->nbtodolate.img_warning($langs->trans("Late")).')';
		}
		else print $langs->trans("FeatureDisabled");
		print '</td>';
		print '<td align="center">'.$acc->getLibStatut(2).'</td>';
		print '<td align="right">';
		print '<a href="account.php?account='.$acc->id.'">'.price($solde).'</a>';
		print '</td>';
		print '</tr>';

		$total += $solde;
	}
}
if (! $found) print '<tr '.$bc[$var].'><td colspan="6">'.$langs->trans("None").'</td></tr>';
// Total
print '<tr class="liste_total"><td colspan="5" class="liste_total">'.$langs->trans("Total").'</td><td align="right" class="liste_total">'.price($total).'</td></tr>';



print "</table>";


/*
 * Boutons d'actions
 */
print "<br><div class=\"tabsAction\">\n";
if ($user->rights->banque->configurer) {
	print '<a class="butAction" href="fiche.php?action=create">'.$langs->trans("NewFinancialAccount").'</a>';
	print '<a class="butAction" href="categ.php">'.$langs->trans("Rubriques").'</a>';
}
print "</div>";


llxFooter();

$db->close();
?>
