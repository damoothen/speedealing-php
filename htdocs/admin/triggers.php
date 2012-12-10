<?php
/* Copyright (C) 2005-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *       \file       htdocs/admin/triggers.php
 *       \brief      Page to view triggers
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/interfaces.class.php';

$langs->load("admin");

if (!$user->admin) accessforbidden();

/*
 * Action
 */

// None


/*
 * View
 */

llxHeader("","");

$form = new Form($db);

print_fiche_titre($langs->trans("TriggersAvailable"),'','setup');

print $langs->trans("TriggersDesc")."<br>";
print "<br>\n";

$template_dir = DOL_DOCUMENT_ROOT.'/core/tpl/';

$interfaces = new Interfaces($db);
$triggers = $interfaces->getTriggersList(0,'priority');

include $template_dir.'triggers.tpl.php';

print dol_fiche_end();

llxFooter();

$db->close();
?>
