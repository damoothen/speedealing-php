<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
 * Copyright (C) 2010-2012 Herve Prot          <herve.prot@symeos.com>
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
 * 	\defgroup   contrat     Module contract
 * 	\brief      Module pour gerer la tenue de contrat de services
 * 	\file       htdocs/core/modules/modContrat.class.php
 * 	\ingroup    contrat
 * 	\brief      Fichier de description et activation du module Contrat
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
  \class      modContrat
  \brief      Classe de description et activation du module Contrat
 */
class modContrat extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modContrat($db) {
		parent::__construct($db);
		$this->values->numero = 54;

		$this->values->family = "crm";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des contrats de services";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'contract';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/contracts/temp");

		// Dependances
		$this->values->depends = array("modService");
		$this->values->requiredby = array();

		// Config pages
		$this->values->config_page_url = array("contract.php@contrat");

		// Constantes
		$this->values->const = array();
		$this->values->const[0][0] = "CONTRACT_ADDON";
		$this->values->const[0][1] = "chaine";
		$this->values->const[0][2] = "mod_contract_serpis";
		$this->values->const[0][3] = 'Nom du gestionnaire de numerotation des contrats';
		$this->values->const[0][4] = 0;
		$this->values->const[1] = array("CONTRAT_ADDON_PDF_ODT_PATH", "chaine", "DOL_DATA_ROOT/doctemplates/contracts", "Directory models");

		// Boxes
		$this->values->boxes = array();
		$this->values->boxes[0][1] = "box_contracts.php";
		$this->values->boxes[1][1] = "box_services_expired.php";

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'contrat';

		$this->values->rights[1][0] = 161;
		$this->values->rights[1][1] = 'Lire les contrats';
		$this->values->rights[1][2] = 'r';
		$this->values->rights[1][3] = 1;
		$this->values->rights[1][4] = 'lire';

		$this->values->rights[2][0] = 162;
		$this->values->rights[2][1] = 'Creer / modifier les contrats';
		$this->values->rights[2][2] = 'w';
		$this->values->rights[2][3] = 0;
		$this->values->rights[2][4] = 'creer';

		$this->values->rights[3][0] = 163;
		$this->values->rights[3][1] = 'Activer un service d\'un contrat';
		$this->values->rights[3][2] = 'w';
		$this->values->rights[3][3] = 0;
		$this->values->rights[3][4] = 'activer';

		$this->values->rights[4][0] = 164;
		$this->values->rights[4][1] = 'Desactiver un service d\'un contrat';
		$this->values->rights[4][2] = 'w';
		$this->values->rights[4][3] = 0;
		$this->values->rights[4][4] = 'desactiver';

		$this->values->rights[5][0] = 165;
		$this->values->rights[5][1] = 'Supprimer un contrat';
		$this->values->rights[5][2] = 'd';
		$this->values->rights[5][3] = 0;
		$this->values->rights[5][4] = 'supprimer';
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

		// Nettoyage avant activation
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
