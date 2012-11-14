<?php

/* Copyright (C) 2012      Patrick Mary             <laube@hotmail.fr>
 * Copyright (C) 2012      Herve Prot               <herve.prot@symeos.com>
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

require_once "../../../main.inc.php";
require_once DOL_DOCUMENT_ROOT . "/core/class/html.formother.class.php";

$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

$couchdb = clone $couch;

$flush = 0;
if ($flush) {
    // reset old value
    $result = $couchdb->limit(50000)->getView('Contact', 'target_id');
    $i = 0;

    if (count($result->rows) == 0) {
        print "Effacement terminé";
        exit;
    }

    foreach ($result->rows AS $aRow) {
        $obj[$i]->_id = $aRow->value->_id;
        $obj[$i]->_rev = $aRow->value->_rev;
        $i++;
    }

    try {
        $couchdb->deleteDocs($obj);
    } catch (Exception $e) {
        echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
        exit(1);
    }

    print "Effacement en cours";
    exit;
}

$objsoc = New Societe($db);

$result = $objsoc->getView("list");
foreach ($result->rows as $aRow) {
    $soc[$aRow->value->rowid] = $aRow->value;
}

/* basic companies request query */
$sql = "SELECT s.*,";
$sql.= " p.code, u1.login as user_creat, u2.login as user_modif";
/* looking for categories ? */
$roc = stristr($sOrder, 'c.label');
$rsc = stristr($sWhere, 'c.label');
if ($roc != false || $rsc != false) {
    $sql.=",c.label";
}
/* looking for sales ? */
$rou = stristr($sOrder, 'u.login');
$rsu = stristr($sWhere, 'u.login');
if ($rou != false || $rsu != false) {
    $sql.=",u.login";
}
$sql .= " FROM (" . MAIN_DB_PREFIX . "socpeople as s";
$sql.= " ) ";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_pays as p on (p.rowid = s.fk_pays)";
$sql.= " LEFT JOIN llx_user AS u1 ON u1.rowid = s.fk_user_creat";
$sql.= " LEFT JOIN llx_user AS u2 ON u2.rowid = s.fk_user_modif";

if ($type != '')
    $sql.= " AND st.type=" . $type;
$sql.= " AND s.entity = " . $conf->entity;

/* get the total of entries */
$resultTotal = $db->query($sql);
$iTotal = $db->num_rows($resultTotal);
//print $sql;exit;

$resultContacts = $db->query($sql);

//$cb = new couchClient("http://193.169.46.49:5984/","dolibarr");
//$cb = new Couchbase;
//$cb->default_bucket_name="dolibarr";
//$cb->addCouchbaseServer("localhost",11211,8092);
//$cb->flush();
//$uuid=$cb->uuid($iTotal); //generation des uuids

/* get companies. usefull to get their sales and categories */

$i = 0;

while ($aRow = $db->fetch_object($resultContacts)) {
    $col[$aRow->rowid]->rowid = (int) $aRow->rowid;
    $col[$aRow->rowid]->class = "Contact";
    $col[$aRow->rowid]->entity = $conf->Couchdb->name;
    $col[$aRow->rowid]->firtname = $aRow->firstname;
    $col[$aRow->rowid]->lastname = $aRow->lastname;
    if(empty($aRow->lastname) && empty($aRow->firtname))
        $col[$aRow->rowid]->name = "Unknown";
    else
        $col[$aRow->rowid]->name = $aRow->firstname . " " . $aRow->lastname;
    $col[$aRow->rowid]->town = $aRow->ville;
    $col[$aRow->rowid]->datec = $db->jdate($aRow->datec);
    $col[$aRow->rowid]->zip = $aRow->cp;
    $col[$aRow->rowid]->tms = $db->jdate($aRow->tms);
    $col[$aRow->rowid]->birthday = $db->jdate($aRow->birthday);
    $col[$aRow->rowid]->address = $aRow->address;
    $col[$aRow->rowid]->civilite = $aRow->civilite;
    $col[$aRow->rowid]->state_id = $aRow->fk_departement;
    $col[$aRow->rowid]->country_id = $aRow->code; // FR
    $col[$aRow->rowid]->poste = $aRow->poste;
    $col[$aRow->rowid]->phone = $aRow->phone;
    $col[$aRow->rowid]->phone_perso = $aRow->phone_perso;
    $col[$aRow->rowid]->phone_mobile = $aRow->phone_mobile;
    $col[$aRow->rowid]->fax = $aRow->fax;
    $col[$aRow->rowid]->email = $aRow->email;
    $col[$aRow->rowid]->notes = $aRow->note;
    $col[$aRow->rowid]->Status = "ST_NEVER";
    $col[$aRow->rowid]->user_creat = $aRow->user_creat;
    $col[$aRow->rowid]->user_modif = $aRow->user_modif;
    $col[$aRow->rowid]->default_lang = $aRow->default_lang;
    
    $col[$aRow->rowid]->newsletter = (bool) !$aRow->newsletter;
    
    if(isset($soc[$aRow->fk_soc]->rowid)) {
        $col[$aRow->rowid]->societe->id = $soc[$aRow->fk_soc]->_id;
        $col[$aRow->rowid]->societe->name = $soc[$aRow->fk_soc]->name;
    }

    $i++;
}

$db->free($resultContacts);
unset($resultContacts);

//print_r($col);
//exit;

/* sql query get categories */
$sql = " SELECT fk_contact,label FROM (llx_categorie_contact as cs,llx_categorie as c) 
where "/* cs.fk_societe in ($companies) and */ . "cs.fk_categorie=c.rowid";
//$sql .= " LIMIT 100";
$resultCate = $db->query($sql);


/* init society categories array */
while ($aRow = $db->fetch_object($resultCate)) {

    if (!empty($col[$aRow->fk_contact]->rowid)) {
        $col[$aRow->fk_contact]->Tag[] = $aRow->label;
    }
}
$db->free($resultCate);
unset($resultCate);

//print_r($col);exit;

try {
    $couchdb->clean($col);
    $result = $couchdb->storeDocs($col, false);
} catch (Exception $e) {
    echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
    exit(1);
}

print_r($result);

print "Import société terminée : " . count($col);
?>