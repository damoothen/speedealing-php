<?php
/* Copyright (C) 2008 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 	\file       	htdocs/ecm/class/htmlecm.form.class.php
 * 	\brief      	Fichier de la classe des fonctions predefinie de composants html
 */
require_once DOL_DOCUMENT_ROOT.'/ecm/class/ecmdirectory.class.php';


/**
 * \class      	FormEcm
 * \brief      	Classe permettant la generation de composants html
 * \remarks		Only common components must be here.
 */
class FormEcm
{
	var $db;
	var $error;


	/**
	 * 	Constructor
	 *
	 * 	@param	DoliDB	$db		Database handler
	 */
	function __construct($db)
	{
		$this->db = $db;
	}


	/**
	 *	Retourne la liste des categories du type choisi
	 *
	 *  @param	int		$selected    		Id categorie preselectionnee
	 *  @param  string	$select_name		Nom formulaire HTML
	 *  @return	string						String with HTML select
	 */
	function select_all_sections($selected='',$select_name='')
	{
		global $langs;
		$langs->load("ecm");

		if ($select_name=="") $select_name="catParent";

		$cat = new EcmDirectory($this->db);
		$cate_arbo = $cat->get_full_arbo();

		$output = '<select class="flat" name="'.$select_name.'">';
		if (is_array($cate_arbo))
		{
			if (! count($cate_arbo)) $output.= '<option value="-1" disabled="disabled">'.$langs->trans("NoCategoriesDefined").'</option>';
			else
			{
				$output.= '<option value="-1">&nbsp;</option>';
				foreach($cate_arbo as $key => $value)
				{
					if ($cate_arbo[$key]['id'] == $selected)
					{
						$add = 'selected="selected" ';
					}
					else
					{
						$add = '';
					}
					$output.= '<option '.$add.'value="'.$cate_arbo[$key]['id'].'">'.$cate_arbo[$key]['fulllabel'].'</option>';
				}
			}
		}
		$output.= '</select>';
		$output.= "\n";
		return $output;
	}
}

?>
