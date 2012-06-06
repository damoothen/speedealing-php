<?php
/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 * 	\defgroup   oscommerce     Module oscommerce
 *	\brief      Module pour gerer une boutique et interface avec OSCommerce
 *  \file       htdocs/core/modules/modBoutique.class.php
 *  \ingroup    oscommerce
 *  \brief      Fichier de description et activation du module OSCommerce
 */

include_once(DOL_DOCUMENT_ROOT ."/core/modules/DolibarrModules.class.php");


/**
 *		\class 		modBoutique
 *		\brief      Classe de description et activation du module OSCommerce
 */
class modBoutique extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modBoutique($db)
	{		
		parent::__construct($db);
		$this->values->numero = 800;

		$this->values->family = "products";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i','',get_class($this));
		$this->values->description = "Interface de visualisation d'une boutique OSCommerce ou OSCSS";
		$this->values->version = 'dolibarr';                        // 'experimental' or 'dolibarr' or version
		$this->values->const_name = 'MAIN_MODULE_'.strtoupper($this->values->name);
		$this->values->special = 1;

		// Data directories to create when module is enabled
		$this->values->dirs = array();

		// Config pages
//		$this->values->config_page_url = array("boutique.php","osc-languages.php");
		$this->values->config_page_url = array("boutique.php@boutique");

		// Dependancies
		$this->values->depends = array();
		$this->values->requiredby = array();
	    $this->values->conflictwith = array("modOSCommerceWS");
	   	$this->values->langfiles = array("shop");

		// Constants
		$this->values->const = array();
		$r=0;

		$this->values->const[$r][0] = "OSC_DB_HOST";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "localhost";
		$this->values->const[$r][3] = "Host for OSC database for OSCommerce module 1";
		$this->values->const[$r][4] = 0;
		$r++;

	    // Boites
	    $this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'boutique';
	}

    /**
     *      Function called when module is enabled.
     *      The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *      It also creates data directories.
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
