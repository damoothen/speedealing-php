<?php
/* Copyright (C) 2012 Laurent Destailleur <eldy@users.sourceforge.net>
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
 *       \file       htdocs/core/ajax/bankconciliate.php
 *       \brief      File to set data for bank concilation
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');    // Required to knwo date format for dol_print_date

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';

$action=GETPOST('action');


/*
 * View
 */

// Ajout directives pour resoudre bug IE
//header('Cache-Control: Public, must-revalidate');
//header('Pragma: public');

//top_htmlhead("", "", 1);  // Replaced with top_httphead. An ajax page does not need html header.
top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (($user->rights->banque->modifier || $user->rights->banque->consolidate) && $action == 'dvnext')
{
	// Increase date
	$al =new AccountLine($db);
    $al->datev_next($_GET["rowid"]);
    $al->fetch($_GET["rowid"]);

    print '<span>'.dol_print_date($db->jdate($al->datev),"day").'</span>';

    exit;
}

if (($user->rights->banque->modifier || $user->rights->banque->consolidate) && $action == 'dvprev')
{
	// Decrease date
	$al =new AccountLine($db);
    $al->datev_previous($_GET["rowid"]);
    $al->fetch($_GET["rowid"]);

    print '<span>'.dol_print_date($db->jdate($al->datev),"day").'</span>';

    exit;
}

?>
