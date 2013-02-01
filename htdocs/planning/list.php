<?php

/* Copyright (C) 2001-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Philippe Grand       <philippe.grand@atoo-net.com>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2011      Patrick Mary         <laube@hotmail.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

require '../main.inc.php';
if (! class_exists('Planning'))
	require DOL_DOCUMENT_ROOT . '/planning/class/planning.class.php';

$langs->load("companies");
$langs->load("customers");
$langs->load("commercial");
$langs->load("orders");

// Security check
$result = restrictedArea($user, 'planning', "", '');

$object = new Planning($db);
/*
 * View
 */

llxHeader('', $langs->trans("Planning"), $help_url, '', '', '', '');

$titre = $langs->trans("ProdPlanning");

print_fiche_titre($titre);
/* ?>
  <div class="dashboard">
  <div class="columns">
  <div class="four-columns twelve-columns-mobile graph">
  <?php $object->graphPieStatus(); ?>
  </div>

  <div class="eight-columns twelve-columns-mobile new-row-mobile graph">
  <?php $object->graphBarStatus(); ?>
  </div>
  </div>
  </div>
  <?php */
print '<div class="with-padding">';

//print start_box($titre,"twelve","16-Companies.png");

/*
 * Barre d'actions
 *
 */

print '<p class="button-height right">';
print '<a class="button icon-star" href="' . strtolower(get_class($object)) . '/fiche.php?action=create">' . $langs->trans("NewThirdParty") . '</a>';
print "</p>";

$i = 0;
$obj = new stdClass();
print '<table class="display dt_act" id="planning" >';
// Ligne des titres
print'<thead>';
print'<tr>';
print'<th>';
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "_id";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->bVisible = false;
$i++;
print'<th class="essential">';
print $langs->trans("Orders");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("name", "url");
$i++;
print'<th class="essential">';
print $langs->trans("Date");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("name", "url");
$i++;
print'<th class="essential">';
print $langs->trans("Ref");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("name", "url");
$i++;
print'<th class="essential">';
print $langs->trans("Companies");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "name";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("name", "url");
$i++;
foreach ($object->fk_extrafields->longList as $aRow) {
    print'<th class="essential">';
    if (isset($object->fk_extrafields->fields->$aRow->label))
        print $langs->transcountry($object->fk_extrafields->fields->$aRow->label, $mysoc->country_id);
    else
        print $langs->trans($aRow);
    print'</th>';
    $obj->aoColumns[$i] = new stdClass();
    $obj->aoColumns[$i] = $object->fk_extrafields->fields->$aRow->aoColumns;
    if (isset($object->fk_extrafields->$aRow->default))
        $obj->aoColumns[$i]->sDefaultContent = $object->fk_extrafields->$aRow->default;
    else {
    	if (! is_object($obj->aoColumns[$i]))
    		$obj->aoColumns[$i] = new stdClass(); // avoid error with php 5.4 strict mode
    	$obj->aoColumns[$i]->sDefaultContent = "";
    }
    $obj->aoColumns[$i]->mDataProp = $aRow;
    $i++;
}
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "dol_select center";
$obj->aoColumns[$i]->sWidth = "100px";
$obj->aoColumns[$i]->sDefaultContent = "ST_NEVER";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i] = new stdClass();
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->sWidth = "60px";
$obj->aoColumns[$i]->bSortable = false;
$obj->aoColumns[$i]->sDefaultContent = "";

$url = "societe/fiche.php";
if ($user->rights->societe->creer && $user->rights->societe->supprimer) {
    $obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"' . $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=edit&backtopage=' . $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"' . $langs->trans("Edit") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"\"";
	ar[ar.length] = " class=\"delEnqBtn\" title=\"' . $langs->trans("Delete") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
	var str = ar.join("");
	return str;
}';
} elseif ($user->rights->societe->creer) {
    $obj->aoColumns[$i]->fnRender = 'function(obj) {
	var ar = [];
	ar[ar.length] = "<a href=\"' . $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=edit&backtopage=' . $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"' . $langs->trans("Edit") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
	var str = ar.join("");
	return str;
}';
}
print'</tr>';
print'</thead>';
print'<tfoot>';
/* input search view */
$i = 0; //Doesn't work with bServerSide
print'<tr>';
print'<th id="' . $i . '"></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Order") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search date") . '" /></th>';
$i++;
print'<th id="' . $i . '"></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Company") . '" /></th>';
$i++;
foreach ($object->fk_extrafields->longList as $aRow) {
    if ($object->fk_extrafields->fields->$aRow->aoColumns->bSearchable = true)
        print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search " . $aRow) . '" /></th>';
    else
        print'<th id="' . $i . '"></th>';
    $i++;
}
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search status") . '" /></th>';
$i++;
print'<th id="' . $i . '"></th>';
$i++;
print'</tr>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';
print "</table>";

//$obj->bServerSide = true;
//$obj->sDom = 'C<\"clear\">lfrtip';
//if (!$user->rights->societe->client->voir)
//    $obj->sAjaxSource = "core/ajax/listdatatables.php?json=listByCommercial&key=" . $user->id . "&class=" . get_class($object);

$object->datatablesCreate($obj, "planning", true, true);

//print end_box();
print '</div>'; // end

llxFooter();
?>
