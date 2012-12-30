<?php
/* Copyright (C) 2004-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2010-2011 Juanjo Menent 		<jmenent@2byte.es>
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
 *	\defgroup   	prelevement     Module prelevement
 *	\brief      	Module de gestion des prelevements bancaires
 *	\file       	htdocs/core/modules/modPrelevement.class.php
 *	\ingroup    	prelevement
 *	\brief      	Fichier de description et activation du module Prelevement
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Prelevement
 */
class modPrelevement extends DolibarrModules
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
		
		$this->numero = 57;

		$this->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion des Prelevements";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'speedealing';

		// Name of png file (without png) used for this module
		$this->picto='payment';

		// Data directories to create when module is enabled
		$this->dirs = array("/prelevement/temp","/prelevement/receipts");

		// Dependancies
		$this->depends = array("modFacture","modBanque");
		$this->requiredby = array();

		// Config pages
		$this->config_page_url = array("prelevement.php");

		// Constantes
		$this->const = array();

		// Boites
		$this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'prelevement';
		$r=0;
		$r++;
		$this->rights[$r][0] = 151;
		$this->rights[$r][1] = 'Read withdrawals';
		$this->rights[$r][2] = 'r';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'bons';
		$this->rights[$r][5] = 'lire';

		$r++;
		$this->rights[$r][0] = 152;
		$this->rights[$r][1] = 'Create/modify a withdrawals';
		$this->rights[$r][2] = 'w';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'bons';
		$this->rights[$r][5] = 'creer';

		$r++;
		$this->rights[$r][0] = 153;
		$this->rights[$r][1] = 'Send withdrawals to bank';
		$this->rights[$r][2] = 'a';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'bons';
		$this->rights[$r][5] = 'send';

		$r++;
		$this->rights[$r][0] = 154;
		$this->rights[$r][1] = 'credit/refuse withdrawals';
		$this->rights[$r][2] = 'a';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'bons';
		$this->rights[$r][5] = 'credit';

/*        $this->rights[2][0] = 154;
        $this->rights[2][1] = 'Setup withdraw account';
        $this->rights[2][2] = 'w';
        $this->rights[2][3] = 0;
        $this->rights[2][4] = 'bons';
        $this->rights[2][5] = 'configurer';
*/
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

		// Permissions
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
