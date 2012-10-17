<?php

/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
 * 	    \file       htdocs/contact/list.php
 *      \ingroup    societe
 * 		\brief      Page to list all contacts
 */
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/contact/class/contact.class.php';

$langs->load("companies");
$langs->load("suppliers");

// Security check
$contactid = GETPOST('id', 'alpha');
if ($user->societe_id)
    $socid = $user->societe_id;
$result = restrictedArea($user, 'contact', $contactid, '');

$type = GETPOST("type");
$view = GETPOST("view");

$sall = GETPOST("contactname");
$userid = GETPOST('userid', 'int');

$langs->load("companies");
$titre = (!empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT) ? $langs->trans("ListOfContacts") : $langs->trans("ListOfContactsAddresses"));
if ($type == "c") {
    $titre.='  (' . $langs->trans("ThirdPartyCustomers") . ')';
    $urlfiche = "fiche.php";
} else if ($type == "p") {
    $titre.='  (' . $langs->trans("ThirdPartyProspects") . ')';
    $urlfiche = "prospect/fiche.php";
} else if ($type == "f") {
    $titre.=' (' . $langs->trans("ThirdPartySuppliers") . ')';
    $urlfiche = "fiche.php";
} else if ($type == "o") {
    $titre.=' (' . $langs->trans("OthersNotLinkedToThirdParty") . ')';
    $urlfiche = "";
}



/*
 * View
 */

$title = (!empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT) ? $langs->trans("Contacts") : $langs->trans("ContactsAddresses"));
llxHeader('', $title, 'EN:Module_Third_Parties|FR:Module_Tiers|ES:M&oacute;dulo_Empresas');

$object = new Contact($db);

print_fiche_titre($title);
print '<div class="with-padding">';

$i = 0;
$obj = new stdClass();
print '<div class="datatable">';
print '<table class="display dt_act" id="list_contacts" >';
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
print $langs->trans("Lastname");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "lastname";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("lastname", "url");
$i++;
print'<th class="essential">';
print $langs->trans("Firstname");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "firstname";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("PostOrFunction");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "PostOrFunction";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('Company');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "societe.name";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("societe.name", "url");
$i++;
print'<th class="essential">';
print $langs->trans('Phone');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "phone";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('Mobile');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "mobile";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('EMail');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "email";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('DateModificationShort');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "datef";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("datef", "datetime");
//$obj->aoColumns[$i]->sClass = "edit";
$i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "dol_select center";
$obj->aoColumns[$i]->sDefaultContent = "0";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->sWidth = "100px";
$obj->aoColumns[$i]->bSortable = false;
$obj->aoColumns[$i]->sDefaultContent = "";

$url = "contact/fiche.php";
$obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"' . $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=edit&backtopage=' . $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"' . $langs->trans("Edit") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"' . $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=delete&backtopage=' . $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"' . $langs->trans("Delete") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
	var str = ar.join("");
	return str;
}';
print'</tr>';
print'</thead>';
print'<tfoot>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print "</table>";
print "</div>";

//$obj->bServerSide = true;
//$obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listDatatables.php?json=listTasks&class=" . get_class($object);
$object->datatablesCreate($obj, "list_contacts", true, true);

print '</div>'; // end

llxFooter();
$db->close();
?>
