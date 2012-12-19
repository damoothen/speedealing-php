<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2009 Laurent Destailleur  <eldy@users.sourceforge.org>
 * Copyright (C) 2011-2012 Juanjo Menent        <jmenent@2byte.es>
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

/**	    \file       htdocs/bookmarks/admin/bookmark.php
 *		\ingroup    bookmark
 *		\brief      Page to setup bookmark module
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");

if (!$user->admin)
accessforbidden();

$action=GETPOST('action','alpha');

if ($action == 'setvalue')
{
	$showmenu = GETPOST('BOOKMARKS_SHOW_IN_MENU','alpha');
	$res = dolibarr_set_const($db, "BOOKMARKS_SHOW_IN_MENU",$showmenu,'chaine',0,'',$conf->entity);

	if (! $res > 0) $error++;

	if (! $error)
	{
		$db->commit();
		$mesg = "<font class=\"ok\">".$langs->trans("SetupSaved")."</font>";
	}
	else
	{
		$db->rollback();
		$mesg = "<font class=\"error\">".$langs->trans("Error")."</font>";
	}
}


/*
 * View
 */

llxHeader();

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("BookmarkSetup"),$linkback,'setup');

print $langs->trans("BookmarkDesc")."<br>\n";

print '<br>';
print '<form method="post" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="setvalue">';

$var=true;

print '<table summary="bookmarklist" class="notopnoleftnoright" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Name").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print "</tr>\n";
$var=!$var;
print '<tr '.$bc[$var].'><td>';
print $langs->trans("NbOfBoomarkToShow").'</td><td>';
print '<input size="3" type="text" name="BOOKMARKS_SHOW_IN_MENU" value="'.$conf->global->BOOKMARKS_SHOW_IN_MENU.'">';
print '</td></tr>';

print '<tr><td colspan="2" align="center"><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td></tr>';
print '</table></form>';

dol_htmloutput_mesg($mesg);

$db->close();

llxFooter();
?>
