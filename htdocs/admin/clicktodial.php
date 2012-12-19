<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.org>
 * Copyright (C) 2011 	    Juanjo Menent		<jmenent@2byte.es>
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
 *   \file       htdocs/admin/clicktodial.php
 *   \ingroup    clicktodial
 *   \brief      Page to setup module clicktodial
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");

if (!$user->admin) accessforbidden();

$action = GETPOST("action");


/*
 *	Actions
 */
if ($action == 'setvalue' && $user->admin)
{
    $result=dolibarr_set_const($db, "CLICKTODIAL_URL",GETPOST("url"),'chaine',0,'',$conf->entity);
    if ($result >= 0)
    {
        $mesg = "<font class=\"ok\">".$langs->trans("SetupSaved")."</font>";
    }
    else
    {
        $mesg = "<font class=\"error\">".$langs->trans("Error")."</font>";
    }
}


/*
 * View
 */

$wikihelp='EN:Module_ClickToDial_En|FR:Module_ClickToDial|ES:Módulo_ClickTodial_Es';
llxHeader('',$langs->trans("ClickToDialSetup"),$wikihelp);

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("ClickToDialSetup"),$linkback,'setup');

print $langs->trans("ClickToDialDesc")."<br>\n";

print '<br>';
print '<form method="post" action="clicktodial.php">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="setvalue">';

$var=true;

print '<table class="nobordernopadding" width="100%">';
print '<tr class="liste_titre">';
print '<td width="120">'.$langs->trans("Name").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print "</tr>\n";
$var=!$var;
print '<tr '.$bc[$var].'><td valign="top">';
print $langs->trans("URL").'</td><td>';
print '<input size="92" type="text" name="url" value="'.$conf->global->CLICKTODIAL_URL.'"><br>';
print '<br>';
print $langs->trans("ClickToDialUrlDesc").'<br>';
print $langs->trans("Example").':<br>http://myphoneserver/mypage?login=__LOGIN__&password=__PASS__&caller=__PHONEFROM__&called=__PHONETO__';
print '</td></tr>';

print '<tr><td colspan="3" align="center"><br><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td></tr>';
print '</table></form>';

/*if (! empty($conf->global->CLICKTODIAL_URL))
 {
 print $langs->trans("Test");
 // Add a phone number to test
 }
 */

dol_htmloutput_mesg($mesg);

$db->close();

llxFooter();
?>
