<?php

/* Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
require_once(DOL_DOCUMENT_ROOT."/adherent/class/adherent.class.php");

$langs->load("members");
$langs->load("companies");

$object = new Adherent($db);

/*
 * View
 */

llxHeader('',$langs->trans("Member"),'EN:Module_Foundations|FR:Module_Adh&eacute;rents|ES:M&oacute;dulo_Miembros');

$titre=$langs->trans("MembersList");

print_fiche_titre($titre);
print '<div class="container">';
print '<div class="row">';

//print start_box($titre,"twelve","16-Users.png");

$i=0;
$obj=new stdClass();
print '<div class="datatable">';
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
$obj->aoColumns[$i]->fnRender= $object->datatablesFnRender("login", "url");
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
print $langs->trans('Group');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Tag";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('Person');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "morphy";
$obj->aoColumns[$i]->bVisible = false;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('EMail');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "email";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("email", "email");
$i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "dol_select center";
$obj->aoColumns[$i]->sWidth = "180px";
$obj->aoColumns[$i]->sDefaultContent = "0";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status", array("dateEnd"=>"last_subscription_date_end"));
$i++;
print'<th class="essential">';
print $langs->trans("EndSubscription");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "last_subscription_date_end";
$obj->aoColumns[$i]->sType="date";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sWidth = "200px";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("last_subscription_date_end", "date");
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->sWidth = "100px";
$obj->aoColumns[$i]->sDefaultContent = "";

$url = "adherent/fiche.php";
$obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"'. $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=edit&backtopage='. $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"'.$langs->trans("Edit").'\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"'. $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=resign&backtopage='. $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"'.$langs->trans("Resiliate").'\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/disable.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"'. $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=delete&backtopage='. $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"'.$langs->trans("Delete").'\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
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

$obj->bServerSide = true;
$object->datatablesCreate($obj,"member",true,true);

print '</div></div>'; // end row

llxFooter();
?>
 
