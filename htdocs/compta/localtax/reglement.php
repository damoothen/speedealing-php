<?php
/* Copyright (C) 2011		Juanjo Menent <jmenent@2byte.es>
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
 *	    \file       htdocs/compta/localtax/reglement.php
 *      \ingroup    tax
 *		\brief      List of IRPF payments
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/localtax/class/localtax.class.php';

$langs->load("compta");
$langs->load("compta");

// Security check
$socid = isset($_GET["socid"])?$_GET["socid"]:'';
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'tax', '', '', 'charges');

/*
 * View
 */

llxHeader();

$localtax_static = new Localtax($db);

print_fiche_titre($langs->transcountry("LT2Payments",$mysoc->country_code));

$sql = "SELECT rowid, amount, label, f.datev as dm";
$sql.= " FROM ".MAIN_DB_PREFIX."localtax as f ";
$sql.= " WHERE f.entity = ".$conf->entity;
$sql.= " ORDER BY dm DESC";

$result = $db->query($sql);
if ($result)
{
    $num = $db->num_rows($result);
    $i = 0;
    $total = 0 ;

    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print '<td nowrap align="left">'.$langs->trans("Ref").'</td>';
    print "<td>".$langs->trans("Label")."</td>";
    print '<td nowrap align="left">'.$langs->trans("DatePayment").'</td>';
    print "<td align=\"right\">".$langs->trans("PayedByThisPayment")."</td>";
    print "</tr>\n";
    $var=1;
    while ($i < $num)
    {
        $obj = $db->fetch_object($result);
        $var=!$var;
        print "<tr $bc[$var]>";

		$localtax_static->id=$obj->rowid;
		$localtax_static->ref=$obj->rowid;
		print "<td>".$localtax_static->getNomUrl(1)."</td>\n";
        print "<td>".dol_trunc($obj->label,40)."</td>\n";
        print '<td align="left">'.dol_print_date($db->jdate($obj->dm),'day')."</td>\n";
        $total = $total + $obj->amount;

        print "<td align=\"right\">".price($obj->amount)."</td>";
        print "</tr>\n";

        $i++;
    }
    print '<tr class="liste_total"><td colspan="3">'.$langs->trans("Total").'</td>';
    print "<td align=\"right\"><b>".price($total)."</b></td></tr>";

    print "</table>";
    $db->free($result);
}
else
{
    dol_print_error($db);
}

$db->close();

llxFooter();
?>
