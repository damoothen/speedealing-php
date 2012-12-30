<?php

/* Copyright (C) 2002-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
if (!class_exists('UserGroup'))
	require DOL_DOCUMENT_ROOT . '/user/class/usergroup.class.php';

$langs->load("users");

$object = new UserGroup($db);

/*
 * View
 */

// FIXME use an ajax response file for avoid reload all classes and increase performance

if ($_GET['json'] == "list") {
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array()
    );

    try {
        $result1 = $object->getView("list");
        $result2 = $object->getView("count_list", array("group"=>true));
    } catch (Exception $exc) {
        print $exc->getMessage();
    }

    $iTotal = count($result1->rows);
    $output["iTotalRecords"] = $iTotal;
    $output["iTotalDisplayRecords"] = $iTotal;

    $result = array();
    if (!empty($result1->rows)) {
    	foreach ($result1->rows as $aRow) {
    		$result[$aRow->value->name] = $aRow->value;
    	}
    }

    if (!empty($result2->rows)) {
    	foreach ($result2->rows as $aRow) {
    		$result[$aRow->key]->nb = $aRow->value;
    	}
    }

    if (!empty($result)) {
    	foreach ($result as $aRow) {
    		$output["aaData"][] = $aRow;
    	}
    }

    header('Content-type: application/json');
    //echo $_GET["callback"] . '(' . json_encode($output) . ');';
    echo json_encode($output);
    exit;
}

/*
 * View
 */

llxHeader();

$title = $langs->trans("ListOfGroups");

print_fiche_titre($title);
print '<div class="with-padding">';
print '<div class="columns">';
print start_box($title, "twelve", "16-Users-2.png", false);

/*
 * Barre d'actions
 *
 */

if ($user->admin) {
    print '<p class="button-height right">';
    print '<span class="button-group">';
    print '<a class="button icon-star" href="user/group/fiche.php?action=create">' . $langs->trans("CreateGroup") . '</a>';
    print "</span>";
    print "</p>";
}


$i = 0;
$obj = new stdClass();

print '<table class="display dt_act" id="group" >';
// Ligne des titres
print'<thead>';
print'<tr>';
print'<th class="essential">';
print $langs->trans("Group");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/ico/icSw2/' . $object->fk_extrafields->ico . '\" border=\"0\" alt=\"' . $langs->trans("See " . get_class($object)) . ' : ";
	ar[ar.length] = obj.aData.name.toString();
	ar[ar.length] = "\" title=\"' . $langs->trans("See " . get_class($object)) . ' : ";
	ar[ar.length] = obj.aData.name.toString();
	ar[ar.length] = "\"></a> <a href=\"' . DOL_URL_ROOT . '/user/group/fiche.php?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "\">";
	ar[ar.length] = obj.aData.name.toString();
	ar[ar.length] = "</a>";
	var str = ar.join("");
	return str;
}';
$i++;
print'<th class="essential">';
print $langs->trans('NbUsers');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "nb";
$obj->aoColumns[$i]->sDefaultContent = 0;
$obj->aoColumns[$i]->sClass = "fright";
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

$object->datatablesCreate($obj, "group", true);



print end_box();
print '<div>';

llxFooter();
?>