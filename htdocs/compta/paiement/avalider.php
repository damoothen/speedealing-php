<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 	\file       htdocs/compta/paiement/avalider.php
 * 	\ingroup    compta
 * 	\brief      Page liste des paiements a valider des factures clients
 */

require '../../main.inc.php';

$langs->load("bills");

// Security check
if (! $user->rights->facture->lire)
  accessforbidden();

$socid=0;
if ($user->societe_id > 0)
{
    $action = '';
    $socid = $user->societe_id;
}


/*
 * Affichage
 */

llxHeader();

$sortfield = GETPOST("sortfield",'alpha');
$sortorder = GETPOST("sortorder",'alpha');
$page = GETPOST("page",'int');
if ($page == -1) { $page = 0; }
$offset = $conf->liste_limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (! $sortorder) $sortorder="DESC";
if (! $sortfield) $sortfield="p.rowid";
$limit = $conf->liste_limit;

$sql = "SELECT p.rowid, p.datep as dp, p.amount, p.statut";
$sql.=", c.libelle as paiement_type, p.num_paiement";
$sql.= " FROM ".MAIN_DB_PREFIX."paiement as p, ".MAIN_DB_PREFIX."c_paiement as c";
if ($socid)
{
    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."paiement_facture as pf ON p.rowid = pf.fk_paiement";
    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."facture as f ON pf.fk_facture = f.rowid";
}
$sql.= " WHERE p.fk_paiement = c.id";
$sql.= " AND p.entity = ".$conf->entity;
if ($socid)
{
    $sql.= " AND f.fk_soc = ".$socid;
}
$sql.= " AND p.statut = 0";
$sql.= " ORDER BY $sortfield $sortorder";
$sql.= $db->plimit($limit+1, $offset);
$resql = $db->query($sql);

if ($resql)
{
    $num = $db->num_rows($resql);
    $i = 0;
    $var=True;

    print_barre_liste($langs->trans("ReceivedCustomersPaymentsToValid"), $page, "avalider.php","",$sortfield,$sortorder,'',$num);

    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print_liste_field_titre($langs->trans("Ref"),"avalider.php","p.rowid","","",'width="60"',$sortfield,$sortorder);
    print_liste_field_titre($langs->trans("Date"),"avalider.php","dp","","",'width="80" align="center"',$sortfield,$sortorder);
    print_liste_field_titre($langs->trans("Type"),"avalider.php","c.libelle","","","",$sortfield,$sortorder);
    print_liste_field_titre($langs->trans("AmountTTC"),"avalider.php","c.libelle","","",'align="right"',$sortfield,$sortorder);
    print "<td>&nbsp;</td>";
    print "</tr>\n";

    while ($i < min($num,$limit))
    {
        $objp = $db->fetch_object($resql);
        $var=!$var;
        print "<tr $bc[$var]>";
        print '<td><a href="'.DOL_URL_ROOT.'/compta/paiement/fiche.php?id='.$objp->rowid.'">'.img_object($langs->trans("ShowPayment"),"payment").' '.$objp->rowid.'</a></td>';
        print '<td width="80" align="center">'.dol_print_date($db->jdate($objp->dp),'day')."</td>\n";
        print "<td>$objp->paiement_type $objp->num_paiement</td>\n";
        print '<td align="right">'.price($objp->amount).'</td>';
        print '<td align="center">';

        if ($objp->statut == 0)
        {
            print '<a href="fiche.php?id='.$objp->rowid.'&amp;action=valide">'.$langs->trans("PaymentStatusToValidShort").'</a>';
        }
        else
        {
            print "-";
        }

        print '</td>';
        print "</tr>";
        $i++;
    }
    print "</table>";
}

$db->close();

llxFooter();
?>
