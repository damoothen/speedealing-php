<?php
/* Copyright (C) 2007      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
 *	\defgroup   	document     Module mass mailings
 *	\brief      	Module pour gerer des generations de documents
 *	\file       	htdocs/core/modules/modDocument.class.php
 *	\ingroup    	document
 *	\brief      	Fichier de description et activation du module Generation document
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Document
 */
class modDocument extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		parent::__construct($db);
		$this->numero = 51;

		$this->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Generation de courriers/publipostages papiers";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'development';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 0;
		$this->picto='email';

		// Data directories to create when module is enabled
		$this->dirs = array("/document/temp");

		// Config pages
		//$this->config_page_url = array("document.php");

		// Dependencies
		$this->depends = array();
		$this->requiredby = array();
		$this->conflictwith = array();
		$this->langfiles = array("orders","bills","companies");

		// Constantes

		$this->const = array();

		// Boites
		$this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'document';

		$r=0;

		$this->rights[$r][0] = 511;
		$this->rights[$r][1] = 'Lire les documents';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'lire';

		$r++;
		$this->rights[$r][0] = 512;
		$this->rights[$r][1] = 'Supprimer les documents clients';
		$this->rights[$r][2] = 'd';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'supprimer';
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
		global $conf;

		// Permissions
		$this->remove($options);

		$sql = array();

		return $this->_init($sql,$options);
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

		return $this->_remove($sql,$options);
    }

}
?>
