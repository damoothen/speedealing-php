<?php
/* Copyright (C) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 * \file 		htdocs/admin/osc-languages.php
 * \ingroup    	boutique
 * \brief      	Page d'administration/configuration du module Boutique
 */

require '../main.inc.php';

$langs->load("admin");

if (!$user->admin)
accessforbidden();


llxHeader();


if (! dol_strlen(OSC_DB_NAME))
{
	print "Non dispo";
	llxFooter();
}

if ($sortfield == "") {
	$sortfield="lower(p.label),p.price";
}
if ($sortorder == "") {
	$sortorder="ASC";
}

if ($page == -1) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;


print_barre_liste("Liste des langues oscommerce", $page, "osc-languages.php");

$sql = "SELECT l.languages_id, l.name, l.code FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."languages as l";
$sql.= $db->plimit($limit, $offset);

print "<p><TABLE border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">";
print "<tr class=\"liste_titre\">";
print "<td>id</td>";
print "<td>Name</td>";
print "<td>Code</td>";
print "</TR>\n";

$resql=$db->query($sql);
if ($resql)
{
	$num = $db->num_rows($resql);
	$i = 0;

	$var=True;
	while ($i < $num) {
		$objp = $db->fetch_object($resql);
		$var=!$var;
		print "<TR $bc[$var]>";
		print "<TD>$objp->languages_id</TD>\n";
		print "<TD>$objp->name</TD>\n";
		print "<TD>$objp->code</TD>\n";
		print "</TR>\n";
		$i++;
	}
	$db->free();
}

print "</TABLE>";


$db->close();

llxFooter();
?>
