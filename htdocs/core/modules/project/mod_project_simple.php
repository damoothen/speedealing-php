<?php
/* Copyright (C) 2010-2012	Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2010		Laurent Destailleur	<eldy@users.sourceforge.net>
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
 *	\file       htdocs/core/modules/project/mod_project_simple.php
 *	\ingroup    project
 *	\brief      File with class to manage the numbering module Simple for project references
 */

require_once DOL_DOCUMENT_ROOT .'/core/modules/project/modules_project.php';


/**
 * 	Class to manage the numbering module Simple for project references
 */
class mod_project_simple extends ModeleNumRefProjects
{
	var $version='dolibarr';		// 'development', 'experimental', 'dolibarr'
	var $prefix='PJ';
    var $error='';
	var $nom = "Simple";


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
     *  Return an example of numbering module values
     * 
     * 	@return     string      Example
     */
    function getExample()
    {
        return $this->prefix."0501-0001";
    }


    /**  Test si les numeros deja en vigueur dans la base ne provoquent pas de
     *   de conflits qui empechera cette numerotation de fonctionner.
     * 
     *   @return     boolean     false si conflit, true si ok
     */
    function canBeActivated()
    {
    	global $conf,$langs;

        $coyymm=''; $max='';

		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";
        $sql.= " FROM ".MAIN_DB_PREFIX."projet";
		$sql.= " WHERE ref LIKE '".$this->prefix."____-%'";
        $sql.= " AND entity = ".$conf->entity;
        $resql=$db->query($sql);
        if ($resql)
        {
            $row = $db->fetch_row($resql);
            if ($row) { $coyymm = substr($row[0],0,6); $max=$row[0]; }
        }
        if (! $coyymm || preg_match('/'.$this->prefix.'[0-9][0-9][0-9][0-9]/i',$coyymm))
        {
            return true;
        }
        else
        {
			$langs->load("errors");
			$this->error=$langs->trans('ErrorNumRefModel',$max);
            return false;
        }
    }


   /**
	*  Return next value
	* 
	*  @param   Societe	$objsoc		Object third party
	*  @param   Project	$project	Object project
	*  @return	string				Value if OK, 0 if KO
	*/
    function getNextValue($objsoc,$project)
    {
		global $db,$conf;

		// D'abord on recupere la valeur max
		$posindice=8;
		$sql = "SELECT MAX(SUBSTRING(ref FROM ".$posindice.")) as max";
		$sql.= " FROM ".MAIN_DB_PREFIX."projet";
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
			dol_syslog("mod_project_simple::getNextValue sql=".$sql);
			return -1;
		}

		$date=empty($project->date_c)?dol_now():$project->date_c;

		//$yymm = strftime("%y%m",time());
		$yymm = strftime("%y%m",$date);
		$num = sprintf("%04s",$max+1);

		dol_syslog("mod_project_simple::getNextValue return ".$this->prefix.$yymm."-".$num);
		return $this->prefix.$yymm."-".$num;
    }


    /** 
     * 	Return next reference not yet used as a reference
     * 
     *  @param	Societe	$objsoc     Object third party
     *  @param  Project	$project	Object project
     *  @return string      		Next not used reference
     */
    function project_get_num($objsoc=0,$project='')
    {
        return $this->getNextValue($objsoc,$project);
    }
}

?>