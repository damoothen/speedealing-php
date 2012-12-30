<?php
/* Copyright (C) 2003-2007 Rodolphe Quiedeville  <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur   <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Marc Barilley / Ocebo <marc@ocebo.com>
 * Copyright (C) 2005-2012 Regis Houssin         <regis.houssin@capnetworks.com>
 * Copyright (C) 2005      Simon TOSSER          <simon@kornog-computing.com>
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
 *       \file       htdocs/product/document.php
 *       \ingroup    product
 *       \brief      Page des documents joints sur les produits
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/product.lib.php';
require_once DOL_DOCUMENT_ROOT.'/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

$langs->load("other");
$langs->load("products");

$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');
$action=GETPOST('action','alpha');
$confirm=GETPOST('confirm','alpha');

// Security check
$fieldvalue = (! empty($id) ? $id : (! empty($ref) ? $ref : ''));
$fieldtype = (! empty($ref) ? 'ref' : 'rowid');
if ($user->societe_id) $socid=$user->societe_id;
$result=restrictedArea($user,'produit|service',$fieldvalue,'product&product','','',$fieldtype);

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


$object = new Product($db);
if ($id > 0 || ! empty($ref))
{
    $result = $object->fetch($id, $ref);

    if (! empty($conf->product->enabled)) $upload_dir = $conf->product->multidir_output[$object->entity].'/'.dol_sanitizeFileName($object->ref);
    elseif (! empty($conf->service->enabled)) $upload_dir = $conf->service->multidir_output[$object->entity].'/'.dol_sanitizeFileName($object->ref);
}
$modulepart='produit';

/*
 * Action envoie fichier
 */

if (GETPOST('sendit') && ! empty($conf->global->MAIN_UPLOAD_DOC))
{
	dol_add_file_process($upload_dir,0,1,'userfile');
}

// Delete
if ($action=='delete')
{
	$langs->load("other");
	$file = $upload_dir . '/' . GETPOST('urlfile');	// Do not use urldecode here ($_GET and $_REQUEST are already decoded by PHP).
	$ret=dol_delete_file($file,0,0,0,$object);
	if ($ret) setEventMessage($langs->trans("FileWasRemoved", GETPOST('urlfile')));
	else setEventMessage($langs->trans("ErrorFailToDeleteFile", GETPOST('urlfile')), 'errors');
	header('Location: '.$_SERVER["PHP_SELF"].'?id='.$id);
	exit;
}


/*
 *	View
 */

$form = new Form($db);

llxHeader("","",$langs->trans("CardProduct".$object->type));


if ($object->id)
{
	$head=product_prepare_head($object, $user);
	$titre=$langs->trans("CardProduct".$object->type);
	$picto=($object->type==1?'service':'product');
	dol_fiche_head($head, 'documents', $titre, 0, $picto);


	// Construit liste des fichiers
	$filearray=dol_dir_list($upload_dir,"files",0,'','\.meta$',$sortfield,(strtolower($sortorder)=='desc'?SORT_DESC:SORT_ASC),1);
	$totalsize=0;
	foreach($filearray as $key => $file)
	{
		$totalsize+=$file['size'];
	}


    print '<table class="border" width="100%">';

    // Ref
    print '<tr>';
    print '<td width="30%">'.$langs->trans("Ref").'</td><td colspan="3">';
	print $form->showrefnav($object,'ref','',1,'ref');
    print '</td>';
    print '</tr>';

    // Label
    print '<tr><td>'.$langs->trans("Label").'</td><td colspan="3">'.$object->libelle.'</td></tr>';

	// Status (to sell)
	print '<tr><td>'.$langs->trans("Status").' ('.$langs->trans("Sell").')</td><td>';
	print $object->getLibStatut(2,0);
	print '</td></tr>';

	// Status (to buy)
	print '<tr><td>'.$langs->trans("Status").' ('.$langs->trans("Buy").')</td><td>';
	print $object->getLibStatut(2,1);
	print '</td></tr>';

    print '<tr><td>'.$langs->trans("NbOfAttachedFiles").'</td><td colspan="3">'.count($filearray).'</td></tr>';
    print '<tr><td>'.$langs->trans("TotalSizeOfAttachedFiles").'</td><td colspan="3">'.$totalsize.' '.$langs->trans("bytes").'</td></tr>';
    print '</table>';

    print '</div>';


    // Affiche formulaire upload
   	$formfile=new FormFile($db);
	$formfile->form_attach_new_file(DOL_URL_ROOT.'/product/document.php?id='.$object->id,'',0,0,($user->rights->produit->creer||$user->rights->service->creer),50,$object);


	// List of document
	$formfile->list_of_documents($filearray,$object,'produit');

}
else
{
	print $langs->trans("UnkownError");
}


llxFooter();
$db->close();
?>
