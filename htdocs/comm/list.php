<?php
/* Copyright (C) 2001-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Philippe Grand       <philippe.grand@atoo-net.com>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2011      Patrick Mary           <laube@hotmail.fr>
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
 *	\file       htdocs/comm/list.php
 *	\ingroup    commercial societe
 *	\brief      List of customers
 *	\version    $Id: list.php,v 1.80 2012/01/12 16:15:05 synry63 Exp $
 */

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/comm/prospect/class/prospect.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formother.class.php");


$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");



// Security check
$socid = GETPOST("socid");
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'societe',$socid,'');

$type               = GETPOST("type",'int');

/*
 * Actions
 */
if ($_GET["action"] == 'cstc')
{
	$sql = "UPDATE ".MAIN_DB_PREFIX."societe SET fk_stcomm = ".$_GET["stcomm"];
	$sql .= " WHERE rowid = ".$_GET["socid"];
	$result=$db->query($sql);
}

/*active datatable js */
$arrayjs=array();
$arrayjs[0]="/lib/datatables/js/jquery.dataTables.js";
$arrayjs[1]="/lib/datatables/js/TableTools.js";
$arrayjs[2]="/lib/datatables/js/ZeroClipboard.js";
$arrayjs[3]="/lib/datatables/js/initXHR.js";
$arrayjs[4]="/lib/datatables/js/request.js";
$arrayjs[5]="/lib/datatables/js/initDatatables.js";
$arrayjs[6]="/lib/datatables/js/searchColumns.js";

/*
 * View
 */

$htmlother=new FormOther($db);


	llxHeader('',$langs->trans("ThirdParty"),$help_url,'','','',$arrayjs);
	
        if($type!='')
        {   
            if($type==0)
                $titre=$langs->trans("ListOfSuspects");
            elseif($type==1)
                $titre=$langs->trans("ListOfProspects");
            else
                $titre=$langs->trans("ListOfCustomers");
        }
        else
            $titre=$langs->trans("ListOfAll");
        
          print_barre_liste($titre, $page,'','','','','',0,0);
                
        
        

 	
   
        
    print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="liste">';    
    // Ligne des titres 
    print'<thead>';
    print '<tr>';
    print'<th class="sorting">';
    print $langs->trans("Company");
    print '&nbsp; &nbsp;';
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("Town");
    print'</th>';
    if(empty($conf->global->SOCIETE_DISABLE_STATE)){
         print'<th class="sorting">';
         print $langs->trans("State");
         print'</th>';
    }
    print'<th class="sorting">';
    print $langs->trans("Zip");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("DateCreation");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans('Categories');
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans('SalesRepresentatives');
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("ProspectLevelShort");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("StatusProsp");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("Status");
    print'&nbsp; &nbsp; &nbsp; &nbsp;';
    print'</th>';
    print'<th>';
    print '&nbsp;';
    print'</th>';
    print '</tr>';   
    print'</thead>';    
    print'<tbody>';
    print'</tbody>';
    print'<tbody>'; 
      print'<tr>';
        print'<td></td>';
        print'<td></td>';
        if(empty($conf->global->SOCIETE_DISABLE_STATE))
          print'<td></td>';
        print'<td></td>';
        print'<td></td>';
        print'<td><input id="0" style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search categories").'" name="search_cate" class="search_init" /></td>';     
        print'<td><input id="1" style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search sales").'" name="search_sales"  class="search_init" /></td>';       
      print'</tr>'; 
   print'</tbody>';
    print "</table>";

llxFooter('$Date: 2011/08/08 16:15:05 $ - $Revision: 1.80 $');

?>
 
