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

/**
 *       \file       htdocs/install/index.php
 *       \ingroup    install
 *       \brief      Install process
 */
include 'inc.php';
include DOL_DOCUMENT_ROOT . '/core/class/html.formadmin.class.php';

$setuplang = GETPOST("selectlang", '', 3) ? GETPOST("selectlang", '', 3) : 'auto';
$langs->setDefaultLang($setuplang);

$langs->load("install");

// For select language
$formadmin = new FormAdmin('');

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

// Show header
pHeader();

// Show wizard
include 'tpl/install.tpl.php';

//Show footer
pFooter();
?>
