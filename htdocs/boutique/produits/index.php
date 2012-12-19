<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 * 		\file 		htdocs/boutique/produits/index.php
 * 		\ingroup    boutique
 * 		\brief      Page gestion produits du module OsCommerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';

$langs->load("companies");



llxHeader();

if ($sortfield == "") {
  $sortfield="lower(c.customers_lastname)";
}
if ($sortorder == "") {
  $sortorder="ASC";
}


if ($page == -1) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;

print_barre_liste("Liste des clients", $page, "index.php");

$sql = "SELECT c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_newsletter";
$sql .= " FROM ".DB_NAME_OSC.".customers as c";
$sql .= " ORDER BY $sortfield $sortorder ";
$sql .= $dbosc->plimit($limit,$offset);

$resql=$dbosc->query($sql);
if ($resql)
{
  $num = $dbosc->num_rows($resql);
  $i = 0;
  print "<table class=\"noborder\" width=\"100%\">";
  print "<tr class=\"liste_titre\">";
  print_liste_field_titre($langs->trans("Firstname"),"index.php", "c.customers_firstname");
  print_liste_field_titre($langs->trans("Lastname"),"index.php", "c.customers_lastname");
  print '<td>'.$langs->trans("EMail").'</td><td align="center">'.$langs->trans("Newsletter").'</td>';
  print "</tr>\n";
  $var=True;
  while ($i < $num)
    {
      $objp = $dbosc->fetch_object($resql);
      $var=!$var;
      print "<tr $bc[$var]>";
      print '<td><a href="fiche.php?id='.$objp->customers_id.'">'.$objp->customers_firstname."</a></td>\n";
      print '<td><a href="fiche.php?id='.$objp->customers_id.'">'.$objp->customers_lastname."</a></td>\n";
      print "<td>$objp->customers_email_address</td>\n";
      print "<td align=\"center\">$objp->customers_newsletter</td>\n";
      print "</tr>\n";
      $i++;
    }
  print "</table>";
  $dbosc->free($resql);
}
else
{
  dol_print_error($dbosc);
}

$dbosc->close();

llxFooter();
?>
