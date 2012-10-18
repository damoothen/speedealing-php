<?php
/* Copyright (C) 2001-2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2012	   Regis Houssin
 * Copyright (C) 2012-2012 Herve Prot           <herve.prot@symeos.com>
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
 *		\ingroup    societe
 *		\brief      Page to setup extra fields of third party
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';

$langs->load("companies");
$langs->load("admin");

$extrafields = new ExtraFields($db);
$form = new Form($db);

// List of supported format
$tmptype2label=getStaticMember(get_class($extrafields),'type2label');
$type2label=array('');
foreach ($tmptype2label as $key => $val) $type2label[$key]=$langs->trans($val);

$action=GETPOST('action', 'alpha');
$attrname=GETPOST('attrname', 'alpha');
$elementtype='company';

if (!$user->admin) accessforbidden();

$acts[0] = "activate";
$acts[1] = "disable";
$actl[0] = img_picto($langs->trans("Disabled"),'switch_off');
$actl[1] = img_picto($langs->trans("Activated"),'switch_on');

/*
 * Actions
 */

require DOL_DOCUMENT_ROOT.'/core/admin_extrafields.inc.php';



/*
 * View
 */

$textobject=$langs->transnoentitiesnoconv("ThirdParty");

$help_url='EN:Module Third Parties setup|FR:Paramétrage_du_module_Tiers';
llxHeader('',$langs->trans("CompanySetup"),$help_url);


$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("CompanySetup"),$linkback,'setup');


$head = societe_admin_prepare_head(null);

dol_fiche_head($head, 'attributes', $langs->trans("ThirdParties"), 0, 'company');


print $langs->trans("DefineHereComplementaryAttributes",$textobject).'<br>'."\n";
print '<br>';

dol_htmloutput_errors($mesg);

// Load attribute_label
$extrafields->fetch_name_optionals_label($elementtype);

print "<table summary=\"listofattributes\" class=\"noborder\" width=\"100%\">";

print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Label").'</td>';
print '<td>'.$langs->trans("AttributeCode").'</td>';
print '<td>'.$langs->trans("Type").'</td>';
print '<td align="right">'.$langs->trans("Size").'</td>';
print '<td align="right">'.$langs->trans("Unique").'</td>';
print '<td width="80">&nbsp;</td>';
print "</tr>\n";

$var=True;
foreach($extrafields->attribute_type as $key => $value)
{
    $var=!$var;
    print "<tr ".$bc[$var].">";
    print "<td>".$extrafields->attribute_label[$key]."</td>\n";
    print "<td>".$key."</td>\n";
    print "<td>".$type2label[$extrafields->attribute_type[$key]]."</td>\n";
    print '<td align="right">'.$extrafields->attribute_size[$key]."</td>\n";
    print '<td align="right">'.yn($extrafields->attribute_unique[$key])."</td>\n";
    print '<td align="right"><a href="'.$_SERVER["PHP_SELF"].'?action=edit&attrname='.$key.'">'.img_edit().'</a>';
    print "&nbsp; <a href=\"".$_SERVER["PHP_SELF"]."?action=delete&attrname=$key\">".img_delete()."</a></td>\n";
    print "</tr>";
    //      $i++;
}

print "</table>";

dol_fiche_end();


// Buttons
if ($action != 'create' && $action != 'edit')
{
    print '<div class="tabsAction">';
    print "<a class=\"butAction\" href=\"".$_SERVER["PHP_SELF"]."?action=create\">".$langs->trans("NewAttribute")."</a>";
    print "</div>";
}


/* ************************************************************************** */
/*                                                                            */
/* Creation d'un champ optionnel
 /*                                                                            */
/* ************************************************************************** */

if ($action == 'create')
{
    print "<br>";
    print_titre($langs->trans('NewAttribute'));

    require DOL_DOCUMENT_ROOT.'/core/tpl/admin_extrafields_add.tpl.php';
}

/* ************************************************************************** */
/*                                                                            */
/* Edition d'un champ optionnel                                               */
/*                                                                            */
/* ************************************************************************** */
if ($action == 'edit' && ! empty($attrname))
{
    print "<br>";
    print_titre($langs->trans("FieldEdition", $attrname));

    require DOL_DOCUMENT_ROOT.'/core/tpl/admin_extrafields_edit.tpl.php';
}



foreach ($extrafields->fields as $key => $aRow)
{   
    print '<div class="row">';
    print start_box($langs->trans($key),"twelve",'16-Alert-2.png','',true);
    
    if($aRow->edit)
    {
       /*
        * Barre d'actions
        *
        */
        if ($action != 'create' && $action != 'edit')
        {
            print '<div class="row sepH_a">';
            print '<div class="right">';
                print '<a class="gh_button primary pill icon add" href='.$_SERVER["PHP_SELF"].'?action=create&fields='.$key.' >'.$langs->trans("NewAttribute").'</a>';
            print "</div>";
            print "</div>";
        }
    }
    
    print '<div class="row">';
    
    $i=0;
    $obj=new stdClass();

    print '<table cellpadding="0" cellspacing="0" border="0" class="display dt_act" id="'.$key.'" >';
    // Ligne des titres 
    print'<thead>';
    print'<tr>';
    print'<th class="center">';
    print $langs->trans("Position");
    print'</th>';
    $obj->aoColumns[$i]->bSearchable = false;
    $i++;
    print'<th class="essential">';
    print $langs->trans("Label");
    print'</th>';
    $obj->aoColumns[$i]->bSearchable = true;
    $i++;
    print'<th class="essential">';
    print $langs->trans("AttributeCode");
    print'</th>';
    $obj->aoColumns[$i]->bSearchable = false;
    $i++;
    print'<th class="essential">';
    print $langs->trans("Type");
    print'</th>';
    $obj->aoColumns[$i]->bSearchable = false;
    $i++;
    print'<th class="essential">';
    print $langs->trans("Size");
    print'</th>';
    $obj->aoColumns[$i]->bSearchable = false;
    $i++;
    print'<th class="center">';
    print $langs->trans("Action");
    print'</th>';
    $obj->aoColumns[$i]->bSearchable = false;
    $i++;
    print "</tr>";
    print "</thead>";
    print "<tbody>";

    foreach($aRow as $key1 => $value)
    {
        if(is_object($value))
        {
            print "<tr>";
            print '<td align="center">'.$value->position.'</td>';
            print "<td>".(empty($value->label)?$langs->trans($key1):$langs->trans($value->label))."</td>";
            print "<td>".$key1."</td>";
            print "<td>".$value->type."</td>";
            print '<td align="right">'.$value->length.'</td>';
            print '<td class "content_actions" align="right">';
            print '<a class="sepV_a" href="'.$_SERVER["PHP_SELF"].'?'.'&fields='.$key.'&attrname='.$key1.'&action='.$acts[$value->enable].'">'.$actl[$value->enable].'</a>';
            if($aRow->edit)
            {
                
                print '<a class="sepV_a" href="'.$_SERVER["PHP_SELF"].'?action=edit&fields='.$key.'&attrname='.$key1.'">'.img_edit().'</a>';
                print '<a class="sepV_a" href="'.$_SERVER["PHP_SELF"].'?action=delete&fields='.$key.'&attrname='.$key1.'">'.img_delete().'</a>';
            }
            print '</td>';
            print "</tr>";
        }
    }

    print "</tbody>";
    print "</table>";
    print "</div>";
    
    print $extrafields->_datatables($obj,$key);
    
    print end_box();
    print '</div>';
}


dol_fiche_end();

llxFooter();

$db->close();
?>
