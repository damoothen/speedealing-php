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
 *	\file       trash/list.php
 *	\brief      List of all the elements put in the trash
 *	\ingroup    trash
 */

require '../main.inc.php';
require DOL_DOCUMENT_ROOT . '/trash/class/trash.class.php';
require DOL_DOCUMENT_ROOT . '/core/class/autoloader.php';


// Security check
$socid = GETPOST("socid");
if ($user->societe_id)
    $socid = $user->societe_id;
$result = restrictedArea($user, 'societe');

$object = new \Trash();

/*
 * View
 */

llxHeader('', $langs->trans("RecycleBin"));

print_fiche_titre($langs->trans("RecycleBin"));

echo '<div class="with-padding">';

echo '<br>';

//echo $object->showList();

echo '</div>'; // end

llxFooter();
?>
