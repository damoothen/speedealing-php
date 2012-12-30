<?php
/* Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2010      Cyrille de Lambert   <info@auguria.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 *       \file       htdocs/societe/ajaxcompanies.php
 *       \brief      File to return Ajax response on third parties request
 */

if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL',1); // Disables token renewal
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');

require '../main.inc.php';


/*
 * View
 */

// Ajout directives pour resoudre bug IE
//header('Cache-Control: Public, must-revalidate');
//header('Pragma: public');

//top_htmlhead("", "", 1);  // Replaced with top_httphead. An ajax page does not need html header.
top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

dol_syslog(join(',',$_GET));


// Generation liste des societes
if (GETPOST('newcompany') || GETPOST('socid','int') || GETPOST('id_fourn'))
{
	$return_arr = array();

	// Define filter on text typed
	$socid = $_GET['newcompany']?$_GET['newcompany']:'';
	if (! $socid) $socid = $_GET['socid']?$_GET['socid']:'';
	if (! $socid) $socid = $_GET['id_fourn']?$_GET['id_fourn']:'';

	$sql = "SELECT rowid, nom";
	$sql.= " FROM ".MAIN_DB_PREFIX."societe as s";
	$sql.= " WHERE s.entity IN (".getEntity('societe', 1).")";
	if ($socid)
	{
        $sql.=" AND (";
        // Add criteria on name/code
        if (! empty($conf->global->SOCIETE_DONOTSEARCH_ANYWHERE))   // Can use index
        {
            $sql.="nom LIKE '" . $db->escape($socid) . "%'";
            $sql.=" OR code_client LIKE '" . $db->escape($socid) . "%'";
            $sql.=" OR code_fournisseur LIKE '" . $db->escape($socid) . "%'";
        }
        else
        {
    		$sql.="nom LIKE '%" . $db->escape($socid) . "%'";
    		$sql.=" OR code_client LIKE '%" . $db->escape($socid) . "%'";
    		$sql.=" OR code_fournisseur LIKE '%" . $db->escape($socid) . "%'";
        }
		if (! empty($conf->global->SOCIETE_ALLOW_SEARCH_ON_ROWID)) $sql.=" OR rowid = '" . $db->escape($socid) . "'";
		$sql.=")";
	}
	if (! empty($_GET["filter"])) $sql.= " AND ".$_GET["filter"]; // Add other filters
	$sql.= " ORDER BY nom ASC";

	//dol_syslog("ajaxcompanies sql=".$sql);
	$resql=$db->query($sql);
	if ($resql)
	{
		while ($row = $db->fetch_array($resql))
		{
		    $label=$row['nom'];
		    if ($socid) $label=preg_replace('/('.preg_quote($socid,'/').')/i','<strong>$1</strong>',$label,1);
			$row_array['label'] = $label;
			$row_array['value'] = $row['nom'];
	        $row_array['key'] = $row['rowid'];

	        array_push($return_arr,$row_array);
	    }

	    echo json_encode($return_arr);
	}
	else
	{
	    echo json_encode(array('nom'=>'Error','label'=>'Error','key'=>'Error','value'=>'Error'));
	}
}
else
{
    echo json_encode(array('nom'=>'ErrorBadParameter','label'=>'ErrorBadParameter','key'=>'ErrorBadParameter','value'=>'ErrorBadParameter'));
}

?>
