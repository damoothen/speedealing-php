<?php
/* Copyright (C) 2006      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2007-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2009      Regis Houssin        <regis.houssin@capnetworks.com>
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
 *		\file       htdocs/compta/paiement/cheque/index.php
 *		\ingroup    compta
 *		\brief      Home page for cheque receipts
 */

require 'pre.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/cheque/class/remisecheque.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';

$langs->load("banks");

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'banque', '','');


$checkdepositstatic=new RemiseCheque($db);
$accountstatic=new Account($db);


/*
 * View
 */

llxHeader('',$langs->trans("ChequesArea"));

print_fiche_titre($langs->trans("ChequesArea"));

print '<table border="0" width="100%" class="notopnoleftnoright">';

print '<tr><td valign="top" width="30%" class="notopnoleft">';

$sql = "SELECT count(b.rowid)";
$sql.= " FROM ".MAIN_DB_PREFIX."bank as b";
$sql.= ", ".MAIN_DB_PREFIX."bank_account as ba";
$sql.= " WHERE ba.rowid = b.fk_account";
$sql.= " AND ba.entity = ".$conf->entity;
$sql.= " AND b.fk_type = 'CHQ'";
$sql.= " AND b.fk_bordereau = 0";
$sql.= " AND b.amount > 0";

$resql = $db->query($sql);

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td colspan="2">'.$langs->trans("BankChecks")."</td>\n";
print "</tr>\n";

if ($resql)
{
  $var=false;
  if ($row = $db->fetch_row($resql) )
    {
      $num = $row[0];
    }
  print "<tr ".$bc[$var].">";
  print '<td>'.$langs->trans("BankChecksToReceipt").'</td>';
  print '<td align="right">';
  print '<a href="'.DOL_URL_ROOT.'/compta/paiement/cheque/fiche.php?leftmenu=customers_bills_checks&action=new">'.$num.'</a>';
  print '</td></tr>';
  print "</table>\n";
}
else
{
  dol_print_error($db);
}


print '</td><td valign="top" width="70%" class="notopnoleftnoright">';


$sql = "SELECT bc.rowid, bc.date_bordereau as db, bc.amount, bc.number as ref";
$sql.= ", bc.statut, bc.nbcheque";
$sql.= ", ba.label, ba.rowid as bid";
$sql.= " FROM ".MAIN_DB_PREFIX."bordereau_cheque as bc";
$sql.= ", ".MAIN_DB_PREFIX."bank_account as ba";
$sql.= " WHERE ba.rowid = bc.fk_bank_account";
$sql.= " AND bc.entity = ".$conf->entity;
$sql.= " ORDER BY bc.rowid";
$sql.= " DESC LIMIT 10";

$resql = $db->query($sql);

if ($resql)
{
	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre">';
	print '<td>'.$langs->trans("CheckReceiptShort").'</td>';
	print '<td>'.$langs->trans("Date")."</td>";
	print '<td>'.$langs->trans("Account").'</td>';
	print '<td align="right">'.$langs->trans("NbOfCheques").'</td>';
	print '<td align="right">'.$langs->trans("Amount").'</td>';
	print '<td align="right">'.$langs->trans("Status").'</td>';
	print "</tr>\n";

	$var=true;
	while ( $objp = $db->fetch_object($resql) )
	{
        $checkdepositstatic->id=$objp->rowid;
        $checkdepositstatic->ref=($objp->ref?$objp->ref:$objp->rowid);
	    $checkdepositstatic->statut=$objp->statut;

		$accountstatic->id=$objp->bid;
		$accountstatic->label=$objp->label;

		$var=!$var;
		print "<tr $bc[$var]>\n";

		print '<td>'.$checkdepositstatic->getNomUrl(1).'</td>';
		print '<td>'.dol_print_date($db->jdate($objp->db),'day').'</td>';
		print '<td>'.$accountstatic->getNomUrl(1).'</td>';
		print '<td align="right">'.$objp->nbcheque.'</td>';
		print '<td align="right">'.price($objp->amount).'</td>';
		print '<td align="right">'.$checkdepositstatic->LibStatut($objp->statut,3).'</td>';

		print '</tr>';
	}
	print "</table>";
	$db->free($resql);
}
else
{
  dol_print_error($db);
}

print "</td></tr>\n";
print "</table>\n";

$db->close();

llxFooter();
?>
