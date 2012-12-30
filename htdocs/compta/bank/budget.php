<?php
/* Copyright (C) 2001-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copytight (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *	    \file       htdocs/compta/bank/budget.php
 *      \ingroup    banque
 *		\brief      Page de budget
 */

require 'pre.inc.php';

$langs->load("categories");

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result=restrictedArea($user,'banque');


/*
 * View
 */

$companystatic=new Societe($db);

llxHeader();

// List movements bu category for bank transactions
print_fiche_titre($langs->trans("BankTransactionByCategories"));

print '<table class="noborder" width="100%">';
print "<tr class=\"liste_titre\">";
print '<td>'.$langs->trans("Rubrique").'</td>';
print '<td align="right">'.$langs->trans("Nb").'</td>';
print '<td align="right">'.$langs->trans("Total").'</td>';
print '<td align="right">'.$langs->trans("Average").'</td>';
print "</tr>\n";

$sql = "SELECT sum(d.amount) as somme, count(*) as nombre, c.label, c.rowid ";
$sql.= " FROM ".MAIN_DB_PREFIX."bank_categ as c";
$sql.= ", ".MAIN_DB_PREFIX."bank_class as l";
$sql.= ", ".MAIN_DB_PREFIX."bank as d";
$sql.= " WHERE c.entity = ".$conf->entity;
$sql.= " AND c.rowid = l.fk_categ";
$sql.= " AND d.rowid = l.lineid";
$sql.= " GROUP BY c.label, c.rowid";
$sql.= " ORDER BY c.label";

$result = $db->query($sql);
if ($result)
{
	$num = $db->num_rows($result);
	$i = 0; $total = 0; $totalnb = 0;

	$var=true;
	while ($i < $num)
	{
		$objp = $db->fetch_object($result);
		$var=!$var;
		print "<tr ".$bc[$var].">";
		print "<td><a href=\"".DOL_URL_ROOT."/compta/bank/search.php?bid=$objp->rowid\">$objp->label</a></td>";
		print '<td align="right">'.$objp->nombre.'</td>';
		print '<td align="right">'.price(abs($objp->somme))."</td>";
		print '<td align="right">'.price(abs(price2num($objp->somme / $objp->nombre,'MT')))."</td>";
		print "</tr>";
		$i++;
		$total += abs($objp->somme);
		$totalnb += $objp->nombre;
	}
	$db->free($result);

	print '<tr class="liste_total"><td colspan="2">'.$langs->trans("Total").'</td>';
	print '<td align="right" class="liste_total">'.price($total).'</td>';
	print '<td align="right" colspan="2" class="liste_total">'.price($totalnb?price2num($total / $totalnb, 'MT'):0).'</td></tr>';
}
else
{
	dol_print_error($db);
}
print "</table>";

$db->close();

llxFooter();
?>
