<?php
/**
 * Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2012	   Florian Henry  <florian.henry@open-concept.pro>
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
 *      \file       public/emailing/mailing-read.php
 *      \ingroup    mailing
 *      \brief      Script use to update mail status if destinaries read it (if images during mail read are display)
 */

define("NOLOGIN",1);		// This means this output page does not require to be logged.
define("NOCSRFCHECK",1);	// We accept to go on this page from external web site.

require '../../main.inc.php';

$id=GETPOST('tag');

if (empty($conf->global->MAILING_EMAIL_UNSUBSCRIBE)) accessforbidden('Option not enabled');


/*
 * Actions
 */

if ($id!='')
{
	$statut='2';
	$sql = "UPDATE ".MAIN_DB_PREFIX."mailing_cibles SET statut=".$statut." WHERE tag='".$id."'";
	dol_syslog("public/emailing/mailing-read.php : Mail read : ".$sql, LOG_DEBUG);

	$resql=$db->query($sql);

	//Update status communication of thirdparty prospect
	$sql = "UPDATE ".MAIN_DB_PREFIX."societe SET fk_stcomm=3 WHERE rowid IN (SELECT source_id FROM ".MAIN_DB_PREFIX."mailing_cibles WHERE tag='".$id."' AND source_type='thirdparty' AND source_id is not null)";
	dol_syslog("public/emailing/mailing-read.php : Mail read thirdparty : ".$sql, LOG_DEBUG);

	$resql=$db->query($sql);

    //Update status communication of contact prospect
	$sql = "UPDATE ".MAIN_DB_PREFIX."societe SET fk_stcomm=3 WHERE rowid IN (SELECT sc.fk_soc FROM ".MAIN_DB_PREFIX."socpeople AS sc INNER JOIN ".MAIN_DB_PREFIX."mailing_cibles AS mc ON mc.tag = '".$id."' AND mc.source_type = 'contact' AND mc.source_id = sc.rowid)";
	dol_syslog("public/emailing/mailing-read.php : Mail read contact : ".$sql, LOG_DEBUG);

	$resql=$db->query($sql);

}

$db->close();
?>
