<?php
/* Copyright (C) 2001-2006	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2013	Regis Houssin			<regis.houssin@capnetworks.com>
 * Copyright (C) 2011		Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2011-2013	Herve Prot				<herve.prot@symeos.com>
 * Copyright (C) 2011		Patrick Mary			<laube@hotmail.fr>
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

/**
 * 	\file       societe/list.php
 * 	\brief      List of all thirdparties
 * 	\ingroup    thirdparty
 */
require '../main.inc.php';
require DOL_DOCUMENT_ROOT . '/core/class/autoloader.php';

$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");

$builder = GETPOST('builder', 'int'); // for test use "&builder=1"
// Security check
$socid = GETPOST("socid");
if ($user->societe_id)
	$socid = $user->societe_id;
$result = restrictedArea($user, 'societe', $socid, '');

$object = new Societe($db);
/*
 * View
 */

llxHeader('', $langs->trans("ThirdParty"), '', '', '', '');

if ($type != '') {
	if ($type == 0)
		$titre = $langs->trans("ListOfSuspects");
	elseif ($type == 1)
		$titre = $langs->trans("ListOfProspects");
	else
		$titre = $langs->trans("ListOfCustomers");
}
else
	$titre = $langs->trans("ListOfAll");

print_fiche_titre($titre);
?>
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
<?php
print '<div class="with-padding">';
//print '<div class="columns">';
//print start_box($titre,"twelve","16-Companies.png",false);

/*
 * Barre d'actions
 *
 */

print '<p class="button-height right">';
print '<a class="button icon-star" href="' . strtolower(get_class($object)) . '/fiche.php?action=create">' . $langs->trans("NewThirdParty") . '</a>';
print "</p>";

if (empty($builder)) {

	print $object->datatablesEdit("societe", $langs->trans("NewThirdParty"));

	$i = 0;
	$obj = new stdClass();
	print '<table class="display dt_act" id="societe" >';
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
	print $langs->trans("Company");
	print'</th>';
	$obj->aoColumns[$i] = new stdClass();
	$obj->aoColumns[$i]->mDataProp = "name";
	$obj->aoColumns[$i]->bUseRendered = false;
	$obj->aoColumns[$i]->bSearchable = true;
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("name", "url");
	$i++;
	if ($user->rights->societe->client->voir) {
		print'<th class="essential">';
		print $langs->trans("SalesRepresentatives");
		print'</th>';
		$obj->aoColumns[$i] = new stdClass();
		$obj->aoColumns[$i]->mDataProp = "commercial_id";
		$obj->aoColumns[$i]->bUseRendered = true;
		$obj->aoColumns[$i]->bSearchable = true;
		$obj->aoColumns[$i]->sDefaultContent = "";
		$obj->aoColumns[$i]->editable = true;
		$user_tmp = new User($db);
		$obj->aoColumns[$i]->fnRender = $user->datatablesFnRender("commercial_id.name", "url", array('id' => "commercial_id.id"));
		$i++;
	}
	foreach ($object->fk_extrafields->oldList as $aRow) {
		print'<th class="essential">';
		if (isset($object->fk_extrafields->fields->$aRow->label))
			print $langs->transcountry($object->fk_extrafields->fields->$aRow->label, $mysoc->country_id);
		else
			print $langs->trans($aRow);
		print'</th>';
		$obj->aoColumns[$i] = new stdClass();
		$obj->aoColumns[$i] = $object->fk_extrafields->fields->$aRow->list;
		if (isset($object->fk_extrafields->$aRow->default))
			$obj->aoColumns[$i]->sDefaultContent = $object->fk_extrafields->$aRow->default;
		else {
			if (!is_object($obj->aoColumns[$i]))
				$obj->aoColumns[$i] = new stdClass(); // to avoid strict mode warning
			$obj->aoColumns[$i]->sDefaultContent = "";
		}
		$obj->aoColumns[$i]->mDataProp = $aRow;
		$i++;
	}
	print'<th class="essential">';
	print $langs->trans('Categories');
	print'</th>';
	$obj->aoColumns[$i] = new stdClass();
	$obj->aoColumns[$i]->mDataProp = "Tag";
	$obj->aoColumns[$i]->sClass = "center";
	$obj->aoColumns[$i]->sDefaultContent = "";
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Tag", "tag");
	$i++;
	/* print'<th class="essential">';
	  print $langs->trans("Date");
	  print'</th>';
	  $obj->aoColumns[$i]->mDataProp = "tms";
	  $obj->aoColumns[$i]->sClass = "center";
	  $obj->aoColumns[$i]->bUseRendered = false;
	  $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("tms", "date");
	  $i++; */

	print'<th class="essential">';
	print $langs->trans("Status");
	print'</th>';
	$obj->aoColumns[$i] = new stdClass();
	$obj->aoColumns[$i]->mDataProp = "Status";
	$obj->aoColumns[$i]->sClass = "center";
	$obj->aoColumns[$i]->sWidth = "100px";
	$obj->aoColumns[$i]->sDefaultContent = "ST_NEVER";
	$obj->aoColumns[$i]->editable = true;
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status");
	$i++;
	print'<th class="essential">';
	print $langs->trans("ProspectLevelShort");
	print'</th>';
	$obj->aoColumns[$i] = new stdClass();
	$obj->aoColumns[$i]->mDataProp = "prospectlevel";
	$obj->aoColumns[$i]->sClass = "center";
	$obj->aoColumns[$i]->sDefaultContent = "PL_NONE";
	$obj->aoColumns[$i]->editable = true;
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("prospectlevel", "status");
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
	print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Company") . '" /></th>';
	$i++;
	if ($user->rights->societe->client->voir) {
		print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Commercial") . '" /></th>';
		$i++;
	}
	foreach ($object->fk_extrafields->oldList as $aRow) {
		if (is_bool($object->fk_extrafields->fields->$aRow->list->searchable) === true && $object->fk_extrafields->fields->$aRow->list->searchable === false)
			print '<th id="' . $i . '"></th>';
		else
			print '<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search " . $aRow) . '" /></th>';
		$i++;
	}
	print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search category") . '" /></th>';
	$i++;
	print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search status") . '" /></th>';
	$i++;
	print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search potential") . '" /></th>';
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
	if ($_GET["disable"])
		$obj->sAjaxSource = "core/ajax/listdatatables.php?json=listDisable&class=" . get_class($object);

	if (!$user->rights->societe->client->voir)
		if ($_GET["disable"])
			$obj->sAjaxSource = "core/ajax/listdatatables.php?json=listDisableByCommercial&key=" . $user->id . "&class=" . get_class($object);
		else
			$obj->sAjaxSource = "core/ajax/listdatatables.php?json=listByCommercial&key=" . $user->id . "&class=" . get_class($object);

	$object->datatablesCreate($obj, "societe", true, true);
}

echo '<br>';

if (!empty($builder))
	echo $object->showList();

//print end_box();
print '</div>'; // end

llxFooter();
?>
