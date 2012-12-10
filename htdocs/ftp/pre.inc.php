<?php
/* Copyright (C) 2008-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *		\file 		htdocs/ftp/pre.inc.php
 *		\ingroup    ftp
 *		\brief      File to manage left menu for FTP module
 */

require (realpath(dirname(__FILE__)) . "/../main.inc.php");

$user->getrights('ecm');

/**
 * Replace the default llxHeader function
 *
 * @param 	string	$head			Optionnal head lines
 * @param 	string	$title			HTML title
 * @param 	string	$help_url		Link to online url help
 * @param 	string	$morehtml		More content into html header
 * @param 	string 	$target			Force target on menu links
 * @param 	int    	$disablejs		More content into html header
 * @param 	int    	$disablehead	More content into html header
 * @param 	array  	$arrayofjs		Array of complementary js files
 * @param 	array  	$arrayofcss		Array of complementary css files
 * @return	none
 */
function llxHeader($head = '', $title='', $help_url='', $morehtml='', $target='', $disablejs=0, $disablehead=0, $arrayofjs='', $arrayofcss='')
{
	global $conf,$langs,$user;
	$langs->load("ftp");

	top_htmlhead($head, $title, $disablejs, $disablehead, $arrayofjs, $arrayofcss);	// Show html headers
	top_menu($head, $title, $target, $disablejs, $disablehead, $arrayofjs, $arrayofcss);	// Show html headers

	$menu = new Menu();

	$MAXFTP=20;
	$i=1;
	while ($i <= $MAXFTP)
	{
		$paramkey='FTP_NAME_'.$i;
		//print $paramkey;
		if (! empty($conf->global->$paramkey))
		{
			$link="/ftp/index.php?idmenu=".$_SESSION["idmenu"]."&numero_ftp=".$i;

			$menu->add($link, dol_trunc($conf->global->$paramkey,24));
		}
		$i++;
	}


	left_menu($menu->liste, $help_url, $morehtml, '', 1);
	main_area();
}
?>
