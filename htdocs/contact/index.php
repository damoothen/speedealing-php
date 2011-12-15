<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2010-2011 Patrick Mary           <laube@hotmail.fr>
 
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
 *	    \file       htdocs/contact/index.php
 *      \ingroup    societe
 *		\brief      Page to list all contacts
 *		\version    $Id: index.php,v 1.106 2011/12/14 23:54:12 synry63 Exp $
 */
require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/contact/class/contact.class.php");
echo'<link rel="stylesheet" type="text/css" href="lib/datatables/css/datatable.css"/>';
echo'<link rel="stylesheet" type="text/css" href="lib/datatables/css/TableTools.css"/>';
$langs->load("companies");
$langs->load("suppliers");
$langs->load('commercial');

// Security check
$contactid = isset($_GET["id"])?$_GET["id"]:'';
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'contact', $contactid,'');

$search_nom=GETPOST("search_nom");
$search_prenom=GETPOST("search_prenom");
$search_societe=GETPOST("search_societe");
$search_poste=GETPOST("search_poste");
$search_phone=GETPOST("search_phone");
$search_phoneper=GETPOST("search_phoneper");
$search_phonepro=GETPOST("search_phonepro");
$search_phonemob=GETPOST("search_phonemob");
$search_fax=GETPOST("search_fax");
$search_email=GETPOST("search_email");
$search_priv=GETPOST("search_priv");
$search_cp=GETPOST("search_cp");


$type=GETPOST("type");
$view=GETPOST("view");

$sall=GETPOST("contactname");
$sortfield = GETPOST("sortfield");
$sortorder = GETPOST("sortorder");
$page = GETPOST("page");

if (! $sortorder) $sortorder="ASC";
if (! $sortfield) $sortfield="p.name";
if ($page < 0) { $page = 0 ; }
$limit = $conf->liste_limit;
$offset = $limit * $page ;

$langs->load("companies");
$titre=$langs->trans("ListOfContacts");
if ($type == "c")
{
	$titre=$langs->trans("ListOfContacts").'  ('.$langs->trans("ThirdPartyCustomers").')';
	$urlfiche="fiche.php";
}
if ($type == "p")
{
	$titre=$langs->trans("ListOfContacts").'  ('.$langs->trans("ThirdPartyProspects").')';
	$urlfiche="prospect/fiche.php";
}
if ($type == "f") {
	$titre=$langs->trans("ListOfContacts").' ('.$langs->trans("ThirdPartySuppliers").')';
	$urlfiche="fiche.php";
}
if ($type == "o") {
	$titre=$langs->trans("ListOfContacts").' ('.$langs->trans("OthersNotLinkedToThirdParty").')';
	$urlfiche="";
}
if ($view == 'phone')  { $text=" (Vue Telephones)"; }
if ($view == 'mail')   { $text=" (Vue EMail)"; }
if ($view == 'recent') { $text=" (Recents)"; }
$titre = $titre." $text";

if ($_POST["button_removefilter"])
{
    $search_nom="";
    $search_prenom="";
    $search_societe="";
    $search_poste="";
    $search_phone="";
    $search_phoneper="";
    $search_phonepro="";
    $search_phonemob="";
    $search_fax="";
    $search_email="";
    $search_priv="";
    $search_cp="";
    $sall="";
}
if ($search_priv < 0) $search_priv='';



/*
 * View
 */

llxHeader('',$langs->trans("ContactsAddresses"),'EN:Module_Third_Parties|FR:Module_Tiers|ES:M&oacute;dulo_Empresas');


$sql = "SELECT s.rowid as socid, s.nom,";
$sql.= " s.cp as cpost, p.rowid as cidp, p.name, p.firstname, p.poste, p.email,";
$sql.= " p.phone, p.phone_mobile, p.fax, p.fk_pays, p.priv,";
$sql.= " p.tms,";
$sql.= " cp.code as pays_code";
$sql.= " FROM ".MAIN_DB_PREFIX."socpeople as p";
$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."c_pays as cp ON cp.rowid = p.fk_pays";
$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON s.rowid = p.fk_soc";
if (!$user->rights->societe->client->voir && !$socid) $sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe_commerciaux as sc ON s.rowid = sc.fk_soc";
$sql.= " WHERE p.entity = ".$conf->entity;
if (!$user->rights->societe->client->voir && !$socid) //restriction
{
	$sql .= " AND (sc.fk_user = " .$user->id." OR p.fk_soc IS NULL)";
}
if ($_GET["userid"])    // propre au commercial
{
    $sql .= " AND p.fk_user_creat=".$_GET["userid"];
}

// Filter to exclude not owned private contacts
if ($search_priv != '0' && $search_priv != '1')
{
	$sql .= " AND (p.priv='0' OR (p.priv='1' AND p.fk_user_creat=".$user->id."))";
}
else
{
	if ($search_priv == '0') $sql .= " AND p.priv='0'";
	if ($search_priv == '1') $sql .= " AND (p.priv='1' AND p.fk_user_creat=".$user->id.")";
}

// Count total nb of records
$nbtotalofrecords = 0;
if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST))
{
    $result = $db->query($sql);
    $nbtotalofrecords = $db->num_rows($result);
}
// Add order and limit
if($view == "recent")
{
    $sql.= " ORDER BY p.datec DESC ";
	$sql.= " ".$db->plimit($conf->liste_limit+1, $offset);
}
else
{
    $sql.= " ORDER BY $sortfield $sortorder ";
	$sql.= " ".$db->plimit($conf->liste_limit+1, $offset);
}

//print $sql;
dol_syslog("contact/index.php sql=".$sql);
$result = $db->query($sql);
if ($result)
{
	$contactstatic=new Contact($db);

    $begin=$_GET["begin"];
    $param ='&begin='.urlencode($begin).'&view='.urlencode($view).'&userid='.urlencode($_GET["userid"]).'&contactname='.urlencode($sall);
    $param.='&type='.urlencode($type).'&view='.urlencode($view).'&search_nom='.urlencode($search_nom).'&search_prenom='.urlencode($search_prenom).'&search_societe='.urlencode($search_societe).'&search_email='.urlencode($search_email).'&search_cp='.urlencode($search_cp);
	if ($search_priv == '0' || $search_priv == '1') $param.="&search_priv=".urlencode($search_priv);

	$num = $db->num_rows($result);
    $i = 0;

    print_barre_liste($titre ,$page, "index.php", $param, $sortfield, $sortorder,'',$num,$nbtotalofrecords);

 
    //links for hide/show
    print '<table cellpadding="2" cellspacing="1" border="1">';
        print'<tr>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(0);">'.$langs->trans("Lastname").'</a>';
           print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(1);">'.$langs->trans("Firstname").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(2);">'.$langs->trans("PostOrFunction").'</a>';
            print'</td>';
             print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(3);">'.$langs->trans("Company").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(4);">'.$langs->trans("Phone").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(5);">'.$langs->trans("EMail").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(6);">'.$langs->trans("Zip").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(7);">'.$langs->trans("DateModificationShort").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(8);">'.$langs->trans("ContactVisibility").'</a>';
            print'</td>';
            
        print'</tr>';
        print'</table>';        
    
    print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="liste">';     
    // Ligne des titres 
    print'<thead>';
    print '<tr>';
    print'<th class="sorting">';
    print $langs->trans("Lastname");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("Firstname");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("PostOrFunction");
    print'</th>';
    if (empty($conf->global->SOCIETE_DISABLE_CONTACTS)){
        print'<th class="sorting">';
        print $langs->trans("Company");
        print'</th>';
    }
    if($view == 'phone'){
        print'<th class="sorting">';
        print $langs->trans("Phone");
        print'</th>';
        print'<th class="sorting">';
        print $langs->trans("Mobile");
        print'</th>';
        print'<th class="sorting">';
        print $langs->trans("Fax");
        print'</th>';
    }
    else{
        print'<th class="sorting">';
        print $langs->trans("Phone");
        print'</th>';
        print'<th class="sorting">';
        print $langs->trans("EMail");
        print'</th>';
    }
    print'<th class="sorting">';
    print $langs->trans("Zip");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("DateModificationShort");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("ContactVisibility");
    print'</th>';
  
    print '<th>&nbsp;</th>';
    print "</tr>\n";
    print'</thead>';

    print'<tbody>';
    while ($i < min($num,$limit))
    {
        $obj = $db->fetch_object($result);

        $var=!$var;

        print "<tr $bc[$var]>";

		// Name
		print '<td valign="middle">';
		$contactstatic->name=$obj->name;
		$contactstatic->firstname='';
		$contactstatic->id=$obj->cidp;
		print $contactstatic->getNomUrl(1,'',20);
		print '</td>';

		// Firstname
        print '<td>'.dol_trunc($obj->firstname,20).'</td>';

		// Function
        print '<td>'.dol_trunc($obj->poste,20).'</td>';

        // Company
        if (empty($conf->global->SOCIETE_DISABLE_CONTACTS))
        {
    		print '<td>';
            if ($obj->socid)
            {
                print '<a href="'.DOL_URL_ROOT.'/comm/fiche.php?socid='.$obj->socid.'">';
                print img_object($langs->trans("ShowCompany"),"company").' '.dol_trunc($obj->nom,20).'</a>';
            }
            else
            {
                print '&nbsp;';
            }
            print '</td>';
        }

        if ($view == 'phone')
        {
            // Phone
            print '<td>'.dol_print_phone($obj->phone,$obj->pays_code,$obj->cidp,$obj->socid,'AC_TEL').'</td>';
            // Phone mobile
            print '<td>'.dol_print_phone($obj->phone_mobile,$obj->pays_code,$obj->cidp,$obj->socid,'AC_TEL').'</td>';
            // Fax
            print '<td>'.dol_print_phone($obj->fax,$obj->pays_code,$obj->cidp,$obj->socid,'AC_TEL').'</td>';
        }
        else
        {
            // Phone
            print '<td>'.dol_print_phone($obj->phone,$obj->pays_code,$obj->cidp,$obj->socid,'AC_TEL').'</td>';
            // EMail
            print '<td>'.dol_print_email($obj->email,$obj->cidp,$obj->socid,'AC_EMAIL',18).'</td>';
        }

		// CP
		print '<td align="center">'.$obj->cpost.'</td>';

		// Date
		print '<td align="center">'.dol_print_date($db->jdate($obj->tms),"day").'</td>';

		// Private/Public
		print '<td align="center">'.$contactstatic->LibPubPriv($obj->priv).'</td>';

		// Links Add action and Export vcard
        print '<td align="right">';
        print '<a href="'.DOL_URL_ROOT.'/comm/action/fiche.php?action=create&amp;backtopage=1&amp;contactid='.$obj->cidp.'&amp;socid='.$obj->socid.'">'.img_object($langs->trans("AddAction"),"action").'</a>';
        print ' &nbsp; ';
        print '<a href="'.DOL_URL_ROOT.'/contact/vcard.php?id='.$obj->cidp.'">';
        print img_picto($langs->trans("VCard"),'vcard.png').' ';
        print '</a></td>';

        print "</tr>\n";
        $i++;
    }
    print'</tbody>';
    print "</table>";

    if ($num > $limit) print_barre_liste('' ,$page, "index.php", '&amp;begin='.$begin.'&amp;view='.$view.'&amp;userid='.$_GET["userid"], $sortfield, $sortorder,'',$num,$nbtotalofrecords, '');

    $db->free($result);
}
else
{
    dol_print_error($db);
}

print '<br>';
$db->close();

llxFooter('$Date: 2011/07/31 23:54:12 $ - $Revision: 1.106 $');
?>
<script type="text/javascript" src="lib/datatables/js/jquery.dataTables.js"></script>           
<script type="text/javascript" src="lib/datatables/js/TableTools.js"></script>           
<script type="text/javascript" src="lib/datatables/js/ZeroClipboard.js"></script>           

<script  type="text/javascript">
  
				/* Init DataTables */
				 
                                 $(document).ready(function() {
                                     
                                    $('#liste').dataTable( {
                                    "sDom": 'T<"clear">lfrtip',
                                    "bPaginate": false,
                                    "oTableTools": {
                                            "sSwfPath": "lib/datatables/swf/copy_cvs_xls_pdf.swf",
                                            "aButtons": [
                                                    "xls"	
                                            ]
                                    }     
                                    });
                                 });    
                              
                              // color for hide/display
                                $("a.visibility").toggle(
                                function()
                                {$(this).css("color", "gray");
                                   $(this).text();
                                   
                                },
                                function()
                                {
                                    $(this).css("color", "blue");
                                    $(this).text(origin);
                                }
                                );
                             //show/hide by column num        
                            function fnShowHide( iCol )
                            {
                                // Get the DataTables object again - this is not a recreation, just a get of the object 
                                var oTable = $('#liste').dataTable();
                                var test = oTable.fnSettings(iCol).get;
                                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                                 oTable.fnSetColumnVis( iCol, bVis ? false : true );
                                 
                            }
			
</script>
   