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

    $head[$h][0] = DOL_URL_ROOT . '/agenda/fiche.php?id=' . $object->id;
    $head[$h][1] = $langs->trans("CardAction");
    $head[$h][2] = 'card';
    $h++;

    if ($conf->ecm->enabled) {
        $head[$h][0] = DOL_URL_ROOT . '/agenda/document.php?id=' . $object->id;
        $head[$h][1] = $langs->trans('Documents');
        $head[$h][2] = 'documents';
        $h++;
    }

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
    $events = $object->getView("list", array("startkey" => $firstDayTimestamp, "endkey" => $lastDayTimestamp));

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
                    print '<li class="important"><a href="' . DOL_URL_ROOT . '/agenda/fiche.php?id=' . $events->rows[$cursor]->id . '" >' . $events->rows[$cursor]->value->label . '</a></li>';
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
    $events = $object->getView("list", array("startkey" => $timestamps[0]['start'], "endkey" => $timestamps[6]['end']));

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

                    print '<a class="agenda-event from-' . $hourStart . ' to-' . $hourEnd . ' anthracite-gradient" href="/agenda/fiche.php?id=' . $events->rows[$cursor]->id . '">';
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