<?php
/* Copyright (C) 2005-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	\file       htdocs/compta/sociales/info.php
 *	\ingroup    tax
 *	\brief      Page with info about social contribution
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/sociales/class/chargesociales.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/tax.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

$langs->load("compta");
$langs->load("bills");

$id=GETPOST('id','int');
$action=GETPOST("action");

// Security check
$socid = GETPOST('socid','int');
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'tax', $langs->trans("SocialContribution"), '', 'charges');


/*
 * View
 */

$help_url='EN:Module_Taxes_and_social_contributions|FR:Module Taxes et dividendes|ES:M&oacute;dulo Impuestos y cargas sociales (IVA, impuestos)';
llxHeader("",$langs->trans("SocialContribution"),$help_url);

$chargesociales = new ChargeSociales($db);
$chargesociales->fetch($id);
$chargesociales->info($id);

$head = tax_prepare_head($chargesociales);

dol_fiche_head($head, 'info', $langs->trans("SocialContribution"), 0, 'bill');


print '<table width="100%"><tr><td>';
dol_print_object_info($chargesociales);
print '</td></tr></table>';

print '</div>';

llxFooter();

$db->close();
?>
