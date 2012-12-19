<?php
/* Copyright (C) 2001-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2006-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  	\file 		htdocs/adherents/htpasswd.php
 *      \ingroup    member
 *      \brief      Page d'export htpasswd du fichier des adherents
 *      \author     Rodolphe Quiedeville
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/security2.lib.php';

// Security check
if (! $user->rights->adherent->export) accessforbidden();


/*
 * View
 */

llxHeader();

$now=dol_now();

if ($sortorder == "") {  $sortorder="ASC"; }
if ($sortfield == "") {  $sortfield="d.login"; }
if (! isset($statut))
{
  $statut = 1 ;
}

if (! isset($cotis))
{
  // par defaut les adherents doivent etre a jour de cotisation
  $cotis=1;
}


$sql = "SELECT d.login, d.pass, d.datefin";
$sql .= " FROM ".MAIN_DB_PREFIX."adherent as d ";
$sql .= " WHERE d.statut = $statut ";
if ($cotis==1)
{
	$sql .= " AND datefin > '".$db->idate($now)."'";
}
$sql.= $db->order($sortfield,$sortorder);
//$sql.=$db->plimit($conf->liste_limit, $offset);

$resql = $db->query($sql);
if ($resql)
{
	$num = $db->num_rows($resql);
	$i = 0;

	print_barre_liste($langs->trans("HTPasswordExport"), $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder,'',0);

	print "<hr>\n";
	while ($i < $num)
	{
		$objp = $db->fetch_object($result);
		$htpass=crypt($objp->pass,makesalt());
		print $objp->login.":".$htpass."<br>\n";
		$i++;
	}
	print "<hr>\n";
}
else
{
	dol_print_error($db);
}


llxFooter();

$db->close();
?>
