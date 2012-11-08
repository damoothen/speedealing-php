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
 *  Action add group
 */
if ($action == 'add') {
    if ($caneditperms) {
        $message = "";
        if (!$_POST["nom"]) {
            $message = '<div class="error">' . $langs->trans("NameNotDefined") . '</div>';
            $action = "create"; // Go back to create page
        }

        if (!$message) {
            $object->name = trim($_POST["nom"]);
            $object->note = trim($_POST["note"]);
            $object->_id = "group:" . $object->name;

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

            $edituser = new User($db);
            $edituser->fetch($userid);

            if ($action == 'adduser') {
                $edituser->group[] = $object->name;
            }
            if ($action == 'removeuser') {
                unset($edituser->group[array_search($object->name, $edituser->group)]);
                $edituser->group = array_merge($edituser->group);
            }
            $edituser->record(true);

            header("Location: fiche.php?id=" . $object->id);
            exit;
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


    /**
     *
     * Visu et edition
     *
     */
} else {
    if ($id) {
        $object->load($id);

        /*
         * Affichage onglets
         */
        $head = group_prepare_head($object);
        $title = $langs->trans("GroupCard") . " : " . $object->name;

        print_fiche_titre($title);
        print '<div class="with-padding">';
        print '<div class="columns">';
        print start_box($title, "twelve", "16-Users-2.png", false);

        dol_fiche_head($head, 'group', $title, 0, 'group');


        /*
         * Fiche en mode visu
         */

        if ($action != 'edit') {

            print "</div>\n";

            print end_box();


            dol_htmloutput_mesg($message);

            /*
             * Liste des utilisateurs dans le groupe
             */

            print start_box($langs->trans("ListOfUsersInGroup"), "twelve", "16-User-2.png", false);

            // On selectionne les users qui ne sont pas deja dans le groupe
            $exclude = array();

            $userstatic = new User($db);
            $result = $userstatic->getView("group", array('key' => $object->name));

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
                print $object->select_fk_extrafields('user', 'user');
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
            print '<th>' . $langs->trans('Action') . '</th>';
            $obj->aoColumns[$i]->mDataProp = "";
            $obj->aoColumns[$i]->sClass = "center content_actions";
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
                    print '<td>' . $useringroup->LibStatus($useringroup->values->Status) . '</td>';
                    print '<td>';
                    if ($user->admin) {
                        print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&amp;action=removeuser&amp;user=' . $useringroup->values->_id . '">';
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

            print end_box();
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
            print '<td width="75%" class="valeur"><input size="15" type="text" name="group" value="' . $object->name . '">';
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
