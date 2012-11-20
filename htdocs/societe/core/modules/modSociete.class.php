<?php

/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   societe     Module societe
 * 	\brief      Module to manage third parties (customers, prospects)
 * 	\file       htdocs/core/modules/modSociete.class.php
 * 	\ingroup    societe
 * 	\brief      Fichier de description et activation du module Societe
 */
include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

/**
 * 	Classe de description et activation du module Societe
 */
class modSociete extends DolibarrModules {

    /**
     *   Constructor. Define names, constants, directories, boxes, permissions
     *
     *   @param      DoliDB		$db      Database handler
     */
    function __construct($db) {
        global $conf;

        parent::__construct($db);

        $this->numero = 1;

        $this->family = "crm";
        // Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Gestion des societes et contacts";

        // Possible values for version are: 'development', 'experimental', 'speedealing' or version
        $this->version = 'speedealing';

        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->special = 0;
        $this->config_page_url = array("societe.php@societe");
        // Name of image file used for this module.
        $this->picto = 'company';

        // Data directories to create when module is enabled
        $this->dirs = array("/societe/temp");

        // Dependances
        $this->depends = array();
        $this->requiredby = array("modExpedition", "modFacture", "modFournisseur", "modFicheinter", "modPropale", "modContrat", "modCommande");
        $this->langfiles = array("companies");

        // Constantes
        $this->const = array();
        $r = 0;

        $this->const[$r][0] = "SOCIETE_CODECLIENT_ADDON";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "mod_codeclient_leopard";
        $this->const[$r][3] = 'Module to control third parties codes';
        $this->const[$r][4] = 0;
        $r++;

        $this->const[$r][0] = "SOCIETE_CODECOMPTA_ADDON";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "mod_codecompta_panicum";
        $this->const[$r][3] = 'Module to control third parties codes';
        $this->const[$r][4] = 0;
        $r++;

        $this->const[$r][0] = "SOCIETE_FISCAL_MONTH_START";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "0";
        $this->const[$r][3] = "Mettre le numero du mois du debut d\'annee fiscale, ex: 9 pour septembre";
        $this->const[$r][4] = 0;
        $r++;

        $this->const[$r][0] = "MAIN_SEARCHFORM_SOCIETE";
        $this->const[$r][1] = "yesno";
        $this->const[$r][2] = "1";
        $this->const[$r][3] = "Show form for quick company search";
        $this->const[$r][4] = 0;
        $r++;

        $this->const[$r][0] = "MAIN_SEARCHFORM_CONTACT";
        $this->const[$r][1] = "yesno";
        $this->const[$r][2] = "1";
        $this->const[$r][3] = "Show form for quick contact search";
        $this->const[$r][4] = 0;
        $r++;

        $this->const[$r][0] = "COMPANY_ADDON_PDF_ODT_PATH";
        $this->const[$r][1] = "chaine";
        $this->const[$r][2] = "DOL_DATA_ROOT/doctemplates/thirdparties";
        $this->const[$r][3] = "";
        $this->const[$r][4] = 0;
        $r++;

        // Boxes
        $this->boxes = array();
        $r = 0;
        $this->boxes[$r][1] = "box_clients.php";
        $r++;
        $this->boxes[$r][1] = "box_prospect.php";
        $r++;
        $this->boxes[$r][1] = "box_contacts.php";
        $r++;
        $this->boxes[$r][1] = "box_activity.php";
        $r++;

        // Permissions
        $this->rights = array();
        $this->rights_class = 'societe';
        $r = 0;
        $this->rights[$r]->id = 121; // id de la permission
        $this->rights[$r]->desc = 'Lire les societes'; // libelle de la permission
        $this->rights[$r]->default = true;
        $this->rights[$r]->perm = array('lire');

        $r++;
        $this->rights[$r]->id = 122; // id de la permission
        $this->rights[$r]->desc = 'Creer modifier les societes'; // libelle de la permission
        $this->rights[$r]->default = 0; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('creer');

        /* 		$r++;
          $this->rights[$r][0] = 241;
          $this->rights[$r][1] = 'Read thirdparties customers';
          $this->rights[$r][2] = 'r';
          $this->rights[$r][3] = 0;
          $this->rights[$r][4] = 'thirparty_customer_advance';      // Visible if option MAIN_USE_ADVANCED_PERMS is on
          $this->rights[$r][5] = 'read';

          $r++;
          $this->rights[$r][0] = 242;
          $this->rights[$r][1] = 'Read thirdparties suppliers';
          $this->rights[$r][2] = 'r';
          $this->rights[$r][3] = 0;
          $this->rights[$r][4] = 'thirdparty_supplier_advance';      // Visible if option MAIN_USE_ADVANCED_PERMS is on
          $this->rights[$r][5] = 'read';
         */

        $r++;
        $this->rights[$r]->id = 125; // id de la permission
        $this->rights[$r]->desc = 'Supprimer les societes'; // libelle de la permission
        $this->rights[$r]->default = 0; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('supprimer');

        /* 		$r++;
          $this->rights[$r][0] = 251;
          $this->rights[$r][1] = 'Create thirdparties customers';
          $this->rights[$r][2] = 'r';
          $this->rights[$r][3] = 0;
          $this->rights[$r][4] = 'thirparty_customer_advance';      // Visible if option MAIN_USE_ADVANCED_PERMS is on
          $this->rights[$r][5] = 'read';

          $r++;
          $this->rights[$r][0] = 252;
          $this->rights[$r][1] = 'Create thirdparties suppliers';
          $this->rights[$r][2] = 'r';
          $this->rights[$r][3] = 0;
          $this->rights[$r][4] = 'thirdparty_supplier_advance';      // Visible if option MAIN_USE_ADVANCED_PERMS is on
          $this->rights[$r][5] = 'read';
         */

        $r++;
        $this->rights[$r]->id = 126; // id de la permission
        $this->rights[$r]->desc = 'Exporter les societes'; // libelle de la permission
        $this->rights[$r]->default = 0; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('export');

        // 262 : Resteindre l'acces des commerciaux
        $r++;
        $this->rights[$r]->id = 262;
        $this->rights[$r]->desc = 'Consulter tous les tiers par utilisateurs internes (sinon uniquement si contact commercial). Non effectif pour utilisateurs externes (tjs limités à eux-meme).';
        $this->rights[$r]->default = 1;
        $this->rights[$r]->perm = array('client', 'voir');

        // 262 : Resteindre l'acces des commerciaux
        $r++;
        $this->rights[$r]->id = 281; // id de la permission
        $this->rights[$r]->desc = 'Lire les contacts'; // libelle de la permission
        $this->rights[$r]->default = 1; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('contact', 'lire');

        $r++;
        $this->rights[$r]->id = 282; // id de la permission
        $this->rights[$r]->desc = 'Creer modifier les contacts'; // libelle de la permission
        $this->rights[$r]->default = 0; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('contact', 'creer');

        $r++;
        $this->rights[$r]->id = 283; // id de la permission
        $this->rights[$r]->desc = 'Supprimer les contacts'; // libelle de la permission
        $this->rights[$r]->default = 0; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('contact', 'supprimer');

        $r++;
        $this->rights[$r]->id = 286; // id de la permission
        $this->rights[$r]->desc = 'Exporter les contacts'; // libelle de la permission
        $this->rights[$r]->default = 0; // La permission est-elle une permission par defaut
        $this->rights[$r]->perm = array('contact', 'export');

        // Menus
        $r = 0;
        $this->menus[$r]->_id = "menu:companies";
        $this->menus[$r]->type = "top";
        $this->menus[$r]->position = 2;
        $this->menus[$r]->langs = "companies";
        $this->menus[$r]->perms = '$user->rights->societe->lire || $user->rights->societe->contact->lire';
        $this->menus[$r]->enabled = '$conf->societe->enabled || $conf->fournisseur->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "ThirdParties";
        $r++;

        $this->menus[$r]->_id = "menu:newcompany";
        $this->menus[$r]->position = 1;
        $this->menus[$r]->url = "/societe/fiche.php?action=create";
        $this->menus[$r]->langs = "companies";
        $this->menus[$r]->perms = '$user->rights->societe->creer';
        $this->menus[$r]->enabled = '$conf->societe->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "MenuNewThirdParty";
        $this->menus[$r]->fk_menu = "menu:companies";
        $r++;
        $this->menus[$r]->_id = "menu:thirdparty";
        $this->menus[$r]->position = 2;
        $this->menus[$r]->url = "/societe/list.php";
        $this->menus[$r]->langs = "companies";
        $this->menus[$r]->perms = '$user->rights->societe->lire';
        $this->menus[$r]->enabled = '$conf->societe->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "ListOfThirdParties";
        $this->menus[$r]->fk_menu = "menu:companies";
        $r++;
        
        $this->menus[$r]->_id = "menu:newcontact";
        $this->menus[$r]->position = 10;
        $this->menus[$r]->url = "/contact/fiche.php?action=create";
        $this->menus[$r]->langs = "companies";
        $this->menus[$r]->perms = '$user->rights->societe->creer';
        $this->menus[$r]->enabled = '$conf->societe->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "NewContact";
        $this->menus[$r]->fk_menu = "menu:companies";
        $r++;
        $this->menus[$r]->_id = "menu:contactsaddresses";
        $this->menus[$r]->position = 11;
        $this->menus[$r]->url = "/contact/list.php";
        $this->menus[$r]->langs = "companies";
        $this->menus[$r]->perms = '$user->rights->societe->lire';
        $this->menus[$r]->enabled = '$conf->societe->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = 'ListOfContacts';
        $this->menus[$r]->fk_menu = "menu:companies";
        $r++;

        // Exports
        //--------
        $r = 0;

        // Export list of third parties and attributes
        $this->export[$r]->code = $this->rights_class . '_' . $r;
        $this->export[$r]->label = 'ExportDataset_company_1';
        $this->export[$r]->icon = 'company';
        $this->export[$r]->permission = '$user->societe->export';

        // Export list of contacts and attributes
        $r++;
        $this->export[$r]->code = $this->rights_class . '_' . $r;
        $this->export[$r]->label = 'ExportDataset_company_2';
        $this->export[$r]->icon = 'contact';
        $this->export[$r]->permission = '$user->societe->contact->export';
        $this->export[$r]->class = "Contact";

        // Imports
        //--------
        $r = 0;

        // Import list of third parties and attributes
        $this->import[$r]->code = $this->rights_class . '_' . $r;
        $this->import[$r]->label = 'ImportDataset_company_1';
        $this->import[$r]->icon = 'company';

        // Import list of contact and attributes
        $r++;
        $this->import[$r]->code = $this->rights_class . '_' . $r;
        $this->import[$r]->label = 'ImportDataset_company_2';
        $this->import[$r]->icon = 'contact';

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
        global $conf, $langs;

        // We disable this to prevent pb of modules not correctly disabled
        //$this->remove($options);
        //ODT template
        $src = DOL_DOCUMENT_ROOT . '/install/doctemplates/thirdparties/template_thirdparty.odt';
        $dirodt = DOL_DATA_ROOT . '/doctemplates/thirdparties';
        $dest = $dirodt . '/template_thirdparty.odt';

        if (file_exists($src) && !file_exists($dest)) {
            require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
            dol_mkdir($dirodt);
            $result = dol_copy($src, $dest, 0, 0);
            if ($result < 0) {
                $langs->load("errors");
                $this->error = $langs->trans('ErrorFailToCopyFile', $src, $dest);
                return 0;
            }
        }

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
