<?php

/* Copyright (C) 2007      Patrick Raguin       <patrick.raguin@gmail.com>
 * Copyright (C) 2009      Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2008-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
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

class MenuAuguria extends nosqlDocument {

	protected $db;
	var $require_left = array("auguria_backoffice");  // Si doit etre en phase avec un gestionnaire de menu gauche particulier
	var $hideifnotallowed = 0;   // Put 0 for back office menu, 1 for front office menu
	var $atarget = ""; // Valeur du target a utiliser dans les liens
	var $topmenu = array(); // array of level 0
	var $submenu = array(); // array of level > 0
	var $selected = array();  // array of selected
	var $idmenu;   // Id of selected menu

	/**
	 *  Constructor
	 *
	 *  @param      DoliDb		$db      Database handler
	 */

	function __construct($db) {
		global $conf;

		parent::__construct($db);

		$tabMenu = array();

		$topmenu = $this->getView("list", array(), true);
		$submenu = $this->getView("submenu", array(), true);

		$this->topmenu = $topmenu->rows;

		// Construct submenu
		foreach ($submenu->rows as $key => $aRow) {
			$menu = $aRow->value;
			$newTabMenu = $this->verifyMenu($menu);
			if ($newTabMenu->enabled == true && $newTabMenu->perms == true) {
				$this->submenu[$aRow->key[0]][] = $newTabMenu;
			}
		}

		return 1;
	}

	/**
	 *  Show menu
	 *
	 * 	@return	void
	 */
	function showmenuTop() {
		$this->print_auguria_menu($this->hideifnotallowed);
	}

	/**
	 * Core function to output top menu auguria
	 *
	 * @param 	DoliDB	$db				Database handler
	 * @param 	string	$atarget		Target
	 * @param 	int		$type_user     	0=Internal,1=External,2=All
	 * @return	void
	 */
	function print_auguria_menu($type_user) {
		global $user, $langs;

		// On sauve en session le menu principal choisi
		if (isset($_GET["idmenu"])) {
			dol_setcache("idmenu", $_GET["idmenu"]);
			$this->idmenu = $_GET["idmenu"];
		}

		$tabMenu = array();

		$this->print_start_menu_array_auguria();

		//print_r($result);exit;
		$i = 0;
		$selectnav = array();
		foreach ($this->topmenu AS $aRow) {
			$newTabMenu = $aRow->value;
			$newTabMenu = $this->verifyMenu($newTabMenu);

			if ($newTabMenu->enabled == true) {
				$idsel = (empty($newTabMenu->_id) ? 'none' : $newTabMenu->_id);
				if ($newTabMenu->perms == true) { // Is allowed
					$url = $this->menuURL($newTabMenu, $newTabMenu->_id);

					// Define the class (top menu selected or not)
					if (empty($this->idmenu))
						$this->idmenu = dol_getcache('idmenu'); // For cache optimisation

					// Submenu level 1
					$selected = $this->print_submenu($newTabMenu, 1);

					print '</li>';
					$i++;
				}
			}
		}
		$this->print_end_menu_array_auguria();

		print "\n";
	}

	/**
	 * Output start menu array
	 *
	 * @return	void
	 */
	function print_start_menu_array_auguria() {
		global $conf;
		print '<section class="navigable">';
		print '<ul class="big-menu">';
	}

	/**
	 * Output menu array
	 *
	 * @return	void
	 */
	function print_end_menu_array_auguria() {
		global $conf;
		print '</ul>';
		print '</section>';
	}

	/**
	 * Core function to output submenu auguria
	 *
	 * @param	string		$menu		    menu father
	 * @param       int		$level          Level for the navigation
	 * @return	void
	 */
	function print_submenu($menuFather, $level) {
		global $user, $conf, $langs;

		$selectnow = false;

		$result = $this->submenu[$menuFather->_id];

		if (count($result) == 0) { // be a <li> link menu
			print '<li>';
			if (!empty($this->idmenu) && $this->menuSelected($menuFather))
				$classname = "current navigable-current";

			$url = $this->menuURL($menuFather, $menuFather->_id);
			print '<a class="' . $classname . '" href="' . $url . '">';
			print $menuFather->title;
			print '</a>';
			print '</li>';
			return false;
		}

		print '<li class="with-right-arrow">';
		print '<span><span class="list-count">'.count($result).'</span>'.$menuFather->title.'</span>';
		print '<ul class="big-menu">';
		
		foreach ($result as $aRow) {
			$menu = $aRow;
			$selected = $this->print_submenu($menu, ($level + 1));

		}

		print '</ul>';
		print '</li>';

		return $selectnow;
	}

	/**
	 * Core function to verify perms of menu
	 *
	 * @param	object		$newTabMenu         One Menu Entry
	 * @return	$newTabMenu with good permissions
	 */
	function verifyMenu($newTabMenu) {
		global $langs, $user;

		// Define $right
		$perms = true;
		if ($newTabMenu->perms) {
            $perms = verifCond($newTabMenu->perms);
			//print "verifCond rowid=".$menu['rowid']." ".$menu['perms'].":".$perms."<br>\n";
		}

		// Define $enabled
		$enabled = true;
		if ($newTabMenu->enabled) {
			$enabled = verifCond($newTabMenu->enabled);
			if (preg_match('/^\$leftmenu/', $newTabMenu->enabled))
				$enabled = 1;
			//print "verifCond rowid=".$menu['rowid']." ".$menu['enabled'].":".$enabled."<br>\n";
		}

		// Define $title
		if ($enabled) {
			$title = $langs->trans($newTabMenu->title);
			if ($title == $newTabMenu->title) {   // Translation not found
				if (!empty($newTabMenu->langs)) { // If there is a dedicated translation file
					$langs->load($newTabMenu->langs);
				}

				if (preg_match("/\//", $newTabMenu->title)) { // To manage translation when title is string1/string2
					$tab_titre = explode("/", $newTabMenu->title);
					$title = $langs->trans($tab_titre[0]) . "/" . $langs->trans($tab_titre[1]);
				} else if (preg_match('/\|\|/', $newTabMenu->title)) { // To manage different translation
					$tab_title = explode("||", $newTabMenu->title);
					$alt_title = explode("@", $tab_title[1]);
					$title_enabled = verifCond($alt_title[1]);
					$title = ($title_enabled ? $langs->trans($alt_title[0]) : $langs->trans($tab_title[0]));
				} else {
					$title = $langs->trans($newTabMenu->title);
				}
			}
		}
		$newTabMenu->enabled = $enabled;
		$newTabMenu->title = $title;
		$newTabMenu->perms = $perms;

		return $newTabMenu;
	}

	/**
	 * Core function generate URL for the menu
	 *
	 * @param	object		$newTabMenu         One Menu Entry
	 * @return	url
	 */
	function menuURL($newTabMenu, $_id) {
		global $user;

		// Define url
		if (preg_match("/^(http:\/\/|https:\/\/)/i", $newTabMenu->url)) {
			$url = $newTabMenu->url;
		} else {
			$url = dol_buildpath($newTabMenu->url, 1);
			if (!preg_match('/mainmenu/i', $url) || !preg_match('/leftmenu/i', $url)) {
				if (!preg_match('/\?/', $url))
					$url.='?';
				else
					$url.='&';
				$url.='idmenu=' . $_id;
			}
			//$url.="idmenu=".$newTabMenu[$i]['rowid'];    // Already done by menuLoad
		}
		$url = preg_replace('/__LOGIN__/', $user->login, $url);

		return $url;
	}

	/**
	 * Core function to test if menu is selected
	 *
	 * @param	object		$newTabMenu         One Menu Entry
	 * @param	session		$session            Session Var
	 * @return	true if selected
	 */
	function menuSelected($newTabMenu) {
		if ($newTabMenu->_id == $this->idmenu)
			return true;

		$result = $this->submenu[$newTabMenu->_id];
		if (count($result) == 0)
			return false;

		foreach ($result as $aRow) {
			if ($this->menuSelected($aRow))
				return true;
		}
		return false;
	}

}

?>
