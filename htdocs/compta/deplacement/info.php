<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2005 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * 	\file       htdocs/compta/deplacement/info.php
 * 	\ingroup    trip
 * 	\brief      Page to show a trip information
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/trip.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/deplacement/class/deplacement.class.php';

$langs->load("trips");

// Security check
$id = GETPOST('id','int');
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'deplacement', $id, '');


/*
 * View
 */

llxHeader();

if ($id)
{
	$object = new Deplacement($db);
	$object->fetch($id);
	$object->info($id);
	
	$head = trip_prepare_head($object);
	
	dol_fiche_head($head, 'info', $langs->trans("TripCard"), 0, 'trip');

    print '<table width="100%"><tr><td>';
    dol_print_object_info($object);
    print '</td></tr></table>';
      
    print '</div>';
}

$db->close();

llxFooter();
?>
