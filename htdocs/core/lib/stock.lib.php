<?php
/* Copyright (C) 2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	    \file       htdocs/core/lib/stock.lib.php
 *		\brief      Library file with function for stock module
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function stock_prepare_head($object)
{
	global $langs, $conf;

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/product/stock/fiche.php?id='.$object->id;
	$head[$h][1] = $langs->trans("WarehouseCard");
	$head[$h][2] = 'card';
	$h++;

	$head[$h][0] = DOL_URL_ROOT.'/product/stock/mouvement.php?id='.$object->id;
	$head[$h][1] = $langs->trans("StockMovements");
	$head[$h][2] = 'movements';
	$h++;

	/*
	$head[$h][0] = DOL_URL_ROOT.'/product/stock/fiche-valo.php?id='.$object->id;
	$head[$h][1] = $langs->trans("EnhancedValue");
	$head[$h][2] = 'value';
	$h++;
	*/

	/* Disabled because will never be implemented. Table always empty.
	if (! empty($conf->global->STOCK_USE_WAREHOUSE_BY_USER))
	{
		// Should not be enabled by defaut because does not work yet correctly because
		// personnal stocks are not tagged into table llx_entrepot
		$head[$h][0] = DOL_URL_ROOT.'/product/stock/user.php?id='.$object->id;
		$head[$h][1] = $langs->trans("Users");
		$head[$h][2] = 'user';
		$h++;
	}
	*/

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf,$langs,$object,$head,$h,'stock');

    $head[$h][0] = DOL_URL_ROOT.'/product/stock/info.php?id='.$object->id;
	$head[$h][1] = $langs->trans("Info");
	$head[$h][2] = 'info';
	$h++;

	return $head;
}

?>