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
 * 	\file       htdocs/comm/serverprocess.php
 * 	\ingroup    commercial societe
 * 	\brief      load data to display
 * 	\version    $Id: serverprocess.php,v 1.6 2012/01/27 16:15:05 synry63 Exp $
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
if (empty($conf->global->SOCIETE_DISABLE_STATE)) {
    if($conf->categorie->enabled){
         $aColumns = array('', 'company', 'ville', 'departement', 'cp', 'datec', 'categorie', 'sale','siren','siret','ape','idprof4', 'fk_prospectlevel', 'fk_stcomm', 'etat', 'priv');
         $aColumnsSql = array('', 's.nom', 's.ville', 'd.nom', 's.cp', 's.datec', 'c.label', 'u.login','s.siren','s.siret','s.ape','s.idprof4','s.fk_prospectlevel', 'fk_stcomm', '', '');
    }
    else {
         $aColumns = array('', 'company', 'ville', 'departement', 'cp', 'datec', 'sale','siren','siret','ape','idprof4', 'fk_prospectlevel', 'fk_stcomm', 'etat', 'priv');
         $aColumnsSql = array('', 's.nom', 's.ville', 'd.nom', 's.cp', 's.datec', 'u.login','s.siren','s.siret','s.ape','s.idprof4','s.fk_prospectlevel', 'fk_stcomm', '', '');
    }
}
else {
    if($conf->categorie->enabled){
        $aColumns = array('', 'company', 'ville', 'cp', 'datec', 'categorie', 'sale','siren','siret','ape','idprof4', 'fk_prospectlevel', 'fk_stcomm', 'etat', 'priv');
        $aColumnsSql = array('', 's.nom', 's.ville', 's.cp', 's.datec', 'c.label', 'u.login','s.siren','s.siret','s.ape','s.idprof4','s.fk_prospectlevel', 'fk_stcomm', '', '');
    
    }
    else{
        $aColumns = array('', 'company', 'ville', 'cp', 'datec', 'sale','siren','siret','ape','idprof4', 'fk_prospectlevel', 'fk_stcomm', 'etat', 'priv');
        $aColumnsSql = array('', 's.nom', 's.ville', 's.cp', 's.datec', 'u.login','s.siren','s.siret','s.ape','s.idprof4','s.fk_prospectlevel', 'fk_stcomm', '', '');

    }
}
/* get Type */
$type = $_GET['type'];
$pstcomm = $_GET['pstcomm'];
$search_sale = $_GET['search_sale'];


/*
 * Paging
 */
//$sLimit = " LIMIT 100";
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
    if ($Cols == 0 || $Cols == ($_GET['iColumns'] - 1) || $Cols == ($_GET['iColumns'] - 2)) {
        $sOrder = "";
    }
}

/* search basic */
$sWhere = "";
if ($_GET['sSearch'] != "") {
    $sWhere = " AND (";
    for ($i = 0; $i < count($aColumnsSql); $i++) {
        if ($aColumnsSql[$i] != '' && $aColumnsSql[$i] != "c.label" && $aColumnsSql[$i] != "u.login") {
            $sWhere .= $aColumnsSql[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
        }
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}

if ($_GET['sSearch_1'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[1]. " LIKE '%" . $_GET['sSearch_1'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_2'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[2] . " LIKE '%" . $_GET['sSearch_2'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_3'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[3] . " LIKE '%" . $_GET['sSearch_3'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_4'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[4] . " LIKE '%" . $_GET['sSearch_4'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_5'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[5] . " LIKE '%" . $_GET['sSearch_5'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_6'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[6] . " LIKE '%" . $_GET['sSearch_6'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_7'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[7] . " LIKE '%" . $_GET['sSearch_7'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_8'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[8] . " LIKE '%" . $_GET['sSearch_8'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_9'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[9] . " LIKE '%" . $_GET['sSearch_9'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_10'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[10] . " LIKE '%" . $_GET['sSearch_10'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_11'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[11] . " LIKE '%" . $_GET['sSearch_11'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_12'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[12] . " LIKE '%" . $_GET['sSearch_12'] . "%'";
    $sWhere .= ')';
}

if ($_GET['sSearch_13'] != "") {
    $sWhere .= " AND (";
    $sWhere .= $aColumnsSql[13]. " LIKE '%" . $_GET['sSearch_13'] . "%'";
    $sWhere .= ')';
}

// If the user must only see his prospect, force searching by him
if (!$user->rights->societe->client->voir && !$socid) {
    $search_sale = $user->id;
}
/*basic companies request query */
$sql = "SELECT s.rowid, s.nom, s.ville, s.datec, s.datea, s.status as status,";
$sql.= " st.libelle as stcomm, s.prefix_comm, s.fk_stcomm, s.fk_prospectlevel,st.type,";
$sql.= " d.nom as departement, s.cp as cp,s.siren,s.siret,s.ape,s.idprof4";
/*looking for categories ? */
$roc = stristr($sOrder, 'c.label');
$rsc = stristr($sWhere, 'c.label');
if ($roc != false || $rsc!=false) {
    $sql.=",c.label";
}
/* looking for sales ? */
$rou = stristr($sOrder, 'u.login');
$rsu = stristr($sWhere, 'u.login');
if ($rou != false || $rsu!=false) {
    $sql.=",u.login";
}
$sql .= " FROM (" . MAIN_DB_PREFIX . "societe as s";
$sql.= " ) ";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_departements as d on (d.rowid = s.fk_departement)";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_stcomm as st ON st.id = s.fk_stcomm";

/* requesting data on categorie filter  */
if ($roc != false || $rsc!=false) {
    $sql.=" LEFT JOIN llx_categorie_societe as cs ON cs.fk_societe = s.rowid ";
    $sql.=" LEFT JOIN llx_categorie as c ON c.rowid=cs.fk_categorie ";
}
/* requesting data on sales filter */
if ($rou != false || $rsu!=false || $search_sale!=0) {
    $sql.=" LEFT JOIN llx_societe_commerciaux as sc ON sc.fk_soc = s.rowid";
    $sql.=" LEFT JOIN llx_user AS u ON u.rowid = sc.fk_user ";
}
$sql.= " WHERE s.client in (1,2,3)";

if ($type != '')
    $sql.= " AND st.type=" . $type;
$sql.= " AND s.entity = " . $conf->entity;

// Insert sale filter
if ($search_sale) {
    $sql .= " AND u.rowid= " . $db->escape($search_sale);
}
// Insert stcomm filter
if ($pstcomm) {
    $sql .= " AND st.id= " . $db->escape($pstcomm);
}
/* get the total of entries */
$resultTotal = $db->query($sql);
$iTotal = $db->num_rows($resultTotal);

$sql.= $sWhere;
/* usefull to regroup by the sale needed */
if($search_sale || $_GET['sSearch_7']!=""){
    $sql.= " GROUP BY s.rowid";
}
$sql.= $sOrder;
$sql.= $sLimit;
$resultSocietes = $db->query($sql);
exit;
//$cb = new couchClient("http://193.169.46.49:5984/","dolibarr");
//$cb = new Couchbase;
//$cb->default_bucket_name="dolibarr";
//$cb->addCouchbaseServer("localhost",11211,8092);

//$cb->flush();

//$uuid=$cb->uuid($iTotal); //generation des uuids

/*get companies. usefull to get their sales and categories */
$i=0;

while ($aRow = $db->fetch_object($resultSocietes)) {
    //if($ancinneValeur!=$aRow->rowid){ //do not insert the (next on the result query) same contact
        //$valueR = $valueR . $aRow->rowid . ',';
        
        //$row=  get_object_vars($aRow);
        //$ancinneValeur = $aRow->rowid;
        $aRow->class="societe";
        //$cb->set($uuid[$i],  json_encode($aRow));
        //$aRow->_id=$aRow->rowid;
        $col[$aRow->rowid] = $aRow;
        
        /*try {
                $cb->storeDoc($aRow);
        } catch (Exception $e) {
            echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
            //exit(1);
        }*/
        
        $i++;
    //}
}
$db->free($resultSocietes);
unset($resultSocietes);

$companies = '""';
if ($valueR != '') {
    $companies = substr_replace($valueR, '', -1);
}
/* sql query get sales */
$sql = " SELECT fk_soc,login FROM (llx_societe_commerciaux as sc,llx_user as u) 
where "/*sc.fk_soc in ($companies) and*/." sc.fk_user=u.rowid";
//$sql .= " LIMIT 100";
$resultCommerciaux = $db->query($sql);

/* init society sales array  */
while ($aRow = $db->fetch_object($resultCommerciaux)) {
    //$commerciauxDeChaqueSociete[$aRow->fk_soc] = $commerciauxDeChaqueSociete[$aRow->fk_soc] . $aRow->login . ', ';
    //$result=$cb->get($aRow->fk_soc);
    //$result=  json_decode($result);
    
    //print $aRow->fk_soc;
    if(!empty($col[$aRow->fk_soc]->rowid)){
        //print $aRow->fk_soc;
        //var_dump($result);exit;
        $col[$aRow->fk_soc]->commerciaux[]=$aRow->login;
        
        //print_r($result);exit;
        
        //$cb->set($aRow->fk_soc,  json_encode($result));
        //exit;
    }
    //$cb->set($aRow->fk_soc);
}
$db->free($resultCommerciaux);
unset($resultCommerciaux);

/* sql query get categories */
$sql = " SELECT fk_societe,label FROM (llx_categorie_societe as cs,llx_categorie as c) 
where "/*cs.fk_societe in ($companies) and*/ ."cs.fk_categorie=c.rowid";
//$sql .= " LIMIT 100";
$resultCate = $db->query($sql);


/* init society categories array */
while ($aRow = $db->fetch_object($resultCate)) {
    //$categoriesDeChaqueSociete[$aRow->fk_societe] = $categoriesDeChaqueSociete[$aRow->fk_societe] . $aRow->label . ', ';
    
    //print $aRow->fk_soc;
    if(!empty($col[$aRow->fk_soc]->rowid)){
        //print $aRow->fk_soc;
        //var_dump($result);exit;
        $col[$aRow->fk_soc]->category[]=$aRow->label;
        //print_r($result);exit;
        //$cb->set($aRow->fk_societe,  json_encode($result));
        //exit;
    }
}
$db->free($resultCate);
unset($resultCate);

$i=0;

foreach ($col as $aRow)
{
    try {
        $couch->storeDocs($col,false);
        //$cb->set($uuid[$i],  json_encode($aRow));
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }
    $i++;
}

?>