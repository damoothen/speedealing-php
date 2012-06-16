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
		$this->values->numero = 30;

		$this->values->family = "financial";

		$this->values->name = "facture";
		$this->values->description = "Gestion des factures";

		// Possible values for version are: 'development', 'experimental', 'speedealing' or version
		$this->values->version = 'speedealing';

		$this->values->picto = 'bill';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/facture/temp");

		// Dependencies
		$this->values->depends = array("modSociete");
		$this->values->requiredby = array("modComptabilite", "modAccounting");
		$this->values->conflictwith = array();
		$this->values->langfiles = array("bills", "companies", "compta", "products");

		// Config pages
		$this->values->config_page_url = array("facture.php");

		// Constantes
		$this->values->const = array();
		$r = 0;

		$this->values->const[$r][0] = "FACTURE_ADDON_PDF";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "crabe";
		$r++;

		$this->values->const[$r][0] = "FACTURE_ADDON";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "terre";
		$r++;

		$this->values->const[$r][0] = "FACTURE_ADDON_PDF_ODT_PATH";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "DOL_DATA_ROOT/doctemplates/invoices";
		$this->values->const[$r][3] = "";
		$this->values->const[$r][4] = 0;
		$r++;

		// Boxes
		$this->values->boxes = array();
		$r = 0;
		$this->values->boxes[$r][1] = "box_factures_imp.php";
		$r++;
		$this->values->boxes[$r][1] = "box_factures.php";
		$r++;

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'facture';
		$r = 0;

		$this->values->rights[$r]->id = 11;
		$this->values->rights[$r]->desc = 'Lire les factures';
		$this->values->rights[$r]->default = 1;
		$this->values->rights[$r]->perm = array('lire');

		$r++;
		$this->values->rights[$r]->id = 12;
		$this->values->rights[$r]->desc = 'Creer/modifier les factures';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('creer');

		// There is a particular permission for unvalidate because this may be not forbidden by some laws
		$r++;
		$this->values->rights[$r]->id = 13;
		$this->values->rights[$r]->desc = 'Dévalider les factures';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('invoice_advance', 'unvalidate');

		$r++;
		$this->values->rights[$r]->id = 14;
		$this->values->rights[$r]->desc = 'Valider les factures';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('valider');

		$r++;
		$this->values->rights[$r]->id = 15;
		$this->values->rights[$r]->desc = 'Envoyer les factures par mail';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('invoice_advance', 'send');

		$r++;
		$this->values->rights[$r]->id = 16;
		$this->values->rights[$r]->desc = 'Emettre des paiements sur les factures';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('paiement');
		$r++;
		$this->values->rights[$r]->id = 19;
		$this->values->rights[$r]->desc = 'Supprimer les factures';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('supprimer');

		$r++;
		$this->values->rights[$r]->id = 1321;
		$this->values->rights[$r]->desc = 'Exporter les factures clients, attributs et reglements';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('facture', 'export');

		// Menus
		//-------

		$r = 0;
		$this->values->menus[$r]->_id = "menu:accountancy";
		$this->values->menus[$r]->type = "top";
		$this->values->menus[$r]->position = 6;
		$this->values->menus[$r]->url = "/compta/index.php";
		$this->values->menus[$r]->langs = "compta";
		$this->values->menus[$r]->perms = '$user->rights->compta->resultat->lire || $user->rights->accounting->plancompte->lire || $user->rights->facture->lire|| $user->rights->deplacement->lire || $user->rights->don->lire || $user->rights->tax->charges->lire';
		$this->values->menus[$r]->enabled = '$conf->comptabilite->enabled || $conf->accounting->enabled || $conf->facture->enabled || $conf->deplacement->enabled || $conf->don->enabled  || $conf->tax->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "MenuFinancial";
		$r++;
		$this->values->menus[$r]->_id = "menu:billscustomers";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/compta/facture/list.php";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->lire';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "BillsCustomers";
		$this->values->menus[$r]->fk_menu = "menu:accountancy";
		$r++;
		$this->values->menus[$r]->_id = "menu:newbill0";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/compta/clients.php?action=facturer";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->creer';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "NewBill";
		$this->values->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->values->menus[$r]->_id = "menu:repeatable";
		$this->values->menus[$r]->position = 4;
		$this->values->menus[$r]->url = "/compta/facture/fiche-rec.php";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->lire';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Repeatable";
		$this->values->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->values->menus[$r]->_id = "menu:unpaid0";
		$this->values->menus[$r]->position = 5;
		$this->values->menus[$r]->url = "/compta/facture/impayees.php?action=facturer";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->lire';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Unpaid";
		$this->values->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->values->menus[$r]->_id = "menu:payments0";
		$this->values->menus[$r]->position = 6;
		$this->values->menus[$r]->url = "/compta/paiement/liste.php";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->lire';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Payments";
		$this->values->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->values->menus[$r]->_id = "menu:statistics3";
		$this->values->menus[$r]->position = 8;
		$this->values->menus[$r]->url = "/compta/facture/stats/index.php";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->lire';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Statistics";
		$this->values->menus[$r]->fk_menu = "menu:billscustomers";
		$r++;
		$this->values->menus[$r]->_id = "menu:reportings";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/compta/paiement/rapport.php";
		$this->values->menus[$r]->langs = "bills";
		$this->values->menus[$r]->perms = '$user->rights->facture->lire';
		$this->values->menus[$r]->enabled = '$conf->facture->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Reportings";
		$this->values->menus[$r]->fk_menu = "menu:payments0";



		// Exports
		//--------
		$r = 0;

		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'CustomersInvoicesAndInvoiceLines'; // Translation key (used only if key ExportDataset_xxx_z not found)
		$this->values->export_icon[$r] = 'bill';
		$this->values->export_permission[$r] = array(array("facture", "facture", "export"));
		$this->values->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.code_compta' => 'CustomerAccountancyCode', 's.code_compta_fournisseur' => 'SupplierAccountancyCode', 's.tva_intra' => 'VATIntra', 'f.rowid' => "InvoiceId", 'f.facnumber' => "InvoiceRef", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.date_lim_reglement' => "DateDue", 'f.total' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.tva' => "TotalVAT", 'f.paye' => "InvoicePaid", 'f.fk_statut' => 'InvoiceStatus', 'f.note' => "NotePrivate", 'f.note_public' => "NotePublic", 'fd.rowid' => 'LineId', 'fd.description' => "LineDescription", 'fd.price' => "LineUnitPrice", 'fd.tva_tx' => "LineVATRate", 'fd.qty' => "LineQty", 'fd.total_ht' => "LineTotalHT", 'fd.total_tva' => "LineTotalVAT", 'fd.total_ttc' => "LineTotalTTC", 'fd.date_start' => "DateStart", 'fd.date_end' => "DateEnd", 'fd.product_type' => "TypeOfLineServiceOrProduct", 'fd.fk_product' => 'ProductId', 'p.ref' => 'ProductRef', 'p.label' => 'ProductLabel');
		$this->values->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.code_compta' => 'company', 's.code_compta_fournisseur' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "invoice", 'f.facnumber' => "invoice", 'f.datec' => "invoice", 'f.datef' => "invoice", 'f.date_lim_reglement' => "invoice", 'f.total' => "invoice", 'f.total_ttc' => "invoice", 'f.tva' => "invoice", 'f.paye' => "invoice", 'f.fk_statut' => 'invoice', 'f.note' => "invoice", 'f.note_public' => "invoice", 'fd.rowid' => 'invoice_line', 'fd.description' => "invoice_line", 'fd.price' => "invoice_line", 'fd.total_ht' => "invoice_line", 'fd.total_tva' => "invoice_line", 'fd.total_ttc' => "invoice_line", 'fd.tva_tx' => "invoice_line", 'fd.qty' => "invoice_line", 'fd.date_start' => "invoice_line", 'fd.date_end' => "invoice_line", 'fd.product_type' => 'invoice_line', 'fd.fk_product' => 'product', 'p.ref' => 'product', 'p.label' => 'product');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c on s.fk_pays = c.rowid,';
		$this->values->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facture as f,';
		$this->values->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facturedet as fd';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p on (fd.fk_product = p.rowid)';
		$this->values->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_facture';
		$this->values->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;
		$r++;

		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'CustomersInvoicesAndPayments'; // Translation key (used only if key ExportDataset_xxx_z not found)
		$this->values->export_icon[$r] = 'bill';
		$this->values->export_permission[$r] = array(array("facture", "facture", "export"));
		$this->values->export_fields_array[$r] = array('s.rowid' => "IdCompany", 's.nom' => 'CompanyName', 's.address' => 'Address', 's.cp' => 'Zip', 's.ville' => 'Town', 'c.code' => 'CountryCode', 's.tel' => 'Phone', 's.siren' => 'ProfId1', 's.siret' => 'ProfId2', 's.ape' => 'ProfId3', 's.idprof4' => 'ProfId4', 's.code_compta' => 'CustomerAccountancyCode', 's.code_compta_fournisseur' => 'SupplierAccountancyCode', 's.tva_intra' => 'VATIntra', 'f.rowid' => "InvoiceId", 'f.facnumber' => "InvoiceRef", 'f.datec' => "InvoiceDateCreation", 'f.datef' => "DateInvoice", 'f.date_lim_reglement' => "DateDue", 'f.total' => "TotalHT", 'f.total_ttc' => "TotalTTC", 'f.tva' => "TotalVAT", 'f.paye' => "InvoicePaid", 'f.fk_statut' => 'InvoiceStatus', 'f.note' => "NotePrivate", 'f.note_public' => "NotePublic", 'p.rowid' => 'PaymentId', 'pf.amount' => 'AmountPayment', 'p.datep' => 'DatePayment', 'p.num_paiement' => 'PaymentNumber');
		$this->values->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => 'company', 's.address' => 'company', 's.cp' => 'company', 's.ville' => 'company', 'c.code' => 'company', 's.tel' => 'company', 's.siren' => 'company', 's.siret' => 'company', 's.ape' => 'company', 's.idprof4' => 'company', 's.code_compta' => 'company', 's.code_compta_fournisseur' => 'company', 's.tva_intra' => 'company', 'f.rowid' => "invoice", 'f.facnumber' => "invoice", 'f.datec' => "invoice", 'f.datef' => "invoice", 'f.date_lim_reglement' => "invoice", 'f.total' => "invoice", 'f.total_ttc' => "invoice", 'f.tva' => "invoice", 'f.paye' => "invoice", 'f.fk_statut' => 'invoice', 'f.note' => "invoice", 'f.note_public' => "invoice", 'p.rowid' => 'payment', 'pf.amount' => 'payment', 'p.datep' => 'payment', 'p.num_paiement' => 'payment');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as c on s.fk_pays = c.rowid,';
		$this->values->export_sql_end[$r] .=' ' . MAIN_DB_PREFIX . 'facture as f';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiement_facture as pf ON pf.fk_facture = f.rowid';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiement as p ON pf.fk_paiement = p.rowid';
		$this->values->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid';
		$this->values->export_sql_end[$r] .=' AND f.entity = ' . $conf->entity;
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
		$this->values->remove($options);

		require_once(DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php');
		$dirodt = DOL_DATA_ROOT . '/doctemplates/invoices';
		dol_mkdir($dirodt);
		$src = DOL_DOCUMENT_ROOT . '/install/doctemplates/invoices/template_invoice.odt';
		$dest = $dirodt . '/template_invoice.odt';
		$result = dol_copy($src, $dest, 0, 0);
		if ($result < 0) {
			$langs->load("errors");
			$this->values->error = $langs->trans('ErrorFailToCopyFile', $src, $dest);
			return 0;
		}

		$sql = array(
			"DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = '" . $this->values->const[0][2] . "' AND entity = " . $conf->entity,
			"INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('" . $this->values->const[0][2] . "','invoice'," . $conf->entity . ")"
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
