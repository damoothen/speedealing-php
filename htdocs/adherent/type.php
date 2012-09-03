<?php

/* Copyright (C) 2001-2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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
 *      \file       htdocs/adherents/type.php
 *      \ingroup    member
 * 		\brief      Member's type setup
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent.class.php");

$langs->load("members");

$rowid = GETPOST('id', 'alpha');
$action = GETPOST('action', 'alpha');

// Security check
$result = restrictedArea($user, 'adherent', $rowid, 'adherent_type');

if (GETPOST('button_removefilter')) {
	$search_lastname = "";
	$search_login = "";
	$search_email = "";
	$type = "";
	$sall = "";
}

$object = new Adherent($db);

/*
 * View
 */

llxHeader('', $langs->trans("MembersTypeSetup"), 'EN:Module_Foundations|FR:Module_Adh&eacute;rents|ES:M&oacute;dulo_Miembros');

$form = new Form($db);


// Liste of members type

if (!$rowid && $action != 'create' && $action != 'edit') {

	print_fiche_titre($langs->trans("MembersTypes"));
	print '<div class="with-padding">';

	$i = 0;
	$obj = new stdClass();
	print '<div class="datatable">';
	print '<table class="display dt_act" id="membertype" >';
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
	$obj->aoColumns[$i]->mDataProp = "tag";
	$obj->aoColumns[$i]->bUseRendered = false;
	$obj->aoColumns[$i]->bSearchable = true;
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("tag", "url", array("url" => $_SERVER["PHP_SELF"] . '?id='));
	$i++;
	print'<th class="essential">';
	print $langs->trans('Total');
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "nb";
	$obj->aoColumns[$i]->sDefaultContent = 0;
	$i++;
	print'</tr>';
	print'</thead>';
	print'<tfoot>';
	print'</tfoot>';
	print'<tbody>';

	$result = $object->getView('tag', array("group" => true));
	if (count($result->rows) > 0)
		foreach ($result->rows as $aRow) {
			$nb = $aRow->value;
			$tmp_id = $aRow->key;

			print "<tr>";
			print '<td>';
			print $tmp_id;
			print '</td>';
			print '<td>';
			print $tmp_id . '</td>';
			print '<td align="right">' . $nb . '</td>';
			print "</tr>";
			$i++;
		}
	print'</tbody>';
	print "</table>";
	print "</div>";

	$obj->bServerSide = false;
	$obj->aaSorting = array(array(1, "asc"));
	$obj->sDom = 'l<fr>t<\"clear\"rtip>';
	$object->datatablesCreate($obj, "membertype", false, true);

	print "</div>";
}


/* * ************************************************************************* */
/*                                                                            */
/* Edition de la fiche                                                        */
/*                                                                            */
/* * ************************************************************************* */
if ($rowid) {

	$titre = $langs->trans("MemberType");

	

	print_fiche_titre($titre . " - " . $rowid);
	print '<div class="with-padding">';
	print '<div class="columns">';

	// Show list of members (nearly same code than in page liste.php)

	$titre = $langs->trans("Members");
	print start_box($titre, "six", "16-Users.png");

	$i = 0;
	$obj = new stdClass();
	print '<table class="display dt_act" id="member" >';
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
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("login", "url");
	$i++;
	print'<th class="essential">';
	print $langs->trans('Name');
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "Lastname";
	$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
	$i++;
	print'<th class="essential">';
	print $langs->trans('Firstname');
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "Firstname";
	$obj->aoColumns[$i]->sDefaultContent = "";
	//$obj->aoColumns[$i]->sClass = "edit";
	$i++;
	print'<th class="essential">';
	print $langs->trans("Status");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "Status";
	$obj->aoColumns[$i]->sClass = "center";
	$obj->aoColumns[$i]->sWidth = "180px";
	$obj->aoColumns[$i]->sDefaultContent = "0";
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status", array("dateEnd" => "last_subscription_date_end"));
	$i++;
	print'<th class="essential">';
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "last_subscription_date_end";
	$obj->aoColumns[$i]->sDefaultContent = "";
	//$obj->aoColumns[$i]->sClass = "edit";
	$obj->aoColumns[$i]->bVisible = false;
	print'</tr>';
	print'</thead>';
	print'<tfoot>';
	print'</tfoot>';
	print'<tbody>';
	$result = $object->getView('group', array("key" => $rowid));
	if (count($result->rows) > 0)
		foreach ($result->rows as $aRow) {
			print '<tr>';
			print '<td>' . $aRow->value->_id . '</td>';
			print '<td>' . $aRow->value->login . '</td>';
			print '<td>' . $aRow->value->Lastname . '</td>';
			print '<td>' . $aRow->value->Firstname . '</td>';
			print '<td>' . (empty($aRow->value->Status) ? "0" : $aRow->value->Status) . '</td>';
			print '<td>' . $aRow->value->last_subscription_date_end . '</td>';
			print '</tr>';
		}
	print'</tbody>';

	print "</table>";

	$obj->sDom = 'l<fr>t<\"clear\"rtip>';
	$obj->bServerSide = false;
	$obj->iDisplayLength = 10;
	$obj->aaSorting = array(array(1, 'asc'));
	$object->datatablesCreate($obj, "member");

	print '<div class="tabsAction">';

	// Add
	print '<a class="butAction" href="adherent/fiche.php?action=create&typeid=' . $rowid . '">' . $langs->trans("AddMember") . '</a>';

	print "</div>";

	print end_box();


	// Messaging

	$titre = $langs->trans("Messenger");
	print start_box($titre, "six", "16-Mail.png");

	print end_box();
	print '</div></div>';
}

$db->close();

llxFooter();
?>
