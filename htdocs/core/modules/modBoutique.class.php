<?php
/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
	function __construct($db)
	{		
		parent::__construct($db);
		$this->numero = 800;

		$this->family = "products";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Interface de visualisation d'une boutique OSCommerce ou OSCSS";
		$this->version = 'dolibarr';                        // 'experimental' or 'dolibarr' or version
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 1;

		// Data directories to create when module is enabled
		$this->dirs = array();

		// Config pages
//		$this->config_page_url = array("boutique.php","osc-languages.php");
		$this->config_page_url = array("boutique.php@boutique");

		// Dependancies
		$this->depends = array();
		$this->requiredby = array();
	    $this->conflictwith = array("modOSCommerceWS");
	   	$this->langfiles = array("shop");

		// Constants
		$this->const = array();
		$r=0;

		$this->const[$r][0] = "OSC_DB_HOST";
		$this->const[$r][1] = "chaine";
		$this->const[$r][2] = "localhost";
		$this->const[$r][3] = "Host for OSC database for OSCommerce module 1";
		$this->const[$r][4] = 0;
		$r++;

	    // Boites
	    $this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'boutique';
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
