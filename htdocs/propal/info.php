<?php
/* Copyright (C) 2004		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2006	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
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
 *      \file       htdocs/comm/propal/info.php
 *      \ingroup    propale
 *      \brief      Page d'affichage des infos d'une proposition commerciale
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/comm/propal/class/propal.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/propal.lib.php';

$langs->load('propal');
$langs->load('compta');

$id=GETPOST('id','int');
$socid=GETPOST('socid','int');

// Security check
if (! empty($user->societe_id)) $socid=$user->societe_id;
$result = restrictedArea($user, 'propal', $id);


/*
 *	View
 */

llxHeader('',$langs->trans('Proposal'),'EN:Commercial_Proposals|FR:Proposition_commerciale|ES:Presupuestos');

$object = new Propal($db);
$object->fetch($id);
$object->fetch_thirdparty();

$head = propal_prepare_head($object);
dol_fiche_head($head, 'info', $langs->trans('Proposal'), 0, 'propal');

$object->info($object->id);

print '<table width="100%"><tr><td>';
dol_print_object_info($object);
print '</td></tr></table>';

print '</div>';


llxFooter();
$db->close();
?>
