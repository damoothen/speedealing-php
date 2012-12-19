<?php
/* Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *     \file       htdocs/compta/bank/info.php
 *     \ingroup    banque
 *     \brief      Onglet info d'une ecriture bancaire
 */

require 'pre.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';

$langs->load("banks");
$langs->load("companies");


/*
 * View
 */

llxHeader();

$line = new AccountLine($db);
$line->fetch($_GET["rowid"]);
$line->info($_GET["rowid"]);


$h=0;

$head[$h][0] = DOL_URL_ROOT.'/compta/bank/ligne.php?rowid='.$_GET["rowid"];
$head[$h][1] = $langs->trans("Card");
$h++;

$head[$h][0] = DOL_URL_ROOT.'/compta/bank/info.php?rowid='.$_GET["rowid"];
$head[$h][1] = $langs->trans("Info");
$hselected = $h;
$h++;


dol_fiche_head($head, $hselected, $langs->trans("LineRecord"),0,'account');

print '<table width="100%"><tr><td>';
dol_print_object_info($line);
print '</td></tr></table>';

print '</div>';


$db->close();

llxFooter();
?>
