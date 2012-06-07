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

$langs->load("users");

$object = new UserDatabase($db);

/*
 * View
 */

if ($_GET['json'] == "list") {
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array()
	);

	try {
		$result = $object->couchdb->listDatabases();
	} catch (Exception $exc) {
		print $exc->getMessage();
	}

	//print_r ($result);

	$iTotal = 0;
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;

	foreach ($result as $aRow) {
		if ($aRow[0] != "_") { // Not _users and _replicator
			try {
				$object->fetch($aRow);
				$info = $object->values;
				$secu = $object->couchAdmin->getSecurity();

				if (count($secu->readers->names) + count($secu->readers->roles) > 0)
					$info->Status = "SECURE";
				else
					$info->Status = "INSECURE";
			} catch (Exception $exc) {
				print $exc->getMessage();
			}

			$output["aaData"][] = $info;
		}
	}

	$iTotal = count($output["aaData"]);
	$output["iTotalRecords"] = $iTotal;
	$output["iTotalDisplayRecords"] = $iTotal;

	header('Content-type: application/json');
	echo json_encode($output);
	exit;
}

/*
 * View
 */

llxHeader();

print '<div class="row">';
print start_box($langs->trans("ListOfDatabases"), "twelve", "16-Cloud.png", false);

print ' <div class="row sepH_b">';
print ' <div class="right">';
if ($user->admin) {
	//print '<a class="gh_button primary pill icon add" href="'.DOL_URL_ROOT.'/user/database/fiche.php?action=create">'.$langs->trans("Create").'</a>';
	//$object->buttonCreate(DOL_URL_ROOT.'/user/database/fiche.php');
}
print "</div>\n";
print "</div>\n";

$i = 0;
$obj = new stdClass();

print '<table class="display dt_act" id="user" >';
// Ligne des titres 
print'<thead>';
print'<tr>';
print'<th class="essential">';
print $langs->trans("Database");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "db_name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . $object->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($object)) . ' : ";
				ar[ar.length] = obj.aData.db_name.toString();
				ar[ar.length] = "\" title=\"' . $langs->trans("See " . get_class($object)) . ' : ";
				ar[ar.length] = obj.aData.db_name.toString();
				ar[ar.length] = "\"></a> <a href=\"' . DOL_URL_ROOT . '/user/database/fiche.php?id=";
				ar[ar.length] = obj.aData.db_name.toString();
				ar[ar.length] = "\">";
				ar[ar.length] = obj.aData.db_name.toString();
				ar[ar.length] = "</a>";
				var str = ar.join("");
				return str;
			}';
$i++;
print'<th class="essential">';
print $langs->trans('NbDoc');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "doc_count";
$obj->aoColumns[$i]->sDefaultContent = 0;
$obj->aoColumns[$i]->sClass = "fright";
$i++;
print'<th class="essential">';
print $langs->trans('UpdateSeq');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "update_seq";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$i++;
print'<th class="essential">';
print $langs->trans('DiskSize');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "disk_size";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("disk_size", "sizeMo");
$i++;
print'<th class="essential">';
print $langs->trans('DataSize');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "data_size";
$obj->aoColumns[$i]->sType = "date";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("data_size", "sizeMo");
$i++;
print'<th class="essential">';
print $langs->trans('Status');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sWidth = "100px";
$obj->aoColumns[$i]->sDefaultContent = "INSECURE";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "fright content_actions";
$obj->aoColumns[$i]->sDefaultContent = "";

$obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"#\" class=\"sepV_a\" title=\"Compact Database\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/ico/icSw2/16-ZIP-File.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"#\" class=\"sepV_a\" title=\"Compact Views\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/ico/icSw2/16-Preview.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"#\" class=\"sepV_a\" title=\"Commit\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/ico/icSw2/16-Create-Write.png\" alt=\"\" /></a>";
	var str = ar.join("");
	return str;
}';
$i++;
print'</tr>';
print'</thead>';
print'<tfoot>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print "</table>";

$obj->sDom = 'l<fr>t<\"clear\"rtip>';
$obj->sAjaxSource = $_SERVER['PHP_SELF'] . '?json=list';

$obj->aaSorting = array(array(0, "asc"));

$object->datatablesCreate($obj, "user", true);



print end_box();
print '<div>';

llxFooter();
?>
