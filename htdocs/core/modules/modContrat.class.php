<?php
/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
 * Copyright (C) 2010-2012 Herve Prot          <herve.prot@symeos.com>
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
 *	\defgroup   contrat     Module contract
 *	\brief      Module pour gerer la tenue de contrat de services
 *	\file       htdocs/core/modules/modContrat.class.php
 *	\ingroup    contrat
 *	\brief      Fichier de description et activation du module Contrat
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Contrat
 */
class modContrat extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		parent::__construct($db);
		$this->numero = 54;

		$this->family = "crm";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion des contrats de services";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'dolibarr';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 0;
		$this->picto='contract';

		// Data directories to create when module is enabled
		$this->dirs = array("/contracts/temp");

		// Dependances
		$this->depends = array("modSociete","modService");
		$this->requiredby = array();

		// Config pages
		$this->config_page_url = array("contract.php");

		// Constantes
		$this->const = array();
		$this->const[0][0] = "CONTRACT_ADDON";
		$this->const[0][1] = "chaine";
		$this->const[0][2] = "mod_contract_serpis";
		$this->const[0][3] = 'Nom du gestionnaire de numerotation des contrats';
		$this->const[0][4] = 0;

		// Boxes
		$this->boxes = array();
		$this->boxes[0][1] = "box_contracts.php";
		$this->boxes[1][1] = "box_services_expired.php";

		// Permissions
		$this->rights = array();
		$this->rights_class = 'contrat';

		$this->rights[1][0] = 161;
		$this->rights[1][1] = 'Lire les contrats';
		$this->rights[1][2] = 'r';
		$this->rights[1][3] = 1;
		$this->rights[1][4] = 'lire';

		$this->rights[2][0] = 162;
		$this->rights[2][1] = 'Creer / modifier les contrats';
		$this->rights[2][2] = 'w';
		$this->rights[2][3] = 0;
		$this->rights[2][4] = 'creer';

		$this->rights[3][0] = 163;
		$this->rights[3][1] = 'Activer un service d\'un contrat';
		$this->rights[3][2] = 'w';
		$this->rights[3][3] = 0;
		$this->rights[3][4] = 'activer';

		$this->rights[4][0] = 164;
		$this->rights[4][1] = 'Desactiver un service d\'un contrat';
		$this->rights[4][2] = 'w';
		$this->rights[4][3] = 0;
		$this->rights[4][4] = 'desactiver';

		$this->rights[5][0] = 165;
		$this->rights[5][1] = 'Supprimer un contrat';
		$this->rights[5][2] = 'd';
		$this->rights[5][3] = 0;
		$this->rights[5][4] = 'supprimer';

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
