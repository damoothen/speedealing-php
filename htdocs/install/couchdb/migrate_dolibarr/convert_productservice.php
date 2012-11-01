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
require_once DOL_DOCUMENT_ROOT . "/comm/prospect/class/prospect.class.php";
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
    $result = $couchdb->limit(50000)->getView('Product', 'target_id');
    $i = 0;

    foreach ($result->rows AS $aRow) {
        $obj[$i]->_id = $aRow->value->_id;
        $obj[$i]->_rev = $aRow->value->_rev;
        $i++;
    }

    if (count($obj) == 0) {
        print "Effacement terminé";
        exit;
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

$sql = "SELECT p.*, c.code, u1.login as user_author ";
$sql .= " FROM (" . MAIN_DB_PREFIX . "product as p";
$sql.= " ) ";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_pays as c on (c.rowid = p.fk_country)";
$sql.= " LEFT JOIN llx_user AS u1 ON u1.rowid = p.fk_user_author";

/* get the total of entries */
$resultTotal = $db->query($sql);
$iTotal = $db->num_rows($resultTotal);

$result = $db->query($sql);

//$cb = new couchClient("http://193.169.46.49:5984/","dolibarr");
//$cb = new Couchbase;
//$cb->default_bucket_name="dolibarr";
//$cb->addCouchbaseServer("localhost",11211,8092);
//$cb->flush();
//$uuid=$cb->uuid($iTotal); //generation des uuids

/* get companies. usefull to get their sales and categories */

$i = 0;

$uuid = $couchdb->getUuids($iTotal);

$price = array();

while ($aRow = $db->fetch_object($result)) {
    $col[$aRow->rowid]->_id = $uuid[$i];
    $col[$aRow->rowid]->class = "Product";
    $col[$aRow->rowid]->name = $aRow->ref;
    $col[$aRow->rowid]->ref_ext = $aRow->ref_ext;
    $col[$aRow->rowid]->datec = $db->jdate($aRow->datec);
    $col[$aRow->rowid]->virtual = $aRow->virtual;
    if ($aRow->type == 0) {
        if ($aRow->finished == 1)
            $col[$aRow->rowid]->finished = "finished";
        else
            $col[$aRow->rowid]->finished = "rowmaterial";
    }
    $col[$aRow->rowid]->tms = $db->jdate($aRow->tms);
    $col[$aRow->rowid]->label = $aRow->label;
    $col[$aRow->rowid]->description = $aRow->description;
    $col[$aRow->rowid]->notes = $aRow->note;
    $col[$aRow->rowid]->customcode = $aRow->customcode;
    $col[$aRow->rowid]->country_id = $aRow->code; // FR
    $col[$aRow->rowid]->recuperableonly = (bool) $aRow->recuperableonly;
    $col[$aRow->rowid]->fk_user_author = $aRow->user_author;

    if ((bool) $aRow->tosell)
        $col[$aRow->rowid]->Status = "SELL";
    if ((bool) $aRow->tobuy) {
        if ((bool) $aRow->tosell)
            $col[$aRow->rowid]->Status = "SELLBUY";
        else
            $col[$aRow->rowid]->Status = "BUY";
    }
    if (empty($col[$aRow->rowid]->Status))
        $col[$aRow->rowid]->Status = "DISABLE";

    if ((int) $aRow->fk_product_type)
        $col[$aRow->rowid]->type = "SERVICE";
    else
        $col[$aRow->rowid]->type = "PRODUCT";

    $col[$aRow->rowid]->duration = $aRow->duration;
    $col[$aRow->rowid]->seuil_stock_alerte = $aRow->seuil_stock_alerte;
    $col[$aRow->rowid]->barcode = $aRow->barcode;
    $col[$aRow->rowid]->fk_barcode_type = $aRow->fk_barcode_type;


    $col[$aRow->rowid]->accountancy_code_sell = $aRow->accountancy_code_sell;
    $col[$aRow->rowid]->accountancy_code_buy = $aRow->accountancy_code_buy;

    $col[$aRow->rowid]->entity = $conf->Couchdb->name;

    $col[$aRow->rowid]->partnumber = $aRow->partnumber;
    $col[$aRow->rowid]->weight = (float) $aRow->weight;
    $col[$aRow->rowid]->weight_units = $aRow->weight_units;
    $col[$aRow->rowid]->length = (float) $aRow->length;
    $col[$aRow->rowid]->length_units = $aRow->length_units;
    $col[$aRow->rowid]->surface = (float) $aRow->surface;
    $col[$aRow->rowid]->surface_units = $aRow->surface_units;
    $col[$aRow->rowid]->volume = $aRow->volume;
    $col[$aRow->rowid]->volume_units = $aRow->volume_units;
    $col[$aRow->rowid]->stock = $aRow->stock;
    $col[$aRow->rowid]->pmp = (float) $aRow->pmp;
    $col[$aRow->rowid]->hidden = $aRow->hidden;

    $obj = new stdClass();
    $obj->tms = $db->jdate($aRow->tms);
    $obj->price = (float) $aRow->price;
    $obj->price_ttc = (float) $aRow->price_ttc;
    $obj->price_min = (float) $aRow->price_min;
    $obj->price_min_ttc = (float) $aRow->price_min_ttc;
    $obj->price_level = (int) $aRow->price_level;
    $obj->ecotax = (float) $aRow->ecotax;
    $obj->ecotax_ttc = (float) $aRow->ecotax_ttc;
    $obj->price_base_type = $aRow->price_base_type;
    $obj->tva_tx = (float) $aRow->tva_tx;
    $obj->recuperableonly = (bool) $aRow->recuperableonly;
    $obj->localtax1_tx = (float) $aRow->localtax1_tx;
    $obj->localtax2_tx = (float) $aRow->localtax2_tx;
    $obj->fk_user_author = $aRow->user_author;

    $col[$aRow->rowid]->price = clone $obj;

    $obj->price_level = "base";
    $obj->class = "Price";
    $obj->fk_product = $uuid[$i];

    $price[] = clone $obj;
    //print count($col[$aRow->rowid]->country_id);exit;

    $i++;
}

$db->free($result);
unset($result);

/* sql query get price */
$sql = " SELECT p.*, u1.login as user_author ";
$sql .= " FROM (" . MAIN_DB_PREFIX . "product_price as p";
$sql.= " ) ";
$sql.= " LEFT JOIN llx_user AS u1 ON u1.rowid = p.fk_user_author";
$sql.= " ORDER BY p.tms";


//print $sql;exit;
$result = $db->query($sql);

/* init society sales array  */
while ($aRow = $db->fetch_object($result)) {
    if (!empty($col[$aRow->fk_product]->_id)) {
        $obj = new stdClass();
        $obj->class = "Price";
        $obj->fk_product = $col[$aRow->fk_product]->_id;
        $obj->tms = $db->jdate($aRow->tms);
        $obj->price = (float) $aRow->price;
        $obj->price_ttc = (float) $aRow->price_ttc;
        $obj->price_min = (float) $aRow->price_min;
        $obj->price_min_ttc = (float) $aRow->price_min_ttc;
        $obj->price_level = (int) $aRow->price_level;
        $obj->ecotax = (float) $aRow->ecotax;
        $obj->ecotax_ttc = (float) $aRow->ecotax_ttc;
        $obj->price_base_type = $aRow->price_base_type;
        $obj->tva_tx = (float) $aRow->tva_tx;
        $obj->recuperableonly = (bool) $aRow->recuperableonly;
        $obj->localtax1_tx = (float) $aRow->localtax1_tx;
        $obj->localtax2_tx = (float) $aRow->localtax2_tx;
        $obj->fk_user_author = $aRow->user_author;

        //print_r($obj);exit;
        $obj->price_level = "level" . $aRow->price_level;

        $price[] = clone $obj;
    }
}
$db->free($result);
unset($result);

//print_r($col);exit;

/* sql query get categories */
$sql = " SELECT fk_product,label FROM (llx_categorie_product as cs,llx_categorie as c) 
where "/* cs.fk_societe in ($companies) and */ . "cs.fk_categorie=c.rowid";
//$sql .= " LIMIT 100";
//print $sql;exit;

$result = $db->query($sql);


/* init society categories array */
while ($aRow = $db->fetch_object($result)) {

    if (!empty($col[$aRow->fk_product]->_id)) {
        $col[$aRow->fk_product]->Tag[] = $aRow->label;
    }
}
$db->free($result);
unset($result);

//print_r($col);exit;

try {
    $couchdb->clean($col);
    $couchdb->clean($price);
    //print_r($col);exit;
    $result = $couchdb->storeDocs($col, false);
    $result1 = $couchdb->storeDocs($price, false);
} catch (Exception $e) {
    echo "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
    exit(1);
}

print_r($result);
print_r($result1);

print "Import société terminée : " . count($col);
?>