<?php

/* Copyright (C) 2008-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2008-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
 * or see http://www.gnu.org/
 */

/**
 *  Return user/group account of web server
 *
 *  @param	string	$mode       'user' or 'group'
 *  @return string				Return user or group of web server
 */
function dol_getwebuser($mode) {
    $t = '?';
    if ($mode == 'user')
        $t = getenv('APACHE_RUN_USER');   // $_ENV['APACHE_RUN_USER'] is empty
    if ($mode == 'group')
        $t = getenv('APACHE_RUN_GROUP');
    return $t;
}

/**
 * Show Dolibarr default login page.
 * Part of this code is also duplicated into main.inc.php::top_htmlhead
 *
 * @param		Translate	$langs		Lang object (must be initialized by a new).
 * @param		Conf		$conf		Conf object
 * @param		Societe		$mysoc		Company object
 * @return		void
 */
function dol_loginfunction($langs, $conf, $mysoc) {
    global $dolibarr_main_demo, $db;
    global $smartphone, $hookmanager;

    // Instantiate hooks of thirdparty module only if not already define
    if (!is_object($hookmanager)) {
        include_once DOL_DOCUMENT_ROOT . '/core/class/hookmanager.class.php';
        $hookmanager = new HookManager($db);
    }
    $hookmanager->initHooks(array('mainloginpage'));

    $langcode = (GETPOST('lang') ? ((is_object($langs) && $langs->defaultlang) ? $langs->defaultlang : 'auto') : GETPOST('lang'));
    $langs->setDefaultLang($langcode);

    $langs->load("main");
    $langs->load("other");
    $langs->load("help");
    $langs->load("admin");

    $main_authentication = $conf->file->main_authentication;
    $session_name = session_name();

    $dol_url_root = DOL_URL_ROOT;

    $php_self = $_SERVER['PHP_SELF'];
    $php_self.= $_SERVER["QUERY_STRING"] ? '?' . $_SERVER["QUERY_STRING"] : '';

    // Title
    $title = 'Speedealing ' . DOL_VERSION;

    // Select templates
    $template_dir = DOL_DOCUMENT_ROOT . "/core/tpl/";

    $conf->css = "/theme/" . (GETPOST('theme') ? GETPOST('theme', 'alpha') : $conf->theme) . "/style.css.php?lang=" . $langs->defaultlang;
    $conf_css = DOL_URL_ROOT . $conf->css;

    // Set cookie for timeout management
    $prefix = dol_getprefix();
    $sessiontimeout = 'DOLSESSTIMEOUT_' . $prefix;
    if (!empty($conf->global->MAIN_SESSION_TIMEOUT))
        setcookie($sessiontimeout, $conf->global->MAIN_SESSION_TIMEOUT, 0, "/", '', 0);

    if (GETPOST('urlfrom', 'alpha'))
        $_SESSION["urlfrom"] = GETPOST('urlfrom', 'alpha');
    else
        unset($_SESSION["urlfrom"]);

    // Show logo (search in order: small company logo, large company logo, theme logo, common logo)
    $width = 0;

    // Home message
    if (!empty($conf->global->MAIN_HOME)) {
        $i = 0;
        while (preg_match('/__\(([a-zA-Z]+)\)__/i', $conf->global->MAIN_HOME, $reg) && $i < 100) {
            $conf->global->MAIN_HOME = preg_replace('/__\(' . $reg[1] . '\)__/i', $langs->trans($reg[1]), $conf->global->MAIN_HOME);
            $i++;
        }
    }
    $main_home = dol_htmlcleanlastbr($conf->global->MAIN_HOME);

    // Google AD
    $main_google_ad_client = ((!empty($conf->global->MAIN_GOOGLE_AD_CLIENT) && !empty($conf->global->MAIN_GOOGLE_AD_SLOT)) ? 1 : 0);

    $dol_loginmesg = (!empty($_SESSION["dol_loginmesg"]) ? $_SESSION["dol_loginmesg"] : '');
    $favicon = DOL_URL_ROOT . '/theme/' . $conf->theme . '/img/favicon.ico';
    $jquerytheme = 'smoothness';

    include($template_dir . 'login.tpl.php'); // To use native PHP


    $_SESSION["dol_loginmesg"] = '';
}

/**
 *  Fonction pour initialiser un salt pour la fonction crypt.
 *
 *  @param		int		$type		2=>renvoi un salt pour cryptage DES
 * 									12=>renvoi un salt pour cryptage MD5
 * 									non defini=>renvoi un salt pour cryptage par defaut
 * 	@return		string				Salt string
 */
function makesalt($type = CRYPT_SALT_LENGTH) {
    dol_syslog("makesalt type=" . $type);
    switch ($type) {
        case 12: // 8 + 4
            $saltlen = 8;
            $saltprefix = '$1$';
            $saltsuffix = '$';
            break;
        case 8:  // 8 (Pour compatibilite, ne devrait pas etre utilise)
            $saltlen = 8;
            $saltprefix = '$1$';
            $saltsuffix = '$';
            break;
        case 2:  // 2
        default:  // by default, fall back on Standard DES (should work everywhere)
            $saltlen = 2;
            $saltprefix = '';
            $saltsuffix = '';
            break;
    }
    $salt = '';
    while (dol_strlen($salt) < $saltlen)
        $salt.=chr(mt_rand(64, 126));

    $result = $saltprefix . $salt . $saltsuffix;
    dol_syslog("makesalt return=" . $result);
    return $result;
}

/**
 *  Encode or decode database password in config file
 *
 *  @param   	int		$level   	Encode level: 0 no encoding, 1 encoding
 * 	@return		int					<0 if KO, >0 if OK
 */
function encodedecode_dbpassconf($level = 0) {
    dol_syslog("encodedecode_dbpassconf level=" . $level, LOG_DEBUG);
    $config = '';
    $passwd = '';
    $passwd_crypted = '';

    if ($fp = fopen(DOL_DOCUMENT_ROOT . '/conf/conf.php', 'r')) {
        while (!feof($fp)) {
            $buffer = fgets($fp, 4096);

            $lineofpass = 0;

            if (preg_match('/^[^#]*dolibarr_main_db_encrypted_pass[\s]*=[\s]*(.*)/i', $buffer, $reg)) { // Old way to save crypted value
                $val = trim($reg[1]); // This also remove CR/LF
                $val = preg_replace('/^["\']/', '', $val);
                $val = preg_replace('/["\'][\s;]*$/', '', $val);
                if (!empty($val)) {
                    $passwd_crypted = $val;
                    $val = dol_decode($val);
                    $passwd = $val;
                    $lineofpass = 1;
                }
            } elseif (preg_match('/^[^#]*dolibarr_main_db_pass[\s]*=[\s]*(.*)/i', $buffer, $reg)) {
                $val = trim($reg[1]); // This also remove CR/LF
                $val = preg_replace('/^["\']/', '', $val);
                $val = preg_replace('/["\'][\s;]*$/', '', $val);
                if (preg_match('/crypted:/i', $buffer)) {
                    $val = preg_replace('/crypted:/i', '', $val);
                    $passwd_crypted = $val;
                    $val = dol_decode($val);
                    $passwd = $val;
                } else {
                    $passwd = $val;
                    $val = dol_encode($val);
                    $passwd_crypted = $val;
                }
                $lineofpass = 1;
            }

            // Output line
            if ($lineofpass) {
                // Add value at end of file
                if ($level == 0) {
                    $config .= '$dolibarr_main_db_pass=\'' . $passwd . '\';' . "\n";
                }
                if ($level == 1) {
                    $config .= '$dolibarr_main_db_pass=\'crypted:' . $passwd_crypted . '\';' . "\n";
                }

                //print 'passwd = '.$passwd.' - passwd_crypted = '.$passwd_crypted;
                //exit;
            } else {
                $config .= $buffer;
            }
        }
        fclose($fp);

        // Write new conf file
        $file = DOL_DOCUMENT_ROOT . '/conf/conf.php';
        if ($fp = @fopen($file, 'w')) {
            fputs($fp, $config);
            fclose($fp);
            // It's config file, so we set read permission for creator only.
            // Should set permission to web user and groups for users used by batch
            //@chmod($file, octdec('0600'));

            return 1;
        } else {
            dol_syslog("encodedecode_dbpassconf Failed to open conf.php file for writing", LOG_WARNING);
            return -1;
        }
    } else {
        dol_syslog("encodedecode_dbpassconf Failed to read conf.php", LOG_ERR);
        return -2;
    }
}

/**
 * Return a generated password using default module
 *
 * @param		boolean		$generic		true=Create generic password (a MD5 string), false=Use the configured password generation module
 * @return		string						New value for password
 */
function getRandomPassword($generic = false) {
    global $db, $conf, $langs, $user;

    $generated_password = '';
    if ($generic)
        $generated_password = dol_hash(mt_rand());
    else if ($conf->global->USER_PASSWORD_GENERATED) {
        $nomclass = "modGeneratePass" . ucfirst($conf->global->USER_PASSWORD_GENERATED);
        $nomfichier = $nomclass . ".class.php";
        //print DOL_DOCUMENT_ROOT."/core/modules/security/generate/".$nomclass;
        require_once(DOL_DOCUMENT_ROOT . "/core/modules/security/generate/" . $nomfichier);
        $genhandler = new $nomclass($db, $conf, $langs, $user);
        $generated_password = $genhandler->getNewGeneratedPassword();
        unset($genhandler);
    }

    return $generated_password;
}

?>