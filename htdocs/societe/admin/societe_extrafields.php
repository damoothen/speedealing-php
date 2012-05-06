<?php
/* Copyright (C) 2001-2002 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
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

require("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/company.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/extrafields.class.php");

$langs->load("companies");
$langs->load("admin");

$extrafields = new ExtraFields($db);
$form = new Form($db);

// List of supported format
$type2label=array(
'varchar'=>$langs->trans('String'),
'text'=>$langs->trans('Text'),
'int'=>$langs->trans('Int'),
//'date'=>$langs->trans('Date'),
//'datetime'=>$langs->trans('DateAndTime')
);

$yesno=array($langs->trans('No'),$langs->trans('Yes'));

$action=GETPOST("action");
$elementtype='company';

if (!$user->admin) accessforbidden();

$acts[0] = "activate";
$acts[1] = "disable";
$actl[0] = img_picto($langs->trans("Disabled"),'switch_off');
$actl[1] = img_picto($langs->trans("Activated"),'switch_on');

/*
 * Actions
 */

$maxsizestring=255;
$maxsizeint=10;

if($action==$acts[0] || $action==$acts[1])
{
        if(isset($_GET["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/",$_GET["attrname"]))
	{
            try {
                    $object=$conf->couchdb->getDoc("extrafields:".$elementtype);
                    if($action == $acts[0])
                        $object->fields->$_GET["fields"]->$_GET["attrname"]->enable = true;
                    else
                        $object->fields->$_GET["fields"]->$_GET["attrname"]->enable = false;
                    $conf->couchdb->storeDoc($object);
            }
            catch (Exception $e) {
            $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
            print $error;
            exit;
            }
            Header("Location: ".$_SERVER["PHP_SELF"]);
            exit;
	}
	else
	{
	    $error++;
		$langs->load("errors");
		$mesg=$langs->trans("ErrorFieldCanNotContainSpecialCharacters",$langs->transnoentities("AttributeCode"));
	}
}


// Rename field
if ($action == 'update' || $action == 'add')
{
	if ($_POST["button"] != $langs->trans("Cancel"))
	{
        // Check values
        if (GETPOST('type')=='varchar' && GETPOST('size') > $maxsizestring)
        {
            $error++;
            $langs->load("errors");
            $mesg=$langs->trans("ErrorSizeTooLongForVarcharType",$maxsizestring);
            if($action='update')
                $action = 'edit';
            else
                $action = 'create';
        }
        if (GETPOST('type')=='int' && GETPOST('size') > $maxsizeint)
        {
            $error++;
            $langs->load("errors");
            $mesg=$langs->trans("ErrorSizeTooLongForIntType",$maxsizeint);
            if($action='update')
                $action = 'edit';
            else
                $action = 'create';
        }

	    if (! $error)
	    {
                if (isset($_POST["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/",$_POST['attrname']))
    		{
                        try {
                            $object=$conf->couchdb->getDoc("extrafields:".$elementtype);
                        }
                        catch (Exception $e) {
                            $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
                            print $error;
                            exit;
                        }
                        
                        $object->fields->$_POST['fields']->$_POST['attrname']->type=$_POST['type'];
                        $object->fields->$_POST['fields']->$_POST['attrname']->length=$_POST['size'];
                        $object->fields->$_POST['fields']->$_POST['attrname']->enable=$_POST['enable'];
                        
                        if (isset($_POST['label']))
                        {
                            $object->fields->$_POST['fields']->$_POST['attrname']->label=$_POST['label'];
                        }
                        foreach ($object->fields as $key => $value) {
                            $array = new ArrayObject($object->fields->$key);
                            $array->uasort(array("ExtraFields","compare")); //trie suivant la position
                            $value=$array;
                        }
                        
                        //print_r ($object->fields);exit;
                        
                        try {
                            $conf->couchdb->storeDoc($object);
                        }
                        catch (Exception $e) {
                            $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
                            print $error;
                            exit;
                        }
                        
    			Header("Location: ".$_SERVER["PHP_SELF"]);
    			exit;
    		}
    		else
    		{
    		    $error++;
                    $langs->load("errors");
                    $mesg=$langs->trans("ErrorFieldCanNotContainSpecialCharacters",$langs->transnoentities("AttributeCode"));
    		}
	    }
	}
}

// Delete attribute
if ($action == 'delete')
{
	if(isset($_GET["attrname"]) && preg_match("/^\w[a-zA-Z0-9-_]*$/",$_GET["attrname"]))
	{
            try {
                    $object=$conf->couchdb->getDoc("extrafields:".$elementtype);
                    unset($object->fields->$_GET["fields"]->$_GET["attrname"]);
                    $conf->couchdb->storeDoc($object);
            }
            catch (Exception $e) {
            $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
            print $error;
            exit;
            }
            Header("Location: ".$_SERVER["PHP_SELF"]);
            exit;
	}
	else
	{
	    $error++;
		$langs->load("errors");
		$mesg=$langs->trans("ErrorFieldCanNotContainSpecialCharacters",$langs->transnoentities("AttributeCode"));
	}
}



/*
 * View
 */

$textobject=$langs->transnoentitiesnoconv("ThirdParty");

$help_url='EN:Module Third Parties setup|FR:Paramétrage_du_module_Tiers';
llxHeader('',$langs->trans("CompanySetup"),$help_url);


$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("CompanySetup"),$linkback,'setup');


$head = societe_admin_prepare_head(null);

dol_fiche_head($head, 'attributes', $langs->trans("ThirdParty"), 0, 'company');


print $langs->trans("DefineHereComplementaryAttributes",$textobject).'<br>';
print '<br>';

dol_htmloutput_errors($mesg);

// Load attribute_label
try {
$extrafields->load("extrafields:".$elementtype);
}
catch (Exception $e) {
    $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
    dol_syslog("societe::load ".$error, LOG_ERR);
    print $error;
    exit;
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

    print '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print '<input type="hidden" name="fields" value="'.$_GET["fields"].'">';
    print '<table summary="listofattributes" class="border" width="100%">';

    print '<input type="hidden" name="action" value="add">';

    // Label
    print '<tr><td class="fieldrequired" required>'.$langs->trans("Label").'</td><td class="valeur"><input type="text" name="label" size="40" value="'.GETPOST('label').'"></td></tr>';
    // Code
    print '<tr><td class="fieldrequired" required>'.$langs->trans("AttributeCode").' ('.$langs->trans("AlphaNumOnlyCharsAndNoSpace").')</td><td class="valeur"><input type="text" name="attrname" size="10" value="'.GETPOST('attrname').'"></td></tr>';
    // Type
    print '<tr><td class="fieldrequired" required>'.$langs->trans("Type").'</td><td class="valeur">';
    print $form->selectarray('type',$type2label,GETPOST('type'));
    print '</td></tr>';
    // Size
    print '<tr><td class="fieldrequired" required>'.$langs->trans("Size").'</td><td><input type="text" name="size" size="5" value="'.(GETPOST('size')?GETPOST('size'):'255').'"></td></tr>';

    print "</table>\n";

    print '<center><br><input type="submit" name="button" class="button small radius nice" value="'.$langs->trans("Save").'"> &nbsp; ';
    print '<input type="submit" name="button" class="button white small radius nice" value="'.$langs->trans("Cancel").'"></center>';

    print "</form>\n";
}

/* ************************************************************************** */
/*                                                                            */
/* Edition d'un champ optionnel                                               */
/*                                                                            */
/* ************************************************************************** */
if ($_GET["attrname"] && $action == 'edit')
{
    print "<br>";
    print_titre($langs->trans("FieldEdition",$_GET["attrname"]));

    /*
     * formulaire d'edition
     */
    print '<form method="post" action="'.$_SERVER["PHP_SELF"].'?fields='.$_GET["fields"].'&attrname='.$_GET["attrname"].'">';
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print '<input type="hidden" name="attrname" value="'.$_GET["attrname"].'">';
    print '<input type="hidden" name="fields" value="'.$_GET["fields"].'">';
    print '<input type="hidden" name="action" value="update">';
    print '<table summary="listofattributes" class="border" width="100%">';

    // Label
    print '<tr>';
    print '<td class="fieldrequired" required>'.$langs->trans("Label").'</td><td class="valeur"><input type="text" name="label" size="40" value="'.$extrafields->fields->$_GET["fields"]->$_GET["attrname"]->label.'"></td>';
    print '</tr>';
    // Code
    print '<tr>';
    print '<td class="fieldrequired" required>'.$langs->trans("AttributeCode").'</td>';
    print '<td class="valeur">'.$_GET["attrname"].'&nbsp;</td>';
    print '</tr>';
    // Type
    $type=$extrafields->fields->$_GET["fields"]->$_GET["attrname"]->type;
    print '<tr><td class="fieldrequired" required>'.$langs->trans("Type").'</td>';
    print '<td class="valeur">';
    print $type2label[$type];
    print '<input type="hidden" name="type" value="'.$type.'">';
    print '</td></tr>';
    // Size
    $size=$extrafields->fields->$_GET["fields"]->$_GET["attrname"]->length;
    print '<tr><td class="fieldrequired" required>'.$langs->trans("Size").'</td><td class="valeur"><input type="text" name="size" size="5" value="'.$size.'"></td></tr>';
    // Enable
    $enable=$extrafields->fields->$_GET["fields"]->$_GET["attrname"]->enable;
    print '<tr><td class="fieldrequired" required>'.$langs->trans("Enable").'</td><td class="valeur">';
    print $form->selectarray('enable',$yesno,$enable);
    print '</td></tr>';

    print '</table>';

    print '<center><br><input type="submit" name="button" class="button small radius nice" value="'.$langs->trans("Save").'"> &nbsp; ';
    print '<input type="submit" name="button" class="button white small radius nice" value="'.$langs->trans("Cancel").'"></center>';

    print "</form>";

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
