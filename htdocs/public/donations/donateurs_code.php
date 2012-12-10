<?php
/* Copyright (C) 2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 *	    \file       htdocs/public/donations/donateurs_code.php
 *      \ingroup    donation
 *		\brief      Page to list donators
 */

define("NOLOGIN",1);		// This means this output page does not require to be logged.
define("NOCSRFCHECK",1);	// We accept to go on this page from external web site.

// C'est un wrapper, donc header vierge
/**
 * Header function
 *
 * @return	void
 */
function llxHeaderVierge() { print '<html><title>Export agenda cal</title><body>'; }
/**
 * Header function
 *
 * @return	void
 */
function llxFooterVierge() { print '</body></html>'; }

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT .'/compta/dons/class/class/don.class.php';

// Security check
if (empty($conf->don->enabled)) accessforbidden('',1,1,1);


$langs->load("donations");


/*
 * View
 */

llxHeaderVierge();

$sql = "SELECT d.datedon as datedon, d.nom, d.prenom, d.amount, d.public, d.societe";
$sql.= " FROM ".MAIN_DB_PREFIX."don as d";
$sql.= " WHERE d.fk_statut in (2, 3) ORDER BY d.datedon DESC";

$resql=$db->query($sql);
if ($resql)
{
	$num = $db->num_rows($resql);
	if ($num)
	{

		print "<TABLE border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">";

		print '<TR>';
		print "<td>".$langs->trans("Name")." / ".$langs->trans("Company")."</td>";
		print "<td>Date</td>";
		print "<td align=\"right\">".$langs->trans("Amount")."</TD>";
		print "</TR>\n";

		$var=True;
		$bc[1]='bgcolor="#f5f5f5"';
		$bc[0]='bgcolor="#f0f0f0"';
		while ($i < $num)
		{
			$objp = $db->fetch_object($resql);

			$var=!$var;
			print "<TR $bc[$var]>";
			if ($objp->public)
			{
				print "<td>".$objp->prenom." ".$objp->nom." ".$objp->societe."</td>\n";
			}
			else
			{
				print "<td>Anonyme Anonyme</td>\n";
			}
			print "<td>".dol_print_date($db->jdate($objp->datedon))."</td>\n";
			print '<td align="right">'.number_format($objp->amount,2,'.',' ').' '.$langs->trans("Currency".$conf->currency).'</td>';
			print "</tr>";
			$i++;
		}
		print "</table>";

	}
	else
	{
		print "Aucun don publique";
	}
}
else
{
	dol_print_error($db);
}

$db->close();

llxFooterVierge();
?>
