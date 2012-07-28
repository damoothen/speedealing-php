<?php
/* Copyright (C) 2012	Christophe Battarel	<christophe.battarel@altairis.fr>
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
 * 	\defgroup   commissions     Module Commissions
 * 	\brief      Example of a module descriptor.
 * 	\file       htdocs/core/modules/modCommissions.class.php
 * 	\ingroup    commissions
 * 	\brief      Description and activation file for module Commissions
 */
include_once(DOL_DOCUMENT_ROOT ."/core/modules/DolibarrModules.class.php");


/**
 * 	Class to describe modude Commisions
 */
class modCommissions extends DolibarrModules
{
    /**
     * 	Constructor
     *
     * 	@param	DoliDB	$db		Database handler
     */
	function __construct($db)
	{
		$this->db = $db;
		parent::__construct($db);

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->values->numero = 60000;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->values->rights_class = 'Commissions';

		// Family can be 'crm','financial','hr','projects','products','ecm','technic','other'
		// It is used to group modules in module setup page
		$this->values->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i','',get_class($this));
		// Module description, used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->description = "Commissions management";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->values->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Where to store the module in setup page (0=common,1=interface,2=other)
		$this->values->special = 0;
		// Name of png file (without png) used for this module.
		// Png file must be in theme/yourtheme/img directory under name object_pictovalue.png.
		$this->values->picto='commissions';

		// Data directories to create when module is enabled.
		$this->values->dirs = array();

		// Config pages. Put here list of php page names stored in admmin directory used to setup module.
		$this->values->config_page_url = array("commissions.php@commissions");

		// Dependencies
		$this->values->depends = array("modFacture", "modMargin");		// List of modules id that must be enabled if this module is enabled
		$this->values->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->values->phpmin = array(5,1);					// Minimum version of PHP required by module
		$this->values->need_dolibarr_version = array(3,2);	// Minimum version of Dolibarr required by module
		$this->values->langfiles = array("commissions");

		// Constants
		$this->values->const = array(0=>array('COMMISSION_BASE',"chaine","TURNOVER",'Default commission base',0));			// List of particular constants to add when module is enabled

		// New pages on tabs
		$this->values->tabs = array();

		// Boxes
		$this->values->values->boxes = array();			// List of boxes
		$r=0;

		// Permissions
		$this->rights = array();		// Permission array used by this module
		$r=0;

		// Add here list of permission defined by an id, a label, a boolean and two constant strings.
		// Example:
		// $this->rights[$r][0] = 2000; 				// Permission id (must not be already used)
		// $this->rights[$r][1] = 'Permision label';	// Permission label
		// $this->rights[$r][3] = 1; 					// Permission by default for new user (0/1)
		// $this->rights[$r][4] = 'level1';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		// $this->rights[$r][5] = 'level2';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		// $r++;


		// Main menu entries
		$this->values->menu = array();			// List of menus to add
		$r = 0;

		// left menu entry
		$r = 0;
		$this->values->menus[$r]->_id = "menu:commission";
		$this->values->menus[$r]->type = "left";
		$this->values->menus[$r]->position = 200;
		$this->values->menus[$r]->url = "/commissions/index.php";
		$this->values->menus[$r]->enabled = '$conf->commissions->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Commissions";
		$this->values->menus[$r]->fk_menu = "menu:accountancy";
		$r++;
 	}

	/**
     *	Function called when module is enabled.
     *	The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *	It also creates data directories.
     *
	 *  @return     int             1 if OK, 0 if KO
     */
	function init()
  	{
    	$sql = array();

		$result=$this->load_tables();

    	return $this->_init($sql);
  	}

	/**
	 *	Function called when module is disabled.
 	 *  Remove from database constants, boxes and permissions from Dolibarr database.
 	 *	Data directories are not deleted.
 	 *
	 *  @return     int             1 if OK, 0 if KO
 	 */
	function remove()
	{
    	$sql = array();

    	return $this->_remove($sql);
  	}


	/**
	 *	Create tables and keys required by module
	 * 	Files mymodule.sql and mymodule.key.sql with create table and create keys
	 * 	commands must be stored in directory /mymodule/sql/
	 *	This function is called by this->init.
	 *
	 * 	@return		int		<=0 if KO, >0 if OK
	 */
	function load_tables()
	{
		return;
	}
}

?>
