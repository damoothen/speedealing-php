<?php

/* Copyright (C) 2009 Laurent Destailleur		<eldy@users.sourceforge.net>
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
 *      \defgroup   webservices     Module webservices
 *      \brief      Module to enable the Dolibarr server of web services
 *       \file       htdocs/core/modules/modWebServices.class.php
 *       \ingroup    webservices
 *       \brief      File to describe webservices module
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 *       \class      modWebServices
 *       \brief      Class to describe a WebServices module
 */
class modWebServices extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modWebServices($db) {
		parent::__construct($db);
		$this->values->numero = 2600;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Enable the Dolibarr web services server";
		$this->values->version = 'dolibarr';						// 'experimental' or 'dolibarr' or version
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		// Where to store the module in setup page (0=common,1=interface,2=others,3=very specific)
		$this->values->special = 1;
		// Name of image file used for this module.
		$this->values->picto = 'technic';

		// Data directories to create when module is enabled
		$this->values->dirs = array();

		// Config pages
		//-------------
		$this->values->config_page_url = array("webservices.php@webservices");

		// Dependancies
		//-------------
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->langfiles = array("other");

		// Constantes
		//-----------
		$this->values->const = array();

		// New pages on tabs
		// -----------------
		$this->values->tabs = array();

		// Boxes
		//------
		$this->values->boxes = array();

		// Permissions
		//------------
		$this->values->rights = array();
		$this->values->rights_class = 'webservices';
		$r = 0;
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
		// Prevent pb of modules not correctly disabled
		//$this->values->remove($options);

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
