<?php
/* Copyright (C) 2008-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011	   Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
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
 * or see http://www.gnu.org/
 */

/**
 *  \file		htdocs/core/lib/agenda.lib.php
 *  \brief		Set of function for the agenda module
 */

/**
 * Show filter form in agenda view
 *
 * @param	Object	$form			Form object
 * @param	int		$canedit		Can edit filter fields
 * @param	int		$status			Status
 * @param 	int		$year			Year
 * @param 	int		$month			Month
 * @param 	int		$day			Day
 * @param 	int		$showbirthday	Show birthday
 * @param 	string	$filtera		Filter on create by user
 * @param 	string	$filtert		Filter on assigned to user
 * @param 	string	$filterd		Filter of done by user
 * @param 	int		$pid			Product id
 * @param 	int		$socid			Third party id
 * @param	array	$showextcals	Array with list of external calendars, or -1 to show no legend
 * @return	void
 */
function print_actions_filter($form, $canedit, $status, $year, $month, $day, $showbirthday, $filtera, $filtert, $filterd, $pid, $socid, $showextcals = array()) {
    global $conf, $langs, $db;

    // Filters
    if ($canedit || $conf->projet->enabled) {
        print '<form name="listactionsfilter" class="listactionsfilter" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
        print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
        print '<input type="hidden" name="status" value="' . $status . '">';
        print '<input type="hidden" name="year" value="' . $year . '">';
        print '<input type="hidden" name="month" value="' . $month . '">';
        print '<input type="hidden" name="day" value="' . $day . '">';
        print '<input type="hidden" name="showbirthday" value="' . $showbirthday . '">';
        print '<table class="nobordernopadding" width="100%">';
        if ($canedit || $conf->projet->enabled) {
            print '<tr><td nowrap="nowrap">';

            print '<table class="nobordernopadding">';

            if ($canedit) {
                print '<tr>';
                print '<td nowrap="nowrap">';
                print $langs->trans("ActionsAskedBy");
                print ' &nbsp;</td><td nowrap="nowrap">';
                print $form->select_dolusers($filtera, 'userasked', 1, '', !$canedit);
                print '</td>';
                print '</tr>';

                print '<tr>';
                print '<td nowrap="nowrap">';
                print $langs->trans("or") . ' ' . $langs->trans("ActionsToDoBy");
                print ' &nbsp;</td><td nowrap="nowrap">';
                print $form->select_dolusers($filtert, 'usertodo', 1, '', !$canedit);
                print '</td></tr>';

                print '<tr>';
                print '<td nowrap="nowrap">';
                print $langs->trans("or") . ' ' . $langs->trans("ActionsDoneBy");
                print ' &nbsp;</td><td nowrap="nowrap">';
                print $form->select_dolusers($filterd, 'userdone', 1, '', !$canedit);
                print '</td></tr>';

                include_once(DOL_DOCUMENT_ROOT . '/core/class/html.formactions.class.php');
                $formactions = new FormActions($db);
                print '<tr>';
                print '<td nowrap="nowrap">';
                print $langs->trans("Type");
                print ' &nbsp;</td><td nowrap="nowrap">';
                print $formactions->select_type_actions(GETPOST('actioncode'), "actioncode");
                print '</td></tr>';
            }

            if ($conf->projet->enabled) {
                print '<tr>';
                print '<td nowrap="nowrap">';
                print $langs->trans("Project") . ' &nbsp; ';
                print '</td><td nowrap="nowrap">';
                select_projects($socid ? $socid : -1, $pid, 'projectid', 64);
                print '</td></tr>';
            }

            print '</table>';
            print '</td>';

            // Buttons
            print '<td align="center" valign="middle" nowrap="nowrap">';
            print img_picto($langs->trans("ViewCal"), 'object_calendar') . ' <input type="submit" class="button" style="width:120px" name="viewcal" value="' . $langs->trans("ViewCal") . '">';
            print '<br>';
            print img_picto($langs->trans("ViewWeek"), 'object_calendarweek') . ' <input type="submit" class="button" style="width:120px" name="viewweek" value="' . $langs->trans("ViewWeek") . '">';
            print '<br>';
            print img_picto($langs->trans("ViewDay"), 'object_calendarday') . ' <input type="submit" class="button" style="width:120px" name="viewday" value="' . $langs->trans("ViewDay") . '">';
            print '<br>';
            print img_picto($langs->trans("ViewList"), 'object_list') . ' <input type="submit" class="button" style="width:120px" name="viewlist" value="' . $langs->trans("ViewList") . '">';
            print '</td>';

            // Legend
            if ($conf->use_javascript_ajax && is_array($showextcals)) {
                print '<td align="center" valign="middle" nowrap="nowrap">';
                print '<script type="text/javascript">' . "\n";
                print 'jQuery(document).ready(function () {' . "\n";
                print 'jQuery("#check_mytasks").click(function() { jQuery(".family_mytasks").toggle(); jQuery(".family_other").toggle(); });' . "\n";
                print 'jQuery("#check_birthday").click(function() { jQuery(".family_birthday").toggle(); });' . "\n";
                print 'jQuery(".family_birthday").toggle();' . "\n";
                print '});' . "\n";
                print '</script>' . "\n";
                print '<table>';
                if (!empty($conf->global->MAIN_JS_SWITCH_AGENDA)) {
                    if (count($showextcals) > 0) {
                        print '<tr><td><input type="checkbox" id="check_mytasks" name="check_mytasks" checked="true" disabled="disabled"> ' . $langs->trans("LocalAgenda") . '</td></tr>';
                        foreach ($showextcals as $val) {
                            $htmlname = dol_string_nospecial($val['name']);
                            print '<script type="text/javascript">' . "\n";
                            print 'jQuery(document).ready(function () {' . "\n";
                            print 'jQuery("#check_' . $htmlname . '").click(function() { jQuery(".family_' . $htmlname . '").toggle(); });' . "\n";
                            print '});' . "\n";
                            print '</script>' . "\n";
                            print '<tr><td><input type="checkbox" id="check_' . $htmlname . '" name="check_' . $htmlname . '" checked="true"> ' . $val['name'] . '</td></tr>';
                        }
                    }
                }
                print '<tr><td><input type="checkbox" id="check_birthday" name="check_birthday checked="false"> ' . $langs->trans("AgendaShowBirthdayEvents") . '</td></tr>';
                print '</table>';
                print '</td>';
            }

            print '</tr>';
        }
        print '</table>';
        print '</form>';
    }
}

function show_array_actions_to_do($max = 5, $fk_task = 0) {
    global $langs, $conf, $user, $db, $bc, $socid;
    $titre = $langs->trans("ActionsToDo");
    print start_box($titre, "twelve", "16-Mail.png");

    $i = 0;
    $obj = new stdClass();
    $object = new Agenda($db);
    print '<table class="display dt_act" id="actionsToDo_datatable" >';
    // Ligne des titres

    print '<thead>';
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
//$obj->aoColumns[$i]->sClass = "edit";
    $i++;
    print'<th class="essential">';
    print $langs->trans('AffectedTo');
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "usertodo";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print'<th class="essential">';
    print $langs->trans("Status");
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "Status";
    $obj->aoColumns[$i]->sClass = "dol_select center";
    $obj->aoColumns[$i]->sDefaultContent = "0";
    $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("status", "status", array("dateEnd" => "last_subscription_date_end"));
    $i++;
    print '</tr>';
    print '</thead>';
    print'<tfoot>';
    print'</tfoot>';
    print'<tbody>';
    $result = $object->getView('actionsToDo');
    if (count($result->rows) > 0)
        foreach ($result->rows as $key => $aRow) {
            print '<tr>';
            print'<td></td>';
            print '<td>' . $aRow->value->label . '</td>';
            print '<td>' . $aRow->value->datef . '</td>';
            print '<td>' . $aRow->value->societe->name . '</td>';
            print '<td>' . $aRow->value->usertodo . '</td>';
            print '<td>' . $aRow->value->status . '</td>';
            print '</tr>';
        }
    print'</tbody>';
    print "</table>";

    $obj->bServerSide = false;
    $obj->iDisplayLength = 10;
    $object->datatablesCreate($obj, "actionsToDo_datatable");

    print end_box();
}

/**
 *  Show actions to do array
 *
 *  @param	int		$max		Max nb of records
 *  @return	void
 */
/*
  function show_array_actions_to_do($max = 5, $fk_task = 0) {
  global $langs, $conf, $user, $db, $bc, $socid;

  $now = dol_now();

  include_once(DOL_DOCUMENT_ROOT . '/agenda/class/agenda.class.php');
  include_once(DOL_DOCUMENT_ROOT . '/societe/class/client.class.php');

  $sql = "SELECT a.id, a.label, a.datep as dp, a.fk_user_author, a.percent,a.note,";
  $sql.= " c.code, c.libelle,c.type,";
  $sql.= " s.nom as sname, s.rowid, s.client";
  $sql.= " FROM " . MAIN_DB_PREFIX . "actioncomm as a";
  $sql.= ", " . MAIN_DB_PREFIX . "c_actioncomm as c";
  $sql.= ", " . MAIN_DB_PREFIX . "societe as s";
  if (!$user->rights->societe->client->voir && !$socid)
  $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
  $sql.= ")";
  $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON a.fk_soc = s.rowid";
  $sql.= " WHERE c.id = a.fk_action";
  $sql.= " AND a.percent < 100";
  $sql.= " AND s.rowid = a.fk_soc";
  $sql.= " AND s.entity = " . $conf->entity;
  if ($fk_task)
  $sql.= " AND a.fk_task = " . $fk_task;
  if (!$user->rights->societe->client->voir && !$socid)
  $sql.= " AND s.rowid = sc.fk_soc AND sc.fk_user = " . $user->id;
  if ($socid)
  $sql.= " AND s.rowid = " . $socid;
  $sql.= " ORDER BY a.datep DESC, a.id DESC";
  $sql.= $db->plimit($max, 0);

  $resql = $db->query($sql);
  if ($resql) {
  $num = $db->num_rows($resql);
  if ($num > 0) {
  print '<table class="noborder" width="100%">';
  print '<tr class="liste_titre"><td colspan="3">' . $langs->trans("LastActionsToDo", $max) . ($fk_task ? " " . $langs->trans("LinkToThisTask") : '') . '</td>';
  print '<td colspan="2" align="right"><a href="' . DOL_URL_ROOT . '/comm/action/listactions.php?status=todo">' . $langs->trans("FullList") . '</a>';
  print '</tr>';
  $var = true;
  $i = 0;

  $staticaction = new Agenda($db);
  $customerstatic = new Client($db);

  while ($i < $num) {
  $obj = $db->fetch_object($resql);
  $var = !$var;

  print "<tr $bc[$var]>";

  $staticaction->type_code = $obj->code;
  $staticaction->libelle = $obj->libelle;
  $staticaction->id = $obj->id;
  $staticaction->type = $obj->type;
  $staticaction->note = $obj->note;
  print '<td>' . $staticaction->getNomUrl(1, 16) . '</td>';

  print '<td>' . dol_trunc($obj->label, 22) . '</td>';

  $customerstatic->id = $obj->rowid;
  $customerstatic->nom = $obj->sname;
  $customerstatic->client = $obj->client;
  print '<td>' . $customerstatic->getNomUrl(1, '', 24) . '</td>';

  $datep = $db->jdate($obj->dp);
  $datep2 = $db->jdate($obj->dp2);

  // Date
  print '<td width="100" align="right">' . dol_print_date($datep, 'day') . '&nbsp;';
  $late = 0;
  if ($obj->percent <= 0 && $datep && $datep < time())
  $late = 1;
  if ($obj->percent == 0 && !$datep && $datep2 && $datep2 < time())
  $late = 1;
  if ($obj->percent > 0 && $obj->percent < 100 && $datep2 && $datep2 < time())
  $late = 1;
  if ($obj->percent > 0 && $obj->percent < 100 && !$datep2 && $datep && $datep < time())
  $late = 1;
  if ($late)
  print img_warning($langs->trans("Late"));
  print "</td>";

  // Statut
  print '<td align="right" width="14">' . $staticaction->LibStatut($obj->percent, 3) . '</td>';

  print "</tr>\n";

  $i++;
  }
  print "</table><br>";
  }
  $db->free($resql);
  }
  else {
  dol_print_error($db);
  }
  }
 * 
 */

function show_array_last_actions_done($max = 5, $fk_task = 0) {
    global $langs, $conf, $user, $db, $bc, $socid;
    $titre = $langs->trans("ActionsDone");
    print start_box($titre, "twelve", "16-Mail.png");

    $i = 0;
    $obj = new stdClass();
    $object = new Agenda($db);
    print '<table class="display dt_act" id="actionsDone_datatable" >';
    // Ligne des titres

    print '<thead>';
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
    $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("datef", "date");
//$obj->aoColumns[$i]->sClass = "edit";
    $i++;
    print'<th class="essential">';
    print $langs->trans('Company');
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "societe.name";
    $obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
    $i++;
    print'<th class="essential">';
    print $langs->trans('AffectedTo');
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "usertodo";
    $obj->aoColumns[$i]->sDefaultContent = "";
    $i++;
    print'<th class="essential">';
    print $langs->trans("Status");
    print'</th>';
    $obj->aoColumns[$i]->mDataProp = "status";
    $obj->aoColumns[$i]->sClass = "dol_select center";
    $obj->aoColumns[$i]->sDefaultContent = "0";
    $obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("status", "status", array("dateEnd" => "last_subscription_date_end"));
    $i++;
    print '</tr>';
    print '</thead>';
    print'<tfoot>';
    print'</tfoot>';
    print'<tbody>';
    $result = $object->getView('actionsDone');
    if (count($result->rows) > 0)
        foreach ($result->rows as $key => $aRow) {
            print '<tr>';
            print'<td></td>';
            print '<td>' . $aRow->value->label . '</td>';
            print '<td>' . $aRow->value->datef . '</td>';
            print '<td>' . $aRow->value->societe->ThirdPartyName . '</td>';
            print '<td>' . $aRow->value->usertodo . '</td>';
            print '<td>' . $aRow->value->status . '</td>';
            print '</tr>';
        }
    print'</tbody>';
    print "</table>";

    $obj->bServerSide = false;
    $obj->iDisplayLength = 10;
    $object->datatablesCreate($obj, "actionsDone_datatable");
    print end_box();
}

/**
 *  Show last actions array
 *
 *  @param	int		$max		Max nb of records
 *  @return	void
 */
/*
  function show_array_last_actions_done($max = 5) {
  global $langs, $conf, $user, $db, $bc, $socid;

  $sql = "SELECT a.id, a.percent, a.datep as da, a.datep2 as da2, a.fk_user_author, a.label, a.note,";
  $sql.= " c.code, c.libelle, c.type,";
  $sql.= " s.rowid, s.nom as sname, s.client";
  $sql.= " FROM " . MAIN_DB_PREFIX . "actioncomm as a";
  $sql.= ", " . MAIN_DB_PREFIX . "c_actioncomm as c";
  $sql.= ", " . MAIN_DB_PREFIX . "societe as s";
  if (!$user->rights->societe->client->voir && !$socid)
  $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
  $sql.= " WHERE c.id = a.fk_action";
  $sql.= " AND a.percent >= 100";
  $sql.= " AND s.rowid = a.fk_soc";
  $sql.= " AND s.entity = " . $conf->entity;
  if ($fk_task)
  $sql.= " AND a.fk_task = " . $fk_task;
  if ($socid)
  $sql.= " AND s.rowid = " . $socid;
  if (!$user->rights->societe->client->voir && !$socid)
  $sql.= " AND s.rowid = sc.fk_soc AND sc.fk_user = " . $user->id;
  $sql .= " ORDER BY a.datep2 DESC";
  $sql .= $db->plimit($max, 0);

  $resql = $db->query($sql);
  if ($resql) {
  $num = $db->num_rows($resql);
  if ($num > 0) {

  print '<table class="noborder" width="100%">';
  print '<tr class="liste_titre"><td colspan="3">' . $langs->trans("LastDoneTasks", $max) . ($fk_task ? (" " . $langs->trans("LinkToThisTask")) : "") . '</td>';
  print '<td colspan="2" align="right"><a href="' . DOL_URL_ROOT . '/comm/action/listactions.php?status=done">' . $langs->trans("FullList") . '</a>';
  print '</td></tr>';
  $var = true;
  $i = 0;

  $object = new Agenda($db);
  $customerstatic = new Societe($db);

  while ($i < $num) {
  $obj = $db->fetch_object($resql);
  $var = !$var;

  print '<tr ' . $bc[$var] . '>';

  $object->type_code = $obj->code;
  $object->libelle = $obj->label;
  $object->id = $obj->id;
  $object->type = $obj->type;
  $object->note = $obj->note;
  print '<td>' . $object->getNomUrl(1, 16) . '</td>';

  print '<td>' . dol_trunc($obj->label, 22) . '</td>';

  $customerstatic->id = $obj->rowid;
  $customerstatic->nom = $obj->sname;
  $customerstatic->client = $obj->client;
  print '<td>' . $customerstatic->getNomUrl(1, '', 24) . '</td>';

  // Date
  print '<td width="100" align="right">' . dol_print_date($db->jdate($obj->da2), 'day');
  print "</td>";

  // Statut
  print '<td align="right" width="14">' . $object->LibStatut($obj->percent, 3) . '</td>';

  print "</tr>\n";
  $i++;
  }
  // TODO Ajouter rappel pour "il y a des contrats a mettre en service"
  // TODO Ajouter rappel pour "il y a des contrats qui arrivent a expiration"
  print "</table><br>";
  }
  $db->free($resql);
  } else {
  dol_print_error($db);
  }
  }
 */

/**
 * Prepare array with list of tabs
 *
 * @return  array				Array of tabs to shoc
 */
function agenda_prepare_head() {
    global $langs, $conf, $user;
    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . "/admin/agenda.php";
    $head[$h][1] = $langs->trans("AutoActions");
    $head[$h][2] = 'autoactions';
    $h++;

    $head[$h][0] = DOL_URL_ROOT . "/admin/agenda_xcal.php";
    $head[$h][1] = $langs->trans("ExportCal");
    $head[$h][2] = 'xcal';
    $h++;

    $head[$h][0] = DOL_URL_ROOT . "/admin/agenda_extsites.php";
    $head[$h][1] = $langs->trans("ExtSites");
    $head[$h][2] = 'extsites';
    $h++;


    return $head;
}

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function actions_prepare_head($object) {
    global $langs, $conf, $user;

    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . '/comm/action/fiche.php?id=' . $object->id;
    $head[$h][1] = $langs->trans("CardAction");
    $head[$h][2] = 'card';
    $h++;

    if ($conf->ecm->enabled) {
        $head[$h][0] = DOL_URL_ROOT . '/comm/action/document.php?id=' . $object->id;
        $head[$h][1] = $langs->trans('Documents');
        $head[$h][2] = 'documents';
        $h++;
    }

    $head[$h][0] = DOL_URL_ROOT . '/comm/action/info.php?id=' . $object->id;
    $head[$h][1] = $langs->trans('Info');
    $head[$h][2] = 'info';
    $h++;

    return $head;
}

/**
 *  Define head array for tabs of agenda setup pages
 *
 *  @param	string	$param		Parameters to add to url
 *  @return array			    Array of head
 */
function calendars_prepare_head($param) {
    global $langs, $conf, $user;

    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . '/comm/action/index.php' . ($param ? '?' . $param : '');
    $head[$h][1] = $langs->trans("Agenda");
    $head[$h][2] = 'card';
    $h++;

    $object = (object) array();

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'agenda');

    return $head;
}

function print_calendar($date) {

    global $db, $langs;
    $nbDaysInMonth = date('t', $date);
    $firstDayTimestamp = dol_mktime(-1, -1, -1, date('n', $date), 1, date('Y', $date));
    $lastDayTimestamp = dol_mktime(23, 59, 59, date('n', $date), $nbDaysInMonth, date('Y', $date));
    $todayTimestamp = dol_mktime(-1, -1, -1, date('n'), date('j'), date('Y'));
    $firstDayOfMonth = date('w', $firstDayTimestamp);

    $object = new Agenda($db);
    $events = $object->getView("listRdv", array("startkey" => $firstDayTimestamp, "endkey" => $lastDayTimestamp));

    print '<table class="calendar fluid large-margin-bottom with-events">';

    // Month an scroll arrows
    print '<caption>';
    print '<span class="cal-prev" >◄</span>';
    print '<a class="cal-next" href="#">►</a>';
    print $langs->trans(date('F', $date)) . ' ' . date('Y', $date);
    print '</caption>';

    // Days names 
    print '<thead>';
    print '<tr>';
    print '<th scope="col">Sun</th>';
    print '<th scope="col">Mon</th>';
    print '<th scope="col">Tue</th>';
    print '<th scope="col">Wed</th>';
    print '<th scope="col">Thu</th>';
    print '<th scope="col">Fri</th>';
    print '<th scope="col">Sat</th>';
    print '</tr>';
    print '</thead>';
    print '<tbody>';
    print '<tr>';

    $calendarCounter = 1;
    for ($i = $firstDayOfMonth; $i > 0; $i--, $calendarCounter++) {
        $previousTimestamp = strtotime($i . " day ago", $firstDayTimestamp);
        print '<td class="prev-month"><span class="cal-day">' . date('d', $previousTimestamp) . '</span></td>';
    }

    $cursor = 0;
    for ($i = 1; $i <= $nbDaysInMonth; $i++, $calendarCounter++) {
        $dayTimestamp = dol_mktime(-1, -1, -1, date('n', $date), $i, date('Y', $date));
        if ($calendarCounter > 1 && ($calendarCounter - 1) % 7 == 0)
            print '</tr><tr>';
        print '<td class="' . ((date('w', $dayTimestamp) == 0 || date('w', $dayTimestamp) == 6) ? 'week-end ' : '') . ' ' . (($dayTimestamp == $todayTimestamp) ? 'today ' : '') . '"><span class="cal-day">' . $i . '</span>';
        print '<ul class="cal-events">';

        if (!empty($events->rows[$cursor])) {
            for ($j = 0; $j < count($events->rows); $j++) {
                if ($events->rows[$cursor]->key >= $dayTimestamp && $events->rows[$cursor]->key < $dayTimestamp + 3600 * 24) {
                    print '<li class="important"><a href="/agenda/fiche.php?id='.$events->rows[$cursor]->id.'" >' . $events->rows[$cursor]->value->label . '</a></li>';
                    $cursor++;
                } else
                    break;
            }
        }

        print '</ul>';
        print '</td>';
    }

    $calendarCounter--;
    $i = 1;
    while ($calendarCounter++ % 7 != 0) {
        print '<td class="next-month"><span class="cal-day">' . $i++ . '</span></td>';
    }

    print '</tr>';

    print '</tbody>';
    print '</table>';
}

function print_week($date) {

    global $db, $langs;

    $timestamps = array();
    $dayOfWeek = date('w', $date);
    for ($i = 0, $d = -$dayOfWeek; $i < 7; $i++, $d++) {
        $tmpTimestamp = strtotime($d . " day", $date);
        $timestamps[$i] = array(
            'start' => dol_mktime(0, 0, 0, date('n', $tmpTimestamp), date('j', $tmpTimestamp), date('Y', $tmpTimestamp)),
            'end' => dol_mktime(23, 59, 59, date('n', $tmpTimestamp), date('j', $tmpTimestamp), date('Y', $tmpTimestamp)),
        );
    }

    $object = new Agenda($db);
    $events = $object->getView("listRdv", array("startkey" => $timestamps[0]['start'], "endkey" => $timestamps[6]['end']));

    $styles = array(
        0 => 'left: 0%; right: 85.7143%; margin-left: -1px;',
        1 => 'left: 14.2857%; right: 71.4286%; margin-left: 0px;',
        2 => 'left: 28.5714%; right: 57.1429%; margin-left: 0px;',
        3 => 'left: 42.8571%; right: 42.8571%; margin-left: 0px;',
        4 => 'left: 57.1429%; right: 28.5714%; margin-left: 0px;',
        5 => 'left: 71.4286%; right: 14.2857%; margin-left: 0px;',
        6 => 'left: 85.7143%; right: 0%; margin-left: 0px;'
    );

    $days = array(
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday'
    );

    print '<div class="agenda with-header auto-scroll scrolling-agenda">';
    print '<ul class="agenda-time">
					<li class="from-7 to-8"><span>7 AM</span></li>
					<li class="from-8 to-9"><span>8 AM</span></li>
					<li class="from-9 to-10"><span>9 AM</span></li>
					<li class="from-10 to-11"><span>10 AM</span></li>
					<li class="from-11 to-12"><span>11 AM</span></li>
					<li class="from-12 to-13 blue"><span>NOON</span></li>
					<li class="from-13 to-14"><span>1 PM</span></li>
					<li class="from-14 to-15"><span>2 PM</span></li>
					<li class="from-15 to-16"><span>3 PM</span></li>
					<li class="from-16 to-17"><span>4 PM</span></li>
					<li class="from-17 to-18"><span>5 PM</span></li>
					<li class="from-18 to-19"><span>6 PM</span></li>
					<li class="from-19 to-20"><span>7 PM</span></li>
					<li class="at-20"><span>8 PM</span></li>
				</ul>';

    print '<div class="agenda-wrapper">';

    $cursor = 0;
    for ($i = 0; $i < 7; $i++) {
        $extraClass = '';
        if ($i == 0)
            $extraClass = 'agenda-visible-first';
        else if ($i == 6)
            $extraClass = 'agenda-visible-last';
        print '<div class="agenda-events agenda-day' . ($i + 1) . ' agenda-visible-column ' . $extraClass . '" style="' . $styles[$i] . '">';
        print '<div class="agenda-header">';
        print $langs->trans($days[$i]);
        print '</div>';

        if (!empty($events->rows[$cursor])) {
            for ($j = 0; $j < count($events->rows); $j++) {
                if ($events->rows[$cursor]->key >= $timestamps[$i]['start'] && $events->rows[$cursor]->key < $timestamps[$i]['end']) {
                    $dateStart = $events->rows[$cursor]->value->datep;
                    $dateEnd = $events->rows[$cursor]->value->datef;
                    if ($events->rows[$cursor]->value->type_code != 'AC_RDV')
                        $dateEnd = $dateStart + $events->rows[$cursor]->value->durationp;
                    $hourStart = date('G', $dateStart);
                    $hourEnd = date('G', $dateEnd);

                    print '<a class="agenda-event from-' . $hourStart . ' to-' . $hourEnd . ' anthracite-gradient" href="/agenda/fiche.php?id='.$events->rows[$cursor]->id.'">';
                    print '<time>' . $hourStart . 'h - ' . $hourEnd . 'h</time>';
                    print $events->rows[$cursor]->value->label;
                    print '</a>';
                    $cursor++;
                } else
                    break;
            }
        }

        print '</div>';
    }

    print '</div>';
    print '</div>';
    
}

function debug($var, $label = '') {
    echo '<pre>' . ($label ? $label . ': ' : '') . '' . print_r($var, true) . '</pre>';
}
?>