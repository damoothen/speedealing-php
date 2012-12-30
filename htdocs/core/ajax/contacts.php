<?php
/* Copyright (C) 2012 Regis Houssin  <regis.houssin@capnetworks.com>
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
 *       \file       htdocs/core/ajax/contacts.php
 *       \brief      File to load contacts combobox
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require '../../main.inc.php';

$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$htmlname	= GETPOST('htmlname','alpha');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

// Load original field value
if (! empty($id) && ! empty($action) && ! empty($htmlname))
{
	$form = new Form($db);
	
	$return=array();
	
	$return['value']	= $form->selectcontacts($id,'','contactid',0,'','',0,'',true);
	$return['num']		= $form->num;
	$return['error']	= $form->error;
	
	echo json_encode($return);
}

?>
