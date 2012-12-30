<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2011 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011 	   Juanjo Menent		<jmenent@2byte.es>
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
 *   	\file       htdocs/adherents/admin/spip.php
 *		\ingroup    mailmanspip
 *		\brief      Page to setup the module MailmanSpip (SPIP)
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/mailmanspip.lib.php';

$langs->load("admin");
$langs->load("members");
$langs->load("mailmanspip");

if (! $user->admin) accessforbidden();


$type=array('yesno','texte','chaine');

$action = GETPOST("action");


/*
 * Actions
 */

// Action mise a jour ou ajout d'une constante
if ($action == 'update' || $action == 'add')
{
	$constname=GETPOST("constname");
	$constvalue=GETPOST("constvalue");

	if (($constname=='ADHERENT_CARD_TYPE' || $constname=='ADHERENT_ETIQUETTE_TYPE') && $constvalue == -1) $constvalue='';
	if ($constname=='ADHERENT_LOGIN_NOT_REQUIRED') // Invert choice
	{
		if ($constvalue) $constvalue=0;
		else $constvalue=1;
	}

	if (in_array($constname,array('ADHERENT_MAIL_VALID','ADHERENT_MAIL_COTIS','ADHERENT_MAIL_RESIL'))) $constvalue=$_POST["constvalue".$constname];
	$consttype=$_POST["consttype"];
	$constnote=GETPOST("constnote");
	$res=dolibarr_set_const($db,$constname,$constvalue,$type[$consttype],0,$constnote,$conf->entity);

	if (! $res > 0) $error++;

	if (! $error)
	{
		$mesg = '<div class="ok">'.$langs->trans("SetupSaved").'</div>';
	}
	else
	{
		$mesg = '<div class="error">'.$langs->trans("Error").'</div>';
	}
}

// Action activation d'un sous module du module adherent
if ($action == 'set')
{
    $result=dolibarr_set_const($db, $_GET["name"],$_GET["value"],'',0,'',$conf->entity);
    if ($result < 0)
    {
        dol_print_error($db);
    }
}

// Action desactivation d'un sous module du module adherent
if ($action == 'unset')
{
    $result=dolibarr_del_const($db,$_GET["name"],$conf->entity);
    if ($result < 0)
    {
        dol_print_error($db);
    }
}



/*
 * View
 */

$help_url='';

llxHeader('',$langs->trans("MailmanSpipSetup"),$help_url);


$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("MailmanSpipSetup"),$linkback,'setup');


$head = mailmanspip_admin_prepare_head();

dol_fiche_head($head, 'spip', $langs->trans("Setup"), 0, 'user');


dol_htmloutput_mesg($mesg);

/*
 * Spip
 */
$var=!$var;
if (! empty($conf->global->ADHERENT_USE_SPIP))
{
    //$lien=img_picto($langs->trans("Active"),'tick').' ';
    $lien='<a href="'.$_SERVER["PHP_SELF"].'?action=unset&value=0&name=ADHERENT_USE_SPIP">';
    //$lien.=$langs->trans("Disable");
    $lien.=img_picto($langs->trans("Activated"),'switch_on');
    $lien.='</a>';
    // Edition des varibales globales
    $constantes=array(
    	'ADHERENT_SPIP_SERVEUR',
    	'ADHERENT_SPIP_DB',
    	'ADHERENT_SPIP_USER',
    	'ADHERENT_SPIP_PASS'
	);

    print_fiche_titre($langs->trans('SPIPTitle'), $lien, '');
    form_constantes($constantes);
    print '<br>';
}
else
{
    $lien='<a href="'.$_SERVER["PHP_SELF"].'?action=set&value=1&name=ADHERENT_USE_SPIP">';
    //$lien.=$langs->trans("Activate");
    $lien.=img_picto($langs->trans("Disabled"),'switch_off');
    $lien.='</a>';
    print_fiche_titre($langs->trans('SPIPTitle'), $lien, '');
}


dol_fiche_end();

llxFooter();

$db->close();
?>