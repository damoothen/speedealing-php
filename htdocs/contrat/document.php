<?php
/* Copyright (C) 2003-2007	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2009	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005		Marc Barilley / Ocebo	<marc@ocebo.com>
 * Copyright (C) 2005-2012	Regis Houssin			<regis.houssin@capnetworks.com>
 * Copyright (C) 2005		Simon TOSSER			<simon@kornog-computing.com>
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
 *       \file       htdocs/contrat/document.php
 *       \ingroup    contrat
 *       \brief      Page des documents joints sur les contrats
 */

require ("../main.inc.php");
require_once DOL_DOCUMENT_ROOT.'/core/lib/contract.lib.php';
require_once DOL_DOCUMENT_ROOT.'/contrat/class/contrat.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

$langs->load("other");
$langs->load("products");
$langs->load("contracts");

$action		= GETPOST('action','alpha');
$confirm	= GETPOST('confirm','alpha');
$id			= GETPOST('id','int');
$ref		= GETPOST('ref','alpha');

// Security check
if ($user->societe_id > 0)
{
	unset($_GET["action"]);
	$action='';
	$socid = $user->societe_id;
}
$result = restrictedArea($user, 'contrat', $id);

// Get parameters
$sortfield = GETPOST("sortfield",'alpha');
$sortorder = GETPOST("sortorder",'alpha');
$page = GETPOST("page",'int');
if ($page == -1) { $page = 0; }
$offset = $conf->liste_limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (! $sortorder) $sortorder="ASC";
if (! $sortfield) $sortfield="name";


$object = new Contrat($db);
$object->fetch($id, $ref);
if ($object->id > 0)
{
	$object->fetch_thirdparty();
}

$upload_dir = $conf->contrat->dir_output.'/'.dol_sanitizeFileName($object->ref);
$modulepart='contract';


/*
 * Action envoie fichier
 */
if (GETPOST('sendit') && ! empty($conf->global->MAIN_UPLOAD_DOC))
{
	dol_add_file_process($upload_dir,0,1,'userfile');
}

// Delete
if ($action == 'confirm_deletefile' && $confirm == 'yes')
{
	if ($object->id)
	{
		$langs->load("other");

		$file = $upload_dir . '/' . GETPOST('urlfile');	// Do not use urldecode here ($_GET and $_REQUEST are already decoded by PHP).
		$ret=dol_delete_file($file,0,0,0,$object);
		if ($ret) setEventMessage($langs->trans("FileWasRemoved", GETPOST('urlfile')));
		else setEventMessage($langs->trans("ErrorFailToDeleteFile", GETPOST('urlfile')), 'errors');
		header('Location: '.$_SERVER["PHP_SELF"].'?id='.$object->id);
		exit;
	}
}


/*
 *
 */

$form = new Form($db);

llxHeader();


if ($object->id)
{
	$head=contract_prepare_head($object, $user);

	dol_fiche_head($head, 'documents', $langs->trans("Contract"), 0, 'contract');


	// Construit liste des fichiers
	$filearray=dol_dir_list($upload_dir,"files",0,'','\.meta$',$sortfield,(strtolower($sortorder)=='desc'?SORT_DESC:SORT_ASC),1);
	$totalsize=0;
	foreach($filearray as $key => $file)
	{
		$totalsize+=$file['size'];
	}


    print '<table class="border" width="100%">';

    $linkback = '<a href="'.DOL_URL_ROOT.'/contrat/liste.php'.(! empty($socid)?'?socid='.$socid:'').'">'.$langs->trans("BackToList").'</a>';

    // Reference
	print '<tr><td width="30%">'.$langs->trans('Ref').'</td><td colspan="3">'.$form->showrefnav($object, 'ref', $linkback, 1, 'ref', 'ref', '').'</td></tr>';

    // Societe
    print '<tr><td>'.$langs->trans("Customer").'</td>';
    print '<td colspan="3">'.$object->thirdparty->getNomUrl(1).'</td></tr>';

    print '<tr><td>'.$langs->trans("NbOfAttachedFiles").'</td><td colspan="3">'.count($filearray).'</td></tr>';
    print '<tr><td>'.$langs->trans("TotalSizeOfAttachedFiles").'</td><td colspan="3">'.$totalsize.' '.$langs->trans("bytes").'</td></tr>';
    print '</table>';

    print '</div>';

    /*
     * Confirmation suppression fichier
     */
    if ($action == 'delete')
    {
    	$ret=$form->form_confirm($_SERVER["PHP_SELF"].'?id='.$id.'&urlfile='.urlencode(GETPOST("urlfile")), $langs->trans('DeleteFile'), $langs->trans('ConfirmDeleteFile'), 'confirm_deletefile', '', 0, 1);
    	if ($ret == 'html') print '<br>';
    }


    // Affiche formulaire upload
   	$formfile=new FormFile($db);
	$formfile->form_attach_new_file($_SERVER['PHP_SELF'].'?id='.$object->id,'',0,0,$user->rights->contrat->creer,50,$object);


	// List of document
	$param='&id='.$object->id;
	$formfile->list_of_documents($filearray,$object,'contract',$param);

}
else
{
	print $langs->trans("UnkownError");
}


llxFooter();
$db->close();
?>
