<?php
/* Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *       \file       htdocs/societe/ajaxcountries.php
 *       \brief      File to return Ajax response on country request
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL',1); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');

require '../main.inc.php';

$country=GETPOST('pays', 'alpha');


/*
 * View
 */

// Ajout directives pour resoudre bug IE
//header('Cache-Control: Public, must-revalidate');
//header('Pragma: public');

//top_htmlhead("", "", 1);  // Replaced with top_httphead. An ajax page does not need html header.
top_httphead();

print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

//print '<body id="mainbody">';

dol_syslog(join(',',$_POST));

// Generation liste des pays
if (! empty($country))
{
	global $langs;
	$langs->load("dict");

	$sql = "SELECT rowid, code, libelle, active";
	$sql.= " FROM ".MAIN_DB_PREFIX."c_pays";
	$sql.= " WHERE active = 1 AND libelle LIKE '%" . $db->escape(utf8_decode($country)) . "%'";
	$sql.= " ORDER BY libelle ASC";

	$resql=$db->query($sql);
	if ($resql)
	{
		print '<ul>';
		while($pays = $db->fetch_object($resql))
		{
			print '<li>';
			// Si traduction existe, on l'utilise, sinon on prend le libellé par défaut
			print ($pays->code && $langs->trans("Country".$pays->code)!="Country".$pays->code?$langs->trans("Country".$pays->code):($pays->libelle!='-'?$pays->libelle:'&nbsp;'));
			print '<span class="informal" style="display:none">'.$pays->rowid.'-idcache</span>';
			print '</li>';
		}
		print '</ul>';
	}
}

//print "</body>";
//print "</html>";
?>
