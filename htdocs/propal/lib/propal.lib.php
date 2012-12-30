<?php
/* Copyright (C) 2006-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2012 David Moothen        <dmoothen@websitti.fr>
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
 *	\file       htdocs/core/lib/propal.lib.php
 *	\brief      Ensemble de fonctions de base pour le module propal
 *	\ingroup    propal
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function propal_prepare_head($object)
{
	global $langs, $conf, $user;
	$langs->load("propal");
	$langs->load("compta");

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/propal/propal.php?id='.$object->id;
	$head[$h][1] = $langs->trans('ProposalCard');
	$head[$h][2] = 'comm';
	$h++;

	if ((empty($conf->commande->enabled) &&	((! empty($conf->expedition_bon->enabled) && $user->rights->expedition->lire)
	|| (! empty($conf->livraison_bon->enabled) && $user->rights->expedition->livraison->lire))))
	{
		$langs->load("sendings");
		$head[$h][0] = DOL_URL_ROOT.'/expedition/propal.php?id='.$object->id;
		if ($conf->expedition_bon->enabled) $text=$langs->trans("Sendings");
		if ($conf->livraison_bon->enabled)  $text.='/'.$langs->trans("Receivings");
		$head[$h][1] = $text;
		$head[$h][2] = 'shipping';
		$h++;
	}
	if (! empty($conf->global->MAIN_USE_PREVIEW_TABS))
	{
		$head[$h][0] = DOL_URL_ROOT.'/propal/apercu.php?id='.$object->id;
		$head[$h][1] = $langs->trans("Preview");
		$head[$h][2] = 'preview';
		$h++;
	}

	if (empty($conf->global->MAIN_DISABLE_CONTACTS_TAB))
	{
		$head[$h][0] = DOL_URL_ROOT.'/propal/contact.php?id='.$object->id;
		$head[$h][1] = $langs->trans('ContactsAddresses');
		$head[$h][2] = 'contact';
		$h++;
	}

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'propal');

    if (empty($conf->global->MAIN_DISABLE_NOTES_TAB))
    {
	    $head[$h][0] = DOL_URL_ROOT.'/propal/note.php?id='.$object->id;
		$head[$h][1] = $langs->trans('Notes');
		$head[$h][2] = 'note';
		$h++;
    }

	$head[$h][0] = DOL_URL_ROOT.'/propal/document.php?id='.$object->id;
	/*$filesdir = $conf->propal->dir_output . "/" . dol_sanitizeFileName($propal->ref);
	include_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
	$listoffiles=dol_dir_list($filesdir,'files',1);
	$head[$h][1] = (count($listoffiles)?$langs->trans('DocumentsNb',count($listoffiles)):$langs->trans('Documents'));*/
	$head[$h][1] = $langs->trans('Documents');
	$head[$h][2] = 'document';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/propal/info.php?id='.$object->id;
	$head[$h][1] = $langs->trans('Info');
	$head[$h][2] = 'info';
	$h++;

	return $head;
}


?>