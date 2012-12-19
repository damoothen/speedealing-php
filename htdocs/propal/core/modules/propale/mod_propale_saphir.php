<?php
/* Copyright (C) 2003-2007 Rodolphe Quiedeville        <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur         <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2007 Regis Houssin               <regis@dolibarr.fr>
 * Copyright (C) 2008      Raphael Bertrand (Resultic) <raphael.bertrand@resultic.fr>
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
 * \file       htdocs/core/modules/propale/mod_propale_saphir.php
 * \ingroup    propale
 * \brief      File that contains the numbering module rules Saphir
 */

require_once DOL_DOCUMENT_ROOT .'/core/modules/propale/modules_propale.php';


/**
 * \class      mod_propale_saphir
 * \brief      Class of file that contains the numbering module rules Saphir
 */
class mod_propale_saphir extends ModeleNumRefPropales
{
	var $version='dolibarr';		// 'development', 'experimental', 'dolibarr'
	var $error = '';
	var $nom = 'Saphir';


    /**
     *  Return description of module
     *
     *  @return     string      Texte descripif
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
		$texte.= '<input type="hidden" name="maskconstpropal" value="PROPALE_SAPHIR_MASK">';
		$texte.= '<table class="nobordernopadding" width="100%">';

		$tooltip=$langs->trans("GenericMaskCodes",$langs->transnoentities("Proposal"),$langs->transnoentities("Proposal"));
		$tooltip.=$langs->trans("GenericMaskCodes2");
		$tooltip.=$langs->trans("GenericMaskCodes3");
		$tooltip.=$langs->trans("GenericMaskCodes4a",$langs->transnoentities("Proposal"),$langs->transnoentities("Proposal"));
		$tooltip.=$langs->trans("GenericMaskCodes5");

		// Parametrage du prefix
		$texte.= '<tr><td>'.$langs->trans("Mask").':</td>';
		$texte.= '<td align="right">'.$form->textwithpicto('<input type="text" class="flat" size="24" name="maskpropal" value="'.$conf->global->PROPALE_SAPHIR_MASK.'">',$tooltip,1,1).'</td>';

		$texte.= '<td align="left" rowspan="2">&nbsp; <input type="submit" class="button" value="'.$langs->trans("Modify").'" name="Button"></td>';

		$texte.= '</tr>';

		$texte.= '</table>';
		$texte.= '</form>';

		return $texte;
    }

    /**
     *  Renvoi un exemple de numerotation
     *
     *  @return     string      Example
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
			$numExample = 'NotConfigured';
		}
		return $numExample;
    }

	/**
	 *  Return next value
	 *
	 *  @param	Societe		$objsoc     Object third party
	 * 	@param	Propal		$propal		Object commercial proposal
	 *  @return string      			Value if OK, 0 if KO
	 */
	function getNextValue($objsoc,$propal)
	{
		global $db,$conf;

		require_once DOL_DOCUMENT_ROOT .'/core/lib/functions2.lib.php';

		// On defini critere recherche compteur
		$mask=$conf->global->PROPALE_SAPHIR_MASK;

		if (! $mask)
		{
			$this->error='NotConfigured';
			return 0;
		}

		$date=$propal->datep;
		$customercode=$objsoc->code_client;
		$numFinal=get_next_value($db,$mask,'propal','ref','',$customercode,$date);

		return  $numFinal;
	}

}
?>