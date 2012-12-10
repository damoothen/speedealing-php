<?php
/* Copyright (C) 2001-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 *      \file       htdocs/comm/bookmark.php
 *      \brief      Page affichage des bookmarks
 */


require '../main.inc.php';


$sortfield = GETPOST("sortfield",'alpha');
$sortorder = GETPOST("sortorder",'alpha');
$page = GETPOST("page",'int');
if ($page == -1) { $page = 0; }
$offset = $conf->liste_limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (! $sortorder) $sortorder="DESC";
if (! $sortfield) $sortfield="bid";
$limit = $conf->liste_limit;


llxHeader();


/*
 * Actions
 */

if ($_GET["action"] == 'add')
{
    $bookmark=new Bookmark($db);
    $bookmark->fk_user=$user->id;
    $bookmark->url=$user->id;
    $bookmark->target=$user->id;
    $bookmark->title='xxx';
    $bookmark->favicon='xxx';

    $res=$bookmark->create();
    if ($res > 0)
    {
        header("Location: ".$_SERVER["PHP_SELF"]);
    }
    else
    {
        $mesg='<div class="error">'.$bookmark->error.'</div>';
    }
}

if ($_GET["action"] == 'delete')
{
    $bookmark=new Bookmark($db);
    $bookmark->id=$_GET["bid"];
    $bookmark->url=$user->id;
    $bookmark->target=$user->id;
    $bookmark->title='xxx';
    $bookmark->favicon='xxx';

    $res=$bookmark->remove();
    if ($res > 0)
    {
        header("Location: ".$_SERVER["PHP_SELF"]);
    }
    else
    {
        $mesg='<div class="error">'.$bookmark->error.'</div>';
    }
}



print_fiche_titre($langs->trans("Bookmarks"));

$sql = "SELECT s.rowid, s.nom, b.dateb as dateb, b.rowid as bid, b.fk_user, b.url, b.target, u.name, u.firstname";
$sql.= " FROM ".MAIN_DB_PREFIX."bookmark as b, ".MAIN_DB_PREFIX."societe as s, ".MAIN_DB_PREFIX."user as u";
$sql.= " WHERE b.fk_soc = s.rowid AND b.fk_user=u.rowid";
if (! $user->admin) $sql.= " AND b.fk_user = ".$user->id;
$sql.= $db->order($sortfield,$sortorder);
$sql.= $db->plimit($limit, $offset);

$resql=$db->query($sql);
if ($resql)
{
  $num = $db->num_rows($resql);
  $i = 0;

  if ($sortorder == "DESC") $sortorder="ASC";
  else $sortorder="DESC";

  print "<table class=\"noborder\" width=\"100%\">";

  print "<tr class=\"liste_titre\">";
  //print "<td>&nbsp;</td>";
  print_liste_field_titre($langs->trans("Id"),$_SERVER["PHP_SELF"],"bid","","",'align="center"',$sortfield,$sortorder);
  print_liste_field_titre($langs->trans("Author"),$_SERVER["PHP_SELF"],"u.name","","","",$sortfield,$sortorder);
  print_liste_field_titre($langs->trans("Date"),$_SERVER["PHP_SELF"],"b.dateb","","",'align="center"',$sortfield,$sortorder);
  print_liste_field_titre($langs->trans("Company"),$_SERVER["PHP_SELF"],"s.nom","","","",$sortfield,$sortorder);
  print_liste_field_titre($langs->trans("Url"),$_SERVER["PHP_SELF"],"b.url","","",'',$sortfield,$sortorder);
  print "<td>".$langs->trans("Target")."</td>";
  print "<td>&nbsp;</td>";
  print "</tr>\n";

  $var=True;
  while ($i < $num)
    {
      $obj = $db->fetch_object($resql);

      $var=!$var;
      print "<tr $bc[$var]>";
      //print "<td>" . ($i + 1 + ($limit * $page)) . "</td>";
      print "<td align=\"center\"><b>".$obj->bid."</b></td>";
      print "<td><a href='".DOL_URL_ROOT."/user/fiche.php?id=".$obj->fk_user."'>".img_object($langs->trans("ShowUser"),"user").' '.$obj->name." ".$obj->firstname."</a></td>\n";
      print '<td align="center">'.dol_print_date($db->jdate($obj->dateb))."</td>";
      print "<td><a href=\"index.php?socid=".$obj->rowid."\">".img_object($langs->trans("ShowCompany"),"company").' '.$obj->nom."</a></td>\n";
      print '<td align="center">'.$obj->url."</td>";
      print '<td align="center">'.$obj->target."</td>";
      print "<td><a href=\"bookmark.php?action=delete&bid=".$obj->bid."\">".img_delete()."</a></td>\n";
      print "</tr>\n";
      $i++;
    }
  print "</table>";
  $db->free($resql);
}
else
{
  dol_print_error($db);
}


$db->close();

llxFooter();
?>
