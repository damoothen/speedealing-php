<?php
/* Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *	\defgroup   deplacement     Module trips
 *	\brief      Module pour gerer les deplacements et notes de frais
 *	\file       htdocs/core/modules/modDeplacement.class.php
 *	\ingroup    deplacement
 *	\brief      Fichier de description et activation du module Deplacement et notes de frais
 */
include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Deplacement
 */
class modDeplacement extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$DB      Database handler
	 */
	function __construct($db)
	{
		global $conf;

		parent::__construct($DB);
		$this->numero = 75;

		$this->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion des notes de frais et deplacements";		// Si traduction Module75Desc non trouvee

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'dolibarr';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 0;
		$this->picto = "trip";

		// Data directories to create when module is enabled
		$this->dirs = array();

		// Config pages
		$this->config_page_url = array();
		$this->langfiles = array("companies","trips");

		// Dependancies
		$this->depends = array();
		$this->requiredby = array();

		// Constants
		$this->const = array();

		// Boxes
		$this->boxes = array();

		// Permissions
		$this->rights = array();
		$this->rights_class = 'deplacement';

		$this->rights[1][0] = 171;
		$this->rights[1][1] = 'Lire les deplacements';
		$this->rights[1][2] = 'r';
		$this->rights[1][3] = 1;
		$this->rights[1][4] = 'lire';

		$this->rights[2][0] = 172;
		$this->rights[2][1] = 'Creer/modifier les deplacements';
		$this->rights[2][2] = 'w';
		$this->rights[2][3] = 0;
		$this->rights[2][4] = 'creer';

    $this->rights[3][0] = 173;
		$this->rights[3][1] = 'Supprimer les deplacements';
		$this->rights[3][2] = 'd';
		$this->rights[3][3] = 0;
		$this->rights[3][4] = 'supprimer';
/*
		$this->rights[4][0] = 174;
		$this->rights[4][1] = 'Bloquer les deplacements';
		$this->rights[4][2] = 'a';
		$this->rights[4][3] = 0;
		$this->rights[4][4] = 'valider';

		$this->rights[5][0] = 175;
		$this->rights[5][1] = 'Debloquer les deplacements';
		$this->rights[5][2] = 'a';
		$this->rights[5][3] = 0;
		$this->rights[5][4] = 'unvalidate';
*/
		$this->rights[6][0] = 178;
		$this->rights[6][1] = 'Exporter les deplacements';
		$this->rights[6][2] = 'd';
		$this->rights[6][3] = 0;
		$this->rights[6][4] = 'export';

		// Exports
		$r=0;

		$r++;
		$this->export_code[$r]='trips_'.$r;
		$this->export_label[$r]='ListTripsAndExpenses';
		$this->export_permission[$r]=array(array("deplacement","export"));
        $this->export_fields_array[$r]=array('u.login'=>'Login','u.name'=>'Lastname','u.firstname'=>'Firstname','d.rowid'=>"TripId",'d.type'=>"Type",'d.km'=>"FeesKilometersOrAmout",'d.dated'=>"Date",'d.note'=>'NotePrivate','d.note_public'=>'NotePublic','s.nom'=>'ThirdParty');
        $this->export_entities_array[$r]=array('u.login'=>'user','u.name'=>'user','u.firstname'=>'user','d.rowid'=>"trip",'d.type'=>"trip",'d.km'=>"trip",'d.dated'=>"trip",'d.note'=>'trip','d.note_public'=>'trip','s.nom'=>'company');
        $this->export_dependencies_array[$r]=array('trip'=>'d.rowid'); // To add unique key if we ask a field of a child to avoid the DISTINCT to discard them

		$this->export_sql_start[$r]='SELECT DISTINCT ';
		$this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'user as u';
		$this->export_sql_end[$r] .=', '.MAIN_DB_PREFIX.'deplacement as d';
		$this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'societe as s ON d.fk_soc = s.rowid';
		$this->export_sql_end[$r] .=' WHERE d.fk_user = u.rowid';
		$this->export_sql_end[$r] .=' AND d.entity = '.$conf->entity;
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
