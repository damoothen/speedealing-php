<?php

/* Copyright (C) 2007-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2007-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2010-2011 Juanjo Menent		<jmenent@2byte.es>
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
 *      \file       htdocs/core/login/functions_dolibarr.php
 *      \ingroup    core
 *      \brief      Authentication functions for Dolibarr mode
 */

/**
 * Check validity of user/password/entity
 * If test is ko, reason must be filled into $_SESSION["dol_loginmesg"]
 *
 * @param	string	$usertotest		Login
 * @param	string	$passwordtotest	Password
 * @param   int		$entitytotest   Number of instance (always 1 if module multicompany not enabled)
 * @return	string					Login if OK, '' if KO
 */
function check_user_password_couchdb($usertotest, $passwordtotest, $dbname) {
    global $db, $conf, $langs;
    global $mc;

    dol_syslog("functions_dolibarr::check_user_password_dolibarr usertotest=" . $usertotest);

    $login = '';

    if (!empty($usertotest)) {

        try {
            $host = substr($conf->Couchdb->host, 7);

            $client = new couchClient('http://' . $usertotest . ':' . $passwordtotest . '@' . $host . ':' . $conf->Couchdb->port . '/', $dbname, array("cookie_auth" => TRUE));
        } catch (Exception $e) {
            dol_print_error($e->getMessage());
            error_log($e->getMessage());
            exit;
        }

        return $client;
    }

    $langs->load('main');
    $langs->load('errors');
    sleep(10);
    print $langs->trans("ErrorBadLoginPassword");
    exit;
}

?>