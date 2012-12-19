<?php

/* Copyright (C) 2006-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2010      Regis Houssin		<regis@dolibarr.fr>
 * Copyright (C) 2010-2011 Herve Prot       	<herve.prot@symeos.com>
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
 * or see http://www.gnu.org/
 */

/**
 * 	    \file       htdocs/core/lib/contact.lib.php
 * 		\brief      Ensemble de fonctions de base pour les contacts
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function contact_prepare_head($object) {
    global $langs, $conf;

    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . '/contact/fiche.php?id=' . $object->id;
    $head[$h][1] = $langs->trans("Card");
    $head[$h][2] = 'card';
    $h++;

    if (!empty($conf->ldap->enabled) && !empty($conf->global->LDAP_CONTACT_ACTIVE)) {
        $langs->load("ldap");

        $head[$h][0] = DOL_URL_ROOT . '/contact/ldap.php?id=' . $object->id;
        $head[$h][1] = $langs->trans("LDAPCard");
        $head[$h][2] = 'ldap';
        $h++;
    }

    $head[$h][0] = DOL_URL_ROOT . '/contact/perso.php?id=' . $object->id;
    $head[$h][1] = $langs->trans("PersonalInformations");
    $head[$h][2] = 'perso';
    $h++;

    $head[$h][0] = DOL_URL_ROOT . '/categories/categorie.php?id=' . $_GET["id"] . '&type=5';
    $head[$h][1] = $langs->trans("Categories");
    $head[$h][2] = 'category';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'contact');

    return $head;
}

?>