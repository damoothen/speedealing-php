<?php

/* Copyright (C) 2002-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
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

require("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . '/user/class/usergroup.class.php');
require_once(DOL_DOCUMENT_ROOT . "/core/lib/usergroups.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

$langs->load("users");
$langs->load("admin");

$id = GETPOST('id', 'alpha');
$action = GETPOST("action");
$confirm = GETPOST("confirm");
$module = GETPOST("module");

// Defini si peux lire les permissions
$canreadperms = ($user->admin || $user->rights->user->user->lire);
// Defini si peux modifier les permissions
$caneditperms = ($user->admin || $user->rights->user->user->creer);
// Advanced permissions
$canreadperms = ($user->admin || ($user->rights->user->group_advance->read && $user->rights->user->group_advance->readperms));
$caneditperms = ($user->admin || $user->rights->user->group_advance->write);

if (!$canreadperms)
	accessforbidden();

$fgroup = new Usergroup($db);
$object = new DolibarrModules($db);

/**
 * Actions
 */
if ($action == 'add' && $caneditperms) {
	$editgroup = new Usergroup($db);
	try {
		$editgroup->load($id);

		$editgroup->values->rights[] = (int) $_GET['pid'];
		$editgroup->record();
	} catch (Exception $e) {
		dol_print_error("", $e->getMessage());
	}
}

if ($action == 'remove' && $caneditperms) {
	$editgroup = new Usergroup($db);
	try {
		$editgroup->load($id);
		if (is_array($editgroup->values->rights)) {
			$idx = array_search((int) $_GET['pid'], $editgroup->values->rights, true);
			if (is_numeric($idx)) {
				unset($editgroup->values->rights[$idx]);

				$editgroup->record();
			}
		}
	} catch (Exception $e) {
		dol_print_error("", $e->getMessage());
	}
}


/**
 * View
 */
$form = new Form($db);

llxHeader('', $langs->trans("Permissions"));

if ($id) {

	$fgroup->load($id);

	/*
	 * Affichage onglets
	 */
	$head = group_prepare_head($fgroup);
	$title = $langs->trans("Group");

	print '<div class="row">';
	print start_box($title, "twelve", "16-Users-2.png", false);

	dol_fiche_head($head, 'rights', $title, 0, 'group');

	/*
	 * Ecran ajout/suppression permission
	 */

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
	print $langs->trans("Enabled");
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
			$perm_class = $aRow->value->rights_class;
			$perm = $aRow->value->perm;

			$idx = null;
			if (is_array($fgroup->values->rights))
				$idx = array_search($aRow->value->id, $fgroup->values->rights, true);

			if ($aRow->value->Status)
				print $object->getLibStatus(); // Enable by default
			elseif (is_numeric($idx))
				print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $fgroup->id . '&pid=' . $aRow->value->id . '&amp;action=remove#' . $aRow->value->id . '">' . img_edit_remove() . '</a>';
			else
				print '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $fgroup->id . '&pid=' . $aRow->value->id . '&amp;action=add#' . $aRow->value->id . '">' . img_edit_add() . '</a>';

			print '</td>';

			print'</tr>';
		}
	}
	print'</tbody>';
	print'</table>';

	$obj->aaSorting = array(array(1, 'asc'));
	$obj->sDom = 'l<fr>t<\"clear\"rtip>';

	print $object->datatablesCreate($obj, "default_right");


	/*    print '<table class="border" width="100%">';

	  // Ref
	  print '<tr><td width="25%" valign="top">'.$langs->trans("Ref").'</td>';
	  print '<td colspan="2">';
	  print $form->showrefnav($fgroup,'id','',$user->rights->user->user->lire || $user->admin);
	  print '</td>';
	  print '</tr>';

	  // Nom
	  print '<tr><td width="25%" valign="top">'.$langs->trans("Name").'</td>';
	  print '<td colspan="2">'.$fgroup->nom.'';
	  if (! $fgroup->entity)
	  {
	  print img_picto($langs->trans("GlobalGroup"),'redstar');
	  }
	  print "</td></tr>\n";

	  // Note
	  print '<tr><td width="25%" valign="top">'.$langs->trans("Note").'</td>';
	  print '<td class="valeur">'.dol_htmlentitiesbr($fgroup->note).'</td>';
	  print "</tr>\n";

	  print '</table><br>';

	  if ($user->admin) print info_admin($langs->trans("WarningOnlyPermissionOfActivatedModules"));

	  print '<table width="100%" class="noborder">';
	  print '<tr class="liste_titre">';
	  print '<td>'.$langs->trans("Module").'</td>';
	  if ($caneditperms) print '<td width="24">&nbsp</td>';
	  print '<td align="center" width="24">&nbsp;</td>';
	  print '<td>'.$langs->trans("Permissions").'</td>';
	  print '</tr>';

	  $sql = "SELECT r.id, r.libelle, r.module";
	  $sql.= " FROM ".MAIN_DB_PREFIX."rights_def as r";
	  $sql.= " WHERE r.libelle NOT LIKE 'tou%'";    // On ignore droits "tous"
	  //$sql.= " AND r.entity = ".(empty($conf->multicompany->enabled) ? $conf->entity : $fgroup->entity);
	  if(! empty($conf->multicompany->enabled))
	  {
	  if(empty($conf->multicompany->transverse_mode))
	  {
	  $sql.= " AND r.entity = ".$conf->entity;
	  }
	  else
	  {
	  $sql.= " AND r.entity IN (0,1)";
	  }
	  }
	  else
	  {
	  $sql.= " AND r.entity = ".$conf->entity;
	  }

	  if (empty($conf->global->MAIN_USE_ADVANCED_PERMS)) $sql.= " AND r.perms NOT LIKE '%_advance'";  // Hide advanced perms if option is disable
	  $sql.= " ORDER BY r.module, r.id";

	  //print $sql;exit;

	  $result=$db->query($sql);
	  if ($result)
	  {
	  $i = 0;
	  $var = true;
	  $oldmod = '';

	  $num = $db->num_rows($result);

	  while ($i < $num)
	  {
	  $obj = $db->fetch_object($result);

	  // Si la ligne correspond a un module qui n'existe plus (absent de includes/module), on l'ignore
	  if (! $modules[$obj->module])
	  {
	  $i++;
	  continue;
	  }

	  if ($oldmod <> $obj->module)
	  {
	  $oldmod = $obj->module;
	  $var = !$var;

	  // Rupture detectee, on recupere objMod
	  $objMod = $modules[$obj->module];
	  $picto=($objMod->picto?$objMod->picto:'generic');

	  if ($caneditperms)
	  {
	  print '<tr '. $bc[$var].'>';
	  print '<td nowrap="nowrap">'.img_object('',$picto).' '.$objMod->getName();
	  print '<a name="'.$objMod->getName().'">&nbsp;</a></td>';
	  print '<td align="center" nowrap="nowrap">';
	  print '<a title='.$langs->trans("All").' alt='.$langs->trans("All").' href="perms.php?id='.$fgroup->id.'&amp;action=addrights&amp;module='.$obj->module.'#'.$objMod->getName().'">'.$langs->trans("All")."</a>";
	  print '/';
	  print '<a title='.$langs->trans("None").' alt='.$langs->trans("None").' href="perms.php?id='.$fgroup->id.'&amp;action=delrights&amp;module='.$obj->module.'#'.$objMod->getName().'">'.$langs->trans("None")."</a>";
	  print '</td>';
	  print '<td colspan="2">&nbsp;</td>';
	  print '</tr>';
	  }
	  }

	  print '<tr '. $bc[$var].'>';

	  // Module
	  print '<td nowrap="nowrap">'.img_object('',$picto).' '.$objMod->getName().'</td>';

	  if (in_array($obj->id, $permsgroup))
	  {
	  // Own permission by group
	  if ($caneditperms)
	  {
	  print '<td align="center"><a href="perms.php?id='.$fgroup->id.'&amp;action=delrights&amp;rights='.$obj->id.'#'.$objMod->getName().'">'.img_edit_remove($langs->trans("Remove")).'</a></td>';
	  }
	  print '<td align="center">';
	  print img_picto($langs->trans("Active"),'tick');
	  print '</td>';
	  }
	  else
	  {
	  // Do not own permission
	  if ($caneditperms)
	  {
	  print '<td align="center"><a href="perms.php?id='.$fgroup->id.'&amp;action=addrights&amp;rights='.$obj->id.'#'.$objMod->getName().'">'.img_edit_add($langs->trans("Add")).'</a></td>';
	  }
	  print '<td>&nbsp</td>';
	  }

	  $perm_libelle=($conf->global->MAIN_USE_ADVANCED_PERMS && ($langs->trans("PermissionAdvanced".$obj->id)!=("PermissionAdvanced".$obj->id))?$langs->trans("PermissionAdvanced".$obj->id):(($langs->trans("Permission".$obj->id)!=("Permission".$obj->id))?$langs->trans("Permission".$obj->id):$obj->libelle));
	  print '<td>'.$perm_libelle. '</td>';

	  print '</tr>';

	  $i++;
	  }
	  } */
	print '</table>';
}

print end_box();
print '</div>';

$db->close();

llxFooter();
?>
