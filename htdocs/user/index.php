<?php

/* Copyright (C) 2002-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis.houssin@capnetworks.com>
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

/**
 *      \file       htdocs/user/index.php
 * 		\ingroup	core
 *      \brief      Page of users
 */
require("../main.inc.php");

if (!$user->rights->user->user->lire && !$user->admin)
    accessforbidden();

$langs->load("users");
$langs->load("companies");

// Security check (for external users)
$socid = 0;
if ($user->societe_id > 0)
    $socid = $user->societe_id;

$object = new User($db);
$companystatic = new Societe($db);

if ($_GET['json'] == "list") {
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array()
    );

    try {
        $result = $object->getView('list');
        $admins = $object->getDatabaseAdminUsers();
        $enabled = $object->getDatabaseReaderUsers();
    } catch (Exception $exc) {
        print $exc->getMessage();
    }

    //print_r ($enabled);

    $iTotal = count($result);
    $output["iTotalRecords"] = $iTotal;
    $output["iTotalDisplayRecords"] = $iTotal;
    $i = 0;
    foreach ($result->rows as $aRow) {
        $name = $aRow->value->email;
        if (in_array($name, $admins)) // Is Localadministrator
            $aRow->value->admin = true;
        else
            $aRow->value->admin = false;

        if (in_array($name, $enabled)) // Is Status = ENABLE
            $aRow->value->Status = "ENABLE";
        else {
            if ($aRow->value->admin)
                $aRow->value->Status = "ENABLE";
            else
                $aRow->value->Status = "DISABLE";
        }
        $output["aaData"][] = $aRow->value;
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
print '<a class="button icon-star" href="user/fiche.php?action=create">' . $langs->trans("CreateUser") . '</a>';
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
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "_id";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->bVisible = false;
$i++;
print'<th class="essential">';
print $langs->trans("Login");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
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
					ar[ar.length] = "<img src=\"theme/' . $conf->theme . '/img/star.png\" border=\"0\" ";
					ar[ar.length] = "\" title=\"' . $langs->trans("Administrator") . '";
					ar[ar.length] = "\">";
				}
				var str = ar.join("");
				return str;
			}';
$i++;
print'<th class="essential">';
print $langs->trans('EMail');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "email";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "";
$i++;
print'<th class="essential">';
print $langs->trans('LastName');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "Lastname";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "";
$i++;
print'<th class="essential">';
print $langs->trans('FirstName');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "Firstname";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "";
$i++;
print'<th class="essential">';
print $langs->trans('Services');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "group";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("group", "tag");
$i++;
print'<th class="essential">';
print $langs->trans('LastConnexion');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
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
$obj->aoColumns[$i] = new stdClass();
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
