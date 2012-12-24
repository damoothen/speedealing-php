<?php

/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2012      Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2012 Herve Prot           <dmoothen@websitti.fr>
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

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

/**
 * 	Class to describe module customer orders
 */
class modCommande extends DolibarrModules {

    /**
     *   Constructor. Define names, constants, directories, boxes, permissions
     *
     *   @param      DoliDB		$db      Database handler
     */
    function __construct($db) {
        global $conf;

        parent::__construct($db);
        $this->numero = 25;

        $this->family = "crm";
        // Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Gestion des commandes clients";
        // Possible values for version are: 'development', 'experimental', 'dolibarr' or version
        $this->version = 'speedealing';

        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->special = 0;
        $this->picto = 'order';

        // Data directories to create when module is enabled
        $this->dirs = array("/commande/temp");

        // Config pages
        $this->config_page_url = array("commande.php@commande");

        // Dependancies
        $this->depends = array("modSociete");
        $this->requiredby = array("modExpedition");
        $this->conflictwith = array();
        $this->langfiles = array('orders', 'bills', 'companies', 'products', 'deliveries');

        // Constantes
        $this->const = array();
        $r = 0;

        $this->const[$r][0] = "COMMANDE_ADDON_PDF";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "einstein";
        $this->const[$r][3] = 'Nom du gestionnaire de generation des commandes en PDF';
        $this->const[$r][4] = 0;

        $r++;
        $this->const[$r][0] = "COMMANDE_ADDON";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "mod_commande_marbre";
        $this->const[$r][3] = 'Nom du gestionnaire de numerotation des commandes';
        $this->const[$r][4] = 0;

        $r++;
        $this->const[$r][0] = "COMMANDE_ADDON_PDF_ODT_PATH";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "DOL_DATA_ROOT/doctemplates/orders";
        $this->const[$r][3] = "";
        $this->const[$r][4] = 0;

        // Boites
        $this->boxes = array();
        $this->boxes[0][1] = "box_commandes.php";

        // Permissions
        $this->rights = array();
        $this->rights_class = 'commande';

        $r = 0;
        $this->rights[$r]->id = 81;
        $this->rights[$r]->desc = 'Lire les commandes clients';
        $this->rights[$r]->default = 1;
        $this->rights[$r]->perm = array('lire');

        $r++;
        $this->rights[$r]->id = 82;
        $this->rights[$r]->desc = 'Creer/modifier les commandes clients';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('creer');

        $r++;
        $this->rights[$r]->id = 84;
        $this->rights[$r]->desc = 'Valider les commandes clients';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('valider');

        $r++;
        $this->rights[$r]->id = 86;
        $this->rights[$r]->desc = 'Envoyer les commandes clients';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('order_advance', 'send');

        $r++;
        $this->rights[$r]->id = 87;
        $this->rights[$r]->desc = 'Cloturer les commandes clients';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('cloturer');

        $r++;
        $this->rights[$r]->id = 88;
        $this->rights[$r]->desc = 'Annuler les commandes clients';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('annuler');

        $r++;
        $this->rights[$r]->id = 89;
        $this->rights[$r]->desc = 'Supprimer les commandes clients';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('supprimer');

        $r++;
        $this->rights[$r]->id = 1421;
        $this->rights[$r]->desc = 'Exporter les commandes clients et attributs';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('commande', 'export');

        // Main menu entries
        $this->menu = array();   // List of menus to add
        $r = 0;

        $this->menus[$r]->_id = "menu:commandes";
        $this->menus[$r]->type = "top";
        $this->menus[$r]->position = 41;
        $this->menus[$r]->langs = "commande";
        $this->menus[$r]->perms = '$user->rights->commande->lire';
        $this->menus[$r]->enabled = '$conf->commande->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "Orders";
        $r++;

        $this->menus[$r]->_id = "menu:newcommande";
        $this->menus[$r]->position = 0;
        $this->menus[$r]->url = "/commande/fiche.php?action=create";
        $this->menus[$r]->langs = "commande";
        $this->menus[$r]->perms = '$user->rights->commande->creer';
        $this->menus[$r]->enabled = '$conf->commande->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "NewOrder";
        $this->menus[$r]->fk_menu = "menu:commandes";
        $r++;

        $this->menus[$r]->_id = "menu:orderslist";
        $this->menus[$r]->position = 1;
        $this->menus[$r]->url = "/commande/list.php";
        $this->menus[$r]->langs = "commande";
        $this->menus[$r]->perms = '$user->rights->commande->lire';
        $this->menus[$r]->enabled = '$conf->commande->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "List";
        $this->menus[$r]->fk_menu = "menu:commandes";
        $r++;

        $this->menus[$r]->_id = "menu:ordersstats";
        $this->menus[$r]->position = 2;
        $this->menus[$r]->url = "/commande/stats/index.php";
        $this->menus[$r]->langs = "commande";
        $this->menus[$r]->perms = '$user->rights->commande->lire';
        $this->menus[$r]->enabled = '$conf->commande->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "OrdersStatistics";
        $this->menus[$r]->fk_menu = "menu:commandes";
        $r++;

        // Exports
        //--------
//		$r=0;
//
//		$r++;
//		$this->export_code[$r]=$this->rights_class.'_'.$r;
//		$this->export_label[$r]='CustomersOrdersAndOrdersLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
//		$this->export_permission[$r]=array(array("commande","commande","export"));
//		$this->export_fields_array[$r]=array('s.rowid'=>"IdCompany",'s.nom'=>'CompanyName','s.address'=>'Address','s.cp'=>'Zip','s.ville'=>'Town','s.fk_pays'=>'Country','s.tel'=>'Phone','s.siren'=>'ProfId1','s.siret'=>'ProfId2','s.ape'=>'ProfId3','s.idprof4'=>'ProfId4','c.rowid'=>"Id",'c.ref'=>"Ref",'c.ref_client'=>"RefCustomer",'c.fk_soc'=>"IdCompany",'c.date_creation'=>"DateCreation",'c.date_commande'=>"OrderDate",'c.amount_ht'=>"Amount",'c.remise_percent'=>"GlobalDiscount",'c.total_ht'=>"TotalHT",'c.total_ttc'=>"TotalTTC",'c.facture'=>"Billed",'c.fk_statut'=>'Status','c.note'=>"Note",'c.date_livraison'=>'DeliveryDate','cd.rowid'=>'LineId','cd.label'=>"Label",'cd.description'=>"LineDescription",'cd.product_type'=>'TypeOfLineServiceOrProduct','cd.tva_tx'=>"LineVATRate",'cd.qty'=>"LineQty",'cd.total_ht'=>"LineTotalHT",'cd.total_tva'=>"LineTotalVAT",'cd.total_ttc'=>"LineTotalTTC",'p.rowid'=>'ProductId','p.ref'=>'ProductRef','p.label'=>'ProductLabel');
//		$this->export_entities_array[$r]=array('s.rowid'=>"company",'s.nom'=>'company','s.address'=>'company','s.cp'=>'company','s.ville'=>'company','s.fk_pays'=>'company','s.tel'=>'company','s.siren'=>'company','s.ape'=>'company','s.idprof4'=>'company','s.siret'=>'company','c.rowid'=>"order",'c.ref'=>"order",'c.ref_client'=>"order",'c.fk_soc'=>"order",'c.date_creation'=>"order",'c.date_commande'=>"order",'c.amount_ht'=>"order",'c.remise_percent'=>"order",'c.total_ht'=>"order",'c.total_ttc'=>"order",'c.facture'=>"order",'c.fk_statut'=>"order",'c.note'=>"order",'c.date_livraison'=>"order",'cd.rowid'=>'order_line','cd.label'=>"order_line",'cd.description'=>"order_line",'cd.product_type'=>'order_line','cd.tva_tx'=>"order_line",'cd.qty'=>"order_line",'cd.total_ht'=>"order_line",'cd.total_tva'=>"order_line",'cd.total_ttc'=>"order_line",'p.rowid'=>'product','p.ref'=>'product','p.label'=>'product');
//		$this->export_dependencies_array[$r]=array('order_line'=>'cd.rowid','product'=>'cd.rowid'); // To add unique key if we ask a field of a child to avoid the DISTINCT to discard them
//
//		$this->export_sql_start[$r]='SELECT DISTINCT ';
//		$this->export_sql_end[$r]  =' FROM ('.MAIN_DB_PREFIX.'commande as c, '.MAIN_DB_PREFIX.'societe as s, '.MAIN_DB_PREFIX.'commandedet as cd)';
//		$this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'product as p on (cd.fk_product = p.rowid)';
//		$this->export_sql_end[$r] .=' WHERE c.fk_soc = s.rowid AND c.rowid = cd.fk_commande';
//		$this->export_sql_end[$r] .=' AND c.entity = '.$conf->entity;
    }

    /**
     * 		Function called when module is enabled.
     * 		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     * 		It also creates data directories
     *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
     *      @return     int             	1 if OK, 0 if KO
     */
    function init($options = '') {
        global $conf, $langs;

        // Permissions
        $this->remove($options);

        //ODT template
        $src = DOL_DOCUMENT_ROOT . '/install/doctemplates/orders/template_order.odt';
        $dirodt = DOL_DATA_ROOT . '/doctemplates/orders';
        $dest = $dirodt . '/template_order.odt';

        if (file_exists($src) && !file_exists($dest)) {
            require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
            dol_mkdir($dirodt);
            $result = dol_copy($src, $dest, 0, 0);
            if ($result < 0) {
                $langs->load("errors");
                $this->error = $langs->trans('ErrorFailToCopyFile', $src, $dest);
                return 0;
            }
        }

        $sql = array(
            "DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = '" . $this->const[0][2] . "' AND entity = " . $conf->entity,
            "INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('" . $this->const[0][2] . "','order'," . $conf->entity . ")"
        );

        return $this->_init($sql, $options);
    }

    /**
     * 		Function called when module is disabled.
     *      Remove from database constants, boxes and permissions from Dolibarr database.
     * 		Data directories are not deleted
     *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
     *      @return     int             	1 if OK, 0 if KO
     */
    function remove($options = '') {
        $sql = array();

        return $this->_remove($sql, $options);
    }

}

?>
