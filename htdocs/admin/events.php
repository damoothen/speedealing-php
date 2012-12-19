<?php
/* Copyright (C) 2008-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	    \file       htdocs/admin/events.php
 *      \ingroup    core
 *      \brief      Log event setup page
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/agenda.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/events.class.php';


if (!$user->admin)
accessforbidden();

$langs->load("users");
$langs->load("admin");
$langs->load("other");

$action=GETPOST("action");


$securityevent=new Events($db);
$eventstolog=$securityevent->eventstolog;



/*
 *	Actions
 */
if ($action == "save")
{
	$i=0;

	$db->begin();

	foreach ($eventstolog as $key => $arr)
	{
		$param='MAIN_LOGEVENTS_'.$arr['id'];
		//print "param=".$param." - ".$_POST[$param];
		if (! empty($_POST[$param])) dolibarr_set_const($db,$param,$_POST[$param],'chaine',0,'',$conf->entity);
		else dolibarr_del_const($db,$param,$conf->entity);
	}

	$db->commit();
	$mesg = "<font class=\"ok\">".$langs->trans("SetupSaved")."</font>";
}



/*
 * View
 */

llxHeader('',$langs->trans("Audit"));

//$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("SecuritySetup"),'','setup');

print $langs->trans("LogEventDesc")."<br>\n";
print "<br>\n";

$head=security_prepare_head();

dol_fiche_head($head, 'audit', $langs->trans("Security"));


print '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="save">';

$var=true;
print "<table class=\"noborder\" width=\"100%\">";
print "<tr class=\"liste_titre\">";
print "<td colspan=\"2\">".$langs->trans("LogEvents")."</td>";
print "</tr>\n";
// Loop on each event type
foreach ($eventstolog as $key => $arr)
{
	if ($arr['id'])
	{
		$var=!$var;
		print '<tr '.$bc[$var].'>';
		print '<td>'.$arr['id'].'</td>';
		print '<td>';
		$key='MAIN_LOGEVENTS_'.$arr['id'];
		$value=$conf->global->$key;
		print '<input '.$bc[$var].' type="checkbox" name="'.$key.'" value="1"'.($value?' checked="checked"':'').'>';
		print '</td></tr>'."\n";
	}
}
print '</table>';

print '<br><center>';
print "<input type=\"submit\" name=\"save\" class=\"button\" value=\"".$langs->trans("Save")."\">";
print "</center>";

print "</form>\n";

print '</div>';


dol_htmloutput_mesg($mesg);


$db->close();

llxFooter();
?>
