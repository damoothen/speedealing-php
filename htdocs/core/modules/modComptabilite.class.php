<?php

/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
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
 * \defgroup   comptabilite     Module comptabilite
 * \brief      Module pour inclure des fonctions de comptabilite (gestion de comptes comptables et rapports)
 * \file       htdocs/core/modules/modComptabilite.class.php
 * \ingroup    comptabilite
 * \brief      Fichier de description et activation du module Comptabilite
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 		\class 		modComptabilite
 *      \brief      Classe de description et activation du module Comptabilite
 */
class modComptabilite extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modComptabilite($db) {
		global $conf;

		parent::__construct($db);
		$this->values->numero = 10;

		$this->values->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion sommaire de comptabilite";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = '';

		// Config pages
		$this->values->config_page_url = array("compta.php");

		// Dependances
		$this->values->depends = array("modFacture", "modBanque");
		$this->values->requiredby = array();
		$this->values->conflictwith = array("modAccounting");
		$this->values->langfiles = array("compta");

		// Constantes
		$this->values->const = array();

		// Data directories to create when module is enabled
		$this->values->dirs = array("/comptabilite/temp",
			"/comptabilite/rapport",
			"/comptabilite/export",
			"/comptabilite/bordereau"
		);

		// Boites
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'compta';
		$r = 0;

		$r++;
		$this->values->rights[$r][0] = 95;
		$this->values->rights[$r][1] = 'Lire CA, bilans, resultats';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'resultat';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 96;
		$this->values->rights[$r][1] = 'Parametrer la ventilation';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'ventilation';
		$this->values->rights[$r][5] = 'parametrer';

		$r++;
		$this->values->rights[$r][0] = 97;
		$this->values->rights[$r][1] = 'Lire les ventilations de factures';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'ventilation';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 98;
		$this->values->rights[$r][1] = 'Ventiler les lignes de factures';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'ventilation';
		$this->values->rights[$r][5] = 'creer';
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
