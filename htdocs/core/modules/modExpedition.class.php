<?php

/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
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
 * 	\defgroup   expedition     Module shipping
 * 	\brief      Module pour gerer les expeditions de produits
 * 	\file       htdocs/core/modules/modExpedition.class.php
 * 	\ingroup    expedition
 * 	\brief      Fichier de description et activation du module Expedition
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class modExpedition
 * 	\brief      Classe de description et activation du module Expedition
 */
class modExpedition extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modExpedition($db) {
		parent::__construct($db);
		$this->values->numero = 80;

		$this->values->family = "crm";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des expeditions";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = "sending";

		// Data directories to create when module is enabled
		$this->values->dirs = array("/expedition/temp",
			"/expedition/sending",
			"/expedition/sending/temp",
			"/expedition/receipt",
			"/expedition/receipt/temp"
		);

		// Config pages
		$this->values->config_page_url = array("confexped.php");

		// Dependances
		$this->values->depends = array("modCommande");
		$this->values->requiredby = array();

		// Constantes
		$this->values->const = array();
		$r = 0;

		$this->values->const[$r][0] = "EXPEDITION_ADDON_PDF";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "rouget";
		$this->values->const[$r][3] = 'Nom du gestionnaire de generation des bons expeditions en PDF';
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "EXPEDITION_ADDON";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "elevement";
		$this->values->const[$r][3] = 'Nom du gestionnaire du type d\'expedition';
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "LIVRAISON_ADDON_PDF";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "typhon";
		$this->values->const[$r][3] = 'Nom du gestionnaire de generation des bons de reception en PDF';
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "LIVRAISON_ADDON";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "mod_livraison_jade";
		$this->values->const[$r][3] = 'Nom du gestionnaire de numerotation des bons de reception';
		$this->values->const[$r][4] = 0;
		$r++;

		$this->values->const[$r][0] = "EXPEDITION_ADDON_NUMBER";
		$this->values->const[$r][1] = "chaine";
		$this->values->const[$r][2] = "mod_expedition_safor";
		$this->values->const[$r][3] = 'Nom du gestionnaire de numerotation des expeditions';
		$this->values->const[$r][4] = 0;
		$r++;

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'expedition';
		$r = 0;

		$r++;
		$this->values->rights[$r][0] = 101;
		$this->values->rights[$r][1] = 'Lire les expeditions';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 102;
		$this->values->rights[$r][1] = 'Creer modifier les expeditions';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'creer';

		$r++;
		$this->values->rights[$r][0] = 104;
		$this->values->rights[$r][1] = 'Valider les expeditions';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'valider';

		$r++;
		$this->values->rights[$r][0] = 105; // id de la permission
		$this->values->rights[$r][1] = 'Envoyer les expeditions aux clients'; // libelle de la permission
		$this->values->rights[$r][2] = 'd'; // type de la permission (deprecie a ce jour)
		$this->values->rights[$r][3] = 0; // La permission est-elle une permission par defaut
		$this->values->rights[$r][4] = 'shipping_advance';
		$this->values->rights[$r][5] = 'send';

		$r++;
		$this->values->rights[$r][0] = 109;
		$this->values->rights[$r][1] = 'Supprimer les expeditions';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'supprimer';

		$r++;
		$this->values->rights[$r][0] = 1101;
		$this->values->rights[$r][1] = 'Lire les bons de livraison';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'livraison';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 1102;
		$this->values->rights[$r][1] = 'Creer modifier les bons de livraison';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'livraison';
		$this->values->rights[$r][5] = 'creer';

		$r++;
		$this->values->rights[$r][0] = 1104;
		$this->values->rights[$r][1] = 'Valider les bons de livraison';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'livraison';
		$this->values->rights[$r][5] = 'valider';

		$r++;
		$this->values->rights[$r][0] = 1109;
		$this->values->rights[$r][1] = 'Supprimer les bons de livraison';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'livraison';
		$this->values->rights[$r][5] = 'supprimer';
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

		// Permissions
		$this->values->remove($options);

		$sql = array();

		$sql = array(
			"DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = '" . $this->values->const[0][2] . "' AND entity = " . $conf->entity,
			"INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('" . $this->values->const[0][2] . "','shipping'," . $conf->entity . ")",
			"DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = '" . $this->values->const[1][2] . "' AND entity = " . $conf->entity,
			"INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('" . $this->values->const[1][2] . "','delivery'," . $conf->entity . ")",
		);

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
