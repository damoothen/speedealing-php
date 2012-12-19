<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.org>
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
 *	    \file       htdocs/admin/notification.php
 *		\ingroup    notification
 *		\brief      Page to setup notification module
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/triggers/interface_50_modNotification_Notification.class.php';

$langs->load("admin");
$langs->load("other");

// Security check
if (!$user->admin)
  accessforbidden();

$action = GETPOST("action");

/*
 * Actions
 */

if ($action == 'setvalue' && $user->admin)
{
	$result=dolibarr_set_const($db, "NOTIFICATION_EMAIL_FROM",$_POST["email_from"],'chaine',0,'',$conf->entity);
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
 *	View
 */

llxHeader();

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("NotificationSetup"),$linkback,'setup');

print $langs->trans("NotificationsDesc").'<br><br>';	

print '<form method="post" action="'.$_SERVER["PHP_SELF"].'">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="setvalue">';

$var=true;

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameter").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print "</tr>\n";
$var=!$var;
print '<tr '.$bc[$var].'><td>';
print $langs->trans("NotificationEMailFrom").'</td><td>';
print '<input size="32" type="text" name="email_from" value="'.$conf->global->NOTIFICATION_EMAIL_FROM.'">';
if (! empty($conf->global->NOTIFICATION_EMAIL_FROM) && ! isValidEmail($conf->global->NOTIFICATION_EMAIL_FROM)) print ' '.img_warning($langs->trans("BadEMail"));
print '</td></tr>';
print '</table>';

print '<br>';

print '<center><input type="submit" class="button" value="'.$langs->trans("Modify").'"></center>';

print '</form>';
print '<br>';


print_fiche_titre($langs->trans("ListOfAvailableNotifications"),'','');

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Module").'</td>';
print '<td>'.$langs->trans("Code").'</td>';
print '<td>'.$langs->trans("Label").'</td>';
print "</tr>\n";

// Load array of available notifications
$notificationtrigger=new InterfaceNotification($db);
$listofnotifiedevents=$notificationtrigger->getListOfManagedEvents();

foreach($listofnotifiedevents as $notifiedevent)
{
    $var=!$var;
    $label=$langs->trans("Notify_".$notifiedevent['code']); //!=$langs->trans("Notify_".$notifiedevent['code'])?$langs->trans("Notify_".$notifiedevent['code']):$notifiedevent['label'];
    print '<tr '.$bc[$var].'>';
    print '<td>'.$notifiedevent['elementtype'].'</td>';
    print '<td>'.$notifiedevent['code'].'</td>';
    print '<td>'.$label.'</td>';
    print '</tr>';
}
print '</table>';

dol_htmloutput_mesg($mesg);

$db->close();

llxFooter();
?>
