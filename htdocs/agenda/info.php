<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *      \file       htdocs/comm/action/info.php
 *      \ingroup    agenda
 *		\brief      Page des informations d'une action
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/agenda.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/comm/action/class/cactioncomm.class.php';
require_once DOL_DOCUMENT_ROOT.'/comm/action/class/actioncomm.class.php';

$langs->load("commercial");

// Security check
if ($user->societe_id > 0)
{
  $action = '';
  $socid = $user->societe_id;
}



/*
 * View
 */

$help_url='EN:Module_Agenda_En|FR:Module_Agenda|ES:M&omodulodulo_Agenda';
llxHeader('',$langs->trans("Agenda"),$help_url);

$act = new ActionComm($db);
$act->fetch($_GET["id"]);
$act->info($_GET["id"]);

$head=actions_prepare_head($act);
dol_fiche_head($head, 'info', $langs->trans("Action"),0,'action');


print '<table width="100%"><tr><td>';
dol_print_object_info($act);
print '</td></tr></table>';

print '</div>';

$db->close();

llxFooter();
?>
