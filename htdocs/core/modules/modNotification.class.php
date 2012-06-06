<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 	\defgroup   notification	Module email notification
 * 	\brief      Module pour gerer les notifications (par mail ou autre)
 * 	\file       htdocs/core/modules/modNotification.class.php
 * 	\ingroup    notification
 * 	\brief      Fichier de description et activation du module Notification
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modNotification
 * 	\brief      Classe de description et activation du module Mailing
 */
class modNotification extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modNotification($db) {
		parent::__construct($db);
		$this->values->numero = 600;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des notifications (par mail) sur evenement Dolibarr";
		$this->values->version = 'dolibarr'; // 'experimental' or 'dolibarr' or version
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 1;
		$this->values->picto = 'email';

		// Data directories to create when module is enabled.
		$this->values->dirs = array();

		// Dependances
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->langfiles = array("mails");

		// Config pages
		$this->values->config_page_url = array("notification.php");

		// Constantes
		$this->values->const = array();

		// Boites
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'notification';
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
