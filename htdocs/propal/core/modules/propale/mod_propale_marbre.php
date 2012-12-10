<?php

/* Copyright (C) 2005-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2012 David Moothen        <dmoothen@websitti.fr>
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
 *    	\file       htdocs/core/modules/propale/mod_propale_marbre.php
 * 		\ingroup    propale
 * 		\brief      File of class to manage commercial proposal numbering rules Marbre
 */
require_once DOL_DOCUMENT_ROOT . '/propal/core/modules/propale/modules_propale.php';

/** 	    \class      mod_propale_marbre
 * 		\brief      Class to manage customer order numbering rules Marbre
 */
class mod_propale_marbre extends ModeleNumRefPropales {

    var $version = 'dolibarr';  // 'development', 'experimental', 'dolibarr'
    var $prefix = 'PR';
    var $error = '';
    var $nom = "Marbre";

    /**
     *  Return description of numbering module
     *
     *  @return     string      Text with description
     */
    function info() {
        global $langs;
        return $langs->trans("SimpleNumRefModelDesc", $this->prefix);
    }

    /**
     *  Return an example of numbering module values
     *
     *  @return     string      Example
     */
    function getExample() {
        return $this->prefix . "0501-0001";
    }

    /**
     *  Test si les numeros deje en vigueur dans la base ne provoquent pas de
     *  de conflits qui empechera cette numerotation de fonctionner.
     *
     *  @return     boolean     false si conflit, true si ok
     */
    function canBeActivated() {
        global $conf, $langs;

        $pryymm = '';
        $max = '';

        $posindice = 8;
        $sql = "SELECT MAX(SUBSTRING(ref FROM " . $posindice . ")) as max";
        $sql.= " FROM " . MAIN_DB_PREFIX . "propal";
        $sql.= " WHERE ref LIKE '" . $this->prefix . "____-%'";
        $sql.= " AND entity = " . $conf->entity;

        $resql = $db->query($sql);
        if ($resql) {
            $row = $db->fetch_row($resql);
            if ($row) {
                $pryymm = substr($row[0], 0, 6);
                $max = $row[0];
            }
        }

        if (!$pryymm || preg_match('/' . $this->prefix . '[0-9][0-9][0-9][0-9]/i', $pryymm)) {
            return true;
        } else {
            $langs->load("errors");
            $this->error = $langs->trans('ErrorNumRefModel', $max);
            return false;
        }
    }

    /**
     *  Return next value
     *
     *  @param	Societe		$objsoc     Object third party
     * 	@param	Propal		$propal		Object commercial proposal
     *  @return string      			Next value
     */
    function getNextValue($objsoc, $propal) {
        global $db, $conf;

//		// D'abord on recupere la valeur max
//		$posindice=8;
//		$sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";	// This is standard SQL
//		$sql.= " FROM ".MAIN_DB_PREFIX."propal";
//		$sql.= " WHERE ref LIKE '".$this->prefix."____-%'";
//		$sql.= " AND entity = ".$conf->entity;
//
//		$resql=$db->query($sql);
//		if ($resql)
//		{
//			$obj = $db->fetch_object($resql);
//			if ($obj) $max = intval($obj->max);
//			else $max=0;
//		}
//		else
//		{
//			dol_syslog("mod_propale_marbre::getNextValue sql=".$sql);
//			return -1;
//		}

        $result = $propal->getView("count");
        $max = (int) $result->rows[0]->value;

        $date = time();
        $yymm = strftime("%y%m", $date);
        $num = sprintf("%04s", $max + 1);

        dol_syslog("mod_propale_marbre::getNextValue return " . $this->prefix . $yymm . "-" . $num);
        return $this->prefix . $yymm . "-" . $num;
    }

    /**
     *  Return next free value
     *
     *  @param	Societe		$objsoc      	Object third party
     * 	@param	Object		$objforref		Object for number to search
     *  @return string      				Next free value
     */
    function getNumRef($objsoc, $objforref) {
        return $this->getNextValue($objsoc, $objforref);
    }

}

?>
