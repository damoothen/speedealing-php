<?php
/* Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2007-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2012      Juanjo Menent        <jmenent@2byte.es>
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
 *       \file       htdocs/fichinter/contact.php
 *       \ingroup    fichinter
 *       \brief      Onglet de gestion des contacts de fiche d'intervention
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/fichinter/class/fichinter.class.php';
require_once DOL_DOCUMENT_ROOT.'/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fichinter.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';

$langs->load("interventions");
$langs->load("sendings");
$langs->load("companies");

$id = GETPOST('id','int');
$ref = GETPOST('ref', 'alpha');
$action = GETPOST('action','alpha');

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'ficheinter', $id, 'fichinter');

$object = new Fichinter($db);
$result = $object->fetch($id,$ref);


/*
 * Ajout d'un nouveau contact
 */

if ($action == 'addcontact' && $user->rights->ficheinter->creer)
{
    if ($result > 0 && $id > 0)
    {
    	$contactid = (GETPOST('userid','int') ? GETPOST('userid','int') : GETPOST('contactid','int'));
  		$result = $object->add_contact($contactid, GETPOST('type','int'), GETPOST('source','alpha'));
    }

	if ($result >= 0)
	{
		header("Location: ".$_SERVER['PHP_SELF']."?id=".$object->id);
		exit;
	}
	else
	{
		if ($object->error == 'DB_ERROR_RECORD_ALREADY_EXISTS')
		{
			$langs->load("errors");
			$mesg = '<div class="error">'.$langs->trans("ErrorThisContactIsAlreadyDefinedAsThisType").'</div>';
		}
		else
		{
			$mesg = '<div class="error">'.$object->error.'</div>';
		}
	}
}

// bascule du statut d'un contact
else if ($action == 'swapstatut' && $user->rights->ficheinter->creer)
{
    $result=$object->swapContactStatus(GETPOST('ligne','int'));
}

// Efface un contact
else if ($action == 'deletecontact' && $user->rights->ficheinter->creer)
{
	$result = $object->delete_contact(GETPOST('lineid','int'));

	if ($result >= 0)
	{
		header("Location: ".$_SERVER['PHP_SELF']."?id=".$object->id);
		exit;
	}
	else {
		dol_print_error($db);
	}
}


/*
 * View
 */

$form = new Form($db);
$formcompany = new FormCompany($db);
$contactstatic=new Contact($db);
$userstatic=new User($db);

llxHeader();


// Mode vue et edition
dol_htmloutput_mesg($mesg);

if ($id > 0 || ! empty($ref))
{
	$soc = new Societe($db);
	$soc->fetch($object->socid);


	$head = fichinter_prepare_head($object);
	dol_fiche_head($head, 'contact', $langs->trans("InterventionCard"), 0, 'intervention');


	/*
	*   Fiche intervention synthese pour rappel
	*/
	print '<table class="border" width="100%">';

	$linkback = '<a href="'.DOL_URL_ROOT.'/fichinter/list.php'.(! empty($socid)?'?socid='.$socid:'').'">'.$langs->trans("BackToList").'</a>';

	// Ref
	print '<tr><td width="25%">'.$langs->trans("Ref").'</td><td colspan="3">';
    print $form->showrefnav($object, 'ref', $linkback, 1, 'ref', 'ref');
	print "</td></tr>";

	// Customer
	if ( is_null($object->client) )
		$object->fetch_thirdparty();

	print "<tr><td>".$langs->trans("Company")."</td>";
	print '<td colspan="3">'.$object->client->getNomUrl(1).'</td></tr>';
	print "</table>";

	print '</div>';

	print '<br>';

	// Contacts lines
	include DOL_DOCUMENT_ROOT.'/core/tpl/contacts.tpl.php';
}


llxFooter();
$db->close();
?>