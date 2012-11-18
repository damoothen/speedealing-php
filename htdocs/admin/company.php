<?php

/* Copyright (C) 2001-2007	Rodolphe Quiedeville            <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
 * Copyright (C) 2010		Juanjo Menent			<jmenent@2byte.es>
 * Copyright (C) 2011		Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2012           Herve Prot                      <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/societe/lib/societe.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php';

$action = GETPOST('action');

$langs->load("admin");
$langs->load("companies");

if (!$user->admin)
    accessforbidden();

$message = '';


/*
 * Actions
 */

if (($action == 'update' && empty($_POST["cancel"]))
        || ($action == 'updateedit')) {
    require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

    $object = new Societe($db);

    $object->_id = "societe:mysoc";
    $object->_rev = $mysoc->_rev;
    $object->country_id = GETPOST('country_id');
    $object->name = $_POST["nom"];
    $object->address = $_POST["address"];
    $object->town = $_POST["ville"];
    $object->zip = $_POST["cp"];
    $object->state_id = $_POST["departement_id"];
    $object->currency = $_POST["currency"];
    $object->phone = $_POST["tel"];
    $object->fax = $_POST["fax"];
    $object->email = $_POST["mail"];
    $object->url = $_POST["web"];
    $object->note = $_POST["note"];
    $object->barcode = $_POST["barcode"];
    if ($_FILES["logo"]["tmp_name"]) {
        if (preg_match('/([^\\/:]+)$/i', $_FILES["logo"]["name"], $reg)) {
            $original_file = $reg[1];

            $isimage = image_format_supported($original_file);
            if ($isimage >= 0) {
                dol_syslog("Move file " . $_FILES["logo"]["tmp_name"] . " to " . $conf->mycompany->dir_output . '/logos/' . $original_file);
                if (!is_dir($conf->mycompany->dir_output . '/logos/')) {
                    dol_mkdir($conf->mycompany->dir_output . '/logos/');
                }
                $result = dol_move_uploaded_file($_FILES["logo"]["tmp_name"], $conf->mycompany->dir_output . '/logos/' . $original_file, 1, 0, $_FILES['logo']['error']);
                if ($result > 0) {
                    $object->logo = $original_file;

                    // Create thumbs of logo (Note that PDF use original file and not thumbs)
                    if ($isimage > 0) {
                        // Create small thumbs for company (Ratio is near 16/9)
                        // Used on logon for example
                        $imgThumbSmall = vignette($conf->mycompany->dir_output . '/logos/' . $original_file, $maxwidthsmall, $maxheightsmall, '_small', $quality);
                        if (preg_match('/([^\\/:]+)$/i', $imgThumbSmall, $reg)) {
                            $imgThumbSmall = $reg[1];
                            $object->logo_small = $imgThumbSmall;
                        }
                        else
                            dol_syslog($imgThumbSmall);

                        // Create mini thumbs for company (Ratio is near 16/9)
                        // Used on menu or for setup page for example
                        $imgThumbMini = vignette($conf->mycompany->dir_output . '/logos/' . $original_file, $maxwidthmini, $maxheightmini, '_mini', $quality);
                        if (preg_match('/([^\\/:]+)$/i', $imgThumbMini, $reg)) {
                            $imgThumbMini = $reg[1];
                            $object->logo_mini = $imgThumbMini;
                        }
                        else
                            dol_syslog($imgThumbMini);
                    }
                    else
                        dol_syslog($langs->trans("ErrorImageFormatNotSupported"), LOG_WARNING);
                }
                else if (preg_match('/^ErrorFileIsInfectedWithAVirus/', $result)) {
                    $langs->load("errors");
                    $tmparray = explode(':', $result);
                    $message .= '<div class="error">' . $langs->trans('ErrorFileIsInfectedWithAVirus', $tmparray[1]) . '</div>';
                } else {
                    $message .= '<div class="error">' . $langs->trans("ErrorFailedToSaveFile") . '</div>';
                }
            } else {
                $message .= '<div class="error">' . $langs->trans("ErrorOnlyPngJpgSupported") . '</div>';
            }
        }
    }

    $object->capital = $_POST["capital"];
    $object->forme_juridique_code = $_POST["forme_juridique_code"];
    $object->idprof1 = $_POST["siren"];
    $object->idprof2 = $_POST["siret"];
    $object->idprof3 = $_POST["ape"];
    $object->idprof4 = $_POST["rcs"];
    $object->idprof5 = $_POST["MAIN_INFO_PROFID5"];
    $object->idprof6 = $_POST["MAIN_INFO_PROFID6"];

    $object->tva_intra = $_POST["tva"];
    
    $object->fiscal_month_start = $_POST["fiscalmonthstart"];

    $object->tva_assuj = $_POST["optiontva"];

    // Local taxes
    $object->localtax1 = $_POST["optionlocaltax1"];
    $object->localtax2 = $_POST["optionlocaltax2"];
    
    $object->record(true);

    if ($action != 'updateedit' && !$message) {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
}

if ($action == 'addthumb') {
    if (file_exists($conf->mycompany->dir_output . '/logos/' . $_GET["file"])) {
        $isimage = image_format_supported($_GET["file"]);

        // Create thumbs of logo
        if ($isimage > 0) {
            // Create small thumbs for company (Ratio is near 16/9)
            // Used on logon for example
            $imgThumbSmall = vignette($conf->mycompany->dir_output . '/logos/' . $_GET["file"], $maxwidthsmall, $maxheightsmall, '_small', $quality);
            if (image_format_supported($imgThumbSmall) >= 0 && preg_match('/([^\\/:]+)$/i', $imgThumbSmall, $reg)) {
                $imgThumbSmall = $reg[1];
                dolibarr_set_const($db, "MAIN_INFO_SOCIETE_LOGO_SMALL", $imgThumbSmall, 'chaine', 0, '', $conf->entity);
            }
            else
                dol_syslog($imgThumbSmall);

            // Create mini thumbs for company (Ratio is near 16/9)
            // Used on menu or for setup page for example
            $imgThumbMini = vignette($conf->mycompany->dir_output . '/logos/' . $_GET["file"], $maxwidthmini, $maxheightmini, '_mini', $quality);
            if (image_format_supported($imgThumbSmall) >= 0 && preg_match('/([^\\/:]+)$/i', $imgThumbMini, $reg)) {
                $imgThumbMini = $reg[1];
                dolibarr_set_const($db, "MAIN_INFO_SOCIETE_LOGO_MINI", $imgThumbMini, 'chaine', 0, '', $conf->entity);
            }
            else
                dol_syslog($imgThumbMini);

            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }
        else {
            $message .= '<div class="error">' . $langs->trans("ErrorImageFormatNotSupported") . '</div>';
            dol_syslog($langs->transnoentities("ErrorImageFormatNotSupported"), LOG_WARNING);
        }
    } else {
        $message .= '<div class="error">' . $langs->trans("ErrorFileDoesNotExists", $_GET["file"]) . '</div>';
        dol_syslog($langs->transnoentities("ErrorFileDoesNotExists", $_GET["file"]), LOG_WARNING);
    }
}

if ($action == 'removelogo') {
    require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

    $logofile = $conf->mycompany->dir_output . '/logos/' . $mysoc->logo;
    dol_delete_file($logofile);
    dolibarr_del_const($db, "MAIN_INFO_SOCIETE_LOGO", $conf->entity);
    $mysoc->logo = '';

    $logosmallfile = $conf->mycompany->dir_output . '/logos/thumbs/' . $mysoc->logo_small;
    dol_delete_file($logosmallfile);
    dolibarr_del_const($db, "MAIN_INFO_SOCIETE_LOGO_SMALL", $conf->entity);
    $mysoc->logo_small = '';

    $logominifile = $conf->mycompany->dir_output . '/logos/thumbs/' . $mysoc->logo_mini;
    dol_delete_file($logominifile);
    dolibarr_del_const($db, "MAIN_INFO_SOCIETE_LOGO_MINI", $conf->entity);
    $mysoc->logo_mini = '';
}


/*
 * View
 */

$wikihelp = 'EN:First_setup|FR:Premiers_paramétrages|ES:Primeras_configuraciones';
llxHeader('', $langs->trans("Setup"), $wikihelp);

$form = new Form($db);
$formother = new FormOther($db);
$formcompany = new FormCompany($db);

$countrynotdefined = '<font class="error">' . $langs->trans("ErrorSetACountryFirst") . ' (' . $langs->trans("SeeAbove") . ')</font>';

$title = $langs->trans("CompanyFoundation");
print_fiche_titre($title);
print '<div class="with-padding">';
print '<div class="columns">';

print start_box($title, "twelve", $mysoc->fk_extrafields->ico, false);

print $langs->trans("CompanyFundationDesc") . "<br>\n";
print "<br>\n";

if ($action == 'edit' || $action == 'updateedit') {
    /**
     * Edition des parametres
     */
    print "\n" . '<script type="text/javascript" language="javascript">';
    print '$(document).ready(function () {
              $("#selectcountry_id").change(function() {
                document.form_index.action.value="updateedit";
                document.form_index.submit();
              });
          });';
    print '</script>' . "\n";

    print '<form enctype="multipart/form-data" method="post" action="' . $_SERVER["PHP_SELF"] . '" name="form_index">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<input type="hidden" name="action" value="update">';
    $var = true;

    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre"><td width="35%">' . $langs->trans("CompanyInfo") . '</td><td>' . $langs->trans("Value") . '</td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td class="fieldrequired">' . $langs->trans("CompanyName") . '</td><td>';
    print '<input name="nom" size="30" value="' . ($mysoc->name ? $mysoc->name : $_POST["nom"]) . '"></td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("CompanyAddress") . '</td><td>';
    print '<textarea name="address" cols="80" rows="' . ROWS_3 . '">' . ($mysoc->address ? $mysoc->address : $_POST["address"]) . '</textarea></td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("CompanyZip") . '</td><td>';
    print '<input name="cp" value="' . ($mysoc->zip ? $mysoc->zip : $_POST["cp"]) . '" size="10"></td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("CompanyTown") . '</td><td>';
    print '<input name="ville" size="30" value="' . ($mysoc->town ? $mysoc->town : $_POST["ville"]) . '"></td></tr>' . "\n";

    // Country
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td class="fieldrequired">' . $langs->trans("Country") . '</td><td>';
    //if (empty($pays_selected)) $pays_selected=substr($langs->defaultlang,-2);    // Par defaut, pays de la localisation
    print $mysoc->select_fk_extrafields('country_id', 'country_id');
    if ($user->admin)
        print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
    print '</td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("State") . '</td><td>';
    print $mysoc->select_fk_extrafields('state_id', 'departement_id');
    print '</td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("CompanyCurrency") . '</td><td>';
    if (empty($mysoc->currency))
        $mysoc->currency = $conf->currency;
    print $mysoc->select_fk_extrafields("currency", 'currency');
    print '</td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("Phone") . '</td><td>';
    print '<input name="tel" value="' . $mysoc->phone . '"></td></tr>';
    print '</td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("Fax") . '</td><td>';
    print '<input name="fax" value="' . $mysoc->fax . '"></td></tr>';
    print '</td></tr>' . "\n";

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("EMail") . '</td><td>';
    print '<input name="mail" size="60" value="' . $mysoc->email . '"></td></tr>';
    print '</td></tr>' . "\n";

    // Web
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("Web") . '</td><td>';
    print '<input name="web" size="60" value="' . $mysoc->url . '"></td></tr>';
    print '</td></tr>' . "\n";

    // Barcode
    if (!empty($conf->barcode->enabled)) {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td>' . $langs->trans("Gencod") . '</td><td>';
        print '<input name="barcode" size="40" value="' . $mysoc->barcode . '"></td></tr>';
        print '</td></tr>';
    }

    // Logo
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("Logo") . ' (png,jpg)</td><td>';
    print '<table width="100%" class="nocellnopadd"><tr class="nocellnopadd"><td valign="middle" class="nocellnopadd">';
    print '<input type="file" class="flat" name="logo" size="50">';
    print '</td><td valign="middle" align="right">';
    if (!empty($mysoc->logo_mini)) {
        print '<a href="' . $_SERVER["PHP_SELF"] . '?action=removelogo">' . img_delete($langs->trans("Delete")) . '</a>';
        if (file_exists($conf->mycompany->dir_output . '/logos/thumbs/' . $mysoc->logo_mini)) {
            print ' &nbsp; ';
            print '<img src="' . DOL_URL_ROOT . '/viewimage.php?modulepart=companylogo&amp;file=' . urlencode('/thumbs/' . $mysoc->logo_mini) . '">';
        }
    } else {
        print '<img height="30" src="' . DOL_URL_ROOT . '/theme/common/nophoto.jpg">';
    }
    print '</td></tr></table>';
    print '</td></tr>';

    // Note
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td valign="top">' . $langs->trans("Note") . '</td><td>';
    print '<textarea class="flat" name="note" cols="80" rows="' . ROWS_5 . '">' . $mysoc->note . '</textarea></td></tr>';
    print '</td></tr>';

    print '</table>';

    print '<br>';

    // Identifiants de la societe (propre au pays)
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre"><td>' . $langs->trans("CompanyIds") . '</td><td>' . $langs->trans("Value") . '</td></tr>';
    $var = true;

    $langs->load("companies");

    // Capital
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Capital") . '</td><td>';
    print '<input name="capital" size="20" value="' . $mysoc->capital . '"></td></tr>';

    // Forme juridique
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("JuridicalStatus") . '</td><td>';
    if ($mysoc->country_id) {
        print $mysoc->select_fk_extrafields("forme_juridique_code", "forme_juridique_code");
    } else {
        print $countrynotdefined;
    }
    print '</td></tr>';

    // ProfID1
    if ($langs->transcountry("ProfId1", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId1", $mysoc->country_id) . '</td><td>';
        if (!empty($mysoc->country_id)) {
            print '<input name="siren" size="20" value="' . $mysoc->idprof1 . '">';
        } else {
            print $countrynotdefined;
        }
        print '</td></tr>';
    }

    // ProfId2
    if ($langs->transcountry("ProfId2", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId2", $mysoc->country_id) . '</td><td>';
        if (!empty($mysoc->country_id)) {
            print '<input name="siret" size="20" value="' . $mysoc->idprof2 . '">';
        } else {
            print $countrynotdefined;
        }
        print '</td></tr>';
    }

    // ProfId3
    if ($langs->transcountry("ProfId3", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId3", $mysoc->country_id) . '</td><td>';
        if (!empty($mysoc->country_id)) {
            print '<input name="ape" size="20" value="' . $mysoc->idprof3 . '">';
        } else {
            print $countrynotdefined;
        }
        print '</td></tr>';
    }

    // ProfId4
    if ($langs->transcountry("ProfId4", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId4", $mysoc->country_id) . '</td><td>';
        if (!empty($mysoc->country_id)) {
            print '<input name="rcs" size="20" value="' . $mysoc->idprof4 . '">';
        } else {
            print $countrynotdefined;
        }
        print '</td></tr>';
    }

    // ProfId5
    if ($langs->transcountry("ProfId5", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId5", $mysoc->country_id) . '</td><td>';
        if (!empty($mysoc->country_id)) {
            print '<input name="MAIN_INFO_PROFID5" size="20" value="' . $mysoc->idprof5 . '">';
        } else {
            print $countrynotdefined;
        }
        print '</td></tr>';
    }

    // ProfId6
    if ($langs->transcountry("ProfId6", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId6", $mysoc->country_id) . '</td><td>';
        if (!empty($mysoc->country_id)) {
            print '<input name="MAIN_INFO_PROFID6" size="20" value="' . $mysoc->idprof6 . '">';
        } else {
            print $countrynotdefined;
        }
        print '</td></tr>';
    }

    // TVA Intra
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("VATIntra") . '</td><td>';
    print '<input name="tva" size="20" value="' . $mysoc->tva_intra . '">';
    print '</td></tr>';

    print '</table>';


    /*
     *  Debut d'annee fiscale
     */
    print '<br>';
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print '<td>' . $langs->trans("FiscalYearInformation") . '</td><td>' . $langs->trans("Value") . '</td>';
    print "</tr>\n";
    $var = true;

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("FiscalMonthStart") . '</td><td>';
    print $formother->select_month($mysoc->fiscal_month_start, 'fiscalmonthstart', 0) . '</td></tr>';

    print "</table>";


    /*
     *  Options fiscale
     */
    print '<br>';
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print '<td>' . $langs->trans("VATManagement") . '</td><td>' . $langs->trans("Description") . '</td>';
    print '<td align="right">&nbsp;</td>';
    print "</tr>\n";
    $var = true;

    $var = !$var;
    print "<tr " . $bc[$var] . "><td width=\"140\"><label><input type=\"radio\" name=\"optiontva\" value=\"1\"" . ($mysoc->tva_assuj ? " checked" : "") . "> " . $langs->trans("VATIsUsed") . "</label></td>";
    print '<td colspan="2">';
    print "<table>";
    print "<tr><td>" . $langs->trans("VATIsUsedDesc") . "</td></tr>";
    print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->trans("VATIsUsedExampleFR") . "</i></td></tr>\n";
    print "</table>";
    print "</td></tr>\n";

    $var = !$var;
    print "<tr " . $bc[$var] . "><td width=\"140\"><label><input type=\"radio\" name=\"optiontva\" value=\"0\"" . (!$mysoc->tva_assuj ? " checked" : "") . "> " . $langs->trans("VATIsNotUsed") . "</label></td>";
    print '<td colspan="2">';
    print "<table>";
    print "<tr><td>" . $langs->trans("VATIsNotUsedDesc") . "</td></tr>";
    print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->trans("VATIsNotUsedExampleFR") . "</i></td></tr>\n";
    print "</table>";
    print "</td></tr>\n";

    print "</table>";

    /*
     *  Local Taxes
     */
    if ($mysoc->country_id == 'ES') {
        // Local Tax 1
        print '<br>';
        print '<table class="noborder" width="100%">';
        print '<tr class="liste_titre">';
        print '<td>' . $langs->transcountry("LocalTax1Management", $mysoc->country_id) . '</td><td>' . $langs->trans("Description") . '</td>';
        print '<td align="right">&nbsp;</td>';
        print "</tr>\n";
        $var = true;

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input type=\"radio\" name=\"optionlocaltax1\" value=\"localtax1on\"" . ($conf->global->FACTURE_LOCAL_TAX1_OPTION != "localtax1off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax1IsUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax1IsUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->transcountry("LocalTax1IsUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input type=\"radio\" name=\"optionlocaltax1\" value=\"localtax1off\"" . ($conf->global->FACTURE_LOCAL_TAX1_OPTION == "localtax1off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax1IsNotUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax1IsNotUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->transcountry("LocalTax1IsNotUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";
        print "</table>";

        // Local Tax 2
        print '<br>';
        print '<table class="noborder" width="100%">';
        print '<tr class="liste_titre">';
        print '<td>' . $langs->transcountry("LocalTax2Management", $mysoc->country_id) . '</td><td>' . $langs->trans("Description") . '</td>';
        print '<td align="right">&nbsp;</td>';
        print "</tr>\n";
        $var = true;

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input type=\"radio\" name=\"optionlocaltax2\" value=\"localtax2on\"" . ($conf->global->FACTURE_LOCAL_TAX2_OPTION != "localtax2off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax2IsUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax2IsUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->transcountry("LocalTax2IsUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input type=\"radio\" name=\"optionlocaltax2\" value=\"localtax2off\"" . ($conf->global->FACTURE_LOCAL_TAX2_OPTION == "localtax2off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax2IsNotUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax2IsNotUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->transcountry("LocalTax2IsNotUsedExample", $mysoc->country_code) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";
        print "</table>";
    }


    print '<br><center>';
    print '<input type="submit" class="button" name="save" value="' . $langs->trans("Save") . '">';
    print ' &nbsp; &nbsp; ';
    print '<input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">';
    print '</center>';
    print '<br>';

    print '</form>';
} else {
    /*
     * Show parameters
     */

    dol_htmloutput_mesg($message);

    // Actions buttons
    //print '<div class="tabsAction">';
    //print '<a class="butAction" href="'.$_SERVER["PHP_SELF"].'?action=edit">'.$langs->trans("Modify").'</a>';
    //print '</div><br>';

    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre"><td>' . $langs->trans("CompanyInfo") . '</td><td>' . $langs->trans("Value") . '</td></tr>';
    $var = true;

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("CompanyName") . '</td><td>';
    if (!empty($mysoc->name))
        print $mysoc->name;
    else
        print img_warning() . ' <font class="error">' . $langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("CompanyName")) . '</font>';
    print '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("CompanyAddress") . '</td><td>' . nl2br($mysoc->address) . '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("CompanyZip") . '</td><td>' . $mysoc->zip . '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("CompanyTown") . '</td><td>' . $mysoc->town . '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("CompanyCountry") . '</td><td>';
    if ($mysoc->country_id) {
        $img = picto_from_langcode($mysoc->country_id);
        if ($mysoc->isInEEC())
            print $form->textwithpicto(($img ? $img . ' ' : '') . $mysoc->country_id, $langs->trans("CountryIsInEEC"), 1, 0);
        else
            print ($img ? $img . ' ' : '') . $mysoc->country_id;
    }
    else
        print img_warning() . ' <font class="error">' . $langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("CompanyCountry")) . '</font>';
    print '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("State") . '</td><td>';
    print $mysoc->print_fk_extrafields("state_id");
    print '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("CompanyCurrency") . '</td><td>';
    print $mysoc->print_fk_extrafields("currency");
    print '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Tel") . '</td><td>' . dol_print_phone($mysoc->phone, $mysoc->country_id) . '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Fax") . '</td><td>' . dol_print_phone($mysoc->fax, $mysoc->country_id) . '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Mail") . '</td><td>' . dol_print_email($mysoc->email, 0, 0, 0, 80) . '</td></tr>';

    // Web
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Web") . '</td><td>' . dol_print_url($mysoc->url, '_blank', 80) . '</td></tr>';

    // Barcode
    if (!empty($conf->barcode->enabled)) {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Gencod") . '</td><td>' . $mysoc->barcode . '</td></tr>';
    }

    // Logo
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Logo") . '</td><td>';

    print '<table width="100%" class="nocellnopadd"><tr class="nocellnopadd"><td valign="middle" class="nocellnopadd">';
    print $mysoc->logo;
    print '</td><td valign="center" align="right">';

    // On propose la generation de la vignette si elle n'existe pas
    if (!is_file($conf->mycompany->dir_output . '/logos/thumbs/' . $mysoc->logo_mini) && preg_match('/(\.jpg|\.jpeg|\.png)$/i', $mysoc->logo)) {
        print '<a href="' . $_SERVER["PHP_SELF"] . '?action=addthumb&amp;file=' . urlencode($mysoc->logo) . '">' . img_picto($langs->trans('GenerateThumb'), 'refresh') . '&nbsp;&nbsp;</a>';
    } else if ($mysoc->logo_mini && is_file($conf->mycompany->dir_output . '/logos/thumbs/' . $mysoc->logo_mini)) {
        print '<img src="' . DOL_URL_ROOT . '/viewimage.php?modulepart=companylogo&amp;file=' . urlencode('/thumbs/' . $mysoc->logo_mini) . '">';
    } else {
        print '<img height="30" src="' . DOL_URL_ROOT . '/theme/common/nophoto.jpg">';
    }
    print '</td></tr></table>';

    print '</td></tr>';

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%" valign="top">' . $langs->trans("Note") . '</td><td>' . (!empty($conf->global->MAIN_INFO_SOCIETE_NOTE) ? nl2br($conf->global->MAIN_INFO_SOCIETE_NOTE) : '') . '</td></tr>';

    print '</table>';


    print '<br>';


    // Identifiants de la societe (propre au pays)
    print '<form name="formsoc" method="post">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre"><td>' . $langs->trans("CompanyIds") . '</td><td>' . $langs->trans("Value") . '</td></tr>';
    $var = true;

    // Capital
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("Capital") . '</td><td>';
    print $mysoc->capital . '</td></tr>';

    // Forme juridique
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("JuridicalStatus") . '</td><td>';
    print $mysoc->print_fk_extrafields('forme_juridique_code');
    print '</td></tr>';

    // ProfId1
    if ($langs->transcountry("ProfId1", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId1", $mysoc->country_id) . '</td><td>';
        print $mysoc->idprof1;
        print '</td></tr>';
    }

    // ProfId2
    if ($langs->transcountry("ProfId2", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId2", $mysoc->country_id) . '</td><td>';
        print $mysoc->idprof2;
        print '</td></tr>';
    }

    // ProfId3
    if ($langs->transcountry("ProfId3", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId3", $mysoc->country_id) . '</td><td>';
        print $mysoc->idprof3;
        print '</td></tr>';
    }

    // ProfId4
    if ($langs->transcountry("ProfId4", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId4", $mysoc->country_id) . '</td><td>';
        print $mysoc->idprof4;
        print '</td></tr>';
    }

    // ProfId5
    if ($langs->transcountry("ProfId5", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId5", $mysoc->country_id) . '</td><td>';
        print $mysoc->idprof5;
        print '</td></tr>';
    }

    // ProfId6
    if ($langs->transcountry("ProfId6", $mysoc->country_id) != '-') {
        $var = !$var;
        print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->transcountry("ProfId6", $mysoc->country_id) . '</td><td>';
        print $mysoc->idprof6;
        print '</td></tr>';
    }

    // TVA
    $var = !$var;
    print '<tr ' . $bc[$var] . '><td>' . $langs->trans("VATIntra") . '</td>';
    print '<td>';
    if (!empty($mysoc->tva_intra)) {
        $s = '';
        $s.=$mysoc->tva_intra;
        $s.='<input type="hidden" name="tva_intra" size="12" maxlength="20" value="' . $mysoc->tva_intra . '">';
        if (empty($conf->global->MAIN_DISABLEVATCHECK)) {
            $s.=' &nbsp; ';
            if (!empty($conf->use_javascript_ajax)) {
                print "\n";
                print '<script language="JavaScript" type="text/javascript">';
                print "function CheckVAT(a) {\n";
                print "newpopup('" . DOL_URL_ROOT . "/societe/checkvat/checkVatPopup.php?vatNumber='+a,'" . dol_escape_js($langs->trans("VATIntraCheckableOnEUSite")) . "',500,285);\n";
                print "}\n";
                print '</script>';
                print "\n";
                $s.='<a href="#" onClick="javascript: CheckVAT(document.formsoc.tva_intra.value);">' . $langs->trans("VATIntraCheck") . '</a>';
                $s = $form->textwithpicto($s, $langs->trans("VATIntraCheckDesc", $langs->trans("VATIntraCheck")), 1);
            } else {
                $s.='<a href="' . $langs->transcountry("VATIntraCheckURL", $mysoc->country_id) . '" target="_blank">' . img_picto($langs->trans("VATIntraCheckableOnEUSite"), 'help') . '</a>';
            }
        }
        print $s;
    } else {
        print '&nbsp;';
    }
    print '</td>';
    print '</tr>';

    print '</table>';
    print '</form>';

    /*
     *  Debut d'annee fiscale
     */
    print '<br>';
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print '<td>' . $langs->trans("FiscalYearInformation") . '</td><td>' . $langs->trans("Value") . '</td>';
    print "</tr>\n";
    $var = true;

    $var = !$var;
    print '<tr ' . $bc[$var] . '><td width="35%">' . $langs->trans("FiscalMonthStart") . '</td><td>';
    $monthstart = $mysoc->fiscal_month_start;
    print dol_print_date(dol_mktime(12, 0, 0, $monthstart, 1, 2000, 1), '%B', 'gm') . '</td></tr>';

    print "</table>";

    /*
     *  Options fiscale
     */
    print '<br>';
    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print '<td>' . $langs->trans("VATManagement") . '</td><td>' . $langs->trans("Description") . '</td>';
    print '<td align="right">&nbsp;</td>';
    print "</tr>\n";
    $var = true;

    $var = !$var;
    print "<tr " . $bc[$var] . "><td width=\"140\"><label><input " . $bc[$var] . " type=\"radio\" name=\"optiontva\" disabled value=\"1\"" . ($mysoc->tva_assuj ? " checked" : "") . "> " . $langs->trans("VATIsUsed") . "</label></td>";
    print '<td colspan="2">';
    print "<table>";
    print "<tr><td>" . $langs->trans("VATIsUsedDesc") . "</td></tr>";
    print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->trans("VATIsUsedExampleFR") . "</i></td></tr>\n";
    print "</table>";
    print "</td></tr>\n";

    $var = !$var;
    print "<tr " . $bc[$var] . "><td width=\"140\"><label><input " . $bc[$var] . " type=\"radio\" name=\"optiontva\" disabled value=\"0\"" . (!$mysoc->tva_assuj ? " checked" : "") . "> " . $langs->trans("VATIsNotUsed") . "</label></td>";
    print '<td colspan="2">';
    print "<table>";
    print "<tr><td>" . $langs->trans("VATIsNotUsedDesc") . "</td></tr>";
    print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->trans("VATIsNotUsedExampleFR") . "</i></td></tr>\n";
    print "</table>";
    print "</td></tr>\n";

    print "</table>";


    /*
     *  Local Taxes
     */
    if ($mysoc->country_id == 'ES') {
        // Local Tax 1
        print '<br>';
        print '<table class="noborder" width="100%">';
        print '<tr class="liste_titre">';
        print '<td>' . $langs->transcountry("LocalTax1Management", $mysoc->country_id) . '</td><td>' . $langs->trans("Description") . '</td>';
        print '<td align="right">&nbsp;</td>';
        print "</tr>\n";
        $var = true;

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input " . $bc[$var] . " type=\"radio\" name=\"optionlocaltax1\" disabled value=\"localtax1on\"" . ($conf->global->FACTURE_LOCAL_TAX1_OPTION != "localtax1off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax1IsUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax1IsUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example", $mysoc->country_id) . ': ' . $langs->transcountry("LocalTax1IsUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input " . $bc[$var] . " type=\"radio\" name=\"optionlocaltax1\" disabled value=\"localtax1off\"" . ($conf->global->FACTURE_LOCAL_TAX1_OPTION == "localtax1off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax1IsNotUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax1IsNotUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example", $mysoc->country_id) . ': ' . $langs->transcountry("LocalTax1IsNotUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";

        print "</table>";

        // Local Tax 2
        print '<br>';
        print '<table class="noborder" width="100%">';
        print '<tr class="liste_titre">';
        print '<td>' . $langs->transcountry("LocalTax2Management", $mysoc->country_id) . '</td><td>' . $langs->trans("Description") . '</td>';
        print '<td align="right">&nbsp;</td>';
        print "</tr>\n";
        $var = true;

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input " . $bc[$var] . " type=\"radio\" name=\"optionlocaltax2\" disabled value=\"localtax2on\"" . ($conf->global->FACTURE_LOCAL_TAX2_OPTION != "localtax2off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax2IsUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax2IsUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->transcountry("LocalTax2IsUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";

        $var = !$var;
        print "<tr " . $bc[$var] . "><td width=\"140\"><label><input " . $bc[$var] . " type=\"radio\" name=\"optionlocaltax2\" disabled value=\"localtax2off\"" . ($conf->global->FACTURE_LOCAL_TAX2_OPTION == "localtax2off" ? " checked" : "") . "> " . $langs->transcountry("LocalTax2IsNotUsed", $mysoc->country_code) . "</label></td>";
        print '<td colspan="2">';
        print "<table>";
        print "<tr><td>" . $langs->transcountry("LocalTax2IsNotUsedDesc", $mysoc->country_id) . "</td></tr>";
        print "<tr><td><i>" . $langs->trans("Example") . ': ' . $langs->transcountry("LocalTax2IsNotUsedExample", $mysoc->country_id) . "</i></td></tr>\n";
        print "</table>";
        print "</td></tr>\n";

        print "</table>";
    }


    // Actions buttons
    print '<div class="tabsAction">';
    print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=edit">' . $langs->trans("Modify") . '</a>';
    print '</div>';
}

print end_box();
print '</div></div>';


llxFooter();

$db->close();
?>