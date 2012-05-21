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
require_once("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/comm/prospect/class/prospect.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formother.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/db/couchdb/lib/couchAdmin.php");
$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */
//print substr($conf->couchdb->host, 7);exit;

$couchdb = new couchClient("http://"."Administrator:admin@".substr($conf->couchdb->host, 7).':'.$conf->couchdb->port.'/',$conf->couchdb->name);

$couchdb->useDatabase("_users");

/*
 * Paging
 */
//$sLimit = " LIMIT 3";

/* search basic */
$sWhere = "";
// If the user must only see his prospect, force searching by him
if (!$user->rights->societe->client->voir && !$socid) {
    $search_sale = $user->id;
}

$flush=0;
if($flush)
{
    // reset old value
    $result = $couchdb->limit(50000)->getView('Societe','target_id');
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



/*basic companies request query */
$sql = "SELECT u.*";
$sql .= " FROM " . MAIN_DB_PREFIX . "user as u";


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
$result = $db->query($sql);

//print $sql;

$couchAdmin = new couchAdmin($couchdb);

$i=0;

while ($aRow = $db->fetch_object($result)) {
		
		//print_r($aRow);exit;
		$couchAdmin->createUser($aRow->login, $aRow->pass);
		
		$col[$aRow->rowid] = $couchAdmin->getUser($aRow->login);
        $col[$aRow->rowid]->tms = $db->jdate($aRow->tms);
		$col[$aRow->rowid]->Lastname = $aRow->name;
		$col[$aRow->rowid]->Firstname = $aRow->firstname;
		$col[$aRow->rowid]->Administrator = (bool)$aRow->admin;
		$col[$aRow->rowid]->PhonePro = $aRow->office_phone;
		$col[$aRow->rowid]->Fax = $aRow->office_fax;
		$col[$aRow->rowid]->PhoneMobile = $aRow->user_mobile;
		$col[$aRow->rowid]->EMail = $aRow->email;
		$col[$aRow->rowid]->Signature = $aRow->signature;
		$col[$aRow->rowid]->Status = (bool)$aRow->statut;
		$col[$aRow->rowid]->Photo = $aRow->photo;
		$col[$aRow->rowid]->Lang = $aRow->lang;
		$col[$aRow->rowid]->rowid =(int)$aRow->rowid;
		
        $i++;
}

$db->free($result);
unset($result);

/* sql query get sales */
$sql = "SELECT g.rowid, g.nom, ug.fk_user";
$sql.= " FROM ".MAIN_DB_PREFIX."usergroup as g,";
$sql.= " ".MAIN_DB_PREFIX."usergroup_user as ug";
$sql.= " WHERE ug.fk_usergroup = g.rowid";
$result = $db->query($sql);

/* init society sales array  */
while ($aRow = $db->fetch_object($result)) {
    if(!empty($col[$aRow->fk_user]->rowid)){
        $couchAdmin->addRoleToUser($col[$aRow->fk_user]->name, $aRow->nom);
    }
}
$db->free($result);
unset($result);

//print_r($col);exit;

try {
    $result = $couchdb->storeDocs($col,false);
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }
    
print_r($result);
    
print "Import société terminée : ".count($col);
?>