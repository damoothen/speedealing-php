<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville   <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur    <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Marc Barilley / Ocebo  <marc@ocebo.com>
 * Copyright (C) 2005-2012 Regis Houssin          <regis@dolibarr.fr>
 * Copyright (C) 2012      Juanjo Menent          <jmenent@2byte.es>
 * Copyright (C) 2012      David Moothen          <dmoothen@websitti.fr>
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
 *	\file       htdocs/commande/liste.php
 *	\ingroup    commande
 *	\brief      Page to list orders
 */


require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/commande/class/commande.class.php';

$langs->load('orders');
$langs->load('deliveries');
$langs->load('companies');

$object = new Commande($db);
$societe = new Societe($db);

if (!empty($_GET['json'])) {
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array()
    );

//    $keystart[0] = $user->id;
//    $keyend[0] = $user->id;
//    $keyend[1] = new stdClass();

    /* $params = array('startkey' => array($user->id, mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"))),
      'endkey' => array($user->id, mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")))); */

    try {
        $result = $object->getView($_GET["json"]);
    } catch (Exception $exc) {
        print $exc->getMessage();
    }

    $iTotal = count($result->rows);
    $output["iTotalRecords"] = $iTotal;
    $output["iTotalDisplayRecords"] = $iTotal;
    $i = 0;
    foreach ($result->rows as $aRow) {
        $output["aaData"][] = $aRow->value;
    }

    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}

/*
 * View
 */

$now=dol_now();

$form = new Form($db);
$formother = new FormOther($db);
$formfile = new FormFile($db);
$companystatic = new Societe($db);

$help_url="EN:Module_Customers_Orders|FR:Module_Commandes_Clients|ES:Módulo_Pedidos_de_clientes";
$title = $langs->trans('Orders');
llxHeader('',$title,$help_url);
print_fiche_titre($title);
?>
<div class="dashboard">
    <div class="columns">
        <div class="four-columns twelve-columns-mobile graph">
            <?php $object->graphPieStatus(); ?>
        </div>

        <div class="eight-columns twelve-columns-mobile new-row-mobile graph">
            <?php $object->graphBarStatus(); ?>
        </div>
    </div>
</div>
<?php
print '<div class="with-padding" >';

/*
 * Barre d'actions
 *
 */

if ($user->rights->commande->creer) {
    print '<p class="button-height right">';
    print '<span class="button-group">';
    print '<a class="button icon-star" href="commande/commande.php?action=create">' . $langs->trans("NewOrder") . '</a>';
    print "</span>";
    print "</p>";
}

$i = 0;
$obj = new stdClass();

print $object->datatablesEdit("listorders", $langs->trans("NewOrder"));

print '<table class="display dt_act" id="listorders" >';
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
print $langs->trans("Ref");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "ref";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("ref", "url", array('url' => 'commande/commande.php?id='));
$i++;
print'<th class="essential">';
print $langs->trans('Company');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "client.name";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $societe->datatablesFnRender("client.name", "url", array('id' => "client.id"));
$i++;
print'<th class="essential">';
print $langs->trans("RefCustomer");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "ref_client";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->editable = true;
$i++;
print'<th class="essential">';
print $langs->trans('Date');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "date";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("date", "date");
$obj->aoColumns[$i]->editable = true;
$i++;
print'<th class="essential">';
print $langs->trans('DateEnd');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "date_livraison";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("date_livraison", "date");
$obj->aoColumns[$i]->editable = true;
$i++;

//print'<th class="essential">';
//print $langs->trans('Contact');
//print'</th>';
//$obj->aoColumns[$i]->mDataProp = "contact.name";
//$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->fnRender = $contact->datatablesFnRender("contact.name", "url", array('id' => "contact.id"));
//$i++;
 print'<th class="essential">';
  print $langs->trans('ActionUserAsk');
  print'</th>';
  $obj->aoColumns[$i] = new stdClass();
  $obj->aoColumns[$i]->mDataProp = "author";
  $obj->aoColumns[$i]->sDefaultContent = "";
  //$obj->aoColumns[$i]->fnRender = $userstatic->datatablesFnRender("author.name", "url", array('id' => "author.id"));
  $i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "DRAFT";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
$obj->aoColumns[$i]->editable = true;
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->sWidth = "60px";
$obj->aoColumns[$i]->bSortable = false;
$obj->aoColumns[$i]->sDefaultContent = "";

$url = "commande/commande.php";
$obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"' . $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=edit&backtopage=' . $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"' . $langs->trans("Edit") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"\"";
	ar[ar.length] = " class=\"delEnqBtn\" title=\"' . $langs->trans("Delete") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
	var str = ar.join("");
	return str;
}';
print'</tr>';
print'</thead>';
print'<tfoot>';
/* input search view */
$i = 0; //Doesn't work with bServerSide
print'<tr>';
print'<th id="' . $i . '"></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Ref") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Company") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search RefCustomer") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Date") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search DateEnd") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search author") . '" /></th>';
$i++;
//print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Status") . '" /></th>';
//$i++;
print'<th id="' . $i . '"></th>';
$i++;
print'</tr>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print "</table>";

$obj->aaSorting = array(array(2, 'asc'));
//$obj->bServerSide = true;

//if ($all) {
//    if ($type == "DONE")
//        $obj->sAjaxSource = "core/ajax/listdatatables.php?json=actionsDONE&class=" . get_class($object);
//    else
//        $obj->sAjaxSource = "core/ajax/listdatatables.php?json=actionsTODO&class=" . get_class($object);
//} else {
//    if ($type == "DONE")
//        $obj->sAjaxSource = $_SERVER["PHP_SELF"] . "?json=listDONEByUser";
//    else
//        $obj->sAjaxSource = $_SERVER["PHP_SELF"] . "?json=listTODOByUser";
//
//}
$obj->sAjaxSource = $_SERVER["PHP_SELF"] . "?json=list";

$object->datatablesCreate($obj, "listorders", true, true);

print '</div>';


llxFooter();

$db->close();
?>
