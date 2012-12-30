<?php
/* Copyright (C) 2010 Regis Houssin  <regis.houssin@capnetworks.com>
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
 *	\file       htdocs/core/modules/project/mod_project_universal.php
 *	\ingroup    project
 *	\brief      Fichier contenant la classe du modele de numerotation de reference de projet Universal
 */

require_once DOL_DOCUMENT_ROOT .'/core/modules/project/modules_project.php';


/**
 * 	Classe du modele de numerotation de reference de projet Universal
 */
class mod_project_universal extends ModeleNumRefProjects
{
	var $version='dolibarr';		// 'development', 'experimental', 'dolibarr'
	var $error = '';
	var $nom = 'Universal';


    /**
     *  Renvoi la description du modele de numerotation
     * 
     *  @return     string      Texte descripif
     */
	function info()
    {
    	global $conf,$langs;

		$langs->load("projects");
		$langs->load("admin");

		$form = new Form($this->db);

		$texte = $langs->trans('GenericNumRefModelDesc')."<br>\n";
		$texte.= '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
		$texte.= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		$texte.= '<input type="hidden" name="action" value="updateMask">';
		$texte.= '<input type="hidden" name="maskconstproject" value="PROJECT_UNIVERSAL_MASK">';
		$texte.= '<table class="nobordernopadding" width="100%">';

		$tooltip=$langs->trans("GenericMaskCodes",$langs->transnoentities("Project"),$langs->transnoentities("Project"));
		$tooltip.=$langs->trans("GenericMaskCodes2");
		$tooltip.=$langs->trans("GenericMaskCodes3");
		$tooltip.=$langs->trans("GenericMaskCodes4a",$langs->transnoentities("Project"),$langs->transnoentities("Project"));
		$tooltip.=$langs->trans("GenericMaskCodes5");

		// Parametrage du prefix
		$texte.= '<tr><td>'.$langs->trans("Mask").':</td>';
		$texte.= '<td align="right">'.$form->textwithpicto('<input type="text" class="flat" size="24" name="maskproject" value="'.$conf->global->PROJECT_UNIVERSAL_MASK.'">',$tooltip,1,1).'</td>';

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
			$numExample = $langs->trans('NotConfigured');
		}
		return $numExample;
    }

   /**
	*  Return next value
	* 
	*  @param	Societe		$objsoc		Object third party
	*  @param   Project		$project	Object project
	*  @return  string					Value if OK, 0 if KO
	*/
    function getNextValue($objsoc,$project)
    {
		global $db,$conf;

		require_once DOL_DOCUMENT_ROOT .'/core/lib/functions2.lib.php';

		// On defini critere recherche compteur
		$mask=$conf->global->PROJECT_UNIVERSAL_MASK;

		if (! $mask)
		{
			$this->error='NotConfigured';
			return 0;
		}

		$date=empty($project->date_c)?dol_now():$project->date_c;
		$numFinal=get_next_value($db,$mask,'projet','ref','',$objsoc->code_client,$date);

		return  $numFinal;
	}


    /**   
     *  Return next reference not yet used as a reference
     * 
     *  @param	Societe		$objsoc     Object third party
     *  @param  Project		$project	Object project
     *  @return string      			Next not used reference
     */
    function project_get_num($objsoc=0,$project='')
    {
        return $this->getNextValue($objsoc,$project);
    }
}

?>