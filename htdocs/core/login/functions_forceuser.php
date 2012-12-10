<?php
/* Copyright (C) 2007 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *
 */

/**
 * \file       htdocs/core/login/functions_forceuser.php
 * \ingroup    core
 * \brief      Authentication functions for forceuser
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
function check_user_password_forceuser($usertotest,$passwordtotest,$entitytotest)
{
	// Variable dolibarr_auto_user must be defined in conf.php file
	global $dolibarr_auto_user;

	dol_syslog("functions_forceuser::check_user_password_forceuser");

	$login=$dolibarr_auto_user;
	if (empty($login)) $login='auto';

	if ($_SESSION["dol_loginmesg"]) $login='';

	return $login;
}


?>