<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010-2012 Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2012      David Moothen        <dmoothen@websitti.fr>
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
require_once(DOL_DOCUMENT_ROOT . "/contact/class/contact.class.php");
require_once(DOL_DOCUMENT_ROOT . "/agenda/class/agenda.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/date.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/agenda/lib/agenda.lib.php");
if (!empty($conf->projet->enabled))
    require_once(DOL_DOCUMENT_ROOT . "/core/lib/project.lib.php");

$langs->load("companies");
$langs->load("agenda");
$langs->load("commercial");


$showbirthday = empty($conf->use_javascript_ajax) ? GETPOST("showbirthday", "int") : 1;



// Security check
$socid = GETPOST("socid", 'int');
if ($user->societe_id)
    $socid = $user->societe_id;
$result = restrictedArea($user, 'agenda', 0, '', 'myactions');

$canedit = 1;
if (!$user->rights->agenda->myactions->read)
    accessforbidden();
if (!$user->rights->agenda->allactions->read)
    $canedit = 0;
if (!$user->rights->agenda->allactions->read || $filter == 'mine') { // If no permission to see all, we show only affected to me
    $filtera = $user->id;
    $filtert = $user->id;
    $filterd = $user->id;
}



/*
 * 	Actions
 */

/*
 *  View
 */

$now = dol_now();

$help_url = 'EN:Module_Agenda_En|FR:Module_Agenda|ES:M&omodulodulo_Agenda';
llxHeader('', $langs->trans("Agenda"), $help_url);

$form = new Form($db);

$object = new Agenda($db);
$contact = new Contact($db);
$societe = new Societe($db);
$societestatic = new Societe($db);
$userstatic = new User($db);

$title = $langs->trans("DoneAndToDoActions");
if ($status == 'done')
    $title = $langs->trans("DoneActions");
if ($status == 'todo')
    $title = $langs->trans("ToDoActions");

if ($socid) {
    $societe = new Societe($db);
    $societe->fetch($socid);
    $newtitle = $langs->trans($title) . ' ' . $langs->trans("For") . ' ' . $societe->name;
} else {
    $newtitle = $langs->trans($title);
}


print_fiche_titre($newtitle);
?>
<div class="dashboard">
    <div class="columns">
        <div class="nine-columns twelve-columns-mobile graph">
            <?php echo $object->graphEisenhower(); ?>
        </div>

        <div class="three-columns twelve-columns-mobile new-row-mobile">
            <ul class="stats split-on-mobile">
                <li>
                    <?php
                    $agenda = new Agenda($db);
                    $result = $agenda->getView("countTODO", array("group" => true, "key" => $user->id));                        
                    print '<strong>'.(int)$result->rows[0]->value.'</strong>';
                    print $langs->trans('NewActions');
                    ?>
                </li>
                <li>
                    <?php
                    $result = $agenda->getView("countON", array("group" => true, "key" => $user->id));
                    print '<strong>'.(int)$result->rows[0]->value.'</strong>';
                    print $langs->trans('DoActions');
                    ?>
                </li>
                <li><?php
                    $result = $agenda->getView("countByUser", array("group" => true,"group_level"=>"none", "startkey" => array($user->id, mktime(0, 0, 0, 1, 1, date(Y))),"endkey"=>array($user->id, new stdClass())));
                    print '<strong>'.(int)$result->rows[0]->value.'</strong>';
                    print $langs->trans('SumMyActions');
                    ?>
                </li>
                <li>
                    <?php
                    $result = $agenda->getView("count", array("group" => true,"group_level"=>"none", "startkey" => mktime(0, 0, 0, 1, 1, date(Y)),"endkey"=>new stdClass()));
                    print '<strong>'.(int)$result->rows[0]->value.'</strong>';
                    print $langs->trans('SumActions');
                    ?>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php
print '<div class="with-padding">';

/*
 * Barre d'actions
 *
 */

if ($user->rights->agenda->myactions->create || $user->rights->agenda->allactions->create) {
    print '<p class="button-height right">';
    print '<span class="button-group">';
    print '<a class="button icon-star" href="' . strtolower(get_class($object)) . '/fiche.php?action=create">' . $langs->trans("NewAction") . '</a>';
    print "</span>";
    print "</p>";
}

$i = 0;
$obj = new stdClass();
print '<table class="display dt_act" id="listactions" >';
// Ligne des titres 
print'<thead>';
print'<tr>';
print'<th>';
print'</th>';
$obj->aoColumns[$i]->mDataProp = "_id";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->bVisible = false;
$i++;
print'<th class="essential">';
print $langs->trans("Titre");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "label";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = true;
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("label", "url");
$i++;
print'<th class="essential">';
print $langs->trans('DateEchAction');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "datef";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("datef", "datetime");
//$obj->aoColumns[$i]->sClass = "edit";
$i++;
print'<th class="essential">';
print $langs->trans('Company');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "societe.name";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $societe->datatablesFnRender("societe.name", "url", array('id' => "societe.id"));
$i++;
print'<th class="essential">';
print $langs->trans('Contact');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "contact.name";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $contact->datatablesFnRender("contact.name", "url", array('id' => "contact.id"));
$i++;
print'<th class="essential">';
print $langs->trans('ActionUserAsk');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "author";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $userstatic->datatablesFnRender("author.name", "url", array('id' => "author.id"));
$i++;
print'<th class="essential">';
print $langs->trans('AffectedTo');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "usertodo";
$obj->aoColumns[$i]->sClass = "dol_select";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $userstatic->datatablesFnRender("usertodo.name", "url", array('id' => "usertodo.id"));
$i++;
print'<th class="essential">';
print $langs->trans('DoneBy');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "userdone";
$obj->aoColumns[$i]->sDefaultContent = "";
$obj->aoColumns[$i]->fnRender = $userstatic->datatablesFnRender("userdone.name", "url", array('id' => "userdone.id"));
$i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "center";
$obj->aoColumns[$i]->sDefaultContent = "0";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status", array("dateEnd" => "last_subscription_date_end"));
$i++;
print'<th class="essential">';
print $langs->trans('Action');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->sWidth = "60px";
$obj->aoColumns[$i]->bSortable = false;
$obj->aoColumns[$i]->sDefaultContent = "";

$url = "agenda/fiche.php";
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
print'</tr>';
print'</thead>';
print'<tfoot>';
/* input search view */
$i = 0; //Doesn't work with bServerSide
print'<tr>';
print'<th id="' . $i . '"></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Name") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Date") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Company") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Contact") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search author") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search usertodo") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search userdone") . '" /></th>';
$i++;
print'<th id="' . $i . '"><input type="text" placeholder="' . $langs->trans("Search Status") . '" /></th>';
$i++;
print'<th id="' . $i . '"></th>';
$i++;
print'</tr>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print "</table>";

//$obj->bServerSide = true;
//$obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listDatatables.php?json=listTasks&class=" . get_class($object);
$object->datatablesCreate($obj, "listactions", true, true);

print '</div>'; // end

llxFooter();
?>
