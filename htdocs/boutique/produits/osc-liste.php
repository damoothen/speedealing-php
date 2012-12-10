<?php
/* Copyright (C) 2001-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006      Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 		\file 		htdocs/boutique/produits/osc-liste.php
 *		\ingroup    boutique
 *		\brief      Page gestion produits du module OsCommerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';


/*
 * View
 */

llxHeader();

if ($sortfield == "") {
  $sortfield="p.label,p.price";
}
if ($sortorder == "") {
  $sortorder="ASC";
}


if ($page == -1) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;


print_barre_liste("Liste des produits oscommerce", $page, "osc-liste.php");

$sql = "SELECT p.products_id, p.products_model, p.products_quantity, p.products_status, d.products_name, m.manufacturers_name, m.manufacturers_id";
$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products as p, ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products_description as d, ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."manufacturers as m";
$sql .= " WHERE p.products_id = d.products_id AND d.language_id =" . $conf->global->OSC_LANGUAGE_ID;
$sql .= " AND p.manufacturers_id=m.manufacturers_id";
if ($reqstock=='epuise')
{
  $sql .= " AND p.products_quantity <= 0";
}

//$sql .= " ORDER BY $sortfield $sortorder ";
$sql .= $dbosc->plimit($limit,$offset);

print "<p><TABLE border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">";
print '<tr class="liste_titre">';
print "<td>id</td>";
print "<td>Ref</td>";
print "<td>Titre</td>";
print "<td>Groupe</td>";
print '<td align="center">Stock</td>';
print '<td align="center">Status</td>';
print '</tr>'."\n";

$resql=$dbosc->query($sql);
if ($resql)
{
  $num = $dbosc->num_rows($resql);
  $i = 0;

  $var=True;
  while ($i < $num)
  {
    $objp = $dbosc->fetch_object($resql);
    $var=!$var;
    print "<TR $bc[$var]>";
    print "<TD>$objp->products_id</TD>\n";
    print "<TD>$objp->products_model</TD>\n";
    print "<TD>$objp->products_name</TD>\n";
    print "<TD>$objp->manufacturers_name</TD>\n";
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
