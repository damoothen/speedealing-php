<?php

/* Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 * 
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

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/useradmin/class/useradmin.class.php");

if (!$user->superadmin)
    accessforbidden();

$langs->load("users");
$langs->load("companies");

// Security check (for external users)
$socid = 0;
if ($user->societe_id > 0)
    $socid = $user->societe_id;

$object = new UserAdmin($db);
$companystatic = new Societe($db);

if ($_GET['json'] == "list") {
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array()
    );

    try {
        $result = $object->getAllUsers(true);
        $admins = $object->getUserAdmins();
    } catch (Exception $exc) {
        print $exc->getMessage();
    }

    //print_r ($result);

    $iTotal = count($result);
    $output["iTotalRecords"] = $iTotal;
    $output["iTotalDisplayRecords"] = $iTotal;
    $i = 0;
    foreach ($result as $aRow) {
        $name = substr($aRow->doc->_id, 17);
        if (isset($admins->$name))
            $aRow->doc->admin = true;
        else
            $aRow->doc->admin = false;
        $output["aaData"][] = $aRow->doc;
    }

    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}

/*
 * View
 */

llxHeader();

$title = $langs->trans("ListOfUsers");

print_fiche_titre($title);
print '<div class="with-padding">';
print '<div class="columns">';
print start_box($title, "twelve", "16-User.png", false);

/*
 * Barre d'actions
 *
 */

print '<p class="button-height right">';
print '<span class="button-group">';
print '<a class="button icon-star" href="useradmin/fiche.php?action=create">' . $langs->trans("CreateUser") . '</a>';
print "</span>";
print "</p>";

$i = 0;
$obj = new stdClass();

print '<table class="display dt_act" id="user" >';
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
print $langs->trans("Login");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;

$url = strtolower(get_class($object)) . '/fiche.php?id=';
$key = "name";
$obj->aoColumns[$i]->fnRender = 'function(obj) {
				var ar = [];
				ar[ar.length] = "<img src=\"theme/' . $conf->theme . '/img/ico/icSw2/' . $object->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($object)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\" title=\"' . $langs->trans("See " . get_class($object)) . ' : ";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "\"> <a href=\"' . $url . '";
				ar[ar.length] = obj.aData._id;
				ar[ar.length] = "\">";
				ar[ar.length] = obj.aData.' . $key . '.toString();
				ar[ar.length] = "</a> ";
				if(obj.aData.admin) {
					ar[ar.length] = "<img src=\"theme/' . $conf->theme . '/img/redstar.png\" border=\"0\" ";
					ar[ar.length] = "\" title=\"' . $langs->trans("SuperAdmin") . '";
					ar[ar.length] = "\">";
				}
				var str = ar.join("");
				return str;
			}';
$i++;
print'<th class="essential">';
print $langs->trans('LastName');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Lastname";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "";
$i++;
print'<th class="essential">';
print $langs->trans('FirstName');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Firstname";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "";
$i++;
print'<th class="essential">';
print $langs->trans('Database');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "entityList";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("entityList", "tag");
$i++;
print'<th class="essential">';
print $langs->trans('LastConnexion');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "NewConnection";
$obj->aoColumns[$i]->sType = "date";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sWidth = "200px";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("NewConnection", "datetime");
$i++;
print'<th class="essential">';
print $langs->trans('Status');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "dol_select center";
$obj->aoColumns[$i]->sWidth = "100px";
$obj->aoColumns[$i]->sDefaultContent = "DISABLE";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
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

$object->datatablesCreate($obj, "user", true);



print end_box();
print '<div>';

llxFooter();
?>
