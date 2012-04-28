<?php

/* Copyright (C) 2001-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Philippe Grand       <philippe.grand@atoo-net.com>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2011      Patrick Mary           <laube@hotmail.fr>
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
 * 	\file       htdocs/comm/list.php
 * 	\ingroup    commercial societe
 * 	\brief      List of customers
 * 	\version    $Id: list.php,v 1.80 2012/01/12 16:15:05 synry63 Exp $
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/comm/prospect/class/prospect.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formother.class.php");

$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");



// Security check
$socid = GETPOST("socid");
if ($user->societe_id)
    $socid = $user->societe_id;
$result = restrictedArea($user, 'societe', $socid, '');

$type = GETPOST("type", 'int');
$pstcomm = GETPOST("pstcomm");
$search_sale = GETPOST("search_sale");

$object = new Societe($couch);

if($_GET['json'])
{
    $output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
    );

    $result = $object->getView("list");


    //print_r($result);
    //exit;
    $iTotal=  count($result->rows);
    $output["iTotalRecords"]=$iTotal;
    $output["iTotalDisplayRecords"]=$iTotal;


    foreach($result->rows AS $aRow) {
        if(!isset($aRow->value->commerciaux))
            $aRow->value->commerciaux=null;
        if(!isset($aRow->value->category))
            $aRow->value->category=null;
        unset($aRow->value->class);
        unset($aRow->value->_rev);
        $output["aaData"][]=$aRow->value;
        unset($aRow);
    }

    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}


/*
 * Actions
 */
if ($_GET["action"] == 'cstc') {
    $sql = "UPDATE " . MAIN_DB_PREFIX . "societe SET fk_stcomm = " . $_GET["stcomm"];
    $sql .= " WHERE rowid = " . $_GET["socid"];
    $result = $db->query($sql);
}
// Select every potentiels.
$sql = "SELECT code, label, sortorder";
$sql.= " FROM ".MAIN_DB_PREFIX."c_prospectlevel";
$sql.= " WHERE active > 0";
$sql.= " ORDER BY sortorder";
$resql = $db->query($sql);
if ($resql)
{
    $tab_level = array();
    while ($obj = $db->fetch_object($resql))
        {     
            $level=$obj->code;
            // Put it in the array sorted by sortorder
            $tab_level[$obj->sortorder] = $level;
        }

 // Added by Matelli (init list option)
   $options = '<option value="">&nbsp;</option>';
   foreach ($tab_level as $tab_level_label)
     {
     $options .= '<option value="'.$tab_level_label.'">';
     $options .= $langs->trans($tab_level_label);
     $options .= '</option>';
     }        
}

/*
 * View
 */

$htmlother = new FormOther($db);


llxHeader('', $langs->trans("ThirdParty"), $help_url, '', '', '', '');

if ($type != '') {
    if ($type == 0)
        $titre = $langs->trans("ListOfSuspects");
    elseif ($type == 1)
        $titre = $langs->trans("ListOfProspects");
    else
        $titre = $langs->trans("ListOfCustomers");
}
else
    $titre = $langs->trans("ListOfAll");

print '<div class=row>';

print start_box($titre,"twelve","addressbook.png");

$i=0;
$obj=new stdClass();

print '<table cellpadding="0" cellspacing="0" border="0" class="display dt_act" id="societe" >';
// Ligne des titres 
print'<thead>';
print'<tr>';
print'<th class="essential">';
print $langs->trans("Company");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = true;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender= '%function(obj) {
var ar = [];
ar[ar.length] = $<a href=\"'.DOL_URL_ROOT.'/societe/soc.php?socid=$;
ar[ar.length] = obj.aData._id;
ar[ar.length] = $\"><img src=\"'.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/object_company.png\" border=\"0\" alt=\"Afficher mailing : $;
ar[ar.length] = obj.aData.name.toString();
ar[ar.length] = $\" title=\"Afficher soci&eacute;t&eacute;:$;
ar[ar.length] = obj.aData.name.toString();
ar[ar.length] = $\"></a> <a href=\"'.DOL_URL_ROOT.'/societe/soc.php?socid=$;
ar[ar.length] = obj.aData._id;
ar[ar.length] = $\">$;
ar[ar.length] = obj.aData.name.toString();
ar[ar.length] = $</a>$;
var str = ar.join("");
return str;
}%';
$i++;
print'<th class="essential">';
print $langs->trans("Town");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "town";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("Zip");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "zip";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->bVisible = false;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
if (empty($conf->global->SOCIETE_DISABLE_STATE)) {
    print'<th class="essential">';
    print $langs->trans("State");
    print'</th>';
}
if ($conf->categorie->enabled) {
    print'<th class="essential">';
    print $langs->trans('Categories');
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "category";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
}
print'<th class="essential">';
print $langs->trans('SalesRepresentatives');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "commerciaux";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('Siren');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "idprof1";
$obj->aoColumns[$i]->bVisible = false;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('Ape');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "idprof2";
$obj->aoColumns[$i]->bVisible = false;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("ProspectLevelShort");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "potentiel";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "fk_stcomm";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = 0;
$obj->aoColumns[$i]->fnRender = '%function(obj) {
var status = new Array();
var stcomm = obj.aData.fk_stcomm;
if(typeof str === $undefined$)
    stcomm = 0;
status[0] = new Array($'.$langs->trans('MailingStatusDraft').'$,0);
status[1] = new Array($'.$langs->trans('MailingStatusValidated').'$,1);
status[2] = new Array($'.$langs->trans('MailingStatusSentPartialy').'$,3);
status[3] = new Array($'.$langs->trans('MailingStatusSentCompletely').'$,6);
var ar = [];
ar[ar.length] = $<img src=\"'.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/statut$;
ar[ar.length] = status[stcomm][1];
ar[ar.length] = $.png\" border=\"0\" alt=\"Afficher mailing : $;
ar[ar.length] = $\" title=\"$;
ar[ar.length] = status[stcomm][0];
ar[ar.length] = $\">$;
var str = ar.join("");
return str;
}%';
$i++;
print'<th class="essential">';
print $langs->trans("Date");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "tms";
$obj->aoColumns[$i]->sType="date";
$obj->aoColumns[$i]->sClass = "center";
//$obj->aoColumns[$i]->sWidth = "50px";
$obj->aoColumns[$i]->fnRender = '%function(obj) {
if(obj.aData.tms)
{
    var date = new Date(obj.aData.tms*1000);
    return date.toLocaleDateString();
}
else
    return null;
}%';
print'</tr>';
print'</thead>';
print'<tfoot>';
/* input search view */
print'<tr>';
print'<th><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Company") . '" /></th>';
print'<th><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Town") . '" /></th>';
print'<th><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Zip") . '" /></th>';
/*if(empty($conf->global->SOCIETE_DISABLE_STATE)) {
    print'<th></th>';
    $i++;
}*/
if ($conf->categorie->enabled) {
        print'<th><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search category") . '" /></th>';
}
print'<th><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search sales") . '" /></th>';
print'<th><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search siren") . '" /></th>';
print'<th></th>';
print'<th></th>';
print'<th></th>';
print'<th></th>';
print'</tr>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print "</table>";

print $object->_datatables($obj,"#societe");

print end_box();
print '</div>'; // end row

llxFooter();
?>
 
