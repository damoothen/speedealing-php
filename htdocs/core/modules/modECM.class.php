<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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

/**     \defgroup   ecm		Module ecm
 *      \brief      Module for ECM (Electronic Content Management)
 *      \file       htdocs/core/modules/modECM.class.php
 *      \ingroup    ecm
 *      \brief      Description and activation file for module ECM
 */

include_once(DOL_DOCUMENT_ROOT ."/core/modules/DolibarrModules.class.php");


/**     \class      modECM
 *      \brief      Description and activation class for module ECM
 */
class modECM extends DolibarrModules
{

   /**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
    */
	function modECM($db)
	{
		parent::__construct($db);

		// Id for module (must be unique).
		// Use here a free id.
		$this->values->numero = 2500;

		// Family can be 'crm','financial','hr','projects','product','ecm','technic','other'
		// It is used to sort modules in module setup page
		$this->values->family = "ecm";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i','',get_class($this));
		// Module description used if translation string 'ModuleXXXDesc' not found (XXX is id value)
		$this->values->description = "Electronic Content Management";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';
		// Key used in llx_const table to save module status enabled/disabled (XXX is id value)
		$this->values->const_name = 'MAIN_MODULE_'.strtoupper($this->values->name);
		// Where to store the module in setup page (0=common,1=interface,2=other)
		$this->values->special = 0;
		// Name of png file (without png) used for this module
		$this->values->picto='dir';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/ecm/temp");

		// Config pages. Put here list of php page names stored in admmin directory used to setup module
		$this->values->config_page_url = array();

		// Dependencies
		$this->values->depends = array();		// List of modules id that must be enabled if this module is enabled
		$this->values->requiredby = array();	// List of modules id to disable if this one is disabled

		// Constants
		$this->values->const = array();			// List of parameters

		// Boxes
		$this->values->boxes = array();			// List of boxes
		$r=0;

		// Add here list of php file(s) stored in core/boxes that contains class to show a box.
		// Example:
        //$this->values->boxes[$r][1] = "myboxa.php";
    	//$r++;
        //$this->values->boxes[$r][1] = "myboxb.php";
    	//$r++;

		// Permissions
		$this->values->rights_class = 'ecm';	// Permission key
		$this->values->rights = array();		// Permission array used by this module

		$r++;
		$this->values->rights[$r][0] = 2501;
		$this->values->rights[$r][1] = 'Consulter/Télécharger les documents';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'read';

		$r++;
		$this->values->rights[$r][0] = 2503;
		$this->values->rights[$r][1] = 'Soumettre ou supprimer des documents';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'upload';

		$r++;
		$this->values->rights[$r][0] = 2515;
		$this->values->rights[$r][1] = 'Administrer les rubriques de documents';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'setup';


        // Menus
		//------
		$this->values->menus = array();			// List of menus to add
		$r=0;

		// Top menu
		$this->values->menu[$r]=array('fk_menu'=>0,
							  'type'=>'top',
							  'titre'=>'MenuECM',
							  'mainmenu'=>'ecm',
							  'url'=>'/ecm/index.php',
							  'langs'=>'ecm',
							  'position'=>100,
							  'perms'=>'$user->rights->ecm->read || $user->rights->ecm->upload || $user->rights->ecm->setup',
							  'enabled'=>'$conf->ecm->enabled',
							  'target'=>'',
							  'user'=>2);			// 0=Menu for internal users, 1=external users, 2=both
		$r++;

		// Left menu linked to top menu
		$this->values->menu[$r]=array('fk_menu'=>'r=0',
							  'type'=>'left',
							  'titre'=>'ECMArea',
							  'mainmenu'=>'ecm',
							  'url'=>'/ecm/index.php',
							  'langs'=>'ecm',
							  'position'=>101,
							  'perms'=>'$user->rights->ecm->read || $user->rights->ecm->upload',
							  'enabled'=>'$user->rights->ecm->read || $user->rights->ecm->upload',
							  'target'=>'',
							  'user'=>2);			// 0=Menu for internal users, 1=external users, 2=both
		$r++;

		$this->values->menu[$r]=array('fk_menu'=>'r=1',
							  'type'=>'left',
							  'titre'=>'ECMNewSection',
							  'mainmenu'=>'ecm',
							  'url'=>'/ecm/docdir.php?action=create',
							  'langs'=>'ecm',
							  'position'=>100,
							  'perms'=>'$user->rights->ecm->setup',
							  'enabled'=>'$user->rights->ecm->setup',
							  'target'=>'',
							  'user'=>2);			// 0=Menu for internal users, 1=external users, 2=both
		$r++;

		$this->values->menu[$r]=array('fk_menu'=>'r=1',
							  'type'=>'left',
							  'titre'=>'ECMFileManager',
							  'mainmenu'=>'ecm',
							  'url'=>'/ecm/index.php?action=file_manager',
							  'langs'=>'ecm',
							  'position'=>102,
							  'perms'=>'$user->rights->ecm->read || $user->rights->ecm->upload',
							  'enabled'=>'$user->rights->ecm->read || $user->rights->ecm->upload',
							  'target'=>'',
							  'user'=>2);			// 0=Menu for internal users, 1=external users, 2=both
		$r++;

		$this->values->menu[$r]=array('fk_menu'=>'r=1',
							  'type'=>'left',
							  'titre'=>'Search',
							  'mainmenu'=>'ecm',
							  'url'=>'/ecm/search.php',
							  'langs'=>'ecm',
							  'position'=>103,
							  'perms'=>'$user->rights->ecm->read',
							  'enabled'=>'$user->rights->ecm->read',
							  'target'=>'',
							  'user'=>2);			// 0=Menu for internal users, 1=external users, 2=both
		$r++;

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

    	return $this->values->_init($sql,$options);
  	}

    /**
	 *		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 *		Data directories are not deleted
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
     */
    function remove($options='')
    {
		$sql = array();

		return $this->values->_remove($sql,$options);
    }

}

?>
