<?php

/* Copyright (C) 2012      Herve Prot			<herve.prot@symeos.com>
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
 */
require_once("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/menubase.class.php");;
$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

$couchdb = new couchClient("http://"."Administrator:admin@".substr($conf->couchdb->host, 7).':'.$conf->couchdb->port.'/',$conf->couchdb->name);


$flush=0;
if($flush)
{
    // reset old value
    $result = $couchdb->limit(50000)->getView('MenuTop','target_id');
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

$obj = new stdClass();
$obj->_id="const";

try{
	$obj = $couchdb->getDoc("const");
}catch(Exception $e) {}


$obj->class="system";
$obj->values=$conf->global;

//print_r($obj);
//exit;

try {
    print_r($couchdb->storeDoc($obj));
    } catch (Exception $e) {
        $error = "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
		dol_print_error("", $error);
        exit(1);
    }
?>