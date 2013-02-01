<?php
/* Copyright (C) 2003-2004 Rodolphe Quiedeville  <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo    <jlb@j1b.org>
 * Copyright (C) 2004-2012 Laurent Destailleur   <eldy@users.sourceforge.net>
 * Copyright (C) 2007      Franky Van Liedekerke <franky.van.liedekerke@telenet.be>
 * Copyright (C) 2005-2013 Regis Houssin         <regis.houssin@capnetworks.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
 *       \file       htdocs/admin/system/about.php
 *       \brief      About Speedealing File page
 */

require '../../main.inc.php';

$langs->load("admin");
$langs->load("help");
$langs->load("members");


/*
 * View
 */

llxHeader();


print_fiche_titre("Speedealing",'','setup');

print '<div style="padding-left: 30px;">'.img_picto_common('', 'dolibarr_box.png','height="120"').'</div>';

print $langs->trans("Version").' / '.$langs->trans("SpeedealingLicense").':';
print '<ul>';
print '<li>'.DOL_VERSION.' / <a href="http://www.apache.org/licenses/">Apache V2</a></li>';
print '</ul>';

//print "<br>\n";

print $langs->trans("Developpers").':';
print '<ul>';
print '<li>'.$langs->trans("DevelopmentPlatform").': <a href="https://speedealing.atlassian.net/" target="_blank">https://speedealing.atlassian.net/</a></li>';
print '</ul>';

//print "<br>\n";

print $langs->trans("OtherInformations").':';

print '<ul>';
print '<li>';
print '<a target="_blank" href="http://www.speedealing.com/">'.$langs->trans("OfficialWebSite").'</a>';
print '</li>';

print '<li>';
print '<a target="_blank" href="http://wiki.speedealing.com/">'.$langs->trans("OfficialWiki").'</a>';
print '</li>';
print '</ul>';

print $langs->trans("Demo").':';
print '<ul>';
print '<li>';
print '<a target="_blank" href="http://demo.speedealing.com/">'.$langs->trans("OfficialDemo").'</a>';
print '</li>';
print '</ul>';

print $langs->trans("ModulesMarketPlaces").':';
print '<ul>';
print '<li>';
print '<a target="_blank" href="http://shop.speedealing.com">'.$langs->trans("OfficialMarketPlace").'</a>';
print '</li>';
print '</ul>';

print dol_fiche_end();

llxFooter();

$db->close();
?>
