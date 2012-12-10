<?php
/* Copyright (C) 2010 Laurent Destailleur         <eldy@users.sourceforge.net>
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
 *	\file			htdocs/core/class/google.class.php
 *	\brief			A set of functions for using Google APIs
 */

/**
 * Class to manage Google API
 */
class GoogleAPI
{
	var $db;
	var $error;

	var $key;

	/**
	 * Constructor
	 *
	 * @param 	DoliDB		$db			Database handler
	 * @param	string		$key		Google key
	 * @return 	GoogleAPI
	 */
	function __construct($db,$key)
	{
		$this->db=$db;
		$this->key=$key;
	}


	/**
	 *  Return geo coordinates of an address
	 *
	 *  @param	string	$address	Address
	 * 								Example: 68 Grande rue Charles de Gaulle,+94130,+Nogent sur Marne,+France
	 *								Example: 188, rue de Fontenay,+94300,+Vincennes,+France
	 *	@return	string				Coordinates
	 */
	function getGeoCoordinatesOfAddress($address)
	{
		global $conf;

		$i=0;

		// Desired address
		$urladdress = "http://maps.google.com/maps/geo?q=".urlencode($address)."&output=xml&key=".$this->key;

		// Retrieve the URL contents
		$page = file_get_contents($urladdress);

		$code = strstr($page, '<coordinates>');
		$code = strstr($code, '>');
		$val=strpos($code, "<");
		$code = substr($code, 1, $val-1);
		//print $code;
		//print "<br>";
		$latitude = substr($code, 0, strpos($code, ","));
		$longitude = substr($code, strpos($code, ",")+1, dol_strlen(strpos($code, ","))-3);

		// Output the coordinates
		//echo "Longitude: $longitude ',' Latitude: $latitude";

		$i++;
	}
}
