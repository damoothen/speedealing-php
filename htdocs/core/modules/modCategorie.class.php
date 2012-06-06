<?php

/* Copyright (C) 2005      Matthieu Valleton    <mv@seeschloss.org>
 * Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \defgroup   category       Module categories
 *      \brief      Module to manage categories
 *      \file       htdocs/core/modules/modCategorie.class.php
 *      \ingroup    category
 *      \brief      Fichier de description et activation du module Categorie
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 *       \class      modCategorie
 *       \brief      Classe de description et activation du module Categorie
 */
class modCategorie extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modCategorie($db) {
		global $conf;

		parent::__construct($db);
		$this->values->numero = 1780;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des categories (produits, clients, fournisseurs...)";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 2;
		$this->values->picto = 'category';

		// Data directories to create when module is enabled
		$this->values->dirs = array();

		// Dependencies
		$this->values->depends = array();

		// Config pages
		$this->values->config_page_url = array();
		$this->values->langfiles = array("products", "companies", "categories");

		// Constantes
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'categorie';

		$r = 0;

		$this->values->rights[$r][0] = 241; // id de la permission
		$this->values->rights[$r][1] = 'Lire les categories'; // libelle de la permission
		$this->values->rights[$r][2] = 'r'; // type de la permission (deprecated)
		$this->values->rights[$r][3] = 1; // La permission est-elle une permission par defaut
		$this->values->rights[$r][4] = 'lire';
		$r++;

		$this->values->rights[$r][0] = 242; // id de la permission
		$this->values->rights[$r][1] = 'Creer/modifier les categories'; // libelle de la permission
		$this->values->rights[$r][2] = 'w'; // type de la permission (deprecated)
		$this->values->rights[$r][3] = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r][4] = 'creer';
		$r++;

		$this->values->rights[$r][0] = 243; // id de la permission
		$this->values->rights[$r][1] = 'Supprimer les categories'; // libelle de la permission
		$this->values->rights[$r][2] = 'd'; // type de la permission (deprecated)
		$this->values->rights[$r][3] = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r][4] = 'supprimer';
		$r++;

		// Exports
		//--------
		$r = 0;

		$r++;
		$this->values->export_code[$r] = 'category_' . $r;
		$this->values->export_label[$r] = 'CatSupList';
		$this->values->export_icon[$r] = 'category';
		$this->values->export_enabled[$r] = '$conf->fournisseur->enabled';
		$this->values->export_permission[$r] = array(array("categorie", "lire"), array("fournisseur", "lire"));
		$this->values->export_fields_array[$r] = array('u.rowid' => "CategId", 'u.label' => "Label", 'u.description' => "Description", 's.rowid' => 'IdThirdParty', 's.nom' => 'Name', 's.prefix_comm' => "Prefix", 's.client' => "Customer", 's.datec' => "DateCreation", 's.tms' => "DateLastModification", 's.code_client' => "CustomerCode", 's.address' => "Address", 's.cp' => "Zip", 's.ville' => "Town", 'p.libelle' => "Country", 'p.code' => "CountryCode", 's.tel' => "Phone", 's.fax' => "Fax", 's.url' => "Url", 's.email' => "Email", 's.siret' => "IdProf1", 's.siren' => "IdProf2", 's.ape' => "IdProf3", 's.idprof4' => "IdProf4", 's.tva_intra' => "VATIntraShort", 's.capital' => "Capital", 's.note' => "Note");
		$this->values->export_entities_array[$r] = array('s.rowid' => 'company', 's.nom' => 'company', 's.prefix_comm' => "company", 's.client' => "company", 's.datec' => "company", 's.tms' => "company", 's.code_client' => "company", 's.address' => "company", 's.cp' => "company", 's.ville' => "company", 'p.libelle' => "company", 'p.code' => "company", 's.tel' => "company", 's.fax' => "company", 's.url' => "company", 's.email' => "company", 's.siret' => "company", 's.siren' => "company", 's.ape' => "company", 's.idprof4' => "company", 's.tva_intra' => "company", 's.capital' => "company", 's.note' => "company"); // We define here only fields that use another picto
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'categorie as u, ' . MAIN_DB_PREFIX . 'categorie_fournisseur as cf, ' . MAIN_DB_PREFIX . 'societe as s LEFT JOIN ' . MAIN_DB_PREFIX . 'c_typent as t ON s.fk_typent = t.id LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as p ON s.fk_pays = p.rowid LEFT JOIN ' . MAIN_DB_PREFIX . 'c_effectif as ce ON s.fk_effectif = ce.id LEFT JOIN ' . MAIN_DB_PREFIX . 'c_forme_juridique as cfj ON s.fk_forme_juridique = cfj.code';
		$this->values->export_sql_end[$r] .=' WHERE u.rowid = cf.fk_categorie AND cf.fk_societe = s.rowid';
		$this->values->export_sql_end[$r] .=' AND u.entity = ' . $conf->entity;
		$this->values->export_sql_end[$r] .=' AND u.type = 1'; // Supplier categories

		$r++;
		$this->values->export_code[$r] = 'category_' . $r;
		$this->values->export_label[$r] = 'CatCusList';
		$this->values->export_icon[$r] = 'category';
		$this->values->export_enabled[$r] = '$conf->societe->enabled';
		$this->values->export_permission[$r] = array(array("categorie", "lire"), array("societe", "lire"));
		$this->values->export_fields_array[$r] = array('u.rowid' => "CategId", 'u.label' => "Label", 'u.description' => "Description", 's.rowid' => 'IdThirdParty', 's.nom' => 'Name', 's.prefix_comm' => "Prefix", 's.client' => "Customer", 's.datec' => "DateCreation", 's.tms' => "DateLastModification", 's.code_client' => "CustomerCode", 's.address' => "Address", 's.cp' => "Zip", 's.ville' => "Town", 'p.libelle' => "Country", 'p.code' => "CountryCode", 's.tel' => "Phone", 's.fax' => "Fax", 's.url' => "Url", 's.email' => "Email", 's.siret' => "IdProf1", 's.siren' => "IdProf2", 's.ape' => "IdProf3", 's.idprof4' => "IdProf4", 's.tva_intra' => "VATIntraShort", 's.capital' => "Capital", 's.note' => "Note", 's.fk_prospectlevel' => 'ProspectLevel', 's.fk_stcomm' => 'ProspectStatus');
		$this->values->export_entities_array[$r] = array('s.rowid' => 'company', 's.nom' => 'company', 's.prefix_comm' => "company", 's.client' => "company", 's.datec' => "company", 's.tms' => "company", 's.code_client' => "company", 's.address' => "company", 's.cp' => "company", 's.ville' => "company", 'p.libelle' => "company", 'p.code' => "company", 's.tel' => "company", 's.fax' => "company", 's.url' => "company", 's.email' => "company", 's.siret' => "company", 's.siren' => "company", 's.ape' => "company", 's.idprof4' => "company", 's.tva_intra' => "company", 's.capital' => "company", 's.note' => "company", 's.fk_prospectlevel' => 'company', 's.fk_stcomm' => 'company'); // We define here only fields that use another picto
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'categorie as u, ' . MAIN_DB_PREFIX . 'categorie_societe as cf, ' . MAIN_DB_PREFIX . 'societe as s LEFT JOIN ' . MAIN_DB_PREFIX . 'c_typent as t ON s.fk_typent = t.id LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as p ON s.fk_pays = p.rowid LEFT JOIN ' . MAIN_DB_PREFIX . 'c_effectif as ce ON s.fk_effectif = ce.id LEFT JOIN ' . MAIN_DB_PREFIX . 'c_forme_juridique as cfj ON s.fk_forme_juridique = cfj.code';
		$this->values->export_sql_end[$r] .=' WHERE u.rowid = cf.fk_categorie AND cf.fk_societe = s.rowid';
		$this->values->export_sql_end[$r] .=' AND u.entity = ' . $conf->entity;
		$this->values->export_sql_end[$r] .=' AND u.type = 2'; // Customer/Prospect categories

		$r++;
		$this->values->export_code[$r] = 'category_' . $r;
		$this->values->export_label[$r] = 'CatConList';
		$this->values->export_icon[$r] = 'category';
		$this->values->export_enabled[$r] = '$conf->societe->enabled';
		$this->values->export_permission[$r] = array(array("categorie", "lire"), array("societe", "lire"), array("societe", "contact", "export"));
		$this->values->export_fields_array[$r] = array('u.rowid' => "CategId", 'u.label' => "Label", 'u.description' => "Description", 's.rowid' => 'IdThirdParty', 's.nom' => 'Name', 's.prefix_comm' => "Prefix", 's.client' => "Customer", 's.datec' => "DateCreation", 's.tms' => "DateLastModification", 's.code_client' => "CustomerCode", 's.address' => "Address", 's.cp' => "Zip", 's.ville' => "Town", 'p.libelle' => "Country", 'p.code' => "CountryCode", 's.tel' => "Phone", 's.fax' => "Fax", 's.url' => "Url", 's.email' => "Email", 's.siret' => "IdProf1", 's.siren' => "IdProf2", 's.ape' => "IdProf3", 's.idprof4' => "IdProf4", 's.tva_intra' => "VATIntraShort", 's.capital' => "Capital", 's.note' => "Note", 's.fk_prospectlevel' => 'ProspectLevel', 's.fk_stcomm' => 'ProspectStatus', 'c.rowid' => "IdContact", 'c.civilite' => "CivilityCode", 'c.poste' => 'Fonction', 'c.name' => 'Lastname', 'c.firstname' => 'Firstname', 'c.datec' => "DateCreation", 'c.tms' => "DateLastModification", 'c.priv' => "ContactPrivate", 'c.address' => "Address", 'c.cp' => "Zip", 'c.ville' => "Town", 'c.phone' => "Phone", 'c.fax' => "Fax", 'c.email' => "EMail");
		$this->values->export_entities_array[$r] = array('s.rowid' => 'company', 's.nom' => 'company', 's.prefix_comm' => "company", 's.client' => "company", 's.datec' => "company", 's.tms' => "company", 's.code_client' => "company", 's.address' => "company", 's.cp' => "company", 's.ville' => "company", 'p.libelle' => "company", 'p.code' => "company", 's.tel' => "company", 's.fax' => "company", 's.url' => "company", 's.email' => "company", 's.siret' => "company", 's.siren' => "company", 's.ape' => "company", 's.idprof4' => "company", 's.tva_intra' => "company", 's.capital' => "company", 's.note' => "company", 's.fk_prospectlevel' => 'company', 's.fk_stcomm' => 'company', 'c.rowid' => "contact", 'c.civilite' => "contact", 'c.poste' => 'contact', 'c.name' => 'contact', 'c.firstname' => 'contact', 'c.datec' => "contact", 'c.tms' => "contact", 'c.priv' => "contact", 'c.address' => "contact", 'c.cp' => "contact", 'c.ville' => "contact", 'c.phone' => "contact", 'c.fax' => "contact", 'c.email' => "contact"); // We define here only fields that use another picto
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'categorie as u, ' . MAIN_DB_PREFIX . 'categorie_contact as cf, ' . MAIN_DB_PREFIX . 'socpeople as c LEFT JOIN ' . MAIN_DB_PREFIX . 'societe as s ON s.rowid=c.fk_soc LEFT JOIN ' . MAIN_DB_PREFIX . 'c_typent as t ON s.fk_typent = t.id LEFT JOIN ' . MAIN_DB_PREFIX . 'c_pays as p ON s.fk_pays = p.rowid LEFT JOIN ' . MAIN_DB_PREFIX . 'c_effectif as ce ON s.fk_effectif = ce.id LEFT JOIN ' . MAIN_DB_PREFIX . 'c_forme_juridique as cfj ON s.fk_forme_juridique = cfj.code';
		$this->values->export_sql_end[$r] .=' WHERE u.rowid = cf.fk_categorie AND cf.fk_contact = c.rowid';
		$this->values->export_sql_end[$r] .=' AND u.type = 5'; // Customer/Prospect categories

		$r++;
		$this->values->export_code[$r] = 'category_' . $r;
		$this->values->export_label[$r] = 'CatProdList';
		$this->values->export_icon[$r] = 'category';
		$this->values->export_enabled[$r] = '$conf->produit->enabled';
		$this->values->export_permission[$r] = array(array("categorie", "lire"), array("produit", "lire"));
		$this->values->export_fields_array[$r] = array('u.rowid' => "CategId", 'u.label' => "Label", 'u.description' => "Description", 'p.rowid' => 'ProductId', 'p.ref' => 'Ref');
		$this->values->export_entities_array[$r] = array('p.rowid' => 'product', 'p.ref' => 'product'); // We define here only fields that use another picto
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'categorie as u, ' . MAIN_DB_PREFIX . 'categorie_product as cp, ' . MAIN_DB_PREFIX . 'product as p';
		$this->values->export_sql_end[$r] .=' WHERE u.rowid = cp.fk_categorie AND cp.fk_product = p.rowid';
		$this->values->export_sql_end[$r] .=' AND u.entity = ' . $conf->entity;
		$this->values->export_sql_end[$r] .=' AND u.type = 0'; // Supplier categories

		$r++;
		$this->values->export_code[$r] = 'category_' . $r;
		$this->values->export_label[$r] = 'CatMemberList';
		$this->values->export_icon[$r] = 'category';
		$this->values->export_enabled[$r] = '$conf->adherent->enabled';
		$this->values->export_permission[$r] = array(array("categorie", "lire"), array("adherent", "lire"));
		$this->values->export_fields_array[$r] = array('u.rowid' => "CategId", 'u.label' => "Label", 'u.description' => "Description", 'p.rowid' => 'MemberId', 'p.nom' => 'Name', 'p.prenom' => 'Firstname');
		$this->values->export_entities_array[$r] = array('p.rowid' => 'member', 'p.nom' => 'member', 'p.prenom' => 'member'); // We define here only fields that use another picto
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'categorie as u, ' . MAIN_DB_PREFIX . 'categorie_member as cp, ' . MAIN_DB_PREFIX . 'adherent as p';
		$this->values->export_sql_end[$r] .=' WHERE u.rowid = cp.fk_categorie AND cp.fk_member = p.rowid';
		$this->values->export_sql_end[$r] .=' AND u.entity = ' . $conf->entity;
		$this->values->export_sql_end[$r] .=' AND u.type = 3'; // Supplier categories
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
		// Permissions
		$this->values->remove($options);

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
