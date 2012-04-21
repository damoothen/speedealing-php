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


llxHeader('', $langs->trans("ThirdParty"), $help_url, '', '', '', $object->arrayjs);

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

print_barre_liste($titre, $page, '', '', '', '', '', 0, 0);

print $object->listSociete("#list");

print '<table cellpadding="0" cellspacing="0" border="0" class="liste" id="list" width="100%">';
// Ligne des titres 
print'<thead>';
print'<tr class="liste_titre">';
print'<th class="sorting">';
print $langs->trans("Company");
print'</th>';
print'<th class="sorting">';
print $langs->trans("Town");
print'</th>';
print'<th class="sorting">';
print $langs->trans("Zip");
print'</th>';
if (empty($conf->global->SOCIETE_DISABLE_STATE)) {
    print'<th class="sorting">';
    print $langs->trans("State");
    print'</th>';
}
if ($conf->categorie->enabled) {
    print'<th class="sorting">';
    print $langs->trans('Categories');
    print'</th>';
}
print'<th class="sorting">';
print $langs->trans('SalesRepresentatives');
print'</th>';
print'<th class="sorting">';
print $langs->trans('Siren');
print'</th>';
print'<th class="sorting">';
print $langs->trans('Ape');
print'</th>';
print'<th class="sorting">';
print $langs->trans("ProspectLevelShort");
print'</th>';
print'<th class="sorting">';
print $langs->trans("Status");
print'</th>';
print'<th class="sorting">';
print $langs->trans("Date");
print'</th>';
print'</tr>';
print'</thead>';
print'<tbody class="contenu">';
print'</tbody>';

$i=0;

/* input search view */
print'<tbody class="recherche">';
print'<tr>';
print'<td id="'.$i.'"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Company") . '" class="inputSearch"/></td>';
$i++;
print'<td id="'.$i.'"><input style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Town") . '" class="inputSearch" /></td>';
$i++;
print'<td id="'.$i.'"><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search Zip") . '" class="inputSearch" /></td>';
$i++;
if(empty($conf->global->SOCIETE_DISABLE_STATE)) {
    print'<th></th>';
    $i++;
}
if ($conf->categorie->enabled) {
        print'<td id="'.$i.'"><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search category") . '" class="inputSearch" /></td>';
        $i++;
}
print'<td id="'.$i.'"><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search sales") . '" class="inputSearch" /></td>';
$i++;
print'<td id="'.$i.'"><input  style="margin-top:1px;"  type="text" placeholder="' . $langs->trans("Search siren") . '" class="inputSearch" /></td>';
$i++;
print'<th></th>';
$i++;   
print'<th></th>';
$i++;
print'<th></th>';
$i++;
print'<th></th>';
print'</tr>';
print'</tbody>';
print "</table>";

llxFooter();
?>
 
