<?php
/* Copyright (C) 2001-2002	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
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
 *      \file       htdocs/admin/system/phpinfo.php
 *		\brief      Page des infos systeme de php
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");

if (! $user->admin)
	accessforbidden();



/*
 * View
 */

llxHeader();

$title='InfoPHP';

if (isset($title))
{
	print_fiche_titre($langs->trans($title), '', 'setup');
}

// Get php_info array
$phparray=phpinfo_array();
foreach($phparray as $key => $value)
{
	//print_titre($key);
	print '<table class="noborder">';
	print '<tr class="liste_titre">';
	//print '<td width="220px">'.$langs->trans("Parameter").'</td>';
	print '<td width="220px">'.$key.'</td>';
	print '<td colspan="2">'.$langs->trans("Value").'</td>';
	print "</tr>\n";

	$var=true;
	//var_dump($value);
	foreach($value as $keyparam => $keyvalue)
	{
		if (! is_array($keyvalue))
		{
			$var=!$var;
			print '<tr '.$bc[$var].'>';
			print '<td>'.$keyparam.'</td>';
			$valtoshow=$keyvalue;
			if ($keyparam == 'X-ChromePhp-Data') $valtoshow=dol_trunc($keyvalue,80);
			print '<td colspan="2">'.$valtoshow.'</td>';
			print '</tr>';
		}
		else
		{
			$var=!$var;
			print '<tr '.$bc[$var].'>';
			print '<td>'.$keyparam.'</td>';
			$i=0;
			foreach($keyvalue as $keyparam2 => $keyvalue2)
			{
				print '<td>';
				$valtoshow=$keyvalue2;
				if ($keyparam == 'disable_functions') $valtoshow=join(', ',explode(',',trim($valtoshow)));
				//print $keyparam2.' = ';
				print $valtoshow;
				$i++;
				print '</td>';
			}
			print '</tr>';
		}
	}
	print '</table><br>';
}


llxFooter();

$db->close();
?>