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

/* get Type */
$type = $_GET['type'];
$pstcomm = $_GET['pstcomm'];
$search_sale = $_GET['search_sale'];

// start storing data

$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => 0,
    "iTotalDisplayRecords" => 0,
    "aaData" => array()
);

$result = $couch->limit(1000)->getView('societe','list');


//print_r($result);
//exit;
$iTotal=  count($result->rows);
$output["iTotalRecords"]=$iTotal;
$output["iTotalDisplayRecords"]=$iTotal;

$prospectstatic = new Prospect($db);


foreach($result->rows AS $aRow) {
    if(!isset($aRow->value->commerciaux))
        $aRow->value->commerciaux=null;
     if(!isset($aRow->value->category))
        $aRow->value->category=null;
    unset($aRow->value->class);
    unset($aRow->value->_rev);
    unset($aRow->value->_id);
    $output["aaData"][]=$aRow->value;
    unset($aRow);
}

//$view = $cb->getView("lookup", "list_company");
//print_r($cb->getAllDocsView());
//$result = $view->getResult(array("limit"=>30,"skip"=>0,"startkey"=>""));
//$resultPages->setPageKey($page_key);
//$result = $resultPages->current();
//$result = $view->getResult();
//$resultPages = $view->getResultPaginator();
//$resultPages->setRowsPerPage(20);
//$result = $resultPages->current();
//$resultPages->next();
//$result = $resultPages->current();
//$resultPages->next();
//$pageKey = $resultPages->key();
//print_r($pageKey);
//$resultPages->setPageKey($pageKey);
//$result = $resultPages->current();
//print $result->rows[2]->key;exit;
//foreach($result->rows AS $row) {
  //      print $row->key. "  ". $row->value->cp. "  ". $row->value->rowid."<br>";
//}


header('Content-type: application/json');
echo json_encode($output);
?>