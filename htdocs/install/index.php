<?php
/* Copyright (C) 2004-2005	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2012		Herve Prot				<herve.prot@symeos.com>
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
 *       \file       htdocs/install/index.php
 *       \ingroup    install
 *       \brief      Show page to select language. This is done only for a first installation.
 *					 For a reinstall this page redirect to page check.php
 */
include_once 'inc.php';
include_once '../core/class/html.form.class.php';
include_once '../core/class/html.formadmin.class.php';

$err = 0;

// Si fichier conf existe deja et rempli, on est pas sur une premiere install,
// on ne passe donc pas par la page de choix de langue
if (file_exists($conffile) && isset($dolibarr_main_url_root))
{
    header("Location: check.php?testget=ok");
    exit;
}

$langs->load("admin");


/*
 * View
 */

$formadmin=new FormAdmin('');	// Note: $db does not exist yet but we don't need it, so we put ''.

pHeader("", "check");   // Etape suivante = check

// Ask installation language
print '<br><br><center>';
print '<table>';

print '<tr>';
print '<td>'.$langs->trans("DefaultLanguage").' : </td><td align="left">';
print $formadmin->select_language('auto','selectlang',1,0,0,1);
print '</td>';
print '</tr>';

print '</table></center>';

print '<br><br>'.$langs->trans("SomeTranslationAreUncomplete");

// Si pas d'erreur, on affiche le bouton pour passer a l'etape suivante
if ($err == 0) pFooter(0);

?>
