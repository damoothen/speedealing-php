<?php
/* Copyright (C) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *
 */

/**
 * 	    \file       htdocs/boutique/critiques/fiche.php
 * 		\ingroup    boutique
 * 		\brief      Page fiche critique OS Commerce
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/boutique/osc_master.inc.php';

$id=$_GET["id"];



llxHeader();

if ($id)
{

  $critique = new Critique($dbosc);
  $result = $critique->fetch($id);

  if ( $result )
    {

      print '<div class="titre">Fiche Critique</div><br>';

      print '<table border="1" width="100%" cellspacing="0" cellpadding="4">';
      print "<tr>";
      print '<td width="20%">Produit</td><td width="80%">'.$critique->product_name.'</td></tr>';

      print '<tr><td width="20%">Texte</td><td width="80%">'.nl2br($critique->text).'</td></tr>';
      print "</table>";



    }
  else
    {
      print "Fetch failed";
    }

}

/* ************************************************************************** */
/*                                                                            */
/* Barre d'action                                                             */
/*                                                                            */
/* ************************************************************************** */

print '<br><table width="100%" border="1" cellspacing="0" cellpadding="3">';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '<td width="20%" align="center">-</td>';
print '</table><br>';



$dbosc->close();

llxFooter();

?>
