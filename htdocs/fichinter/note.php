<?php
/* Copyright (C) 2005-2012	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2012	Juanjo Menent	<jmenent@2byte.es>
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
 *	\file       htdocs/fichinter/note.php
 *	\ingroup    fichinter
 *	\brief      Fiche d'information sur une fiche d'intervention
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/fichinter/class/fichinter.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fichinter.lib.php';

$langs->load('companies');
$langs->load("interventions");

$id = GETPOST('id','int');
$ref = GETPOST('ref', 'alpha');
$action=GETPOST('action','alpha');

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'ficheinter', $id, 'fichinter');

$object = new Fichinter($db);
$object->fetch($id,$ref);


/*
 * Actions
 */

if ($action == 'setnote_public' && $user->rights->ficheinter->creer)
{
	$result=$object->update_note_public(dol_html_entity_decode(GETPOST('note_public'), ENT_QUOTES));
	if ($result < 0) dol_print_error($db,$object->error);
}

else if ($action == 'setnote_private' && $user->rights->ficheinter->creer)
{
	$result=$object->update_note(dol_html_entity_decode(GETPOST('note_private'), ENT_QUOTES));
	if ($result < 0) dol_print_error($db,$object->error);
}


/*
 * View
 */

llxHeader();

$form = new Form($db);

if ($id > 0 || ! empty($ref))
{
	dol_htmloutput_mesg($mesg);

	$societe = new Societe($db);
	if ($societe->fetch($object->socid))
	{
		$head = fichinter_prepare_head($object);
		dol_fiche_head($head, 'note', $langs->trans('InterventionCard'), 0, 'intervention');

		print '<table class="border" width="100%">';

		$linkback = '<a href="'.DOL_URL_ROOT.'/fichinter/list.php'.(! empty($socid)?'?socid='.$socid:'').'">'.$langs->trans("BackToList").'</a>';

		print '<tr><td width="25%">'.$langs->trans('Ref').'</td><td colspan="3">';
		print $form->showrefnav($object, 'ref', $linkback, 1, 'ref', 'ref');
		print '</td></tr>';

		// Company
		print '<tr><td>'.$langs->trans('Company').'</td><td colspan="3">'.$societe->getNomUrl(1).'</td></tr>';

		print "</table>";

		print '<br>';

		include DOL_DOCUMENT_ROOT.'/core/tpl/notes.tpl.php';

		dol_fiche_end();
	}
}

llxFooter();
$db->close();
?>
