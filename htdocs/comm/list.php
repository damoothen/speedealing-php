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
                
        
        
   /*hide/show */   
    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(0);">'.$langs->trans("Detail").'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(1);">'.$langs->trans("Company").'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(2);">'.$langs->trans("Town").'&nbsp;</a>';    
    if(empty($conf->global->SOCIETE_DISABLE_STATE))
        print'<a href="javascript:void(0);" onclick="fnShowHide(3);">'.$langs->trans("State").'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(4);">'.$langs->trans("Zip").'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(5);">'.$langs->trans("DateCreation").'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(6);">'.$langs->trans('Categories').'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(7);">'.$langs->trans('SalesRepresentatives').'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(8);">'.$langs->trans('Siren').'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(9);">'.$langs->trans('Siret').'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(10);">'.$langs->trans('Ape').'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(11);">'.$langs->trans('idprof4').'&nbsp;</a>';    
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(12);">'.$langs->trans("ProspectLevelShort").'&nbsp;</a>';	
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(13),test(13);">'.$langs->trans("StatusProsp").'&nbsp;</a>';	
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(14);">'.$langs->trans("Status").'&nbsp;</a>';	
    print'<a class="hideshow" href="javascript:void(0);" onclick="fnShowHide(15);">'.$langs->trans("Actif").'&nbsp;</a>';	

    
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
    print $langs->trans('Siren');
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans('Siret');
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans('Ape');
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans('idprof4');
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
    print'<tbody class="contenu">'; 
    print'</tbody>';
    print'<tbody class="recherche">'; 
      print'<tr>';
        print'<td id="1"><input style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Company").'" class="inputSearch"/></td>';
        print'<td id="2"><input style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Town").'" class="inputSearch" /></td>';
        if(empty($conf->global->SOCIETE_DISABLE_STATE))
          print'<td id="3"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search State").'"class="inputSearch"/></td>';
        print'<td id="4"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search Zip").'"class="inputSearch" /></td>';
        print'<td id="5"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search DateCreation").'" class="inputSearch" /></td>';
        print'<td id="6"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search categories").'" class="inputSearch" /></td>';     
        print'<td id="7"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search sales").'" class="inputSearch" /></td>';       
        print'<td id="8"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search siren").'" class="inputSearch" /></td>';       
        print'<td id="9"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search siret").'" class="inputSearch" /></td>';       
        print'<td id="10"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search ape").'" class="inputSearch" /></td>';       
        print'<td id="11"><input  style="margin-top:1px;"  type="text" placeholder="'.$langs->trans("Search idprof4").'" class="inputSearch" /></td>';       
   
        print'<td id="12"><select class="level">
            <option  value="">&nbsp;</option>
            <option  value="PL_LOW">'.$langs->trans("Low").'</option>
            <option  value="PL_MEDIUM">'.$langs->trans("Medium").'</option>
            <option  value="PL_HIGH">'.$langs->trans("High").'</option>
              </select></td>';
        
        print'<td id="13"><select class="level">
            <option  value="">&nbsp;</option>
            <option  value="-1">'.$langs->trans("Don't contact").'</option>
            <option  value="0">'.$langs->trans("Never contact").'</option>    
            <option  value="4">'.$langs->trans("Cold prospect").'</option>
            <option  value="6">'.$langs->trans("Cold prospect").'</option>
            <option  value="7">'.$langs->trans("1 order").'</option>
            <option  value="8">'.$langs->trans("+2 order").'</option>
            <option  value="9">'.$langs->trans("Customer regular").'</option>    
            </select></td>';     
        
      print'</tr>'; 
   print'</tbody>';
    print "</table>";

llxFooter('$Date: 2011/08/08 16:15:05 $ - $Revision: 1.80 $');

?>
 
