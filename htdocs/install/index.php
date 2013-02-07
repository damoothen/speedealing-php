<?php

/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2013	Herve Prot		<herve.prot@symeos.com>
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

include 'inc.php';
include '../core/class/html.formadmin.class.php';

$setuplang = GETPOST("selectlang", '', 3) ? GETPOST("selectlang", '', 3) : 'auto';
$langs->setDefaultLang($setuplang);

$langs->load("install");

$formadmin = new FormAdmin('');

// MAIN_DOCUMENT_ROOT
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

// Force https by default
if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
	$https_enabled = true;
else
	$https_enabled = false;

// Create matrice conf file
if (is_readable($conffile) && filesize($conffile) > 8) {
	// Conf file already defined
} else {
	// First we try by copying example
	if (@copy($conffile . ".example", $conffile)) {
		// Success
	} else {
		$fp = @fopen($conffile, "w");
		if ($fp) {
			@fwrite($fp, '<?php');
			@fputs($fp, "\n");
			@fputs($fp, "?>");
			fclose($fp);
		}
	}
}


/*
 * View
 */

pHeader();

include 'tpl/install.tpl.php';

pFooter();
?>
