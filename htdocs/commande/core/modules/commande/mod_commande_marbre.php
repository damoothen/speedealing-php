<?php
/* Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
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
 *  \file       htdocs/core/modules/commande/mod_commande_marbre.php
 *  \ingroup    commande
 *  \brief      File of class to manage customer order numbering rules Marbre
 */
require_once DOL_DOCUMENT_ROOT .'/commande/core/modules/commande/modules_commande.php';

/**
 *	Class to manage customer order numbering rules Marbre
 */
class mod_commande_marbre extends ModeleNumRefCommandes
{
	var $version='dolibarr';		// 'development', 'experimental', 'dolibarr'
	var $prefix='CO';
	var $error='';
	var $nom='Marbre';


    /**
     *  Return description of numbering module
     *
     *  @return     string      Text with description
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
	 *  Test si les numeros deje en vigueur dans la base ne provoquent pas de
	 *  de conflits qui empechera cette numerotation de fonctionner.
	 *
	 *  @return     boolean     false si conflit, true si ok
	 */
	function canBeActivated()
	{
		global $conf,$langs;

		$coyymm=''; $max='';

		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";
		$sql.= " FROM ".MAIN_DB_PREFIX."commande";
		$sql.= " WHERE ref LIKE '".$this->prefix."____-%'";
		$sql.= " AND entity = ".$conf->entity;

		$resql=$db->query($sql);
		if ($resql)
		{
			$row = $db->fetch_row($resql);
			if ($row) { $coyymm = substr($row[0],0,6); $max=$row[0]; }
		}
		if ($coyymm && ! preg_match('/'.$this->prefix.'[0-9][0-9][0-9][0-9]/i',$coyymm))
		{
			$langs->load("errors");
			$this->error=$langs->trans('ErrorNumRefModel', $max);
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
                $result = $object->getView("count");
                $max = (int) $result->rows[0]->value;

		//$date=time();
		$date=$object->date;
		$yymm = strftime("%y%m",$date);
		$num = sprintf("%04s",$max+1);

		dol_syslog("mod_commande_marbre::getNextValue return ".$this->prefix.$yymm."-".$num);
		return $this->prefix.$yymm."-".$num;
	}


	/**
	 *  Return next free value
	 *
	 *  @param	Societe		$objsoc     Object third party
	 * 	@param	string		$objforref	Object for number to search
	 *  @return string      			Next free value
	 */
	function commande_get_num($objsoc,$objforref)
	{
		return $this->getNextValue($objsoc,$objforref);
	}

}
?>
