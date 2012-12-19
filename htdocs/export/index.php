<?php

/* Copyright (C) 2005-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
 *       \file       htdocs/exports/index.php
 *       \ingroup    export
 *       \brief      Home page of export wizard
 */
require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/export/class/export.class.php';

$langs->load("exports");

if (!$user->rights->export->lire)
    accessforbidden();

$export = new Export($db);
$export->load_arrays($user);


/*
 * View
 */

$form = new Form($db);

llxHeader('', $langs->trans("ExportsArea"), 'EN:Module_Exports_En|FR:Module_Exports|ES:M&oacute;dulo_Exportaciones');

print_fiche_titre($langs->trans("ExportsArea"));
print '<div class="with-padding">';

print $langs->trans("FormatedExportDesc1") . '<br>';
print $langs->trans("FormatedExportDesc2") . ' ';
print $langs->trans("FormatedExportDesc3") . '<br>';
print '<br>';

print '<div class="columns">';

print start_box($langs->trans("Module"), "seven", "16-User.png", false);

// List export set
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>' . $langs->trans("Module") . '</td>';
print '<td>' . $langs->trans("ExportableDatas") . '</td>';
//print '<td>&nbsp;</td>';
print '</tr>';
$var = true;
if (count($export->array_export_code)) {
    foreach ($export->array_export_code as $key => $value) {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td>';
        //print img_object($export->array_export_module[$key]->getName(),$export->array_export_module[$key]->picto).' ';
        print $export->array_export_module[$key]->getName();
        print '</td><td>';
        print img_object($export->array_export_module[$key]->getName(), $export->array_export_icon[$key]) . ' ';
        $string = $langs->trans($export->array_export_label[$key]);
        print ($string != $export->array_export_label[$key] ? $string : $export->array_export_label[$key]);
        print '</td>';
        //        print '<td width="24">';
        //        print '<a href="'.DOL_URL_ROOT.'/exports/export.php?step=2&amp;datatoexport='.$export->array_export_code[$key].'&amp;action=cleanselect">'.img_picto($langs->trans("NewExport"),'filenew').'</a>';
        //        print '</td>';
        print '</tr>';
    }
} else {
    print '<tr><td ' . $bc[false] . ' colspan="2">' . $langs->trans("NoExportableData") . '</td></tr>';
}
print '</table>';
print '<br>';

print '<center>';
if (count($export->array_export_code)) {
    if ($user->rights->export->creer) {
        print '<a class="butAction" href="' . DOL_URL_ROOT . '/export/export.php?leftmenu=export">' . $langs->trans("NewExport") . '</a>';
    } else {
        print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->transnoentitiesnoconv("NotEnoughPermissions")) . '">' . $langs->trans("NewExport") . '</a>';
    }
    /*
      print '<center><form action="'.DOL_URL_ROOT.'/exports/export.php?leftmenu=export"><input type="submit" class="button" value="'.$langs->trans("NewExport").'"';
      print ($user->rights->export->creer?'':' disabled="disabled"');
      print '></form></center>';
     */
}
print '</center>';

print end_box();

print start_box($langs->trans("AvailableFormats"), "five", "16-User.png", false);


// List of available export format
$var = true;
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td colspan="2">' . $langs->trans("AvailableFormats") . '</td>';
print '<td>' . $langs->trans("LibraryShort") . '</td>';
print '<td align="right">' . $langs->trans("LibraryVersion") . '</td>';
print '</tr>';

include_once DOL_DOCUMENT_ROOT . '/export/core/modules/export/modules_export.php';
$model = new ModeleExports();
$liste = $model->liste_modeles($db);    // This is not a static method for exports because method load non static properties

$var = true;
foreach ($liste as $key => $val) {
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

$db->close();
?>
