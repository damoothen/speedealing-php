<?PHP
/* Copyright (C) 2007 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2008 Jean Heimburger	<jean@tiaris.fr> 
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
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */

/**
        \file       scripts/ecommerce/ws_import.php
		\ingroup    ecommerce
        \brief      Script for importing orders, clients, products from ecommerce site
        \version    
		\author		Jean Heimburger
		\remarks		http://www.tiaris.fr
*/

// Test if batch mode
$sapi_type = php_sapi_name();
$script_file=__FILE__; 
if (eregi('([^\\\/]+)$',$script_file,$reg)) $script_file=$reg[1];
$path=eregi_replace($script_file,'',$_SERVER["PHP_SELF"]);

if (substr($sapi_type, 0, 3) == 'cgi') {
    echo "Erreur: Vous utilisez l'interpreteur PHP pour le mode CGI. Pour executer $script_file en ligne de commande, vous devez utiliser l'interpreteur PHP pour le mode CLI.\n";
    exit;
}

// Include Dolibarr environment
require_once($path."../../htdocs/master.inc.php");

// After this $db is a defined handler to database.
dol_include_once("/ecommerce/class/ecom_site.class.php");
dol_include_once("/ecommerce/class/ecom_order.class.php");
dol_include_once("/ecommerce/class/E_product.class.php");
dol_include_once("/ecommerce/class/E_order.class.php");
dol_include_once("/ecommerce/class/ecom_log.class.php");
require_once($path."../../htdocs/product/class/product.class.php");

//includes 
dol_include_once("/ecommerce/includes/orders.inc.php");
dol_include_once("/ecommerce/includes/products.inc.php");
dol_include_once("/ecommerce/includes/prospects.inc.php");

// Main
$version='$Revision: 1.4 $';
@set_time_limit(0);
$error=0;

$langs->setDefaultLang();
$langs->load("main");
$langs->load("ecommerce@ecommerce");
 
$user->fetch('',ECOM_BATCH_USER, '');
print "***** $script_file ($version) *****\n";
print "user ".ECOM_BATCH_USER."\n";
//Chargement des droits
$user->getrights('');

// -------------------- START OF YOUR CODE HERE --------------------

// Check parameters
if (! isset($argv[1])) {
    print "Usage: $script_file order/product/prospect siteid  ...\n";
    return -1;
}

if (! isset($argv[2])) {
    print "il faut spécifier le siteid\n";
    return -1;
}
$siteid=$argv[2];

// lecture du site
if ($siteid)
{
// get site_parameters
	$site_params = new Ecom_site($db);
	if ($site_params->fetch($siteid,$user) < 0) 
	{
		print "erreur lecture site ".$site_params->error;
		return -2;
	}
}
		
switch ($action = $argv[1])
{
	case 'order':
		print "traitement des commandes\n";
		if ($res = get_orders($siteid, $db, $user) > 0) print "traitement des commandes réussi $res\n";
		else print "Erreur traitement des commandes \n";
		$res = import_orders($siteid, $db, $user);
		if ($res < 0) print "Erreur import des commandes"."\n";
		elseif ($res == 0) print "aucune commande à traiter"."\n";
		else  print $res." commandes importées"."\n"; 
		break;
	case 'product':
		print "traitement des produits"."\n";
		print "étape 1 liste des nouveaux produits"."\n";
		// traitement de la limite
		if (! isset($argv[3])) $limit = 0;
		elseif ($argv[3] > 0 ) $limit = $argv[3];
		else $limit = 100; // on limite

		// pour ne pas re-traiter les produits en erreur
		$sql = "SELECT max(ecom_product) last FROM ".MAIN_DB_PREFIX."ecom_product ep ";
		$sql .= "WHERE ep.siteid = '".$siteid."' ";
	
		dol_syslog("products.inc.php::get_new_products $sql", LOG_DEBUG);
		$resql=$db->query($sql);
		if ($resql)
		{
			$obj = $db->fetch_object($resql);
			if ($obj)		
				$last = $obj->last;
			else 
			{
				dol_syslog("products.inc.php::get_new_products rien � faire", LOG_DEBUG);
				$last = 0;
			}
		}
		else
		{
			dol_syslog("products.inc.php::get_new_products erreur ".$sql, LOG_DEBUG);
			return -1;

		}
		
		// 1. recherche des nouveaux porduits		
		if ($res = get_new_products($siteid,$db, $user, $limit) < 0) print "Erreur traitement des produits �tape 1"."\n";
		else 	print "fin �tape 1 d�but �tape 2"."\n";
		// on fait les deux traitements syst�matiquement
		if ($res = get_products($siteid, $db, $user, $limit, $last)) print "traitement produit r�ussi $last $limit"." \n";
			else print "Erreur traitement des produits $last $limit"."\n";
		
		break;
	case 'prospect':
		print "traitement des prospects\n";
		
		// traitement de la limite
		if (! isset($argv[3])) $limit = 0;
		elseif ($argv[3] > 0 ) $limit = $argv[3];
		else $limit = 100; // on limite
		
		// pour ne pas re-traiter les prospects 
		$sql = "SELECT max(ecom_customer) last FROM ".MAIN_DB_PREFIX."ecom_customer ec ";
		$sql .= "WHERE ec.siteid = '".$siteid."' AND ec.site_cust_status = 'P'";
	
		dolibarr_syslog("ws_import.inc.php::import_prospects $sql", LOG_DEBUG);
		$resql=$db->query($sql);
		if ($resql)
		{
			$obj = $db->fetch_object($resql);
			if ($obj)		
				$last = $obj->last;
			else 
			{
				dolibarr_syslog("ws_import::aucun nouveau propect", LOG_DEBUG);
				$last = 0;
			}
		}
		else
		{
			dolibarr_syslog("ws_import::import prospects erreur ".$sql, LOG_ERR);
			return -1;

		}
		
		
		if ($res = get_new_prospects($siteid,$db, $user, $limit) < 0) print "Erreur traitement des prospects étape 1"."\n";
		else 	print "fin étape 1 début étape 2"."\n";
		// on fait les deux traitements systématiquement
		if ($res = import_prospects($siteid, $db, $user, $limit, $last)) print "traitement des prospects réussi $last $limit"." \n";
			else print "Erreur traitement des prospects $last $limit"."\n";
					
		break;
	default :
		print "commande inconnue : ".$argv[1]."\n";
		$error = -1;
}


/*function get_products($siteid, $db, $user)
{
	$e_prod = new E_product($db, $siteid, $user);
	if ($e_prod->wsfetch(27,'') < 0) print "erreur ".$e_prod->error."\n";
//	print_r($e_prod);
	$prod = new Product($db);
	$prod = $e_prod->ecom2dolibarr(27);
	if (!$prod) print "Erreur ecom2dolibarr \n";
  // print_r($prod);
//  print_r($user);
  	$id = $prod->create($user);
  	// voir autres traitements � faire dans fiche.php
	print "produit cr�� ".$id."\n";	
	return $id;
}
*/

// -------------------- END OF YOUR CODE --------------------


return $error;
?>
