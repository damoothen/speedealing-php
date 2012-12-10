<?php
/* Copyright (C) 2010 Servitux Servicios Informaticos <info@servitux.es>
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
 *	\file       htdocs/asterisk/cidlookup.php
 *  \brief      Script to search companies names based on incoming calls
 *	\remarks    To use this script, your Asterisk must be compiled with CURL,
 *	            and your dialplan must be something like this:
 *
 * exten => s,1,Set(CALLERID(name)=${CURL(http://IP-DOLIBARR:80/asterisk/cidlookup.php?phone=${CALLERID(num)})})
 *
 *			Change IP-DOLIBARR to the IP address of your dolibarr
 *			server
 *
 */

$phone = $_GET['phone'];

include '../master.inc.php';


// Check parameters
if (empty($phone))
{
	print "Error: Url must be called with parameter phone=phone to search\n";
	exit;
}

$sql = "SELECT nom as name FROM ".MAIN_DB_PREFIX."societe as s";
$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."socpeople as sp ON sp.fk_soc = s.rowid";
$sql.= " WHERE s.entity IN (".getEntity('societe', 1).")";
$sql.= " AND (s.tel='".$db->escape($phone)."'";
$sql.= " OR sp.phone='".$db->escape($phone)."'";
$sql.= " OR sp.phone_perso='".$db->escape($phone)."'";
$sql.= " OR sp.phone_mobile='".$db->escape($phone)."')";
$sql.= $db->plimit(1);

dol_syslog('cidlookup search information with phone '.$phone, LOG_DEBUG);
$resql = $db->query($sql);
if ($resql)
{
	$obj = $db->fetch_object($resql);
	if ($obj)
	{
		$found = $obj->name;
	}
	$db->free($resql);
}
else
{
	dol_print_error($db,'Error');
}

echo $found;

?>
