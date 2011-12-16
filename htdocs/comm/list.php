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
 *	\version    $Id: list.php,v 1.80 2011/08/08 16:15:05 eldy Exp $
 */

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/comm/prospect/class/prospect.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formother.class.php");
echo'<link rel="stylesheet" type="text/css" href="'.dol_buildpath("/lib/datatables/css/datatable.css",1).'"/>';
echo'<link rel="stylesheet" type="text/css" href="'.dol_buildpath("/lib/datatables/css/TableTools.css",1).'"/>';


$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");

// Security check
$socid = GETPOST("socid");
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'societe',$socid,'');

$pstcomm            = GETPOST("pstcomm");
$type               = GETPOST("type",'int');
$search_nom         = GETPOST("search_nom");

$sortfield = GETPOST("sortfield",'alpha');
$sortorder = GETPOST("sortorder",'alpha');
$page      = GETPOST("page",'int');
if ($page == -1) { $page = 0; }
$offset = $conf->liste_limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (! $sortorder) $sortorder="ASC";
if (! $sortfield) $sortfield="s.nom";

$search_level_from = GETPOST("search_level_from","alpha");
$search_level_to   = GETPOST("search_level_to","alpha");

$search_cp=trim($_REQUEST['search_cp']);

// If both parameters are set, search for everything BETWEEN them
if ($search_level_from != '' && $search_level_to != '')
{
	// Ensure that these parameters are numbers
	$search_level_from = (int) $search_level_from;
	$search_level_to = (int) $search_level_to;

	// If from is greater than to, reverse orders
	if ($search_level_from > $search_level_to)
	{
		$tmp = $search_level_to;
		$search_level_to = $search_level_from;
		$search_level_from = $tmp;
	}

	// Generate the SQL request
	$sortwhere = '(sortorder BETWEEN '.$search_level_from.' AND '.$search_level_to.') AS is_in_range';
}
// If only "from" parameter is set, search for everything GREATER THAN it
else if ($search_level_from != '')
{
	// Ensure that this parameter is a number
	$search_level_from = (int) $search_level_from;

	// Generate the SQL request
	$sortwhere = '(sortorder >= '.$search_level_from.') AS is_in_range';
}
// If only "to" parameter is set, search for everything LOWER THAN it
else if ($search_level_to != '')
{
	// Ensure that this parameter is a number
	$search_level_to = (int) $search_level_to;

	// Generate the SQL request
	$sortwhere = '(sortorder <= '.$search_level_to.') AS is_in_range';
}
// If no parameters are set, dont search for anything
else
{
	$sortwhere = '0 as is_in_range';
}

// Select every potentiels, and note each potentiels which fit in search parameters
dol_syslog('list::prospects_prospect_level',LOG_DEBUG);
$sql = "SELECT code, label, sortorder, ".$sortwhere;
$sql.= " FROM ".MAIN_DB_PREFIX."c_prospectlevel";
$sql.= " WHERE active > 0";
$sql.= " ORDER BY sortorder";

$resql = $db->query($sql);
if ($resql)
{
	$tab_level = array();
	$search_levels = array();

	while ($obj = $db->fetch_object($resql))
	{
		// Compute level text
		$level=$langs->trans($obj->code);
		if ($level == $obj->code) $level=$langs->trans($obj->label);

		// Put it in the array sorted by sortorder
		$tab_level[$obj->sortorder] = $level;

		// If this potentiel fit in parameters, add its code to the $search_levels array
		if ($obj->is_in_range == 1)
		{
			$search_levels[] = '"'.preg_replace('[^A-Za-z0-9_-]', '', $obj->code).'"';
		}

		$i++;
	}

	// Implode the $search_levels array so that it can be use in a "IN (...)" where clause.
	// If no paramters was set, $search_levels will be empty
	$search_levels = implode(',', $search_levels);
}
else dol_print_error($db);

// Load sale and categ filters
$search_sale = isset($_GET["search_sale"])?$_GET["search_sale"]:$_POST["search_sale"];
$search_categ = isset($_GET["search_categ"])?$_GET["search_categ"]:$_POST["search_categ"];
// If the user must only see his prospect, force searching by him
if (!$user->rights->societe->client->voir && !$socid) $search_sale = $user->id;

/*
 * Actions
 */
if ($_GET["action"] == 'cstc')
{
	$sql = "UPDATE ".MAIN_DB_PREFIX."societe SET fk_stcomm = ".$_GET["stcomm"];
	$sql .= " WHERE rowid = ".$_GET["socid"];
	$result=$db->query($sql);
}


/*
 * View
 */

$htmlother=new FormOther($db);

$sql = "SELECT s.rowid, s.nom, s.ville, s.datec, s.datea, s.status as status,";
$sql.= " st.libelle as stcomm, s.prefix_comm, s.fk_stcomm, s.fk_prospectlevel,st.type,";
$sql.= " d.nom as departement, s.cp as cp";
// Updated by Matelli 
// We'll need these fields in order to filter by sale (including the case where the user can only see his prospects)
if ($search_sale) $sql .= ", sc.fk_soc, sc.fk_user";
// We'll need these fields in order to filter by categ
if ($search_categ) $sql .= ", cs.fk_categorie, cs.fk_societe";
$sql .= " FROM (".MAIN_DB_PREFIX."societe as s";
// We'll need this table joined to the select in order to filter by sale
if ($search_sale || !$user->rights->societe->client->voir) $sql.= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
// We'll need this table joined to the select in order to filter by categ
if ($search_categ) $sql.= ", ".MAIN_DB_PREFIX."categorie_societe as cs";
$sql.= " ) ";
$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."c_departements as d on (d.rowid = s.fk_departement)";
$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."c_stcomm as st ON st.id = s.fk_stcomm";
$sql.= " WHERE s.client in (1,2,3)";
if($type!='')
    $sql.= " AND st.type=".$type;
$sql.= " AND s.entity = ".$conf->entity;
if ($user->societe_id) $sql.= " AND s.rowid = " .$user->societe_id;
if ($search_sale) $sql.= " AND s.rowid = sc.fk_soc";		// Join for the needed table to filter by sale
if ($search_categ) $sql.= " AND s.rowid = cs.fk_societe";	// Join for the needed table to filter by categ
if (isset($pstcomm) && $pstcomm != '') $sql.= " AND s.fk_stcomm=".$pstcomm;

if ($search_nom)   $sql .= " AND s.nom like '%".$db->escape(strtolower($search_nom))."%'";
// Insert levels filters
if ($search_levels)
{
	$sql .= " AND s.fk_prospectlevel IN (".$search_levels.')';
}
// Insert sale filter
if ($search_sale)
{
	$sql .= " AND sc.fk_user = ".$db->escape($search_sale);
}
// Insert categ filter
if ($search_categ)
{
	$sql .= " AND cs.fk_categorie = ".$db->escape($search_categ);
}


// Count total nb of records
$nbtotalofrecords = 0;
if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST))
{
	$result = $db->query($sql);
	$nbtotalofrecords = $db->num_rows($result);
}

$sql.= " ORDER BY $sortfield $sortorder, s.nom ASC";
$sql.= $db->plimit($conf->liste_limit+1, $offset);

$resql = $db->query($sql);
if ($resql)
{
	$num = $db->num_rows($resql);

	if ($num == 1 && $socname)
	{
		$obj = $db->fetch_object($resql);
		Header("Location: list.php?socid=".$obj->rowid);
		exit;
	}
	else
	{
        $help_url='EN:Module_Third_Parties|FR:Module_Tiers|ES:Empresas';
        llxHeader('',$langs->trans("ThirdParty"),$help_url);
	}

	$param='&amp;search_nom='.urlencode($search_nom).'&amp;search_ville='.urlencode($search_ville);
 	// Added by Matelli 
 	// Store the status filter in the URL
 	if (isset($search_cstc))
 	{
 		foreach ($search_cstc as $key => $value)
 		{
 			if ($value == 'true')
 				$param.='&amp;search_cstc['.((int) $key).']=true';
 			else
 				$param.='&amp;search_cstc['.((int) $key).']=false';
 		}
 	}
 	if ($search_level_from != '') $param.='&amp;search_level_from='.$search_level_from;
 	if ($search_level_to != '') $param.='&amp;search_level_to='.$search_level_to;
 	if ($search_categ != '') $param.='&amp;search_categ='.$search_categ;
 	if ($search_sale != '') $param.='&amp;search_sale='.$search_sale;
        if ($search_cp != '') $param.='&amp;search_cp='.$search_cp;
        if ($pstcomm != '') $param.='&amp;pstcomm='.$pstcomm;
        if ($type != '') $param.='&amp;type='.$type;
 	// $param and $urladd should have the same value
 	$urladd = $param;

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
        
            
                
        
	print_barre_liste($titre, $page, $_SERVER["PHP_SELF"],$param,$sortfield,$sortorder,'',$num,$nbtotalofrecords);
        

 	// Print the search-by-sale and search-by-categ filters
 	print '<form method="get" action="list.php" id="formulaire_recherche">';
        
        print '<table cellpadding="2" cellspacing="1" border="0">';
	// Filter on categories
 	$moreforfilter='';
	if ($conf->categorie->enabled)
	{
	 	$moreforfilter.=$langs->trans('Categories'). ': ';
		$moreforfilter.=$htmlother->select_categories(2,$search_categ,'search_categ');
	 	$moreforfilter.=' &nbsp; &nbsp; &nbsp; ';
	}
 	// If the user can view prospects other than his'
 	if ($user->rights->societe->client->voir || $socid)
 	{
	 	$moreforfilter.=$langs->trans('SalesRepresentatives'). ': ';
		$moreforfilter.=$htmlother->select_salesrepresentatives($search_sale,'search_sale',$user);
 	}
 	if ($moreforfilter)
	{
		print '<tr class="liste_titre">';
		print '<td class="liste_titre" colspan="9">';
	    print $moreforfilter;
	    print '</td></tr>';
	}
        print'</table>';
        
          //links for hide/show
    print '<table cellpadding="2" cellspacing="1" border="1">';
        print'<tr>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(0);">'.$langs->trans("Company").'</a>';
           print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(1);">'.$langs->trans("Town").'</a>';
            print'</td>';
            if(empty($conf->global->SOCIETE_DISABLE_STATE)){
                print'<td>';
                     print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(2);">'.$langs->trans("State").'</a>';
                print'</td>';
            }
             print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(3);">'.$langs->trans("Zip").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(4);">'.$langs->trans("DateCreation").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(5);">'.$langs->trans("ProspectLevelShort").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(6);">'.$langs->trans("StatusProsp").'</a>';
            print'</td>';
            print'<td>';
                 print'<a class="visibility" href="javascript:void(0);" onclick="fnShowHide(7);">'.$langs->trans("Status").'</a>';
            print'</td>';
            
        print'</tr>';
        print'</table>';        
  
        
    print '<table cellpadding="0" cellspacing="0" border="0" class="display" id="liste">';    
    // Ligne des titres 
    print'<thead>';
    print '<tr>';
    print'<th class="sorting">';
    print $langs->trans("Company");
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
    print $langs->trans("ProspectLevelShort");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("StatusProsp");
    print'</th>';
    print'<th class="sorting">';
    print $langs->trans("Status");
    print'</th>';
    print'<th class="sorting">';
    print '&nbsp;';
    print'</th>';

    /*
	print '<tr class="liste_titre">';
	print '<td class="liste_titre">';
	print '<input type="text" class="flat" name="search_nom" size="10" value="'.$search_nom.'">';
	print '</td><td class="liste_titre">';
	print '<input type="text" class="flat" name="search_ville" size="10" value="'.$search_ville.'">';
	print '</td>';
        if (empty($conf->global->SOCIETE_DISABLE_STATE))
        {
            print '<td class="liste_titre" align="center">';
            print '<input type="text" class="flat" name="search_departement" size="10" value="'.$search_departement.'">';
            print '</td>';
        }
	print '<td class="liste_titre">';
	print '<input type="text" class="flat" name="search_cp" size="8" value="'.$_GET["search_cp"].'">';
    print '</td>';
    print '<td align="center" class="liste_titre">';
	print '<input class="flat" type="text" size="10" name="search_datec" value="'.$search_datec.'">';
    print '</td>';

 	// Added by Matelli 
 	print '<td class="liste_titre" align="center">';
 	// Generate in $options_from the list of each option sorted
 	$options_from = '<option value="">&nbsp;</option>';
 	foreach ($tab_level as $tab_level_sortorder => $tab_level_label)
 	{
 		$options_from .= '<option value="'.$tab_level_sortorder.'"'.($search_level_from == $tab_level_sortorder ? ' selected="selected"':'').'>';
 		$options_from .= $langs->trans($tab_level_label);
 		$options_from .= '</option>';
 	}

 	// Reverse the list
 	array_reverse($tab_level, true);

 	// Generate in $options_to the list of each option sorted in the reversed order
 	$options_to = '<option value="">&nbsp;</option>';
 	foreach ($tab_level as $tab_level_sortorder => $tab_level_label)
 	{
 		$options_to .= '<option value="'.$tab_level_sortorder.'"'.($search_level_to == $tab_level_sortorder ? ' selected="selected"':'').'>';
 		$options_to .= $langs->trans($tab_level_label);
 		$options_to .= '</option>';
 	}
        
 	// Print these two select
 	print $langs->trans("From").' <select class="flat" name="search_level_from">'.$options_from.'</select>';
 	print ' ';
 	print $langs->trans("To").' <select class="flat" name="search_level_to">'.$options_to.'</select>';
 print '</td>';

   
    print '<td class="liste_titre" align="center">';
    print '<input type="hidden" class="flat" name="type" size="10" value="'.$type.'">';
    print $htmlother->select_stcomm($type,$pstcomm,'pstcomm');
    print '</td>';
    
    print '<td class="liste_titre" align="center">';
    print '&nbsp;';
    print '</td>';

    // Print the search button
    print '<td class="liste_titre" align="right">';
    print '<input class="liste_titre" name="button_search" type="image" src="'.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/search.png" value="'.dol_escape_htmltag($langs->trans("Search")).'" title="'.dol_escape_htmltag($langs->trans("Search")).'">';
    print '</td>';

    print "</tr>\n";
*/
	$i = 0;
	$var=true;

	$prospectstatic=new Prospect($db);
        print'<tbody>';
	while ($i < min($num,$conf->liste_limit))
	{
		$obj = $db->fetch_object($resql);

		$var=!$var;

		print '<tr>';
		print '<td>';
		$prospectstatic->id=$obj->rowid;
		$prospectstatic->nom=$obj->nom;
                $prospectstatic->status=$obj->status;
                if($obj->type==2)
                    print $prospectstatic->getNomUrl(1,'customer');
                else
                    print $prospectstatic->getNomUrl(1,'prospect');
                print '</td>';
		print "<td>".$obj->ville."&nbsp;</td>";
                if (empty($conf->global->SOCIETE_DISABLE_STATE))
                    print "<td align=\"center\">$obj->departement</td>";
		print "<td align=\"left\">$obj->cp</td>";
		// Creation date
		print "<td align=\"center\">".dol_print_date($db->jdate($obj->datec))."</td>";
		// Level
		print "<td align=\"center\">";
		print $prospectstatic->LibLevel($obj->fk_prospectlevel);
		print "</td>";
		// Statut
		print '<td align="left" nowrap="nowrap">';
		print $prospectstatic->LibProspStatut($obj->fk_stcomm,2);
		print "</td>";

		// icone action
		print '<td align="center" nowrap>';
                $prospectstatic->stcomm_id=$obj->fk_stcomm;
		$prospectstatic->type=$obj->type;
                print $prospectstatic->getIconList(DOL_URL_ROOT."/comm/list.php?socid=".$obj->rowid.'&amp;action=cstc&amp;'.$param.($page?'&amp;page='.$page:''));
		print '</td>';

                print '<td align="right">';
		print $prospectstatic->getLibStatut(3);
                print '</td>';

        print "</tr>\n";
		$i++;
	}
        

	if ($num > $conf->liste_limit || $page > 0) print_barre_liste('', $page, $_SERVER["PHP_SELF"],$param,$sortfield,$sortorder,'',$num,$nbtotalofrecords);
        print'</tbody>';
	print "</table>";

	print "</form>";

	$db->free($resql);
}
else
{
	dol_print_error($db);
}

$db->close();

llxFooter('$Date: 2011/08/08 16:15:05 $ - $Revision: 1.80 $');
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/jquery.dataTables.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/TableTools.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/ZeroClipboard.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/initDatatables.js",1).'"></script>';    

?>
