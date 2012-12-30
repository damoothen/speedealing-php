<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copytight (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *		\file   	htdocs/compta/bank/pre.inc.php
 *		\ingroup    compta
 *		\brief  	Fichier gestionnaire du menu compta banque
 */

require_once realpath(dirname(__FILE__)) . '/../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';

$langs->load("banks");
$langs->load("categories");


/**
 * Replace the default llxHeader function
 *
 * @param 	string 	$head		Optionnal head lines
 * @param 	string 	$title		HTML title
 * @param 	string 	$help_url	Link to online url help to show on left menu
 * @param 	string 	$target		Force target on menu links
 * @param 	int    	$disablejs	More content into html header
 * @param 	int    	$disablehead	More content into html header
 * @param 	array  	$arrayofjs	Array of complementary js files
 * @param 	array  	$arrayofcss	Array of complementary css files
 * @return	none
 */
function llxHeader($head = '', $title='', $help_url='', $target='', $disablejs=0, $disablehead=0, $arrayofjs='', $arrayofcss='')
{
	global $db, $user, $conf, $langs;

	top_htmlhead($head, $title, $disablejs, $disablehead, $arrayofjs, $arrayofcss);	// Show html headers
	top_menu($head, $title, $target, $disablejs, $disablehead, $arrayofjs, $arrayofcss);	// Show html headers

	$menu = new Menu();

	// Entry for each bank account
	if ($user->rights->banque->lire)
	{
		$sql = "SELECT rowid, label, courant, rappro, courant";
		$sql.= " FROM ".MAIN_DB_PREFIX."bank_account";
		$sql.= " WHERE entity = ".$conf->entity;
		$sql.= " AND clos = 0";
        $sql.= " ORDER BY label";

		$resql = $db->query($sql);
		if ($resql)
		{
			$numr = $db->num_rows($resql);
			$i = 0;

			if ($numr > 0) 	$menu->add('/compta/bank/index.php',$langs->trans("BankAccounts"),0,$user->rights->banque->lire);

			while ($i < $numr)
			{
				$objp = $db->fetch_object($resql);
				$menu->add('/compta/bank/fiche.php?id='.$objp->rowid,$objp->label,1,$user->rights->banque->lire);
                if ($objp->rappro && $objp->courant != 2 && empty($objp->clos))  // If not cash account and not closed and can be reconciliate
                {
				    $menu->add('/compta/bank/rappro.php?account='.$objp->rowid,$langs->trans("Conciliate"),2,$user->rights->banque->consolidate);
                }
				$i++;
			}
		}
		else dol_print_error($db);
		$db->free($resql);
	}

	left_menu('', $help_url, '', $menu->liste, 1);
    main_area();
}
?>
