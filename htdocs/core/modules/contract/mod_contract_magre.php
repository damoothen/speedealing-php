<?php
/* Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
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
 *  \file       htdocs/core/modules/contract/mod_contract_magre.php
 *  \ingroup    contract
 *  \brief      File of class to manage contract numbering rules Magre
 */

require_once DOL_DOCUMENT_ROOT .'/core/modules/contract/modules_contract.php';

/**
 *	Class to manage contract numbering rules Magre
 */
class mod_contract_magre extends ModelNumRefContracts
{
	var $version='dolibarr';
	var $error = '';
	var $nom = 'Magre';

	/**
	 *	Return default description of numbering model
	 *
	 *	@return     string      text description
	 */
	function info()
    {
    	global $conf,$langs;

		$langs->load("bills");

		$form = new Form($this->db);

		$texte = $langs->trans('GenericNumRefModelDesc')."<br>\n";
		$texte.= '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
		$texte.= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		$texte.= '<input type="hidden" name="action" value="updateMask">';
		$texte.= '<input type="hidden" name="maskconstcontract" value="CONTRACT_MAGRE_MASK">';
		$texte.= '<table class="nobordernopadding" width="100%">';

		$tooltip=$langs->trans("GenericMaskCodes",$langs->transnoentities("Contract"),$langs->transnoentities("Contract"));
		$tooltip.=$langs->trans("GenericMaskCodes2");
		$tooltip.=$langs->trans("GenericMaskCodes3");
		$tooltip.=$langs->trans("GenericMaskCodes4a",$langs->transnoentities("Contract"),$langs->transnoentities("Contract"));
		$tooltip.=$langs->trans("GenericMaskCodes5");

		$texte.= '<tr><td>'.$langs->trans("Mask").':</td>';
		$texte.= '<td align="right">'.$form->textwithpicto('<input type="text" class="flat" size="24" name="maskcontract" value="'.$conf->global->CONTRACT_MAGRE_MASK.'">',$tooltip,1,1).'</td>';
		$texte.= '<td align="left" rowspan="2">&nbsp; <input type="submit" class="button" value="'.$langs->trans("Modify").'" name="Button"></td>';
		$texte.= '</tr>';
		$texte.= '</table>';
		$texte.= '</form>';

		return $texte;
    }

	/**
	 *	Return numbering example
	 *
	 *	@return     string      Example
	 */
    function getExample()
    {
     	global $conf,$langs,$mysoc;

    	$old_code_client=$mysoc->code_client;
    	$mysoc->code_client='CCCCCCCCCC';
     	$numExample = $this->getNextValue($mysoc,'');
		$mysoc->code_client=$old_code_client;

		if (! $numExample)
		{
			$numExample = $langs->trans('NotConfigured');
		}
		return $numExample;
    }

	/**
	 *	Return next value
	 *
	 *	@param	Societe		$objsoc     third party object
	 *	@param	Object		$contract	contract object
	 *	@return string      			Value if OK, 0 if KO
	 */
    function getNextValue($objsoc,$contract)
    {
		global $db,$conf;

		require_once DOL_DOCUMENT_ROOT .'/core/lib/functions2.lib.php';

		$mask=$conf->global->CONTRACT_MAGRE_MASK;

		if (! $mask)
		{
			$this->error='NotConfigured';
			return 0;
		}

		$numFinal=get_next_value($db,$mask,'contrat','ref','',$objsoc->code_client,$contract->date_contrat);

		return  $numFinal;
	}

	/**
	 *	Return next value
	 *
	 *	@param	Societe		$objsoc     third party object
	 *	@param	Object		$objforref	contract object
	 *	@return string      			Value if OK, 0 if KO
	 */
    function contract_get_num($objsoc,$objforref)
    {
        return $this->getNextValue($objsoc,$objforref);
    }

}

?>