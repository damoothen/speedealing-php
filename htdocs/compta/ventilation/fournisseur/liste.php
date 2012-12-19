<?php
/* Copyright (C) 2002-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
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
 *
 */


/**
 *   \file       htdocs/compta/ventilation/liste.php
 *   \ingroup    compta
 *   \brief      Page de ventilation des lignes de facture
 */

require '../../../main.inc.php';

$langs->load("bills");

if (!$user->rights->facture->lire) accessforbidden();
if (!$user->rights->compta->ventilation->creer) accessforbidden();
/*
 * Securite acces client
 */
if ($user->societe_id > 0) accessforbidden();


llxHeader('','Ventilation');

/*
 * Lignes de factures
 *
 */
$page = $_GET["page"];
if ($page < 0) $page = 0;
$limit = $conf->liste_limit;
$offset = $limit * $page ;

$sql = "SELECT f.facnumber, f.rowid as facid, l.fk_product, l.description, l.total_ttc as price, l.rowid, l.fk_code_ventilation ";
$sql .= " FROM ".MAIN_DB_PREFIX."facture_fourn_det as l";
$sql .= " , ".MAIN_DB_PREFIX."facture_fourn as f";
$sql .= " WHERE f.rowid = l.fk_facture_fourn AND f.fk_statut = 1 AND fk_code_ventilation = 0";
$sql .= " ORDER BY l.rowid DESC ".$db->plimit($limit+1,$offset);

$result = $db->query($sql);
if ($result)
{
  $num_lignes = $db->num_rows($result);
  $i = 0;

  print_barre_liste("Lignes de facture à ventiler",$page,"liste.php","",$sortfield,$sortorder,'',$num_lignes);

  print '<table class="noborder" width="100%">';
  print '<tr class="liste_titre"><td>Facture</td>';
  print '<td>'.$langs->trans("Description").'</td>';
  print '<td align="right">&nbsp;</td>';
  print '<td>&nbsp;</td>';
  print "</tr>\n";

  $var=True;
  while ($i < min($num_lignes, $limit))
    {
      $objp = $db->fetch_object($result);
      $var=!$var;
      print "<tr $bc[$var]>";

      print '<td><a href="'.DOL_URL_ROOT.'/fourn/facture/fiche.php?facid='.$objp->facid.'">'.$objp->facnumber.'</a></td>';
      print '<td>'.stripslashes(nl2br($objp->description)).'</td>';

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
$db->close();

llxFooter();
?>
