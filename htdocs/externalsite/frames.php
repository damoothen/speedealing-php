<?php
/* Copyright (C) 2004-2010 Laurent Destailleur <eldy@users.sourceforge.net>
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
 *     \file       htdocs/externalsite/frames.php
 *     \ingroup    externalsite
 *     \brief      Page that build two frames: One for menu, the other for the target page to show
 *     \author	   Laurent Destailleur
 */

require '../main.inc.php';

$langs->load("externalsite@externalsite");

if (empty($conf->global->EXTERNALSITE_URL))
{
	llxHeader();
	print '<div class="error">Module ExternalSite was not configured properly.</div>';
	llxFooter();
}

$mainmenu=GETPOST('mainmenu');
$leftmenu=GETPOST('leftmenu');
$idmenu=GETPOST('idmenu');
$theme=GETPOST('theme');
$codelang=GETPOST('lang');

print "
<html>
<head>
<title>Dolibarr frame for external web site</title>
</head>

<frameset rows=\"".$heightforframes.",*\" border=0 framespacing=0 frameborder=0>
    <frame name=\"barre\" src=\"frametop.php?mainmenu=".$mainmenu."&leftmenu=".$leftmenu."&idmenu=".$idmenu.($theme?'&theme='.$theme:'').($codelang?'&lang='.$codelang:'')."&nobackground=1\" noresize scrolling=\"NO\" noborder>
    <frame name=\"main\" src=\"".$conf->global->EXTERNALSITE_URL."\">
    <noframes>
    <body>

    </body>
    </noframes>
</frameset>

<noframes>
<body>
	<br><center>
	Sorry, your browser is too old or not correctly configured to view this area.<br>
	Your browser must support frames.<br>
	</center>
</body>
</noframes>

</html>
";


?>
