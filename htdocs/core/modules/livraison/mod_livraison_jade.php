<?php
/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin        <regis@dolibarr.fr>
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
 *   \file       htdocs/core/modules/livraison/mod_livraison_jade.php
 *   \ingroup    delivery
 *   \brief      Fichier contenant la classe du modele de numerotation de reference de bon de livraison Jade
 */

require_once DOL_DOCUMENT_ROOT .'/core/modules/livraison/modules_livraison.php';


/**
 *  \class      mod_livraison_jade
 *  \brief      Classe du modele de numerotation de reference de bon de livraison Jade
 */

class mod_livraison_jade extends ModeleNumRefDeliveryOrder
{
	var $version='dolibarr';		// 'development', 'experimental', 'dolibarr'
	var $error = '';
	var $nom = "Jade";

    var $prefix='BL';


	/**
	 *   Renvoi la description du modele de numerotation
	 *
	 *   @return     string      Texte descripif
	 */
	function info()
	{
		global $langs;
		return $langs->trans("SimpleNumRefModelDesc",$this->prefix);
	}

	/**
	 *  Renvoi un exemple de numerotation
	 *
     *  @return     string      Example
     */
    function getExample()
    {
        return $this->prefix."0501-0001";
    }

    /**
     *  Test si les numeros deja en vigueur dans la base ne provoquent pas de
     *  de conflits qui empechera cette numerotation de fonctionner.
     *
     *  @return     boolean     false si conflit, true si ok
     */
    function canBeActivated()
    {
        global $langs,$conf;

        $langs->load("bills");

        // Check invoice num
        $fayymm=''; $max='';

        $posindice=8;
        $sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";   // This is standard SQL
        $sql.= " FROM ".MAIN_DB_PREFIX."livraison";
        $sql.= " WHERE ref LIKE '".$this->prefix."____-%'";
        $sql.= " AND entity = ".$conf->entity;

        $resql=$db->query($sql);
        if ($resql)
        {
            $row = $db->fetch_row($resql);
            if ($row) { $fayymm = substr($row[0],0,6); $max=$row[0]; }
        }
        if ($fayymm && ! preg_match('/'.$this->prefix.'[0-9][0-9][0-9][0-9]/i',$fayymm))
        {
            $langs->load("errors");
            $this->error=$langs->trans('ErrorNumRefModel',$max);
            return false;
        }

        return true;
    }

    /**
	 * 	Return next free value
	 *
	 *  @param	Societe		$objsoc     Object thirdparty
	 *  @param  Object		$object		Object we need next value for
	 *  @return string      			Value if KO, <0 if KO
	 */
    function getNextValue($objsoc,$object)
    {
        global $db,$conf;

        // D'abord on recupere la valeur max
        $posindice=8;
        $sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";   // This is standard SQL
        $sql.= " FROM ".MAIN_DB_PREFIX."livraison";
        $sql.= " WHERE ref LIKE '".$this->prefix."____-%'";
        $sql.= " AND entity = ".$conf->entity;

        $resql=$db->query($sql);
        dol_syslog("mod_livraison_jade::getNextValue sql=".$sql);
        if ($resql)
        {
            $obj = $db->fetch_object($resql);
            if ($obj) $max = intval($obj->max);
            else $max=0;
        }
        else
        {
            dol_syslog("mod_livraison_jade::getNextValue sql=".$sql, LOG_ERR);
            return -1;
        }

        $date=$object->date_delivery;
        if (empty($date)) $date=dol_now();
        $yymm = strftime("%y%m",$date);
        $num = sprintf("%04s",$max+1);

        dol_syslog("mod_livraison_jade::getNextValue return ".$this->prefix.$yymm."-".$num);
        return $this->prefix.$yymm."-".$num;
    }


	/**
	 *  Return next free ref
	 *
     *  @param	Societe		$objsoc      	Object thirdparty
     *  @param  Object		$object			Object livraison
     *  @return string      				Texte descripif
     */
    function livraison_get_num($objsoc=0,$object='')
    {
        return $this->getNextValue($objsoc,$object);
    }

}
?>
