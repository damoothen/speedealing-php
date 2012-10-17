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

?>