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


/*
 * View
 */



llxHeader('',$langs->trans("ContactsAddresses"),'EN:Module_Third_Parties|FR:Module_Tiers|ES:M&oacute;dulo_Empresas');
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
    print '<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>';
    print "</tr>\n";
    
  
    print'</thead>';

    print'<tbody>';
        print'<td colspan="5" class="dataTables_empty">Loading data from server</td>';
    print'</tbody>';   
    print "</table>";
print '<br>';


llxFooter('$Date: 2011/07/31 23:54:12 $ - $Revision: 1.106 $');

//import datatables js lib
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/jquery.dataTables.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/TableTools.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/ZeroClipboard.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/initXHR.js",1).'"></script>';    
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/request.js",1).'"></script>';    
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/initDatatables.js",1).'"></script>';    
?>
   