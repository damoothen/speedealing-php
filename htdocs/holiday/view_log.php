<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011      Dimitri Mouillard    <dmouillard@teclib.com>
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
 *  Displays the log of actions performed in the module.
 *
 *  \file       view_log.php
 *  \ingroup    holiday
 */

require('../main.inc.php');
require_once DOL_DOCUMENT_ROOT.'/user/class/user.class.php';
require_once DOL_DOCUMENT_ROOT.'/holiday/common.inc.php';

// Protection if external user
if ($user->societe_id > 0) accessforbidden();

// Si l'utilisateur n'a pas le droit de lire cette page
if(!$user->rights->holiday->view_log) accessforbidden();



/*
 * View
*/

llxHeader(array(),$langs->trans('CPTitreMenu'));


$cp = new Holiday($db);
$log_holiday = $cp->fetchLog('','');

print_fiche_titre($langs->trans('LogCP'));

print '<table class="noborder" width="100%">';
print '<tbody>';
print '<tr class="liste_titre">';

print '<td class="liste_titre">'.$langs->trans('ID').'</td>';
print '<td class="liste_titre" align="center">'.$langs->trans('Date').'</td>';
print '<td class="liste_titre">'.$langs->trans('ActionByCP').'</td>';
print '<td class="liste_titre">'.$langs->trans('UserUpdateCP').'</td>';
print '<td class="liste_titre">'.$langs->trans('ActionTypeCP').'</td>';
print '<td class="liste_titre" align="right">'.$langs->trans('PrevSoldeCP').'</td>';
print '<td class="liste_titre" align="right">'.$langs->trans('NewSoldeCP').'</td>';

print '</tr>';
$var=true;

foreach($cp->logs as $logs_CP)
{
   	$var=!$var;

   	$user_action = new User($db);
   	$user_action->fetch($logs_CP['fk_user_action']);

   	$user_update = new User($db);
   	$user_update->fetch($logs_CP['fk_user_update']);

   	print '<tr '.$bc[$var].'>';
   	print '<td>'.$logs_CP['rowid'].'</td>';
   	print '<td style="text-align: center;">'.$logs_CP['date_action'].'</td>';
   	print '<td>'.$user_action->getFullName($langs).'</td>';
   	print '<td>'.$user_update->getFullName($langs).'</td>';
   	print '<td>'.$logs_CP['type_action'].'</td>';
   	print '<td style="text-align: right;">'.$logs_CP['prev_solde'].' jours</td>';
   	print '<td style="text-align: right;">'.$logs_CP['new_solde'].' jours</td>';
   	print '</tr>'."\n";

}

if($log_holiday == '2')
{
    print '<tr>';
    print '<td colspan="7" class="pair" style="text-align: center; padding: 5px;">'.$langs->trans('NoResult').'</td>';
    print '</tr>';
}

print '</tbody>'."\n";
print '</table>'."\n";


// Fin de page
$db->close();
llxFooter();
?>
