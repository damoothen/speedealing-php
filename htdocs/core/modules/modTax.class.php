<?php

/* Copyright (C) 2003		Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012	Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004		Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004		Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2011	Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012  Herve Prot           <herve.prot@symeos.com>
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
 * or see http://www.gnu.org/
 */

/**
 * 		\defgroup   tax		Module taxes
 * 		\brief      Module pour inclure des fonctions de saisies des taxes (tva) et charges sociales
 *      \file       htdocs/core/modules/modTax.class.php
 *      \ingroup    tax
 *      \brief      Fichier de description et activation du module Taxe
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 * 	\class 		modTax
 * 	\brief      Classe de description et activation du module Tax
 */
class modTax extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modTax($db) {
		global $conf;

		parent::__construct($db);
		$this->values->numero = 500;

		$this->values->family = "financial";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		// Module description used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->description = "Gestion des taxes, charges sociales et dividendes";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->values->version = 'dolibarr';

		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'bill';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/tax/temp");

		// Config pages
		$this->values->config_page_url = array("taxes.php");

		// Dependances
		$this->values->depends = array();
		$this->values->requiredby = array();
		$this->values->conflictwith = array();
		$this->values->langfiles = array("compta", "bills");

		// Constantes
		$this->values->const = array();

		// Boites
		$this->values->boxes = array();

		// Permissions
		$this->values->rights = array();
		$this->values->rights_class = 'tax';
		$r = 0;

		$r++;
		$this->values->rights[$r][0] = 91;
		$this->values->rights[$r][1] = 'Lire les charges';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'charges';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 92;
		$this->values->rights[$r][1] = 'Creer/modifier les charges';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'charges';
		$this->values->rights[$r][5] = 'creer';

		$r++;
		$this->values->rights[$r][0] = 93;
		$this->values->rights[$r][1] = 'Supprimer les charges';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'charges';
		$this->values->rights[$r][5] = 'supprimer';

		$r++;
		$this->values->rights[$r][0] = 94;
		$this->values->rights[$r][1] = 'Exporter les charges';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'charges';
		$this->values->rights[$r][5] = 'export';


		// Exports
		//--------
		$r = 0;

		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'Taxes et charges sociales, et leurs reglements';
		$this->values->export_permission[$r] = array(array("tax", "charges", "export"));
		$this->values->export_fields_array[$r] = array('cc.libelle' => "Type", 'c.rowid' => "IdSocialContribution", 'c.libelle' => "Label", 'c.date_ech' => 'DateDue', 'c.periode' => 'Period', 'c.amount' => "AmountExpected", "c.paye" => "Status", 'p.rowid' => 'PaymentId', 'p.datep' => 'DatePayment', 'p.amount' => 'AmountPayment', 'p.num_paiement' => 'Numero');
		$this->values->export_entities_array[$r] = array('cc.libelle' => "tax_type", 'c.rowid' => "tax", 'c.libelle' => 'tax', 'c.date_ech' => 'tax', 'c.periode' => 'tax', 'c.amount' => "tax", "c.paye" => "tax", 'p.rowid' => 'payment', 'p.datep' => 'payment', 'p.amount' => 'payment', 'p.num_paiement' => 'payment');

		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM ' . MAIN_DB_PREFIX . 'c_chargesociales as cc, ' . MAIN_DB_PREFIX . 'chargesociales as c';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'paiementcharge as p ON p.fk_charge = c.rowid';
		$this->values->export_sql_end[$r] .=' WHERE c.fk_type = cc.id';
		$this->values->export_sql_end[$r] .=' AND c.entity = ' . $conf->entity;
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

		// Nettoyage avant activation
		$this->values->remove($options);

		$sql = array();

		return $this->values->_init($sql, $options);
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

		return $this->values->_remove($sql, $options);
	}

}

?>
