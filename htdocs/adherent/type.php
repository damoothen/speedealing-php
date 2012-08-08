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
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent_type.class.php");

$langs->load("members");

$rowid = GETPOST('id', 'alpha');
$action = GETPOST('action', 'alpha');

$search_lastname = GETPOST('search_nom', 'alpha');
$search_login = GETPOST('search_login', 'alpha');
$search_email = GETPOST('search_email', 'alpha');
$type = GETPOST('type', 'alpha');
$status = GETPOST('status', 'alpha');

$sortfield = GETPOST('sortfield', 'alpha');
$sortorder = GETPOST('sortorder', 'alpha');
$page = GETPOST('page', 'int');
if ($page == -1) {
	$page = 0;
}
$offset = $conf->liste_limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortorder) {
	$sortorder = "DESC";
}
if (!$sortfield) {
	$sortfield = "d.nom";
}

// Security check
$result = restrictedArea($user, 'adherent', $rowid, 'adherent_type');

if (GETPOST('button_removefilter')) {
	$search_lastname = "";
	$search_login = "";
	$search_email = "";
	$type = "";
	$sall = "";
}

$object = new AdherentType($db);

/*
 * 	Actions
 */
if ($action == 'add' && $user->rights->adherent->configurer) {
	if ($_POST["button"] != $langs->trans("Cancel")) {
		$adht = new AdherentType($db);

		$adht->libelle = trim($_POST["libelle"]);
		$adht->cotisation = trim($_POST["cotisation"]);
		$adht->note = trim($_POST["comment"]);
		$adht->mail_valid = trim($_POST["mail_valid"]);
		$adht->vote = trim($_POST["vote"]);

		if ($adht->libelle) {
			$id = $adht->create($user->id);
			if ($id > 0) {
				Header("Location: " . $_SERVER["PHP_SELF"]);
				exit;
			} else {
				$mesg = $adht->error;
				$action = 'create';
			}
		} else {
			$mesg = $langs->trans("ErrorFieldRequired", $langs->transnoentities("Label"));
			$action = 'create';
		}
	}
}

if ($action == 'update' && $user->rights->adherent->configurer) {
	if ($_POST["button"] != $langs->trans("Cancel")) {
		$adht = new AdherentType($db);
		$adht->load($rowid);
		$adht->libelle = trim($_POST["libelle"]);
		$adht->cotisation = trim($_POST["cotisation"]);
		$adht->note = trim($_POST["comment"]);
		$adht->mail_valid = trim($_POST["mail_valid"]);
		$adht->vote = trim($_POST["vote"]);

		$adht->update($user->id);

		Header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $rowid);
		exit;
	}
}

if ($action == 'delete' && $user->rights->adherent->configurer) {

	$adht->delete($rowid);
	Header("Location: " . $_SERVER["PHP_SELF"]);
	exit;
}

if ($action == 'commentaire' && $user->rights->adherent->configurer) {
	$don = new Don($db);
	$don->fetch($rowid);
	$don->update_note($_POST["commentaire"]);
}


/*
 * View
 */

llxHeader('', $langs->trans("MembersTypeSetup"), 'EN:Module_Foundations|FR:Module_Adh&eacute;rents|ES:M&oacute;dulo_Miembros');

$form = new Form($db);


// Liste of members type

if (!$rowid && $action != 'create' && $action != 'edit') {

	print_fiche_titre($langs->trans("MembersTypes"));

	$result = $object->getView('list');

	$i = 0;

	print '<table class="noborder" width="100%">';

	print '<tr class="liste_titre">';
	print '<td>' . $langs->trans("Group") . '</td>';
	print '<td align="center">' . $langs->trans("SubscriptionRequired") . '</td>';
	print '<td align="center">' . $langs->trans("VoteAllowed") . '</td>';
	print '<td>&nbsp;</td>';
	print "</tr>\n";

	$var = true;

	if (count($result->rows) > 0)
		foreach ($result->rows as $aRow) {
			$objp = $aRow->value;
			$objp->id = $objp->_id;

			$var = !$var;
			print "<tr " . $bc[$var] . ">";
			print '<td><a href="' . $_SERVER["PHP_SELF"] . '?id=' . $objp->id . '">' . img_object($langs->trans("ShowType"), 'group') . ' ' . $objp->libelle . '</a></td>';
			print '<td align="center">' . yn($objp->cotisation) . '</td>';
			print '<td align="center">' . yn($objp->vote) . '</td>';
			print '<td align="right"><a href="' . $_SERVER["PHP_SELF"] . '?action=edit&id=' . $objp->id . '">' . img_edit() . '</a></td>';
			print "</tr>";
			$i++;
		}
	print "</table>";


	/*
	 * Barre d'actions
	 *
	 */
	print '<div class="tabsAction">';

	// New type
	if ($user->rights->adherent->configurer) {
		print '<a class="butAction" href="' . $_SERVER['PHP_SELF'] . '?action=create">' . $langs->trans("NewType") . '</a>';
	}

	print "</div>";
}


/* * ************************************************************************* */
/*                                                                            */
/* Creation d'un type adherent                                                */
/*                                                                            */
/* * ************************************************************************* */
if ($action == 'create') {
	$form = new Form($db);

	print_fiche_titre($langs->trans("NewMemberType"));

	if ($mesg)
		print '<div class="error">' . $mesg . '</div>';

	print '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<table class="border" width="100%">';

	print '<input type="hidden" name="action" value="add">';

	print '<tr><td class="fieldrequired">' . $langs->trans("Label") . '</td><td><input type="text" name="libelle" size="40"></td></tr>';

	print '<tr><td>' . $langs->trans("SubscriptionRequired") . '</td><td>';
	print $form->selectyesno("cotisation", 1, 1);
	print '</td></tr>';

	print '<tr><td>' . $langs->trans("VoteAllowed") . '</td><td>';
	print $form->selectyesno("vote", 0, 1);
	print '</td></tr>';

	print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td>';
	print '<textarea name="comment" wrap="soft" cols="60" rows="3"></textarea></td></tr>';

	print '<tr><td valign="top">' . $langs->trans("WelcomeEMail") . '</td><td>';
	require_once(DOL_DOCUMENT_ROOT . "/core/class/doleditor.class.php");
	$doleditor = new DolEditor('mail_valid', $adht->mail_valid, '', 280, 'dolibarr_notes', '', false, true, $conf->fckeditor->enabled, 15, 90);
	$doleditor->Create();
	print '</td></tr>';

	print "</table>\n";

	print '<br>';
	print '<center><input type="submit" name="button" class="button" value="' . $langs->trans("Add") . '"> &nbsp; &nbsp; ';
	print '<input type="submit" name="button" class="button" value="' . $langs->trans("Cancel") . '"></center>';

	print "</form>\n";
}

/* * ************************************************************************* */
/*                                                                            */
/* Edition de la fiche                                                        */
/*                                                                            */
/* * ************************************************************************* */
if ($rowid > 0) {
	if ($action != 'edit') {
		$adht = new AdherentType($db);
		$adht->fetch($rowid);

		print '<div class="row">';

		$titre = $langs->trans("MemberType");
		print start_box($titre, "twelve", "16-Users.png");

		dol_fiche_head($head, 'card', $langs->trans("MemberType"), 0, 'group');


		print '<table class="border" width="100%">';

		// Ref
		print '<tr><td width="15%">' . $langs->trans("Ref") . '</td>';
		print '<td>';
		print $form->showrefnav($adht, 'id');
		print '</td></tr>';

		// Label
		print '<tr><td width="15%">' . $langs->trans("Label") . '</td><td>' . $adht->libelle . '</td></tr>';

		print '<tr><td>' . $langs->trans("SubscriptionRequired") . '</td><td>';
		print yn($adht->cotisation);
		print '</tr>';

		print '<tr><td>' . $langs->trans("VoteAllowed") . '</td><td>';
		print yn($adht->vote);
		print '</tr>';

		print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td>';
		print nl2br($adht->note) . "</td></tr>";

		print '<tr><td valign="top">' . $langs->trans("WelcomeEMail") . '</td><td>';
		print nl2br($adht->mail_valid) . "</td></tr>";

		print '</table>';

		print '</div>';

		/*
		 * Barre d'actions
		 *
		 */
		print '<div class="tabsAction">';

		// Edit
		if ($user->rights->adherent->configurer) {
			print '<a class="butAction" href="' . $_SERVER['PHP_SELF'] . '?action=edit&amp;id=' . $adht->id . '">' . $langs->trans("Modify") . '</a>';
		}

		// Delete
		if ($user->rights->adherent->configurer) {
			print '<a class="butActionDelete" href="' . $_SERVER['PHP_SELF'] . '?action=delete&id=' . $adht->id . '">' . $langs->trans("DeleteType") . '</a>';
		}

		print "</div>";

		print end_box();
		print "</div>";


		// Show list of members (nearly same code than in page liste.php)

		print '<div class="row">';

		$titre = $langs->trans("MemberType");
		print start_box($titre, "six", "16-User.png");

		$object = new Adherent($db);

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
		$obj->aoColumns[$i]->mDataProp = "lastname";
		$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
		$i++;
		print'<th class="essential">';
		print $langs->trans('Firstname');
		print'</th>';
		$obj->aoColumns[$i]->mDataProp = "firstname";
		$obj->aoColumns[$i]->sDefaultContent = "";
		//$obj->aoColumns[$i]->sClass = "edit";
		$i++;
		print'<th class="essential">';
		print $langs->trans("Status");
		print'</th>';
		$obj->aoColumns[$i]->mDataProp = "Status";
		$obj->aoColumns[$i]->sClass = "select center";
		$obj->aoColumns[$i]->sWidth = "180px";
		$obj->aoColumns[$i]->sDefaultContent = "0";
		$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status", array("dateEnd"=>"last_subscription_date_end"));
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
		$result = $object->getView('group', array("key" => $adht->libelle));
		if (count($result->rows) > 0)
			foreach ($result->rows as $aRow) {
				print '<tr>';
				print '<td>' . $aRow->value->_id . '</td>';
				print '<td>' . $aRow->value->login . '</td>';
				print '<td>' . $aRow->value->lastname . '</td>';
				print '<td>' . $aRow->value->firstname . '</td>';
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
		print '<a class="butAction" href="adherent/fiche.php?action=create&typeid=' . $adht->libelle . '">' . $langs->trans("AddMember") . '</a>';

		print "</div>";

		print end_box();


		// Messaging

		$titre = $langs->trans("Messenger");
		print start_box($titre, "six", "16-Mail.png");

		print end_box();
	}

	if ($action == 'edit') {
		$form = new Form($db);

		$adht = new AdherentType($db);
		$adht->load($rowid);

		$h = 0;

		$head[$h][0] = $_SERVER["PHP_SELF"] . '?id=' . $adht->id;
		$head[$h][1] = $langs->trans("Card");
		$head[$h][2] = 'card';
		$h++;

		dol_fiche_head($head, 'card', $langs->trans("MemberType"), 0, 'group');

		print '<form method="post" action="' . $_SERVER["PHP_SELF"] . '?id=' . $rowid . '">';
		print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
		print '<input type="hidden" name="id" value="' . $rowid . '">';
		print '<input type="hidden" name="action" value="update">';
		print '<table class="border" width="100%">';

		print '<tr><td width="15%">' . $langs->trans("Ref") . '</td><td>' . $adht->id . '</td></tr>';

		print '<tr><td>' . $langs->trans("Label") . '</td><td><input type="text" name="libelle" size="40" value="' . $adht->libelle . '"></td></tr>';

		print '<tr><td>' . $langs->trans("SubscriptionRequired") . '</td><td>';
		print $form->selectyesno("cotisation", $adht->cotisation, 1);
		print '</td></tr>';

		print '<tr><td>' . $langs->trans("VoteAllowed") . '</td><td>';
		print $form->selectyesno("vote", $adht->vote, 1);
		print '</td></tr>';

		print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td>';
		print '<textarea name="comment" wrap="soft" cols="90" rows="3">' . $adht->note . '</textarea></td></tr>';

		print '<tr><td valign="top">' . $langs->trans("WelcomeEMail") . '</td><td>';
		require_once(DOL_DOCUMENT_ROOT . "/core/class/doleditor.class.php");
		$doleditor = new DolEditor('mail_valid', $adht->mail_valid, '', 280, 'dolibarr_notes', '', false, true, $conf->fckeditor->enabled, 15, 90);
		$doleditor->Create();
		print "</td></tr>";

		print '</table>';

		print '<center><input type="submit" class="button" value="' . $langs->trans("Save") . '"> &nbsp; &nbsp;';
		print '<input type="submit" name="button" class="button" value="' . $langs->trans("Cancel") . '"></center>';

		print "</form>";
	}
}

$db->close();

print dol_fiche_end();

llxFooter();
?>
