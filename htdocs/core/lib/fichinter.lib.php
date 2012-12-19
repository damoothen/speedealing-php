<?php
/* Copyright (C) 2006-2007	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2007		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2012		Regis Houssin			<regis@dolibarr.fr>
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
 *	\file       htdocs/core/lib/fichinter.lib.php
 *	\brief      Ensemble de fonctions de base pour le module fichinter
 *	\ingroup    fichinter
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function fichinter_prepare_head($object)
{
	global $langs, $conf, $user;
	$langs->load("fichinter");

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/fichinter/fiche.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'card';
	$h++;
	
	if (empty($conf->global->MAIN_DISABLE_CONTACTS_TAB))
	{
		$head[$h][0] = DOL_URL_ROOT.'/fichinter/contact.php?id='.$object->id;
		$head[$h][1] = $langs->trans('InterventionContact');
		$head[$h][2] = 'contact';
		$h++;
	}

	if (! empty($conf->global->MAIN_USE_PREVIEW_TABS))
	{
		$head[$h][0] = DOL_URL_ROOT.'/fichinter/apercu.php?id='.$object->id;
		$head[$h][1] = $langs->trans('Preview');
		$head[$h][2] = 'preview';
		$h++;
	}

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'intervention');
    
    if (empty($conf->global->MAIN_DISABLE_NOTES_TAB))
    {
    	$head[$h][0] = DOL_URL_ROOT.'/fichinter/note.php?id='.$object->id;
    	$head[$h][1] = $langs->trans('Notes');
    	$head[$h][2] = 'note';
    	$h++;
    }

	$head[$h][0] = DOL_URL_ROOT.'/fichinter/document.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Documents");
	$head[$h][2] = 'documents';
	$h++;

    $head[$h][0] = DOL_URL_ROOT.'/fichinter/info.php?id='.$object->id;
	$head[$h][1] = $langs->trans('Info');
	$head[$h][2] = 'info';
	$h++;

	return $head;
}

?>
