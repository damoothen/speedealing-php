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
	function __construct($db) {
		global $conf;

		//$this->couchdb = $db; // Just for first install
		parent::__construct($db);
		$this->numero = 0;

		$this->family = "base";  // Family for module (or "base" if core module)
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		$this->description = "Gestion des utilisateurs (requis)";
		$this->always_enabled = 1; // Can't be disabled
		// Possible values for version are: 'development', 'experimental', 'speedealing' or version
		$this->version = 'speedealing';

		$this->special = 0;
		$this->picto = 'group';

		// Data directories to create when module is enabled
		$this->dirs = array("/users/temp");

		// Config pages
		$this->config_page_url = array("user.php");

		// Dependancies
		$this->depends = array();
		$this->requiredby = array();
		$this->langfiles = array("main", "users", "companies");

		// Constants
		$this->const = array();

		// Boxes
		$this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'user';
		$this->rights_admin_allowed = 1; // Admin is always granted of permission (even when module is disabled)
		$r = 0;

		$this->rights[$r]->id = 251;
		$this->rights[$r]->desc = 'Consulter les autres utilisateurs';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user', 'lire');

		$r++;
		$this->rights[$r]->id = 252;
		$this->rights[$r]->desc = 'Consulter les permissions des autres utilisateurs';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user_advance', 'readperms');

		$r++;
		$this->rights[$r]->id = 253;
		$this->rights[$r]->desc = 'Creer/modifier utilisateurs internes et externes';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user', 'creer');

		$r++;
		$this->rights[$r]->id = 254;
		$this->rights[$r]->desc = 'Creer/modifier utilisateurs externes seulement';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user_advance', 'write');

		$r++;
		$this->rights[$r]->id = 255;
		$this->rights[$r]->desc = 'Modifier le mot de passe des autres utilisateurs';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user', 'password');

		$r++;
		$this->rights[$r]->id = 256;
		$this->rights[$r]->desc = 'Supprimer ou desactiver les autres utilisateurs';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user', 'supprimer');

		$r++;
		$this->rights[$r]->id = 341;
		$this->rights[$r]->desc = 'Consulter ses propres permissions';
		$this->rights[$r]->default = 1;
		$this->rights[$r]->perm = array('self_advance', 'readperms');

		$r++;
		$this->rights[$r]->id = 342;
		$this->rights[$r]->desc = 'Creer/modifier ses propres infos utilisateur';
		$this->rights[$r]->default = 1;
		$this->rights[$r]->perm = array('self', 'creer');

		$r++;
		$this->rights[$r]->id = 343;
		$this->rights[$r]->desc = 'Modifier son propre mot de passe';
		$this->rights[$r]->default = 1;
		$this->rights[$r]->perm = array('self', 'password');

		$r++;
		$this->rights[$r]->id = 344;
		$this->rights[$r]->desc = 'Modifier ses propres permissions';
		$this->rights[$r]->default = 1;
		$this->rights[$r]->perm = array('self_advance', 'writeperms');

		$r++;
		$this->rights[$r]->id = 351;
		$this->rights[$r]->desc = 'Consulter les groupes';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('group_advance', 'read');

		$r++;
		$this->rights[$r]->id = 352;
		$this->rights[$r]->desc = 'Consulter les permissions des groupes';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('group_advance', 'readperms');

		$r++;
		$this->rights[$r]->id = 353;
		$this->rights[$r]->desc = 'Creer/modifier les groupes et leurs permissions';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('group_advance', 'write');

		$r++;
		$this->rights[$r]->id = 354;
		$this->rights[$r]->desc = 'Supprimer ou desactiver les groupes';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('group_advance', 'delete');

		$r++;
		$this->rights[$r]->id = 358;
		$this->rights[$r]->desc = 'Exporter les utilisateurs';
		$this->rights[$r]->default = 0;
		$this->rights[$r]->perm = array('user', 'export');

		// Menus
		$r = 0;
		$this->menus[$r]->_id = "menu:home";
		$this->menus[$r]->type = "top";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/index.php";
		$this->menus[$r]->enabled = "1";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Home";

		$r++;
		$this->menus[$r]->_id = "menu:setup";
		$this->menus[$r]->url = "/admin/index.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->position = 0;
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->enabled = '$user->admin';
		$this->menus[$r]->title = "Setup";
		$this->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->menus[$r]->_id = "menu:systeminfo";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/admin/system/index.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->enabled = '$user->admin';
		$this->menus[$r]->title = "SystemInfo";
		$this->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->menus[$r]->_id = "menu:systemtools";
		$this->menus[$r]->position = 2;
		$this->menus[$r]->url = "/admin/tools/index.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->enabled = '$user->admin';
		$this->menus[$r]->title = "SystemTools";
		$this->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->menus[$r]->_id = "menu:menuusersandgroups";
		$this->menus[$r]->position = 3;
		$this->menus[$r]->url = "/user/home.php";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->enabled = '$user->admin';
		$this->menus[$r]->title = "MenuUsersAndGroups";
		$this->menus[$r]->fk_menu = "menu:home";
		$r++;

		$this->menus[$r]->_id = "menu:menucompanysetup";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/admin/company.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "MenuCompanySetup";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:modules";
		$this->menus[$r]->position = 2;
		$this->menus[$r]->url = "/admin/modules.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Modules";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:menus";
		$this->menus[$r]->position = 3;
		$this->menus[$r]->url = "/admin/menus.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Menus";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:guisetup";
		$this->menus[$r]->position = 4;
		$this->menus[$r]->url = "/admin/ihm.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "GUISetup";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:boxes";
		$this->menus[$r]->position = 5;
		$this->menus[$r]->url = "/admin/boxes.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Boxes";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:alerts";
		$this->menus[$r]->position = 6;
		$this->menus[$r]->url = "/admin/delais.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Alerts";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:security";
		$this->menus[$r]->position = 7;
		$this->menus[$r]->url = "/admin/proxy.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Security";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:menulimits";
		$this->menus[$r]->position = 8;
		$this->menus[$r]->url = "/admin/limits.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "MenuLimits";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:pdf";
		$this->menus[$r]->position = 9;
		$this->menus[$r]->url = "/admin/pdf.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "PDF";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:emails";
		$this->menus[$r]->position = 10;
		$this->menus[$r]->url = "/admin/mails.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Emails";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:sms";
		$this->menus[$r]->position = 11;
		$this->menus[$r]->url = "/admin/sms.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Sms";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:dictionnarysetup";
		$this->menus[$r]->position = 12;
		$this->menus[$r]->url = "/admin/dict.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "DictionnarySetup";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:othersetup";
		$this->menus[$r]->position = 13;
		$this->menus[$r]->url = "/admin/const.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "OtherSetup";
		$this->menus[$r]->fk_menu = "menu:setup";
		$r++;

		$this->menus[$r]->_id = "menu:dolibarr";
		$this->menus[$r]->url = "/admin/system/dolibarr.php";
		$this->menus[$r]->position = 0;
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Dolibarr";
		$this->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;
		
		$this->menus[$r]->_id = "menu:memcached";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/memcached/admin/memcached.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Memcached";
		$this->menus[$r]->enabled = '$conf->memcached->host';
		$this->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;
		
		$this->menus[$r]->_id = "menu:phpinfo";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/admin/system/phpinfo.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "PHPinfo";
		$this->menus[$r]->enabled = '1';
		$this->menus[$r]->fk_menu = "menu:systeminfo";
		$r++;

		$this->menus[$r]->_id = "menu:allparameters";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/admin/system/constall.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "AllParameters";
		$this->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->menus[$r]->_id = "menu:modules0";
		$this->menus[$r]->position = 2;
		$this->menus[$r]->url = "/admin/system/modules.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Modules";
		$this->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->menus[$r]->_id = "menu:triggers";
		$this->menus[$r]->position = 3;
		$this->menus[$r]->url = "/admin/triggers.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Triggers";
		$this->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->menus[$r]->_id = "menu:about";
		$this->menus[$r]->position = 4;
		$this->menus[$r]->url = "/admin/system/about.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "About";
		$this->menus[$r]->fk_menu = "menu:dolibarr";
		$r++;

		$this->menus[$r]->_id = "menu:backup";
		$this->menus[$r]->url = "/admin/tools/dolibarr_export.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Backup";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:restore";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/admin/tools/dolibarr_import.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Restore";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:menuupgrade";
		$this->menus[$r]->position = 2;
		$this->menus[$r]->url = "/admin/tools/update.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "MenuUpgrade";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:audit";
		$this->menus[$r]->position = 4;
		$this->menus[$r]->url = "/admin/tools/listevents.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Audit";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:sessions";
		$this->menus[$r]->position = 5;
		$this->menus[$r]->url = "/admin/tools/listsessions.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Sessions";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:purge";
		$this->menus[$r]->position = 6;
		$this->menus[$r]->url = "/admin/tools/purge.php";
		$this->menus[$r]->langs = "admin";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Purge";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:helpcenter";
		$this->menus[$r]->position = 7;
		$this->menus[$r]->url = "/support/index.php";
		$this->menus[$r]->langs = "help";
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "HelpCenter";
		$this->menus[$r]->fk_menu = "menu:systemtools";
		$r++;

		$this->menus[$r]->_id = "menu:users";
		$this->menus[$r]->url = "/user/index.php";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->perms = '$user->rights->user->user->lire || $user->admin';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Users";
		$this->menus[$r]->fk_menu = "menu:menuusersandgroups";
		$r++;

		$this->menus[$r]->_id = "menu:groups";
		$this->menus[$r]->position = 1;
		$this->menus[$r]->url = "/user/group/index.php";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->perms = '($conf->global->MAIN_USE_ADVANCED_PERMS?$user->rights->user->group_advance->read:$user->rights->user->user->lire) || $user->admin';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Groups";
		$this->menus[$r]->fk_menu = "menu:menuusersandgroups";
		$r++;

		$this->menus[$r]->_id = "menu:databases";
		$this->menus[$r]->position = 2;
		$this->menus[$r]->url = "/user/database/index.php";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->perms = '($user->rights->user->user->lire) || $user->admin';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "Databases";
		$this->menus[$r]->fk_menu = "menu:menuusersandgroups";
		$r++;

		$this->menus[$r]->_id = "menu:newuser";
		$this->menus[$r]->url = "/user/fiche.php?action=create";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->perms = '$user->rights->user->user->creer || $user->admin';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "NewUser";
		$this->menus[$r]->fk_menu = "menu:users";
		$r++;

		$this->menus[$r]->_id = "menu:newgroup";
		$this->menus[$r]->url = "/user/group/fiche.php?action=create";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->perms = '$user->rights->user->group_advance->write || $user->admin';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "NewGroup";
		$this->menus[$r]->fk_menu = "menu:groups";
		$r++;

		$this->menus[$r]->_id = "menu:newdatabase";
		$this->menus[$r]->url = "/user/database/fiche.php?action=create";
		$this->menus[$r]->langs = "users";
		$this->menus[$r]->perms = '$user->admin';
		$this->menus[$r]->usertype = 2;
		$this->menus[$r]->title = "NewDatabase";
		$this->menus[$r]->fk_menu = "menu:databases";


		// Exports
		//--------
		$r = 0;

		$r++;
		$this->export_code[$r] = $this->rights_class . '_' . $r;
		$this->export_label[$r] = 'Liste des utilisateurs Dolibarr et attributs';
		$this->export_permission[$r] = array(array("user",
				"user",
				"export"));
		$this->export_fields_array[$r] = array('u.rowid' => "Id",
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
		$this->export_entities_array[$r] = array('u.rowid' => "user",
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
			unset($this->export_fields_array[$r]['u.fk_member']);
			unset($this->export_entities_array[$r]['u.fk_member']);
		}
		$this->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'user as u';
		$this->export_sql_end[$r] .=' WHERE u.entity IN (0,' . $conf->entity . ')';
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
		//$result=$this->create_view();

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
