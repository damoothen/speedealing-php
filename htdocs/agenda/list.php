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

// Define list of all external calendars
$listofextcals = array();


$param = '';
if ($status)
    $param = "&status=" . $status;
if ($filter)
    $param.="&filter=" . $filter;
if ($filtera)
    $param.="&filtera=" . $filtera;
if ($filtert)
    $param.="&filtert=" . $filtert;
if ($filterd)
    $param.="&filterd=" . $filterd;
if ($socid)
    $param.="&socid=" . $socid;
if ($showbirthday)
    $param.="&showbirthday=1";
if ($pid)
    $param.="&projectid=" . $pid;
if ($type)
    $param.="&type=" . $type;
/*
$sql = "SELECT s.nom as societe, s.rowid as socid, s.client,";
$sql.= " a.id, a.datep as dp, a.datep2 as dp2,";
//$sql.= " a.datea as da, a.datea2 as da2,";
$sql.= " a.fk_contact, a.note, a.label, a.percent as percent,";
$sql.= " c.code as acode, c.libelle, c.type, a.note,";
$sql.= " ua.login as loginauthor, ua.rowid as useridauthor,";
$sql.= " ut.login as logintodo, ut.rowid as useridtodo,";
$sql.= " ud.login as logindone, ud.rowid as useriddone,";
$sql.= " sp.name, sp.firstname";
$sql.= " FROM (" . MAIN_DB_PREFIX . "c_actioncomm as c,";
if (!$user->rights->societe->client->voir && !$socid)
    $sql.= " " . MAIN_DB_PREFIX . "societe_commerciaux as sc,";
$sql.= " " . MAIN_DB_PREFIX . 'user as u,';
$sql.= " " . MAIN_DB_PREFIX . "actioncomm as a)";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON a.fk_soc = s.rowid";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "socpeople as sp ON a.fk_contact = sp.rowid";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "user as ua ON a.fk_user_author = ua.rowid";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "user as ut ON a.fk_user_action = ut.rowid";
$sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "user as ud ON a.fk_user_done = ud.rowid";
$sql.= " WHERE c.id = a.fk_action";
$sql.= ' AND a.fk_user_author = u.rowid';
$sql.= ' AND a.entity IN (' . getEntity() . ')'; // To limit to entity
if ($actioncode)
    $sql.=" AND c.code='" . $db->escape($actioncode) . "'";
if ($pid)
    $sql.=" AND a.fk_project=" . $db->escape($pid);
if (!$user->rights->societe->client->voir && !$socid)
    $sql.= " AND s.rowid = sc.fk_soc AND sc.fk_user = " . $user->id;
if ($socid)
    $sql.= " AND s.rowid = " . $socid;
if ($type)
    $sql.= " AND c.id = " . $type;
if ($status == 'done') {
    $sql.= " AND (a.percent = 100 OR (a.percent = -1 AND a.datep2 <= '" . $db->idate($now) . "'))";
}
if ($status == 'todo') {
    $sql.= " AND ((a.percent >= 0 AND a.percent < 100) OR (a.percent = -1 AND a.datep2 > '" . $db->idate($now) . "'))";
}
if ($filtera > 0 || $filtert > 0 || $filterd > 0) {
    $sql.= " AND (";
    if ($filtera > 0)
        $sql.= " a.fk_user_author = " . $filtera;
    if ($filtert > 0)
        $sql.= ($filtera > 0 ? " OR " : "") . " a.fk_user_action = " . $filtert;
    if ($filterd > 0)
        $sql.= ($filtera > 0 || $filtert > 0 ? " OR " : "") . " a.fk_user_done = " . $filterd;
    $sql.= ")";
}
$sql.= $db->order($sortfield, $sortorder);
$sql.= $db->plimit($limit + 1, $offset);
//print $sql;

dol_syslog("comm/action/listactions.php sql=" . $sql);
$resql = $db->query($sql);
$num = $db->num_rows($resql);
 * 
 */
$object = new Agenda($db);
$contact = new Contact($db);
$societe = new Societe($db);
$societestatic = new Societe($db);

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
print '<div class="with-padding">';

$i=0;
$obj=new stdClass();
print '<div class="datatable">';
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
$obj->aoColumns[$i]->fnRender= $object->datatablesFnRender("label", "url");
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
$i++;
print'<th class="essential">';
print $langs->trans('AffectedTo');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "usertodo";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans('DoneBy');
print'</th>';
$obj->aoColumns[$i]->mDataProp = "userdone";
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("Status");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "Status";
$obj->aoColumns[$i]->sClass = "dol_select center";
$obj->aoColumns[$i]->sDefaultContent = "0";
$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("Status", "status", array("dateEnd"=>"last_subscription_date_end"));
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
	ar[ar.length] = "<a href=\"'. $url . '?id=";
	ar[ar.length] = obj.aData._id.toString();
	ar[ar.length] = "&action=edit&backtopage='. $_SERVER['PHP_SELF'] . '\" class=\"sepV_a\" title=\"'.$langs->trans("Edit").'\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/edit.png\" alt=\"\" /></a>";
	ar[ar.length] = "<a href=\"\"";
	ar[ar.length] = " class=\"delEnqBtn\" title=\"' . $langs->trans("Delete") . '\"><img src=\"' . DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/delete.png\" alt=\"\" /></a>";
	var str = ar.join("");
	return str;
}';
print'</tr>';
print'</thead>';
print'<tfoot>';
print'</tfoot>';
print'<tbody>';
print'</tbody>';

print "</table>";
print "</div>";

//$obj->bServerSide = true;
//$obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listDatatables.php?json=listTasks&class=" . get_class($object);
$object->datatablesCreate($obj,"listactions",true,true);

print '</div>'; // end

llxFooter();
?>
