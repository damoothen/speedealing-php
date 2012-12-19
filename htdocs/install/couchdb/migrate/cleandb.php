<?php

/* Copyright (C) 2012      Patrick Mary           <laube@hotmail.fr>
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
 * 	\file       htdocs/comm/serverprocess.php
 * 	\ingroup    commercial societe
 * 	\brief      load data to display
 * 	\version    $Id: serverprocess.php,v 1.6 2012/01/27 16:15:05 synry63 Exp $
 */
require_once("../../../main.inc.php");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */


$couchdb = clone $couch;

$flush = $_GET["flush"];
if($flush)
{
    // reset old value
    //$result = $couchdb->limit(50000)->getView('unlink','link');
    $result = $couchdb->limit(50000)->getView('unlink','target_id');
    $i=0;
    
    if(count($result->rows)==0)
    {
        print "Effacement terminé";
        exit;
    }
    
    foreach ($result->rows AS $aRow)
    {
        $obj[$i]->_id=$aRow->value->_id;
        $obj[$i]->_rev=$aRow->value->_rev;
        $i++;
    }

    try {
        $couchdb->deleteDocs($obj);
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }

    print "Effacement en cours";
    exit;
}

?>