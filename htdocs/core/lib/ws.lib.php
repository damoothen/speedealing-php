<?php
/* Copyright (C) 2011 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  \file		htdocs/core/lib/ws.lib.php
 *  \ingroup	webservices
 *  \brief		Set of function for manipulating web services
 */


/**
 *  Check authentication array and set error, errorcode, errorlabel
 *
 *  @param	array	$authentication     Array with authentication informations ('login'=>,'password'=>,'entity'=>,'securekey'=>)
 *  @param 	int		&$error				Number of errors
 *  @param  string	&$errorcode			Error string code
 *  @param  string	&$errorlabel		Error string label
 *  @return User						Return user object identified by login/pass/entity into authentication array
 */
function check_authentication($authentication,&$error,&$errorcode,&$errorlabel)
{
    global $db,$conf,$langs;
    global $dolibarr_main_authentication,$dolibarr_auto_user;

    $fuser=new User($db);

    if (! $error && ($authentication['securekey'] != $conf->global->WEBSERVICES_KEY))
    {
        $error++;
        $errorcode='BAD_VALUE_FOR_SECURITY_KEY'; $errorlabel='Value provided into securekey entry field does not match security key defined in Webservice module setup';
    }

    if (! $error && ! empty($authentication['entity']) && ! is_numeric($authentication['entity']))
    {
        $error++;
        $errorcode='BAD_PARAMETERS'; $errorlabel="The entity parameter must be empty (or filled with numeric id of instance if multicompany module is used).";
    }

    if (! $error)
    {
        $result=$fuser->fetch('',$authentication['login'],'',0);
        if ($result < 0)
        {
            $error++;
            $errorcode='ERROR_FETCH_USER'; $errorlabel='A technical error occurred during fetch of user';
        }
        else if ($result == 0)
        {
            $error++;
            $errorcode='BAD_CREDENTIALS'; $errorlabel='Bad value for login or password';
        }

		if (! $error && $fuser->statut == 0)
		{
			$error++;
			$errorcode='ERROR_USER_DISABLED'; $errorlabel='This user has been locked or disabled';
		}

    	// Validation of login
		if (! $error)
		{
			$fuser->getrights();	// Load permission of user

        	// Authentication mode
        	if (empty($dolibarr_main_authentication)) $dolibarr_main_authentication='http,dolibarr';
        	// Authentication mode: forceuser
        	if ($dolibarr_main_authentication == 'forceuser' && empty($dolibarr_auto_user)) $dolibarr_auto_user='auto';
        	// Set authmode
        	$authmode=explode(',',$dolibarr_main_authentication);

            include_once DOL_DOCUMENT_ROOT.'/core/lib/security2.lib.php';
        	$login = checkLoginPassEntity($authentication['login'],$authentication['password'],$authentication['entity'],$authmode);
			if (empty($login))
			{
			    $error++;
                $errorcode='BAD_CREDENTIALS'; $errorlabel='Bad value for login or password';
			}
		}
    }

    return $fuser;
}

?>
