<?php

/* Copyright (C) 2007      Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 * or see http://www.gnu.org/
 */

/**
 * 	\defgroup   	document     Module mass mailings
 * 	\brief      	Module pour gerer des generations de documents
 * 	\file       	htdocs/core/modules/modDocument.class.php
 * 	\ingroup    	document
 * 	\brief      	Fichier de description et activation du module Generation document
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modDocument
 * 	\brief      Classe de description et activation du module Document
 */
class modDocument extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modDocument($db) {
		parent::__construct($db);
		$this->values->numero = 51;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Generation de courriers/publipostages papiers";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'development';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'email';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/document/temp");

		// Config pages
		//$this->values->config_page_url = array("document.php");
		// Dependencies
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->conflictwith = array();
		$this->values->langfiles = array("orders", "bills", "companies");

		// Constantes

		$this->values->const = array();

		// Boites
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'document';

		$r = 0;

		$this->values->rights[$r][0] = 511;
		$this->values->rights[$r][1] = 'Lire les documents';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 512;
		$this->values->rights[$r][1] = 'Supprimer les documents clients';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'supprimer';
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
