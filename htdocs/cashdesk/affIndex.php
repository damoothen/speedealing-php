<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier      <jeremie.o@laposte.net>
 * Copyright (C) 2008-2010 Laurent Destailleur   <eldy@uers.sourceforge.net>
 * Copyright (C) 2009      Regis Houssin         <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Juanjo Menent         <jmenent@2byte.es>
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
 *	\file       htdocs/cashdesk/affIndex.php
 *	\ingroup    cashdesk
 *	\brief      First page of point of sale module
 */
require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';

// Test if already logged
if ( $_SESSION['uid'] <= 0 )
{
	header('Location: index.php');
	exit;
}

$langs->load("cashdesk");


/*
 * View
 */

//header("Content-type: text/html; charset=UTF-8");
header("Content-type: text/html; charset=".$conf->file->character_set_client);

$arrayofjs=array();
$arrayofcss=array('/cashdesk/css/style.css');

top_htmlhead($head,$langs->trans("CashDesk"),0,0,$arrayofjs,$arrayofcss);

print '<body>'."\n";

if (!empty($error))
{
	print $error;
	print '</body></html>';
	exit;
}

print '<div class="conteneur">'."\n";
print '<div class="conteneur_img_gauche">'."\n";
print '<div class="conteneur_img_droite">'."\n";

print '<h1 class="entete"><span>POINT OF SALE</span></h1>'."\n";

print '<div class="menu_principal">'."\n";
include_once 'tpl/menu.tpl.php';
print '</div>'."\n";

print '<div class="contenu">'."\n";
include_once 'affContenu.php';
print '</div>'."\n";

include_once 'affPied.php';

print '</div></div></div>'."\n";
print '</body></html>'."\n";
?>