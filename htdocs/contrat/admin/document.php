<?php
/* Copyright (C) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/societe/document.php
 *  \brief      Tab for documents linked to third party
 *  \ingroup    societe
 */

require("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/admin.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/contract.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/files.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/images.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formfile.class.php");

$langs->load("companies");
$langs->load('other');

$mesg='';

$action		= GETPOST('action');
$confirm	= GETPOST('confirm');

// Security check
if (!$user->admin) accessforbidden();

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

$tmpdir=trim($conf->global->CONTRAT_ADDON_PDF_ODT_PATH);
if($conf->multicompany->enabled && $conf->entity > 1)
    $upload_dir=preg_replace('/DOL_DATA_ROOT/',DOL_DATA_ROOT."/".$conf->entity,$tmpdir);
else
    $upload_dir=preg_replace('/DOL_DATA_ROOT/',DOL_DATA_ROOT,$tmpdir);

/*
 * Actions
 */

// Post file
if ( $_POST["sendit"] && ! empty($conf->global->MAIN_UPLOAD_DOC))
{
		if (create_exdir($upload_dir) >= 0)
		{
			$resupload=dol_move_uploaded_file($_FILES['userfile']['tmp_name'], $upload_dir . "/" . $_FILES['userfile']['name'],0,0,$_FILES['userfile']['error']);
			if (is_numeric($resupload) && $resupload > 0)
			{
			    if (image_format_supported($upload_dir . "/" . $_FILES['userfile']['name']) == 1)
			    {
	                // Create small thumbs for company (Ratio is near 16/9)
	                // Used on logon for example
	                $imgThumbSmall = vignette($upload_dir . "/" . $_FILES['userfile']['name'], $maxwidthsmall, $maxheightsmall, '_small', $quality, "thumbs");

	                // Create mini thumbs for company (Ratio is near 16/9)
	                // Used on menu or for setup page for example
	                $imgThumbMini = vignette($upload_dir . "/" . $_FILES['userfile']['name'], $maxwidthmini, $maxheightmini, '_mini', $quality, "thumbs");
			    }
				$mesg = '<div class="ok">'.$langs->trans("FileTransferComplete").'</div>';
			}
			else
			{
				$langs->load("errors");
				if (is_numeric($resupload) && $resupload < 0)	// Unknown error
				{
					$mesg = '<div class="error">'.$langs->trans("ErrorFileNotUploaded").'</div>';
				}
				else if (preg_match('/ErrorFileIsInfectedWithAVirus/',$resupload))	// Files infected by a virus
				{
					$mesg = '<div class="error">'.$langs->trans("ErrorFileIsInfectedWithAVirus").'</div>';
				}
				else	// Known error
				{
					$mesg = '<div class="error">'.$langs->trans($resupload).'</div>';
				}
			}
		}
}

// Delete file
if ($action == 'confirm_deletefile' && $confirm == 'yes')
{
	$file = $upload_dir . "/" . $_GET['urlfile'];	// Do not use urldecode here ($_GET and $_REQUEST are already decoded by PHP).
	dol_delete_file($file,0,0,0,$object);
	$mesg = '<div class="ok">'.$langs->trans("FileWasRemoved").'</div>';
}


/*
 * View
 */

clearstatcache();

$form = new Form($db);

$help_url='EN:Module Third Parties setup|FR:Paramétrage_du_module_Tiers|ES:Configuración_del_módulo_terceros';
llxHeader('',$langs->trans("Files"),$help_url);

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("ContractsSetup"),$linkback,'setup');

$head = contract_admin_prepare_head(null);

dol_fiche_head($head, 'files', $langs->trans("Files"), 0, 'contract');

		// Construit liste des fichiers
		$filearray=dol_dir_list($upload_dir,"files",0,'\.odt','\.meta$',$sortfield,(strtolower($sortorder)=='desc'?SORT_DESC:SORT_ASC),1);
		$totalsize=0;
		foreach($filearray as $key => $file)
		{
			$totalsize+=$file['size'];
		}


		print '<table class="border"width="100%">';

    	// Nbre fichiers
		print '<tr><td>'.$langs->trans("NbOfAttachedFiles").'</td><td colspan="3">'.count($filearray).'</td></tr>';

		//Total taille
		print '<tr><td>'.$langs->trans("TotalSizeOfAttachedFiles").'</td><td colspan="3">'.$totalsize.' '.$langs->trans("bytes").'</td></tr>';

		print '</table>';

		print '</div>';

		dol_htmloutput_mesg($mesg,$mesgs);

		/*
		 * Confirmation suppression fichier
		 */
		if ($action == 'delete')
		{
			$ret=$form->form_confirm($_SERVER["PHP_SELF"].'?socid='.$socid.'&urlfile='.urldecode($_GET["urlfile"]), $langs->trans('DeleteFile'), $langs->trans('ConfirmDeleteFile'), 'confirm_deletefile', '', 0, 1);
			if ($ret == 'html') print '<br>';
		}

		$formfile=new FormFile($db);

        // Show upload form
		$formfile->form_attach_new_file($_SERVER["PHP_SELF"].'?socid='.$socid,'',0,0,$user->rights->societe->creer,50,$object);

		// List of document
		$param='&socid='.$object->id;
		$formfile->list_of_documents($filearray,$object,'modelcontract',$param);

		print "<br><br>";
$db->close();


llxFooter();

?>
