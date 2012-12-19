<?php
/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	\defgroup   bookmark    Module bookmarks
 *	\brief      Module to manage Bookmarks
 *	\file       htdocs/core/modules/modBookmark.class.php
 *	\ingroup    bookmark
 *	\brief      Fichier de description et activation du module Bookmarks
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Bookmark
 */
class modBookmark extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		parent::__construct($db);
		$this->numero = 330;

		$this->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion des Bookmarks";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'dolibarr';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 2;
		$this->picto='bookmark';

		// Data directories to create when module is enabled
		$this->dirs = array();

		// Dependancies
		$this->depends = array();
		$this->requiredby = array();
		$this->langfiles = array("bookmarks");

		// Config pages
		$this->config_page_url = array('bookmark.php@bookmarks');

		// Constantes
		$this->const = array();

		// Boites
		$this->boxes = array();
		$this->boxes[0][1] = "box_bookmarks.php";

		// Permissions
		$this->rights = array();
		$this->rights_class = 'bookmark';
		$r=0;

		$r++;
		$this->rights[$r][0] = 331; // id de la permission
		$this->rights[$r][1] = 'Lire les bookmarks'; // libelle de la permission
		$this->rights[$r][2] = 'r'; // type de la permission (deprecie a ce jour)
		$this->rights[$r][3] = 1; // La permission est-elle une permission par defaut
		$this->rights[$r][4] = 'lire';

		$r++;
		$this->rights[$r][0] = 332; // id de la permission
		$this->rights[$r][1] = 'Creer/modifier les bookmarks'; // libelle de la permission
		$this->rights[$r][2] = 'r'; // type de la permission (deprecie a ce jour)
		$this->rights[$r][3] = 1; // La permission est-elle une permission par defaut
		$this->rights[$r][4] = 'creer';

		$r++;
		$this->rights[$r][0] = 333; // id de la permission
		$this->rights[$r][1] = 'Supprimer les bookmarks'; // libelle de la permission
		$this->rights[$r][2] = 'r'; // type de la permission (d�pr�ci� � ce jour)
		$this->rights[$r][3] = 1; // La permission est-elle une permission par d�faut
		$this->rights[$r][4] = 'supprimer';

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
