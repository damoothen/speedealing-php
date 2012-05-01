<?php

/* Copyright (C) 2012      Patrick Mary           <laube@hotmail.fr>
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

/**
 * 	\file       htdocs/comm/serverprocess.php
 * 	\ingroup    commercial societe
 * 	\brief      load data to display
 * 	\version    $Id: serverprocess.php,v 1.6 2012/01/27 16:15:05 synry63 Exp $
 */
require_once("../../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/menubase.class.php");;
$langs->load("companies");
$langs->load("customers");
$langs->load("suppliers");
$langs->load("commercial");
/* Array of database columns which should be read and sent back to DataTables. Use a space where
 * you want to insert a non-database field (for example a counter or static image)
 */


$flush=0;
if($flush)
{
    // reset old value
    $result = $couch->limit(50000)->getView('menu','target_id');
    $i=0;
    
    if(count($result->rows)==0)
    {
        print "Effacement terminé";
        exit;
    }
    
    foreach ($result->rows AS $aRow)
    {
        $obj[$i]->_id=$aRow->value->_id;
        $obj[$i]->_rev=$aRow->value->_rev;
        $i++;
    }

    try {
        $couch->deleteDocs($obj);
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }

    print "Effacement en cours";
    exit;
}


/*basic companies request query */
$sql = "SELECT * FROM ".MAIN_DB_PREFIX."menu WHERE menu_handler='auguria' ORDER BY rowid";


$result = $db->query($sql);

$i=0;

$aRow = new Menubase($db);

while ($aRow = $db->fetch_object($result)) {
        
        //print_r($aRow);
        
        unset($aRow->menu_handler);
        unset($aRow->entity);
        $rowid = (int)$aRow->rowid;
        unset($aRow->rowid);
        $fk_menu=(int)$aRow->fk_menu;
        unset($aRow->fk_menu);
        $level=(int)$aRow->level;
        unset($aRow->level);
        unset($aRow->fk_leftmenu);
        unset($aRow->fk_mainmenu);
        unset($aRow->target);
        $aRow->tms=dol_now();
        $aRow->title=$aRow->titre;
        unset($aRow->titre);
        $aRow->enabled = (bool)$aRow->enabled;
        $aRow->position = (int)$aRow->position;
        $aRow->usertype = (int)$aRow->usertype;
        $pos=strpos($aRow->url, "?");
        
        $tabperefils[$rowid]=$fk_menu;
        
        if($pos!=false)
        {
            $aRow->url=substr($aRow->url, 0,$pos);
        }
        
        $name = "menu:".strtolower($aRow->title);
        $aRow->_id = $name;
        
        if($aRow->type == "top")
        {
            $aRow->class="menu";
            unset($aRow->type);
            unset($aRow->leftmenu);
            $name="menu:".$aRow->mainmenu;
            unset($aRow->mainmenu);
            $obj[$name] = $aRow;
            $obj[$name]->_id = $name;
        }
        else if($level==0)// left
        {
            unset($aRow->type);
            unset($aRow->leftmenu);
            unset($aRow->mainmenu);
            unset($aRow->tms);
            $obj[$tabname[$fk_menu]]->submenu[$name] = $aRow;
            uasort($obj[$tabname[$fk_menu]]->submenu,array("Menubase","compare")); // suivant position
        }
        else if($level==1)
        {
            unset($aRow->type);
            unset($aRow->leftmenu);     
            unset($aRow->mainmenu);
            unset($aRow->tms);
            $aRow->_id = "menu:".strtolower($aRow->title);
            $obj[$tabname[$tabperefils[$fk_menu]]]->submenu[$tabname[$fk_menu]]->submenu[$name] = $aRow;
            uasort($obj[$tabname[$tabperefils[$fk_menu]]]->submenu[$tabname[$fk_menu]]->submenu,array("Menubase","compare"));
        }
        else
        {
            unset($aRow->type);
            unset($aRow->leftmenu);     
            unset($aRow->mainmenu);
            unset($aRow->tms);
            $aRow->_id = "menu:".strtolower($aRow->title);
            $obj[$tabname[$tabperefils[$tabperefils[$fk_menu]]]]->submenu[$tabname[$tabperefils[$fk_menu]]]->submenu[$tabname[$fk_menu]]->submenu[$name] = $aRow;
            uasort($obj[$tabname[$tabperefils[$tabperefils[$fk_menu]]]]->submenu[$tabname[$tabperefils[$fk_menu]]]->submenu[$tabname[$fk_menu]]->submenu,array("Menubase","compare"));
        }
        
        $tabname[$rowid]=$name;
        
        $i++;
}
$db->free($result);
unset($result);

//print json_encode($obj);
//exit;

$i=0;

try {
    print_r($conf->couchdb->storeDocs($obj,false));
    } catch (Exception $e) {
        echo "Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
        exit(1);
    }
?>