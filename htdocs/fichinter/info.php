<?php
/* Copyright (C) 2005-2009  Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2009-2010  Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011       Juanjo Menent        <jmenent@2byte.es>
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
 *	\file       htdocs/fichinter/info.php
 *	\ingroup    fichinter
 *	\brief      Page d'affichage des infos d'une fiche d'intervention
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/fichinter/class/fichinter.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fichinter.lib.php';

$langs->load('companies');
$langs->load("interventions");

$fichinterid = GETPOST('id','int');

// Security check
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'ficheinter', $fichinterid, 'fichinter');


/*
*	View
*/

llxHeader();

$fichinter = new Fichinter($db);
$fichinter->fetch($fichinterid);

$societe = new Societe($db);
$societe->fetch($fichinter->socid);

$head = fichinter_prepare_head($fichinter);
dol_fiche_head($head, 'info', $langs->trans('InterventionCard'), 0, 'intervention');

$fichinter->info($fichinter->id);

print '<table width="100%"><tr><td>';
dol_print_object_info($fichinter);
print '</td></tr></table>';

print '</div>';

$db->close();

llxFooter();
?>
