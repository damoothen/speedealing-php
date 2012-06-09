<?php

/* Copyright (C) 2002-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot			<herve.prot@symeos.com>
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

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/usergroups.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

$langs->load("users");
$langs->load("admin");

$id = GETPOST('id', 'alpha');
$action = GETPOST('action', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$module = GETPOST('module');

if (!isset($id) || empty($id))
	accessforbidden();

// Defini si peux lire les permissions
$canreaduser = ($user->admin || $user->rights->user->user->lire);
// Defini si peux modifier les autres utilisateurs et leurs permisssions
$caneditperms = ($user->admin || $user->rights->user->user->creer);
// Advanced permissions
$canreaduser = ($user->admin || ($user->rights->user->user->lire && $user->rights->user->user_advance->readperms));
$caneditselfperms = ($user->id == $id && $user->rights->user->self_advance->writeperms);
$caneditperms = (($caneditperms || $caneditselfperms) ? 1 : 0);


// Security check
$socid = 0;
if ($user->societe_id > 0)
	$socid = $user->societe_id;
$feature2 = (($socid && $user->rights->user->self->creer) ? '' : 'user');
if ($user->id == $id && (empty($conf->global->MAIN_USE_ADVANCED_PERMS) || $user->rights->user->self_advance->readperms)) { // A user can always read its own card if not advanced perms enabled, or if he has advanced perms
	$feature2 = '';
	$canreaduser = 1;
}
$result = restrictedArea($user, 'user', $id, '&user', $feature2);
if ($user->id <> $id && !$canreaduser)
	accessforbidden();

$fuser = new User($db);

/**
 * Actions
 */
if ($action == 'add' && $caneditperms) {
	try {
		$fuser->load($id);

		$fuser->values->rights->$_GET['pid'] = true;
		$fuser->record();
	} catch (Exception $e) {
		$mesg = $e->getMessage();
	}
	Header("Location: " . $_SERVER['PHP_SELF'] . "?id=".$id."&mesg=" . urlencode($mesg));
	exit;
}

if ($action == 'remove' && $caneditperms) {
	try {
		$fuser->load($id);
		unset($fuser->values->rights->$_GET['pid'] );

		$fuser->record();
	} catch (Exception $e) {
		$mesg = $e->getMessage();
	}
	Header("Location: " . $_SERVER['PHP_SELF'] . "?id=".$id."&mesg=" . urlencode($mesg));
	exit;
}



/* * ************************************************************************* */
/*                                                                            */
/* Visu et edition                                                            */
/*                                                                            */
/* * ************************************************************************* */

llxHeader('', $langs->trans("Permissions"));

$form = new Form($db);

$fuser->fetch($id);

$object = new DolibarrModules($db);

/*
 * Affichage onglets
 */
$head = user_prepare_head($fuser);

$title = $langs->trans("User");

print '<div class="row">';
print start_box($title, "twelve", "16-User-2.png", false);
dol_fiche_head($head, 'rights', $title, 0, 'user');
// Search all modules with permission and reload permissions def.

/*
 * Ecran ajout/suppression permission
 */

if ($user->admin)
	print info_admin($langs->trans("WarningOnlyPermissionOfActivatedModules"));

$i = 0;
$obj = new stdClass();

print '<table class="display dt_act" id="rights">';

print'<thead>';
print'<tr>';

print'<th>';
print'</th>';
$obj->aoColumns[$i]->mDataProp = "id";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bVisible = false;
$i++;

print'<th class="essential">';
print $langs->trans("Module");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sWidth = "18em";
$i++;

print'<th>';
print $langs->trans("Permission");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "desc";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bVisible = true;
$i++;

print'<th class="essential">';
print $langs->trans("Enabled");
print'</th>';
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
                var nTrs = jQuery('#rights tbody tr');
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

try {
	$result = $object->getView("default_right");
} catch (Exception $exc) {
	print $exc->getMessage();
}

if (count($result->rows)) {

	foreach ($result->rows as $aRow) {
		print'<tr>';

		$object->values->name = $aRow->value->name;
		$object->values->numero = $aRow->value->numero;
		$object->values->rights_class = $aRow->value->rights_class;
		$object->values->id = $aRow->value->id;
		$object->values->perm = $aRow->value->perm;
		$object->values->desc = $aRow->value->desc;
		$object->values->Status = ($aRow->value->Status == true ? "true" : "false");

		print '<td>' . $aRow->value->id . '</td>';
		print '<td>' . img_object('', $aRow->value->picto) . " " . $object->getName() . '</td>';
		print '<td>' . $object->getPermDesc() . '<a name="' . $aRow->value->id . '">&nbsp;</a></td>';
		print '<td>';
		
		$perm = $aRow->value->id;
		

		if ($caneditperms) {
			if ($aRow->value->Status)
				print $object->getLibStatus(); // Enable by default
			elseif ($fuser->values->rights->$perm)
				print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $fuser->id . '&pid=' . $aRow->value->id . '&amp;action=remove#' . $aRow->value->id . '">' . img_edit_remove() . '</a>';
			else
				print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $fuser->id . '&pid=' . $aRow->value->id . '&amp;action=add#' . $aRow->value->id . '">' . img_edit_add() . '</a>';
		}
		else {
				print $object->getLibStatus();
		}
		print '</td>';

		print'</tr>';
	}
}
print'</tbody>';
print'</table>';

$obj->aaSorting = array(array(1, 'asc'));
$obj->sDom = 'l<fr>t<\"clear\"rtip>';
$obj->iDisplayLength = -1;

print $object->datatablesCreate($obj, "rights");


print end_box();
print '</div>';

llxFooter();
?>
