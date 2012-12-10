<?php
/* Copyright (C) 2003		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004-2005	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2004		Sebastien Di Cintio		<sdicintio@ressource-toi.org>
 * Copyright (C) 2004		Benoit Mortier			<benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012	Regis Houssin			<regis@dolibarr.fr>
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
 *  \file           htdocs/admin/system/dbtable.php
 *  \brief          Page d'info des contraintes d'une table
 */

require '../../main.inc.php';

$langs->load("admin");

if (! $user->admin)
	accessforbidden();

$table=GETPOST('table','alpha');


/*
 * View
 */

llxHeader();


print_fiche_titre($langs->trans("Table") . " ".$table,'','setup');

// Define request to get table description
$base=0;
if (preg_match('/mysql/i',$conf->db->type))
{
	$sql = "SHOW TABLE STATUS LIKE '".$db->escape($table)."'";
	$base=1;
}
else if ($conf->db->type == 'pgsql')
{
	$sql = "SELECT conname,contype FROM pg_constraint";
	$base=2;
}

if (! $base)
{
	print $langs->trans("FeatureNotAvailableWithThisDatabaseDriver");
}
else
{
	$resql = $db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
		$var=True;
		$i=0;
		while ($i < $num)
		{
			$row = $db->fetch_row($resql);
			$i++;
		}
	}

	if ($base == 1)
	{
		$link=array();
		$cons = explode(";", $row[14]);
		if (! empty($cons))
		{
			foreach($cons as $cc)
			{
				$cx = preg_replace("/\)\sREFER/", "", $cc);
				$cx = preg_replace("/\(`/", "", $cx);
				$cx = preg_replace("/`\)/", "", $cx);
				$cx = preg_replace("/`\s/", "", $cx);

				$val = explode("`",$cx);

				$link[trim($val[0])][0] = (isset($val[1])?$val[1]:'');
				$link[trim($val[0])][1] = (isset($val[2])?$val[2]:'');
			}
		}

		//  var_dump($link);

		print '<table>';
		print '<tr class="liste_titre"><td>'.$langs->trans("Fields").'</td><td>'.$langs->trans("Type").'</td><td>'.$langs->trans("Index").'</td>';
		print '<td>'.$langs->trans("FieldsLinked").'</td></tr>';

		$sql = "DESCRIBE ".$table;
		$resql = $db->query($sql);
		if ($resql)
		{
			$num = $db->num_rows($resql);
			$var=True;
			$i=0;
			while ($i < $num)
			{
				$row = $db->fetch_row($resql);
				$var=!$var;
				print "<tr $bc[$var]>";

				print "<td>$row[0]</td>";
				print "<td>$row[1]</td>";
				print "<td>$row[3]</td>";
				print "<td>".(isset($link[$row[0]][0])?$link[$row[0]][0]:'').".";
				print (isset($link[$row[0]][1])?$link[$row[0]][1]:'')."</td>";

				print '</tr>';
				$i++;
			}
		}
		print '</table>';
	}
}

llxFooter();
$db->close();
?>