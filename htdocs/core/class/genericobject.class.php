<?php
/* Copyright (C) 2006-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *	\file       htdocs/core/class/genericobject.class.php
 *	\ingroup    core
 *	\brief      File of class of generic business class
 */
require_once DOL_DOCUMENT_ROOT .'/core/class/commonobject.class.php';


/**
 *	\class 		GenericObject
 *	\brief 		Class of a generic business object
 */

class GenericObject extends CommonObject
{
	var $db;
	
	var $fk_status;

	/**
	 *	Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
	    $this->db=$db;
	    
	    parent::__construct($db);
	    
	    try {
	    	//$this->fk_extrafields = $this->couchdb->getDoc("extrafields:".  get_class($this)); // load fields company
	    	$this->fk_status = $this->couchdb->getDoc("status:Societe"); //load status table
	    	//$this->fk_country = $this->couchdb->getDoc("dict:country"); //load country table
	    }catch (Exception $e) {
	    	$error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
	    	print $error;
	    	exit;
	    }
	}

}

?>
