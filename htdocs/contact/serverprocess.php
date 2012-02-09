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
 * 	\file       htdocs/contact/serverprocess.php
 * 	\ingroup    societe
 * 	\brief      load data to display
 * 	\version    $Id: serverprocess.php,v 1.5 2012/01/19 16:15:05 synry63 Exp $
 */
require_once("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/contact/class/contact.class.php");
$langs->load("companies");
$langs->load("suppliers");
$langs->load('commercial');
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

if (empty($conf->global->SOCIETE_DISABLE_CONTACTS)){
    $aColumns = array('', 'name', 'firstname', 'poste', 'nom', 'phone', 'email', 'cpost', 'categorie', 'tms', 'priv', '');
    $aColumnsSql = array('', 'p.name', 'p.firstname', 'p.poste', 's.nom', 'p.phone', 'p.email', 's.cp', 'c.label', 'p.tms', 'p.priv', '');
     
}
else {
    $aColumns = array('', 'name', 'firstname', 'poste', 'phone', 'email', 'cpost', 'categorie', 'tms', 'priv', '');
    $aColumnsSql = array('', 'p.name', 'p.firstname', 'p.poste', 'p.phone', 'p.email', 's.cp', 'c.label', 'p.tms', 'p.priv', '');
  
}

/* get Type */
$type = $_GET['type'];
if ($type != "") {
    if ($type == "p")
        $type = '1';
    else if ($type == "c")
        $type = '2';
    else if ($type == "o")
        $type = '0';
}
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
    if ($Cols == 10 || $Cols == 0) { // no ordering on colum unname
        $sOrder = "";
    }
}

/* search basic */
$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = " AND (";
    for ($i = 0; $i < count($aColumnsSql); $i++) {
        if ($aColumnsSql[$i] != '')
            $sWhere .= $aColumnsSql[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
/* search on Lastname */
if ($_GET['sSearch_1'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "p.name " . " LIKE '%" . $_GET['sSearch_1'] . "%'";
    $sWhere .= ')';
}
/* search on Firstname*/
if ($_GET['sSearch_2'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "p.firstname " . " LIKE '%" . $_GET['sSearch_2'] . "%'";
    $sWhere .= ')';
}
/* search on PostOrFunction */
if ($_GET['sSearch_3'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "p.poste " . " LIKE '%" . $_GET['sSearch_3'] . "%'";
    $sWhere .= ')';
}
/* search on company*/
if ($_GET['sSearch_4'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "s.nom " . " LIKE '%" . $_GET['sSearch_4'] . "%'";
    $sWhere .= ')';
}
/* search on phone*/
if ($_GET['sSearch_5'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "p.phone " . " LIKE '%" . $_GET['sSearch_5'] . "%'";
    $sWhere .= ')';
}
/* search on mail*/
if ($_GET['sSearch_6'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "p.email " . " LIKE '%" . $_GET['sSearch_6'] . "%'";
    $sWhere .= ')';
}
/* search on zip */
if ($_GET['sSearch_7'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "s.cp " . " LIKE '%" . $_GET['sSearch_7'] . "%'";
    $sWhere .= ')';
}
/* search on categorie */
if ($_GET['sSearch_8'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "c.label " . " LIKE '%" . $_GET['sSearch_8'] . "%'";
    $sWhere .= ')';
}
/* search on date */
if ($_GET['sSearch_9'] != "") {
    $sWhere .= " AND (";
    $sWhere .= "p.tms " . " LIKE '%" . $_GET['sSearch_9'] . "%'";
    $sWhere .= ')';
}
/* sql query */
$sql = "SELECT s.rowid as socid, s.nom,";
$sql.= " s.cp as cpost, p.rowid as cidp, p.name, p.firstname, p.poste, p.email,";
$sql.= " p.phone, p.phone_mobile, p.fax, p.fk_pays, p.priv,";
$sql.= " p.tms,";
$sql.= " cp.code as pays_code";
$ro = stristr($sOrder, 'c.label');
$rs = stristr($sWhere, 'c.label');
if ($ro != false || $rs!=false) {
    $sql.=",c.label";
}
$sql.= " FROM " . MAIN_DB_PREFIX . "socpeople as p";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_pays as cp ON cp.rowid = p.fk_pays";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON s.rowid = p.fk_soc";

/* requesting data on categorie filter  */

if ($ro != false  || $rs!=false) {
    $sql.=" LEFT JOIN llx_categorie_contact as cc ON cc.fk_contact = p.rowid ";
    $sql.=" LEFT JOIN llx_categorie as c ON c.rowid=cc.fk_categorie ";
}


if ($type != "")
    $sql.= " LEFT JOIN llx_c_stcomm as cs ON cs.id=s.fk_stcomm";

if (!$user->rights->societe->client->voir) {
    $sql .= " LEFT JOIN " . MAIN_DB_PREFIX . "societe_commerciaux as sc ON s.rowid = sc.fk_soc";
}
$sql.= " WHERE p.entity = " . $conf->entity . ' ';
if (!$user->rights->societe->client->voir) { //restriction
    $sql .= " AND (sc.fk_user = " . $user->id . " OR p.fk_soc IS NULL)";
}

// Filter to exclude not owned private contacts
$sql .= " AND (p.priv='0' OR (p.priv='1' AND p.fk_user_creat=" . $user->id . "))";
if ($type != '') {
    $sql.= " AND cs.type=" . $type;
}
//print $sql;

$result = $db->query($sql);
$iTotal = $db->num_rows($result);
$sql.= $sWhere;
$sql.= $sOrder;
$sql.= $sLimit;
$resultContact = $db->query($sql);

/* get contacts. usefull to get their categories */
while ($aRow = $db->fetch_object($resultContact)) {
    if($ancinneValeur!=$aRow->socid){//do not insert the (next on the result query) same contact
        $valueR = $valueR . $aRow->cidp . ',';
        $col[] = $aRow;
        $ancinneValeur = $aRow->socid;
    }
    
}
$contacts = '""';
if ($valueR != '') {
    $contacts = substr_replace($valueR, '', -1);
}
/* query get categories */
$sql = "SELECT cs.fk_contact,c.label FROM (llx_categorie_contact as cs,llx_categorie as c) 
WHERE cs.fk_contact in ($contacts) AND cs.fk_categorie=c.rowid";
$resultCate = $db->query($sql);
$categoriesDeChaqueContact = array();
$contactstatic = new Contact($db);

/* init contact categories array */
while ($aRow = $db->fetch_object($resultCate)) {
    $categoriesDeChaqueContact[$aRow->fk_contact] = $categoriesDeChaqueContact[$aRow->fk_contact] . $aRow->label . ', ';
}
/*
 * Output
 */
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iTotal,
    "aaData" => array()
);
if ($col != null) {
    foreach ($col as $aRow) {
        $row = array();

        for ($i = 0; $i < count($aColumns); $i++) {

            if ($aColumns[$i] == "name") {
                $contactstatic->name = $aRow->name;
                $contactstatic->firstname = '';
                $contactstatic->id = $aRow->cidp;
                $row[] = $contactstatic->getNomUrl(1, '', 20);
            } else if ($aColumns[$i] == "tms") {
                $row[] = dol_print_date($db->jdate($aRow->tms), "day");
            } else if ($aColumns[$i] == "categorie") {
                $row[] = $categoriesDeChaqueContact[$aRow->cidp];
            } else if ($aColumns[$i] == "email") {
                $row[] = dol_print_email($aRow->email, $aRow->cidp, $aRow->socid, 'AC_EMAIL', 18);
            } else if ($aColumns[$i] == "priv") {
                $row[] = $contactstatic->LibPubPriv($aRow->priv);
            } else if ($i == 0) {
                $row[] = '<img id="' . $aRow->cidp . '" class="plus" src="../theme/cameleo/img/details_open.png">';
            } else if ($i == (count($aColumns) - 1)) { //last
                $row[] = '<a href="' . DOL_URL_ROOT . '/comm/action/fiche.php?action=create&amp;backtopage=1&amp;contactid=' . $aRow->cidp . '&amp;socid=' . $aRow->socid . '">' . img_object($langs->trans("AddAction"), "action") . '</a>'
                        . ' &nbsp;'
                        . ' &nbsp;' .
                        '<a href="' . DOL_URL_ROOT . '/contact/vcard.php?id=' . $aRow->cidp . '">' .
                        img_picto($langs->trans("VCard"), 'vcard.png') . ' ' .
                        '</a>';
            } else if ($aColumns[$i] != ' ') {
                /* General output */
                $attribut = $aColumns[$i];
                $row[] = $aRow->$attribut;
            }
        }

        $output['aaData'][] = $row;
    }
}
//$db->free($result);
header('Content-type: application/json');
echo json_encode($output);
?>