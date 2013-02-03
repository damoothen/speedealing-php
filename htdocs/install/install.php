<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
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
 *       \file       htdocs/install/install.php
 *       \ingroup    install
 *       \brief      Install process
 */
include 'inc.php';
include '../core/class/html.form.class.php';
include '../core/class/html.formadmin.class.php';

$setuplang=GETPOST("selectlang",'',3)?GETPOST("selectlang",'',3):'auto';
$langs->setDefaultLang($setuplang);

$langs->load("install");

$formadmin=new FormAdmin('');

// MAIN_DOCUMENT_ROOT
if (!isset($dolibarr_main_url_root) || dol_strlen($dolibarr_main_url_root) == 0) {
	//print "x".$_SERVER["SCRIPT_FILENAME"]." y".$_SERVER["DOCUMENT_ROOT"];
	// Si le php fonctionne en CGI, alors SCRIPT_FILENAME vaut le path du php et
	// ce n'est pas ce qu'on veut. Dans ce cas, on propose $_SERVER["DOCUMENT_ROOT"]
	if (preg_match('/^php$/i', $_SERVER["SCRIPT_FILENAME"]) || preg_match('/[\\/]php$/i', $_SERVER["SCRIPT_FILENAME"]) || preg_match('/php\.exe$/i', $_SERVER["SCRIPT_FILENAME"])) {
		$dolibarr_main_document_root = $_SERVER["DOCUMENT_ROOT"];

		if (!preg_match('/[\\/]speedealing[\\/]htdocs$/i', $dolibarr_main_document_root)) {
			$dolibarr_main_document_root.="/speedealing/htdocs";
		}
	} else {
		$dolibarr_main_document_root = dirname(dirname($_SERVER["SCRIPT_FILENAME"]));
		// Nettoyage du path propose
		// Gere les chemins windows avec double "\"
		$dolibarr_main_document_root = str_replace('\\\\', '/', $dolibarr_main_document_root);

		// Supprime les slash ou antislash de fins
		$dolibarr_main_document_root = preg_replace('/[\\/]+$/', '', $dolibarr_main_document_root);
	}
}

// MAIN_DATA_ROOT
if (empty($dolibarr_main_data_root)) {
	// Si le repertoire documents non defini, on en propose un par defaut
	if (empty($force_install_main_data_root)) {
		$dolibarr_main_data_root = preg_replace("/\/htdocs$/", "", $dolibarr_main_document_root);
		$dolibarr_main_data_root.="/documents";
	} else {
		$dolibarr_main_data_root = $force_install_main_data_root;
	}
}

// MAIN_URL_ROOT
if (!empty($main_url))
	$dolibarr_main_url_root = $main_url;
if (empty($dolibarr_main_url_root)) {
	// If defined (Ie: Apache with Linux)
	if (isset($_SERVER["SCRIPT_URI"])) {
		$dolibarr_main_url_root = $_SERVER["SCRIPT_URI"];
	}
	// If defined (Ie: Apache with Caudium)
	elseif (isset($_SERVER["SERVER_URL"]) && isset($_SERVER["DOCUMENT_URI"])) {
		$dolibarr_main_url_root = $_SERVER["SERVER_URL"] . $_SERVER["DOCUMENT_URI"];
	}
	// If SCRIPT_URI, SERVER_URL, DOCUMENT_URI not defined (Ie: Apache 2.0.44 for Windows)
	else {
		$proto = 'http';
		if (!empty($_SERVER["HTTP_HOST"]))
			$serverport = $_SERVER["HTTP_HOST"];
		else
			$serverport = $_SERVER["SERVER_NAME"];
		$dolibarr_main_url_root = $proto . "://" . $serverport . $_SERVER["SCRIPT_NAME"];
	}
	// Clean proposed URL
	$dolibarr_main_url_root = preg_replace('/\/install\.php$/', '', $dolibarr_main_url_root); // Remove the /fileconf.php
	$dolibarr_main_url_root = preg_replace('/\/$/', '', $dolibarr_main_url_root);     // Remove the /
	$dolibarr_main_url_root = preg_replace('/\/index\.php$/', '', $dolibarr_main_url_root);  // Remove the /index.php
	$dolibarr_main_url_root = preg_replace('/\/install$/', '', $dolibarr_main_url_root);   // Remove the /install
}


/*
 * View
 */

pHeader();

include 'tpl/install.tpl.php';

pFooter();

?>
