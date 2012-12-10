<?php
/* Copyright (C) 2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * or see http://www.gnu.org/
 */

/**
 *      \file       htdocs/core/modules/societe/mod_codecompta_panicum.php
 *      \ingroup    societe
 *      \brief      File of class to manage accountancy code of thirdparties with Panicum rules
 */
require_once DOL_DOCUMENT_ROOT.'/core/modules/societe/modules_societe.class.php';


/**
 *      \class 		mod_codecompta_panicum
 *		\brief 		Class to manage accountancy code of thirdparties with Panicum rules
 */
class mod_codecompta_panicum extends ModeleAccountancyCode
{
	var $nom='Panicum';
    var $version='dolibarr';        // 'development', 'experimental', 'dolibarr'


	/**
	 * 	Constructor
	 */
	function __construct()
	{
	}


	/**
	 * Return description of module
	 *
	 * @param	string	$langs		Object langs
	 * @return 	string      		Description of module
	 */
	function info($langs)
	{
		return $langs->trans("ModuleCompanyCode".$this->nom);
	}

	/**
	 *  Return an example of result returned by getNextValue
	 *
	 *  @param	Translate	$langs		Object langs
	 *  @param	Societe		$objsoc		Object thirdparty
	 *  @param	int			$type		Type of third party (1:customer, 2:supplier, -1:autodetect)
	 *  @return	string					Example
	 */
	function getExample($langs,$objsoc=0,$type=-1)
	{
		return '';
	}

	/**
	 *  Set accountancy account code for a third party into this->code
	 *
	 *  @param	DoliDB	$db              Database handler
	 *  @param  Societe	$societe         Third party object
	 *  @param  int		$type			'customer' or 'supplier'
	 *  @return	int						>=0 if OK, <0 if KO
	 */
	function get_code($db, $societe, $type='')
	{
		$this->code='';

		if (is_object($societe)) {
			if ($type == 'supplier') $this->code = (! empty($societe->code_compta_fournisseur)?$societe->code_compta_fournisseur:'');
			else $this->code = (! empty($societe->code_compta)?$societe->code_compta:'');
		}

		return 0; // return ok
	}
}

?>
