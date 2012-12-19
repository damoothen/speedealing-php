<?php
/* Copyright (C) 2009-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
 *      \file       htdocs/imports/emptyexample.php
 *      \ingroup    import
 *      \brief      Show example of import file
 */

// This file is a wrapper, so empty header
function llxHeader() { print '<html><title>Build an import example file</title><body>'; }
// This file is a wrapper, so empty footer
function llxFooter() { print '</body></html>'; }

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/import/class/import.class.php';
require_once DOL_DOCUMENT_ROOT.'/import/core/modules/import/modules_import.php';

$datatoimport=GETPOST('datatoimport');
$format=GETPOST('format');

$langs->load("exports");

// Check exportkey
if (empty($datatoimport))
{
	$user->getrights();

	llxHeader();
	print '<div class="error">Bad value for datatoimport.</div>';
	llxFooter();
	exit;
}


$filename=$langs->trans("ExampleOfImportFile").'_'.$datatoimport.'.'.$format;

$objimport=new Import($db);
$objimport->load_arrays($user,$datatoimport);
// Load arrays from descriptor module
$entity=$objimport->array_import_entities[0][$code];
$entityicon=$entitytoicon[$entity]?$entitytoicon[$entity]:$entity;
$entitylang=$entitytolang[$entity]?$entitytolang[$entity]:$entity;
$fieldstarget=$objimport->array_import_fields[0];
$valuestarget=$objimport->array_import_examplevalues[0];

$attachment = true;
if (isset($_GET["attachment"])) $attachment=$_GET["attachment"];
//$attachment = false;
$contenttype=dol_mimetype($format);
if (isset($_GET["contenttype"])) $contenttype=$_GET["contenttype"];
//$contenttype='text/plain';
$outputencoding='UTF-8';

if ($contenttype)       header('Content-Type: '.$contenttype.($outputencoding?'; charset='.$outputencoding:''));
if ($attachment) 		header('Content-Disposition: attachment; filename="'.$filename.'"');


// List of targets fields
$headerlinefields=array();
$contentlinevalues=array();
$i = 0;
foreach($fieldstarget as $code=>$label)
{
	$withoutstar=preg_replace('/\*/','',$fieldstarget[$code]);
	$headerlinefields[]=$langs->transnoentities($withoutstar).($withoutstar != $fieldstarget[$code]?'*':'').' ('.$code.')';
	$contentlinevalues[]=$valuestarget[$code];
}
//var_dump($headerlinefields);
//var_dump($contentlinevalues);

print $objimport->build_example_file($format,$headerlinefields,$contentlinevalues,$datatoimport);

?>
