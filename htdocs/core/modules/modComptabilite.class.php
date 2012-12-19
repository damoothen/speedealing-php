<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * \defgroup   comptabilite     Module comptabilite
 * \brief      Module pour inclure des fonctions de comptabilite (gestion de comptes comptables et rapports)
 * \file       htdocs/core/modules/modComptabilite.class.php
 * \ingroup    comptabilite
 * \brief      Fichier de description et activation du module Comptabilite
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Comptabilite
 */
class modComptabilite extends DolibarrModules
{

   /**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
    */
	function __construct($db)
	{
		global $conf;

		parent::__construct($db);
		$this->numero = 10;

		$this->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion sommaire de comptabilite";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'dolibarr';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 0;
        $this->picto='';

		// Config pages
		$this->config_page_url = array("compta.php");

		// Dependances
		$this->depends = array("modFacture","modBanque");
		$this->requiredby = array();
		$this->conflictwith = array("modAccounting");
		$this->langfiles = array("compta");

		// Constantes
		$this->const = array();

		// Data directories to create when module is enabled
		$this->dirs = array("/comptabilite/temp",
		                    "/comptabilite/rapport",
		                    "/comptabilite/export",
		                    "/comptabilite/bordereau"
		                    );

		// Boites
		$this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'compta';
		$r=0;

		$r++;
		$this->rights[$r][0] = 95;
		$this->rights[$r][1] = 'Lire CA, bilans, resultats';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'resultat';
		$this->rights[$r][5] = 'lire';

		$r++;
		$this->rights[$r][0] = 96;
		$this->rights[$r][1] = 'Parametrer la ventilation';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'ventilation';
		$this->rights[$r][5] = 'parametrer';

		$r++;
		$this->rights[$r][0] = 97;
		$this->rights[$r][1] = 'Lire les ventilations de factures';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'ventilation';
		$this->rights[$r][5] = 'lire';

		$r++;
		$this->rights[$r][0] = 98;
		$this->rights[$r][1] = 'Ventiler les lignes de factures';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'ventilation';
		$this->rights[$r][5] = 'creer';
	}


   /**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
    */
	function init($options='')
	{
		global $conf;

		// Nettoyage avant activation
		$this->remove($options);

		$sql = array();

		return $this->_init($sql,$options);
	}

    /**
	 *		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 *		Data directories are not deleted
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
     */
    function remove($options='')
    {
		$sql = array();

		return $this->_remove($sql,$options);
    }

}
?>
