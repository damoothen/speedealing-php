<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Eric Seigne          <erics@rycks.com>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010-2011 Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2011-2012 Patrick Mary           <laube@hotmail.fr>
 
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
$langs->load("companies");
$langs->load("suppliers");
$langs->load('commercial');
// Security check
$contactid = isset($_GET["id"])?$_GET["id"]:'';
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'contact', $contactid,'');

$type=GETPOST("type");
$view=GETPOST("view");
$sall=GETPOST("contactname");
if ($type == "c")
{
	$titre=$langs->trans("ListOfContacts").'  ('.$langs->trans("ThirdPartyCustomers").')';
	$urlfiche="fiche.php";
}
else if ($type == "p")
{
	$titre=$langs->trans("ListOfContacts").'  ('.$langs->trans("ThirdPartyProspects").')';
	$urlfiche="prospect/fiche.php";
}
else if ($type == "f") {
	$titre=$langs->trans("ListOfContacts").' ('.$langs->trans("ThirdPartySuppliers").')';
	$urlfiche="fiche.php";
}
else if ($type == "o") {
	$titre=$langs->trans("ListOfContacts").' ('.$langs->trans("OthersNotLinkedToThirdParty").')';
	$urlfiche="";
}
else{
    $titre=$langs->trans("ListOfContacts");
}
if ($view == 'phone')  { $text=" (Vue Telephones)"; }
if ($view == 'mail')   { $text=" (Vue EMail)"; }
if ($view == 'recent') { $text=" (Recents)"; }
$titre = $titre." $text";

/*import datatable js */
$arrayjs=array();
$arrayjs[0]="/lib/datatables/js/jquery.dataTables.js";
$arrayjs[1]="/lib/datatables/js/TableTools.js";
$arrayjs[2]="/lib/datatables/js/ZeroClipboard.js";
$arrayjs[3]="/lib/datatables/js/initXHR.js";
$arrayjs[4]="/lib/datatables/js/request.js";
$arrayjs[5]="/lib/datatables/js/initDatatables.js";
$arrayjs[6]="/lib/datatables/js/searchColumns.js";
llxHeader('',$langs->trans("ContactsAddresses"),'EN:Module_Third_Parties|FR:Module_Tiers|ES:M&oacute;dulo_Empresas','','','',$arrayjs);
print_barre_liste($titre ,'','','', '','','','','');

 /*hide/show */   
    print'<table class ="hideshow">';
    print'<tbody>';
    print'<tr>';
    print'<td>';
    // print'<a  href="javascript:void(0);" onclick="fnShowHide(0);">'.$langs->trans("Detail").'&nbsp;</a>';    
    print '<p>'.$langs->trans("Visibility").' : '.'</p>';
    print'</td>';
    print'<td>';
    print'<a  href="javascript:void(0);" onclick="fnShowHide(1);">'.$langs->trans("Lastname").'&nbsp;</a>';    
    print'</td>';
    print'<td>';
    print'<a  href="javascript:void(0);" onclick="fnShowHide(2);">'.$langs->trans("Firstname").'&nbsp;</a>';    
    print'</td>';
    print'<td>';
    print'<a  href="javascript:void(0);" onclick="fnShowHide(3);">'.$langs->trans("PostOrFunction").'&nbsp;</a>';    
    print'</td>';
    
    if(empty($conf->global->SOCIETE_DISABLE_CONTACTS))
    {
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(4);">'.$langs->trans("Company").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(5);">'.$langs->trans("Phone").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(6);">'.$langs->trans("EMail").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(7);">'.$langs->trans("Zip").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(8);">'.$langs->trans("Categories").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(9);">'.$langs->trans("DateModificationShort").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(10);">'.$langs->trans("ContactVisibility").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(11);">'.$langs->trans("Action").'&nbsp;</a>';	
        print'</td>';
    }
    else {
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(4);">'.$langs->trans("Phone").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(5);">'.$langs->trans("EMail").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(6);">'.$langs->trans("Zip").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(7);">'.$langs->trans("Categories").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(8);">'.$langs->trans("DateModificationShort").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(9);">'.$langs->trans("ContactVisibility").'&nbsp;</a>';    
        print'</td>';
        print'<td>';
        print'<a  href="javascript:void(0);" onclick="fnShowHide(10);">'.$langs->trans("Action").'&nbsp;</a>';    
        print'</td>';
       
    }
    print'</tr>';
    print'</tbody>';
    print'</table>';
   
 
 /*
 * View
 */
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
    print $langs->trans("Categories");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("DateModificationShort");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("ContactVisibility");
    print'</th>';
    print '<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
    print "</tr>\n";
    
  
    print'</thead>';

    print'<tbody>';
    print'</tbody>';
    
    print'<tbody>'; 
      print'<tr>';
        print'<td id="1"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Lastname").'" class="inputSearch" /></td>';
        print'<td id="2"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Firstname").'"class="inputSearch"/></td>';
        print'<td id="3"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search PostOrFunction").'"class="inputSearch"/></td>';
        if(empty($conf->global->SOCIETE_DISABLE_CONTACTS))
            print'<td id="4"><input  style="margin-top:1px;" type="text" placeholder="'.$langs->trans("Search Company").'" class="inputSearch"/></td>';
        print'<td id="5"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Phone").'" class="inputSearch"/></td>';
        print'<td id="6"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search EMail").'" class="inputSearch"/></td>';     
        print'<td id="7"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Zip").'" class="inputSearch"/></td>';       
        print'<td id="8"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search categories").'" class="inputSearch"/></td>';       
        print'<td id="9"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search DateModificationShort").'" class="inputSearch"/></td>';       
        
      print'</tr>'; 
   print'</tbody>';
    
    print "</table>";
print '<br>';


llxFooter('$Date: 2011/07/31 23:54:12 $ - $Revision: 1.106 $');

?>
   