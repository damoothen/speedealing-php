<?php
/* Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2011 	   Juanjo Menent		<jmenent@2byte.es>
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
 *	    \file       htdocs/admin/confexped.php
 *		\ingroup    produit
 *		\brief      Page to setup sending module
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");
$langs->load("sendings");
$langs->load("deliveries");

if (!$user->admin)
  accessforbidden();

$action=GETPOST('action','alpha');

// Shipment note
if ($action == 'activate_sending')
{
    dolibarr_set_const($db, "MAIN_SUBMODULE_EXPEDITION", "1",'chaine',0,'',$conf->entity);
    header("Location: confexped.php");
    exit;
}
else if ($action == 'disable_sending')
{
	dolibarr_del_const($db, "MAIN_SUBMODULE_EXPEDITION",$conf->entity);
    header("Location: confexped.php");
    exit;
}
// Delivery note
else if ($action == 'activate_delivery')
{
    dolibarr_set_const($db, "MAIN_SUBMODULE_EXPEDITION", "1",'chaine',0,'',$conf->entity);    // We must also enable this
    dolibarr_set_const($db, "MAIN_SUBMODULE_LIVRAISON", "1",'chaine',0,'',$conf->entity);
	header("Location: confexped.php");
	exit;
}
else if ($action == 'disable_delivery')
{
	dolibarr_del_const($db, "MAIN_SUBMODULE_LIVRAISON",$conf->entity);
    header("Location: confexped.php");
    exit;
}


/*
 * Affiche page
 */
$dir = DOL_DOCUMENT_ROOT."/core/modules/expedition/";
$form=new Form($db);

llxHeader("","");

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("SendingsSetup"),$linkback,'setup');
print '<br>';

$h = 0;

$head[$h][0] = DOL_URL_ROOT."/admin/confexped.php";
$head[$h][1] = $langs->trans("Setup");
$hselected=$h;
$h++;

if (! empty($conf->global->MAIN_SUBMODULE_EXPEDITION))
{
	$head[$h][0] = DOL_URL_ROOT."/admin/expedition.php";
	$head[$h][1] = $langs->trans("Sending");
	$h++;
}

if (! empty($conf->global->MAIN_SUBMODULE_LIVRAISON))
{
	$head[$h][0] = DOL_URL_ROOT."/admin/livraison.php";
	$head[$h][1] = $langs->trans("Receivings");
	$h++;
}

dol_fiche_head($head, $hselected, $langs->trans("ModuleSetup"));

/*
 * Formulaire parametres divers
 */

$var=true;

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Feature").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="center" width="100">'.$langs->trans("Status").'</td>';
print '</tr>'."\n";

// expedition activation/desactivation
$var=!$var;
print "<tr ".$bc[$var].">";
print '<td>'.$langs->trans("SendingsAbility").'</td>';
print '<td align="center" width="20">';
print '</td>';
print '<td align="center" width="100">';

if($conf->global->MAIN_SUBMODULE_EXPEDITION == 0)
{
	print '<a href="confexped.php?action=activate_sending">'.img_picto($langs->trans("Disabled"),'switch_off').'</a>';
}
else if($conf->global->MAIN_SUBMODULE_EXPEDITION == 1)
{
	print '<a href="confexped.php?action=disable_sending">'.img_picto($langs->trans("Enabled"),'switch_on').'</a>';
}

print "</td>";
print '</tr>';

// Bon de livraison activation/desactivation
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("DeliveriesOrderAbility").'</td>';
print '<td align="center" width="20">';
print '</td>';
print '<td align="center" width="100">';

if($conf->global->MAIN_SUBMODULE_LIVRAISON == 0)
{
	print '<a href="confexped.php?action=activate_delivery">'.img_picto($langs->trans("Disabled"),'switch_off').'</a>';
}
else if($conf->global->MAIN_SUBMODULE_LIVRAISON == 1)
{
	print '<a href="confexped.php?action=disable_delivery">'.img_picto($langs->trans("Enabled"),'switch_on').'</a>';
}

print "</td>";
print '</tr>';
print '</table>';

print '</div>';

print info_admin($langs->trans("NoNeedForDeliveryReceipts"));

$db->close();

llxFooter();
?>
