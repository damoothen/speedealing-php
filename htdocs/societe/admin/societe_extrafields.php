<?php
/* Copyright (C) 2001-2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2012	   Regis Houssin
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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

/**
 *      \file       htdocs/societe/admin/societe_extrafields.php
 * 		\ingroup    societe
 * 		\brief      Page to setup extra fields of third party
 */
require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/societe/lib/societe.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';

$langs->load("companies");
$langs->load("admin");

$object = new Societe($db);
$form = new Form($db);

// List of supported format
$tmptype2label = $object->fk_extrafields->type2label;
foreach ($tmptype2label as $key => $val)
    $type2label[$key] = $langs->trans($val);

$action = GETPOST('action', 'alpha');
$attrname = GETPOST('attrname', 'alpha');

if (!$user->admin)
    accessforbidden();

$acts[0] = "enable";
$acts[1] = "disable";
$actl[0] = img_picto($langs->trans("Disabled"), 'switch_off');
$actl[1] = img_picto($langs->trans("Activated"), 'switch_on');

/*
 * Actions
 */

$maxsizestring = 255;
$maxsizeint = 10;

$extrasize = GETPOST('size');
if (GETPOST('type') == 'double' && strpos($extrasize, ',') === false)
    $extrasize = '24,8';
if (GETPOST('type') == 'date')
    $extrasize = '';
if (GETPOST('type') == 'datetime')
    $extrasize = '';


// Add attribute
if ($action == 'add') {
    if ($_POST["button"] != $langs->trans("Cancel")) {
        // Check values
        if (!GETPOST('type')) {
            $error++;
            $langs->load("errors");
            $mesg = $langs->trans("ErrorFieldRequired", $langs->trans("Type"));
            $action = 'create';
        }

        if (GETPOST('type') == 'varchar' && $extrasize > $maxsizestring) {
            $error++;
            $langs->load("errors");
            $mesg = $langs->trans("ErrorSizeTooLongForVarcharType", $maxsizestring);
            $action = 'create';
        }
        if (GETPOST('type') == 'int' && $extrasize > $maxsizeint) {
            $error++;
            $langs->load("errors");
            $mesg = $langs->trans("ErrorSizeTooLongForIntType", $maxsizeint);
            $action = 'create';
        }

        if (!$error) {
            // Type et taille non encore pris en compte => varchar(255)
            if (isset($_POST["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $_POST['attrname'])) {
                $result = $object->fk_extrafields->addExtraField($_POST['attrname'], $_POST['label'], $_POST['type'], $extrasize);
                if ($result > 0) {
                    header("Location: " . $_SERVER["PHP_SELF"]);
                    exit;
                } else {
                    $error++;
                    $mesg = $object->fk_extrafields->error;
                }
            } else {
                $error++;
                $langs->load("errors");
                $mesg = $langs->trans("ErrorFieldCanNotContainSpecialCharacters", $langs->transnoentities("AttributeCode"));
                $action = 'create';
            }
        }
    }
}

// Rename field
if ($action == 'update') {
    if ($_POST["button"] != $langs->trans("Cancel")) {
        // Check values
        if (!GETPOST('type')) {
            $error++;
            $langs->load("errors");
            $mesg = $langs->trans("ErrorFieldRequired", $langs->trans("Type"));
            $action = 'create';
        }
        if (GETPOST('type') == 'varchar' && $extrasize > $maxsizestring) {
            $error++;
            $langs->load("errors");
            $mesg = $langs->trans("ErrorSizeTooLongForVarcharType", $maxsizestring);
            $action = 'edit';
        }
        if (GETPOST('type') == 'int' && $extrasize > $maxsizeint) {
            $error++;
            $langs->load("errors");
            $mesg = $langs->trans("ErrorSizeTooLongForIntType", $maxsizeint);
            $action = 'edit';
        }

        if (!$error) {
            if (isset($_POST["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $_POST['attrname'])) {
                $result = $object->fk_extrafields->update($_POST['attrname'], $_POST['label'], $_POST['type'], $extrasize);
                if ($result > 0) {
                    header("Location: " . $_SERVER["PHP_SELF"]);
                    exit;
                } else {
                    $error++;
                    $mesg = $object->fk_extrafields->error;
                }
            } else {
                $error++;
                $langs->load("errors");
                $mesg = $langs->trans("ErrorFieldCanNotContainSpecialCharacters", $langs->transnoentities("AttributeCode"));
            }
        }
    }
}

// Delete attribute
if ($action == 'delete') {
    if (isset($_GET["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $_GET["attrname"])) {
        $result = $object->fk_extrafields->delete($_GET["attrname"]);
        if ($result >= 0) {
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }
        else
            $mesg = $object->fk_extrafields->error;
    }
    else {
        $error++;
        $langs->load("errors");
        $mesg = $langs->trans("ErrorFieldCanNotContainSpecialCharacters", $langs->transnoentities("AttributeCode"));
    }
}

// enable or disable
if ($action == 'enable') {
    if (isset($_GET["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $_GET["attrname"])) {
        $result = $object->fk_extrafields->setStatus($_GET["attrname"], true);
        if ($result > 0) {
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }
        else
            $mesg = $object->fk_extrafields->error;
    }
}

if ($action == 'disable') {
    if (isset($_GET["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $_GET["attrname"])) {
        $result = $object->fk_extrafields->setStatus($_GET["attrname"], false);
        if ($result > 0) {
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit;
        }
        else
            $mesg = $object->fk_extrafields->error;
    }
}

/*
 * View
 */

$textobject = $langs->transnoentitiesnoconv("ThirdParty");

$help_url = 'EN:Module Third Parties setup|FR:Paramétrage_du_module_Tiers';
llxHeader('', $langs->trans("CompanySetup"), $help_url);


$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';

print_fiche_titre($langs->trans("CompanySetup"), $linkback, 'setup');
print '<div class="with-padding">';
print '<div class="columns">';

print start_box($langs->trans($langs->trans("CompanySetup")), "twelve", '16-Alert-2.png', false);

$head = societe_admin_prepare_head(null);

dol_fiche_head($head, 'attributes', $langs->trans("ThirdParties"), 0, 'company');


print $langs->trans("DefineHereComplementaryAttributes", $textobject) . '<br>' . "\n";
print '<br>';

dol_htmloutput_errors($mesg);

/* * ************************************************************************* */
/*                                                                            */
/* Edition d'un champ optionnel                                               */
/*                                                                            */
/* * ************************************************************************* */
if ($action == 'create' || $action == 'edit' && !empty($attrname)) {
    print "<br>";
    if ($action == 'create')
        print_titre($langs->trans('NewAttribute'));
    else
        print_titre($langs->trans("FieldEdition", $attrname));
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            function init_typeoffields(type)
            {
                var size = jQuery("#size");
                if (type == 'date') { size.val('').attr('disabled','disabled'); }
                else if (type == 'datetime') { size.val('').attr('disabled','disabled'); }
                else if (type == 'double') { size.val('24,8').removeAttr('disabled'); }
                else if (type == 'int') { size.val('10').removeAttr('disabled'); }
                else if (type == 'text') { size.val('2000').removeAttr('disabled'); }
                else if (type == 'varchar') { size.val('255').removeAttr('disabled'); }
                else size.val('').attr('disabled','disabled');
            }
            init_typeoffields('');
            jQuery("#type").change(function() {
                init_typeoffields($(this).val());
            });
        });
    </script>

    <?php if ($action == 'create') : ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
            <input type="hidden" name="action" value="add">

            <table summary="listofattributes" class="border centpercent">
                <!-- Label -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("Label"); ?></td><td class="valeur"><input type="text" name="label" size="40" value="<?php echo GETPOST('label'); ?>"></td></tr>
                <!-- Code -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("AttributeCode"); ?> (<?php echo $langs->trans("AlphaNumOnlyCharsAndNoSpace"); ?>)</td><td class="valeur"><input type="text" name="attrname" size="10" value="<?php echo GETPOST('attrname'); ?>"></td></tr>
                <!-- Type -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("Type"); ?></td><td class="valeur">
                        <?php print $form->selectarray('type', $type2label, GETPOST('type')); ?>
                    </td></tr>
                <!-- Size -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("Size"); ?></td><td class="valeur"><input id="size" type="text" name="size" size="5" value="<?php echo (GETPOST('size') ? GETPOST('size') : ''); ?>"></td></tr>
            </table>

            <div align="center"><br><input type="submit" name="button" class="button" value="<?php echo $langs->trans("Save"); ?>"> &nbsp;
                <input type="submit" name="button" class="button" value="<?php echo $langs->trans("Cancel"); ?>"></div>

        </form>
    <?php else : ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?attrname=<?php echo $attrname; ?>" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
            <input type="hidden" name="attrname" value="<?php echo $attrname; ?>">
            <input type="hidden" name="action" value="update">

            <table summary="listofattributes" class="border centpercent">
                <!-- Label -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("Label"); ?></td><td class="valeur"><input type="text" name="label" size="40" value="<?php echo $object->fk_extrafields->fields->$attrname->label; ?>"></td></tr>
                <!-- Code -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("AttributeCode"); ?></td><td class="valeur"><?php echo $attrname; ?></td></tr>
                <!-- Type -->
                <?php
                $type = $object->fk_extrafields->fields->$attrname->type;
                $size = $object->fk_extrafields->fields->$attrname->size;
                ?>
                <tr><td class="fieldrequired"><?php echo $langs->trans("Type"); ?></td><td class="valeur">
                        <?php print $form->selectarray('type', $type2label, $type); ?>
                    </td></tr>
                <!-- Size -->
                <tr><td class="fieldrequired"><?php echo $langs->trans("Size"); ?></td><td><input id="size" type="text" name="size" size="5" value="<?php echo $size; ?>"></td></tr>
            </table>

            <div align="center"><br><input type="submit" name="button" class="button" value="<?php echo $langs->trans("Save"); ?>"> &nbsp;
                <input type="submit" name="button" class="button" value="<?php echo $langs->trans("Cancel"); ?>"></div>

        </form>
    <?php endif; ?>
    <?php
}

dol_fiche_end();

/*
 * Barre d'actions
 *
 */
if ($action != 'create' && $action != 'edit') {
    print '<p class="button-height right">';
    print '<span class="button-group">';
    print '<a class="button compact" href=' . $_SERVER["PHP_SELF"] . '?action=create&fields=' . $key . ' ><span class="button-icon blue-gradient glossy"><span class="icon-star"></span></span>' . $langs->trans("NewAttribute") . '</a>';
    print "</span>";
    print "</p>";
}

$i = 0;
$obj = new stdClass();
//print '<div class="datatable">';
print '<table class="display dt_act" id="list_fields" >';
// Ligne des titres 
print'<thead>';
print'<tr>';
print'<th>';
print'</th>';
$obj->aoColumns[$i]->mDataProp = "_id";
$obj->aoColumns[$i]->bUseRendered = false;
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->bVisible = false;
$i++;
print'<th class="essential">';
print $langs->trans("Position");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "order";
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("Label");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "label";
$obj->aoColumns[$i]->bSearchable = true;
//$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("AttributeCode");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "key";
$obj->aoColumns[$i]->bSearchable = false;
//$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
print'<th class="essential">';
print $langs->trans("Type");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "type";
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->sDefaultContent = "";
$i++;
/* print'<th class="essential">';
  print $langs->trans("Size");
  print'</th>';
  $obj->aoColumns[$i]->mDataProp = "size";
  $obj->aoColumns[$i]->bSearchable = false;
  $obj->aoColumns[$i]->sDefaultContent = "";
  $i++; */
print'<th class="essential">';
print $langs->trans("Action");
print'</th>';
$obj->aoColumns[$i]->mDataProp = "action";
$obj->aoColumns[$i]->sClass = "center content_actions";
$obj->aoColumns[$i]->bSearchable = false;
$obj->aoColumns[$i]->sWidth = "100px";
print "</tr>";
print "</thead>";
print "<tbody>";

foreach ($object->fk_extrafields->fields as $key => $aRow) {
    if (is_object($aRow) && $aRow->edit) {
        print "<tr>";
        print '<td>' . $key . '</td>';
        print '<td>' . $aRow->pos . '</td>';
        print "<td>" . (empty($aRow->label) ? $langs->trans($key) : $langs->trans($aRow->label)) . "</td>";
        print "<td>" . $key . "</td>";
        print "<td>" . $aRow->type . "</td>";
        // print '<td>' . $aRow->length . '</td>';
        print '<td>';
        print '<a class="sepV_a" href="' . $_SERVER["PHP_SELF"] . '?' . 'attrname=' . $key . '&action=' . $acts[$aRow->enable] . '">' . $actl[$aRow->enable] . '</a>';
        if ($aRow->edit && $aRow->optional) {
            print '<a class="sepV_a" href="' . $_SERVER["PHP_SELF"] . '?action=edit&attrname=' . $key . '">' . img_edit() . '</a>';
            print '<a class="sepV_a confirm" href="' . $_SERVER["PHP_SELF"] . '?action=delete&attrname=' . $key . '">' . img_delete() . '</a>';
        }
        print '</td>';
        print "</tr>";
    }
}

print "</tbody>";
print "</table>";
//print '</div>';

$obj->iDisplayLength = 100;
print $object->datatablesCreate($obj, "list_fields");

print end_box();
print '</div>';
print '</div>';

llxFooter();

$db->close();
?>
