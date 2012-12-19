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
 */

/**
 * 	    \file       htdocs/boutique/promotion/index.php
 * 		\ingroup    boutique
 * 		\brief      Page gestion promotions OSCommerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';


llxHeader();

if ($action == "inactive")
{
  $promotion = new Promotion($dbosc);
  $promotion->set_inactive($id);
}
if ($action == "active")
{
  $promotion = new Promotion($dbosc);
  $promotion->set_active($id);
}

if ($sortfield == "")
{
  $sortfield="pd.products_name";
}
if ($sortorder == "")
{
  $sortorder="ASC";
}

if ($page == -1) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;

print_barre_liste("Liste des promotions", $page, "index.php", "",$sortfield, $sortorder);

$urladd = "&sortorder=$sortorder&sortfield=$sortfield";

$sql = "SELECT pd.products_name, s.specials_new_products_price, p.products_price, p.products_model, s.status, p.products_id,";
$sql.= " expires_date as fin";
$sql.= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."specials as s,".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products_description as pd,".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products as p";
$sql.= " WHERE s.products_id = pd.products_id AND pd.products_id = p.products_id AND pd.language_id = ".$conf->global->OSC_LANGUAGE_ID;
$sql.= " ORDER BY $sortfield $sortorder ";
$sql.= $dbosc->plimit($limit,$offset);

$resql=$dbosc->query($sql);
if ($resql)
{
  $num = $dbosc->num_rows($resql);
  $i = 0;
  print '<table class=\"noborder width="100%">';
  print "<tr class=\"liste_titre\">";
  print_liste_field_titre($langs->trans("Ref"),"index.php", "p.products_model");
  print_liste_field_titre("Titre","index.php", "pd.products_name");
  print "<td>&nbsp;</td><td>&nbsp;</td><td>Fin</td>";
  print '<td align="right">Prix initial</td>';
  print '<td align="right">Prix remise</td>';
  print "</tr>\n";
  $var=True;
  while ($i < $num)
    {
      $objp = $dbosc->fetch_object($i);
      $var=!$var;

      print "<tr $bc[$var]>";
      print '<td>'.$objp->products_model."</td>";
      print '<td>'.$objp->products_name."</td>";

      if ($objp->status == 1)
	{
	  print '<td align="center"><img src="/theme/'.$conf->theme.'/img/icon_status_green.png" border="0" alt="actif"></td>';
	  print '<td align="center">';
	  print '<a href="index.php?action=inactive&id='.$objp->products_id.''.$urladd.'&page='.$page.'">';
	  print '<img src="/theme/'.$conf->theme.'/img/icon_status_red_light.png" border="0"></a></td>';
	}
      else
	{
	  print '<td align="center">';
	  print '<a href="index.php?action=active&id='.$objp->products_id.''.$urladd.'&page='.$page.'">';
	  print '<img src="/theme/'.$conf->theme.'/img/icon_status_green_light.png" border="0"></a></td>';
	  print '<td align="center"><img src="/theme/'.$conf->theme.'/img/icon_status_red.png" border="0" alt="inactif"></td>';
	}
      print "<td>".dol_print_date($dbosc->jdate($objp->fin),'day')."</td>";
      print '<td align="right">'.price($objp->products_price)."</td>";
      print '<td align="right">'.price($objp->specials_new_products_price)."</td>";
      print "</tr>";
      $i++;
    }
  print "</TABLE>";
  $dbosc->free();
}
else
{
	dol_print_error($dbosc);
}
$dbosc->close();

llxFooter();

?>
