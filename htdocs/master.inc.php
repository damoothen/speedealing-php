<?PHP
/* Copyright (C) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Xavier Dutoit        <doli@sydesy.com>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2005 	   Simon Tosser         <simon@kornog-computing.com>
 * Copyright (C) 2006 	   Andre Cianfarani     <andre.cianfarani@acdeveloppement.net>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2011      Philippe Grand       <philippe.grand@atoo-net.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
 *	\file       htdocs/master.inc.php
 * 	\ingroup	core
 *  \brief      File that defines environment for all Dolibarr process (pages or scripts)
 * 				This script reads the conf file, init $lang, $db and and empty $user
 */

//require_once("filefunc.inc.php");	// May have been already require by main.inc.php. But may not by scripts.


/*
 * Create $conf object
 */
require_once(DOL_DOCUMENT_ROOT."/core/class/conf.class.php");

$conf = new Conf();
// Identifiant propres au serveur couchdb
$conf->Couchdb->protocol				= $dolibarr_main_couchdb_protocol;
$conf->Couchdb->host					= $dolibarr_main_couchdb_host;
$conf->Couchdb->port					= $dolibarr_main_couchdb_port;
$conf->Couchdb->name					= $dolibarr_main_couchdb_name;
// Identifiant pour le serveur memcached
$conf->memcached->host					= $dolibarr_main_memcached_host;
$conf->memcached->port					= $dolibarr_main_memcached_port;

// Identifiant propres au serveur base de donnee
$conf->db->host							= $dolibarr_main_db_host;
$conf->db->port							= $dolibarr_main_db_port;
$conf->db->name							= $dolibarr_main_db_name;
$conf->db->user							= $dolibarr_main_db_user;
$conf->db->pass							= $dolibarr_main_db_pass;
$conf->db->type							= $dolibarr_main_db_type;
$conf->db->prefix						= $dolibarr_main_db_prefix;
$conf->db->character_set				= $dolibarr_main_db_character_set;
$conf->db->dolibarr_main_db_collation	= $dolibarr_main_db_collation;
$conf->db->dolibarr_main_db_encryption	= $dolibarr_main_db_encryption;
$conf->db->dolibarr_main_db_cryptkey	= $dolibarr_main_db_cryptkey;
$conf->file->main_limit_users			= $dolibarr_main_limit_users;
$conf->file->mailing_limit_sendbyweb	= $dolibarr_mailing_limit_sendbyweb;
// Identification mode
$conf->file->main_authentication		= empty($dolibarr_main_authentication)?'':$dolibarr_main_authentication;
// Force https
$conf->file->main_force_https			= empty($dolibarr_main_force_https)?'':$dolibarr_main_force_https;
// Cookie cryptkey
$conf->file->cookie_cryptkey			= empty($dolibarr_main_cookie_cryptkey)?'':$dolibarr_main_cookie_cryptkey;
// Define array of document root directories
$conf->file->dol_document_root			= array('main' => DOL_DOCUMENT_ROOT);
if (! empty($dolibarr_main_document_root_alt))
{
	// dolibarr_main_document_root_alt contains several directories
	$values=preg_split('/[;,]/',$dolibarr_main_document_root_alt);
	foreach($values as $value)
	{
		$conf->file->dol_document_root['alt']=$value;
	}
}
// Force db type (for test purpose)
if (defined('TEST_DB_FORCE_TYPE')) $conf->db->type=constant('TEST_DB_FORCE_TYPE');

// Chargement des includes principaux de librairies communes
if (! defined('NOREQUIREUSER')) require_once(DOL_DOCUMENT_ROOT ."/user/class/user.class.php");		// Need 500ko memory

// For couchdb

require_once(DOL_DOCUMENT_ROOT ."/core/db/couchdb/lib/couch.php");
require_once(DOL_DOCUMENT_ROOT ."/core/db/couchdb/lib/couchClient.php");
require_once(DOL_DOCUMENT_ROOT ."/core/class/nosqlDocument.class.php");

/*
 * Creation objet $langs (must be before all other code)
 */
if (! defined('NOREQUIRETRAN'))
{
	require_once(DOL_DOCUMENT_ROOT ."/core/class/translate.class.php");
	$langs = new Translate('',$conf);	// A mettre apres lecture de la conf
}

/*
 * Object $db
 */
if (! defined('NOREQUIREDB'))
{
    $db=getDoliDBInstance($conf->db->type,$conf->db->host,$conf->db->user,$conf->db->pass,$conf->db->name,$conf->db->port);

	if ($db->error)
	{
		dol_print_error($db,"host=".$conf->db->host.", port=".$conf->db->port.", user=".$conf->db->user.", databasename=".$conf->db->name.", ".$db->error);
		exit;
	}
}

// Now database connexion is known, so we can forget password
unset($dolibarr_main_db_pass); 	// We comment this because this constant is used in a lot of pages
unset($conf->db->pass);				// This is to avoid password to be shown in memory/swap dump

/*
 * Object $user
 */
if (! defined('NOREQUIREUSER'))
{
	$user = new User($db);
}

if (! defined('MAIN_LABEL_MENTION_NPR') ) define('MAIN_LABEL_MENTION_NPR','NPR');

// We force feature to help debug
//$conf->global->MAIN_JS_ON_PAYMENT=0;    // We disable this. See bug #402 on doliforge

?>
