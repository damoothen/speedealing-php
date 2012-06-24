<?php

/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   user  Module user management
 * 	\brief      Module pour gerer les utilisateurs
 * 	\file       htdocs/core/modules/modUser.class.php
 * 	\ingroup    user
 * 	\brief      Fichier de description et activation du module Utilisateur
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class      modUser
 * 	\brief      Classe de description et activation du module User
 */
class modUser extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modUser($db) {
		global $conf;

		//$this->couchdb = $db; // Just for first install
		parent::__construct($db);
		$this->values->numero = 0;

		$this->values->family = "base";  // Family for module (or "base" if core module)
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des utilisateurs (requis)";
		$this->values->always_enabled = 1; // Can't be disabled
		// Possible values for version are: 'development', 'experimental', 'speedealing' or version
		$this->values->version = 'speedealing';

		$this->values->special = 0;
		$this->values->picto = 'group';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/users/temp");

		// Config pages
		$this->values->config_page_url = array("user.php");

		// Dependancies
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->langfiles = array("main", "users", "companies");

		// Constants
		$this->values->const = array();

		// Boxes
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'user';
		$this->values->rights_admin_allowed = 1; // Admin is always granted of permission (even when module is disabled)
		$r = 0;

		$this->values->rights[$r]->id = 251;
		$this->values->rights[$r]->desc = 'Consulter les autres utilisateurs';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user', 'lire');

		$r++;
		$this->values->rights[$r]->id = 252;
		$this->values->rights[$r]->desc = 'Consulter les permissions des autres utilisateurs';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user_advance', 'readperms');

		$r++;
		$this->values->rights[$r]->id = 253;
		$this->values->rights[$r]->desc = 'Creer/modifier utilisateurs internes et externes';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user', 'creer');

		$r++;
		$this->values->rights[$r]->id = 254;
		$this->values->rights[$r]->desc = 'Creer/modifier utilisateurs externes seulement';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user_advance', 'write');

		$r++;
		$this->values->rights[$r]->id = 255;
		$this->values->rights[$r]->desc = 'Modifier le mot de passe des autres utilisateurs';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user', 'password');

		$r++;
		$this->values->rights[$r]->id = 256;
		$this->values->rights[$r]->desc = 'Supprimer ou desactiver les autres utilisateurs';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user', 'supprimer');

		$r++;
		$this->values->rights[$r]->id = 341;
		$this->values->rights[$r]->desc = 'Consulter ses propres permissions';
		$this->values->rights[$r]->default = 1;
		$this->values->rights[$r]->perm = array('self_advance', 'readperms');

		$r++;
		$this->values->rights[$r]->id = 342;
		$this->values->rights[$r]->desc = 'Creer/modifier ses propres infos utilisateur';
		$this->values->rights[$r]->default = 1;
		$this->values->rights[$r]->perm = array('self', 'creer');

		$r++;
		$this->values->rights[$r]->id = 343;
		$this->values->rights[$r]->desc = 'Modifier son propre mot de passe';
		$this->values->rights[$r]->default = 1;
		$this->values->rights[$r]->perm = array('self', 'password');

		$r++;
		$this->values->rights[$r]->id = 344;
		$this->values->rights[$r]->desc = 'Modifier ses propres permissions';
		$this->values->rights[$r]->default = 1;
		$this->values->rights[$r]->perm = array('self_advance', 'writeperms');

		$r++;
		$this->values->rights[$r]->id = 351;
		$this->values->rights[$r]->desc = 'Consulter les groupes';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('group_advance', 'read');

		$r++;
		$this->values->rights[$r]->id = 352;
		$this->values->rights[$r]->desc = 'Consulter les permissions des groupes';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('group_advance', 'readperms');

		$r++;
		$this->values->rights[$r]->id = 353;
		$this->values->rights[$r]->desc = 'Creer/modifier les groupes et leurs permissions';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('group_advance', 'write');

		$r++;
		$this->values->rights[$r]->id = 354;
		$this->values->rights[$r]->desc = 'Supprimer ou desactiver les groupes';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('group_advance', 'delete');

		$r++;
		$this->values->rights[$r]->id = 358;
		$this->values->rights[$r]->desc = 'Exporter les utilisateurs';
		$this->values->rights[$r]->default = 0;
		$this->values->rights[$r]->perm = array('user', 'export');

		// Menus
		$r = 0;
		$this->values->menus[$r]->_id = "menu:home";
		$this->values->menus[$r]->type = "top";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/index.php";
		$this->values->menus[$r]->enabled = "1";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Home";

		$r++;
		$this->values->menus[$r]->_id = "menu:setup";
		$this->values->menus[$r]->url = "/admin/index.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->position = 0;
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->enabled = "$user->admin";
		$this->values->menus[$r]->title = "Setup";
		$this->values->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->values->menus[$r]->_id = "menu:systeminfo";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/admin/system/index.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->enabled = "$user->admin";
		$this->values->menus[$r]->title = "SystemInfo";
		$this->values->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->values->menus[$r]->_id = "menu:systemtools";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/admin/tools/index.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->enabled = "$user->admin";
		$this->values->menus[$r]->title = "SystemTools";
		$this->values->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->values->menus[$r]->_id = "menu:menuusersandgroups";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/user/home.php";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->enabled = "$user->admin";
		$this->values->menus[$r]->title = "MenuUsersAndGroups";
		$this->values->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->values->menus[$r]->_id = "menu:menucompanysetup";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/admin/company.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "MenuCompanySetup";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:modules";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/admin/modules.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Modules";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:menus";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/admin/menus.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Menus";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:guisetup";
		$this->values->menus[$r]->position = 4;
		$this->values->menus[$r]->url = "/admin/ihm.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "GUISetup";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:boxes";
		$this->values->menus[$r]->position = 5;
		$this->values->menus[$r]->url = "/admin/boxes.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Boxes";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:alerts";
		$this->values->menus[$r]->position = 6;
		$this->values->menus[$r]->url = "/admin/delais.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Alerts";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:security";
		$this->values->menus[$r]->position = 7;
		$this->values->menus[$r]->url = "/admin/proxy.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Security";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:menulimits";
		$this->values->menus[$r]->position = 8;
		$this->values->menus[$r]->url = "/admin/limits.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "MenuLimits";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:pdf";
		$this->values->menus[$r]->position = 9;
		$this->values->menus[$r]->url = "/admin/pdf.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "PDF";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:emails";
		$this->values->menus[$r]->position = 10;
		$this->values->menus[$r]->url = "/admin/mails.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Emails";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:sms";
		$this->values->menus[$r]->position = 11;
		$this->values->menus[$r]->url = "/admin/sms.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Sms";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:dictionnarysetup";
		$this->values->menus[$r]->position = 12;
		$this->values->menus[$r]->url = "/admin/dict.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "DictionnarySetup";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:othersetup";
		$this->values->menus[$r]->position = 13;
		$this->values->menus[$r]->url = "/admin/const.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "OtherSetup";
		$this->values->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->values->menus[$r]->_id = "menu:dolibarr";
		$this->values->menus[$r]->url = "/admin/system/dolibarr.php";
		$this->values->menus[$r]->position = 0;
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Dolibarr";
		$this->values->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;
		
		$this->values->menus[$r]->_id = "menu:memcached";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/memcached/admin/memcached.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Memcached";
		$this->values->menus[$r]->enabled = '$conf->memcached->host';
		$this->values->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;

		$this->values->menus[$r]->_id = "menu:os";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/admin/system/os.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "OS";
		$this->values->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;

		$this->values->menus[$r]->_id = "menu:webserver";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/admin/system/web.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "WebServer";
		$this->values->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;

		$this->values->menus[$r]->_id = "menu:php";
		$this->values->menus[$r]->position = 4;
		$this->values->menus[$r]->url = "/admin/system/phpinfo.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Php";
		$this->values->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;

		$this->values->menus[$r]->_id = "menu:database";
		$this->values->menus[$r]->position = 5;
		$this->values->menus[$r]->url = "/admin/system/database.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Database";
		$this->values->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;

		$this->values->menus[$r]->_id = "menu:allparameters";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/admin/system/constall.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "AllParameters";
		$this->values->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->values->menus[$r]->_id = "menu:modules0";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/admin/system/modules.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Modules";
		$this->values->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->values->menus[$r]->_id = "menu:triggers";
		$this->values->menus[$r]->position = 3;
		$this->values->menus[$r]->url = "/admin/triggers.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Triggers";
		$this->values->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->values->menus[$r]->_id = "menu:about";
		$this->values->menus[$r]->position = 4;
		$this->values->menus[$r]->url = "/admin/system/about.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "About";
		$this->values->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->values->menus[$r]->_id = "menu:backup";
		$this->values->menus[$r]->url = "/admin/tools/dolibarr_export.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Backup";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:restore";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/admin/tools/dolibarr_import.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Restore";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:menuupgrade";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/admin/tools/update.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "MenuUpgrade";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:audit";
		$this->values->menus[$r]->position = 4;
		$this->values->menus[$r]->url = "/admin/tools/listevents.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Audit";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:sessions";
		$this->values->menus[$r]->position = 5;
		$this->values->menus[$r]->url = "/admin/tools/listsessions.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Sessions";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:purge";
		$this->values->menus[$r]->position = 6;
		$this->values->menus[$r]->url = "/admin/tools/purge.php";
		$this->values->menus[$r]->langs = "admin";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Purge";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:helpcenter";
		$this->values->menus[$r]->position = 7;
		$this->values->menus[$r]->url = "/support/index.php";
		$this->values->menus[$r]->langs = "help";
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "HelpCenter";
		$this->values->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->values->menus[$r]->_id = "menu:users";
		$this->values->menus[$r]->url = "/user/index.php";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->perms = '$user->rights->user->user->lire || $user->admin';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Users";
		$this->values->menus[$r]->fk_menu = "menu:menuusersandgroups";
		$r++;

		$this->values->menus[$r]->_id = "menu:groups";
		$this->values->menus[$r]->position = 1;
		$this->values->menus[$r]->url = "/user/group/index.php";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->perms = '($conf->global->MAIN_USE_ADVANCED_PERMS?$user->rights->user->group_advance->read:$user->rights->user->user->lire) || $user->admin';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Groups";
		$this->values->menus[$r]->fk_menu = "menu:menuusersandgroups";
		$r++;

		$this->values->menus[$r]->_id = "menu:databases";
		$this->values->menus[$r]->position = 2;
		$this->values->menus[$r]->url = "/user/database/index.php";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->perms = '($user->rights->user->user->lire) || $user->admin';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "Databases";
		$this->values->menus[$r]->fk_menu = "menu:menuusersandgroups";
		$r++;

		$this->values->menus[$r]->_id = "menu:newuser";
		$this->values->menus[$r]->url = "/user/fiche.php?action=create";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->perms = '$user->rights->user->user->creer || $user->admin';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "NewUser";
		$this->values->menus[$r]->fk_menu = "menu:users";
		$r++;

		$this->values->menus[$r]->_id = "menu:newgroup";
		$this->values->menus[$r]->url = "/user/group/fiche.php?action=create";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->perms = '$user->rights->user->group_advance->write || $user->admin';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "NewGroup";
		$this->values->menus[$r]->fk_menu = "menu:groups";
		$r++;

		$this->values->menus[$r]->_id = "menu:newdatabase";
		$this->values->menus[$r]->url = "/user/database/fiche.php?action=create";
		$this->values->menus[$r]->langs = "users";
		$this->values->menus[$r]->perms = '$user->admin';
		$this->values->menus[$r]->usertype = 2;
		$this->values->menus[$r]->title = "NewDatabase";
		$this->values->menus[$r]->fk_menu = "menu:databases";


		// Exports
		//--------
		$r = 0;

		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'Liste des utilisateurs Dolibarr et attributs';
		$this->values->export_permission[$r] = array(array("user",
				"user",
				"export"));
		$this->values->export_fields_array[$r] = array('u.rowid' => "Id",
			'u.login' => "Login",
			'u.name' => "Lastname",
			'u.firstname' => "Firstname",
			'u.office_phone' => 'Tel', 'u.office_fax' => 'Fax', 'u.email' => 'EMail', 'u.datec' => "DateCreation",
			'u.tms' => "DateLastModification",
			'u.admin' => "Admin",
			'u.statut' => 'Status', 'u.note' => "Note",
			'u.datelastlogin' => 'LastConnexion', 'u.datepreviouslogin' => 'PreviousConnexion', 'u.fk_socpeople' => "IdContact",
			'u.fk_societe' => "IdCompany",
			'u.fk_member' => "MemberId");
		$this->values->export_entities_array[$r] = array('u.rowid' => "user",
			'u.login' => "user",
			'u.name' => "user",
			'u.firstname' => "user",
			'u.office_phone' => 'user', 'u.office_fax' => 'user', 'u.email' => 'user', 'u.datec' => "user",
			'u.tms' => "user",
			'u.admin' => "user",
			'u.statut' => 'user', 'u.note' => "user",
			'u.datelastlogin' => 'user', 'u.datepreviouslogin' => 'user', 'u.fk_socpeople' => "contact",
			'u.fk_societe' => "company",
			'u.fk_member' => "member");
		if (empty($conf->adherent->enabled)) {
			unset($this->values->export_fields_array[$r]['u.fk_member']);
			unset($this->values->export_entities_array[$r]['u.fk_member']);
		}
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'user as u';
		$this->values->export_sql_end[$r] .=' WHERE u.entity IN (0,' . $conf->entity . ')';
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
		//$this->remove($options);
		//$result=$this->values->create_view();

		$this->_load_documents('/user/json/');

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
