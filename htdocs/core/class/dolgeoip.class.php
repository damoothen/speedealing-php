<?php
/* Copyright (C) 2009-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  \file		htdocs/core/class/dolgeoip.class.php
 * 	\ingroup	geoip
 *  \brief		File of class to manage module geoip
 */


/**
 * 		\class      DolGeoIP
 *      \brief      Classe to manage GeoIP
 *      			Usage:
 *					$geoip=new GeoIP('country',$datfile);
 *					$geoip->getCountryCodeFromIP($ip);
 *					$geoip->close();
 */
class DolGeoIP
{
	var $gi;

	/**
	 * Constructor
	 *
	 * @param 	string	$type		'country' or 'city'
	 * @param	string	$datfile	Data file
	 * @return 	GeoIP
	 */
	function __construct($type,$datfile)
	{
		if ($type == 'country')
		{
		    // geoip may have been already included with PEAR
		    if (! function_exists('geoip_country_code_by_name')) $res=include_once GEOIP_PATH.'geoip.inc';
		}
		else if ($type == 'city')
		{
		    // geoip may have been already included with PEAR
		    if (! function_exists('geoip_country_code_by_name')) $res=include_once GEOIP_PATH.'geoipcity.inc';
		}
		else { print 'ErrorBadParameterInConstructor'; return 0; }

		// Here, function exists (embedded into PHP or exists because we made include)
		if (empty($type) || empty($datfile))
		{
			//dol_syslog("DolGeoIP::DolGeoIP parameter datafile not defined", LOG_ERR);
			$this->errorlabel='DolGeoIP constructor was called with no datafile parameter';
			//dol_print_error('','DolGeoIP constructor was called with no datafile parameter');
			print $this->errorlabel;
			return 0;
		}
		if (! file_exists($datfile))
		{
			//dol_syslog("DolGeoIP::DolGeoIP datafile ".$datfile." can not be read", LOG_ERR);
			$this->error='ErrorGeoIPClassNotInitialized';
			$this->errorlabel="Datafile ".$datfile." not found";
			print $this->errorlabel;
			return 0;
		}

		if (function_exists('geoip_open'))
		{
		    $this->gi = geoip_open($datfile,GEOIP_STANDARD);
		}
		else
		{
		    $this->gi = 'NOGI';    // We are using embedded php geoip functions
		    //print 'function_exists(geoip_country_code_by_name))='.function_exists('geoip_country_code_by_name');
		    //print geoip_database_info();
		}
	}

	/**
	 * Return in lower case the country code from an ip
	 *
	 * @param	string	$ip		IP to scan
	 * @return	string			Country code (two letters)
	 */
	function getCountryCodeFromIP($ip)
	{
		if (empty($this->gi))
		{
			return '';
		}
		if ($this->gi == 'NOGI')
		{
		    // geoip_country_code_by_addr does not exists
    		return strtolower(geoip_country_code_by_name($ip));
		}
		else
		{
		    if (! function_exists('geoip_country_code_by_addr')) return strtolower(geoip_country_code_by_name($this->gi, $ip));
		    return strtolower(geoip_country_code_by_addr($this->gi, $ip));
		}
	}

	/**
	 * Return in lower case the country code from a host name
	 *
	 * @param	string	$name	FQN of host (example: myserver.xyz.com)
	 * @return	string			Country code (two letters)
	 */
	function getCountryCodeFromName($name)
	{
		if (empty($this->gi))
		{
			return '';
		}
		return geoip_country_code_by_name($this->gi, $name);
	}

	/**
	 * Return verion of data file
	 *
	 * @return	string		Version of datafile
	 */
	function getVersion()
	{
	    if ($this->gi == 'NOGI') return geoip_database_info();
		return '';
	}

	/**
	 * Close geoip object
	 *
	 * @return	void
	 */
	function close()
	{
	    if (function_exists('geoip_close'))    // With some geoip with PEAR, geoip_close function may not exists
	    {
	        geoip_close($this->gi);
	    }
	}
}
?>
