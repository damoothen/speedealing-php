<?php
/* Copyright (C) 2002-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
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
 * 		\file       htdocs/compta/ventilation/liste.php
 * 		\ingroup    compta
 * 		\brief      Page de ventilation des lignes de facture
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';

$langs->load("compta");
$langs->load("bills");

if (!$user->rights->facture->lire) accessforbidden();
if (!$user->rights->compta->ventilation->creer) accessforbidden();

// Securite acces client
if ($user->societe_id > 0) accessforbidden();


llxHeader('','Ventilation');

/*
 * Lignes de factures
 */

$sortfield = GETPOST("sortfield",'alpha');
$sortorder = GETPOST("sortorder",'alpha');
$page = GETPOST("page",'int');
if ($page == -1) { $page = 0; }
$offset = $conf->liste_limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
$limit = $conf->liste_limit;

$sql = "SELECT f.facnumber, f.rowid as facid, l.fk_product, l.description, l.price, l.rowid, l.fk_code_ventilation,";
$sql.= " p.rowid as product_id, p.ref as product_ref, p.label as product_label, p.fk_product_type as type";
$sql.= " FROM ".MAIN_DB_PREFIX."facture as f";
$sql.= " , ".MAIN_DB_PREFIX."facturedet as l";
$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."product as p ON p.rowid = l.fk_product";
$sql.= " WHERE f.rowid = l.fk_facture AND f.fk_statut = 1 AND fk_code_ventilation = 0";
$sql.= " AND f.entity = ".$conf->entity;
$sql.= " ORDER BY l.rowid DESC ".$db->plimit($limit+1,$offset);

$result = $db->query($sql);
if ($result)
{
	$num_lignes = $db->num_rows($result);
	$i = 0;

	print_barre_liste($langs->trans("InvoiceLinesToDispatch"),$page,"liste.php","",$sortfield,$sortorder,'',$num_lignes);

	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre"><td>'.$langs->trans("Invoice").'</td>';
	print '<td>'.$langs->trans("Ref").'</td>';
	print '<td>'.$langs->trans("Label").'</td>';
	print '<td>'.$langs->trans("Description").'</td>';
	print '<td align="right">'.$langs->trans("Montant").'</td>';
	print '<td>&nbsp;</td>';
	print "</tr>\n";

	$facture_static=new Facture($db);
	$product_static=new Product($db);

	$var=True;
	while ($i < min($num_lignes, $limit))
	{
		$objp = $db->fetch_object($result);
		$var=!$var;
		print "<tr $bc[$var]>";

		// Ref facture
		$facture_static->ref=$objp->facnumber;
		$facture_static->id=$objp->facid;
		print '<td>'.$facture_static->getNomUrl(1).'</td>';

		// Ref produit
		$product_static->ref=$objp->product_ref;
		$product_static->id=$objp->product_id;
		$product_static->type=$objp->type;
		print '<td>';
		if ($product_static->id) print $product_static->getNomUrl(1);
		else print '&nbsp;';
		print '</td>';

		print '<td>'.dol_trunc($objp->product_label,24).'</td>';
		print '<td>'.nl2br(dol_trunc($objp->description,32)).'</td>';

		print '<td align="right">';
		print price($objp->price);
		print '</td>';

		print '<td align="right"><a href="fiche.php?id='.$objp->rowid.'">';
		print img_edit();
		print '</a></td>';

		print "</tr>";
		$i++;
	}
	print "</table>";
}
else
{
	print $db->error();
}

llxFooter();
$db->close();
?>
