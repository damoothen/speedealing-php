<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
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
 *       \file       htdocs/user/group/fiche.php
 *       \brief      Onglet groupes utilisateurs
 */
require("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/user/class/usergroup.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/usergroups.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/user/class/userdatabase.class.php");

// Defini si peux lire/modifier utilisateurs et permisssions
$canreadperms = ($user->admin || $user->rights->user->user->lire);
$caneditperms = ($user->admin || $user->rights->user->user->creer);
$candisableperms = ($user->admin || $user->rights->user->user->supprimer);

$langs->load("users");
$langs->load("other");

$id = GETPOST('id', 'alpha');
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$userid = GETPOST('user', 'alpha');
$databaseid = GETPOST('databaseid', 'alpha');

// Security check
$result = restrictedArea($user, 'user', $id, 'usergroup&usergroup', 'user');

$object = new Usergroup($db);


/**
 *  Action remove group
 */
if ($action == 'confirm_delete' && $confirm == "yes") {
	if ($caneditperms) {
		$object->fetch($id);
		$object->delete();
		Header("Location: index.php");
		exit;
	} else {
		$langs->load("errors");
		$message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
	}
}

/**
 *  Action add group
 */
if ($action == 'add') {
	if ($caneditperms) {
		$message = "";
		if (!$_POST["nom"]) {
			$message = '<div class="error">' . $langs->trans("NameNotDefined") . '</div>';
			$action = "create";	// Go back to create page
		}

		if (!$message) {
			$object->values->name = trim($_POST["nom"]);
			$object->values->note = trim($_POST["note"]);
			$object->values->_id = "group:".$object->values->name;

			$object->record();

			Header("Location: fiche.php?id=" . $object->id);
			exit;
		}
	} else {
		$langs->load("errors");
		$message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
	}
}

// Add/Remove user into group
if ($action == 'adduser' || $action == 'removeuser') {
	if ($caneditperms) {
		if ($userid) {

			$object->load($id);

			if ($action == 'adduser') {
				$object->couchAdmin->addRoleToUser($userid, $object->values->name);
			}
			if ($action == 'removeuser') {
				$object->couchAdmin->removeRoleFromUser($userid, $object->values->name);
			}

			if ($result > 0) {
				header("Location: fiche.php?id=" . $object->id);
				exit;
			} else {
				$message.=$edituser->error;
			}
		}
	} else {
		$langs->load("errors");
		$message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
	}
}

// Add/Remove database into roles
if ($action == 'adddatabase' || $action == 'removedatabase') {
	if ($caneditperms) {
		if ($databaseid) {

			$object->load($id);
			
			$database = new UserDatabase($db);
			$database->fetch($databaseid);

			if ($action == 'adddatabase') {
				if($_POST['admin'])
					$database->couchAdmin->addDatabaseAdminRole($object->values->name);
				else
					$database->couchAdmin->addDatabaseReaderRole($object->values->name);
			}
			if ($action == 'removedatabase') {
				$database->couchAdmin->removeDatabaseAdminRole($object->values->name);
				$database->couchAdmin->removeDatabaseReaderRole($object->values->name);
			}

			if ($result > 0) {
				header("Location: fiche.php?id=" . $object->id);
				exit;
			} else {
				$message.=$edituser->error;
			}
		}
	} else {
		$langs->load("errors");
		$message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
	}
}


if ($action == 'update') {
	if ($caneditperms) {
		$message = "";

		$db->begin();

		$object->fetch($id);

		$object->oldcopy = dol_clone($object);

		$object->nom = trim($_POST["group"]);
		$object->note = dol_htmlcleanlastbr($_POST["note"]);

		if ($conf->multicompany->enabled && !empty($conf->global->MULTICOMPANY_TRANSVERSE_MODE))
			$object->entity = 0;
		else
			$object->entity = $_POST["entity"];

		$ret = $object->update();

		if ($ret >= 0 && !count($object->errors)) {
			$message.='<div class="ok">' . $langs->trans("GroupModified") . '</div>';
			$db->commit();
		} else {
			$message.='<div class="error">' . $object->error . '</div>';
			$db->rollback();
		}
	} else {
		$langs->load("errors");
		$message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
	}
}



/*
 * View
 */

llxHeader('', $langs->trans("GroupCard"));

$form = new Form($db);
$fuserstatic = new User($db);

if ($action == 'create') {
	print_fiche_titre($langs->trans("NewGroup"));

	if ($message) {
		print $message . "<br>";
	}

	print '<form action="' . $_SERVER["PHP_SELF"] . '" method="post">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<input type="hidden" name="action" value="add">';

	print '<table class="border" width="100%">';

	print "<tr>" . '<td valign="top" class="fieldrequired">' . $langs->trans("Name") . '</td>';
	print '<td class="valeur"><input size="30" type="text" name="nom" value=""></td></tr>';

	print "<tr>" . '<td valign="top">' . $langs->trans("Note") . '</td><td>';
	require_once(DOL_DOCUMENT_ROOT . "/core/class/doleditor.class.php");
	$doleditor = new DolEditor('note', '', '', 240, 'dolibarr_notes', '', false, true, $conf->global->FCKEDITOR_ENABLE_SOCIETE, ROWS_8, 90);
	$doleditor->Create();
	print "</td></tr>\n";
	print "</table>\n";

	print '<center><br><input class="button small nice" value="' . $langs->trans("CreateGroup") . '" type="submit"></center>';

	print "</form>";
	dol_fiche_end();
}


/* * ************************************************************************* */
/*                                                                            */
/* Visu et edition                                                            */
/*                                                                            */
/* * ************************************************************************* */ else {
	if ($id) {
		$object->load($id);

		/*
		 * Affichage onglets
		 */
		$head = group_prepare_head($object);
		$title = $langs->trans("Group");
		print '<div class="row">';
		print start_box($title, "twelve", "16-Users-2.png", false);

		/*
		 * Confirmation suppression
		 */
		if ($action == 'delete') {
			$ret = $form->form_confirm($_SERVER['PHP_SELF'] . "?id=" . $object->id, $langs->trans("DeleteAGroup"), $langs->trans("ConfirmDeleteGroup", $object->name), "confirm_delete", '', 0, 1);
			if ($ret == 'html')
				print '<br>';
		}

		/*
		 * Fiche en mode visu
		 */

		if ($action != 'edit') {
			/*
			 * Barre d'actions
			 */
			print '<div class="row sepH_a">';
			print '<div class="gh_button-group right">';

			if ($caneditperms) {
				print '<a class="gh_button pill primary" href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&amp;action=edit">' . $langs->trans("Modify") . '</a>';
			}

			if ($candisableperms) {
				print '<a class="gh_button pill icon trash danger" href="' . $_SERVER['PHP_SELF'] . '?action=delete&amp;id=' . $object->id . '">' . $langs->trans("Delete") . '</a>';
			}

			print "</div>\n";
			print "</div>\n";

			print '<table class="border" width="100%">';

			// Ref
			print '<tr><td width="25%" valign="top">' . $langs->trans("Ref") . '</td>';
			print '<td colspan="2">';
			print $form->showrefnav($object, 'id', '', $user->rights->user->user->lire || $user->admin);
			print '</td>';
			print '</tr>';

			// Name
			print '<tr><td width="25%" valign="top">' . $langs->trans("Name") . '</td>';
			print '<td width="75%" class="valeur">' . $object->values->name;
			print "</td></tr>\n";

			print "</table>\n";

			print "</div>\n";

			print '</div>';
			print '</div>';


			dol_htmloutput_mesg($message);

			/*
			 * Liste des utilisateurs dans le groupe
			 */

			print '<div class="row">';
			print start_box($langs->trans("ListOfUsersInGroup"), "twelve", "16-User-2.png", false);

			// On selectionne les users qui ne sont pas deja dans le groupe
			$exclude = array();

			$userstatic = new User($db);
			$result = $userstatic->getView("roles", array('key' => $object->values->name));

			if (count($result->rows)) {
				foreach ($result->rows as $useringroup) {
					$exclude[] = $useringroup->value->_id;
				}
			}

			if ($caneditperms) {
				print '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '" method="POST">' . "\n";
				print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
				print '<input type="hidden" name="action" value="adduser">';
				print '<table class="noborder" width="100%">' . "\n";
				print '<tr class="liste_titre"><td class="liste_titre" width="25%">' . $langs->trans("NonAffectedUsers") . '</td>' . "\n";
				print '<td>';
				print $form->select_dolusers('', 'user', 1, $exclude, 0, '', '');
				print '</td><td>';
				print '<input type="submit" class="tiny nice button" value="' . $langs->trans("Add") . '">';
				print '</td></tr>' . "\n";
				print '</table></form>' . "\n";
				print '<br>';
			}

			/*
			 * Group members
			 */
			$obj = new stdClass();
			$i = 0;
			print '<table class="display" id="users">';
			print '<thead>';
			print '<tr>';
			print '<th>' . $langs->trans("Login") . '</th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$i++;
			print '<th>' . $langs->trans("Lastname") . '</th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$i++;
			print '<th>' . $langs->trans("Firstname") . '</th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$i++;
			print '<th>' . $langs->trans("Status") . '</th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$obj->aoColumns[$i]->sClass = "center";
			$i++;
			print '<th></th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$obj->aoColumns[$i]->sClass = "fright content_actions";
			$i++;
			print "</tr>\n";
			print '</thead>';

			print '<tbody>';
			if (count($result->rows)) {
				$var = True;

				foreach ($result->rows as $aRow) {
					$var = !$var;

					$useringroup = new User($db);
					$useringroup->values = $aRow->value;
					$useringroup->admin = $useringroup->values->Administrator;
					$useringroup->id = $useringroup->values->_id;

					print "<tr $bc[$var]>";
					print '<td>';
					print '<a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $useringroup->id . '">' . img_object($langs->trans("ShowUser"), "user") . ' ' . $useringroup->values->name . '</a>';
					if ($useringroup->admin)
						print img_picto($langs->trans("Administrator"), 'star');
					print '</td>';
					print '<td>' . $useringroup->values->Lastname . '</td>';
					print '<td>' . $useringroup->values->Firstname . '</td>';
					print '<td>' . $useringroup->getLibStatus() . '</td>';
					print '<td>';
					if ($user->admin) {
						print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&amp;action=removeuser&amp;user=' . $useringroup->values->name . '">';
						print img_delete($langs->trans("RemoveFromGroup"));
					} else {
						print "-";
					}
					print "</td></tr>\n";
				}
			}
			print '<tbody>';
			print "</table>";

			$obj->aaSorting = array(array(0, "asc"));
			$obj->sDom = 'l<fr>t<\"clear\"rtip>';

			$object->datatablesCreate($obj, "users");

			print '</div>';
			print '</div>';

			/*
			 * Liste des databases du groupe / role
			 */

			print '<div class="row">';
			print start_box($langs->trans("ListOfRolesInDatabase"), "twelve", "16-Cloud.png", false);

			// On selectionne les users qui ne sont pas deja dans le groupe
			$exclude = array();
			
			//print_r($object->database);

			if (count($object->databases)) {
				foreach ($object->databases as $dbingroup) {
					$exclude[] = $dbingroup->db_name;
				}
			}

			if ($caneditperms) {
				print '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '" method="POST">' . "\n";
				print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
				print '<input type="hidden" name="action" value="adddatabase">';
				print '<table class="noborder" width="100%">' . "\n";
				print '<tr class="liste_titre"><td class="liste_titre" width="25%">' . $langs->trans("NonAffectedUsers") . '</td>' . "\n";
				print '<td>';
				print $form->select_doldatabases('', 'databaseid', 1, $exclude, 0, '', '');
				print "</td>\n";
				//print '<td valign="top">' . $langs->trans("Administrator") . '</td>';
				//print "<td>" . $form->selectyesno('admin', 0, 1);
				//print "</td>\n";
				print '</td><td>';
				print '<input type="submit" class="tiny nice button" value="' . $langs->trans("Add") . '">';
				print '</td></tr>' . "\n";
				print '</table></form>' . "\n";
				print '<br>';
			}

			/*
			 * Group members
			 */
			$obj = new stdClass();
			$i = 0;
			print '<table class="display" id="databases">';
			print '<thead>';
			print '<tr>';
			print '<th>' . $langs->trans("Group") . '</th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$i++;
			print '<th></th>';
			$obj->aoColumns[$i]->mDataProp = "";
			$obj->aoColumns[$i]->sClass = "fright content_actions";
			$i++;
			print "</tr>\n";
			print '</thead>';

			print '<tbody>';
			if (count($object->databases)) {
				$var = True;

				foreach ($object->databases as $aRow) {
					$var = !$var;

					print "<tr $bc[$var]>";
					print '<td>';
					print '<a href="' . DOL_URL_ROOT . '/user/database/fiche.php?id=' . $aRow->db_name. '">' . img_object($langs->trans("ShowDatabase"), "database") . ' ' . $aRow->db_name . '</a>';
					if ($aRow->Administrator)
						print img_picto($langs->trans("Administrator"), 'star');
					print '</td>';
					print '<td>';
					if ($user->admin) {
						print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&amp;action=removedatabase&amp;databaseid=' . $aRow->db_name . '">';
						print img_delete($langs->trans("RemoveFromGroup"));
					} else {
						print "-";
					}
					print "</td></tr>\n";
				}
			}
			print '<tbody>';
			print "</table>";

			$obj->aaSorting = array(array(0, "asc"));
			$obj->sDom = 'l<fr>t<\"clear\"rtip>';

			$object->datatablesCreate($obj, "databases");

			print '</div>';
			print '</div>';
		}

		/*
		 * Fiche en mode edition
		 */
		if ($action == 'edit' && $caneditperms) {
			print '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '" method="post" name="updategroup" enctype="multipart/form-data">';
			print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
			print '<input type="hidden" name="action" value="update">';

			print '<table class="border" width="100%">';
			print '<tr><td width="25%" valign="top" class="fieldrequired">' . $langs->trans("Name") . '</td>';
			print '<td width="75%" class="valeur"><input size="15" type="text" name="group" value="' . $object->nom . '">';
			print "</td></tr>\n";

			print '<tr><td width="25%" valign="top">' . $langs->trans("Note") . '</td>';
			print '<td class="valeur">';
			require_once(DOL_DOCUMENT_ROOT . "/core/class/doleditor.class.php");
			$doleditor = new DolEditor('note', $object->note, '', 240, 'dolibarr_notes', '', true, false, $conf->global->FCKEDITOR_ENABLE_SOCIETE, ROWS_8, 90);
			$doleditor->Create();
			print '</td>';
			print "</tr>\n";
			print "</table>\n";

			print '<center><br><input class="button" value="' . $langs->trans("Save") . '" type="submit"></center>';

			print '</form>';

			print '</div>';
		}
	}
}

dol_fiche_end();

llxFooter();

$db->close();
?>
