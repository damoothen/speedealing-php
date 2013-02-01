<?php

/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
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

// Security check
$socid = GETPOST("socid", "alpha", 1);
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

$view = GETPOST('view', 'alpha') ? GETPOST('view', 'alpha') : 'month';

$langs->load("agenda");
$langs->load("other");
$langs->load("commercial");

$object = new Agenda($db);

/*
 * View
 */

$help_url = 'EN:Module_Agenda_En|FR:Module_Agenda|ES:M&oacute;dulo_Agenda';
llxHeader('', $langs->trans("Calendar"), $help_url);

print_fiche_titre($langs->trans("Calendar"), true);
print '<div class="with-padding">';
print '<div class="columns">';

print '<div class="twelve-columns">';
$object->print_week(dol_now());
print '</div>';

print '<div class="twelve-columns">';
$object->print_calendar(dol_now());
print '</div>';

print '</div></div>';

llxFooter();
?>