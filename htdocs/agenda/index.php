<?php

/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Juanjo Menent        <jmenent@2byte.es>
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
 */

/**
 *  \file       htdocs/comm/action/index.php
 *  \ingroup    agenda
 *  \brief      Home page of calendar events
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/societe/class/societe.class.php");
require_once(DOL_DOCUMENT_ROOT . "/contact/class/contact.class.php");
require_once(DOL_DOCUMENT_ROOT . "/agenda/class/agenda.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/date.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/agenda/lib/agenda.lib.php");
if ($conf->projet->enabled)
    require_once(DOL_DOCUMENT_ROOT . "/core/lib/project.lib.php");

/*
if (!isset($conf->global->AGENDA_MAX_EVENTS_DAY_VIEW))
    $conf->global->AGENDA_MAX_EVENTS_DAY_VIEW = 3;

$showbirthday = empty($conf->use_javascript_ajax) ? GETPOST("showbirthday", "int") : 1;

// Security check
$socid = GETPOST("socid", "int", 1);
if ($user->societe_id)
    $socid = $user->societe_id;
$result = restrictedArea($user, 'agenda', 0, '', 'myactions');

$canedit = 1;
if (!$user->rights->agenda->myactions->read)
    accessforbidden();
if (!$user->rights->agenda->allactions->read)
    $canedit = 0;
if (!$user->rights->agenda->allactions->read || $filter == 'mine') {  // If no permission to see all, we show only affected to me
    $filtera = $user->id;
    $filtert = $user->id;
    $filterd = $user->id;
}

$action = GETPOST('action', 'alpha');
//$year=GETPOST("year");
$year = GETPOST("year", "int") ? GETPOST("year", "int") : date("Y");
$month = GETPOST("month", "int") ? GETPOST("month", "int") : date("m");
$week = GETPOST("week", "int") ? GETPOST("week", "int") : date("W");
$day = GETPOST("day", "int") ? GETPOST("day", "int") : 0;
$actioncode = GETPOST("actioncode", "alpha", 3);
$pid = GETPOST("projectid", "int", 3);
$status = GETPOST("status");
$type = GETPOST("type");
$maxprint = (isset($_GET["maxprint"]) ? GETPOST("maxprint") : $conf->global->AGENDA_MAX_EVENTS_DAY_VIEW);

if (GETPOST('viewcal')) {
    $action = 'show_month';
    $day = '';
}                                                   // View by month
if (GETPOST('viewweek')) {
    $action = 'show_week';
    $week = ($week ? $week : date("W"));
    $day = ($day ? $day : date("d"));
}  // View by week
if (GETPOST('viewday')) {
    $action = 'show_day';
    $day = ($day ? $day : date("d"));
}                                  // View by day

$langs->load("other");
$langs->load("commercial");

*/


/*
 * Actions
 */

/*
if (GETPOST("viewlist")) {
    $param = '';
    foreach ($_POST as $key => $val) {
        if ($key == 'token')
            continue;
        $param.='&' . $key . '=' . urlencode($val);
    }
    //print $param;
    header("Location: " . DOL_URL_ROOT . '/comm/action/listactions.php?' . $param);
    exit;
}

if ($action == 'delete_action') {
    $event = new Agenda($db);
    $event->fetch($actionid);
    $result = $event->delete();
}
*/

$view = GETPOST('view', 'alpha') ? GETPOST('view', 'alpha') : 'month';


/*
 * View
 */

$help_url = 'EN:Module_Agenda_En|FR:Module_Agenda|ES:M&oacute;dulo_Agenda';
llxHeader('', $langs->trans("Calendar"), $help_url);
print_fiche_titre($langs->trans("Calendar"), true);

if ($conf->use_javascript_ajax) {
    print "\n" . '<script type="text/javascript" language="javascript">';
    print 'jQuery(document).ready(function () {
                
                jQuery("#button-view-month").click(function(){
                    window.location = "'.$_SERVER['PHP_SELF'].'?view=month";
                    return false;
                });
                
                jQuery("#button-view-week").click(function(){
                    window.location = "'.$_SERVER['PHP_SELF'].'?view=week";
                    return false;
                });

            });';
    print '</script>' . "\n";
}

if ($conf->use_javascript_ajax) {
    print "\n" . '<script type="text/javascript" language="javascript">';
    print 'jQuery(document).ready(function () {
                
               var flagAllActions = true;
               var flagMyActions = false;
               
               function buttonsActionsManager(obj){
                    switch (obj.val()) {
                        case "all-actions":
                            flagAllActions = true;
                            flagMyActions = false;
                            break;
                        case "my-actions":
                            flagAllActions = false;
                            flagMyActions = true;
                            break;
                        default: alert("ok");
                    }
                    jQuery("#buttons-actions .active").removeClass("active");
                    obj.parent().addClass("active");
                    refreshDisplay();
                    return false;
               }
               
               function refreshDisplay(){
                    alert("refresh");
               }

               jQuery("#buttons-actions input[type=radio]").click(function(){
                    buttonsActionsManager(jQuery(this));
               });
               

            });';
    print '</script>' . "\n";
}

print '<p></p>';
print '<p class="button-height" >';
print '<span class="button-group margin-right">';
print '<a class="button" href="#" id="button-view-month" >' . $langs->trans('Month') . '</a>';
print '<a class="button" href="#" id="button-view-week" >' . $langs->trans('Week') . '</a>';
print '</span>';


print '<span class="button-group margin-right" id="buttons-actions">';
print '<label class="button green-active active" for="button-all-actions">';
print '<input type="radio" checked="" value="all-actions" id="button-all-actions" name="button-actions">';
print $langs->trans('AllActions');
print '</label>';
print '<label class="button green-active" for="button-my-actions">';
print '<input type="radio" checked="" value="my-actions" id="button-my-actions" name="button-actions">';
print $langs->trans('MyActions');
print '</label>';
print '</span>';

print '<span class="button-group margin-right" id="buttons-status">';
print '<label class="button green-active active" for="button-todo">';
print '<input type="radio" checked="" value="todo" id="button-todo" name="button-status">';
print $langs->trans('MenuToDoActions');
print '</label>';
print '<label class="button green-active" for="button-done">';
print '<input type="radio" checked="" value="done" id="button-done" name="button-status">';
print $langs->trans('MenuDoneActions');
print '</label>';
print '</span>';
print '</p>';

switch ($view) {
    case 'week': 
        print_week(dol_now());
        break;
    default:
        print_calendar(dol_now());
}

llxFooter();
?>