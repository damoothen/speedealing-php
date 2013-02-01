<?php

/* Copyright (C) 2005-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

/**
 * 	Classe de description et activation du module export
 */
class modExport extends DolibarrModules {

    /**
     *   Constructor. Define names, constants, directories, boxes, permissions
     *
     *   @param      DoliDB		$db      Database handler
     */
    function __construct($db) {
        parent::__construct($db);
        $this->numero = 240;

        $this->family = "technic";
        // Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Outils d'exports de donnees Dolibarr (via un assistant)";
        $this->version = 'speedealing';                        // 'experimental' or 'dolibarr' or version
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->special = 0;
        $this->picto = 'technic';

        // Data directories to create when module is enabled
        $this->dirs = array("/export/temp");

        // Config pages
        $this->config_page_url = array();

        // Dependances
        $this->depends = array("Import");
        $this->requiredby = array();
        $this->phpmin = array(4, 2, 0);
        $this->phpmax = array();

        // Constantes
        $this->const = array();

        // Boxes
        $this->boxes = array();

        // Permissions
        $this->rights = array();
        $this->rights_class = 'export';
        $r = 0;

        $this->rights[$r]->id = 1201; // id de la permission
        $this->rights[$r]->desc = 'Lire les exports'; // libelle de la permission
        $this->rights[$r]->default = true;
        $this->rights[$r]->perm = array('lire');
        $r++;
        
        $this->rights[$r]->id = 1202; // id de la permission
        $this->rights[$r]->desc = 'Creer/modifier un export'; // libelle de la permission
        $this->rights[$r]->default = false;
        $this->rights[$r]->perm = array('creer');
        $r++;
        
        // Menus
        $r = 0;
        
        $this->menus[$r]->_id = "menu:formatedexport";
        $this->menus[$r]->position = 2;
        $this->menus[$r]->url = "/export/index.php";
        $this->menus[$r]->langs = "exports";
        $this->menus[$r]->perms = '$user->rights->export->lire';
        $this->menus[$r]->enabled = '$conf->export->enabled';
        $this->menus[$r]->usertype = 2;
        $this->menus[$r]->title = "FormatedExport";
        $this->menus[$r]->fk_menu = "menu:tools";
        $r++;
        
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
