<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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
 *	\file       htdocs/admin/workflow.php
 *	\ingroup    company
 *	\brief      Workflows setup page
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");
$langs->load("workflow");

if (! $user->admin) accessforbidden();

$action = GETPOST('action', 'alpha');

/*
 * Actions
 */
if (preg_match('/set(.*)/',$action,$reg))
{
    if (! dolibarr_set_const($db, $reg[1], 1, 'chaine', 0, '', $conf->entity) > 0)
    {
        dol_print_error($db);
    }
}

if (preg_match('/del(.*)/',$action,$reg))
{
    if (! dolibarr_del_const($db, $reg[1], $conf->entity) > 0)
    {
        dol_print_error($db);
    }
}


/*
 * 	View
 */

llxHeader('',$langs->trans("WorkflowSetup"),'');

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("WorkflowSetup"),$linkback,'setup');

print $langs->trans("WorkflowDesc").'<br>';
print "<br>";

// List of workflow we can enable

print '<table class="noborder" width="100%">'."\n";
print '<tr class="liste_titre">'."\n";
print '  <td>'.$langs->trans("Description").'</td>';
print '  <td align="center">'.$langs->trans("Status").'</td>';
print "</tr>\n";

clearstatcache();

$workflowcodes=array(
	'WORKFLOW_PROPAL_AUTOCREATE_ORDER'=>array('enabled'=>'! empty($conf->propal->enabled) && ! empty($conf->commande->enabled)', 'picto'=>'order'),
	'WORKFLOW_ORDER_CLASSIFY_BILLED_PROPAL'=>array('enabled'=>'! empty($conf->propal->enabled) && ! empty($conf->commande->enabled)', 'picto'=>'order','warning'=>'WarningCloseAlways'),
	'WORKFLOW_ORDER_AUTOCREATE_INVOICE'=>array('enabled'=>'! empty($conf->commande->enabled) && ! empty($conf->facture->enabled)', 'picto'=>'bill'),
	'WORKFLOW_INVOICE_CLASSIFY_BILLED_ORDER'=>array('enabled'=>'! empty($conf->facture->enabled) && ! empty($conf->commande->enabled)', 'picto'=>'bill','warning'=>'WarningCloseAlways'),
);

if (! empty($conf->modules_parts['workflow']) && is_array($conf->modules_parts['workflow']))
{
	foreach($conf->modules_parts['workflow'] as $workflow)
	{
		$workflowcodes = array_merge($workflowcodes, $workflow);
	}
}

$nbqualified=0;

foreach($workflowcodes as $key => $params)
{
	$picto=$params['picto'];
	$enabled=$params['enabled'];
   	if (! verifCond($enabled)) continue;

   	$nbqualified++;
	$var = !$var;
   	print "<tr ".$bc[$var].">\n";
   	print "<td>".img_object('', $picto).$langs->trans('desc'.$key);
   	if (! empty($params['warning']))
   	{
   		$langs->load("errors");
   		print ' '.img_warning($langs->transnoentitiesnoconv($params['warning']));
   	}
   	print "</td>\n";
   	print '<td align="center">';
   	if (! empty($conf->use_javascript_ajax))
   	{
   		print ajax_constantonoff($key);
   	}
   	else
   	{
   		if (! empty($conf->global->$key))
   		{
   			print '<a href="'.$_SERVER['PHP_SELF'].'?action=del'.$key.'">';
  			print img_picto($langs->trans("Activated"),'switch_on');
   			print '</a>';
   		}
   		else
   		{
   			print '<a href="'.$_SERVER['PHP_SELF'].'?action=set'.$key.'">';
  			print img_picto($langs->trans("Disabled"),'switch_off');
   			print '</a>';
   		}
   	}
   	print '</td>';
   	print '</tr>';
}

if ($nbqualified == 0)
{
    print '<tr><td colspan="3">'.$langs->trans("ThereIsNoWorkflowToModify");
}
print '</table>';


llxFooter();

$db->close();
?>
