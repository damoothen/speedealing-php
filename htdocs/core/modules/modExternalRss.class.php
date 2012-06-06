<?php

/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  \defgroup   externalrss     Module externalrss
 * 	\brief      Module pour inclure des informations externes RSS
 * 	\file       htdocs/core/modules/modExternalRss.class.php
 * 	\ingroup    externalrss
 * 	\brief      Fichier de description et activation du module externalrss
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**     \class      modExternalRss
 * 		\brief      Classe de description et activation du module externalrss
 */
class modExternalRss extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modExternalRss($db) {
		global $conf;

		parent::__construct($db);
		$this->values->numero = 320;

		$this->values->family = "technic";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Ajout de files d'informations RSS dans les ecrans Dolibarr";
		$this->values->version = 'dolibarr';						// 'experimental' or 'dolibarr' or version
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 1;
		$this->values->picto = 'rss';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/externalrss/temp");

		// Config pages
		$this->values->config_page_url = array("external_rss.php");

		// Dependances
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->phpmin = array(4, 2, 0);
		$this->values->phpmax = array();

		// Constantes
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();
		// Les boites sont ajoutees lors de la configuration des flux
		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'externalrss';
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

		// Recherche configuration de boites
		$this->values->boxes = array();
		$sql = "select name, value from " . MAIN_DB_PREFIX . "const";
		$sql.= " WHERE name like 'EXTERNAL_RSS_TITLE_%'";
		$sql.= " AND entity = " . $conf->entity;
		$result = $this->values->db->query($sql);
		if ($result) {
			while ($obj = $this->values->db->fetch_object($result)) {
				if (preg_match('/EXTERNAL_RSS_TITLE_([0-9]+)/i', $obj->name, $reg)) {
					// Definie la boite si on a trouvee une ancienne configuration
					$this->values->boxes[$reg[1]][0] = "(ExternalRSSInformations)";
					$this->values->boxes[$reg[1]][1] = "box_external_rss.php";
					$this->values->boxes[$reg[1]][2] = $reg[1] . " (" . $obj->value . ")";
				}
			}
			$this->values->db->free($result);
		}

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

		// Delete old declarations of RSS box
		$this->values->boxes[0][1] = "box_external_rss.php";

		return $this->values->_remove($sql, $options);
	}

}

?>
