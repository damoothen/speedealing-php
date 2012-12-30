<?php
/* Copyright (C) 2010 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	\file       htdocs/projet/note.php
 *	\ingroup    project
 *	\brief      Fiche d'information sur un projet
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/project.lib.php';

$langs->load('projects');

$action=GETPOST('action');
$id = GETPOST('id','int');
$ref= GETPOST('ref');

$mine = $_REQUEST['mode']=='mine' ? 1 : 0;
//if (! $user->rights->projet->all->lire) $mine=1;	// Special for projects

$object = new Project($db);
if ($ref)
{
    $object->fetch(0,$ref);
    $id=$object->id;
}

// Security check
$socid=0;
if ($user->societe_id > 0) $socid=$user->societe_id;
$result = restrictedArea($user, 'projet', $id);



/******************************************************************************/
/*                     Actions                                                */
/******************************************************************************/

if ($action == 'setnote_public' && $user->rights->projet->creer)
{
	$object->fetch($id);
	$result=$object->update_note_public(dol_html_entity_decode(GETPOST('note_public'), ENT_QUOTES));
	if ($result < 0) dol_print_error($db,$object->error);
}

if ($action == 'setnote_private' && $user->rights->projet->creer)
{
	$object->fetch($id);
	$result=$object->update_note(dol_html_entity_decode(GETPOST('note_private'), ENT_QUOTES));
	if ($result < 0) dol_print_error($db,$object->error);
}


/*
 * View
 */

$help_url="EN:Module_Projects|FR:Module_Projets|ES:M&oacute;dulo_Proyectos";
llxHeader("",$langs->trans("Project"),$help_url);

$form = new Form($db);
$userstatic=new User($db);
$object = new Project($db);

$now=dol_now();

if ($id > 0 || ! empty($ref))
{
	if ($object->fetch($id, $ref))
	{
		if ($object->societe->id > 0)  $result=$object->societe->fetch($object->societe->id);

        // To verify role of users
        //$userAccess = $object->restrictedProjectArea($user,'read');
        $userWrite  = $object->restrictedProjectArea($user,'write');
        //$userDelete = $object->restrictedProjectArea($user,'delete');
        //print "userAccess=".$userAccess." userWrite=".$userWrite." userDelete=".$userDelete;

		$head = project_prepare_head($object);
		dol_fiche_head($head, 'notes', $langs->trans('Project'), 0, ($object->public?'projectpub':'project'));

		print '<table class="border" width="100%">';

		$linkback = '<a href="'.DOL_URL_ROOT.'/projet/liste.php">'.$langs->trans("BackToList").'</a>';

		// Ref
		print '<tr><td width="30%">'.$langs->trans("Ref").'</td><td>';
		// Define a complementary filter for search of next/prev ref.
	    if (! $user->rights->projet->all->lire)
        {
            $projectsListId = $object->getProjectsAuthorizedForUser($user,$mine,0);
            $object->next_prev_filter=" rowid in (".(count($projectsListId)?join(',',array_keys($projectsListId)):'0').")";
        }
		print $form->showrefnav($object, 'ref', $linkback, 1, 'ref', 'ref');
		print '</td></tr>';

		// Label
		print '<tr><td>'.$langs->trans("Label").'</td><td>'.$object->title.'</td></tr>';

		// Third party
		print '<tr><td>'.$langs->trans("Company").'</td><td>';
		if ($object->societe->id > 0) print $object->societe->getNomUrl(1);
		else print'&nbsp;';
		print '</td></tr>';

		// Visibility
		print '<tr><td>'.$langs->trans("Visibility").'</td><td>';
		if ($object->public) print $langs->trans('SharedProject');
		else print $langs->trans('PrivateProject');
		print '</td></tr>';

		// Statut
		print '<tr><td>'.$langs->trans("Status").'</td><td>'.$object->getLibStatut(4).'</td></tr>';

		print "</table>";

		print '<br>';

		$colwidth=30;
		include DOL_DOCUMENT_ROOT.'/core/tpl/notes.tpl.php';

		dol_fiche_end();;
	}
}

llxFooter();

$db->close();
?>
