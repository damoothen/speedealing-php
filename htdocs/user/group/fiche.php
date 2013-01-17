<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
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

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/usergroup.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/usergroups.lib.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/userdatabase.class.php';

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
            $object->name = strtolower(trim($_POST["nom"]));
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

if ($action == 'add_right' && $caneditperms) {
    $editgroup = new Usergroup($db);
    try {
        $editgroup->load($id);

        // For avoid error in strict mode
        if (! is_object($editgroup->rights))
        	$editgroup->rights = new stdClass();

        $editgroup->rights->$_GET['pid'] = true;
        $editgroup->record();
    } catch (Exception $e) {
        $mesg = $e->getMessage();
    }
    Header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id . "&mesg=" . urlencode($mesg));
    exit;
}

if ($action == 'remove_right' && $caneditperms) {
    $editgroup = new Usergroup($db);
    try {
        $editgroup->load($id);
        unset($editgroup->rights->$_GET['pid']);

        $editgroup->record();
    } catch (Exception $e) {
        $mesg = $e->getMessage();
    }
    Header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id . "&mesg=" . urlencode($mesg));
    exit;
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
    if (! class_exists('DolEditor'))
    	require DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
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
        $title = $langs->trans("GroupCard") . " : " . $object->name;

        print_fiche_titre($title);
        print '<div class="with-padding">';
        print '<div class="columns">';

        /*
         * Fiche en mode visu
         */

        if ($action != 'edit') {


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
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "";
            $i++;
            print '<th>' . $langs->trans("Lastname") . '</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "";
            $i++;
            print '<th>' . $langs->trans("Firstname") . '</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "";
            $i++;
            print '<th>' . $langs->trans("Status") . '</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "";
            $obj->aoColumns[$i]->sClass = "center";
            $i++;
            print '<th>' . $langs->trans('Action') . '</th>';
            $obj->aoColumns[$i] = new stdClass();
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
                    $useringroup->id = $useringroup->values->_id;
                    $useringroup->email = $useringroup->values->email;

                    print "<tr $bc[$var]>";
                    print '<td>';
                    print '<a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $useringroup->id . '">' . img_object($langs->trans("ShowUser"), "user") . ' ' . $useringroup->values->name . '</a>';
                    if ($useringroup->admin)
                        print img_picto($langs->trans("Administrator"), 'star');
                    print '</td>';
                    print '<td>' . $useringroup->values->Lastname . '</td>';
                    print '<td>' . $useringroup->values->Firstname . '</td>';
                    print '<td>' . $useringroup->LibStatus($useringroup->Status) . '</td>';
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

            print start_box($title, "twelve", "16-Users-2.png", false);

            /*
             * Ecran ajout/suppression permission
             */

            $i = 0;
            $obj = new stdClass();

            if ($user->admin)
                print info_admin($langs->trans("WarningOnlyPermissionOfActivatedModules"));

            print '<table class="display dt_act" id="perms_rights">';

            print'<thead>';
            print'<tr>';

            print'<th>';
            print'</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "id";
            $obj->aoColumns[$i]->sDefaultContent = "";
            $obj->aoColumns[$i]->bVisible = false;
            $i++;

            print'<th class="essential">';
            print $langs->trans("Module");
            print'</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "name";
            $obj->aoColumns[$i]->sDefaultContent = "";
            $obj->aoColumns[$i]->sWidth = "18em";
            $i++;

            print'<th>';
            print $langs->trans("Permission");
            print'</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "desc";
            $obj->aoColumns[$i]->sDefaultContent = "";
            $obj->aoColumns[$i]->bVisible = true;
            $i++;

            print'<th class="essential">';
            print $langs->trans("Enabled");
            print'</th>';
            $obj->aoColumns[$i] = new stdClass();
            $obj->aoColumns[$i]->mDataProp = "Status";
            $obj->aoColumns[$i]->sDefaultContent = "false";
            $obj->aoColumns[$i]->sClass = "center";

            print'</tr>';
            print'</thead>';
            $obj->fnDrawCallback = "function(oSettings){
                if ( oSettings.aiDisplay.length == 0 )
                {
                    return;
                }
                var nTrs = jQuery('#perms_rights tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = '';
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                    var iDisplayIndex = oSettings._iDisplayStart + i;
                     var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData['name'];
                         if (sGroup!=null && sGroup!='' && sGroup != sLastGroup)
                            {
                                var nGroup = document.createElement('tr');
                                var nCell = document.createElement('td');
                                nCell.colSpan = iColspan;
                                nCell.className = 'group';
                                nCell.innerHTML = sGroup;
                                nGroup.appendChild( nCell );
                                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                                sLastGroup = sGroup;
                            }


                }
	}";

            $i = 0;
            print'<tfoot>';
            print'</tfoot>';
            print'<tbody>';

            $objectM = new DolibarrModules($db);

            try {
                $result = $objectM->getView("default_right");
            } catch (Exception $exc) {
                print $exc->getMessage();
            }

            if (count($result->rows)) {

                foreach ($result->rows as $aRow) {
                    print'<tr>';

                    $objectM->name = $aRow->value->name;
                    $objectM->numero = $aRow->value->numero;
                    $objectM->rights_class = $aRow->value->rights_class;
                    $objectM->id = $aRow->value->id;
                    $objectM->perm = $aRow->value->perm;
                    $objectM->desc = $aRow->value->desc;
                    $objectM->Status = ($aRow->value->Status == true ? "true" : "false");

                    print '<td>' . $aRow->value->id . '</td>';
                    print '<td>' . img_object('', $aRow->value->picto) . " " . $objectM->getName() . '</td>';
                    print '<td>' . $objectM->getPermDesc() . '<a name="' . $aRow->value->id . '">&nbsp;</a></td>';
                    print '<td>';

                    $perm = $aRow->value->id;

                    if ($caneditperms) {
                        if ($aRow->value->Status)
                            print $objectM->getLibStatus(); // Enable by default
                        elseif ($object->rights->$perm)
                            print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&pid=' . $aRow->value->id . '&amp;action=remove_right#' . $aRow->value->id . '">' . img_edit_remove() . '</a>';
                        else
                            print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $object->id . '&pid=' . $aRow->value->id . '&amp;action=add_right#' . $aRow->value->id . '">' . img_edit_add() . '</a>';
                    }
                    else {
                        print $objectM->getLibStatus();
                    }
                    print '</td>';

                    print'</tr>';
                }
            }
            print'</tbody>';
            print'</table>';

            $obj->aaSorting = array(array(1, 'asc'));
            $obj->sDom = 'l<fr>t<\"clear\"rtip>';
            $obj->iDisplayLength = 50;

            print $objectM->datatablesCreate($obj, "perms_rights");


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
            if (! class_exists('DolEditor'))
            	require DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
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
