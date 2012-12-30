<?php
/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2010-2012 Juanjo Menent 		<jmenent@2byte.es>
 * Copyright (C) 2005-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \file       htdocs/compta/prelevement/rejets.php
 *      \ingroup    prelevement
 *      \brief      Reject page
 */

require '../bank/pre.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/prelevement/class/rejetprelevement.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';

$langs->load("withdrawals");
$langs->load("companies");
$langs->load("categories");

// Security check
$socid = GETPOST('socid','int');
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'prelevement','','','bons');

// Get supervariables
$page = GETPOST('page','int');
$sortorder = GETPOST('sortorder','alpha');
$sortfield = GETPOST('sortfield','alpha');

/*
 * View
 */

llxHeader('',$langs->trans("WithdrawsRefused"));

$offset = $conf->liste_limit * $page ;
$pageprev = $page - 1;
$pagenext = $page + 1;

if ($sortorder == "") $sortorder="DESC";
if ($sortfield == "") $sortfield="p.datec";

$rej = new RejetPrelevement($db, $user);

/*
 * Liste des factures
 *
 */
$sql = "SELECT pl.rowid, pr.motif, p.ref, pl.statut";
$sql.= " , s.rowid as socid, s.nom";
$sql.= " FROM ".MAIN_DB_PREFIX."prelevement_bons as p";
$sql.= " , ".MAIN_DB_PREFIX."prelevement_rejet as pr";
$sql.= " , ".MAIN_DB_PREFIX."prelevement_lignes as pl";
$sql.= " , ".MAIN_DB_PREFIX."societe as s";
$sql.= " WHERE pr.fk_prelevement_lignes = pl.rowid";
$sql.= " AND pl.fk_prelevement_bons = p.rowid";
$sql.= " AND pl.fk_soc = s.rowid";
$sql.= " AND p.entity = ".$conf->entity;
if ($socid) $sql.= " AND s.rowid = ".$socid;
$sql .= " ORDER BY $sortfield $sortorder " . $db->plimit($conf->liste_limit+1, $offset);

$result = $db->query($sql);
if ($result)
{
	$num = $db->num_rows($result);
	$i = 0;

	print_barre_liste($langs->trans("WithdrawsRefused"), $page, "rejets.php", $urladd, $sortfield, $sortorder, '', $num);
	print"\n<!-- debut table -->\n";
	print '<table class="noborder" width="100%" cellspacing="0" cellpadding="4">';
	print '<tr class="liste_titre">';
	print_liste_field_titre($langs->trans("Nb"),"rejets.php","p.ref",'',$urladd);
	print_liste_field_titre($langs->trans("ThirdParty"),"rejets.php","s.nom",'',$urladd);
	print_liste_field_titre($langs->trans("Reason"),"rejets.php","pr.motif","",$urladd);
	print '</tr>';

	$var=True;

	$total = 0;

	while ($i < min($num,$conf->liste_limit))
	{
		$obj = $db->fetch_object($result);

		print "<tr $bc[$var]><td>";
		print '<img border="0" src="./img/statut'.$obj->statut.'.png"></a>&nbsp;';
		print '<a href="'.DOL_URL_ROOT.'/compta/prelevement/ligne.php?id='.$obj->rowid.'">';

		print substr('000000'.$obj->rowid, -6)."</a></td>";

		print '<td><a href="'.DOL_URL_ROOT.'/comm/fiche.php?socid='.$obj->socid.'">'.stripslashes($obj->nom)."</a></td>\n";

		print '<td>'.$rej->motifs[$obj->motif].'</td>';
		print "</tr>\n";
		$var=!$var;
		$i++;
	}

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
