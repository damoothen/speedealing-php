<?php
/* Copyright (C) 2004-2007 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *      \file       htdocs/user/info.php
 *      \ingroup    core
 *		\brief      Page des informations d'un utilisateur
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/usergroups.lib.php';
require_once DOL_DOCUMENT_ROOT.'/user/class/user.class.php';

$langs->load("users");

// Security check
$id = GETPOST('id','int');
$fuser = new User($db);
$fuser->fetch($id);

// Security check
$socid=0;
if ($user->societe_id > 0) $socid = $user->societe_id;
$feature2 = (($socid && $user->rights->user->self->creer)?'':'user');
if ($user->id == $id)	// A user can always read its own card
{
	$feature2='';
}
$result = restrictedArea($user, 'user', $id, '&user', $feature2);

// If user is not user read and no permission to read other users, we stop
if (($fuser->id != $user->id) && (! $user->rights->user->user->lire))
  accessforbidden();



/*
 * View
 */

llxHeader();

$fuser->info($id);

$head = user_prepare_head($fuser);

$title = $langs->trans("User");
dol_fiche_head($head, 'info', $title, 0, 'user');


print '<table width="100%"><tr><td>';
dol_print_object_info($fuser);
print '</td></tr></table>';

print '</div>';

$db->close();

llxFooter();
?>
