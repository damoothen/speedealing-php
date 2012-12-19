<?php
/* Copyright (C) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003 Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2006 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 	    \file       htdocs/boutique/critiques/bestproduct.php
 * 		\ingroup    boutique
 * 		\brief      Page affichage meilleures critiques OS Commerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';


/*
 * View
 */

llxHeader();

if ($sortfield == "") {
	$sortfield="rat";
}
if ($sortorder == "") {
	$sortorder="DESC";
}


if ($page == -1) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;


print_barre_liste("Liste des produits classes par critiques", $page, "bestproduct.php");

$sql = "SELECT sum(r.reviews_rating)/count(r.reviews_rating) as rat, r.products_id, p.products_model, p.products_quantity, p.products_status";
$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."reviews as r,".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products as p ";
$sql .= " WHERE r.products_id = p.products_id";
$sql .= " GROUP BY r.products_id, p.products_model, p.products_quantity, p.products_status";
$sql .= " ORDER BY $sortfield $sortorder ";
$sql .= $dbosc->plimit($limit,$offset);

print "<p><TABLE border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">";
print "<TR class=\"liste_titre\"><td>".$langs->trans("Ref");
print '</td><TD align="center">Indice critiques</TD>';
print '</td><td align="center">Quantite';
print '</td><td align="center">Status</TD>';
print "</TR>\n";


$resql=$dbosc->query($sql);
if ($resql)
{
	$num = $dbosc->num_rows($resql);
	$i = 0;

	$var=True;
	while ($i < $num) {
		$objp = $dbosc->fetch_object($resql);
		$var=!$var;
		print "<TR $bc[$var]>";
		print '<TD><a href="'.DOL_URL_ROOT.'/boutique/livre/fiche.php?oscid='.$objp->products_id.'">'.$objp->products_model.'</a></TD>';
		print '<TD align="center">'.$objp->rat."</TD>\n";
		print '<TD align="center">'.$objp->products_quantity."</TD>\n";
		print '<TD align="center">'.$objp->products_status."</TD>\n";
		print "</TR>\n";
		$i++;
	}
	$dbosc->free();
}
else
{
	dol_print_error($dbosc);
}

print "</TABLE>";


$dbosc->close();

llxFooter();
?>
