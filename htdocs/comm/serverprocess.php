<?php
/* Copyright (C) 2012      Patrick Mary           <laube@hotmail.fr>
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
 *	\file       htdocs/comm/serverprocess.php
 *	\ingroup    commercial societe
 *	\brief      load data to display
 *	\version    $Id: serverprocess.php,v 1.5 2012/01/19 16:15:05 synry63 Exp $
 */

require_once("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/comm/prospect/class/prospect.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formother.class.php");
$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
$aColumns = array('', 'company', 'ville', 'departement', 'cp', 'datec','categorie','sale','fk_prospectlevel', 'fk_stcomm', 'etat', 'priv');
$aColumnsSql = array('', 's.nom', 's.ville', 'd.nom', 's.cp', 's.datec','c.label','u.name','s.fk_prospectlevel', 'fk_stcomm', '', '');

/* get Type */
$type = $_GET['type']; 

/*
 * Paging
 */
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = " LIMIT " . $_GET['iDisplayStart'] . ", " .
            $_GET['iDisplayLength'];
}

/*
 * Ordering
 */

if (isset($_GET['iSortCol_0'])) {
    $sOrder = " ORDER BY  ";
    for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
            $Cols = intval($_GET['iSortCol_' . $i]);
            $sOrder .= $aColumnsSql[intval($_GET['iSortCol_' . $i])] . "
				 	" . ($_GET['sSortDir_' . $i]) . ", ";
        }
    }
    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == "ORDER BY") {
        $sOrder = "";
    }
    if ($Cols == 10 || $Cols == 0 || $Cols == 11) {
        $sOrder = "";
    }
}

/* search basic */
$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = " AND (";
    for ($i = 0; $i < count($aColumnsSql); $i++) {
        if ($aColumnsSql[$i] != '') {
            $sWhere .= $aColumnsSql[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
        }
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
/*search on categories */
if ($_GET['sSearch_0'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "c.label " . " LIKE '%" . $_GET['sSearch_0'] . "%'";
    $sWhere .= ')';
}
/*search on sales */
if ($_GET['sSearch_1'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "u.name " . " LIKE '%" . $_GET['sSearch_1'] . "%'";
    $sWhere .= ')';
}
// If the user must only see his prospect, force searching by him
if (!$user->rights->societe->client->voir && !$socid){
        $search_sale = $user->id;
}


/* sql query */
$sql = "SELECT s.rowid, s.nom, s.ville, s.datec, s.datea, s.status as status,";
$sql.= " st.libelle as stcomm, s.prefix_comm, s.fk_stcomm, s.fk_prospectlevel,st.type,";
$sql.= " d.nom as departement, s.cp as cp,c.label,u.name "; 

$sql .= " FROM (" . MAIN_DB_PREFIX . "societe as s";
$sql.= " ) ";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_departements as d on (d.rowid = s.fk_departement)";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_stcomm as st ON st.id = s.fk_stcomm";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe_commerciaux as sc ON sc.fk_soc = s.rowid";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "user AS u ON u.rowid = sc.fk_user";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "categorie_societe as cs ON cs.fk_societe = s.rowid";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "categorie as c ON c.rowid=cs.fk_categorie";
$sql.= " WHERE s.client in (1,2,3)";

if ($type != '')
    $sql.= " AND st.type=" . $type;

$sql.= " AND s.entity = " . $conf->entity;

// Insert sale filter
if ($search_sale) {
    $sql .= " AND u.rowid= " . $db->escape($search_sale);
}
$result = $db->query($sql);
if ($result) {
    $iTotal = $db->num_rows($result); 
    $sql.= $sWhere;
    $sql.= $sOrder;
    $sql.= $sLimit;
    $result = $db->query($sql);
    $prospectstatic = new Prospect($db);

    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iTotal,
        "aaData" => array()
    );

    while ($aRow = $db->fetch_object($result)) {
        $row = array();

        for ($i = 0; $i < count($aColumns); $i++) {

            if ($aColumns[$i] == "company") {
                $prospectstatic->id = $aRow->rowid;
                $prospectstatic->nom = $aRow->nom;
                $prospectstatic->status = $aRow->status;
                if ($aRow->type == 2)
                    $row[] = $prospectstatic->getNomUrl(1, 'customer');
                else
                    $row[] = $prospectstatic->getNomUrl(1, 'prospect');
            }
            else if($aColumns[$i]=="categorie"){
                $row[] = $aRow->label;
            }
            else if($aColumns[$i]=="sale"){
                $row[] = $aRow->name;
            }
            else if ($aColumns[$i] == "datec") {
                $row[] = dol_print_date($db->jdate($aRow->datec));
            } else if ($aColumns[$i] == "etat") {
                $prospectstatic->stcomm_id = $aRow->fk_stcomm;
                $prospectstatic->type = $aRow->type;
                $row[] = $prospectstatic->getIconList(DOL_URL_ROOT . "/comm/list.php?socid=" . $aRow->rowid . $param . '&lang='.$langs->defaultlang.'&type='.$type.'&action=cstc&amp;' . ($page ? '&amp;page=' . $page : ''));
            } else if ($aColumns[$i] == "fk_prospectlevel") { // Level
                $row[] = $prospectstatic->LibLevel($aRow->fk_prospectlevel);
            } else if ($aColumns[$i] == "fk_stcomm") { //status
                $row[] = $prospectstatic->LibProspStatut($aRow->fk_stcomm, 2);
            } else if ($i == 0) { //first
                $row[] = '<img id="' . $aRow->rowid . '" class="plus" src="../theme/cameleo/img/details_open.png">';
            } else if ($i == (count($aColumns) - 1)) { //last
                $row[] = $prospectstatic->getLibStatut(3);
            } else if ($aColumns[$i] != ' ') {
                /* General output */
                $attribut = $aColumns[$i];
                $row[] = $aRow->$attribut;
            }
        }

        $output['aaData'][] = $row;
    }
    $db->free($result);
    header('Content-type: application/json');
    echo json_encode($output);
} else {
    dol_print_error($db);
}
?>