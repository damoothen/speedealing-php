<?php

/* Copyright (C) 2003-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
 * 		\defgroup   facture     Module invoices
 *      \brief      Module pour gerer les factures clients et/ou fournisseurs
 *      \file       htdocs/core/modules/modFacture.class.php
 * 		\ingroup    facture
 * 		\brief      Fichier de la classe de description et activation du module Facture
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 *  Classe de description et activation du module Facture
 */
class modFacture extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modFacture($db) {
		global $conf;

		parent::__construct($db);
		$this->numero = 30;

		$this->family = "financial";

		$this->name = "facture";
		$this->description = "Gestion des factures";

		// Possible values for version are: 'development', 'experimental', 'speedealing' or version
		$this->version = 'speedealing';

		$this->picto = 'bill';

		// Data directories to create when module is enabled
		$this->dirs = array("/facture/temp");

		// Dependencies
		$this->depends = array("modSociete");
		$this->requiredby = array("modComptabilite", "modAccounting");
		$this->conflictwith = array();
		$this->langfiles = array("bills", "companies", "compta", "products");

		// Config pages
		$this->config_page_url = array("facture.php");

		// Constantes
		$this->const = array();
		$r = 0;

		$this->const[$r][0] = "FACTURE_ADDON_PDF";
		$this->const[$r][1] = "chaine";
		$this->const[$r][2] = "crabe";
		$r++;

		$this->const[$r][0] = "FACTURE_ADDON";
		$this->const[$r][1] = "chaine";
		$this->const[$r][2] = "terre";
		$r++;

		$this->const[$r][0] = "FACTURE_ADDON_PDF_ODT_PATH";
		$this->const[$r][1] = "chaine";
		$this->const[$r][2] = "DOL_DATA_ROOT/doctemplates/invoices";
		$this->const[$r][3] = "";
		$this->const[$r][4] = 0;
		$r++;

		// Boxes
		$this->boxes = array();
		$r = 0;
		$this->boxes[$r][1] = "box_factures_imp.php";
		$r++;
		$this->boxes[$r][1] = "box_factures.php";
		$r++;

		// Permissions
		$this->rights = array();
		$this->rights_class = 'facture';
		$r = 0;

		$this->rights[$r]->id = 11;
		$this->rights[$r]->desc = 'Lire les factures';
		$this->rights[$r]->default = 1;
		$this->rights[$r]->perm = array('lire');

		$r++;
		$this->rights[$r]->id = 12;
		$this->rights[$r]->desc = 'Creer/modifier les factures';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('creer');

		// There is a particular permission for unvalidate because this may be not forbidden by some laws
		$r++;
		$this->rights[$r]->id = 13;
		$this->rights[$r]->desc = 'Dévalider les factures';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('invoice_advance', 'unvalidate');

		$r++;
		$this->rights[$r]->id = 14;
		$this->rights[$r]->desc = 'Valider les factures';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('valider');

		$r++;
		$this->rights[$r]->id = 15;
		$this->rights[$r]->desc = 'Envoyer les factures par mail';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('invoice_advance', 'send');

		$r++;
		$this->rights[$r]->id = 16;
		$this->rights[$r]->desc = 'Emettre des paiements sur les factures';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('paiement');
		$r++;
		$this->rights[$r]->id = 19;
		$this->rights[$r]->desc = 'Supprimer les factures';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('supprimer');

		$r++;
		$this->rights[$r]->id = 1321;
		$this->rights[$r]->desc = 'Exporter les factures clients, attributs et reglements';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('facture', 'export');

		// Menus
		//-------

		$r = 0;
		$this->menus[$r]->_id = "menu:accountancy";
		$this->menus[$r]->type = "top";
		$this->menus[$r]->position = 6;
		$this->menus[$r]->url = "/compta/index.php";
		$this->menus[$r]->langs = "compta";
		$this->menus[$r]->perms = '$user->rights->compta->resultat->lire || $user->rights->accounting->plancompte->lire || $user->rights->facture->lire|| $user->rights->deplacement->lire || $user->rights->don->lire || $user->rights->tax->charges->lire';
		$this->menus[$r]->enabled = '$conf->comptabilite->enabled || $conf->accounting->enabled || $conf->facture->enabled || $conf->deplacement->enabled || $conf->don->enabled  || $conf->tax->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "MenuFinancial";
		$r++;
		$this->menus[$r]->_id = "menu:billscustomers";
		$this->menus[$r]->position = 3;
		$this->menus[$r]->url = "/compta/facture/list.php";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->lire';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "BillsCustomers";
		$this->menus[$r]->fk_menu = "menu:accountancy";
		$r++;
		$this->menus[$r]->_id = "menu:newbill0";
		$this->menus[$r]->position = 3;
		$this->menus[$r]->url = "/compta/clients.php?action=facturer";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->creer';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "NewBill";
		$this->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->menus[$r]->_id = "menu:repeatable";
		$this->menus[$r]->position = 4;
		$this->menus[$r]->url = "/compta/facture/fiche-rec.php";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->lire';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Repeatable";
		$this->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->menus[$r]->_id = "menu:unpaid0";
		$this->menus[$r]->position = 5;
		$this->menus[$r]->url = "/compta/facture/impayees.php?action=facturer";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->lire';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Unpaid";
		$this->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->menus[$r]->_id = "menu:payments0";
		$this->menus[$r]->position = 6;
		$this->menus[$r]->url = "/compta/paiement/liste.php";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->lire';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Payments";
		$this->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->menus[$r]->_id = "menu:statistics3";
		$this->menus[$r]->position = 8;
		$this->menus[$r]->url = "/compta/facture/stats/index.php";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->lire';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Statistics";
		$this->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->menus[$r]->_id = "menu:reportings";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/compta/paiement/rapport.php";
		$this->menus[$r]->langs = "bills";
		$this->menus[$r]->perms = '$user->rights->facture->lire';
		$this->menus[$r]->enabled = '$conf->facture->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Reportings";
		$this->menus[$r]->fk_menu = "menu:payments0";



		// Exports
		//--------
		$r = 0;

		$this->export_code[$r] = $this->rights_class . '_' . $r;
		$this->export_label[$r] = 'CustomersInvoicesAndInvoiceLines'; // Translation key (used only if key ExportDataset_xxx_z not found)
		$this->export_icon[$r] = 'bill';
		$this->export_permission[$r] = array(array("facture", "facture", "export"));
		$this->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.code_compta' => 'CustomerAccountancyCode', 's.code_compta_fournisseur' => 'SupplierAccountancyCode', 's.tva_intra' => 'VATIntra', 'f.rowid' => "InvoiceId", 'f.facnumber' => "InvoiceRef", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.date_lim_reglement' => "DateDue", 'f.total' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.tva' => "TotalVAT", 'f.paye' => "InvoicePaid", 'f.fk_statut' => 'InvoiceStatus', 'f.note' => "NotePrivate", 'f.note_public' => "NotePublic", 'fd.rowid' => 'LineId', 'fd.description' => "LineDescription", 'fd.price' => "LineUnitPrice", 'fd.tva_tx' => "LineVATRate", 'fd.qty' => "LineQty", 'fd.total_ht' => "LineTotalHT", 'fd.total_tva' => "LineTotalVAT", 'fd.total_ttc' => "LineTotalTTC", 'fd.date_start' => "DateStart", 'fd.date_end' => "DateEnd", 'fd.product_type' => "TypeOfLineServiceOrProduct", 'fd.fk_product' => 'ProductId', 'p.ref' => 'ProductRef', 'p.label' => 'ProductLabel');
		$this->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.code_compta' => 'company', 's.code_compta_fournisseur' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "invoice", 'f.facnumber' => "invoice", 'f.datec' => "invoice", 'f.datef' => "invoice", 'f.date_lim_reglement' => "invoice", 'f.total' => "invoice", 'f.total_ttc' => "invoice", 'f.tva' => "invoice", 'f.paye' => "invoice", 'f.fk_statut' => 'invoice', 'f.note' => "invoice", 'f.note_public' => "invoice", 'fd.rowid' => 'invoice_line', 'fd.description' => "invoice_line", 'fd.price' => "invoice_line", 'fd.total_ht' => "invoice_line", 'fd.total_tva' => "invoice_line", 'fd.total_ttc' => "invoice_line", 'fd.tva_tx' => "invoice_line", 'fd.qty' => "invoice_line", 'fd.date_start' => "invoice_line", 'fd.date_end' => "invoice_line", 'fd.product_type' => 'invoice_line', 'fd.fk_product' => 'product', 'p.ref' => 'product', 'p.label' => 'product');

		$this->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c on s.fk_pays = c.rowid,';
		$this->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facture as f,';
		$this->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facturedet as fd';
		$this->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p on (fd.fk_product = p.rowid)';
		$this->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_facture';
		$this->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;
		$r++;

		$this->export_code[$r] = $this->rights_class . '_' . $r;
		$this->export_label[$r] = 'CustomersInvoicesAndPayments'; // Translation key (used only if key ExportDataset_xxx_z not found)
		$this->export_icon[$r] = 'bill';
		$this->export_permission[$r] = array(array("facture", "facture", "export"));
		$this->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.code_compta' => 'CustomerAccountancyCode', 's.code_compta_fournisseur' => 'SupplierAccountancyCode', 's.tva_intra' => 'VATIntra', 'f.rowid' => "InvoiceId", 'f.facnumber' => "InvoiceRef", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.date_lim_reglement' => "DateDue", 'f.total' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.tva' => "TotalVAT", 'f.paye' => "InvoicePaid", 'f.fk_statut' => 'InvoiceStatus', 'f.note' => "NotePrivate", 'f.note_public' => "NotePublic", 'p.rowid' => 'PaymentId', 'pf.amount' => 'AmountPayment', 'p.datep' => 'DatePayment', 'p.num_paiement' => 'PaymentNumber');
		$this->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.code_compta' => 'company', 's.code_compta_fournisseur' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "invoice", 'f.facnumber' => "invoice", 'f.datec' => "invoice", 'f.datef' => "invoice", 'f.date_lim_reglement' => "invoice", 'f.total' => "invoice", 'f.total_ttc' => "invoice", 'f.tva' => "invoice", 'f.paye' => "invoice", 'f.fk_statut' => 'invoice', 'f.note' => "invoice", 'f.note_public' => "invoice", 'p.rowid' => 'payment', 'pf.amount' => 'payment', 'p.datep' => 'payment', 'p.num_paiement' => 'payment');

		$this->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c on s.fk_pays = c.rowid,';
		$this->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facture as f';
		$this->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiement_facture as pf ON pf.fk_facture = f.rowid';
		$this->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiement as p ON pf.fk_paiement = p.rowid';
		$this->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid';
		$this->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;
		$r++;
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

		// Remove permissions and default values
		$this->remove($options);

		require_once(DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php');
		$dirodt = DOL_DATA_ROOT . '/doctemplates/invoices';
		dol_mkdir($dirodt);
		$src = DOL_DOCUMENT_ROOT . '/install/doctemplates/invoices/template_invoice.odt';
		$dest = $dirodt . '/template_invoice.odt';
		$result = dol_copy($src, $dest, 0, 0);
		if ($result < 0) {
			$langs->load("errors");
			$this->error = $langs->trans('ErrorFailToCopyFile', $src, $dest);
			return 0;
		}

		$sql = array(
			"DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = '" . $this->const[0][2] . "' AND entity = " . $conf->entity,
			"INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('" . $this->const[0][2] . "','invoice'," . $conf->entity . ")"
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
