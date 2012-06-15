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
		$this->values->numero = 22;

		$this->values->family = "technic";
// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des EMailings";
		$this->values->version = 'speedealing'; // 'experimental' or 'speedealing' or version
		$this->values->special = 0;
		$this->values->picto = 'email';

// Data directories to create when module is enabled
		$this->values->dirs = array("/mailing/temp");

// Dependances
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->langfiles = array("mails");

// Config pages
		$this->values->config_page_url = array("mailing.php");

// Constantes
		$this->values->const = array();

// Boites
		$this->values->boxes = array();

// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'mailing';

		$r = 0;
		$this->values->rights[$r]->id = 221; // id de la permission
		$this->values->rights[$r]->desc = 'Consulter les mailings'; // libelle de la permission
		$this->values->rights[$r]->default = 1; // La permission est-elle une permission par defaut
		$this->values->rights[$r]->perm = array('lire');
		$r++;
		$this->values->rights[$r]->id = 222;
		$this->values->rights[$r]->desc = 'Creer/modifier les mailings (sujet, destinataires...)';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('creer');
		$r++;
		$this->values->rights[$r]->id = 223;
		$this->values->rights[$r]->desc = 'Valider les mailings (permet leur envoi)';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('valider');
		$r++;
		$this->values->rights[$r]->id = 229;
		$this->values->rights[$r]->desc = 'Supprimer les mailings)';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('supprimer');
		$r++;
		$this->values->rights[$r]->id = 237;
		$this->values->rights[$r]->desc = 'View recipients and info';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('mailing_advance','recipient');
		$r++;
		$this->values->rights[$r]->id = 238;
		$this->values->rights[$r]->desc = 'Manually send mailings';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('mailing_advance','send');
		$r++;
		$this->values->rights[$r]->id = 239;
		$this->values->rights[$r]->desc = 'Delete mailings after validation and/or sent';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('mailing_advance','delete');

		$this->values->menu = array();   // List of menus to add
		$r = 0;
		$this->values->menus[$r]->_id = "menu:tools";
		$this->values->menus[$r]->type = "top";
		$this->values->menus[$r]->position = 8;
		$this->values->menus[$r]->url = "/core/tools.php";
		$this->values->menus[$r]->langs = "other";
		$this->values->menus[$r]->perms = '$user->rights->mailing->lire || $user->rights->export->lire || $user->rights->import->run';
		$this->values->menus[$r]->enabled = '$conf->Mailing->enabled || $conf->Export->enabled || $conf->Import->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Tools";
		$r++;
		$this->values->menus[$r]->_id = "menu:emailings";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/comm/mailing/index.php";
		$this->values->menus[$r]->langs = "mails";
		$this->values->menus[$r]->perms = '$user->rights->mailing->lire';
		$this->values->menus[$r]->enabled = '$conf->Mailing->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "EMailings";
		$this->values->menus[$r]->fk_menu = "menu:tools";
		$r++;
		$this->values->menus[$r]->_id = "menu:newmailing";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/comm/mailing/fiche.php?action=create";
		$this->values->menus[$r]->langs = "mails";
		$this->values->menus[$r]->perms = '$user->rights->mailing->creer';
		$this->values->menus[$r]->enabled = '$conf->Mailing->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "NewMailing";
		$this->values->menus[$r]->fk_menu = "menu:emailings";
		$r++;
		$this->values->menus[$r]->_id = "menu:list17";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/comm/mailing/liste.php";
		$this->values->menus[$r]->langs = "mails";
		$this->values->menus[$r]->perms = '$user->rights->mailing->lire';
		$this->values->menus[$r]->enabled = '$conf->Mailing->enabled';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "List";
		$this->values->menus[$r]->fk_menu = "menu:emailings";
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
