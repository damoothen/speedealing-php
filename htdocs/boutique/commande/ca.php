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
 * 		\file 		htdocs/boutique/commande/ca.php
 * 		\ingroup    boutique
 * 		\brief      Page ca commandes du module OsCommerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';


/*
 * View
 */

llxHeader();

if ($sortfield == "")
{
  $sortfield="date_purchased";
}
if ($sortorder == "")
{
  $sortorder="DESC";
}

if ($page == -1) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;

print_barre_liste("Liste des commandes", $page, "ca.php");

print '<table class="noborder" cellspacing="0" cellpadding="3">';
print '<tr class="liste_titre"><td>'.$langs->trans("Description").'</td>';
print '<td align="right">'.$langs->trans("Lastname").'</td></tr>';

$sql = "SELECT sum(t.value) as value";
$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."orders_total as t";
$sql .= " WHERE t.class = 'ot_subtotal'";

$resql=$dbosc->query($sql);
if ($resql)
{
  $num = $dbosc->num_rows($resql);

  $var=True;
  if ($num > 0)
    {
      $objp = $dbosc->fetch_object($resql);
      $var=!$var;
      print "<tr $bc[$var]>";
      print '<td>Somme des commandes</td>';
      print '<td align="right">'.price($objp->value).'</td>';

      print "</tr>\n";
      $i++;
    }

  $dbosc->free();
}
else
{
  dol_print_error($dbosc);
}

$sql = "SELECT sum(t.value) as value";
$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."orders_total as t";
$sql .= " WHERE t.class = 'ot_shipping'";
$resql=$dbosc->query($sql);
if ($resql)
{
  $num = $dbosc->num_rows($resql);

  $var=True;
  if ($num > 0)
    {
      $objp = $dbosc->fetch_object($resql);
      $var=!$var;
      print "<tr $bc[$var]>";
      print '<td>Somme des frais de port</td>';
      print '<td align="right">'.price($objp->value).'</td></tr>';
      $i++;
    }

  $dbosc->free();
}
else
{
  dol_print_error($dbosc);
}


print "</table>";

$dbosc->close();

llxFooter();
?>
