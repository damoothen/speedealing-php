<?php
/* Copyright (C) 2003-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
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
 *  \file       htdocs/product/stock/index.php
 *  \ingroup    stock
 *  \brief      Home page of stock area
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/product/stock/class/entrepot.class.php';

$langs->load("stocks");

if (!$user->rights->stock->lire)
  accessforbidden();


/*
 * View
 */

$help_url='EN:Module_Stocks_En|FR:Module_Stock|ES:M&oacute;dulo_Stocks';
llxHeader("",$langs->trans("Stocks"),$help_url);

print_fiche_titre($langs->trans("StocksArea"));

print '<table border="0" width="100%" class="notopnoleftnoright">';
print '<tr><td valign="top" width="30%" class="notopnoleft">';

/*
 * Zone recherche entrepot
 */
print '<form method="post" action="'.DOL_URL_ROOT.'/product/stock/liste.php">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<table class="noborder nohover" width="100%">';
print "<tr class=\"liste_titre\">";
print '<td colspan="3">'.$langs->trans("Search").'</td></tr>';
print "<tr ".$bc[false]."><td>";
print $langs->trans("Ref").':</td><td><input class="flat" type="text" size="18" name="sref"></td><td rowspan="2"><input type="submit" value="'.$langs->trans("Search").'" class="button"></td></tr>';
print "<tr ".$bc[false]."><td>".$langs->trans("Other").':</td><td><input type="text" name="sall" class="flat" size="18"></td>';
print "</table></form><br>";

$sql = "SELECT e.label, e.rowid, e.statut";
$sql.= " FROM ".MAIN_DB_PREFIX."entrepot as e";
$sql.= " WHERE e.statut in (0,1)";
$sql.= " AND e.entity = ".$conf->entity;
$sql.= $db->order('e.statut','DESC');
$sql.= $db->plimit(15, 0);

$result = $db->query($sql);

if ($result)
{
    $num = $db->num_rows($result);

    $i = 0;

    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre"><td colspan="2">'.$langs->trans("Warehouses").'</td></tr>';

    if ($num)
    {
        $entrepot=new Entrepot($db);

        $var=True;
        while ($i < $num)
        {
            $objp = $db->fetch_object($result);
            $var=!$var;
            print "<tr $bc[$var]>";
            print "<td><a href=\"fiche.php?id=$objp->rowid\">".img_object($langs->trans("ShowStock"),"stock")." ".$objp->label."</a></td>\n";
            print '<td align="right">'.$entrepot->LibStatut($objp->statut,5).'</td>';
            print "</tr>\n";
            $i++;
        }
        $db->free($result);

    }
    print "</table>";
}
else
{
    dol_print_error($db);
}

print '</td><td valign="top" width="70%" class="notopnoleftnoright">';

// Last movements
$max=10;
$sql = "SELECT p.rowid, p.label as produit,";
$sql.= " e.label as stock, e.rowid as entrepot_id,";
$sql.= " m.value, m.datem";
$sql.= " FROM ".MAIN_DB_PREFIX."entrepot as e";
$sql.= ", ".MAIN_DB_PREFIX."stock_mouvement as m";
$sql.= ", ".MAIN_DB_PREFIX."product as p";
$sql.= " WHERE m.fk_product = p.rowid";
$sql.= " AND m.fk_entrepot = e.rowid";
$sql.= " AND e.entity = ".$conf->entity;
if (empty($conf->global->STOCK_SUPPORTS_SERVICES)) $sql.= " AND p.fk_product_type = 0";
$sql.= $db->order("datem","DESC");
$sql.= $db->plimit($max,0);

dol_syslog("Index:list stock movements sql=".$sql, LOG_DEBUG);
$resql = $db->query($sql);
if ($resql)
{
	$num = $db->num_rows($resql);

	print '<table class="noborder" width="100%">';
	print "<tr class=\"liste_titre\">";
	print '<td>'.$langs->trans("LastMovements",min($num,$max)).'</td>';
	print '<td>'.$langs->trans("Product").'</td>';
	print '<td>'.$langs->trans("Warehouse").'</td>';
	print '<td align="right"><a href="'.DOL_URL_ROOT.'/product/stock/mouvement.php">'.$langs->trans("FullList").'</a></td>';
	print "</tr>\n";

	$var=True;
	$i=0;
	while ($i < min($num,$max))
	{
		$objp = $db->fetch_object($resql);
		$var=!$var;
		print "<tr $bc[$var]>";
		print '<td>'.dol_print_date($db->jdate($objp->datem),'dayhour').'</td>';
		print "<td><a href=\"../fiche.php?id=$objp->rowid\">";
		print img_object($langs->trans("ShowProduct"),"product").' '.$objp->produit;
		print "</a></td>\n";
		print '<td><a href="fiche.php?id='.$objp->entrepot_id.'">';
		print img_object($langs->trans("ShowWarehouse"),"stock").' '.$objp->stock;
		print "</a></td>\n";
		print '<td align="right">';
		if ($objp->value > 0) print '+';
		print $objp->value.'</td>';
		print "</tr>\n";
		$i++;
	}
	$db->free($resql);

	print "</table>";
}

print '</td></tr></table>';

llxFooter();

$db->close();

?>
