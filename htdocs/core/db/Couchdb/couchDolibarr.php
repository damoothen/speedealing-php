<?php
/* Copyright (C) 2012      Herve Prot  <herve.prot@symeos.com>
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
 *	\file       htdocs/core/db/Couchdb/couchDolibarr.php
 *	\ingroup    core
 *	\brief      File of parent class of all other classes
 */

include_once(DOL_DOCUMENT_ROOT ."/core/db/Couchdb/couch.php");
include_once(DOL_DOCUMENT_ROOT ."/core/db/Couchdb/couchClient.php");
include_once(DOL_DOCUMENT_ROOT ."/core/db/Couchdb/couchDocument.php");


/**
 *	Parent class of all other classes
 */
abstract class couchDolibarr extends couchDocument
{
    
        var $db;
        
        var $arrayjs = array("/core/datatables/js/jquery.dataTables.js",
            "/core/datatables/js/TableTools.js",
            "/core/datatables/js/ZeroClipboard.js",
            "/core/datatables/js/initXHR.js",
            "/core/datatables/js/request.js",
            "/core/datatables/js/searchColumns.js");
    
        /**
	*class constructor
	*
	* @param couchClient $client couchClient connection object
	*
	*/
    
        function __construct(couchClient $db)
	{
                parent::__construct($db);
                $this->__couch_data->autocommit = false;
		$this->db = $db;
	}
        
        /**
	 *  just a proxy method to couchClient->deleteDoc
	 *
	 *  @return int         		1 success
	 */
	public function delete()
	{
            $this->__couch_data->client->deleteDoc($this);
                
            return 1;
	}
}


?>
