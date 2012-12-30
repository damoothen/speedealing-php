<?php
/* Copyright (C) 2010-2011 	Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2010		Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2011      	Regis Houssin		<regis.houssin@capnetworks.com>
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
 *	\file       htdocs/core/lib/prelevement.lib.php
 *	\brief      Ensemble de fonctions de base pour le module prelevement
 *	\ingroup    propal
 */


/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function prelevement_prepare_head($object)
{
	global $langs, $conf, $user;
	$langs->load("withdrawals");

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/fiche.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'prelevement';
	$h++;

	if (! empty($conf->global->MAIN_USE_PREVIEW_TABS))
	{
		$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/bon.php?id='.$object->id;
		$head[$h][1] = $langs->trans("Preview");
		$head[$h][2] = 'preview';
		$h++;
	}

	$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/lignes.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Lines");
	$head[$h][2] = 'lines';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/factures.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Bills");
	$head[$h][2] = 'invoices';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/fiche-rejet.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Rejects");
	$head[$h][2] = 'rejects';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/fiche-stat.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Statistics");
	$head[$h][2] = 'statistics';
	$h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'prelevement');

    return $head;
}

/**
 *	Check need data to create standigns orders receipt file
 *
 *	@return    	int		-1 if ko 0 if ok
 */
function prelevement_check_config()
{
	global $conf;
    if(empty($conf->global->PRELEVEMENT_USER)) return -1;
	if(empty($conf->global->PRELEVEMENT_ID_BANKACCOUNT)) return -1;
	if(empty($conf->global->PRELEVEMENT_NUMERO_NATIONAL_EMETTEUR)) return -1;
	return 0;
}

?>