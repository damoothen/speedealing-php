<?php
/* Copyright (C) 2003-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2008-2011 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\defgroup   banque     Module bank
 * 	\brief      Module pour gerer la tenue d'un compte bancaire et rapprochements
 *	\file       htdocs/core/modules/modBanque.class.php
 *	\ingroup    banque
 *	\brief      Fichier de description et activation du module Banque
 */

include_once(DOL_DOCUMENT_ROOT ."/core/modules/DolibarrModules.class.php");


/**
 *	\class      modBanque
 *	\brief      Classe de description et activation du module Banque
 */

class modBanque extends DolibarrModules
{

	/**
	 *	Constructor.
	 *
	 *	@param	DoliDB	$db		Database handler
	 */
	function modBanque($db)
	{
		global $conf;

		parent::__construct($db);
		$this->values->numero = 85;

		$this->values->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i','',get_class($this));
		$this->values->description = "Gestion des comptes financiers de type Comptes bancaires ou postaux";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_'.strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto='account';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/banque/temp");

        // Config pages
        //-------------
        $this->values->config_page_url = array("bank.php@compta/bank");

		// Dependancies
		$this->values->depends = array();
		$this->values->requiredby = array("modComptabilite","modAccounting");
		$this->values->conflictwith = array();
		$this->values->langfiles = array("banks","compta","bills","companies");

		// Constants
		$this->values->const = array();

		// Boites
		$this->values->boxes = array();
		$this->values->boxes[0][1] = "box_comptes.php";

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'banque';
		$r=0;

		$r++;
		$this->values->rights[$r][0] = 111; // id de la permission
		$this->values->rights[$r][1] = 'Lire les comptes bancaires'; // libelle de la permission
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 112; // id de la permission
		$this->values->rights[$r][1] = 'Creer/modifier montant/supprimer ecriture bancaire'; // libelle de la permission
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'modifier';

		$r++;
		$this->values->rights[$r][0] = 113; // id de la permission
		$this->values->rights[$r][1] = 'Configurer les comptes bancaires (creer, gerer categories)'; // libelle de la permission
		$this->values->rights[$r][2] = 'a';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'configurer';

		$r++;
		$this->values->rights[$r][0] = 114; // id de la permission
		$this->values->rights[$r][1] = 'Rapprocher les ecritures bancaires'; // libelle de la permission
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'consolidate';

		$r++;
		$this->values->rights[$r][0] = 115; // id de la permission
		$this->values->rights[$r][1] = 'Exporter transactions et releves'; // libelle de la permission
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'export';

		$r++;
		$this->values->rights[$r][0] = 116; // id de la permission
		$this->values->rights[$r][1] = 'Virements entre comptes'; // libelle de la permission
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'transfer';

		$r++;
		$this->values->rights[$r][0] = 117; // id de la permission
		$this->values->rights[$r][1] = 'Gerer les envois de cheques'; // libelle de la permission
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'cheque';



		// Exports
		//--------
		$r=0;

		$r++;
		$this->values->export_code[$r]=$this->values->rights_class.'_'.$r;
		$this->values->export_label[$r]='Ecritures bancaires et releves';
		$this->values->export_permission[$r]=array(array("banque","export"));
		$this->values->export_fields_array[$r]=array('b.rowid'=>'IdTransaction','ba.ref'=>'AccountRef','ba.label'=>'AccountLabel','b.datev'=>'DateValue','b.dateo'=>'DateOperation','b.label'=>'Label','b.num_chq'=>'ChequeOrTransferNumber','-b.amount'=>'Debit','b.amount'=>'Credit','b.num_releve'=>'AccountStatement','b.datec'=>"DateCreation","bu.url_id"=>"IdThirdParty","s.nom"=>"ThirdParty","s.code_compta"=>"CustomerAccountancyCode","s.code_compta_fournisseur"=>"SupplierAccountancyCode");
		$this->values->export_entities_array[$r]=array('b.rowid'=>'account','ba.ref'=>'account','ba.label'=>'account','b.datev'=>'account','b.dateo'=>'account','b.label'=>'account','b.num_chq'=>'account','-b.amount'=>'account','b.amount'=>'account','b.num_releve'=>'account','b.datec'=>"account","bu.url_id"=>"company","s.nom"=>"company","s.code_compta"=>"company","s.code_compta_fournisseur"=>"company");
		$this->values->export_special_array[$r]=array('-b.amount'=>'NULLIFNEG','b.amount'=>'NULLIFNEG');
	    if (empty($conf->fournisseur->enabled))
        {
            unset($this->values->export_fields_array[$r]['s.code_compta_fournisseur']);
            unset($this->values->export_entities_array[$r]['s.code_compta_fournisseur']);
        }
		$this->values->export_sql_start[$r]='SELECT DISTINCT ';
		$this->values->export_sql_end[$r]  =' FROM ('.MAIN_DB_PREFIX.'bank_account as ba, '.MAIN_DB_PREFIX.'bank as b)';
		$this->values->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX."bank_url as bu ON (bu.fk_bank = b.rowid AND bu.type = 'company')";
		$this->values->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'societe as s ON bu.url_id = s.rowid';
		$this->values->export_sql_end[$r] .=' WHERE ba.rowid = b.fk_account';
		$this->values->export_sql_end[$r] .=' AND ba.entity = '.$conf->entity;
		$this->values->export_sql_end[$r] .=' ORDER BY b.datev, b.num_releve';
	}


    /**
     *      Function called when module is enabled.
     *      The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *      It also creates data directories.
     *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options='')
	{
		global $conf;

		// Permissions
		$this->values->remove($options);

		$sql = array();

		return $this->values->_init($sql,$options);
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

		return $this->values->_remove($sql,$options);
    }

}
?>