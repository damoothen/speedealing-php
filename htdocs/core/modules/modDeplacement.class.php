<?php

/* Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   deplacement     Module trips
 * 	\brief      Module pour gerer les deplacements et notes de frais
 * 	\file       htdocs/core/modules/modDeplacement.class.php
 * 	\ingroup    deplacement
 * 	\brief      Fichier de description et activation du module Deplacement et notes de frais
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modDeplacement
 * 	\brief      Classe de description et activation du module Deplacement
 */
class modDeplacement extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$DB      Database handler
	 */
	function modDeplacement($DB) {
		global $conf;

		parent::__construct($DB);
		$this->values->numero = 75;

		$this->values->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des notes de frais et deplacements";  // Si traduction Module75Desc non trouvee
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = "trip";

		// Data directories to create when module is enabled
		$this->values->dirs = array();

		// Config pages
		$this->values->config_page_url = array();
		$this->values->langfiles = array("companies", "trips");

		// Dependancies
		$this->values->depends = array();
		$this->values->requiredby = array();

		// Constants
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'deplacement';

		$this->values->rights[1][0] = 171;
		$this->values->rights[1][1] = 'Lire les deplacements';
		$this->values->rights[1][2] = 'r';
		$this->values->rights[1][3] = 1;
		$this->values->rights[1][4] = 'lire';

		$this->values->rights[2][0] = 172;
		$this->values->rights[2][1] = 'Creer/modifier les deplacements';
		$this->values->rights[2][2] = 'w';
		$this->values->rights[2][3] = 0;
		$this->values->rights[2][4] = 'creer';

		$this->values->rights[3][0] = 173;
		$this->values->rights[3][1] = 'Supprimer les deplacements';
		$this->values->rights[3][2] = 'd';
		$this->values->rights[3][3] = 0;
		$this->values->rights[3][4] = 'supprimer';
		/*
		  $this->values->rights[4][0] = 174;
		  $this->values->rights[4][1] = 'Bloquer les deplacements';
		  $this->values->rights[4][2] = 'a';
		  $this->values->rights[4][3] = 0;
		  $this->values->rights[4][4] = 'valider';

		  $this->values->rights[5][0] = 175;
		  $this->values->rights[5][1] = 'Debloquer les deplacements';
		  $this->values->rights[5][2] = 'a';
		  $this->values->rights[5][3] = 0;
		  $this->values->rights[5][4] = 'unvalidate';
		 */
		$this->values->rights[6][0] = 178;
		$this->values->rights[6][1] = 'Exporter les deplacements';
		$this->values->rights[6][2] = 'd';
		$this->values->rights[6][3] = 0;
		$this->values->rights[6][4] = 'export';

		// Exports
		$r = 0;

		$r++;
		$this->values->export_code[$r] = 'trips_' . $r;
		$this->values->export_label[$r] = 'ListTripsAndExpenses';
		$this->values->export_permission[$r] = array(array("deplacement", "export"));
		$this->values->export_fields_array[$r] = array('u.login' => 'Login', 'u.name' => 'Lastname', 'u.firstname' => 'Firstname', 'd.rowid' => "TripId", 'd.type' => "Type", 'd.km' => "FeesKilometersOrAmout", 'd.dated' => "Date", 'd.note' => 'NotePrivate', 'd.note_public' => 'NotePublic', 's.nom' => 'ThirdParty');
		$this->values->export_entities_array[$r] = array('u.login' => 'user', 'u.name' => 'user', 'u.firstname' => 'user', 'd.rowid' => "trip", 'd.type' => "trip", 'd.km' => "trip", 'd.dated' => "trip", 'd.note' => 'trip', 'd.note_public' => 'trip', 's.nom' => 'company');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'user as u';
		$this->values->export_sql_end[$r] .=', ' . MAIN_DB_PREFIX . 'deplacement as d';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'societe as s ON d.fk_soc = s.rowid';
		$this->values->export_sql_end[$r] .=' WHERE d.fk_user = u.rowid';
		$this->values->export_sql_end[$r] .=' AND d.entity = ' . $conf->entity;
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
