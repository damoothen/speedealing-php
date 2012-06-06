<?php

/* Copyright (C) 2005-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  \defgroup   import      Module import
 *  \brief      Module to make generic import of data into dolibarr database
 * 	\file       htdocs/core/modules/modImport.class.php
 * 	\ingroup    import
 * 	\brief      Fichier de description et activation du module Import
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**     \class      modImport
 * 		\brief      Classe de description et activation du module Import
 */
class modImport extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modImport($db) {
		parent::__construct($db);
		$this->values->numero = 250;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Outils d'imports de donnees Dolibarr (via un assistant)";
		// Possible values for version are: 'experimental' or 'dolibarr' or version
		$this->values->version = 'dolibarr';						// 'experimental' or 'dolibarr' or version
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'technic';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/import/temp");

		// Config pages
		$this->values->config_page_url = array();

		// D�pendances
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->phpmin = array(4, 3, 0); // Need auto_detect_line_endings php option to solve MAC pbs.
		$this->values->phpmax = array();
		$this->values->need_dolibarr_version = array(2, 7, -1); // Minimum version of Dolibarr required by module
		$this->values->need_javascript_ajax = 1;

		// Constantes
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'import';
		$r = 0;

		$r++;
		$this->values->rights[$r][0] = 1251;
		$this->values->rights[$r][1] = 'Run mass imports of external data (data load)';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'run';
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
