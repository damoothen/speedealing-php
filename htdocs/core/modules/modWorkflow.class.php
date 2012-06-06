<?php

/* Copyright (C) 2010-2012	Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010		Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011-2012 Herve Prot            <herve.prot@symeos.com>
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
 *      \defgroup   workflow     Module workflow
 *      \brief		Workflow management
 *      \file       htdocs/core/modules/modWorkflow.class.php
 *      \ingroup    workflow
 *      \brief      File to describe and activate module Workflow
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 *  \class      modWorkflow
 *  \brief      Classe de description et activation du module Workflow
 */
class modWorkflow extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db) {
		parent::__construct($db);

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->values->numero = 6000;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->values->rights_class = 'workflow';

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		// Module description, used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->description = "Workflow management";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		// Where to store the module in setup page (0=common,1=interface,2=others,3=very specific)
		$this->values->special = 2;
		// Name of png file (without png) used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		$this->values->picto = 'technic';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/workflow/temp");

		// Config pages. Put here list of php page names stored in admmin directory used to setup module.
		$this->values->config_page_url = array('workflow.php');

		// Dependencies
		$this->values->depends = array();	   // List of modules id that must be enabled if this module is enabled
		$this->values->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->values->phpmin = array(5, 2);				 // Minimum version of PHP required by module
		$this->values->need_dolibarr_version = array(2, 8);  // Minimum version of Dolibarr required by module
		$this->values->langfiles = array("@workflow");

		// Constants
		// List of particular constants to add when module is enabled
		//Example: $this->values->const=array(0=>array('MODULE_MY_NEW_CONST1','chaine','myvalue','This is a constant to add',0),
		//                            1=>array('MODULE_MY_NEW_CONST2','chaine','myvalue','This is another constant to add',0) );
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();
		//$this->values->boxes[0][1] = "box_workflow@workflow";
		// Permissions
		$this->values->rights = array();
		$r = 0;

		/*
		  $r++;
		  $this->values->rights[$r][0] = 6001; // id de la permission
		  $this->values->rights[$r][1] = "Lire les workflow"; // libelle de la permission
		  $this->values->rights[$r][2] = 'r'; // type de la permission (deprecie a ce jour)
		  $this->values->rights[$r][3] = 1; // La permission est-elle une permission par defaut
		  $this->values->rights[$r][4] = 'read';
		 */

		// Main menu entries
		$this->values->menus = array();		 // List of menus to add
		$r = 0;
		/*
		  $this->values->menu[$r]=array('fk_menu'=>0,
		  'type'=>'top',
		  'titre'=>'Workflow',
		  'mainmenu'=>'workflow',
		  'url'=>'/workflow/index.php',
		  'langs'=>'@workflow',
		  'position'=>100,
		  'perms'=>'$user->rights->workflow->read',
		  'enabled'=>'$conf->workflow->enabled',
		  'target'=>'',
		  'user'=>0);
		  $r++;

		  $this->values->menu[$r]=array(  'fk_menu'=>'r=0',
		  'type'=>'left',
		  'titre'=>'Workflow',
		  'mainmenu'=>'workflow',
		  'url'=>'/workflow/index.php',
		  'langs'=>'@workflow',
		  'position'=>101,
		  'enabled'=>1,
		  'perms'=>'$user->rights->workflow->read',
		  'target'=>'',
		  'user'=>0);
		  $r++;
		 */
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

	/**
	 *      Create tables and keys required by module
	 *      This function is called by this->init.
	 *
	 *      @return     int     <=0 if KO, >0 if OK
	 */
	function load_tables() {
		return $this->values->_load_tables('/workflow/sql/');
	}

}

?>
