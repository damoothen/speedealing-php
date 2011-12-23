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
        $param ='&lang='.urlencode($_GET['lang']); // add lang to url when pagination
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
        
        print '<table cellpadding="0" cellspacing="0" border="0">';
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
    print'<th>';
    print '&nbsp;';
    print'</th>';
    print '</tr>';   
    print'</thead>';
    
    	$i = 0;
	$var=true;

	$prospectstatic=new Prospect($db);
        print'<tbody>';
	while ($i < min($num,$conf->liste_limit))
	{
		$obj = $db->fetch_object($resql);
                
		$var=!$var;

		print '<tr id="'.$obj->rowid.'">';
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
                print $prospectstatic->getIconList(DOL_URL_ROOT."/comm/list.php?socid=".$obj->rowid.$param.'&action=cstc&amp;'.($page?'&amp;page='.$page:''));
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
//import datatables js lib
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/jquery.dataTables.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/TableTools.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/ZeroClipboard.js",1).'"></script>';           
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/initXHR.js",1).'"></script>';    
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/request.js",1).'"></script>';    
print'<script type="text/javascript" src="'.dol_buildpath("/lib/datatables/js/initDatatables.js",1).'"></script>';    

?>
