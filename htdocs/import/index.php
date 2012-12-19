<?php

/* Copyright (C) 2005-2012  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2012       Herve Prot              <herve.prot@symeos.com>
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

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/import/class/import.class.php';

$langs->load("exports");

if (!$user->societe_id == 0)
    accessforbidden();

$import = new Import($db);
$import->load_arrays($user);


/*
 * View
 */

$form = new Form($db);

llxHeader('', $langs->trans("ImportArea"), 'EN:Module_Imports_En|FR:Module_Imports|ES:M&oacute;dulo_Importaciones');

print_fiche_titre($langs->trans("ImportArea"));
print '<div class="with-padding">';
print $langs->trans("FormatedImportDesc1") . '<br>';
print $langs->trans("FormatedImportDesc2") . '<br>';
print '<br>';
print '<div class="columns">';

print start_box($langs->trans("Module"), "seven", "16-User.png", false);

// List of import set
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>' . $langs->trans("Module") . '</td>';
print '<td>' . $langs->trans("ImportableDatas") . '</td>';
//print '<td>&nbsp;</td>';
print '</tr>';
$val = true;
if (count($import->array_import_code)) {
    foreach ($import->array_import_code as $key => $value) {
        $val = !$val;
        print '<tr ' . $bc[$val] . '><td>';
        print img_object($import->array_import_module[$key]->getName(), $import->array_import_module[$key]->picto) . ' ';
        print $import->array_import_module[$key]->getName();
        print '</td><td>';
        $string = $langs->trans($import->array_import_label[$key]);
        print ($string != $import->array_import_label[$key] ? $string : $import->array_import_label[$key]);
        print '</td>';
        //        print '<td width="24">';
        //        print '<a href="'.DOL_URL_ROOT.'/imports/import.php?step=2&amp;datatoimport='.$import->array_import_code[$key].'&amp;action=cleanselect">'.img_picto($langs->trans("NewImport"),'filenew').'</a>';
        //        print '</td>';
        print '</tr>';
    }
} else {
    print '<tr><td ' . $bc[false] . ' colspan="2">' . $langs->trans("NoImportableData") . '</td></tr>';
}
print '</table>';
print '<br>';

print '<center>';
if (count($import->array_import_code)) {
    //if ($user->rights->import->run)
    //{
    print '<a class="butAction" href="' . DOL_URL_ROOT . '/import/import.php">' . $langs->trans("NewImport") . '</a>';
    //}
    //else
    //{
    //	print '<a class="butActionRefused" href="#" title="'.dol_escape_htmltag($langs->trans("NotEnoughPermissions")).'">'.$langs->trans("NewImport").'</a>';
    //}
}
print '</center>';

print end_box();

print start_box($langs->trans("AvailableFormats"), "five", "16-User.png", false);

// List of available import format
$var = true;
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td colspan="2">' . $langs->trans("AvailableFormats") . '</td>';
print '<td>' . $langs->trans("LibraryShort") . '</td>';
print '<td align="right">' . $langs->trans("LibraryVersion") . '</td>';
print '</tr>';

include_once DOL_DOCUMENT_ROOT . '/import/core/modules/import/modules_import.php';
$model = new ModeleImports();
$liste = $model->liste_modeles($db);

foreach ($liste as $key) {
    $var = !$var;
    print '<tr ' . $bc[$var] . '>';
    print '<td width="16">' . img_picto_common($model->getDriverLabel($key), $model->getPicto($key)) . '</td>';
    $text = $model->getDriverDesc($key);
    print '<td>' . $form->textwithpicto($model->getDriverLabel($key), $text) . '</td>';
    print '<td>' . $model->getLibLabel($key) . '</td>';
    print '<td nowrap="nowrap" align="right">' . $model->getLibVersion($key) . '</td>';
    print '</tr>';
}

print '</table>';

print end_box();
print '</div></div>';

llxFooter();
?>
