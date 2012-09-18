<?php

/* Copyright (C) 2003,2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2009-2011 Regis Houssin        <regis@dolibarr.fr>
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
 * 		\defgroup   agenda     Module agenda
 *      \brief      Module pour gerer l'agenda et actions
 *      \file       htdocs/core/modules/modAgenda.class.php
 *      \ingroup    agenda
 *      \brief      Fichier de description et activation du module agenda
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 *      \class      modAgenda
 *      \brief      Classe de description et activation du module Adherent
 */
class modAgenda extends DolibarrModules {

    /**
     *   Constructor. Define names, constants, directories, boxes, permissions
     *
     *   @param      DoliDB		$db      Database handler
     */
    function modAgenda($db) {
        parent::__construct($db);

        $this->numero = 2400;

        $this->family = "projects";
        // Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
        $this->name = strtolower(preg_replace('/^mod/i', '', get_class($this)));
        $this->description = "Gestion de l'agenda et des actions";
        $this->version = 'speedealing';                        // 'experimental' or 'dolibarr' or version
        // Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->special = 0;
        $this->picto = 'action';

        // Data directories to create when module is enabled
        $this->dirs = array("/agenda/temp");

        // Config pages
        //-------------
        $this->config_page_url = array("agenda.php@agenda");

        // Dependancies
        //-------------
        $this->depends = array();
        $this->requiredby = array();
        $this->langfiles = array("companies");

        // Constantes
        //-----------
        $this->const = array();
        $this->const[0] = array("MAIN_AGENDA_ACTIONAUTO_COMPANY_CREATE", "chaine", "1");
        $this->const[1] = array("MAIN_AGENDA_ACTIONAUTO_CONTRACT_VALIDATE", "chaine", "1");
        $this->const[2] = array("MAIN_AGENDA_ACTIONAUTO_PROPAL_VALIDATE", "chaine", "1");
        $this->const[3] = array("MAIN_AGENDA_ACTIONAUTO_PROPAL_SENTBYMAIL", "chaine", "1");
        $this->const[4] = array("MAIN_AGENDA_ACTIONAUTO_ORDER_VALIDATE", "chaine", "1");
        $this->const[5] = array("MAIN_AGENDA_ACTIONAUTO_ORDER_SENTBYMAIL", "chaine", "1");
        $this->const[6] = array("MAIN_AGENDA_ACTIONAUTO_BILL_VALIDATE", "chaine", "1");
        $this->const[7] = array("MAIN_AGENDA_ACTIONAUTO_BILL_PAYED", "chaine", "1");
        $this->const[8] = array("MAIN_AGENDA_ACTIONAUTO_BILL_CANCEL", "chaine", "1");
        $this->const[9] = array("MAIN_AGENDA_ACTIONAUTO_BILL_SENTBYMAIL", "chaine", "1");
        $this->const[10] = array("MAIN_AGENDA_ACTIONAUTO_ORDER_SUPPLIER_VALIDATE", "chaine", "1");
        $this->const[11] = array("MAIN_AGENDA_ACTIONAUTO_BILL_SUPPLIER_VALIDATE", "chaine", "1");
        $this->const[12] = array("MAIN_AGENDA_ACTIONAUTO_SHIPPING_VALIDATE", "chaine", "1");
        $this->const[13] = array("MAIN_AGENDA_ACTIONAUTO_SHIPPING_SENTBYMAIL", "chaine", "1");

        // New pages on tabs
        // -----------------
        $this->tabs = array();

        // Boxes
        //------
        $this->boxes = array();
        $this->boxes[0][1] = "box_actions.php@agenda";

        // Permissions
        //------------
        $this->rights = array();
        $this->rights_class = 'agenda';
        $r = 0;

        // $this->rights[$r][0]     Id permission (unique tous modules confondus)
        // $this->rights[$r][1]     Libelle par defaut si traduction de cle "PermissionXXX" non trouvee (XXX = Id permission)
        // $this->rights[$r][2]     Non utilise
        // $this->rights[$r][3]     1=Permis par defaut, 0=Non permis par defaut
        // $this->rights[$r][4]     Niveau 1 pour nommer permission dans code
        // $this->rights[$r][5]     Niveau 2 pour nommer permission dans code
        // $r++;


        $this->rights[$r]->id = 2401;
        $this->rights[$r]->desc = 'Read actions/tasks linked to his account';
        $this->rights[$r]->default = 1;
        $this->rights[$r]->perm = array('myactions', 'read');
        $r++;

        $this->rights[$r]->id = 2402;
        $this->rights[$r]->desc = 'Create/modify actions/tasks linked to his account';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('myactions', 'create');
        $r++;

        $this->rights[$r]->id = 2403;
        $this->rights[$r]->desc = 'Delete actions/tasks linked to his account';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('myactions', 'delete');
        $r++;

        $this->rights[$r]->id = 2411;
        $this->rights[$r]->desc = 'Read actions/tasks of others';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('allactions', 'read');
        $r++;

        $this->rights[$r]->id = 2412;
        $this->rights[$r]->desc = 'Create/modify actions/tasks of others';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('allactions', 'create');
        $r++;

        $this->rights[$r]->id = 2413;
        $this->rights[$r]->desc = 'Delete actions/tasks of others';
        $this->rights[$r]->default = 0;
        $this->rights[$r]->perm = array('allactions', 'delete');
        $r++;

        // Main menu entries
        $this->menu = array();   // List of menus to add
        $r = 0;

        // Add here entries to declare new menus
        // Example to declare the Top Menu entry:
        // $this->menu[$r]=array(	'fk_menu'=>0,			// Put 0 if this is a top menu
        //							'type'=>'top',			// This is a Top menu entry
        //							'titre'=>'MyModule top menu',
        //							'mainmenu'=>'mymodule',
        //							'url'=>'/mymodule/pagetop.php',
        //							'langs'=>'mylangfile',	// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
        //							'position'=>100,
        //							'enabled'=>'1',			// Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
        //							'perms'=>'1',			// Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
        //							'target'=>'',
        //							'user'=>2);				// 0=Menu for internal users, 1=external users, 2=both
        // $r++;
        $this->menus[$r]->_id = "menu:agenda";
        $this->menus[$r]->type = "top";
        $this->menus[$r]->position = 10;
        $this->menus[$r]->url = "/agenda/listactions.php";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "Agenda";
        $r++;

        $this->menus[$r]->_id = "menu:actionagenda";
        $this->menus[$r]->position = 100;
        $this->menus[$r]->url = "/agenda/listactions.php";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "Actions";
        $this->menus[$r]->fk_menu = "menu:agenda";
        $r++;

        $this->menus[$r]->_id = "menu:newaction";
        $this->menus[$r]->position = 101;
        $this->menus[$r]->url = "/agenda/fiche.php?action=create";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '($user->rights->agenda->myactions->create||$user->rights->agenda->allactions->create)';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "NewAction";
        $this->menus[$r]->fk_menu = "menu:actionagenda";
        $r++;

        $this->menus[$r]->_id = "menu:actioncalendar";
        $this->menus[$r]->position = 102;
        $this->menus[$r]->url = "/agenda/index.php";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "Calendar";
        $this->menus[$r]->fk_menu = "menu:actionagenda";
        $r++;

        $this->menus[$r]->_id = "menu:MenuToDoMyActions";
        $this->menus[$r]->position = 103;
        $this->menus[$r]->url = "/agenda/index.php?status=todo&amp;filter=mine";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuToDoMyActions";
        $this->menus[$r]->fk_menu = "menu:actioncalendar";
        $r++;

        $this->menus[$r]->_id = "menu:MenuDoneMyActions";
        $this->menus[$r]->position = 104;
        $this->menus[$r]->url = "/agenda/index.php?status=done&amp;filter=mine";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuDoneMyActions";
        $this->menus[$r]->fk_menu = "menu:actioncalendar";
        $r++;

        $this->menus[$r]->_id = "menu:MenuToDoActions";
        $this->menus[$r]->position = 105;
        $this->menus[$r]->url = "/agenda/index.php?status=todo";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->allactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuToDoActions";
        $this->menus[$r]->fk_menu = "menu:actioncalendar";
        $r++;

        $this->menus[$r]->_id = "menu:MenuDoneActions";
        $this->menus[$r]->position = 106;
        $this->menus[$r]->url = "/agenda/index.php?status=done";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->allactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuDoneActions";
        $this->menus[$r]->fk_menu = "menu:actioncalendar";
        $r++;

        $this->menus[$r]->_id = "menu:agendaList";
        $this->menus[$r]->position = 112;
        $this->menus[$r]->url = "/agenda/listactions.php";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "List";
        $this->menus[$r]->fk_menu = "menu:agenda";
        $r++;

        $this->menus[$r]->_id = "menu:MenuToDoMyActions";
        $this->menus[$r]->position = 113;
        $this->menus[$r]->url = "/agenda/listactions.php?status=todo&amp;filter=mine";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuToDoMyActions";
        $this->menus[$r]->fk_menu = "menu:agendaList";
        $r++;

        $this->menus[$r]->_id = "menu:MenuDoneMyActions";
        $this->menus[$r]->position = 114;
        $this->menus[$r]->url = "/agenda/listactions.php?status=done&amp;filter=mine";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->myactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuDoneMyActions";
        $this->menus[$r]->fk_menu = "menu:agendaList";
        $r++;

        $this->menus[$r]->_id = "menu:MenuToDoActions";
        $this->menus[$r]->position = 115;
        $this->menus[$r]->url = "/agenda/listactions.php?status=todo";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->allactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuToDoActions";
        $this->menus[$r]->fk_menu = "menu:agendaList";
        $r++;

        $this->menus[$r]->_id = "menu:MenuDoneActions";
        $this->menus[$r]->position = 116;
        $this->menus[$r]->url = "/agenda/listactions.php?status=done";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->allactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuDoneActions";
        $this->menus[$r]->fk_menu = "menu:agendaList";
        $r++;

        $this->menus[$r]->_id = "menu:agendaReportings";
        $this->menus[$r]->position = 120;
        $this->menus[$r]->url = "/agenda/rapport/index.php";
        $this->menus[$r]->langs = "agenda";
        $this->menus[$r]->perms = '$user->rights->agenda->allactions->read';
        $this->menus[$r]->enabled = '$conf->agenda->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "Reportings";
        $this->menus[$r]->fk_menu = "menu:agenda";
        $r++;

        // Exports
        //--------
        $r = 0;

        // $this->export_code[$r]          Code unique identifiant l'export (tous modules confondus)
        // $this->export_label[$r]         Libelle par defaut si traduction de cle "ExportXXX" non trouvee (XXX = Code)
        // $this->export_permission[$r]    Liste des codes permissions requis pour faire l'export
        // $this->export_fields_sql[$r]    Liste des champs exportables en codif sql
        // $this->export_fields_name[$r]   Liste des champs exportables en codif traduction
        // $this->export_sql[$r]           Requete sql qui offre les donnees a l'export
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
        // Prevent pb of modules not correctly disabled
        //$this->remove($options);

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
