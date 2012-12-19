<?php
/* Copyright (C) 2007-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2009      Regis Houssin        <regis@dolibarr.fr>
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
 *       \file       htdocs/admin/translation.php
 *       \brief      Page to show translation information
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("companies");
$langs->load("products");
$langs->load("admin");
$langs->load("sms");
$langs->load("other");
$langs->load("errors");

if (!$user->admin) accessforbidden();


$action=GETPOST('action');


/*
 * Actions
 */

// None



/*
 * View
 */

$wikihelp='EN:Setup|FR:Paramétrage|ES:Configuración';
llxHeader('',$langs->trans("Setup"),$wikihelp);

print_fiche_titre($langs->trans("TranslationSetup"),'','setup');

print $langs->trans("TranslationDesc")."<br>\n";
print "<br>\n";

dol_htmloutput_mesg($message);

print $langs->trans("CurrentUserLanguage").': <strong>'.$langs->defaultlang.'</strong><br>';
print img_warning().' '.$langs->trans("SomeTranslationAreUncomplete").'<br>';

$urlwikitranslatordoc='http://wiki.dolibarr.org/index.php/Translator_documentation';
print $langs->trans("SeeAlso").': <a href="'.$urlwikitranslatordoc.'" target="_blank">'.$urlwikitranslatordoc.'</a><br>';


llxFooter();

$db->close();
?>
