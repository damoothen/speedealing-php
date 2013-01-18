<?php
/* Copyright (C) 2003-2004 Rodolphe Quiedeville  <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo    <jlb@j1b.org>
 * Copyright (C) 2004-2012 Laurent Destailleur   <eldy@users.sourceforge.net>
 * Copyright (C) 2007      Franky Van Liedekerke <franky.van.liedekerke@telenet.be>
 * Copyright (C) 2005-2013 Regis Houssin         <regis.houssin@capnetworks.com>
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
