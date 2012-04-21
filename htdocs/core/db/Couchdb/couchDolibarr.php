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
    
        protected $db;
        
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
                $this->setAutocommit(false);
                $this->class = $this->element;
		$this->db = $db;
	}
        
        /**
	 *  Record fonction for update : suppress empty value
	 *
	 *  @return int         		1 success
	 */
	public function record()
	{
            foreach ($this->__couch_data->fields as $key => $aRow)
            {
                if(empty($aRow))
                {
                    unset($this->__couch_data->fields->$key);
                }
                else
                    trim($aRow);
                    
            }
            return parent::record();
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
        
        
        /**
	 *  Get a view name for the class
	 *
         *  @param  string                      view name
	 *  @return array         		1 success
	 */
        
        public function getView($name)
        {
            global $conf;
            
            return $this->db->limit($conf->liste_limit)->getView($this->class,$name);
        }
        
        
        
        /**
	 *  For list datatables generation
	 *
         *  @param $obj object of aocolumns details
         *  @param $ref_css name of #list
	 *  @return string
	 */
	public function _datatables($obj,$ref_css)
	{
            global $conf,$langs;
            
            $obj->sAjaxSource = $_SERVER['PHP_SELF']."?json=true";
            $obj->iDisplayLength = (int)$conf->global->MAIN_SIZE_LISTE_LIMIT;
            $obj->aLengthMenu= array(array(10, 25, 50, 100, 1000, -1), array(10, 25, 50, 100,1000,"All"));
            $obj->bProcessing = true;
            $obj->bJQueryUI = true;
            $obj->bDeferRender = true;
            $obj->oLanguage->sUrl = DOL_URL_ROOT.'/core/datatables/langs/'.($langs->defaultlang?$langs->defaultlang:"en_US").".txt";
            $obj->sDom = '<\"top\"Tflpi<\"clear\">>rt<\"bottom\"pi<\"clear\">>';
            $obj->oTableTools->sSwfPath = DOL_URL_ROOT.'/core/datatables/swf/copy_cvs_xls_pdf.swf';
            $obj->oTableTools->aButtons = array("xls");
            
            $output ='<script type="text/javascript" charset="utf-8">';
            $output.='$(document).ready(function() {';
            $output.='oTable = $(\''.$ref_css.'\').dataTable(';
            
            $json = json_encode($obj);
            $json = str_replace('"%', '', $json);
            $json = str_replace('%"', '', $json);
            $json = str_replace('\n', '', $json);
            $json = str_replace('\"', '"', $json);
            $json = str_replace('\"', '"', $json);
            $json = str_replace('$', '"', $json);
            $output.=$json;
            
            $output.= ");";
            $output.= "});";
            $output.='</script>';
                
            return $output;
	}
        
        
        
}


?>
