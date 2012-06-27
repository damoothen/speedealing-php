<?php

/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   societe     Module societe
 * 	\brief      Module to manage third parties (customers, prospects)
 * 	\file       htdocs/core/modules/modSociete.class.php
 * 	\ingroup    societe
 * 	\brief      Fichier de description et activation du module Societe
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modSociete
 * 	\brief      Classe de description et activation du module Societe
 */
class modSociete extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modSociete($db) {
		global $conf;

		parent::__construct($db);

		$this->values->numero = 1;

		$this->values->family = "crm";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = "societe";
		$this->values->description = "Gestion des societes et contacts";

		// Possible values for version are: 'development', 'experimental', 'speedealing' or version
		$this->values->version = 'speedealing';

		$this->values->special = 0;
		$this->values->config_page_url = array("societe.php@societe");
		// Name of image file used for this module.
		$this->values->picto = 'company';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/societe/temp");

		// Dependances
		$this->values->depends = array();
		$this->values->requiredby = array("Expedition", "Facture", "Fournisseur", "Ficheinter", "Propale", "Contrat", "Commande");
		$this->values->langfiles = array("companies");

		// Constantes
		$this->values->const = array();
		$r = 0;

		$this->values->const[$r][0] = "SOCIETE_FISCAL_MONTH_START";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "0";
		$this->values->const[$r][3] = "Mettre le numero du mois du debut d\'annee fiscale, ex: 9 pour septembre";
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "MAIN_SEARCHFORM_SOCIETE";
		$this->values->const[$r][1] = "yesno";
		$this->values->const[$r][2] = "1";
		$this->values->const[$r][3] = "Show form for quick company search";
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "MAIN_SEARCHFORM_CONTACT";
		$this->values->const[$r][1] = "yesno";
		$this->values->const[$r][2] = "1";
		$this->values->const[$r][3] = "Show form for quick contact search";
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "COMPANY_ADDON_PDF_ODT_PATH";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "DOL_DATA_ROOT/doctemplates/thirdparties";
		$this->values->const[$r][3] = "";
		$this->values->const[$r][4] = 0;
		$r++;

		// Boxes
		$this->values->boxes = array();
		$r = 0;
		$this->values->boxes[$r][1] = "box_clients.php";
		$r++;
		$this->values->boxes[$r][1] = "box_prospect.php";
		$r++;
		$this->values->boxes[$r][1] = "box_contacts.php";
		$r++;

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'societe';
		$r = 0;
		$this->values->rights[$r]->id = 121; // id de la permission
		$this->values->rights[$r]->desc = 'Lire les societes'; // libelle de la permission
		$this->values->rights[$r]->default = true;
		$this->values->rights[$r]->perm = array('lire');

		$r++;
		$this->values->rights[$r]->id = 122; // id de la permission
		$this->values->rights[$r]->desc = 'Creer modifier les societes'; // libelle de la permission
		$this->values->rights[$r]->default = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('creer');

		$r++;
		$this->values->rights[$r]->id = 125; // id de la permission
		$this->values->rights[$r]->desc = 'Supprimer les societes'; // libelle de la permission
		$this->values->rights[$r]->default = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('supprimer');

		$r++;
		$this->values->rights[$r]->id = 126; // id de la permission
		$this->values->rights[$r]->desc = 'Exporter les societes'; // libelle de la permission
		$this->values->rights[$r]->default = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('export');

		// 262 : Resteindre l'acces des commerciaux
		$r++;
		$this->values->rights[$r]->id = 262;
		$this->values->rights[$r]->desc = 'Consulter tous les tiers par utilisateurs internes (sinon uniquement si contact commercial). Non effectif pour utilisateurs externes (tjs limités à eux-meme).';
		$this->values->rights[$r]->default = 1;
		$this->values->rights[$r]->perm = array('client', 'voir');

		$r++;
		$this->values->rights[$r]->id = 281; // id de la permission
		$this->values->rights[$r]->desc = 'Lire les contacts'; // libelle de la permission
		$this->values->rights[$r]->default = 1; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('contact', 'lire');

		$r++;
		$this->values->rights[$r]->id = 282; // id de la permission
		$this->values->rights[$r]->desc = 'Creer modifier les contacts'; // libelle de la permission
		$this->values->rights[$r]->default = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('contact', 'creer');

		$r++;
		$this->values->rights[$r]->id = 283; // id de la permission
		$this->values->rights[$r]->desc = 'Supprimer les contacts'; // libelle de la permission
		$this->values->rights[$r]->default = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('contact', 'supprimer');

		$r++;
		$this->values->rights[$r]->id = 286; // id de la permission
		$this->values->rights[$r]->desc = 'Exporter les contacts'; // libelle de la permission
		$this->values->rights[$r]->default = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('contact', 'export');

		// Menus
		$r = 0;
		$this->values->menus[$r]->_id = "menu:companies";
		$this->values->menus[$r]->type = "top";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/societe/index.php";
		$this->values->menus[$r]->langs = "companies";
		$this->values->menus[$r]->perms = '$user->rights->societe->lire || $user->rights->societe->contact->lire';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled || $conf->fournisseur->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "ThirdParties";

		$r++;
		$this->values->menus[$r]->_id = "menu:thirdparty";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/comm/list.php";
		$this->values->menus[$r]->langs = "companies";
		$this->values->menus[$r]->perms = '$user->rights->societe->lire';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "ListCustomersShort";
		$this->values->menus[$r]->fk_menu = "menu:companies";
		$r++;
		$this->values->menus[$r]->_id = "menu:contactsaddresses";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/contact/list.php";
		$this->values->menus[$r]->langs = "companies";
		$this->values->menus[$r]->perms = '$user->rights->societe->lire';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = 'ContactsAddresses||Contacts@$conf->global->SOCIETE_ADDRESSES_MANAGEMENT';
		$this->values->menus[$r]->fk_menu = "menu:companies";
		$r++;
		$this->values->menus[$r]->_id = "menu:newcontactaddress";
		$this->values->menus[$r]->url = "/contact/fiche.php?action=create";
		$this->values->menus[$r]->langs = "companies";
		$this->values->menus[$r]->perms = '$user->rights->societe->creer';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = 'NewContactAddress||NewContact@$conf->global->SOCIETE_ADDRESSES_MANAGEMENT';
		$this->values->menus[$r]->fk_menu = "menu:contactsaddresses";
		$r++;
		$this->values->menus[$r]->_id = "menu:list";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/contact/list.php";
		$this->values->menus[$r]->langs = "companies";
		$this->values->menus[$r]->perms = '$user->rights->societe->lire';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "List";
		$this->values->menus[$r]->fk_menu = "menu:contactsaddresses";
		$r++;
		$this->values->menus[$r]->_id = "menu:menunewthirdparty";
		$this->values->menus[$r]->url = "/societe/fiche.php?action=create";
		$this->values->menus[$r]->langs = "companies";
		$this->values->menus[$r]->perms = '$user->rights->societe->creer';
		$this->values->menus[$r]->enabled = '$conf->societe->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "MenuNewThirdParty";
		$this->values->menus[$r]->fk_menu = "menu:thirdparty";
		$r++;

		// Exports
		//--------
		$r = 0;

		// Export list of third parties and attributes
		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'ExportDataset_company_1';
		$this->values->export_icon[$r] = 'company';
		$this->values->export_permission[$r] = array(array("societe", "export"));
		$this->values->export_fields_array[$r] = array('s.rowid' => "Id", 's.nom' => "Name", 's.status' => "Status", 's.client' => "Customer", 's.fournisseur' => "Supplier", 's.datec' => "DateCreation", 's.tms' => "DateLastModification", 's.code_client' => "CustomerCode", 's.code_fournisseur' => "SupplierCode", 's.address' => "Address", 's.cp' => "Zip", 's.ville' => "Town", 'p.libelle' => "Country", 'p.code' => "CountryCode", 's.tel' => "Phone", 's.fax' => "Fax", 's.url' => "Url", 's.email' => "Email", 's.default_lang' => "DefaultLang", 's.siret' => "IdProf1", 's.siren' => "IdProf2", 's.ape' => "IdProf3", 's.idprof4' => "IdProf4", 's.tva_intra' => "VATIntraShort", 's.capital' => "Capital", 's.note' => "Note", 't.libelle' => "ThirdPartyType", 'ce.code' => "Effectif", "cfj.libelle" => "JuridicalStatus", 's.fk_prospectlevel' => 'ProspectLevel', 's.fk_stcomm' => 'ProspectStatus', 'd.nom' => 'State');
		if (!empty($conf->global->SOCIETE_USEPREFIX))
			$this->values->export_fields_array[$r]['s.prefix'] = 'Prefix';
		$this->values->export_entities_array[$r] = array(); // We define here only fields that use another picto
		/*// Add extra fields
		$sql = "SELECT name, label FROM " . MAIN_DB_PREFIX . "extrafields WHERE elementtype = 'company'";
		$resql = $this->db->query($sql);
		if ($resql) { // This can fail when class is used on old database (during migration for example)
			while ($obj = $this->db->fetch_object($resql)) {
				$fieldname = 'extra.' . $obj->name;
				$fieldlabel = ucfirst($obj->label);
				$this->values->export_fields_array[$r][$fieldname] = $fieldlabel;
				$this->values->export_entities_array[$r][$fieldname] = 'company';
			}
		}*/
		// End add axtra fields
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'societe as s';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'societe_extrafields as extra ON s.rowid = extra.fk_object';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_typent as t ON s.fk_typent = t.id';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as p ON s.fk_pays = p.rowid';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_effectif as ce ON s.fk_effectif = ce.id';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_forme_juridique as cfj ON s.fk_forme_juridique = cfj.code';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_departements as d ON s.fk_departement = d.rowid';
		$this->values->export_sql_end[$r] .=' WHERE s.entity IN (' . getEntity('societe', 1) . ')';

		// Export list of contacts and attributes
		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'ExportDataset_company_2';
		$this->values->export_icon[$r] = 'contact';
		$this->values->export_permission[$r] = array(array("societe", "contact", "export"));
		$this->values->export_fields_array[$r] = array('c.rowid' => "IdContact", 'c.civilite' => "CivilityCode", 'c.name' => 'Lastname', 'c.firstname' => 'Firstname', 'c.datec' => "DateCreation", 'c.tms' => "DateLastModification", 'c.priv' => "ContactPrivate", 'c.address' => "Address", 'c.cp' => "Zip", 'c.ville' => "Town", 'c.phone' => "Phone", 'c.fax' => "Fax", 'c.email' => "EMail", 'p.libelle' => "Country", 'p.code' => "CountryCode", 's.rowid' => "IdCompany", 's.nom' => "CompanyName", 's.status' => "Status", 's.code_client' => "CustomerCode", 's.code_fournisseur' => "SupplierCode");
		$this->values->export_entities_array[$r] = array('s.rowid' => "company", 's.nom' => "company", 's.code_client' => "company", 's.code_fournisseur' => "company"); // We define here only fields that use another picto
		if (empty($conf->fournisseur->enabled)) {
			unset($this->values->export_fields_array[$r]['s.code_fournisseur']);
			unset($this->values->export_entities_array[$r]['s.code_fournisseur']);
		}
		/*// Add extra fields
		$sql = "SELECT name, label FROM " . MAIN_DB_PREFIX . "extrafields WHERE elementtype = 'contact'";
		$resql = $this->db->query($sql);
		if ($resql) { // This can fail when class is used on old database (during migration for example)
			while ($obj = $this->db->fetch_object($resql)) {
				$fieldname = 'extra.' . $obj->name;
				$fieldlabel = ucfirst($obj->label);
				$this->values->export_fields_array[$r][$fieldname] = $fieldlabel;
				$this->values->export_entities_array[$r][$fieldname] = 'contact';
			}
		}*/
		// End add axtra fields
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'socpeople as c';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'societe as s ON c.fk_soc = s.rowid';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as p ON c.fk_pays = p.rowid';
		$this->values->export_sql_end[$r] .=' WHERE c.entity IN (' . getEntity("societe", 1) . ')';


		// Imports
		//--------
		$r = 0;

		// Import list of third parties and attributes
		$r++;
		$this->values->import_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->import_label[$r] = 'ImportDataset_company_1';
		$this->values->import_icon[$r] = 'company';
		$this->values->import_entities_array[$r] = array();  // We define here only fields that use another icon that the one defined into import_icon
		$this->values->import_tables_array[$r] = array('s' => MAIN_DB_PREFIX . 'societe', 'extra' => MAIN_DB_PREFIX . 'societe_extrafields'); // List of tables to insert into (insert done in same order)
		$this->values->import_fields_array[$r] = array('s.nom' => "Name*", 's.status' => "Status", 's.client' => "Customer*", 's.fournisseur' => "Supplier*", 's.code_client' => "CustomerCode", 's.code_fournisseur' => "SupplierCode", 's.code_compta' => "CustomerAccountancyCode", 's.code_compta_fournisseur' => "SupplierAccountancyCode", 's.address' => "Address", 's.cp' => "Zip", 's.ville' => "Town", 's.fk_pays' => "CountryCode", 's.tel' => "Phone", 's.fax' => "Fax", 's.url' => "Url", 's.email' => "Email", 's.siret' => "IdProf1", 's.siren' => "IdProf2", 's.ape' => "IdProf3", 's.idprof4' => "IdProf4", 's.tva_intra' => "VATIntraShort", 's.capital' => "Capital", 's.note' => "Note", 's.fk_typent' => "ThirdPartyType", 's.fk_effectif' => "Effectif", "s.fk_forme_juridique" => "JuridicalStatus", 's.fk_prospectlevel' => 'ProspectLevel', 's.fk_stcomm' => 'ProspectStatus', 's.default_lang' => 'DefaultLanguage', 's.barcode' => 'BarCode', 's.datec' => "DateCreation");
		/*// Add extra fields
		$sql = "SELECT name, label FROM " . MAIN_DB_PREFIX . "extrafields WHERE elementtype = 'company'";
		$resql = $this->db->query($sql);
		if ($resql) { // This can fail when class is used on old database (during migration for example)
			while ($obj = $this->db->fetch_object($resql)) {
				$fieldname = 'extra.' . $obj->name;
				$fieldlabel = ucfirst($obj->label);
				$this->values->import_fields_array[$r][$fieldname] = $fieldlabel;
			}
		}*/
		// End add extra fields
		$this->values->import_fieldshidden_array[$r] = array('s.fk_user_creat' => 'user->id', 'extra.fk_object' => 'lastrowid-' . MAIN_DB_PREFIX . 'societe'); // aliastable.field => ('user->id' or 'lastrowid-'.tableparent)
		$this->values->import_convertvalue_array[$r] = array(
			's.fk_typent' => array('rule' => 'fetchidfromcodeid', 'classfile' => '/core/class/ctypent.class.php', 'class' => 'Ctypent', 'method' => 'fetch', 'dict' => 'DictionnaryCompanyType'),
			's.fk_pays' => array('rule' => 'fetchidfromcodeid', 'classfile' => '/core/class/cpays.class.php', 'class' => 'Cpays', 'method' => 'fetch', 'dict' => 'DictionnaryCountry'),
			's.fk_stcomm' => array('rule' => 'zeroifnull'),
			's.code_client' => array('rule' => 'getcustomercodeifnull'),
			's.code_fournisseur' => array('rule' => 'getsuppliercodeifnull'),
			's.code_compta' => array('rule' => 'getcustomeraccountancycodeifnull'),
			's.code_compta_fournisseur' => array('rule' => 'getsupplieraccountancycodeifnull')
		);
		//$this->values->import_convertvalue_array[$r]=array('s.fk_soc'=>array('rule'=>'lastrowid',table='t');
		$this->values->import_regex_array[$r] = array('s.status' => '^[0|1]', 's.client' => '^[0|1|2|3]', 's.fournisseur' => '^[0|1]', 's.fk_typent' => 'id@' . MAIN_DB_PREFIX . 'c_typent', 's.datec' => '^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$');
		$this->values->import_examplevalues_array[$r] = array('s.nom' => "MyBigCompany", 's.status' => "0 (closed) or 1 (active)", 's.client' => '0 (no customer no prospect)/1 (customer)/2 (prospect)/3 (customer and prospect)', 's.fournisseur' => '0 or 1', 's.datec' => dol_print_date(dol_now(), '%Y-%m-%d'), 's.code_client' => "CU01-0001 or auto", 's.code_fournisseur' => "SU01-0001 or auto", 's.address' => "61 jump street", 's.cp' => "123456", 's.ville' => "Big town", 's.fk_pays' => 'US, FR, DE...', 's.tel' => "0101010101", 's.fax' => "0101010102", 's.url' => "http://mycompany.com", 's.email' => "test@mycompany.com", 's.siret' => "", 's.siren' => "", 's.ape' => "", 's.idprof4' => "", 's.tva_intra' => "FR0123456789", 's.capital' => "10000", 's.note' => "This is an example of note for record", 's.fk_typent' => "2", 's.fk_effectif' => "3", "s.fk_forme_juridique" => "1", 's.fk_prospectlevel' => 'PL_MEDIUM', 's.fk_stcomm' => '0', 's.default_lang' => 'en_US', 's.barcode' => '123456789');

		// Import list of contact and attributes
		$r++;
		$this->values->import_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->import_label[$r] = 'ImportDataset_company_2';
		$this->values->import_icon[$r] = 'contact';
		$this->values->import_entities_array[$r] = array('s.fk_soc' => 'company'); // We define here only fields that use another icon that the one defined into import_icon
		$this->values->import_tables_array[$r] = array('s' => MAIN_DB_PREFIX . 'socpeople'); // List of tables to insert into (insert done in same order)
		$this->values->import_fields_array[$r] = array('s.fk_soc' => 'ThirdPartyName*', 's.civilite' => 'Civility', 's.name' => "Name*", 's.firstname' => "Firstname", 's.address' => "Address", 's.cp' => "Zip", 's.ville' => "Town", 's.fk_pays' => "CountryCode", 's.birthday' => "BirthdayDate", 's.poste' => "Role", 's.phone' => "Phone", 's.phone_perso' => "PhonePerso", 's.phone_mobile' => "PhoneMobile", 's.fax' => "Fax", 's.email' => "Email", 's.note' => "Note", 's.datec' => "DateCreation");
		$this->values->import_fieldshidden_array[$r] = array('s.fk_user_creat' => 'user->id'); // aliastable.field => ('user->id' or 'lastrowid-'.tableparent)
		$this->values->import_convertvalue_array[$r] = array(
			's.fk_soc' => array('rule' => 'fetchidfromref', 'file' => '/societe/class/societe.class.php', 'class' => 'Societe', 'method' => 'fetch', 'element' => 'ThirdParty'),
			's.fk_pays' => array('rule' => 'fetchidfromcodeid', 'classfile' => '/core/class/cpays.class.php', 'class' => 'Cpays', 'method' => 'fetch', 'dict' => 'DictionnaryCountry'),
		);
		//$this->values->import_convertvalue_array[$r]=array('s.fk_soc'=>array('rule'=>'lastrowid',table='t');
		$this->values->import_regex_array[$r] = array('s.birthday' => '^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$', 's.datec' => '^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$');
		$this->values->import_examplevalues_array[$r] = array('s.fk_soc' => 'MyBigCompany', 's.civilite' => "MR", 's.name' => "Smith", 's.firstname' => 'John', 's.address' => '61 jump street', 's.cp' => '75000', 's.ville' => 'Bigtown', 's.fk_pays' => 'US, FR, DE...', 's.datec' => '1972-10-10', 's.poste' => "Director", 's.phone' => "5551122", 's.phone_perso' => "5551133", 's.phone_mobile' => "5551144", 's.fax' => "5551155", 's.email' => "johnsmith@email.com", 's.note' => "My comments");
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

		// We disable this to prevent pb of modules not correctly disabled
		//$this->values->remove($options);

		require_once(DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php');
		$dirodt = DOL_DATA_ROOT . '/doctemplates/thirdparties';
		dol_mkdir($dirodt);
		$src = DOL_DOCUMENT_ROOT . '/install/doctemplates/thirdparties/template_thirdparty.odt';
		$dest = $dirodt . '/template_thirdparty.odt';
		$result = dol_copy($src, $dest, 0, 0);
		if ($result < 0) {
			$langs->load("errors");
			$this->values->error = $langs->trans('ErrorFailToCopyFile', $src, $dest);
			return 0;
		}

		$sql = array();

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
