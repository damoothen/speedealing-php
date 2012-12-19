<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *   	\file       htdocs/admin/index.php
 *		\brief      Page d'accueil de l'espace administration/configuration
 */

require '../main.inc.php';

$langs->load("admin");
$langs->load("companies");

if (!$user->admin) accessforbidden();

$mesg='';


/*
 * View
 */

$wikihelp='EN:First_setup|FR:Premiers_paramétrages|ES:Primeras_configuraciones';
llxHeader('',$langs->trans("Setup"),$wikihelp);

$form = new Form($db);


print_fiche_titre($langs->trans("SetupArea"),'','setup');

if ($mesg) print $mesg.'<br>';

print $langs->trans("SetupDescription1").' ';
print $langs->trans("AreaForAdminOnly").' ';


//print "<br>";
//print "<br>";
print $langs->trans("SetupDescription2")."<br><br>";

print '<br>';
//print '<hr style="color: #DDDDDD;">';
print img_picto('','puce').' '.$langs->trans("SetupDescription3",DOL_URL_ROOT.'/admin/company.php?mainmenu=home');
if (empty($conf->global->MAIN_INFO_SOCIETE_NOM) || empty($conf->global->MAIN_INFO_SOCIETE_PAYS))
{
	$langs->load("errors");
	$warnpicto=img_warning($langs->trans("WarningMandatorySetupNotComplete"));
	print '<br><a href="'.DOL_URL_ROOT.'/admin/company.php?mainmenu=home">'.$warnpicto.' '.$langs->trans("WarningMandatorySetupNotComplete").'</a>';
}
print '<br>';
print '<br>';
print '<br>';
//print '<hr style="color: #DDDDDD;">';
print img_picto('','puce').' '.$langs->trans("SetupDescription4",DOL_URL_ROOT.'/admin/modules.php?mainmenu=home');
if (count($conf->modules) <= 1)	// If only user module enabled
{
	$langs->load("errors");
	$warnpicto=img_warning($langs->trans("WarningMandatorySetupNotComplete"));
	print '<br><a href="'.DOL_URL_ROOT.'/admin/modules.php?mainmenu=home">'.$warnpicto.' '.$langs->trans("WarningMandatorySetupNotComplete").'</a>';
}
print '<br>';
print '<br>';
print '<br>';
//print '<hr style="color: #DDDDDD;">';
print $langs->trans("SetupDescription5")."<br>";
//print '<hr style="color: #DDDDDD;">';
print "<br>";

/*
print '<table width="100%">';
print '<tr '.$bc[false].'><td '.$bc[false].'>'.img_picto('','puce').' '.$langs->trans("SetupDescription3")."</td></tr>";
print '<tr '.$bc[true].'><td '.$bc[true].'>'.img_picto('','puce').' '.$langs->trans("SetupDescription4")."</td></tr>";
print '<tr '.$bc[false].'><td '.$bc[false].'>'.img_picto('','puce').' '.$langs->trans("SetupDescription5")."</td></tr>";
print '</table>';
*/

//print '<br>';
//print info_admin($langs->trans("OnceSetupFinishedCreateUsers")).'<br>';


$db->close();

llxFooter();
?>
