<?php
/* Copyright (C) 2006-2012	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2009-2012	Regis Houssin		<regis.houssin@capnetworks.com>
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
 * \file       htdocs/core/lib/contract.lib.php
 * \brief      Ensemble de fonctions de base pour le module contrat
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function contract_prepare_head($object)
{
	global $langs, $conf;
	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/contrat/fiche.php?id='.$object->id;
	$head[$h][1] = $langs->trans("ContractCard");
	$head[$h][2] = 'card';
	$h++;
	
	if (empty($conf->global->MAIN_DISABLE_CONTACTS_TAB))
	{
		$head[$h][0] = DOL_URL_ROOT.'/contrat/contact.php?id='.$object->id;
		$head[$h][1] = $langs->trans("ContactsAddresses");
		$head[$h][2] = 'contact';
		$h++;
	}

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'contract');
    
    if (empty($conf->global->MAIN_DISABLE_NOTES_TAB))
    {
    	$head[$h][0] = DOL_URL_ROOT.'/contrat/note.php?id='.$object->id;
    	$head[$h][1] = $langs->trans("Note");
    	$head[$h][2] = 'note';
    	$h++;
    }

	$head[$h][0] = DOL_URL_ROOT.'/contrat/document.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Documents");
	$head[$h][2] = 'documents';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/contrat/info.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Info");
	$head[$h][2] = 'info';
	$h++;

	return $head;
}

/**
 *  Return array head with list of tabs to view object informations.
 *
 *  @param	Object	$object		Thirdparty
 *  @return	array   	        head array with tabs
 */
function contract_admin_prepare_head($object)
{
    global $langs, $conf, $user;

    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT.'/contrat/admin/contract.php';
    $head[$h][1] = $langs->trans("Miscellanous");
    $head[$h][2] = 'general';
    $h++;
    
    $head[$h][0] = DOL_URL_ROOT.'/contrat/admin/document.php';
    $head[$h][1] = $langs->trans("Documents");
    $head[$h][2] = 'files';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    //complete_head_from_modules($conf,$langs,$object,$head,$h,'contrat_admin');
    
    //configuration des modèles de contrat odt
    $dirtoscan.=($dirtoscan?',':'').preg_replace('/[\r\n]+/',',',trim($conf->global->CONTRAT_ADDON_PDF_ODT_PATH));
    include_once(DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php');

    $listoffiles=array();

    // Now we add models found in directories scanned
    $listofdir=explode(',',$dirtoscan);
    foreach($listofdir as $key=>$tmpdir)
    {
        $tmpdir=trim($tmpdir);
        if($conf->multicompany->enabled && $conf->entity > 1)
            $tmpdir=preg_replace('/DOL_DATA_ROOT/',DOL_DATA_ROOT."/".$conf->entity,$tmpdir);
        else
            $tmpdir=preg_replace('/DOL_DATA_ROOT/',DOL_DATA_ROOT,$tmpdir);
                    
        if (! $tmpdir) { unset($listofdir[$key]); continue; }
        if (is_dir($tmpdir))
        {
            $tmpfiles=dol_dir_list($tmpdir,'files',0,'\.odt');
            if (count($tmpfiles)) $listoffiles=array_merge($listoffiles,$tmpfiles);
        }
    }
    /*if (count($listoffiles) && $conf->extrafields->enabled)
    {
        foreach($listoffiles as $record)
        {
            $head[$h][0] = dol_buildpath('/extrafields/admin/contrat_extrafields.php?ref='.$record['name'],1);
            $head[$h][1] = $record['name'];
            $head[$h][2] = $record['name'];
            $h++;
        }
    }
    else
    {
            $head[$h][0] = DOL_URL_ROOT.'/contrat/admin/contrat_extrafields.php?';
            $head[$h][1] = $langs->trans("ExtraFields");
            $head[$h][2] = 'attributes';
            $h++;
    }*/

    complete_head_from_modules($conf,$langs,$object,$head,$h,'contrat_admin','remove');

    return $head;
}


?>