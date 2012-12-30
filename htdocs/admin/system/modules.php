<?php

/* Copyright (C) 2005-2009	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2007		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2010-2012	Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2012           Herve Prot              <herve.prot@symeos.com>
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
 *  \file       htdocs/admin/system/modules.php
 *  \brief      File to list all Dolibarr modules
 */
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';

$langs->load("admin");
$langs->load("install");
$langs->load("other");

if (!$user->admin)
    accessforbidden();


/*
 * View
 */

llxHeader();

print_fiche_titre($langs->trans("AvailableModules"), '', 'setup');

print $langs->trans("ToActivateModule") . '<br>';
print "<br>\n";

$modules = array();
$modules_names = array();
$modules_files = array();
$modulesdir = dolGetModulesDirs();

// Load list of modules
foreach ($modulesdir as $dir) {
    $handle = @opendir(dol_osencode($dir));
    if (is_resource($handle)) {
        while (($file = readdir($handle)) !== false) {
            if (is_readable($dir . $file) && substr($file, 0, 3) == 'mod' && substr($file, dol_strlen($file) - 10) == '.class.php') {
                $modName = substr($file, 0, dol_strlen($file) - 10);

                if ($modName) {
                    include_once $dir . $file;
                    $objMod = new $modName($db);

                    $modules[$objMod->numero] = $objMod;
                    $modules_names[$objMod->numero] = $objMod->name;
                    $modules_files[$objMod->numero] = $file;
                    $picto[$objMod->numero] = (isset($objMod->picto) && $objMod->picto) ? $objMod->picto : 'generic';
                }
            }
        }
        closedir($handle);
    }
}
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>' . $langs->trans("Modules") . '</td>';
print '<td>' . $langs->trans("Version") . '</td>';
print '<td align="center">' . $langs->trans("Id Module") . '</td>';
print '<td>' . $langs->trans("Id Permissions") . '</td>';
print '</tr>';
$var = false;
$sortorder = $modules_names;
ksort($sortorder);
$rights_ids = array();
foreach ($sortorder as $numero => $name) {
    $idperms = "";
    $var = !$var;
    // Module
    print "<tr $bc[$var]><td width=\"300\" nowrap=\"nowrap\">";
    $alt = $name . ' - ' . $modules_files[$numero];
    if (!empty($picto[$numero])) {
        if (preg_match('/^\//', $picto[$numero]))
            print img_picto($alt, $picto[$numero], 'width="14px"', 1);
        else
            print img_object($alt, $picto[$numero], 'width="14px"');
    }
    else {
        print img_object($alt, $picto[$numero], 'width="14px"');
    }
    print ' ' . $modules[$numero]->getName();
    print "</td>";
    // Version
    print '<td>' . $modules[$numero]->getVersion() . '</td>';
    // Id
    print '<td align="center">' . $numero . '</td>';
    // Permissions
    if ($modules[$numero]->rights) {
        foreach ($modules[$numero]->rights as $rights) {
            $idperms.=($idperms ? ", " : "") . $rights->id;
            array_push($rights_ids, $rights->id);
        }
    }
    print '<td>' . ($idperms ? $idperms : "&nbsp;") . '</td>';
    print "</tr>\n";
}
print '</table>';
print '<br>';
sort($rights_ids);
$old = '';
foreach ($rights_ids as $right_id) {
    if ($old == $right_id)
        print "Warning duplicate id on permission : " . $right_id . "<br>";
    $old = $right_id;
}

print dol_fiche_end();

llxFooter();
$db->close();
?>
