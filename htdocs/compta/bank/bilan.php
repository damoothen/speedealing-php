<?php
/* Copyright (C) 2001-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004      Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	    \file       htdocs/compta/bank/bilan.php
 *      \ingroup    banque
 *		\brief      Page de bilan
 */

require 'pre.inc.php';

if (!$user->rights->banque->lire)
  accessforbidden();


/**
 * 	Get result of sql for field amount
 *
 * 	@param	string	$sql	SQL string
 * 	@return	int				Amount
 */
function valeur($sql)
{
	global $db;
	$resql=$db->query($sql);
	if ($resql)
	{
		$obj=$db->fetch_object($resql);
		$valeur = $obj->amount;
		$db->free($resql);
	}
	return $valeur;
}


/*
 *	View
 */

llxHeader();

print_titre("Bilan");
print '<br>';

print '<table class="noborder" width="100%" cellspacing="0" cellpadding="2">';
print "<tr class=\"liste_titre\">";
echo '<td colspan="2">'.$langs->trans("Summary").'</td>';
print "</tr>\n";

$var=!$var;
$sql = "SELECT sum(amount) as amount FROM ".MAIN_DB_PREFIX."paiement";
$paiem = valeur($sql);
print "<tr $bc[$var]><td>Somme des paiements (associes a une facture)</td><td align=\"right\">".price($paiem)."</td></tr>";

$var=!$var;
$sql = "SELECT sum(amount) as amount FROM ".MAIN_DB_PREFIX."bank WHERE amount > 0";
$credits = valeur($sql);
print "<tr $bc[$var]><td>Somme des credits</td><td align=\"right\">".price($credits)."</td></tr>";

$var=!$var;
$sql = "SELECT sum(amount) as amount FROM ".MAIN_DB_PREFIX."bank WHERE amount < 0";
$debits = valeur($sql);
print "<tr $bc[$var]><td>Somme des debits</td><td align=\"right\">".price($debits)."</td></tr>";

$var=!$var;
$sql = "SELECT sum(amount) as amount FROM ".MAIN_DB_PREFIX."bank ";
$solde = valeur($sql);
print "<tr $bc[$var]><td>".$langs->trans("BankBalance")."</td><td align=\"right\">".price($solde)."</td></tr>";


print "</table>";

$db->close();

llxFooter();
?>
