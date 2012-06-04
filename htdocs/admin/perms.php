<?php

/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

$langs->load("admin");
$langs->load("users");
$langs->load("other");

$action = GETPOST('action');

if (!$user->admin)
	accessforbidden();

$object = new DolibarrModules($db);

/*
 * Actions
 */

if ($action == 'add') {

	try {
		$object->load($_GET['id']);
		$object->values->rights[$_GET['pid']]->default = true;
		$object->record();
	} catch (Exception $e) {
		dol_print_error('', $e->getMessage());
	}
}

if ($action == 'remove') {
	try {
		$object->load($_GET['id']);
		$object->values->rights[$_GET['pid']]->default = false;
		$object->record();
	} catch (Exception $e) {
		dol_print_error('', $e->getMessage());
	}
}

$langs->load("admin");

/*
 * View
 */

llxHeader('', $langs->trans("DefaultRights"));

print '<div class="row">';
print start_box($langs->trans("SecuritySetup"), 'twelve', '16-Cog-4.png', false);

print $langs->trans("DefaultRightsDesc");
print " " . $langs->trans("OnlyActiveElementsAreShown") . "<br>\n";
print "<br>\n";

$head = security_prepare_head();

dol_fiche_head($head, 'default', $langs->trans("Security"));


$i = 0;
$obj = new stdClass();

print '<table class="display dt_act" id="default_right">';

print'<thead>';
print'<tr>';

print'<th>';
print'</th>';
$obj->aoColumns[$i]->mDataProp = "id";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bVisible = false;
$i++;

print'<th class="essential">';
print $langs->trans("Module");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;

print'<th>';
print $langs->trans("Permission");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "desc";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->bVisible = true;
$i++;

print'<th class="essential">';
print $langs->trans("Default");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sDefaultContent = "false";
$obj->aoColumns[$i]->sClass = "center";

print'</tr>';
print'</thead>';
$obj->fnDrawCallback = "function(oSettings){
                if ( oSettings.aiDisplay.length == 0 )
                {
                    return;
                }
                var nTrs = jQuery('#default_right tbody tr');
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = '';
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                    var iDisplayIndex = oSettings._iDisplayStart + i;
                     var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData['name'];
                         if (sGroup!=null && sGroup!='' && sGroup != sLastGroup)
                            {
                                var nGroup = document.createElement('tr');
                                var nCell = document.createElement('td');
                                nCell.colSpan = iColspan;
                                nCell.className = 'group';
                                nCell.innerHTML = sGroup;
                                nGroup.appendChild( nCell );
                                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                                sLastGroup = sGroup;
                            }
                    
                    
                }
	}";

$i = 0;
print'<tfoot>';
print'</tfoot>';
print'<tbody>';

try {
	$result = $object->getView("default_right");
} catch (Exception $exc) {
	print $exc->getMessage();
}

if (count($result->rows)) {

	foreach ($result->rows as $aRow) {
		print'<tr>';

		$object->values->name = $aRow->value->name;
		$object->values->numero = $aRow->value->numero;
		$object->values->rights_class = $aRow->value->rights_class;
		$object->values->id = $aRow->value->id;
		$object->values->perm = $aRow->value->perm;
		$object->values->Status = ($aRow->value->Status == true ? "true" : "false");

		print '<td>' . $aRow->value->id . '</td>';
		print '<td>' . img_object('', $aRow->value->picto) . " " . $object->getName() . '</td>';
		print '<td>' . $object->getPermDesc() . '<a name="' . $aRow->value->id . '">&nbsp;</a></td>';
		print '<td>';
		if ($aRow->value->Status) {
			print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $aRow->value->_id . '&pid=' . $aRow->value->idx . '&amp;action=remove#' . $aRow->value->id . '">' . img_edit_remove() . '</a>';
		} else {
			print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $aRow->value->_id . '&pid=' . $aRow->value->idx . '&amp;action=add#' . $aRow->value->id . '">' . img_edit_add() . '</a>';
		}
		print '</td>';

		print'</tr>';
	}
}
print'</tbody>';
print'</table>';

$obj->aaSorting = array(array(1, 'asc'));
$obj->sDom = 'l<fr>t<\"clear\"rtip>';

print $object->datatablesCreate($obj, "default_right");



/*
  // Break found, it's a new module to catch
  if ($oldmod <> $obj->module) {
  $oldmod = $obj->module;
  $objMod = $modules[$obj->module];
  $picto = ($objMod->picto ? $objMod->picto : 'generic');

  print '<tr class="liste_titre">';
  print '<td>' . $langs->trans("Module") . '</td>';
  print '<td>' . $langs->trans("Permission") . '</td>';
  print '<td align="center">' . $langs->trans("Default") . '</td>';
  print "</tr>\n";
  }

  $var = !$var;
  print '<tr ' . $bc[$var] . '>';

  print '<td>' . img_object('', $picto) . ' ' . $objMod->getName();
  print '<a name="' . $objMod->getName() . '">&nbsp;</a>';

  $perm_libelle = $obj->libelle;
  print '<td>' . $perm_libelle . '</td>';

  print '<td align="center">';
  if ($obj->bydefault == 1) {
  print '<a href="' . $_SERVER['PHP_SELF'] . '?pid=' . $obj->id . '&amp;action=remove#' . $objMod->getName() . '">' . img_edit_remove() . '</a>';
  } else {
  print '<a href="' . $_SERVER['PHP_SELF'] . '?pid=' . $obj->id . '&amp;action=add#' . $objMod->getName() . '">' . img_edit_add() . '</a>';
  }

  print '</td></tr>';
  $i++;
  } */


print '</table>';

print '</div>';

print end_box();
print '</div>';

$db->close();

llxFooter();
?>
