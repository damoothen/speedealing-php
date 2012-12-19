<?php
/* Copyright (C) 2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * or see http://www.gnu.org/
 */

/**
 *	    \file       htdocs/core/lib/emailing.lib.php
 *		\brief      Library file with function for emailing module
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function emailing_prepare_head($object)
{
	global $user, $langs, $conf;

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT."/comm/mailing/fiche.php?id=".$object->id;
	$head[$h][1] = $langs->trans("MailCard");
	$head[$h][2] = 'card';
	$h++;
	
	if (! empty($conf->global->MAIN_USE_ADVANCED_PERMS) && ! $user->rights->mailing->mailing_advance->recipient) {
		return $head;
	}

	$head[$h][0] = DOL_URL_ROOT."/comm/mailing/cibles.php?id=".$object->id;
	$head[$h][1] = $langs->trans("MailRecipients");
	$head[$h][2] = 'targets';
	$h++;

	$head[$h][0] = DOL_URL_ROOT."/comm/mailing/info.php?id=".$object->id;
	$head[$h][1] = $langs->trans("Info");
	$head[$h][2] = 'info';
	$h++;

	return $head;
}

?>