<?php

/* Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/useradmin/class/useradmin.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/images.lib.php");

$id = GETPOST('id');
$action = GETPOST("action");
$confirm = GETPOST("confirm");

// Define value to know what current user can do on users
$canadduser = ($user->admin || $user->rights->user->user->creer);
$canreaduser = ($user->admin || $user->rights->user->user->lire);
$canedituser = ($user->admin || $user->id == $id);
$candisableuser = ($user->admin || $user->rights->user->user->supprimer);
$canreadgroup = $canreaduser;
$caneditgroup = $canedituser;
// Define value to know what current user can do on properties of edited user
if ($id) {
    // $user est le user qui edite, $_GET["id"] est l'id de l'utilisateur edite
    $caneditfield = ((($user->id == $id) && $user->rights->user->self->creer)
            || (($user->id != $id) && $user->rights->user->user->creer));
    $caneditpassword = ((($user->id == $id) && $user->rights->user->self->password)
            || (($user->id != $id) && $user->rights->user->user->password));
}

// Security check
$socid = 0;
if ($user->societe_id > 0)
    $socid = $user->societe_id;
$feature2 = 'user';
if ($user->id == $id) {
    $feature2 = '';
    $canreaduser = 1;
} // A user can always read its own card

$result = restrictedArea($user, 'user', $id, '&user', $feature2);
if ($user->id <> $id && !$canreaduser)
    accessforbidden();

$langs->load("users");
$langs->load("companies");
$langs->load("ldap");

$form = new Form($db);
$edituser = new UserAdmin($db);

/**
 * Actions
 */
if ($_GET["subaction"] == 'addrights' && $canedituser) {
    $edituser->fetch($id);
    $edituser->addrights($_GET["rights"]);
}

if ($_GET["subaction"] == 'delrights' && $canedituser) {
    $edituser->fetch($id);
    $edituser->delrights($_GET["rights"]);
}

if ($action == 'confirm_disable' && $confirm == "yes" && $candisableuser) {
    if ($id <> $user->id) {
        $edituser->fetch($id);
        $edituser->setstatus(0);
        Header("Location: " . $_SERVER['PHP_SELF'] . '?id=' . $id);
        exit;
    }
}
if ($action == 'confirm_enable' && $confirm == "yes" && $candisableuser) {
    if ($id <> $user->id) {
        $message = '';

        $edituser->fetch($id);

        if (!empty($conf->file->main_limit_users)) {
            $nb = $edituser->getNbOfUsers("active", 1);
            if ($nb >= $conf->file->main_limit_users) {
                $message = '<div class="error">' . $langs->trans("YourQuotaOfUsersIsReached") . '</div>';
            }
        }

        if (!$message) {
            $edituser->setstatus(1);
            Header("Location: " . $_SERVER['PHP_SELF'] . '?id=' . $id);
            exit;
        }
    }
}

if ($action == 'confirm_delete' && $confirm == "yes" && $candisableuser) {
    if ($id <> $user->id) {
        $edituser->id = $id;
        $result = $edituser->delete();
        if ($result < 0) {
            $langs->load("errors");
            $message = '<div class="error">' . $langs->trans("ErrorUserCannotBeDelete") . '</div>';
        } else {
            Header("Location: index.php");
            exit;
        }
    }
}

// Action ajout user
if ((($action == 'add' && $canadduser) || ($action == 'update' && $canedituser)) && !$_POST["cancel"]) {
    $message = "";
    if (!$_POST["nom"]) {
        $message = '<div class="error">' . $langs->trans("NameNotDefined") . '</div>';
        $action = "create"; // Go back to create page
    }
    if (!$_POST["login"]) {
        $message = '<div class="error">' . $langs->trans("LoginNotDefined") . '</div>';
        $action = "create"; // Go back to create page
    }

    if (!empty($conf->file->main_limit_users) && $action == 'add') { // If option to limit users is set
        $nb = $edituser->getNbOfUsers("active", 1);
        if ($nb >= $conf->file->main_limit_users) {
            $message = '<div class="error">' . $langs->trans("YourQuotaOfUsersIsReached") . '</div>';
            $action = "create"; // Go back to create page
        }
    }

    if (!$message) {
        $edituser->Lastname = $_POST["nom"];
        $edituser->Firstname = $_POST["prenom"];
        $edituser->name = $_POST["login"];
        $edituser->pass = $_POST["password"];
        $edituser->entity = $_POST["default_entity"];
        $edituser->admin = (bool) $_POST["admin"];

        $id = $edituser->update($user, 0, $action);

        if ($id == $edituser->name) {
            Header("Location: " . $_SERVER['PHP_SELF'] . '?id=org.couchdb.user:' . $id);
            exit;
        } else {
            $langs->load("errors");
            if (is_array($edituser->errors) && count($edituser->errors))
                $message = '<div class="error">' . join('<br>', $langs->trans($edituser->errors)) . '</div>';
            else
                $message = '<div class="error">' . $langs->trans($edituser->error) . '</div>';
            print $edituser->error;
            if ($action == "add")
                $action = "create"; // Go back to create page
            if ($action == "update")
                $action = "edit"; // Go back to create page
        }
    }
}

// Change password with a new generated one
if ((($action == 'confirm_password' && $confirm == 'yes')
        || ($action == 'confirm_passwordsend' && $confirm == 'yes')) && $caneditpassword) {
    $edituser->fetch($id);

    $newpassword = $edituser->setPassword($user, '');
    if ($newpassword < 0) {
        // Echec
        $message = '<div class="error">' . $langs->trans("ErrorFailedToSetNewPassword") . '</div>';
    } else {
        // Succes
        if ($action == 'confirm_passwordsend' && $confirm == 'yes') {
            if ($edituser->send_password($user, $newpassword) > 0) {
                $message = '<div class="ok">' . $langs->trans("PasswordChangedAndSentTo", $edituser->email) . '</div>';
                //$message.=$newpassword;
            } else {
                $message = '<div class="ok">' . $langs->trans("PasswordChangedTo", $newpassword) . '</div>';
                $message.= '<div class="error">' . $edituser->error . '</div>';
            }
        } else {
            $message = '<div class="ok">' . $langs->trans("PasswordChangedTo", $newpassword) . '</div>';
        }
    }
}

/*
 * View
 */

llxHeader('', $langs->trans("UserCard"));

$form = new Form($db);

if (($action == 'create') || ($action == 'adduserldap')) {
    /*     * ************************************************************************* */
    /*                                                                            */
    /* Affichage fiche en mode creation                                           */
    /*                                                                            */
    /*     * ************************************************************************* */

    print_fiche_titre($langs->trans("NewUser"));
    print '<div class="with-padding">';

    print $langs->trans("CreateInternalUserDesc");
    print "<br>";
    print "<br>";

    dol_htmloutput_errors($message);

    print '<form action="' . $_SERVER["PHP_SELF"] . '" method="post" name="createuser">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<input type="hidden" name="action" value="add">';
    if ($ldap_sid)
        print '<input type="hidden" name="ldap_sid" value="' . $ldap_sid . '">';
    print '<input type="hidden" name="entity" value="' . $conf->entity . '">';

    print '<table class="border" width="100%">';

    print '<tr>';

    // Login
    print '<tr><td valign="top"><span class="fieldrequired">' . $langs->trans("EMail") . '</span></td>';
    print '<td>';
    print '<input size="20" maxsize="24" type="text" name="login" value="' . $_POST["login"] . '">';
    print '</td></tr>';

    // Nom
    print '<td valign="top" width="160"><span class="fieldrequired">' . $langs->trans("Lastname") . '</span></td>';
    print '<td>';
    if ($ldap_nom) {
        print '<input type="hidden" name="nom" value="' . $ldap_nom . '">';
        print $ldap_nom;
    } else {
        print '<input size="30" type="text" name="nom" value="' . $_POST["nom"] . '">';
    }
    print '</td></tr>';

    // Prenom
    print '<tr><td valign="top">' . $langs->trans("Firstname") . '</td>';
    print '<td>';
    if ($ldap_prenom) {
        print '<input type="hidden" name="prenom" value="' . $ldap_prenom . '">';
        print $ldap_prenom;
    } else {
        print '<input size="30" type="text" name="prenom" value="' . $_POST["prenom"] . '">';
    }
    print '</td></tr>';

    $generated_password = '';
    if (!$ldap_sid) { // ldap_sid is for activedirectory
        require_once(DOL_DOCUMENT_ROOT . "/core/lib/security2.lib.php");
        $generated_password = getRandomPassword('');
    }
    $password = $generated_password;

    // Mot de passe
    print '<tr><td valign="top">' . $langs->trans("Password") . '</td>';
    print '<td>';
    if ($ldap_sid) {
        print 'Mot de passe du domaine';
    } else {
        if ($ldap_pass) {
            print '<input type="hidden" name="password" value="' . $ldap_pass . '">';
            print preg_replace('/./i', '*', $ldap_pass);
        } else {
            // We do not use a field password but a field text to show new password to use.
            print '<input size="30" maxsize="32" type="text" name="password" value="' . $password . '">';
        }
    }
    print '</td></tr>';

    // Administrateur
    print '<tr><td valign="top">' . $langs->trans("SuperAdministrator") . '</td>';
    print '<td>';
    print $form->selectyesno('admin', $_POST["admin"], 1);
    print "</td></tr>\n";

    // Type
    print '<tr><td valign="top">' . $langs->trans("Type") . '</td>';
    print '<td>';
    print $form->textwithpicto($langs->trans("Internal"), $langs->trans("InternalExternalDesc"));
    print '</td></tr>';

    print "</table>\n";

    print '<center><br><input class="button" value="' . $langs->trans("CreateUser") . '" name="create" type="submit"></center>';

    print "</form>";
    print "</div>";
} else {
    /**
     *
     * Visu et edition
     *
     * */
    if ($id) {
        $fuser = new UserAdmin($db);
        $fuser->fetch($id);

        $title = $langs->trans("User");

        print_fiche_titre($title);
        print '<div class="with-padding">';
        print '<div class="columns">';
        print start_box($title, "twelve", "16-User.png", false);

        dol_fiche_head($head, 'user', $title, 0, 'user');

        /*
         * Confirmation reinitialisation mot de passe
         */
        if ($action == 'password') {
            $ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=$fuser->id", $langs->trans("ReinitPassword"), $langs->trans("ConfirmReinitPassword", $fuser->login), "confirm_password", '', 0, 1);
            if ($ret == 'html')
                print '<br>';
        }

        /*
         * Confirmation envoi mot de passe
         */
        if ($action == 'passwordsend') {
            $ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=$fuser->id", $langs->trans("SendNewPassword"), $langs->trans("ConfirmSendNewPassword", $fuser->login), "confirm_passwordsend", '', 0, 1);
            if ($ret == 'html')
                print '<br>';
        }

        /*
         * Confirmation desactivation
         */
        if ($action == 'disable') {
            $ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=$fuser->id", $langs->trans("DisableAUser"), $langs->trans("ConfirmDisableUser", $fuser->login), "confirm_disable", '', 0, 1);
            if ($ret == 'html')
                print '<br>';
        }

        /*
         * Confirmation activation
         */
        if ($action == 'enable') {
            $ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=$fuser->id", $langs->trans("EnableAUser"), $langs->trans("ConfirmEnableUser", $fuser->login), "confirm_enable", '', 0, 1);
            if ($ret == 'html')
                print '<br>';
        }

        /*
         * Confirmation suppression
         */
        if ($action == 'delete') {
            $ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=$fuser->id", $langs->trans("DeleteAUser"), $langs->trans("ConfirmDeleteUser", $fuser->login), "confirm_delete", '', 0, 1);
            if ($ret == 'html')
                print '<br>';
        }

        dol_htmloutput_mesg($message);

        /*
         * Fiche en mode visu
         */
        if ($action != 'edit') {

            print '<table class="border" width="100%">';

            // Ref
            print '<tr><td width="25%" valign="top">' . $langs->trans("Ref") . '</td>';
            print '<td colspan="2">';
            print $form->showrefnav($fuser, 'id', '', $user->rights->user->user->lire || $user->admin);
            print '</td>';
            print '</tr>' . "\n";

            $rowspan = 14;

            // EMail
            print '<tr><td valign="top">' . $langs->trans("EMail") . '</td>';
            print '<td>' . dol_print_email($fuser->name, 0, 0, 1) . '</td>';
            print "</tr>\n";

            // Lastname
            print '<tr><td valign="top">' . $langs->trans("Lastname") . '</td>';
            print '<td>' . $fuser->Lastname . '</td>';

            print '</tr>' . "\n";

            // Firstname
            print '<tr><td valign="top">' . $langs->trans("Firstname") . '</td>';
            print '<td>' . $fuser->Firstname . '</td>';
            print '</tr>' . "\n";

            // Password
            print '<tr><td valign="top">' . $langs->trans("Password") . '</td>';
            if ($fuser->ldap_sid) {
                if ($passDoNotExpire) {
                    print '<td>' . $langs->trans("LdapUacf_" . $statutUACF) . '</td>';
                } else if ($userChangePassNextLogon) {
                    print '<td class="warning">' . $langs->trans("UserMustChangePassNextLogon", $ldap->domainFQDN) . '</td>';
                } else if ($userDisabled) {
                    print '<td class="warning">' . $langs->trans("LdapUacf_" . $statutUACF, $ldap->domainFQDN) . '</td>';
                } else {
                    print '<td>' . $langs->trans("DomainPassword") . '</td>';
                }
            } else {
                print '<td>';
                if ($user->admin)
                    print $langs->trans("Crypted") . ': ' . $fuser->pass_indatabase_crypted;
                else
                    print $langs->trans("Hidden");
                print "</td>";
            }
            print '</tr>' . "\n";

            // Administrator
            print '<tr><td valign="top">' . $langs->trans("Administrator") . '</td><td>';
            print yn($fuser->admin);
            print '</td></tr>' . "\n";

            // Default entity
            print '<tr><td valign="top">' . $langs->trans("Entity") . '</td><td>';
            print $fuser->entity;
            print '</td></tr>' . "\n";

            // Statut
            print '<tr><td valign="top">' . $langs->trans("Status") . '</td>';
            print '<td>';
            print $fuser->getLibStatus();
            print '</td>';
            print '</tr>' . "\n";

            /* print '<tr><td valign="top">' . $langs->trans("LastConnexion") . '</td>';
              print '<td>' . dol_print_date($fuser->datelastlogin, "dayhour") . '</td>';
              print "</tr>\n";

              print '<tr><td valign="top">' . $langs->trans("PreviousConnexion") . '</td>';
              print '<td>' . dol_print_date($fuser->datepreviouslogin, "dayhour") . '</td>';
              print "</tr>\n"; */

            print "</table>\n";

            /*
             * Buttons actions
             */

            print '<div class="tabsAction">';
            print '<span class="button-group">';
            if ($caneditfield) {
                if (!empty($conf->global->MAIN_ONLY_LOGIN_ALLOWED)) {
                    print '<a class="button" href="#" title="' . dol_escape_htmltag($langs->trans("DisabledInMonoUserMode")) . '">' . $langs->trans("Modify") . '</a>';
                } else {
                    print '<a class="button icon-pencil" href="' . $_SERVER["PHP_SELF"] . '?id=' . $fuser->id . '&amp;action=edit">' . $langs->trans("Modify") . '</a>';
                }
            } elseif ($caneditpassword && !$fuser->ldap_sid) {
                print '<a class="button" href="' . $_SERVER["PHP_SELF"] . '?id=' . $fuser->id . '&amp;action=edit">' . $langs->trans("EditPassword") . '</a>';
            }

            // Si on a un gestionnaire de generation de mot de passe actif
            if ($conf->global->USER_PASSWORD_GENERATED != 'none') {
                if ($fuser->Status == "DISABLE") {
                    print '<a class="button disabled" href="#" title="' . dol_escape_htmltag($langs->trans("UserDisabled")) . '">' . $langs->trans("ReinitPassword") . '</a>';
                } elseif (($user->id != $id && $caneditpassword) && $fuser->login && !$fuser->ldap_sid) {
                    print '<a class="button" href="' . $_SERVER["PHP_SELF"] . '?id=' . $fuser->id . '&amp;action=password">' . $langs->trans("ReinitPassword") . '</a>';
                }

                if ($fuser->Status == "DISABLE") {
                    print '<a class="button disabled" href="#" title="' . dol_escape_htmltag($langs->trans("UserDisabled")) . '">' . $langs->trans("SendNewPassword") . '</a>';
                } else if (($user->id != $id && $caneditpassword) && $fuser->login && !$fuser->ldap_sid) {
                    if ($fuser->email)
                        print '<a class="button " href="' . $_SERVER["PHP_SELF"] . '?id=' . $fuser->id . '&amp;action=passwordsend">' . $langs->trans("SendNewPassword") . '</a>';
                    else
                        print '<a class="button disabled" href="#" title="' . dol_escape_htmltag($langs->trans("NoEMail")) . '">' . $langs->trans("SendNewPassword") . '</a>';
                }
            }

            // Activer
            if ($user->id <> $id && $candisableuser && $fuser->Status != "ENABLE") {
                print '<a class="button icon-unlock" href="' . $_SERVER["PHP_SELF"] . '?id=' . $fuser->id . '&amp;action=enable">' . $langs->trans("Reactivate") . '</a>';
            }
            // Desactiver
            if ($user->id <> $id && $candisableuser && $fuser->Status == "ENABLE") {
                print '<a class="button icon-lock" href="' . $_SERVER["PHP_SELF"] . '?action=disable&amp;id=' . $fuser->id . '">' . $langs->trans("DisableUser") . '</a>';
            }
            // Delete
            if ($user->id <> $id && $candisableuser) {
                print '<a class="button red-gradient icon-trash" href="' . $_SERVER["PHP_SELF"] . '?action=delete&amp;id=' . $fuser->id . '">' . $langs->trans("DeleteUser") . '</a>';
            }

            print "</span></div>";

            print '</div>';

            print end_box();
        }


        /*
         * Fiche en mode edition
         */

        if ($action == 'edit' && ($canedituser || ($user->id == $fuser->id))) {

            print '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $fuser->id . '" method="POST" name="updateuser" enctype="multipart/form-data">';
            print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
            print '<input type="hidden" name="action" value="update">';
            print '<table width="100%" class="border">';

            $rowspan = 12;

            print '<tr><td width="25%" valign="top">' . $langs->trans("Ref") . '</td>';
            print '<td colspan="2">';
            print $fuser->id;
            print '</td>';
            print '</tr>';

            // Login
            print "<tr>" . '<td valign="top"><span class="fieldrequired">' . $langs->trans("EMail") . '</span></td>';
            print '<td>';
            print '<input type="hidden" name="login" value="' . $fuser->name . '">';
            print $fuser->name;
            print '</td>';
            print '</tr>';

            // Lastname
            print "<tr>";
            print '<td valign="top" class="fieldrequired">' . $langs->trans("Lastname") . '</td>';
            print '<td>';
            print '<input size="30" type="text" class="flat" name="nom" value="' . $fuser->Lastname . '">';
            print '</td>';

            // Firstname
            print "<tr>" . '<td valign="top">' . $langs->trans("Firstname") . '</td>';
            print '<td>';
            print '<input size="30" type="text" class="flat" name="prenom" value="' . $fuser->Firstname . '">';
            print '</td></tr>';

            // Pass
            print '<tr><td valign="top">' . $langs->trans("Password") . '</td>';
            print '<td>';
            if ($caneditpassword) {
                $text = '<input size="12" maxlength="32" type="password" class="flat" name="password" value="' . $fuser->pass . '">';
                if ($dolibarr_main_authentication && $dolibarr_main_authentication == 'http') {
                    $text = $form->textwithpicto($text, $langs->trans("SpeedealingInHttpAuthenticationSoPasswordUseless", $dolibarr_main_authentication), 1, 'warning');
                }
            } else {
                $text = preg_replace('/./i', '*', $fuser->pass);
            }
            print $text;
            print "</td></tr>\n";

            // Administrator
            print '<tr><td valign="top">' . $langs->trans("SuperAdministrator") . '</td>';
            print yn($fuser->admin);
            print '</td></tr>';

            // Entity by default
            print '<tr><td width="25%" valign="top">' . $langs->trans("Entity") . '</td>';
            print '<td>';
            print $fuser->entity;
            print '<input type="hidden" name="default_entity" value="' . $conf->Couchdb->name . '">';
            print '</td></tr>';

            // Statut
            print '<tr><td valign="top">' . $langs->trans("Status") . '</td>';
            print '<td>';
            print $fuser->getLibStatus();
            print '</td></tr>';

            print '</table>';

            print '<br><center>';
            print '<input value="' . $langs->trans("Save") . '" class="button" type="submit" name="save">';
            print ' &nbsp; ';
            print '<input value="' . $langs->trans("Cancel") . '" class="button" type="submit" name="cancel">';
            print '</center>';

            print '</form>';

            print '</div>';
        }

        $ldap->close;
    }
}

print end_box();
print '</div>';

dol_fiche_end();

llxFooter();
?>