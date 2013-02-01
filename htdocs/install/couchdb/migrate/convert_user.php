<?php

/* Copyright (C) 2012      Patrick Mary           <laube@hotmail.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
require_once("../../../main.inc.php");
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

$couchdb = clone $couch;
$couchdbuser = clone $couch;
$couchdbuser->useDatabase("_users");

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

$entity = $conf->Couchdb->name;

$flush = $_GET["flush"];
if ($flush) {

    // reset old value
    //$couchdb->setQueryParameters(array("key" => $entity));
    $result = $couchdb->limit(50000)->getView('User', 'target_id');

    $couchdbuser->setQueryParameters(array("key" => $entity));
    $result2 = $couchdbuser->limit(50000)->getView('UserAdmin', 'target_id');

    $i = 0;

    if (count($result->rows) == 0 && count($result2->rows) == 0) {
        print "Effacement terminé";
        exit;
    }

    foreach ($result->rows AS $aRow) {
        $obj[$i]->_id = $aRow->value->_id;
        $obj[$i]->_rev = $aRow->value->_rev;
        $i++;
    }

    $i = 0;
    foreach ($result2->rows AS $aRow) {
        $obj2[$i]->_id = $aRow->value->_id;
        $obj2[$i]->_rev = $aRow->value->_rev;
        $i++;
    }

    try {
        if (count($obj))
            $couchdb->deleteDocs($obj);
        if (count($obj2))
            $couchdbuser->deleteDocs($obj2);
    } catch (Exception $e) {
        echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
        exit(1);
    }

    print "Effacement en cours";
    exit;
}



/* basic companies request query */
$sql = "SELECT u.*";
$sql .= " FROM " . MAIN_DB_PREFIX . "user as u";


/* get the total of entries */
$resultTotal = $db->query($sql);
$iTotal = $db->num_rows($resultTotal);

$sql.= $sWhere;
/* usefull to regroup by the sale needed */
if ($search_sale || $_GET['sSearch_7'] != "") {
    $sql.= " GROUP BY s.rowid";
}
$sql.= $sOrder;
$sql.= $sLimit;
$result = $db->query($sql);

//print $sql;

$couchAdmin = new couchAdmin($couchdb);
$object = new User($db);

$i = 0;

while ($aRow = $db->fetch_object($result)) {

    $aRow->login = strtolower(dol_delaccents($aRow->login)); // supprime les accents

    try {
        $object->load("user:" . $aRow->login);
        $col[$aRow->rowid]->_rev = $object->_rev;
    } catch (Exception $e) {
        print $e->getMessage();
        print " <b>Not exist</b><br>";
    }
    // Verification email sinon construction automatique
    if (empty($aRow->email)) {
        $aRow->email = $aRow->login . '@' . $entity . '.fr';
        if ($aRow->login == "admin")
            $aRow->email = "admin@speedealing.com";
    }

    try {
        if ($user->superadmin) {
            $colAdmin[$aRow->rowid] = $couchAdmin->getUser($aRow->email);
            $exist = true;
        }
    } catch (Exception $e) {
        $couchAdmin->createUser($aRow->email, $aRow->pass);
        $colAdmin[$aRow->rowid] = $couchAdmin->getUser($aRow->email);
        $exist = false;
    }

    $col[$aRow->rowid]->_id = "user:" . $aRow->login;
    $col[$aRow->rowid]->name = $aRow->login;
    $col[$aRow->rowid]->tms = $db->jdate($aRow->tms);
    $col[$aRow->rowid]->Lastname = $aRow->name;
    $col[$aRow->rowid]->Firstname = $aRow->firstname;
    $col[$aRow->rowid]->PhonePro = $aRow->office_phone;
    $col[$aRow->rowid]->Fax = $aRow->office_fax;
    $col[$aRow->rowid]->PhoneMobile = $aRow->user_mobile;
    $col[$aRow->rowid]->email = $aRow->email;
    $col[$aRow->rowid]->rowid = $aRow->rowid;
    $col[$aRow->rowid]->signature = $aRow->signature;
    $col[$aRow->rowid]->Status = (bool) $aRow->statut;
    if ($col[$aRow->rowid]->Status)
        $col[$aRow->rowid]->Status = "ENABLE";
    else
        $col[$aRow->rowid]->Status = "DISABLE";

    $col[$aRow->rowid]->Lang = $aRow->lang;
    $col[$aRow->rowid]->class = "User";

    $col[$aRow->rowid]->group = array();

    if ($exist) {
        if (!in_array($entity, $colAdmin[$aRow->rowid]->entityList)) // Not already in the list
            $colAdmin[$aRow->rowid]->entityList[] = $entity;
    } else {
        $colAdmin[$aRow->rowid]->Status = $col[$aRow->rowid]->Status;
        $colAdmin[$aRow->rowid]->Lastname = $aRow->name;
        $colAdmin[$aRow->rowid]->Firstname = $aRow->firstname;
        $colAdmin[$aRow->rowid]->entity = $entity;
        $colAdmin[$aRow->rowid]->CreateDate = dol_now();
        $colAdmin[$aRow->rowid]->entityList[] = $entity;
    }

    if ($aRow->email != "admin@speedealing.com") { // Already a super admin
        if ($aRow->admin)
            $couchAdmin->addDatabaseAdminUser($aRow->email);
        else
            $couchAdmin->addDatabaseReaderUser($aRow->email); // Add database reader role for user
    }

    //print_r($col[$aRow->rowid]);
    //exit;

    $i++;
}

//print_r($colAdmin);

$db->free($result);
unset($result);

/* sql query get groups */
$sql = "SELECT g.rowid, g.nom, ug.fk_user";
$sql.= " FROM " . MAIN_DB_PREFIX . "usergroup as g,";
$sql.= " " . MAIN_DB_PREFIX . "usergroup_user as ug";
$sql.= " WHERE ug.fk_usergroup = g.rowid";
$result = $db->query($sql);

/* create group  */
while ($aRow = $db->fetch_object($result)) {
    if (!empty($col[$aRow->fk_user]->rowid)) {
        $group[$aRow->rowid]->_id = 'group:' . strtolower($aRow->nom);
        $group[$aRow->rowid]->name = strtolower($aRow->nom);
        $group[$aRow->rowid]->rowid = $aRow->rowid;
        $group[$aRow->rowid]->class = "UserGroup";
        $group[$aRow->rowid]->right = new stdClass();

        $col[$aRow->fk_user]->group[] = strtolower($aRow->nom);
        //$couchAdmin->addRoleToUser($col[$aRow->fk_user]->name, $aRow->nom);
    }
}
$db->free($result);
unset($result);

// Get right
$sql = "SELECT *";
$sql.= " FROM " . MAIN_DB_PREFIX . "user_rights";
$result = $db->query($sql);

/* user right  */
while ($aRow = $db->fetch_object($result)) {
    if (!empty($col[$aRow->fk_user]->rowid)) {
        $id = $aRow->fk_id;
        $col[$aRow->fk_user]->own_rights->$id = true;
    }
}
$db->free($result);
unset($result);

// Get right
$sql = "SELECT *";
$sql.= " FROM " . MAIN_DB_PREFIX . "usergroup_rights";
$result = $db->query($sql);

/* group right  */
while ($aRow = $db->fetch_object($result)) {
    if (!empty($group[$aRow->fk_usergroup]->rowid)) {
        $id = $aRow->fk_id;
        $group[$aRow->fk_usergroup]->rights->$id = true;
    }
}
$db->free($result);
unset($result);

//print_r($colAdmin);exit;

try {
    $result = $couchdb->storeDocs($col, false);
    $result = $couchdb->storeDocs($group, false);
    $result = $couchdbuser->storeDocs($colAdmin, false);
} catch (Exception $e) {
    echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
    exit(1);
}

print_r($result);

print "Import user terminée : " . count($col);
?>