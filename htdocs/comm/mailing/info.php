<?php
/* Copyright (C) 2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2010 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *      \file       htdocs/comm/mailing/info.php
 *      \ingroup    mailing
 *		\brief      Page with log information for emailing
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT .'/comm/mailing/class/mailing.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/emailing.lib.php';

$langs->load("mails");

// Security check
if (! $user->rights->mailing->lire || $user->societe_id > 0)
accessforbidden();



/*
 * View
 */

llxHeader('',$langs->trans("Mailing"),'EN:Module_EMailing|FR:Module_Mailing|ES:M&oacute;dulo_Mailing');

$form = new Form($db);

$mil = new Mailing($db);

if ($mil->fetch($_REQUEST["id"]) >= 0)
{
	$head = emailing_prepare_head($mil);

	dol_fiche_head($head, 'info', $langs->trans("Mailing"), 0, 'email');


	print '<table width="100%"><tr><td>';
	$mil->user_creation=$mil->user_creat;
	$mil->date_creation=$mil->date_creat;
	$mil->user_validation=$mil->user_valid;
	$mil->date_validation=$mil->date_valid;
	dol_print_object_info($mil);
	print '</td></tr></table>';

	print '</div>';
}

$db->close();

llxFooter();
?>
