<?php

/* Copyright (C) 2012      Herve Prot               <herve.prot@symeos.com>
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
require_once(DOL_DOCUMENT_ROOT . "/contact/class/contact.class.php");

$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */

$couchdb = clone $couch;

$flush = $_GET["flush"];
if ($flush) {
    // reset old value
    $result = $couchdb->limit(50000)->getView('Agenda', 'target_id');
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

$objsoc = new Societe($db);

$result = $objsoc->getView("list");
foreach ($result->rows as $aRow) {
    $soc[$aRow->value->rowid] = $aRow->value;
}

$objcon = new Contact($db);

$result = $objcon->getView("list");
foreach ($result->rows as $aRow) {
    $contact[$aRow->value->rowid] = $aRow->value;
}


/* basic companies request query */
$sql = "SELECT s.*,";
$sql.= " p.code, p.libelle as codelabel, u1.login as user_author, u2.login as user_action, u3.login as user_done, u4.login as user_mod";
$sql .= " FROM (" . MAIN_DB_PREFIX . "actioncomm as s";
$sql.= " ) ";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_actioncomm as p on (p.id = s.fk_action)";
$sql.= " LEFT JOIN llx_user AS u1 ON u1.rowid = s.fk_user_author";
$sql.= " LEFT JOIN llx_user AS u2 ON u2.rowid = s.fk_user_action";
$sql.= " LEFT JOIN llx_user AS u3 ON u3.rowid = s.fk_user_done";
$sql.= " LEFT JOIN llx_user AS u4 ON u4.rowid = s.fk_user_mod";

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

while ($aRow = $db->fetch_object($resultAgenda)) {
    $aRow->rowid = $aRow->id;
    $col[$aRow->rowid]->rowid = (int) $aRow->id;
    $col[$aRow->rowid]->class = "Agenda";
    $col[$aRow->rowid]->entity = $conf->Couchdb->name;
    $col[$aRow->rowid]->datep = $db->jdate($aRow->datep);
    $col[$aRow->rowid]->datef = $db->jdate($aRow->datef);
    $col[$aRow->rowid]->type_code = $aRow->code;
    if(!empty($aRow->label))
        $col[$aRow->rowid]->label = $aRow->label;
    else
        $col[$aRow->rowid]->label = $aRow->codelabel;
    $col[$aRow->rowid]->datec = $db->jdate($aRow->datec);
    $col[$aRow->rowid]->tms = $db->jdate($aRow->tms);

    if (!empty($aRow->user_author)) {
        $col[$aRow->rowid]->author->id = "user:" . $aRow->user_author;
        $col[$aRow->rowid]->author->name = $aRow->user_author;
    }
    else
        $col[$aRow->rowid]->author = new stdClass ();

    if (!empty($aRow->user_action)) {
        $col[$aRow->rowid]->usertodo->id = "user:" . $aRow->user_action;
        $col[$aRow->rowid]->usertodo->name = $aRow->user_action;
    } else
        $col[$aRow->rowid]->usertodo = new stdClass ();

    if (!empty($aRow->user_done)) {
        $col[$aRow->rowid]->userdone->id = "user:" . $aRow->user_done;
        $col[$aRow->rowid]->userdone->name = $aRow->user_done;
    } else
        $col[$aRow->rowid]->userdone = new stdClass ();

    if (!empty($aRow->user_mod))
        $col[$aRow->rowid]->user_modif = "user:" . $aRow->user_mod;
    if (isset($soc[$aRow->fk_soc]->rowid)) {
        $col[$aRow->rowid]->societe->id = $soc[$aRow->fk_soc]->_id;
        $col[$aRow->rowid]->societe->name = $soc[$aRow->fk_soc]->name;
    } else
        $col[$aRow->rowid]->societe = new stdClass();
    if (isset($contact[$aRow->fk_contact]->rowid)) {
        $col[$aRow->rowid]->contact->id = $contact[$aRow->fk_contact]->_id;
        $col[$aRow->rowid]->contact->name = $contact[$aRow->fk_contact]->name;
    }else
        $col[$aRow->rowid]->contact = new stdClass();
    $col[$aRow->rowid]->fulldayevent = (bool) $aRow->fulldayevent;
    $col[$aRow->rowid]->punctual = (bool) $aRow->punctual;
    $col[$aRow->rowid]->durationp = (int) $aRow->durationp;
    $col[$aRow->rowid]->percentage = (int) $aRow->percent;
    $col[$aRow->rowid]->location = $aRow->location;
    $col[$aRow->rowid]->notes = $aRow->note;
    if ($aRow->percent == 0)
        $col[$aRow->rowid]->Status = "TODO";
    elseif ($aRow->percent == 100)
        $col[$aRow->rowid]->Status = "DONE";
    else
        $col[$aRow->rowid]->Status = "ON";

    $i++;
}

$db->free($resultAgenda);
unset($resultAgenda);

//print_r($col);
//exit;

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