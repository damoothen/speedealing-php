<?php

/* Copyright (C) 2012      Herve Prot           <herve.prot@symeos.com>
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

require("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/user/class/userdatabase.class.php");

$langs->load("users");

$id = GETPOST('id', 'alpha');
$action = GETPOST("action");
$confirm = GETPOST("confirm");

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
                try {
                    $object->fetch($aRow);
                } catch (Exception $e) {

                }
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

if ($action == 'confirm_delete' && $confirm == "yes") {
    if ($user->admin) {
        $object->id = $id;
        $object->delete();
        Header("Location: index.php");
        exit;
    } else {
        $langs->load("errors");
        $message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
    }
}

if ($action == 'commit') {
    if ($user->admin) {
        $object->id = $id;
        $object->commit();
        Header("Location: index.php");
        exit;
    } else {
        $langs->load("errors");
        $message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
    }
}

if ($action == 'compact') {
    if ($user->admin) {
        $object->id = $id;
        //$object->purgeDatabase(); //Need Optimization
        $object->compact();
        $object->compactView();
        Header("Location: index.php");
        exit;
    } else {
        $langs->load("errors");
        $message = '<div class="error">' . $langs->trans('ErrorForbidden') . '</div>';
    }
}

/*
 * View
 */

llxHeader();

$form = new Form($db);

if ($action == 'delete') {
    $ret = $form->form_confirm($_SERVER['PHP_SELF'] . "?id=" . $id, $langs->trans("DeleteADatabase"), $langs->trans("ConfirmDatabase", $id), "confirm_delete", '', 0, 1);
    if ($ret == 'html')
        print '<br>';
}


$title = $langs->trans("ListOfDatabases");

print_fiche_titre($title);
print '<div class="with-padding">';
print '<div class="columns">';
print start_box($title, "twelve", "16-Cloud.png", false);

if ($user->admin) {
    print '<p class="button-height right">';
    print '<span class="button-group">';
    print '<a class="button icon-star" href="user/database/fiche.php?action=create">' . $langs->trans("CreateDatabase") . '</a>';
    print "</span>";
    print "</p>";
}

$i = 0;
$obj = new stdClass();

print '<table class="display dt_act" id="database" >';
// Ligne des titres
print'<thead>';
print'<tr>';
print'<th class="essential">';
print $langs->trans("Database");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "db_name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/ico/icSw2/' . $object->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($object)) . ' : ";
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
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "doc_count";
$obj->aoColumns[$i]->sDefaultContent = 0;
$obj->aoColumns[$i]->sClass = "fright";
$i++;
print'<th class="essential">';
print $langs->trans('UpdateSeq');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "update_seq";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$i++;
print'<th class="essential">';
print $langs->trans('Commit');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "committed_update_seq";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$i++;
print'<th class="essential">';
print $langs->trans('DiskSize');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "disk_size";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("disk_size", "sizeMo");
$i++;
print'<th class="essential">';
print $langs->trans('DataSize');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "data_size";
$obj->aoColumns[$i]->sType = "date";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "fright";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("data_size", "sizeMo");
$i++;
print'<th class="essential">';
print $langs->trans('Status');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sWidth = "100px";
$obj->aoColumns[$i]->sDefaultContent = "INSECURE";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->sDefaultContent = "";

$obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"' . $_SERVER['PHP_SELF'] . '?id=";
	ar[ar.length] = obj.aData.db_name.toString();
	ar[ar.length] = "&action=compact\" class=\"sepV_a\" title=\"Compact Database\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/processing.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"' . $_SERVER['PHP_SELF'] . '?id=";
	ar[ar.length] = obj.aData.db_name.toString();
	ar[ar.length] = "&action=commit\" class=\"sepV_a\" title=\"Commit\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/save.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"' . $_SERVER['PHP_SELF'] . '?id=";
	ar[ar.length] = obj.aData.db_name.toString();
	ar[ar.length] = "&action=delete\" class=\"sepV_a\" title=\"Delete\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
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

$object->datatablesCreate($obj, "database", true);



print end_box();
print '<div>';
print '<div>';

llxFooter();
?>