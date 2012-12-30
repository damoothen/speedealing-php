<?php
/* Copyright (C) 2010-2012 Regis Houssin  <regis.houssin@capnetworks.com>
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
 *       \file       htdocs/core/ajax/row.php
 *       \brief      File to return Ajax response on Row move
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1'); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/genericobject.class.php';


/*
 * View
 */

top_httphead();

print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

// Registering the location of boxes
if ((isset($_POST['roworder']) && ! empty($_POST['roworder'])) && (isset($_POST['table_element_line']) && ! empty($_POST['table_element_line']))
	&& (isset($_POST['fk_element']) && ! empty($_POST['fk_element'])) && (isset($_POST['element_id']) && ! empty($_POST['element_id'])) )
{
	$roworder=GETPOST('roworder','alpha',2);
	$table_element_line=GETPOST('table_element_line','alpha',2);
	$fk_element=GETPOST('fk_element','alpha',2);
	$element_id=GETPOST('element_id','int',2);

	dol_syslog("AjaxRow roworder=".$roworder." table_element_line=".$table_element_line." fk_element=".$fk_element." element_id=".$element_id, LOG_DEBUG);

	$rowordertab = explode(',',$roworder);
	foreach($rowordertab as $value)
	{
		if (! empty($value)) $newrowordertab[] = $value;
	}

	$row=new GenericObject($db);
	$row->table_element_line = $table_element_line;
	$row->fk_element = $fk_element;
	$row->id = $element_id;
	$result=$row->line_ajaxorder($newrowordertab);

	// Reorder line to have position of chilren lines sharing same counter than parent lines
	// This should be useless because there is no need to have children sharing same counter that parent.
	if (in_array($fk_element,array('fk_facture','fk_propal','fk_commande')))
	{
		$result=$row->line_order(true);
	}
}

?>
