<?php
/* Copyright (C) 2007-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2007-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2010-2011 Juanjo Menent		<jmenent@2byte.es>
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
function check_user_password_dolibarr($usertotest,$passwordtotest,$entitytotest=1)
{
	global $db,$conf,$langs;
	global $mc;

	dol_syslog("functions_dolibarr::check_user_password_dolibarr usertotest=".$usertotest);

	$login='';

	if (! empty($usertotest))
	{
	
		try {
			$host = substr($conf->couchdb->host,7);
	
			$client = new couchClient('http://'.$usertotest.':'.$passwordtotest.'@'.$host.':'.$conf->couchdb->port.'/',$conf->couchdb->name, array("cookie_auth"=>TRUE));
			$_SESSION['couchdb']=$client->getSessionCookie();
		} catch (Exception $e)
		{
			print $e->getMessage();exit;
		}
		
		if(strlen($_SESSION['couchdb']) < 15)
		{
			sleep(1);
			$langs->load('main');
			$langs->load('errors');
			$_SESSION["dol_loginmesg"]=$langs->trans("ErrorBadLoginPassword");
		}
		else
			$login=$usertotest;
	}

	return $login;
}


?>