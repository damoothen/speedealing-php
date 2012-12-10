<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copytight (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
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
 *      \file       htdocs/compta/bank/categ.php
 *      \ingroup    compta
 *      \brief      Page ajout de categories bancaires
 */

require 'pre.inc.php';

if (!$user->rights->banque->configurer)
  accessforbidden();



/*
* Actions ajout cat�gorie
*/
if ($_POST["action"] == 'add')
{
	if ($_POST["label"])
	{
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."bank_categ (";
		$sql.= "label";
		$sql.= ", entity";
		$sql.= ") VALUES (";
		$sql.= "'".$db->escape($_POST["label"])."'";
		$sql.= ", ".$conf->entity;
		$sql.= ")";

		$result = $db->query($sql);

		if (!$result)
		{
			dol_print_error($db);
		}
	}
}

/*
* Action suppression cat�gorie
*/
if ( $_REQUEST['action'] == 'delete' )
{
	if ( $_REQUEST['categid'] )
	{
		$sql = "DELETE FROM ".MAIN_DB_PREFIX."bank_categ";
		$sql.= " WHERE rowid = '".$_REQUEST['categid']."'";
		$sql.= " AND entity = ".$conf->entity;

		$result = $db->query($sql);

		if (!$result)
		{
			dol_print_error($db);
		}
	}
}



/*
 * Affichage liste des cat�gories
 */

llxHeader();


print_fiche_titre($langs->trans("Rubriques"));


print '<form method="post" action="categ.php">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print "<input type=\"hidden\" name=\"action\" value=\"add\">";
print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Ref").'</td><td colspan="2">'.$langs->trans("Label").'</td>';
print "</tr>\n";

$sql = "SELECT rowid, label";
$sql.= " FROM ".MAIN_DB_PREFIX."bank_categ";
$sql.= " WHERE entity = ".$conf->entity;
$sql.= " ORDER BY label";

$result = $db->query($sql);
if ($result)
{
  $num = $db->num_rows($result);
  $i = 0; $total = 0;

  $var=True;
  while ($i < $num)
    {
      $objp = $db->fetch_object($result);
      $var=!$var;
      print "<tr $bc[$var]>";
      print '<td><a href="'.DOL_URL_ROOT.'/compta/bank/budget.php?bid='.$objp->rowid.'">'.$objp->rowid.'</a></td>';
      print "<td>$objp->label</td>";
      print '<td style="text-align: center;"><a href="categ.php?categid='.$objp->rowid.'&amp;action=delete">'.img_delete().'</a></td>';
      print "</tr>";
      $i++;
    }
  $db->free($result);
}

/*
 * Affichage ligne ajout de categorie
 */
$var=!$var;
print "<tr $bc[$var]>";
print "<td>&nbsp;</td><td><input name=\"label\" type=\"text\" size=45></td>";
print '<td align="center"><input type="submit" class="button" value="'.$langs->trans("Add").'"></td></tr>';
print "</table></form>";



$db->close();

llxFooter();
?>
