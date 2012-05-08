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
    $result = $couch->limit(50000)->getView('societe','target_id');
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
        $couch->deleteDocs($obj);
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }

    print "Effacement en cours";
    exit;
}



/*basic companies request query */
$sql = "SELECT s.*,";
$sql.= " st.code as stcomm, p.code, u1.login as user_creat, u2.login as user_modif ";
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
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_pays as p on (p.rowid = s.fk_pays)";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_stcomm as st ON st.id = s.fk_stcomm";
$sql.= " LEFT JOIN llx_user AS u1 ON u1.rowid = s.fk_user_creat";
$sql.= " LEFT JOIN llx_user AS u2 ON u2.rowid = s.fk_user_modif";

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

//$cb = new couchClient("http://193.169.46.49:5984/","dolibarr");
//$cb = new Couchbase;
//$cb->default_bucket_name="dolibarr";
//$cb->addCouchbaseServer("localhost",11211,8092);

//$cb->flush();

//$uuid=$cb->uuid($iTotal); //generation des uuids

/*get companies. usefull to get their sales and categories */

$i=0;

while ($aRow = $db->fetch_object($resultSocietes)) {
        $col[$aRow->rowid]->rowid =(int)$aRow->rowid;
        $col[$aRow->rowid]->class="company";
        $col[$aRow->rowid]->ThirdPartyName = $aRow->nom;
        $col[$aRow->rowid]->Town = $aRow->ville;
        $col[$aRow->rowid]->DateCreate = $db->jdate($aRow->datec);
        $col[$aRow->rowid]->Zip = $aRow->cp;
        $col[$aRow->rowid]->tms = $db->jdate($aRow->tms);
        $col[$aRow->rowid]->CustomerCode = $aRow->code_client;
        $col[$aRow->rowid]->SupplierCode = $aRow->code_fournisseur;
        $col[$aRow->rowid]->Accounting->CustomerCode = $aRow->code_compta;
        $col[$aRow->rowid]->Accounting->SupplierCode = $aRow->code_compta_fournisseur;
        $col[$aRow->rowid]->Address = $aRow->address;
        $col[$aRow->rowid]->State = $aRow->fk_departement;
        $col[$aRow->rowid]->Country = $aRow->code; // FR
        $col[$aRow->rowid]->AddressBook->Phone = $aRow->tel;
        $col[$aRow->rowid]->AddressBook->Fax = $aRow->fax;
        $col[$aRow->rowid]->AddressBook->EMail = $aRow->email;
        if(!empty($aRow->url))
            $col[$aRow->rowid]->url[] = $aRow->url;
        $col[$aRow->rowid]->Deal->SIREN = $aRow->siren;
        $col[$aRow->rowid]->Deal->SIRET = $aRow->siret;
        $col[$aRow->rowid]->Deal->NAF = $aRow->ape;
        $col[$aRow->rowid]->Accounting->VATIntra = $aRow->tva_intra;
        $col[$aRow->rowid]->Accounting->VATIsUsed = (bool)$aRow->tva_assuj;
        $col[$aRow->rowid]->Deal->Capital = (int)$aRow->capital;
        $col[$aRow->rowid]->Status = $aRow->stcomm;
        $col[$aRow->rowid]->Notes = $aRow->note;
        $col[$aRow->rowid]->Prefix = $aRow->prefix_comm;
        $col[$aRow->rowid]->Deal->ProspectLevelShort = (int)$aRow->fk_prospectlevel;
        $col[$aRow->rowid]->UserCreate = $aRow->user_creat;
        $col[$aRow->rowid]->UserUpdate = $aRow->user_modif;
        $col[$aRow->rowid]->Deal->CustomerRelativeDiscountShort = (int)$aRow->remise_client;
        $col[$aRow->rowid]->Gencode = $aRow->barcode;
        $col[$aRow->rowid]->DefaultLang = $aRow->default_lang;
        $col[$aRow->rowid]->Deal->PriceLevel = $aRow->price_level;
        if($aRow->latitude && $aRow->longitude)
        {
            $col[$aRow->rowid]->gps[0] = (int)$aRow->latitude;
            $col[$aRow->rowid]->gps[1] = (int)$aRow->longitude;
        }
        $col[$aRow->rowid]->Logo = $aRow->logo;
        $col[$aRow->rowid]->NoMailing = (bool)!$aRow->newsletter;
        
        $i++;
}

$db->free($resultSocietes);
unset($resultSocietes);

/* sql query get sales */
$sql = " SELECT fk_soc,login FROM (llx_societe_commerciaux as sc,llx_user as u) 
where "/*sc.fk_soc in ($companies) and*/." sc.fk_user=u.rowid";
//$sql .= " LIMIT 100";
$resultCommerciaux = $db->query($sql);

/* init society sales array  */
while ($aRow = $db->fetch_object($resultCommerciaux)) {
    if(!empty($col[$aRow->fk_soc]->rowid)){
        $col[$aRow->fk_soc]->Deal->SalesRepresentatives[]=$aRow->login;   
    }
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
    
    if(!empty($col[$aRow->fk_soc]->rowid)){
        $col[$aRow->fk_soc]->tags[]=$aRow->label;
    }
}
$db->free($resultCate);
unset($resultCate);

//print_r($col);exit;

try {
    $conf->couchdb->clean($col);
    $conf->couchdb->storeDocs($col,false);
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }
    
print "Import société terminée : ".count($col);
?>