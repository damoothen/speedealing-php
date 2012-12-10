<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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

/**
 * 	\defgroup   accounting 			Module accounting
 * 	\brief      Module to include accounting features
 *	\file       htdocs/core/modules/modAccounting.class.php
 *	\ingroup    accounting
 * 	\brief      Fichier de description et activation du module Comptabilite Expert
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Comptabilite Expert
 */
class modAccounting extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		global $conf;

		parent::__construct($db);
		$this->numero = 50400;

		$this->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		$this->description = "Gestion complete de comptabilite (doubles parties)";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		//$this->version = 'dolibarr';
		$this->version = "development";

		$this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
		$this->special = 0;

		// Config pages
		$this->config_page_url = array("accounting.php");

		// Dependancies
		$this->depends = array("modFacture", "modBanque", "modTax");
		$this->requiredby = array();
		$this->conflictwith = array("modComptabilite");
		$this->langfiles = array("compta");

		// Constants
		$this->const = array(0 => array('MAIN_COMPANY_CODE_ALWAYS_REQUIRED', 'chaine', '1', 'With this constants on, third party code is always required whatever is numbering module behaviour', 0, 'current', 1),
			1 => array('MAIN_BANK_ACCOUNTANCY_CODE_ALWAYS_REQUIRED', 'chaine', '1', 'With this constants on, bank account number is always required', 0, 'current', 1),
		);   // List of particular constants to add when module is enabled
		// Data directories to create when module is enabled
		$this->dirs = array("/accounting/temp");

		// Boxes
		$this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'accounting';

		$this->rights[$r][0] = 50401;
		$this->rights[$r][1] = 'Lire le plan de compte';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'plancompte';
		$this->rights[$r][5] = 'lire';

		$this->rights[$r][0] = 50402;
		$this->rights[$r][1] = 'Creer/modifier un plan de compte';
		$this->rights[$r][2] = 'w';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'plancompte';
		$this->rights[$r][5] = 'creer';

		$this->rights[$r][0] = 50403;
		$this->rights[$r][1] = 'Cloturer plan de compte';
		$this->rights[$r][2] = 'w';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'plancompte';
		$this->rights[$r][5] = 'cloturer';

		$this->rights[$r][0] = 50411;
		$this->rights[$r][1] = 'Lire les mouvements comptables';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'mouvements';
		$this->rights[$r][5] = 'lire';

		$this->rights[$r][0] = 50412;
		$this->rights[$r][1] = 'Creer/modifier/annuler les mouvements comptables';
		$this->rights[$r][2] = 'w';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'mouvements';
		$this->rights[$r][5] = 'creer';

		$this->rights[$r][0] = 50415;
		$this->rights[$r][1] = 'Lire CA, bilans, resultats, journaux, grands livres';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'comptarapport';
		$this->rights[$r][5] = 'lire';
	}


	/**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options = '') {
		// Prevent pb of modules not correctly disabled
		//$this->remove($options);

		$sql = array();

		return $this->_init($sql, $options);
	}

	/**
	 * 		Function called when module is enabled.
	 * 		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 * 		It also creates data directories
	 *
	 *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function remove($options = '') {
		global $conf;

		$sql = array("DELETE FROM " . MAIN_DB_PREFIX . "const where name='MAIN_COMPANY_CODE_ALWAYS_REQUIRED' and entity IN ('0','" . $conf->entity . "')");

		return $this->_remove($sql, $options);
	}
}
?>
