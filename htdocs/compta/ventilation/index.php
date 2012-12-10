<?php
/* Copyright (C) 2001-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2005 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *   \file       htdocs/compta/ventilation/index.php
 *   \ingroup    compta
 *   \brief      Page accueil ventilation
 */

require '../../main.inc.php';

$langs->load("compta");
$langs->load("bills");

llxHeader('','Compta - Ventilation');

print_fiche_titre("Ventilation Comptable");

print '<table border="0" width="100%" class="notopnoleftnoright">';

print '<tr><td valign="top" width="30%" class="notopnoleft">';

$sql = "SELECT count(*) FROM ".MAIN_DB_PREFIX."facturedet as fd";
$sql.= " , ".MAIN_DB_PREFIX."facture as f";
$sql.= " WHERE fd.fk_code_ventilation = 0";
$sql.= " AND f.rowid = fd.fk_facture AND f.fk_statut = 1;";

$result = $db->query($sql);
if ($result)
{
  $row = $db->fetch_row($result);
  $nbfac = $row[0];

  $db->free($result);
}

$var=true;

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre"><td colspan="2">'.$langs->trans("Lines").'</tr>';
print '<tr class="liste_titre"><td>'.$langs->trans("Type").'</td><td align="right">'.$langs->trans("Nb").'</td></tr>';
$var=!$var;
print "<tr $bc[$var]>".'<td>'.$langs->trans("Invoices").'</td><td align="right">'.$nbfac.'</td></tr>';
$var=!$var;
print "</table>\n";

print '</td><td valign="top" width="70%" class="notopnoleftnoright">';

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre"><td>Type</td><td align="center">'.$langs->trans("NbOfLines").'</td><td align="center">'.$langs->trans("AccountNumber").'</td><td align="center">'.$langs->trans("TransID").'</td></tr>';

$sql = "SELECT count(*), ccg.intitule, ccg.rowid,ccg.numero FROM ".MAIN_DB_PREFIX."facturedet as fd";
$sql.= " ,".MAIN_DB_PREFIX."compta_compte_generaux as ccg";
$sql.= " WHERE fd.fk_code_ventilation = ccg.rowid";
$sql.= " GROUP BY ccg.rowid";

$resql = $db->query($sql);
if ($resql)
{
    $i = 0;
    $num = $db->num_rows($resql);
    $var=true;

    while ($i < $num)
    {

        $row = $db->fetch_row($resql);
        $var=!$var;
        print '<tr '.$bc[$var].'><td>'.$row[1].'</td><td align="center">'.$row[0].'</td>';
        print '<td align="center">'.$row[3].'</td><td align="center">'.$row[2].'</td></tr>';
        $i++;
    }
    $db->free($resql);
}
print "</table>\n";

print '</td></tr></table>';

llxFooter();

?>
