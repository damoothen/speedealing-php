<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2012 Laurent Destailleur  <eldy@users.sourceforge.org>
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
 * 	\defgroup   mailmanspip      Module mailmanspip
 * 	\brief      Module to manage mailman and spip
 * 	\file       htdocs/core/modules/modMailmanSpip.class.php
 * 	\ingroup    mailmanspip
 * 	\brief      Fichier de description et activation du module de click to Dial
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	Classe de description et activation du module de Click to Dial
 */
class modMailmanSpip extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modMailmanSpip($db) {
		parent::__construct($db);
		$this->values->numero = 105;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Mailman or Spip interface for member module";

		$this->values->version = 'dolibarr';  // 'development' or 'experimental' or 'dolibarr' or version

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 1;
		$this->values->picto = 'technic';

		// Data directories to create when module is enabled
		$this->values->dirs = array();

		// Dependencies
		$this->values->depends = array('modAdherent');
		$this->values->requiredby = array();

		// Config pages
		$this->values->config_page_url = array('mailman.php@adherents');

		// Constants
		$this->values->const = array();
		$this->values->const[1] = array("ADHERENT_MAILMAN_UNSUB_URL", "chaine", "http://lists.domain.com/cgi-bin/mailman/admin/%LISTE%/members?adminpw=%MAILMAN_ADMINPW%&user=%EMAIL%", "Url de désinscription aux listes mailman");
		$this->values->const[2] = array("ADHERENT_MAILMAN_URL", "chaine", "http://lists.domain.com/cgi-bin/mailman/admin/%LISTE%/members?adminpw=%MAILMAN_ADMINPW%&send_welcome_msg_to_this_batch=1&subscribees=%EMAIL%", "Url pour les inscriptions mailman");
		$this->values->const[3] = array("ADHERENT_MAILMAN_LISTS", "chaine", "", "Mailing-list to subscribe new members to");

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'clicktodial';
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
		global $conf;

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
