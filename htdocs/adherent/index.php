<?php

/* Copyright (C) 2001-2002	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2003		Jean-Louis Bergamo		<jlb@j1b.org>
 * Copyright (C) 2004-2012	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *       \file       htdocs/adherents/index.php
 *       \ingroup    member
 *       \brief      Page accueil module adherents
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent.class.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent_type.class.php");

$langs->load("companies");
$langs->load("members");


/*
 * View
 */

llxHeader('', $langs->trans("Members"), 'EN:Module_Foundations|FR:Module_Adh&eacute;rents|ES:M&oacute;dulo_Miembros');

$staticmember = new Adherent($db);
$statictype = new AdherentType($db);

print_fiche_titre($langs->trans("MembersArea"));

print '<table border="0" width="100%" class="notopnoleftnoright">';

$var = True;

$Adherents = array();
$AdherentsAValider = array();
$MemberUpToDate = array();
$AdherentsResilies = array();

$AdherentType = array();

$adht = new AdherentType($db);
$result = $adht->getView('list');
if (count($result->rows)) {
	foreach ($result->rows as $aRow) {
		$objp = $aRow->value;

		$adhtype = new AdherentType($db);
		$adhtype->id = $objp->_id;
		$adhtype->cotisation = $objp->cotisation;
		$adhtype->libelle = $objp->libelle;
		$AdherentType[$objp->libelle] = $adhtype;
	}
}

$now = dol_now();

$doc->_id = "_temp_view";
$doc->map = "function(doc) {\n  var now = Math.round(+new Date()/1000);\n\n  if(doc.class && doc.class==\"Adherent\"){\n    if(doc.last_subscription_date_end && doc.Status == 1) {\n      if(doc.last_subscription_date_end < now)\n        emit([doc.typeid,\"expired\"], 1);\n      else\n        emit([doc.typeid,\"actived\"], 1);\n    }\n    else\n      emit([doc.typeid,doc.Status], 1);\n  }\n}";
$doc->reduce = "function(keys, values) {\n  return sum(values)\n}";

$result = $staticmember->storeDoc($doc);
if (count($result->rows)) {
	foreach ($result->rows as $aRow) {
		$Adherents[$aRow->key[0]][$aRow->key[1]] = $aRow->value;
	}
}

foreach ($AdherentType as $key => $adhtype) {
	foreach ($staticmember->fk_extrafields->fields->Status->values as $idx => $row) {
		if ($Adherents[$key][$idx]) {
			$somme[$idx]+=$Adherents[$key][$idx];
			$total+=$Adherents[$key][$idx];
		}
	}
}

print '<tr><td width="30%" class="notopnoleft" valign="top">';

/*
 * Statistics
 */

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre"><td colspan="2">' . $langs->trans("Statistics") . '</td></tr>';
print '<tr><td align="center">';

$dataval = array();
$datalabels = array();
$i = 0;
foreach ($AdherentType as $key => $adhtype) {
	$datalabels[] = array($i, $adhtype->getNomUrl(0, dol_size(16)));
	foreach ($staticmember->fk_extrafields->fields->Status->values as $idx => $row) {
		$dataval[$key][] = array($i, $Adherents[$key][$idx]);
	}
	$i++;
}

$dataseries = array();
foreach ($staticmember->fk_extrafields->fields->Status->values as $idx => $row)
	$dataseries[] = array('label' => $langs->trans($row->label), 'data' => round($somme[$idx]));
$data = array('series' => $dataseries);
dol_print_graph('stats', 330, 180, $data, 1, 'pie', 1);
print '</td></tr>';
print '<tr class="liste_total"><td>' . $langs->trans("Total") . '</td><td align="right">';
print $total;
print '</td></tr>';
print '</table>';

print '</td><td class="notopnoleftnoright" valign="top">';

$var = true;

// Summary of members by type
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>' . $langs->trans("MembersTypes") . '</td>';
foreach ($staticmember->fk_extrafields->fields->Status->values as $aRow)
	print '<td align=right>' . $langs->trans($aRow->label) . '</td>';
print "</tr>\n";

foreach ($AdherentType as $key => $adhtype) {
	$var = !$var;
	print "<tr $bc[$var]>";
	print '<td><a href="adherent/type.php?id=' . $adhtype->id . '">' . img_object($langs->trans("ShowType"), "group") . ' ' . $adhtype->getNomUrl(0, dol_size(16)) . '</a></td>';
	foreach ($staticmember->fk_extrafields->fields->Status->values as $idx => $row) {
		if ($Adherents[$key][$idx]) {
			print '<td align="right">' . $Adherents[$key][$idx] . ' ' . $staticmember->LibStatus($idx) . '</td>';
		}
		else
			print '<td></td>';
	}
	print "</tr>\n";
}
print '<tr class="liste_total">';
print '<td class="liste_total">' . $langs->trans("Total") . '</td>';
foreach ($staticmember->fk_extrafields->fields->Status->values as $idx => $row) {
	if ($somme[$idx])
		print '<td class="liste_total" align="right">' . $somme[$idx] . ' ' . $staticmember->LibStatus($idx) . '</td>';
	else
		print '<td></td>';
}

print '</tr>';

print "</table>\n";
print "<br>\n";


// List of subscription by year
$Total = array();
$Number = array();
$tot = 0;
$numb = 0;

$result = $staticmember->getView('cotisationCount', array("group" => true));
if (count($result->rows) > 0)
	foreach ($result->rows as $aRow) {
		$Number[$aRow->key] = $aRow->value;
	}

$result = $staticmember->getView('cotisationAmount', array("group" => true));
if (count($result->rows) > 0)
	foreach ($result->rows as $aRow) {
		$Total[$aRow->key] = $aRow->value;
	}

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>' . $langs->trans("Subscriptions") . '</td>';
print '<td align="right">' . $langs->trans("Number") . '</td>';
print '<td align="right">' . $langs->trans("AmountTotal") . '</td>';
print '<td align="right">' . $langs->trans("AmountAverage") . '</td>';
print "</tr>\n";

$var = true;
krsort($Total);
foreach ($Total as $key => $value) {
	$var = !$var;
	print "<tr $bc[$var]>";
	print "<td><a href=\"adherent/cotisations.php?date_select=$key\">$key</a></td>";
	print "<td align=\"right\">" . $Number[$key] . "</td>";
	print "<td align=\"right\">" . price($value) . "</td>";
	print "<td align=\"right\">" . price(price2num($value / $Number[$key], 'MT')) . "</td>";
	$numb+=$Number[$key];
	$tot+=$value;
	print "</tr>\n";
}

// Total
print '<tr class="liste_total">';
print '<td>' . $langs->trans("Total") . '</td>';
print "<td align=\"right\">" . $numb . "</td>";
print '<td align="right">' . price($tot) . "</td>";
print "<td align=\"right\">" . price(price2num($numb > 0 ? ($tot / $numb) : 0, 'MT')) . "</td>";
print "</tr>\n";
print "</table><br>\n";

print '</td></tr>';
print '</table>';

print dol_fiche_end();

llxFooter();
$db->close();
?>
