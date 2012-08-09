<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   mailing  Module emailing
 * 	\brief      Module to manage EMailings
 * 	\file       htdocs/core/modules/modMailing.class.php
 * 	\ingroup    mailing
 * 	\brief      Fichier de description et activation du module Mailing
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modMailing
 * 	\brief      Classe de description et activation du module Mailing
 */
class modMailing extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modMailing($db) {
		parent::__construct($db);
		$this->numero = 22;

		$this->family = "technic";
// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		$this->description = "Gestion des EMailings";
		$this->version = 'speedealing'; // 'experimental' or 'speedealing' or version
		$this->special = 0;
		$this->picto = 'email';

// Data directories to create when module is enabled
		$this->dirs = array("/mailing/temp");

// Dependances
		$this->depends = array();
		$this->requiredby = array();
		$this->langfiles = array("mails");

// Config pages
		$this->config_page_url = array("mailing.php");

// Constantes
		$this->const = array();

// Boites
		$this->boxes = array();

// Permissions
		$this->rights = array();
		$this->rights_class = 'mailing';

		$r = 0;
		$this->rights[$r]->id = 221; // id de la permission
		$this->rights[$r]->desc = 'Consulter les mailings'; // libelle de la permission
		$this->rights[$r]->default = 1; // La permission est-elle une permission par defaut
		$this->rights[$r]->perm = array('lire');
		$r++;
		$this->rights[$r]->id = 222;
		$this->rights[$r]->desc = 'Creer/modifier les mailings (sujet, destinataires...)';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('creer');
		$r++;
		$this->rights[$r]->id = 223;
		$this->rights[$r]->desc = 'Valider les mailings (permet leur envoi)';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('valider');
		$r++;
		$this->rights[$r]->id = 229;
		$this->rights[$r]->desc = 'Supprimer les mailings)';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('supprimer');
		$r++;
		$this->rights[$r]->id = 237;
		$this->rights[$r]->desc = 'View recipients and info';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('mailing_advance','recipient');
		$r++;
		$this->rights[$r]->id = 238;
		$this->rights[$r]->desc = 'Manually send mailings';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('mailing_advance','send');
		$r++;
		$this->rights[$r]->id = 239;
		$this->rights[$r]->desc = 'Delete mailings after validation and/or sent';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('mailing_advance','delete');

		$this->menu = array();   // List of menus to add
		$r = 0;
		$this->menus[$r]->_id = "menu:tools";
		$this->menus[$r]->type = "top";
		$this->menus[$r]->position = 8;
		$this->menus[$r]->url = "/core/tools.php";
		$this->menus[$r]->langs = "other";
		$this->menus[$r]->perms = '$user->rights->mailing->lire || $user->rights->export->lire || $user->rights->import->run';
		$this->menus[$r]->enabled = '$conf->Mailing->enabled || $conf->Export->enabled || $conf->Import->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Tools";
		$r++;
		$this->menus[$r]->_id = "menu:emailings";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/comm/mailing/index.php";
		$this->menus[$r]->langs = "mails";
		$this->menus[$r]->perms = '$user->rights->mailing->lire';
		$this->menus[$r]->enabled = '$conf->Mailing->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "EMailings";
		$this->menus[$r]->fk_menu = "menu:tools";
		$r++;
		$this->menus[$r]->_id = "menu:newmailing";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/comm/mailing/fiche.php?action=create";
		$this->menus[$r]->langs = "mails";
		$this->menus[$r]->perms = '$user->rights->mailing->creer';
		$this->menus[$r]->enabled = '$conf->Mailing->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "NewMailing";
		$this->menus[$r]->fk_menu = "menu:emailings";
		$r++;
		$this->menus[$r]->_id = "menu:list17";
		$this->menus[$r]->position = 2;
		$this->menus[$r]->url = "/comm/mailing/liste.php";
		$this->menus[$r]->langs = "mails";
		$this->menus[$r]->perms = '$user->rights->mailing->lire';
		$this->menus[$r]->enabled = '$conf->Mailing->enabled';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "List";
		$this->menus[$r]->fk_menu = "menu:emailings";
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
		$this->remove($options);

		$sql = array();

		return $this->_init($sql, $options);
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

		return $this->_remove($sql, $options);
	}

}

?>
