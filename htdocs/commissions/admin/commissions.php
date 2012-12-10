<?php
/* Copyright (C) 2012	Christophe Battarel	<christophe.battarel@altairis.fr>
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
 *      \file       /htdocs/admin/commissions.php
 *		\ingroup    commissions
 *		\brief      Page to setup advanced commissions module
 */

include '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/commissions/lib/commissions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once(DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php');
require_once(DOL_DOCUMENT_ROOT."/compta/facture/class/facture.class.php");

$langs->load("admin");
$langs->load("bills");
$langs->load("commissions");

if (! $user->admin) accessforbidden();


/*
 * Action
 */
if (GETPOST('commissionBase'))
{
    if (dolibarr_set_const($db, 'COMMISSION_BASE', GETPOST('commissionBase'), 'string', 0, '', $conf->entity) > 0)
    {
          $conf->global->COMMISSION_BASE = GETPOST('commissionBase');
          setEventMessage($langs->trans("RecordModifiedSuccessfully"));
    }
    else
    {
        dol_print_error($db);
    }
}

if (GETPOST('productCommissionRate'))
{
    if (dolibarr_set_const($db, 'PRODUCT_COMMISSION_RATE', GETPOST('productCommissionRate'), 'rate', 0, '', $conf->entity) > 0)
    {
    }
    else
    {
        dol_print_error($db);
    }
}

if (GETPOST('serviceCommissionRate'))
{
    if (dolibarr_set_const($db, 'SERVICE_COMMISSION_RATE', GETPOST('serviceCommissionRate'), 'rate', 0, '', $conf->entity) > 0)
    {
    }
    else
    {
        dol_print_error($db);
    }
}

if (GETPOST('AGENT_CONTACT_TYPE'))
{
    if (dolibarr_set_const($db, 'AGENT_CONTACT_TYPE', GETPOST('AGENT_CONTACT_TYPE'), 'chaine', 0, '', $conf->entity) > 0)
    {
          $conf->global->AGENT_CONTACT_TYPE = GETPOST('AGENT_CONTACT_TYPE');
    }
    else
    {
        dol_print_error($db);
    }
}

/*
 * View
 */

llxHeader('',$langs->trans("CommissionsSetup"));


$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("commissionsSetup"),$linkback,'setup');


$head = commissions_admin_prepare_head();

dol_fiche_head($head, 'parameters', $langs->trans("Commissions"), 0, 'commissions');

print "<br>";


print_fiche_titre($langs->trans("MemberMainOptions"),'','');
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Description").'</td>';
print '<td align="left">'.$langs->trans("Value").'</td>'."\n";
print '<td align="left">'.$langs->trans("Details").'</td>'."\n";
print '</tr>';

$var=true;
$form = new Form($db);

print '<form method="post">';

// COMMISSION BASE (TURNOVER / MARGIN)
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("CommissionBase").'</td>';
print '<td align="left">';
print '<input type="radio" name="commissionBase" value="TURNOVER" ';
if (isset($conf->global->COMMISSION_BASE) && $conf->global->COMMISSION_BASE == "TURNOVER")
  print 'checked';
print ' />';
print $langs->trans("CommissionBasedOnTurnover");
print '<br/>';
print '<input type="radio" name="commissionBase" value="MARGIN" ';
if (empty($conf->margin->enabled))
  print 'disabled';
elseif (isset($conf->global->COMMISSION_BASE) && $conf->global->COMMISSION_BASE == "MARGIN")
  print 'checked';
print ' />';
print $langs->trans("CommissionBasedOnMargins");
print '</td>';
print '<td>'.$langs->trans('CommissionBaseDetails');
print '<br/>';
print $langs->trans('CommissionBasedOnMarginsDetails');
print '</td>';
print '</tr>';

// PRODUCT COMMISSION RATE
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ProductCommissionRate").'</td>';
print '<td align="left">';
print '<input type="text" name="productCommissionRate" value="'.(! empty($conf->global->PRODUCT_COMMISSION_RATE)?$conf->global->PRODUCT_COMMISSION_RATE:'').'" size=6 />&nbsp; %';
print '</td>';
print '<td>'.$langs->trans('ProductCommissionRateDetails').'</td>';
print '</tr>';

// SERVICE COMMISSION RATE
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ServiceCommissionRate").'</td>';
print '<td align="left">';
print '<input type="text" name="serviceCommissionRate" value="'.(! empty($conf->global->SERVICE_COMMISSION_RATE)?$conf->global->SERVICE_COMMISSION_RATE:'').'" size=6 />&nbsp; %';
print '</td>';
print '<td>'.$langs->trans('ServiceCommissionRateDetails').'</td>';
print '</tr>';

// INTERNAL CONTACT TYPE USED AS COMMERCIAL AGENT
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("AgentContactType").'</td>';
print '<td align="left">';
$formcompany = new FormCompany($db);
$facture = new Facture($db);
print $formcompany->selectTypeContact($facture, $conf->global->AGENT_CONTACT_TYPE, "AGENT_CONTACT_TYPE","internal","code",1);
print '</td>';
print '<td>'.$langs->trans('AgentContactTypeDetails').'</td>';
print '</tr>';

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td align="center" colspan="3">';
print '<input type="submit" class="button" />';
print '</td>';
print '</tr>';

print '</table>';

print '</form>';

dol_fiche_end();

print '<br>';

llxFooter();
$db->close();
?>
