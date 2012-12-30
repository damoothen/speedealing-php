<?PHP

/* Copyright (C) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Xavier Dutoit        <doli@sydesy.com>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2005 	   Simon Tosser         <simon@kornog-computing.com>
 * Copyright (C) 2006 	   Andre Cianfarani     <andre.cianfarani@acdeveloppement.net>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2011      Philippe Grand       <philippe.grand@atoo-net.com>
 * Copyright (C) 2012      Herve Prot			<herve.prot@symeos.com>
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
 * 	\file       htdocs/after.inc.php
 * 	\ingroup	core
 *  \brief      File that defines environment for all Dolibarr process (pages or scripts)
 * 				This script reads the conf file, init $lang, $db and and empty $user
 */
/*
 * Load object $conf
 * After this, all parameters conf->global->CONSTANTS are loaded
 */
if (!defined('NOREQUIREDB')) {
    // Here we read database (llx_const table) and define $conf->global->XXX var.
    // if no db specified, using default database form user profile (entity)
    // Just after login : we choose the default entity

    $conf->useDatabase();
    $conf->setValues();
}

// Overwrite database value
if (!empty($conf->file->mailing_limit_sendbyweb)) {
    $conf->global->MAILING_LIMIT_SENDBYWEB = $conf->file->mailing_limit_sendbyweb;
}

// If software has been locked. Only login $conf->global->MAIN_ONLY_LOGIN_ALLOWED is allowed.
if (!empty($conf->global->MAIN_ONLY_LOGIN_ALLOWED)) {
    $ok = 0;
    if ((!session_id() || !isset($_SESSION["dol_login"])) && !isset($_POST["username"]) && !empty($_SERVER["GATEWAY_INTERFACE"]))
        $ok = 1; // We let working pages if not logged and inside a web browser (login form, to allow login by admin)
    elseif (isset($_POST["username"]) && $_POST["username"] == $conf->global->MAIN_ONLY_LOGIN_ALLOWED)
        $ok = 1; // We let working pages that is a login submission (login submit, to allow login by admin)
    elseif (defined('NOREQUIREDB'))
        $ok = 1; // We let working pages that don't need database access (xxx.css.php)
    elseif (defined('EVEN_IF_ONLY_LOGIN_ALLOWED'))
        $ok = 1; // We let working pages that ask to work even if only login enabled (logout.php)
    elseif (session_id() && isset($_SESSION["dol_login"]) && $_SESSION["dol_login"] == $conf->global->MAIN_ONLY_LOGIN_ALLOWED)
        $ok = 1; // We let working if user is allowed admin
    if (!$ok) {
        if (session_id() && isset($_SESSION["dol_login"]) && $_SESSION["dol_login"] != $conf->global->MAIN_ONLY_LOGIN_ALLOWED) {
            print 'Sorry, your application is offline.' . "\n";
            print 'You are logged with user "' . $_SESSION["dol_login"] . '" and only administrator user "' . $conf->global->MAIN_ONLY_LOGIN_ALLOWED . '" is allowed to connect for the moment.' . "\n";
            $nexturl = DOL_URL_ROOT . '/user/logout.php';
            print 'Please try later or <a href="' . $nexturl . '">click here to disconnect and change login user</a>...' . "\n";
        } else {
            print 'Sorry, your application is offline. Only administrator user "' . $conf->global->MAIN_ONLY_LOGIN_ALLOWED . '" is allowed to connect for the moment.' . "\n";
            $nexturl = DOL_URL_ROOT . '/';
            print 'Please try later or <a href="' . $nexturl . '">click here to change login user</a>...' . "\n";
        }
        exit;
    }
}

// Set default language (must be after the setValues of $conf)
if (!defined('NOREQUIRETRAN')) {
    $langs->setDefaultLang($conf->global->MAIN_LANG_DEFAULT);
}

/*
 * Create object $mysoc (A thirdparty object that contains properties of companies managed by Dolibarr.
 */
if (!defined('NOREQUIREDB') && !defined('NOREQUIRESOC')) {
    require_once(DOL_DOCUMENT_ROOT . "/societe/class/societe.class.php");
    $mysoc = new Societe($db);
    $mysoc->load("societe:mysoc", true);

    // For some countries, we need to invert our address with customer address
    if ($mysoc->country_id == 'DE' && !isset($conf->global->MAIN_INVERT_SENDER_RECIPIENT))
        $conf->global->MAIN_INVERT_SENDER_RECIPIENT = 1;
}
?>
