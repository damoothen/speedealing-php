<?php

/* Copyright (C) 2001-2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo <jlb@j1b.org>
 * Copyright (C) 2004-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \file       htdocs/adherents/cotisations.php
 *      \ingroup    member
 * 		\brief      Page de consultation et insertion d'une cotisation
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent.class.php");
require_once(DOL_DOCUMENT_ROOT . "/compta/bank/class/account.class.php");

$langs->load("members");

$msg = '';
$date_select = GETPOST("date_select");

if (!$user->rights->adherent->cotisation->lire)
	accessforbidden();

// Static objects
$adherent = new Adherent($db);
$accountstatic = new Account($db);


/*
 * 	Actions
 */


/*
 * View
 */

llxHeader('', $langs->trans("ListOfSubscriptions"), 'EN:Module_Foundations|FR:Module_Adh&eacute;rents|ES:M&oacute;dulo_Miembros');

if ($msg)
	print $msg . '<br>';

if (!empty($date_select))
	$result = $adherent->getView('cotisationYear', array("key" => (int) $date_select));
else
	$result = $adherent->getView('cotisationYear');

$title = $langs->trans("ListOfSubscriptions");
if (!empty($date_select))
	$title.=' (' . $langs->trans("Year") . ' ' . $date_select . ')';

print '<div class="row">';

print start_box($titre, "twelve", "16-Money.png");

$i = 0;
$obj = new stdClass();
print '<div class="datatable">';
print '<table class="display dt_act" id="cotisation_datatable" >';
// Ligne des titres 
print'<thead>';
print'<tr>';
print'<th>';
print'</th>';
$obj->aoColumns[$i]->mDataProp = "_id";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->bVisible = false;
$i++;
print'<th class="essential">';
print $langs->trans("Id");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "login";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $adherent->datatablesFnRender("login", "url");
$i++;
print'<th class="essential">';
print $langs->trans('Firstname');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Firstname";
$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
$i++;
print'<th class="essential">';
print $langs->trans('Lastname');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Lastname";
$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
$i++;
print'<th class="essential">';
print $langs->trans('Label');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "note";
$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
$i++;
if ($conf->banque->enabled) {
	print'<th class="essential">';
	print $langs->trans('Account');
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "account";
	$obj->aoColumns[$i]->sDefaultContent = "";
	//$obj->aoColumns[$i]->sClass = "edit";
	$i++;
}
print'<th class="essential">';
print $langs->trans('Date');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "dateh";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->fnRender = $adherent->datatablesFnRender("dateh", "date");
$i++;
print'<th class="essential">';
print $langs->trans('DateEnd');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "datef";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->fnRender = $adherent->datatablesFnRender("datef", "date");
$i++;
print'<th class="essential">';
print $langs->trans('Amount');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "amount";
$obj->aoColumns[$i]->sClass = "fright";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $adherent->datatablesFnRender("amount", "price");
print'</tr>';
print'</thead>';
print'<tfoot>';
print'</tfoot>';
print'<tbody>';
$var = true;
$total = 0;
if (count($result->rows) > 0)
	foreach ($result->rows as $aRow) {
		$objp = $aRow->value;

		$total+=$objp->amount;

		$cotisation->ref = $objp->crowid;
		$cotisation->id = $objp->crowid;

		$adherent->Lastname = $objp->Lastname;
		$adherent->Firstname = $objp->Firstname;
		$adherent->ref = $adherent->getFullName($langs);
		$adherent->id = $objp->_id;
		$adherent->login = $objp->login;

		print "<tr>";

		print '<td>' . $objp->_id . '</td>';

		// Login
		print '<td>' . $objp->login . '</td>';
		print '<td>' . $objp->Firstname . '</td>';
		print '<td>' . $objp->Lastname . '</td>';

		// Libelle
		print '<td>';
		print dol_trunc($objp->note, 32);
		print '</td>';

		// Banque
		if ($conf->banque->enabled) {
			if ($objp->fk_account) {
				$accountstatic->id = $objp->fk_account;
				$accountstatic->fetch($objp->fk_account);
				//$accountstatic->label=$objp->label;
				print '<td>' . $accountstatic->getNomUrl(1) . '</td>';
			} else {
				print "<td>";
				if ($allowinsertbankafter && $objp->cotisation) {
					print '<input type="hidden" name="action" value="2bank">';
					print '<input type="hidden" name="rowid" value="' . $objp->crowid . '">';
					$form = new Form($db);
					$form->select_comptes('', 'accountid', 0, '', 1);
					print '<br>';
					$form->select_types_paiements('', 'paymenttypeid');
					print '<input name="num_chq" type="text" class="flat" size="5">';
				} else {
					print '&nbsp;';
				}
				print "</td>\n";
			}
		}

		// Date start
		print '<td>' . $objp->dateh . "</td>\n";

		// Date end
		print '<td>' . $objp->datef . "</td>\n";

		// Price
		print '<td>' . $objp->amount . '</td>';

		print "</tr>";
		$i++;
	}
print'</tbody>';
print "</table>";
print "</div>";

$obj->bServerSide = false;
$obj->sDom = 'l<fr>t<\"clear\"rtip>';
$obj->aaSorting = array(array(1, 'asc'));
$adherent->datatablesCreate($obj, "cotisation_datatable");

print end_box();
print '</div>'; // end row


$db->close();

llxFooter();
?>
