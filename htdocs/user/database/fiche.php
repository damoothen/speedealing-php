<?php

/* Copyright (C) 2012      Herve Prot           <herve.prot@symeos.com>
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
require_once(DOL_DOCUMENT_ROOT . "/user/class/userdatabase.class.php");
require_once(DOL_DOCUMENT_ROOT . "/user/class/usergroup.class.php");

// Defini si peux lire/modifier utilisateurs et permisssions
$canreadperms = ($user->admin );
$caneditperms = ($user->admin );
$candisableperms = ($user->admin );

$langs->load("users");
$langs->load("other");

$id = GETPOST('id', 'alpha');
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$userid = GETPOST('user', 'alpha');

// Security check
$result = restrictedArea($user, 'user', $id, 'usergroup&usergroup', 'user');

$object = new UserDatabase($db);

/**
 *  Action add database
 */
if ($action == 'add') {
    if ($caneditperms) {
        $message = "";
        if (!$_POST["nom"]) {
            $message = '<div class="error">' . $langs->trans("NameNotDefined") . '</div>';
            $action = "create"; // Go back to create page
        }

        if (!$message) {
            try {
                $object->id = trim($_POST["nom"]);
                $object->create();
            } catch (Exception $e) {
                $langs->load("errors");
                $message = '<div class="error">' . $langs->trans("ErrorDatabaseAlreadyExists", $object->id) . '</div>';
                $action = "create"; // Go back to create page
            }
            Header("Location: fiche.php?id=" . $object->id);
            exit;
        }
    } else {
        $langs->load("errors");
        $message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
    }
}

// Add/Remove user into database
if ($action == 'adduser' || $action == 'removeuser') {
    if ($caneditperms) {
        if ($userid) {
            $object->fetch($id);

            if ($action == 'adduser') {
                if ($_POST['admin'] == true)
                    $object->couchAdmin->addDatabaseAdminUser($userid);
                else
                    $object->couchAdmin->addDatabaseReaderUser($userid);
            }
            if ($action == 'removeuser') {
                $object->couchAdmin->removeDatabaseAdminUser($userid);
                $object->couchAdmin->removeDatabaseReaderUser($userid);
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

/*
 * View
 */

llxHeader('', $langs->trans("DatabaseCard"));

$form = new Form($db);
$fuserstatic = new User($db);

if ($action == 'create') {
    print_fiche_titre($langs->trans("NewDatabase"));

    if ($message) {
        print $message . "<br>";
    }

    print '<form action="' . $_SERVER["PHP_SELF"] . '" method="post">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<input type="hidden" name="action" value="add">';

    print '<table class="border" width="100%">';

    print "<tr>" . '<td valign="top" class="fieldrequired">' . $langs->trans("Name") . '</td>';
    print '<td class="valeur"><input size="30" type="text" name="nom" value=""></td></tr>';

    print "</table>\n";

    print '<center><br><input class="button" value="' . $langs->trans("CreateDatabase") . '" type="submit"></center>';

    print "</form>";

    /**
     *
     * Visu et edition
     *
     */
} else {
    if ($id) {
        try {
            $object->fetch($id);
        } catch (Exception $e) {
            $action = 'edit';
            $object->values->name = $id;
        }

        /*
         * Affichage onglets
         */
        $title = $langs->trans("Database") . " : " . $object->values->db_name;

        print_fiche_titre($title);
        print '<div class="with-padding">';
        print '<div class="columns">';
        /*
         * Fiche en mode visu
         */

        if ($action != 'edit') {

            dol_htmloutput_mesg($message);

            /*
             * Liste des utilisateurs dans le database
             */

            print start_box($langs->trans("ListOfUsersInDatabase"), "twelve", "16-User-2.png", false);

// On selectionne les users qui ne sont pas deja dans le groupe
            $exclude = array();

            if (!empty($object->members)) {
                foreach ($object->members as $useringroup) {
                    $exclude[] = $useringroup->_id;
                }
            }

            if ($caneditperms) {
                print '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '" method="POST">' . "\n";
                print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
                print '<input type="hidden" name="action" value="adduser">';
                print '<table class="noborder" width="100%">' . "\n";
                print '<tr class="liste_titre"><td class="liste_titre" width="25%">' . $langs->trans("NonAffectedUsersDatabase") . '</td>' . "\n";
                print '<td>';
                print $object->select_fk_extrafields('user', 'user');
                print '</td>';
                print '<td valign="top">' . $langs->trans("Administrator") . '</td>';
                print "<td>" . $form->selectyesno('admin', 0, 1);
                print "</td>\n";
                print '<td><input type="submit" class="tiny nice button" value="' . $langs->trans("Add") . '">';
                print '</td></tr>' . "\n";
                print '</table></form>' . "\n";
                print '<br>';
            }

            /*
             * Users members
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
            if (!empty($object->members)) {

                foreach ($object->members as $aRow) {

                    $useringroup = new User($db);
                    $useringroup->values = $aRow;
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

            print end_box();
        }

        /*
         * Fiche en mode edition
         */
        if ($action == 'edit' && $caneditperms) {
            print start_box($title, "twelve", "16-Cloud.png", false);

            print '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '" method="post" name="updategroup" enctype="multipart/form-data">';
            print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
            print '<input type="hidden" name="action" value="update">';

            print '<table class="border" width="100%">';
            print '<tr><td width="25%" valign="top" class="fieldrequired">' . $langs->trans("Name") . '</td>';
            print '<td width="75%" class="valeur"><input size="15" type="text" name="group" value="' . $object->values->name . '">';
            print "</td></tr>\n";

            print "</table>\n";

            print '<center><br><input class="button" value="' . $langs->trans("Save") . '" type="submit"></center>';

            print '</form>';

            print '</div>';
        }
    }
}

print '</div></div>';

llxFooter();
?>
