<?php
/* Copyright (C) 2005-2012	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin		<regis@dolibarr.fr>
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
 *	    \file       htdocs/core/lib/invoice.lib.php
 *		\brief      Functions used by invoice module
 * 		\ingroup	invoice
 */

/**
 * Initialize the array of tabs for customer invoice
 *
 * @param	Facture		$object		Invoice object
 * @return	array					Array of head tabs
 */
function facture_prepare_head($object)
{
	global $langs, $conf;
	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/compta/facture.php?facid='.$object->id;
	$head[$h][1] = $langs->trans('CardBill');
	$head[$h][2] = 'compta';
	$h++;
	
	if (empty($conf->global->MAIN_DISABLE_CONTACTS_TAB))
	{
		$head[$h][0] = DOL_URL_ROOT.'/compta/facture/contact.php?facid='.$object->id;
		$head[$h][1] = $langs->trans('ContactsAddresses');
		$head[$h][2] = 'contact';
		$h++;
	}

	if (! empty($conf->global->MAIN_USE_PREVIEW_TABS))
	{
		$head[$h][0] = DOL_URL_ROOT.'/compta/facture/apercu.php?facid='.$object->id;
		$head[$h][1] = $langs->trans('Preview');
		$head[$h][2] = 'preview';
		$h++;
	}

	//if ($fac->mode_reglement_code == 'PRE')
	if (! empty($conf->prelevement->enabled))
	{
		$head[$h][0] = DOL_URL_ROOT.'/compta/facture/prelevement.php?facid='.$object->id;
		$head[$h][1] = $langs->trans('StandingOrders');
		$head[$h][2] = 'standingorders';
		$h++;
	}

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'invoice');
    
    if (empty($conf->global->MAIN_DISABLE_NOTES_TAB))
    {
    	$head[$h][0] = DOL_URL_ROOT.'/compta/facture/note.php?facid='.$object->id;
    	$head[$h][1] = $langs->trans('Notes');
    	$head[$h][2] = 'note';
    	$h++;
    }

	$head[$h][0] = DOL_URL_ROOT.'/compta/facture/document.php?facid='.$object->id;
	/*$filesdir = $conf->facture->dir_output . "/" . dol_sanitizeFileName($fac->ref);
	include_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
	$listoffiles=dol_dir_list($filesdir,'files',1);
	$head[$h][1] = (count($listoffiles)?$langs->trans('DocumentsNb',count($listoffiles)):$langs->trans('Documents'));*/
	$head[$h][1] = $langs->trans('Documents');
	$head[$h][2] = 'documents';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/compta/facture/info.php?facid='.$object->id;
	$head[$h][1] = $langs->trans('Info');
	$head[$h][2] = 'info';
	$h++;

	return $head;
}

?>