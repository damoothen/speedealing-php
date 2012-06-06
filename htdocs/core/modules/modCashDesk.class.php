<?php
/* Copyright (C) 2008-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \defgroup   pos       Module points of sale
 *      \brief      Module to manage points of sale
 *      \file       htdocs/core/modules/modCashDesk.class.php
 *      \ingroup    pos
 *      \brief      File to enable/disable module Point Of Sales
 */
include_once(DOL_DOCUMENT_ROOT ."/core/modules/DolibarrModules.class.php");


/**
 *       \class      modCashDesk
 *       \brief      Class to describe and enable module Point Of Sales
 */
class modCashDesk extends DolibarrModules
{
	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modCashDesk ($db)
	{
		parent::__construct($db);

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used module id).
		$this->values->numero = 50100;
		// Key text used to identify module (for permission, menus, etc...)
		$this->values->rights_class = 'cashdesk';

		$this->values->family = "products";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i','',get_class($this));
		$this->values->description = "CashDesk module";

		$this->values->revision = '1.27';
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_'.strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'list';

		// Data directories to create when module is enabled
		$this->values->dirs = array();

		// Config pages. Put here list of php page names stored in admmin directory used to setup module.
		$this->values->config_page_url = array("cashdesk.php@cashdesk");

		// Dependencies
		$this->values->depends = array("modBanque","modFacture","modProduct");	// List of modules id that must be enabled if this module is enabled
		$this->values->requiredby = array();			// List of modules id to disable if this one is disabled
		$this->values->phpmin = array(4,1);					// Minimum version of PHP required by module
		$this->values->need_dolibarr_version = array(2,4);	// Minimum version of Dolibarr required by module
		$this->values->langfiles = array("cashdesk");

		// Constantes
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'cashdesk';
		$r=0;

		$r++;
		$this->values->rights[$r][0] = 50101;
		$this->values->rights[$r][1] = 'Use point of sale';
		$this->values->rights[$r][2] = 'a';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'use';

		// Main menu entries
		$this->values->menus = array();			// List of menus to add
		$r=0;

		// This is to declare the Top Menu entry:
		$this->values->menu[$r]=array(	    'fk_menu'=>0,			// Put 0 if this is a top menu
									'type'=>'top',			// This is a Top menu entry
									'titre'=>'CashDeskMenu',
									'mainmenu'=>'cashdesk',
									'url'=>'/cashdesk/index.php?user=__LOGIN__',
									'langs'=>'cashdesk',	// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
									'position'=>100,
                                    'enabled'=>'$conf->cashdesk->enabled',
		                            'perms'=>'$user->rights->cashdesk->use',		// Use 'perms'=>'1' if you want your menu with no permission rules
									'target'=>'pointofsale',
									'user'=>0);				// 0=Menu for internal users, 1=external users, 2=both

		$r++;

		// This is to declare a Left Menu entry:
		// $this->values->menu[$r]=array(	'fk_menu'=>'r=0',		// Use r=value where r is index key used for the top menu entry
		//							'type'=>'left',			// This is a Left menu entry
		//							'titre'=>'Title left menu',
		//							'mainmenu'=>'mymodule',
		//							'url'=>'/comm/action/index2.php',
		//							'langs'=>'mylangfile',	// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
		//							'position'=>100,
		//							'perms'=>'$user->rights->mymodule->level1->level2',		// Use 'perms'=>'1' if you want your menu with no permission rules
		//							'target'=>'',
		//							'user'=>2);				// 0=Menu for internal users, 1=external users, 2=both
		// $r++;
	}


    /**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
     */
	function init($options='')
  	{
    	$sql = array();

		// Remove permissions and default values
		$this->values->remove($options);

    	return $this->values->_init($sql,$options);
  	}

    /**
     *  Function called when module is disabled.
     *  Remove from database constants, boxes and permissions from Dolibarr database.
     *  Data directories are not deleted.
     *
     *  @param	string	$options	Options
     *  @return int             	1 if OK, 0 if KO
     */
  	function remove($options='')
	{
    	$sql = array();

    	return $this->values->_remove($sql,$options);
  	}

}
?>
