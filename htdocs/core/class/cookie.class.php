<?php
/* Copyright (C) 2009  Regis Houssin  <regis@dolibarr.fr>
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
 *	\file       htdocs/core/class/cookie.class.php
 *	\ingroup    core
 *	\brief      File of class to manage cookies
 */


/**
 *	\class      DolCookie
 *	\brief      Class to manage cookies
 */
class DolCookie
{
	var $myKey;
	var $myCookie;
	var $myValue;
	var $myExpire;
	var $myPath;
	var $myDomain;
	var	$mySsecure;
	var $cookiearray;
	var $cookie;

	/**
	 *  Constructor
	 *
	 *  @param      string		$key      Personnal key
	 */
	function __construct($key = '')
	{
		$this->myKey = $key;
		$this->cookiearray = array();
		$this->cookie = "";
		$this->myCookie = "";
		$this->myValue = "";
	}


	/**
	 * Encrypt en create the cookie
	 *
	 * @return	void
	 */
	function cryptCookie()
	{
		if (!empty($this->myKey))
		{
			$valuecrypt = base64_encode($this->myValue);
			$max=dol_strlen($valuecrypt)-1;
			for ($f=0 ; $f <= $max; $f++)
			{
				$this->cookie .= intval(ord($valuecrypt[$f]))*$this->myKey."|";
			}
		}
		else
		{
			$this->cookie = $this->myValue;
		}

		setcookie($this->myCookie, $this->cookie, $this->myExpire, $this->myPath, $this->myDomain, $this->mySecure);
	}

	/**
	 * Decrypt the cookie
	 *
	 * @return	void
	 */
	function decryptCookie()
	{
		if (!empty($this->myKey))
		{
			$this->cookiearray = explode("|",$_COOKIE[$this->myCookie]);
			$this->myValue = "" ;
			$num = (count($this->cookiearray) - 2);
			for ($f = 0; $f <= $num; $f++)
			{
				$this->myValue .= strval(chr($this->cookiearray[$f]/$this->myKey));
			}

			return(base64_decode($this->myValue));
		}
		else
		{
			return($_COOKIE[$this->myCookie]);
		}
	}

	/**
	 * Set and create the cookie
	 *
	 * @param  	string		$cookie  	Cookie name
	 * @param  	string		$value   	Cookie value
	 * @param	string		$expire		Expiration
	 * @param	string		$path		Path of cookie
	 * @param	string		$domain		Domain name
	 * @param	int			$secure		0 or 1
	 * @return	void
	 */
	function _setCookie($cookie, $value, $expire=0, $path="/", $domain="", $secure=0)
	{
		$this->myCookie = $cookie;
		$this->myValue = $value;
		$this->myExpire = $expire;
		$this->myPath = $path;
		$this->myDomain = $domain;
		$this->mySecure = $secure;

		//print 'key='.$this->myKey.' name='.$this->myCookie.' value='.$this->myValue.' expire='.$this->myExpire;

		$this->cryptCookie();
	}

	/**
	 *  Get the cookie
	 *
	 *  @param   	string		$cookie         Cookie name
	 *  @return  	string						Decrypted value
	 */
	function _getCookie($cookie)
	{
		$this->myCookie = $cookie;

		$decryptValue = $this->decryptCookie();

		return $decryptValue;
	}

}

?>