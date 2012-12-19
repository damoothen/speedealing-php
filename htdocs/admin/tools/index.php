<?php
/* Copyright (C) 2001-2004	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2006	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
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
 *    	\file       htdocs/admin/tools/index.php
 * 		\brief      Page d'accueil de l'espace outils admin
 */

require '../../main.inc.php';

$langs->load("admin");
$langs->load("companies");

if (! $user->admin)
	accessforbidden();


/*
 * View
 */

$title=$langs->trans("SystemToolsArea");
if (GETPOST('leftmenu') == 'modulesadmintools') $title=$langs->trans("ModulesSystemTools");

llxHeader(array(),$title);

$form = new Form($db);

print_fiche_titre($title,'','setup');

print $langs->trans("SystemToolsAreaDesc").'<br>';
print "<br>";

print info_admin($langs->trans("SystemAreaForAdminOnly")).'<br>';


llxFooter();
$db->close();
?>