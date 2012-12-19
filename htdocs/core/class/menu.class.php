<?php
/* Copyright (C) 2002-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  \file       htdocs/core/class/menu.class.php
 *  \ingroup    core
 *  \brief      Fichier de la classe de gestion du menu gauche
 */


/**
 *	Class to manage left menus
 */
class Menu
{
    var $liste;

    /**
	 *	Constructor
     */
    function __construct()
    {
      	$this->liste = array();
    }

    /**
     * Clear property ->liste
     *
     * @return	void
     */
    function clear()
    {
        $this->liste = array();
    }

    /**
     * Add a menu entry into this->liste (at end)
     *
     * @param	string	$url        Url to follow on click
     * @param   string	$titre      Label of menu to add
     * @param   string	$level      Level of menu to add
     * @param   int		$enabled    Menu active or not
     * @param   string	$target		Target lien
     * @param	string	$mainmenu	Main menu ('home', 'companies', 'products', ...)
     * @param	string	$leftmenu	Left menu ('setup', 'system', 'admintools', ...)
     * @return	void
     */
    function add($url, $titre, $level=0, $enabled=1, $target='',$mainmenu='',$leftmenu='')
    {
        $this->liste[]=array('url'=>$url,'titre'=>$titre,'level'=>$level,'enabled'=>$enabled,'target'=>$target,'mainmenu'=>$mainmenu,'leftmenu'=>$leftmenu);
    }

    /**
     * Insert a menu entry into this->liste
     *
     * @param	int		$idafter	Array key after which inserting new entry
     * @param	string	$url        Url to follow on click
     * @param   string	$titre      Label of menu to add
     * @param   string	$level      Level of menu to add
     * @param   int		$enabled    Menu active or not
     * @param   string	$target		Target lien
     * @param	string	$mainmenu	Main menu ('home', 'companies', 'products', ...)
     * @param	string	$leftmenu	Left menu ('setup', 'system', 'admintools', ...)
     * @return	void
     */
    function insert($idafter, $url, $titre, $level=0, $enabled=1, $target='',$mainmenu='',$leftmenu='')
    {
        $array_start = array_slice($this->liste,0,($idafter+1));
        $array_new   = array(0=>array('url'=>$url,'titre'=>$titre,'level'=>$level,'enabled'=>$enabled,'target'=>$target,'mainmenu'=>$mainmenu,'leftmenu'=>$leftmenu));
        $array_end   = array_slice($this->liste,($idafter+1));
        $this->liste=array_merge($array_start,$array_new,$array_end);
    }

    /**
     * Remove a menu entry from this->liste
     *
     * @return	void
     */
    function remove_last()
    {
    	if (count($this->liste) > 1) array_pop($this->liste);
    }

}
