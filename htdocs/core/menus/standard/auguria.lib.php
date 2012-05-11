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
	global $user,$couchdb,$langs;

	// On sauve en session le menu principal choisi
	if (isset($_GET["idmenu"]))   $_SESSION["idmenu"]=$_GET["idmenu"];

	$tabMenu=array();
        
	try {	
	    $result = $couchdb->getView("menu","list");
	    $submenu_tmp = $couchdb->getView("menu","submenu");
	} catch (Exception $e) {
	    $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
            dol_print_error('',$error);
            exit;
	}

	// Construct submenu
	foreach ($submenu_tmp->rows as $key => $aRow)
	{
	    $submenu[$aRow->key[0]][]= $aRow->value;
	}
	//print_r($submenu);exit;
	
	unset($submenu_tmp);

	print_start_menu_array_auguria();

        //print_r($result);exit;
        $i=0;
        $selectnav = array();
	foreach($result->rows AS $aRow) 
	{
                $newTabMenu = $aRow->value;
                $newTabMenu = verifyMenu($newTabMenu);
                
                if ($newTabMenu->enabled == true)
                {
                    $idsel=(empty($newTabMenu->_id)?'none':$newTabMenu->_id);
                    if ($newTabMenu->perms == true)	// Is allowed
                    {
                        $url = menuURL($newTabMenu, $newTabMenu->_id);
                        
                        //print $url;exit;
        
                        // Define the class (top menu selected or not)
                        $classname='mb_parent';
                        if($i==0)
                            $classname.=' first_el';
                        if (! empty($_SESSION['idmenu']) && menuSelected($newTabMenu, $newTabMenu->_id))
                        {
                            $classname.=' pageselected';
                            $selectnav[0]->name = $newTabMenu->title;
                            $selectnav[0]->url = $url;
                        }

                        print '<li>';
                        print '<a class="'.$classname.'" href="'.$url.'">';
                        print $newTabMenu->title;
                        print '</a>';
                        // Submenu level 1
                        $selected = print_submenu($submenu,$newTabMenu->_id,$selectnav,1);
			if($selected)
			{
			    $selectnav[0]->name = $newTabMenu->title;
			    $selectnav[0]->url = $url;
			}

                        print '</li>';
                        $i++;
                    }
		}
	}
	print_end_menu_array_auguria($selectnav);

	print "\n";
}


/**
 * Output start menu array
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
function print_end_menu_array_auguria($selectnav)
{
	global $conf;
	print '</ul>';
        print '</nav>';
        print '<ul id="breadcrumbs" class="cf">
            <li>You are here:</li>';
        
        for($i=0;$i < count($selectnav);$i++)
        {
            print '<li><a href="'.$selectnav[$i]->url.'">'.$selectnav[$i]->name.'</a></li>';
        }
        //print '<li><span>'.$selectnav[count($selectnav)-1]->name.'</span></a></li>';
        print '</ul>'."\n";
        print '</div>';
}

/**
 * Core function to output submenu auguria
 *
 * @param	array		$submenu            All entries menu
 * @param	string		$id		    Id name menu father
 * @param 	array		$selectnav          Array of selected navigation
 * @param       int		$level              Level for the navigation
 * @return	void
 */
function print_submenu(&$submenu, $id, &$selectnav, $level)
{
    global $user,$conf,$langs;
    
    $selectnow = false;
    
    $result = $submenu[$id];
    
    if(count($result)==0)
	return false;
    
    print '<ul style="display:none">';
    foreach ($result as $aRow)
    {
        $menu = $aRow;
        //print_r($menu);exit;
        $newTabMenu = verifyMenu($menu);
                
        if ($newTabMenu->enabled == true)
        {
            //$idsel=(empty($newTabMenu_id)?'none':$newTabMenu->_id);
            if ($newTabMenu->perms == true)	// Is allowed
            {
                $url = menuURL($newTabMenu, $menu->_id);
                       
                //print $url;exit;
        
                // Define the class (top menu selected or not)
                $classname='mb_parent';
                if($i==0)
                    $classname.=' first_el';
                if (! empty($_SESSION['idmenu']) && menuSelected($newTabMenu,$menu->_id))
                {
                    $classname.=' pageselected';
                    $selectnav[$level]->name = $newTabMenu->title;
                    $selectnav[$level]->url = $url;
		    $selectnow = true;
                }

                print '<li>';
                print '<a class="'.$classname.'" href="'.$url.'">';
                print '<!-- Add menu entry with mainmenu='.$menu->_id.' -->'."\n";
                print $newTabMenu->title;
                print '</a>';
                // Submenu level 1
                //if(isset($newTabMenu->submenu))
                $selected = print_submenu($submenu,$newTabMenu->_id, $selectnav, ($level+1));
		if($selected)
		{
		    $selectnav[$level]->name = $newTabMenu->title;
		    $selectnav[$level]->url = $url;
		    $selectnow = true;
		}
                print '</li>';
                $i++;
            }
        }
    }

    print '</ul>';

    return $selectnow;
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
function menuURL($newTabMenu, $_id)
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
	    $url.='idmenu='.$_id;
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
function menuSelected($newTabMenu,$_id)
{   
    if($_id==$_SESSION['idmenu'])
        return true;
    
    /*if(isset($newTabMenu->fk_menu))
    {
	if(menuSelected($aRow, $key))
	    return true;
    }*/
    else
	return false;
}

?>
