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
 */

/**
 * \file       htdocs/core/login/functions_http.php
 * \ingroup    core
 * \brief      Authentication functions for HTTP Basic
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
function check_user_password_http($usertotest,$passwordtotest,$entitytotest)
{
	dol_syslog("functions_http::check_user_password_http _SERVER[REMOTE_USER]=".(empty($_SERVER["REMOTE_USER"])?'':$_SERVER["REMOTE_USER"]));

	$login='';
	if (! empty($_SERVER["REMOTE_USER"]))
	{
		$login=$_SERVER["REMOTE_USER"];
	}

	return $login;
}


?>