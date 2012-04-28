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
	require_once(DOL_DOCUMENT_ROOT."/core/class/menubase.class.php");

	global $user,$conf,$langs,$dolibarr_main_db_name;

	// On sauve en session le menu principal choisi
	if (isset($_GET["mainmenu"])) $_SESSION["mainmenu"]=$_GET["mainmenu"];
	if (isset($_GET["idmenu"]))   $_SESSION["idmenu"]=$_GET["idmenu"];
	$_SESSION["leftmenuopened"]="";

	$tabMenu=array();
	$menuArbo = new Menubase($db,'auguria','top');
	$newTabMenu = $menuArbo->menuTopCharger('', '', $type_user, 'auguria',$tabMenu);

	print_start_menu_array_auguria();

	$num = count($newTabMenu);
	for($i = 0; $i < $num; $i++)
	{
		if ($newTabMenu[$i]['enabled'] == true)
		{
			$idsel=(empty($newTabMenu[$i]['mainmenu'])?'none':$newTabMenu[$i]['mainmenu']);
			if ($newTabMenu[$i]['perms'] == true)	// Is allowed
			{
				// Define url
				if (preg_match("/^(http:\/\/|https:\/\/)/i",$newTabMenu[$i]['url']))
				{
					$url = $newTabMenu[$i]['url'];
				}
				else
				{
					$url=dol_buildpath($newTabMenu[$i]['url'],1);
					if (! preg_match('/mainmenu/i',$url) || ! preg_match('/leftmenu/i',$url))
					{
                        if (! preg_match('/\?/',$url)) $url.='?';
                        else $url.='&';
					    $url.='mainmenu='.$newTabMenu[$i]['mainmenu'].'&leftmenu=';
					}
					//$url.="idmenu=".$newTabMenu[$i]['rowid'];    // Already done by menuLoad
				}
                                $url=preg_replace('/__LOGIN__/',$user->login,$url);

				// Define the class (top menu selected or not)
                                $classname='mb_parent';
                                if($i==0)
                                    $classname.=' first_el';
				if (! empty($_SESSION['idmenu']) && $newTabMenu[$i]['rowid'] == $_SESSION['idmenu']) $classname.=' pageselected';
				else if (! empty($_SESSION["mainmenu"]) && $newTabMenu[$i]['mainmenu'] == $_SESSION["mainmenu"]) $classname.=' pageselected';

				print '<li>';
				print '<a class="'.$classname.'" href="'.$url.'"'.($newTabMenu[$i]['target']?' target="'.$newTabMenu[$i]['target'].'"':($atarget?' target="'.$atarget.'"':'')).'>';
				print $newTabMenu[$i]['titre'];
				print '</a>';
                                // Submenu
                                print_submenu($db, $newTabMenu[$i]['mainmenu']);
                                
                                print '</li>';
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
function print_submenu($db,$mainmenu)
{
    global $user,$conf,$langs,$dolibarr_main_db_name,$mysoc;

    $overwritemenufor = array();
    $newmenu = new Menu();

    if (isset($_GET["leftmenu"])) {
        // On sauve en session le menu principal choisi
        $leftmenu=$_GET["leftmenu"];
        $_SESSION["leftmenu"]=$leftmenu;
        if ($_SESSION["leftmenuopened"]==$leftmenu) {
            //$leftmenu="";
            $_SESSION["leftmenuopened"]="";
        }
        else {
            $_SESSION["leftmenuopened"]=$leftmenu;
        }
    } else {
        // On va le chercher en session si non defini par le lien
        $leftmenu=isset($_SESSION["leftmenu"])?$_SESSION["leftmenu"]:'';
    }

    /**
     * On definit newmenu en fonction de mainmenu et leftmenu
     * ------------------------------------------------------
     */
    if ($mainmenu)
    {
        require_once(DOL_DOCUMENT_ROOT."/core/class/menubase.class.php");

        $tabMenu=array();
        $menuArbo = new Menubase($db,'auguria','left');
        $newmenu = $menuArbo->menuLeftCharger($newmenu,$mainmenu,$leftmenu,($user->societe_id?1:0),'auguria',$tabMenu);
        //var_dump($newmenu);
    }


    //var_dump($menu_array_before);exit;
    //var_dump($menu_array_after);exit;
    $menu_array=$newmenu->liste;
    if (is_array($menu_array_before)) $menu_array=array_merge($menu_array_before, $menu_array);
    if (is_array($menu_array_after))  $menu_array=array_merge($menu_array, $menu_array_after);
    //var_dump($menu_array);exit;

    // Show menu
    $alt=0;
    if (is_array($menu_array))
    {
        $num=count($menu_array);
        if ($num==0)
            return 0;
        
        print '<ul style="display:none">';
    	for ($i = 0; $i < $num; $i++)
        {
            print '<li>'."\n";

            // Place tabulation
            $tabstring='';
            $tabul=($menu_array[$i]['level'] - 1);
            if ($tabul > 0)
            {
                for ($j=0; $j < $tabul; $j++)
                {
                    $tabstring.='&nbsp; &nbsp;';
                }
            }

            // Add mainmenu in GET url. This make to go back on correct menu even when using Back on browser.
            $url=dol_buildpath($menu_array[$i]['url'],1);

            if (! preg_match('/mainmenu=/i',$menu_array[$i]['url']))
            {
                if (! preg_match('/\?/',$url)) $url.='?';
                else $url.='&';
                $url.='mainmenu='.$mainmenu;
            }

            print '<!-- Add menu entry with mainmenu='.$menu_array[$i]['mainmenu'].', leftmenu='.$menu_array[$i]['leftmenu'].', level='.$menu_array[$i]['mainmenu'].' -->'."\n";

            // Menu link
            if ($menu_array[$i]['enabled'])
            {
                print '<a href="'.$url.'"'.($menu_array[$i]['target']?' target="'.$menu_array[$i]['target'].'"':'').'>'.$menu_array[$i]['titre'].'</a>';
            }
            else
            {
                print '<a href="#">'.$menu_array[$i]['titre'].'</a>';
            }
                
            if($menu_array[$i]['level'] == $menu_array[$i+1]['level'])
            {   
                print '</li>';
            }
            else if($menu_array[$i]['level'] < $menu_array[$i+1]['level'])
            {
                print '<ul style="display:none">';
            }
            else
            {
                for ($j=0;$j<($menu_array[$i]['level']-$menu_array[$i+1]['level']);$j++)
                {
                    print '</li>';
                    print '</ul>';
                }
            }            
        }
        
        print '</ul>';
    }

    return count($menu_array);
}

/**
 * Core function to output left menu auguria
 *
 * @param	DoliDB		$db                  Database handler
 * @param 	array		$menu_array_before   Table of menu entries to show before entries of menu handler
 * @param   array		$menu_array_after    Table of menu entries to show after entries of menu handler
 * @return	void
 */
/*function print_left_auguria_menu($db,$menu_array_before,$menu_array_after)
{
    global $user,$conf,$langs,$dolibarr_main_db_name,$mysoc;

    $overwritemenufor = array();
    $newmenu = new Menu();

    // Read mainmenu and leftmenu that define which menu to show
    if (isset($_GET["mainmenu"])) {
        // On sauve en session le menu principal choisi
        $mainmenu=$_GET["mainmenu"];
        $_SESSION["mainmenu"]=$mainmenu;
        $_SESSION["leftmenuopened"]="";
    } else {
        // On va le chercher en session si non defini par le lien
        $mainmenu=$_SESSION["mainmenu"];
    }

    if (isset($_GET["leftmenu"])) {
        // On sauve en session le menu principal choisi
        $leftmenu=$_GET["leftmenu"];
        $_SESSION["leftmenu"]=$leftmenu;
        if ($_SESSION["leftmenuopened"]==$leftmenu) {
            //$leftmenu="";
            $_SESSION["leftmenuopened"]="";
        }
        else {
            $_SESSION["leftmenuopened"]=$leftmenu;
        }
    } else {
        // On va le chercher en session si non defini par le lien
        $leftmenu=isset($_SESSION["leftmenu"])?$_SESSION["leftmenu"]:'';
    }

    //this->menu_array contains menu in pre.inc.php


    // Show logo company
    if (! empty($conf->global->MAIN_SHOW_LOGO))
    {
        $mysoc->logo_mini=$conf->global->MAIN_INFO_SOCIETE_LOGO_MINI;
        if (! empty($mysoc->logo_mini) && is_readable($conf->mycompany->dir_output.'/logos/thumbs/'.$mysoc->logo_mini))
        {
            $urllogo=DOL_URL_ROOT.'/viewimage.php?cache=1&amp;modulepart=companylogo&amp;file='.urlencode('thumbs/'.$mysoc->logo_mini);
            print "\n".'<!-- Show logo on menu -->'."\n";
            print '<div class="blockvmenuimpair">'."\n";
            if (! empty($conf->global->MAIN_MODULE_MULTICOMPANY))
            {	
			$res=@dol_include_once('/multicompany/class/actions_multicompany.class.php');

			if ($res)
			{
				$mc = new ActionsMulticompany($db);
                                $mc->getInfo($conf->entity);
                                $company=$mc->label;
                                print '<div class="menu_titre" id="menu_titre_logo"><a class="vmenu" href="'.DOL_MAIN_URL_ROOT.'/admin/company.php">'.$company.'</a></div>';
			}
            }
            else
                 print '<div class="menu_titre" id="menu_titre_logo"></div>';
            print '<div class="menu_top" id="menu_top_logo"></div>';
            print '<div class="menu_contenu" id="menu_contenu_logo">';
            print '<center><img title="" src="'.$urllogo.'"></center>'."\n";
            print '</div>';
            print '<div class="menu_end" id="menu_end_logo"></div>';
            print '</div>'."\n";
        }
        else
        {
        
            if (! empty($conf->global->MAIN_MODULE_MULTICOMPANY))
            {	
                $res=@dol_include_once('/multicompany/class/actions_multicompany.class.php');
                if ($res)
                {
                    $mc = new ActionsMulticompany($db);
                    $mc->getInfo($conf->entity);
                    $company=$mc->label;
                    print '<div class="blockvmenuimpair">'."\n";
                    print '<div class="menu_titre" id="menu_titre_logo"><a class="vmenu" href="'.DOL_MAIN_URL_ROOT.'/admin/company.php">'.$company.'</a></div>';
                    print '</div>'."\n";
                }
            }
        }
    }
    if ($mainmenu)
    {
        require_once(DOL_DOCUMENT_ROOT."/core/class/menubase.class.php");

        $tabMenu=array();
        $menuArbo = new Menubase($db,'auguria','left');
        $newmenu = $menuArbo->menuLeftCharger($newmenu,$mainmenu,$leftmenu,($user->societe_id?1:0),'auguria',$tabMenu);
        //var_dump($newmenu);
    }


    //var_dump($menu_array_before);exit;
    //var_dump($menu_array_after);exit;
    $menu_array=$newmenu->liste;
    if (is_array($menu_array_before)) $menu_array=array_merge($menu_array_before, $menu_array);
    if (is_array($menu_array_after))  $menu_array=array_merge($menu_array, $menu_array_after);
    //var_dump($menu_array);exit;

    // Show menu
    $alt=0;
    if (is_array($menu_array))
    {
        $num=count($menu_array);
    	for ($i = 0; $i < $num; $i++)
        {
            $alt++;
            if (empty($menu_array[$i]['level']))
            {
                if (($alt%2==0))
                {
                	if ($conf->use_javascript_ajax && $conf->global->MAIN_MENU_USE_JQUERY_ACCORDION)
                	{
                		print '<div class="blockvmenupair">'."\n";
                	}
                	else
                	{
                		print '<div class="blockvmenuimpair">'."\n";
                	}
                }
                else
                {
                    print '<div class="blockvmenupair">'."\n";
                }
            }

            // Place tabulation
            $tabstring='';
            $tabul=($menu_array[$i]['level'] - 1);
            if ($tabul > 0)
            {
                for ($j=0; $j < $tabul; $j++)
                {
                    $tabstring.='&nbsp; &nbsp;';
                }
            }

            // Add mainmenu in GET url. This make to go back on correct menu even when using Back on browser.
            $url=dol_buildpath($menu_array[$i]['url'],1);

            if (! preg_match('/mainmenu=/i',$menu_array[$i]['url']))
            {
                if (! preg_match('/\?/',$url)) $url.='?';
                else $url.='&';
                $url.='mainmenu='.$mainmenu;
            }

            print '<!-- Add menu entry with mainmenu='.$menu_array[$i]['mainmenu'].', leftmenu='.$menu_array[$i]['leftmenu'].', level='.$menu_array[$i]['mainmenu'].' -->'."\n";

            // Menu niveau 0
            if ($menu_array[$i]['level'] == 0)
            {
                if ($menu_array[$i]['enabled'])
                {
                    print '<div class="menu_titre">'.$tabstring.'<a class="vmenu" href="'.$url.'"'.($menu_array[$i]['target']?' target="'.$menu_array[$i]['target'].'"':'').'>'.$menu_array[$i]['titre'].'</a></div>';
                }
                else if (empty($conf->global->MAIN_MENU_HIDE_UNAUTHORIZED))
                {
                    print '<div class="menu_titre">'.$tabstring.'<font class="vmenudisabled">'.$menu_array[$i]['titre'].'</font></div>';
                }
                print "\n".'<div id="section_content_'.$i.'">'."\n";
                print '<div class="menu_top"></div>'."\n";
            }
            // Menu niveau > 0
            if ($menu_array[$i]['level'] > 0)
            {
                if ($menu_array[$i]['enabled'])
                {
                    print '<div class="menu_contenu">'.$tabstring.'<a class="vsmenu" href="'.$url.'"'.($menu_array[$i]['target']?' target="'.$menu_array[$i]['target'].'"':'').'>'.$menu_array[$i]['titre'].'</a></div>';
                }
                else if (empty($conf->global->MAIN_MENU_HIDE_UNAUTHORIZED))
                {
                    print '<div class="menu_contenu">'.$tabstring.'<font class="vsmenudisabled">'.$menu_array[$i]['titre'].'</font></div>';
                }
            }

            // If next is a new block or end
            if (empty($menu_array[$i+1]['level']))
            {
                print '<div class="menu_end"></div>'."\n";
                print "</div><!-- end section content -->\n";
                print "</div><!-- end blockvmenu  pair/impair -->\n";
            }
        }
    }

    return count($menu_array);
}*/

?>
