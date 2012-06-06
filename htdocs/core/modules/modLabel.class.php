<?php

/* Copyright (C) 2007-2009 Regis Houssin       <regis@dolibarr.fr>
 * Copyright (C) 2008      Laurent Destailleur <eldy@users.sourceforge.net>
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
 * 	\defgroup   label         Module labels
 * 	\brief      Module pour gerer les formats d'impression des etiquettes
 * 	\file       htdocs/core/modules/modLabel.class.php
 * 	\ingroup    other
 * 	\brief      Fichier de description et activation du module Label
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modLabel
 * 	\brief      Classe de description et activation du module Label
 */
class modLabel extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modLabel($db) {
		parent::__construct($db);
		$this->values->numero = 60;

		$this->values->family = "other";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des etiquettes";
		$this->values->version = 'development';  // 'development' or 'experimental' or 'dolibarr' or version
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 2;
		$this->values->picto = 'label';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/label/temp");

		// Dependancies
		$this->values->depends = array();
		$this->values->requiredby = array();

		// Config pages
		$this->values->config_page_url = array("label.php");

		// Constants
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'label';

		$this->values->rights[1][0] = 601; // id de la permission
		$this->values->rights[1][1] = 'Lire les etiquettes'; // libelle de la permission
		$this->values->rights[1][3] = 1; // La permission est-elle une permission par defaut
		$this->values->rights[1][4] = 'lire';

		$this->values->rights[2][0] = 602; // id de la permission
		$this->values->rights[2][1] = 'Creer/modifier les etiquettes'; // libelle de la permission
		$this->values->rights[2][3] = 0; // La permission est-elle une permission par defaut
		$this->values->rights[2][4] = 'creer';

		$this->values->rights[4][0] = 609; // id de la permission
		$this->values->rights[4][1] = 'Supprimer les etiquettes'; // libelle de la permission
		$this->values->rights[4][3] = 0; // La permission est-elle une permission par defaut
		$this->values->rights[4][4] = 'supprimer';
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

}

?>
