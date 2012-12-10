<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003-2005 Eric Seigne <eric.seigne@ryxeo.com>
 * Copyright (C) 2004-2006 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *
 */

/**
 *  \file       htdocs/boutique/commande/fiche.php
 *  \ingroup    boutique
 *  \brief      Page fiche commande OSCommerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';
include_once DOL_DOCUMENT_ROOT.'/boutique/commande/class/boutiquecommande.class.php';

$langs->load("products");

$id=GETPOST('id', 'int');

/*
 *	View
 */

llxHeader();


if ($id > 0)
{
	$commande = new BoutiqueCommande($db);
	$result = $commande->fetch($id);
	if ($result)
	{
		print '<div class="titre">'.$langs->trans("OrderCard").': '.$commande->id.'</div><br>';

		print '<table border="1" width="100%" cellspacing="0" cellpadding="4">';
		print '<tr><td width="20%">Date</td><td width="80%" colspan="2">'.$commande->date.'</td></tr>';
		print '<td width="20%">Client</td><td width="80%" colspan="2"><a href="'.DOL_URL_ROOT.'/boutique/client/fiche.php?id='.$commande->client_id.'">'.$commande->client_name.'</a></td></tr>';

		print '<td width="20%">Paiement</td><td width="80%" colspan="2">'.$commande->payment_method.'</td></tr>';

		print "<tr><td>".$langs->trans("Address")."</td><td>".$langs->trans("Delivery")."</td><td>".$langs->trans("Invoice")."</td></tr>";

		print "<td>&nbsp;</td><td>".$commande->delivery_adr->name."<br>".$commande->delivery_adr->street."<br>".$commande->delivery_adr->cp."<br>".$commande->delivery_adr->city."<br>".$commande->delivery_adr->country."</td>";
		print "<td>".$commande->billing_adr->name."<br>".$commande->billing_adr->street."<br>".$commande->billing_adr->cp."<br>".$commande->billing_adr->city."<br>".$commande->billing_adr->country."</td>";
		print "</tr>";

		print "</table>";

		print "<br>";

		/*
		 * Produits
		 *
		 */
		$sql = "SELECT orders_id, products_id, products_model, products_name, products_price, final_price, products_quantity";
		$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."orders_products";
		$sql .= " WHERE orders_id = " . $commande->id;
		//$commande->id;
		//	echo $sql;
		$resql=$dbosc->query($sql);
		if ($resql)
		{
			$num = $dbosc->num_rows($resql);
			$i = 0;
			print '<table class="noborder" width="100%">';
			print '<tr class="liste_titre"><td align="left" width="40%">'.$langs->trans("Products").'</td>';
			print '<td align="center">'.$langs->trans("Number").'</td><td align="right">'.$langs->trans("Price").'</td><td align="right">Prix final</td>';
			print "</tr>\n";
			$var=True;
			while ($i < $num)
			{
				$objp = $dbosc->fetch_object($resql);
				$var=!$var;
				print "<tr $bc[$var]>";
				print '<td align="left" width="40%">';
				print '<a href="fiche.php?id='.$objp->products_id.'"><img src="/theme/'.$conf->theme.'/img/filenew.png" border="0" width="16" height="16" alt="Fiche livre"></a>';

				print '<a href="fiche.php?id='.$objp->products_id.'">'.$objp->products_name.'</a>';
				print "</td>";

				print '<td align="center"><a href="fiche.php?id='.$objp->rowid."\">$objp->products_quantity</a></TD>\n";
				print "<td align=\"right\"><a href=\"fiche.php?id=$objp->rowid\">".price($objp->products_price)."</a></TD>\n";
				print "<td align=\"right\"><a href=\"fiche.php?id=$objp->rowid\">".price($objp->final_price)."</a></TD>\n";

				print "</tr>\n";
				$i++;
			}
			print "</table>";
			$dbosc->free();
		}
		else
		{
			print $dbosc->error();
		}

		/*
		 *
		 *
		 */
		print "<br>";

		print '<table border="1" width="100%" cellspacing="0" cellpadding="4">';
		print "<tr>";
		print '<td width="20%">Frais d\'expeditions</td><td width="80%">'.price($commande->total_ot_shipping).' EUR</td></tr>';
		print '<td width="20%">'.$langs->trans("Lastname").'</td><td width="80%">'.price($commande->total_ot_total).' EUR</td></tr>';
		print "</table>";



	}
	else
	{
		print "Fetch failed";
	}
}
else
{
	print "Error";
}


/* ************************************************************************** */
/*                                                                            */
/* Barre d'action                                                             */
/*                                                                            */
/* ************************************************************************** */

print '<br><table width="100%" border="1" cellspacing="0" cellpadding="3">';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '</table><br>';



$dbosc->close();

llxFooter();
?>
