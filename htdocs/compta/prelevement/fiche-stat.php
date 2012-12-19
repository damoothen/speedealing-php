<?php
/* Copyright (C) 2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2010-2012 Juanjo Menent   <jmenent@2byte.es>
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
 *	\file       htdocs/compta/prelevement/fiche-stat.php
 *  \ingroup    prelevement
 *	\brief      Prelevement statistics
 */

require '../bank/pre.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/prelevement.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/prelevement/class/bonprelevement.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/prelevement/class/ligneprelevement.class.php';

// Security check
if ($user->societe_id > 0) accessforbidden();

$langs->load("withdrawals");
$langs->load("categories");

// Get supervariables
$prev_id = GETPOST('id','int');
$page = GETPOST('page','int');

/*
 * View
 */

llxHeader('',$langs->trans("WithdrawalReceipt"));

if ($prev_id)
{
	$bon = new BonPrelevement($db,"");

	if ($bon->fetch($prev_id) == 0)
	{
		$head = prelevement_prepare_head($bon);
		dol_fiche_head($head, 'statistics', $langs->trans("WithdrawalReceipt"), '', 'payment');

		print '<table class="border" width="100%">';

		print '<tr><td width="20%">'.$langs->trans("Ref").'</td><td>'.$bon->getNomUrl(1).'</td></tr>';
		print '<tr><td width="20%">'.$langs->trans("Date").'</td><td>'.dol_print_date($bon->datec,'day').'</td></tr>';
		print '<tr><td width="20%">'.$langs->trans("Amount").'</td><td>'.price($bon->amount).'</td></tr>';
		print '<tr><td width="20%">'.$langs->trans("File").'</td><td>';

		$relativepath = 'receipts/'.$bon->ref;

		print '<a href="'.DOL_URL_ROOT.'/document.php?type=text/plain&amp;modulepart=prelevement&amp;file='.urlencode($relativepath).'">'.$relativepath.'</a>';

		print '</td></tr>';

		// Status
		print '<tr><td width="20%">'.$langs->trans('Status').'</td>';
		print '<td>'.$bon->getLibStatut(1).'</td>';
		print '</tr>';

		if($bon->date_trans <> 0)
		{
			$muser = new User($db);
			$muser->fetch($bon->user_trans);

			print '<tr><td width="20%">'.$langs->trans("TransData").'</td><td>';
			print dol_print_date($bon->date_trans,'day');
			print ' '.$langs->trans("By").' '.$muser->getFullName($langs).'</td></tr>';
			print '<tr><td width="20%">'.$langs->trans("TransMetod").'</td><td>';
			print $bon->methodes_trans[$bon->method_trans];
			print '</td></tr>';
		}
		if($bon->date_credit <> 0)
		{
			print '<tr><td width="20%">'.$langs->trans('CreditDate').'</td><td>';
			print dol_print_date($bon->date_credit,'day');
			print '</td></tr>';
		}

		print '</table>';

		print '</div>';
	}
	else
	{
		$langs->load("errors");
		print $langs->trans("Error");
	}

	/*
	 * Stats
	 *
	 */
	$ligne=new LignePrelevement($db,$user);

	$sql = "SELECT sum(pl.amount), pl.statut";
	$sql.= " FROM ".MAIN_DB_PREFIX."prelevement_lignes as pl";
	$sql.= " WHERE pl.fk_prelevement_bons = ".$prev_id;
	$sql.= " GROUP BY pl.statut";

	$resql=$db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
		$i = 0;

		print"\n<!-- debut table -->\n";
		print '<table class="noborder" width="100%" cellspacing="0" cellpadding="4">';
		print '<tr class="liste_titre">';
		print '<td>'.$langs->trans("Status").'</td><td align="right">'.$langs->trans("Amount").'</td><td align="right">%</td></tr>';

		$var=false;

		while ($i < $num)
		{
			$row = $db->fetch_row($resql);

			print "<tr $bc[$var]><td>";

			print $ligne->LibStatut($row[1],1);

			print '</td><td align="right">';
			print price($row[0]);

			print '</td><td align="right">';
			print round($row[0]/$bon->amount*100,2)." %";
			print '</td>';

			print "</tr>\n";

			$var=!$var;
			$i++;
		}

		print "</table>";
		$db->free($resql);
	}
	else
	{
		print $db->error() . ' ' . $sql;
	}
}

$db->close();

llxFooter();
?>
