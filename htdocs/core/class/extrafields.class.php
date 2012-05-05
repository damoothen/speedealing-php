<?php
/* Copyright (C) 2002-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier	    <benoit.mortier@opensides.be>
 * Copyright (C) 2009-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2009-2011 Regis Houssin        <regis@dolibarr.fr>
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
 * 	\file 		htdocs/core/class/extrafields.class.php
 *	\ingroup    core
 *	\brief      File of class to manage extra fields
 */

/**
 *	Class to manage standard extra fields
 */
class ExtraFields extends CommonObject
{
	var $db;

	var $error;
	var $errno;


	/**
	 *	Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	function ExtraFields($db)
	{
            global $conf;
            
            $this->db = $db;
            $this->error = array();
            parent::__construct($db);

            return 1;
	}

    	/**
	 *  Return HTML string to put an input field into a page
	 *
	 *  @param	string	$key             Key of attribute
	 *  @param  string	$value           Value to show
	 *  @param  string	$moreparam       To add more parametes on html input tag
	 *  @return	void
	 */
	function showInputField($key,$value,$moreparam='')
	{
		global $conf;

        $label=$this->attribute_label[$key];
	    $type=$this->attribute_type[$key];
        $size=$this->attribute_size[$key];
        $elementtype=$this->attribute_elementtype[$key];
        if ($type == 'date')
        {
            $showsize=10;
        }
        elseif ($type == 'datetime')
        {
            $showsize=19;
        }
        elseif ($type == 'int')
        {
            $showsize=10;
        }
        else
        {
            $showsize=round($size);
            if ($showsize > 48) $showsize=48;
        }

		if ($type == 'int')
        {
        	$out='<input type="text" name="options_'.$key.'" size="'.$showsize.'" maxlength="'.$size.'" value="'.$value.'"'.($moreparam?$moreparam:'').'>';
        }
        else if ($type == 'varchar')
        {
        	$out='<input type="text" name="options_'.$key.'" size="'.$showsize.'" maxlength="'.$size.'" value="'.$value.'"'.($moreparam?$moreparam:'').'>';
        }
        else if ($type == 'text')
        {
        	require_once(DOL_DOCUMENT_ROOT."/core/class/doleditor.class.php");
        	$doleditor=new DolEditor('options_'.$key,$value,'',200,'dolibarr_notes','In',false,false,$conf->fckeditor->enabled && $conf->global->FCKEDITOR_ENABLE_SOCIETE,5,100);
        	$out=$doleditor->Create(1);
        }
	    else if ($type == 'date') $out.=' (YYYY-MM-DD)';
        else if ($type == 'datetime') $out.=' (YYYY-MM-DD HH:MM:SS)';
	    return $out;
	}

    /**
     * Return HTML string to put an output field into a page
     *
     * @param   string	$key            Key of attribute
     * @param   string	$value          Value to show
     * @param	string	$moreparam		More param
     * @return	string					Formated value
     */
    function showOutputField($key,$value,$moreparam='')
    {
        $label=$this->attribute_label[$key];
        $type=$this->attribute_type[$key];
        $size=$this->attribute_size[$key];
        $elementtype=$this->attribute_elementtype[$key];
        if ($type == 'date')
        {
            $showsize=10;
        }
        elseif ($type == 'datetime')
        {
            $showsize=19;
        }
        elseif ($type == 'int')
        {
            $showsize=10;
        }
        else
        {
            $showsize=round($size);
            if ($showsize > 48) $showsize=48;
        }
        //print $type.'-'.$size;
        $out=$value;
        return $out;
    }

}
?>
