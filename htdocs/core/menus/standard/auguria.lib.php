<?php
/* Copyright (C) 2010		Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2010-2012	Regis Houssin		<regis@dolibarr.fr>
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
 * or see http://www.gnu.org/
 */

/**
 *  \file		htdocs/core/menus/standard/auguria.lib.php
 *  \brief		Library for file auguria menus
 */



/**
 * Core function to output top menu auguria
 *
 * @param 	DoliDB	$db				Database handler
 * @param 	string	$atarget		Target
 * @param 	int		$type_user     	0=Internal,1=External,2=All
 * @return	void
 */
function print_auguria_menu($db,$atarget,$type_user)
{
	global $user,$conf,$langs;

	// On sauve en session le menu principal choisi
	if (isset($_GET["idmenu"]))   $_SESSION["idmenu"]=$_GET["idmenu"];

	$tabMenu=array();
        
        $result = $conf->couchdb->getView("menu","list");

	print_start_menu_array_auguria();

        //print_r($result);exit;
        $i=0;
	foreach($result->rows AS $aRow) 
	{
                $newTabMenu = $aRow->value;
                $newTabMenu = verifyMenu($newTabMenu);
                
                if ($newTabMenu->enabled == true)
                {
                    $idsel=(empty($newTabMenu->_id)?'none':$newTabMenu->_id);
                    if ($newTabMenu->perms == true)	// Is allowed
                    {
                        $url = menuURL($newTabMenu);
                        
                        //print $url;exit;
        
                        // Define the class (top menu selected or not)
                        $classname='mb_parent';
                        if($i==0)
                            $classname.=' first_el';
                        if (! empty($_SESSION['idmenu']) && menuSelected($newTabMenu, $_SESSION['idmenu'])) $classname.=' pageselected';

                        print '<li>';
                        print '<a class="'.$classname.'" href="'.$url.'">';
                        print $newTabMenu->title;
                        print '</a>';
                        // Submenu level 1
                        if(isset($newTabMenu->submenu))
                            print_submenu($newTabMenu->submenu);
                                
                        print '</li>';
                        $i++;
                    }
		}
	}

	print_end_menu_array_auguria();

	print "\n";
}


/**
 * Output start menu entry
 *
 * @return	void
 */
function print_start_menu_array_auguria()
{
	global $conf;
        print '<nav id="smoothmenu_h" class="ddsmoothmenu tinyNav">';
        print '<ul class="cf">';
}
/**
 * Output menu array
 *
 * @return	void
 */
function print_end_menu_array_auguria()
{
	global $conf;
	print '</ul>';
        print '</nav>';
        print '<ul id="breadcrumbs" class="cf">
            <li>You are here:</li>
            <li><a href="#">Content</a></li>
            <li><a href="#">Article</a></li>
            <li><span>Lorem Ipsum&hellip;</span></li>';
        print '</ul>'."\n";
        print '</div>';
}

/**
 * Core function to output submenu auguria
 *
 * @param	DoliDB		$db                  Database handler
 * @param 	array		$menu_array_before   Table of menu entries to show before entries of menu handler
 * @param   array		$menu_array_after    Table of menu entries to show after entries of menu handler
 * @return	void
 */
function print_submenu($submenu)
{
    global $user,$conf,$langs;
    
    print '<ul style="display:none">';
    foreach ($submenu as $aRow)
    {
        $menu = $aRow;
        //print_r($menu);exit;
        $newTabMenu = verifyMenu($menu);
                
        if ($newTabMenu->enabled == true)
        {
            $idsel=(empty($newTabMenu->_id)?'none':$newTabMenu->_id);
            if ($newTabMenu->perms == true)	// Is allowed
            {
                $url = menuURL($newTabMenu);
                       
                //print $url;exit;
        
                // Define the class (top menu selected or not)
                $classname='mb_parent';
                if($i==0)
                    $classname.=' first_el';
                if (! empty($_SESSION['idmenu']) && menuSelected($newTabMenu, $_SESSION['idmenu'])) $classname.=' pageselected';

                print '<li>';
                print '<a class="'.$classname.'" href="'.$url.'">';
                print '<!-- Add menu entry with mainmenu='.$newTabMenu->_id.' -->'."\n";
                print $newTabMenu->title;
                print '</a>';
                // Submenu level 1
                if(isset($newTabMenu->submenu))
                    print_submenu($newTabMenu->submenu);
                                
                print '</li>';
                $i++;
            }
        }
    }

    print '</ul>';

    return count($submenu);
}

/**
 * Core function to verify perms of menu
 *
 * @param	object		$newTabMenu         One Menu Entry
 * @return	$newTabMenu with good permissions
 */

function verifyMenu($newTabMenu)
{
    global $langs, $user;


    // Define $right
    $perms = true;
    if ($newTabMenu->perms)
    {
        $perms = verifCond($newTabMenu->perms);
        //print "verifCond rowid=".$menu['rowid']." ".$menu['perms'].":".$perms."<br>\n";
    }

    // Define $enabled
    $enabled = true;
    if ($newTabMenu->enabled)
    {
        $enabled = verifCond($newTabMenu->enabled);
        if (preg_match('/^\$leftmenu/',$newTabMenu->enabled)) $enabled=1;
        //print "verifCond rowid=".$menu['rowid']." ".$menu['enabled'].":".$enabled."<br>\n";
    }

    // Define $title
    if ($enabled)
    {
        $title = $langs->trans($newTabMenu->title);
        if ($title == $newTabMenu->title)   // Translation not found
        {
            if (! empty($newTabMenu->langs))    // If there is a dedicated translation file
            {
                $langs->load($newTabMenu->langs);
            }

            if (preg_match("/\//",$newTabMenu->title)) // To manage translation when title is string1/string2
            {
                $tab_titre = explode("/",$newTabMenu->title);
                $title = $langs->trans($tab_titre[0])."/".$langs->trans($tab_titre[1]);
            }
            else if (preg_match('/\|\|/',$newTabMenu->title)) // To manage different translation
            {
                $tab_title = explode("||",$newTabMenu->title);
                $alt_title = explode("@",$tab_title[1]);
                $title_enabled = verifCond($alt_title[1]);
                $title = ($title_enabled ? $langs->trans($alt_title[0]) : $langs->trans($tab_title[0]));
            }
            else
            {
                $title = $langs->trans($newTabMenu->title);
            }
        }
    }
    $newTabMenu->enabled=$enabled;
    $newTabMenu->title=$title;
    $newTabMenu->perms=$perms;
    
    return $newTabMenu;
}


/**
 * Core function generate URL for the menu
 *
 * @param	object		$newTabMenu         One Menu Entry
 * @return	url
 */
function menuURL($newTabMenu)
{
    global $user;
    
    // Define url
    if (preg_match("/^(http:\/\/|https:\/\/)/i",$newTabMenu->url))
    {
        $url = $newTabMenu->url;
    }
    else
    {
        $url=dol_buildpath($newTabMenu->url,1);
	if (! preg_match('/mainmenu/i',$url) || ! preg_match('/leftmenu/i',$url))
	{
            if (! preg_match('/\?/',$url)) $url.='?';
            else $url.='&';
	    $url.='idmenu='.$newTabMenu->_id;
	}
	//$url.="idmenu=".$newTabMenu[$i]['rowid'];    // Already done by menuLoad
     }
     $url=preg_replace('/__LOGIN__/',$user->login,$url);
     
     return $url;
}

/**
 * Core function to test if menu is selected
 *
 * @param	object		$newTabMenu         One Menu Entry
 * @param	session		$session            Session Var
 * @return	true if selected
 */
function menuSelected($newTabMenu,$session)
{
    if($newTabMenu->_id==$session)
        return true;
    
    if(isset($newTabMenu->submenu))
    {
        foreach($newTabMenu->submenu AS $aRow)
        {
            if(menuSelected($aRow, $session))
                return true;
        }
    }
    return false;
}

?>
