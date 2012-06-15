<?php

/* Copyright (C) 2003		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011	Regis Houssin			<regis@dolibarr.fr>
 * Copyright (C) 2012		Herve Prot				<herve.prot@symeos.com>
 * 
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
 * 		\defgroup   fournisseur     Module suppliers
 * 		\brief      Module pour gerer des societes et contacts de type fournisseurs
 * 		\file       htdocs/core/modules/modFournisseur.class.php
 * 		\ingroup    fournisseur
 * 		\brief      Fichier de description et activation du module Fournisseur
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	Classe de description et activation du module Fournisseur
 */
class modFournisseur extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modFournisseur($db) {
		global $conf;

		parent::__construct($db);
		$this->values->numero = 40;

		$this->values->family = "products";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des fournisseurs";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'company';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/fournisseur/temp",
			"/fournisseur/commande",
			"/fournisseur/commande/temp",
			"/fournisseur/facture",
			"/fournisseur/facture/temp"
		);

		// Dependances
		$this->values->depends = array("modSociete");
		$this->values->requiredby = array();
		$this->values->langfiles = array("bills", "companies", "suppliers");

		// Config pages
		$this->values->config_page_url = array("fournisseur.php");

		// Constantes
		$this->values->const = array();
		$r = 0;

		$this->values->const[$r][0] = "COMMANDE_SUPPLIER_ADDON_PDF";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "muscadet";
		$r++;

		$this->values->const[$r][0] = "COMMANDE_SUPPLIER_ADDON";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "mod_commande_fournisseur_muguet";
		$r++;

		$this->values->const[$r][0] = "INVOICE_SUPPLIER_ADDON_PDF";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "canelle";
		$r++;

		// Boxes
		$this->values->boxes = array();
		$r = 0;

		$this->values->boxes[$r][1] = "box_fournisseurs.php";
		$r++;

		$this->values->boxes[$r][1] = "box_factures_fourn_imp.php";
		$r++;

		$this->values->boxes[$r][1] = "box_factures_fourn.php";
		$r++;

		$this->boxes[$r][1] = "box_supplier_orders.php";
        $r++;

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'fournisseur';
		$r = 0;

		$r++;
		$this->values->rights[$r][0] = 1181;
		$this->values->rights[$r][1] = 'Consulter les fournisseurs';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 1182;
		$this->values->rights[$r][1] = 'Consulter les commandes fournisseur';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 1183;
		$this->values->rights[$r][1] = 'Creer une commande fournisseur';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'creer';

		$r++;
		$this->values->rights[$r][0] = 1184;
		$this->values->rights[$r][1] = 'Valider une commande fournisseur';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'valider';

		$r++;
		$this->values->rights[$r][0] = 1185;
		$this->values->rights[$r][1] = 'Approuver une commande fournisseur';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'approuver';

		$r++;
		$this->values->rights[$r][0] = 1186;
		$this->values->rights[$r][1] = 'Commander une commande fournisseur';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'commander';

		$r++;
		$this->values->rights[$r][0] = 1187;
		$this->values->rights[$r][1] = 'Receptionner une commande fournisseur';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'receptionner';

		$r++;
		$this->values->rights[$r][0] = 1188;
		$this->values->rights[$r][1] = 'Supprimer une commande fournisseur';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'supprimer';

		$r++;
		$this->values->rights[$r][0] = 1231;
		$this->values->rights[$r][1] = 'Consulter les factures fournisseur';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'facture';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 1232;
		$this->values->rights[$r][1] = 'Creer une facture fournisseur';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'facture';
		$this->values->rights[$r][5] = 'creer';

		$r++;
		$this->values->rights[$r][0] = 1233;
		$this->values->rights[$r][1] = 'Valider une facture fournisseur';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'facture';
		$this->values->rights[$r][5] = 'valider';

		$r++;
		$this->values->rights[$r][0] = 1234;
		$this->values->rights[$r][1] = 'Supprimer une facture fournisseur';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'facture';
		$this->values->rights[$r][5] = 'supprimer';

		$r++;
		$this->values->rights[$r][0] = 1235;
		$this->values->rights[$r][1] = 'Envoyer les factures par mail';
		$this->values->rights[$r][2] = 'a';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'supplier_invoice_advance';
		$this->values->rights[$r][5] = 'send';

		$r++;
		$this->values->rights[$r][0] = 1236;
		$this->values->rights[$r][1] = 'Exporter les factures fournisseurs, attributs et reglements';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'facture';
		$this->values->rights[$r][5] = 'export';

		$r++;
		$this->values->rights[$r][0] = 1237;
		$this->values->rights[$r][1] = 'Exporter les commande fournisseurs, attributs';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'commande';
		$this->values->rights[$r][5] = 'export';

		// Menus
		//--------
		$r = 0;
		$this->values->menus[$r]->_id = "menu:listsuppliersshort";
		$this->values->menus[$r]->position = 5;
		$this->values->menus[$r]->url = "/fourn/liste.php";
		$this->values->menus[$r]->langs = "suppliers";
		$this->values->menus[$r]->perms = '$user->rights->societe->lire && $user->rights->fournisseur->lire';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled && $conf->fournisseur->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "ListSuppliersShort";
		$this->values->menus[$r]->fk_menu = "menu:thirdparty";

		// Exports
		//--------
		$r = 0;

		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'Factures fournisseurs et lignes de facture';
		$this->values->export_icon[$r] = 'bill';
		$this->values->export_permission[$r] = array(array("fournisseur", "facture", "export"));
		$this->values->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.idprof5' => 'ProfId5', 's.idprof6' => 'ProfId6', 's.tva_intra' => 'VATIntra', 'f.rowid' => "InvoiceId", 'f.facnumber' => "InvoiceRef", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.total_ht' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.total_tva' => "TotalVAT", 'f.paye' => "InvoicePaid", 'f.fk_statut' => 'InvoiceStatus', 'f.note' => "InvoiceNote", 'fd.rowid' => 'LineId', 'fd.description' => "LineDescription", 'fd.tva_tx' => "LineVATRate", 'fd.qty' => "LineQty", 'fd.total_ht' => "LineTotalHT", 'fd.total_ttc' => "LineTotalTTC", 'fd.tva' => "LineTotalVAT", 'fd.product_type' => 'TypeOfLineServiceOrProduct', 'fd.fk_product' => 'ProductId', 'p.ref' => 'ProductRef', 'p.label' => 'ProductLabel');
		$this->values->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.idprof5' => 'company', 's.idprof6' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "invoice", 'f.facnumber' => "invoice", 'f.datec' => "invoice", 'f.datef' => "invoice", 'f.total_ht' => "invoice", 'f.total_ttc' => "invoice", 'f.total_tva' => "invoice", 'f.paye' => "invoice", 'f.fk_statut' => 'invoice', 'f.note' => "invoice", 'fd.rowid' => 'invoice_line', 'fd.description' => "invoice_line", 'fd.tva_tx' => "invoice_line", 'fd.qty' => "invoice_line", 'fd.total_ht' => "invoice_line", 'fd.total_ttc' => "invoice_line", 'fd.tva' => "invoice_line", 'fd.product_type' => 'invoice_line', 'fd.fk_product' => 'product', 'p.ref' => 'product', 'p.label' => 'product');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c ON s.fk_pays = c.rowid,';
		$this->values->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facture_fourn as f, ' . MAIN_DB_PREFIX . 'facture_fourn_det as fd';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p on (fd.fk_product = p.rowid)';
		$this->values->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_facture_fourn';
		$this->values->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;

		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'Factures fournisseurs et reglements';
		$this->values->export_icon[$r] = 'bill';
		$this->values->export_permission[$r] = array(array("fournisseur", "facture", "export"));
		$this->values->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.idprof5' => 'ProfId5', 's.idprof6' => 'ProfId6', 's.tva_intra' => 'VATIntra', 'f.rowid' => "InvoiceId", 'f.facnumber' => "InvoiceRef", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.total_ht' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.total_tva' => "TotalVAT", 'f.paye' => "InvoicePaid", 'f.fk_statut' => 'InvoiceStatus', 'f.note' => "InvoiceNote", 'p.rowid' => 'PaymentId', 'pf.amount' => 'AmountPayment', 'p.datep' => 'DatePayment', 'p.num_paiement' => 'PaymentNumber');
		$this->values->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.idprof5' => 'company', 's.idprof6' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "invoice", 'f.facnumber' => "invoice", 'f.datec' => "invoice", 'f.datef' => "invoice", 'f.total_ht' => "invoice", 'f.total_ttc' => "invoice", 'f.total_tva' => "invoice", 'f.paye' => "invoice", 'f.fk_statut' => 'invoice', 'f.note' => "invoice", 'p.rowid' => 'payment', 'pf.amount' => 'payment', 'p.datep' => 'payment', 'p.num_paiement' => 'payment');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c ON s.fk_pays = c.rowid,';
		$this->values->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facture_fourn as f';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiementfourn_facturefourn as pf ON pf.fk_facturefourn = f.rowid';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiementfourn as p ON pf.fk_paiementfourn = p.rowid';
		$this->values->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid';
		$this->values->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;

		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'Commandes fournisseurs et lignes de commandes';
		$this->values->export_icon[$r] = 'order';
		$this->values->export_permission[$r] = array(array("fournisseur", "commande", "export"));
		$this->values->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.idprof5' => 'ProfId5', 's.idprof6' => 'ProfId6', 's.tva_intra' => 'VATIntra', 'f.rowid' => "OrderId", 'f.ref' => "Ref", 'f.ref_supplier' => "RefSupplier", 'f.date_creation' => "DateCreation", 'f.date_commande' => "OrderDate", 'f.total_ht' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.tva' => "TotalVAT", 'f.fk_statut' => 'Status', 'f.note' => "Note", 'fd.rowid' => 'LineId', 'fd.description' => "LineDescription", 'fd.tva_tx' => "LineVATRate", 'fd.qty' => "LineQty", 'fd.total_ht' => "LineTotalHT", 'fd.total_ttc' => "LineTotalTTC", 'fd.total_tva' => "LineTotalVAT", 'fd.product_type' => 'TypeOfLineServiceOrProduct', 'fd.fk_product' => 'ProductId', 'p.ref' => 'ProductRef', 'p.label' => 'ProductLabel');
		$this->values->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.idprof5' => 'company', 's.idprof6' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "order", 'f.ref' => "order", 'f.ref_supplier' => "order", 'f.date_creation' => "order", 'f.date_commande' => "order", 'f.total_ht' => "order", 'f.total_ttc' => "order", 'f.tva' => "order", 'f.fk_statut' => 'order', 'f.note' => "order", 'fd.rowid' => 'order_line', 'fd.description' => "order_line", 'fd.tva_tx' => "order_line", 'fd.qty' => "order_line", 'fd.total_ht' => "order_line", 'fd.total_ttc' => "order_line", 'fd.total_tva' => "order_line", 'fd.product_type' => 'order_line', 'fd.fk_product' => 'product', 'p.ref' => 'product', 'p.label' => 'product');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c ON s.fk_pays = c.rowid,';
		$this->values->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'commande_fournisseur as f, ' . MAIN_DB_PREFIX . 'commande_fournisseurdet as fd';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p on (fd.fk_product = p.rowid)';
		$this->values->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_commande';
		$this->values->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;
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
		global $conf;

		$this->values->remove($options);

		$sql = array(
			"DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = '" . $this->values->const[0][2] . "' AND entity = " . $conf->entity,
			"INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('" . $this->values->const[0][2] . "','order_supplier'," . $conf->entity . ")",
		);

		return $this->values->_init($sql, $options);
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

		return $this->values->_remove($sql, $options);
	}

}
?>
