<?php
/* Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
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
 *  \file       htdocs/core/modules/contract/mod_contract_serpis.php
 *  \ingroup    contract
 *  \brief      File of class to manage contract numbering rules Serpis
 */
require_once DOL_DOCUMENT_ROOT .'/core/modules/contract/modules_contract.php';

/**
 * 	Class to manage contract numbering rules Serpis
 */
class mod_contract_serpis extends ModelNumRefContracts
{
	var $version='dolibarr';
	var $prefix='CT';
	var $error='';
	var $nom='Serpis';


	/**
	 *	Return default description of numbering model
	 *
	 *	@return     string      text description
	 */
    function info()
    {
    	global $langs;
      	return $langs->trans("SimpleNumRefModelDesc",$this->prefix);
    }


	/**
	 *	Return numbering example
	 *
	 *	@return     string      Example
	 */
	function getExample()
	{
		return $this->prefix."0501-0001";
	}


	/**
	 *	Test if existing numbers make problems with numbering
	 *
	 *	@return     boolean     false if conflit, true if ok
	 */
	function canBeActivated()
	{
		global $conf,$langs;

		$coyymm=''; $max='';

		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";
		$sql.= " FROM ".MAIN_DB_PREFIX."contrat";
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
	 *	Return next value
	 *
	 *	@param	Societe		$objsoc     third party object
	 *	@param	Object		$contract	contract object
	 *	@return string      			Value if OK, 0 if KO
	 */
	function getNextValue($objsoc,$contract)
	{
		global $db,$conf;

		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";
		$sql.= " FROM ".MAIN_DB_PREFIX."contrat";
		$sql.= " WHERE ref like '".$this->prefix."____-%'";
		$sql.= " AND entity = ".$conf->entity;

		$resql=$db->query($sql);
		if ($resql)
		{
			$obj = $db->fetch_object($resql);
			if ($obj) $max = intval($obj->max);
			else $max=0;
		}
		else
		{
			dol_syslog("mod_contract_serpis::getNextValue sql=".$sql);
			return -1;
		}

		$date=$contract->date_contrat;
		$yymm = strftime("%y%m",$date);
		$num = sprintf("%04s",$max+1);

		dol_syslog("mod_contract_serpis::getNextValue return ".$this->prefix.$yymm."-".$num);
		return $this->prefix.$yymm."-".$num;
	}


	/**
	 *	Return next value
	 *
	 *	@param	Societe		$objsoc     third party object
	 *	@param	Object		$objforref	contract object
	 *	@return string      			Value if OK, 0 if KO
	 */
	function contract_get_num($objsoc,$objforref)
	{
		return $this->getNextValue($objsoc,$objforref);
	}

}
?>
