<?php
/* Copyright (C) 2012 Laurent Destailleur   <eldy@users.sourceforge.net>
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
 *	\file       htdocs/core/class/html.formpropal.class.php
 *  \ingroup    core
 *	\brief      File of class with all html predefined components
 */


/**
 *	Class to manage generation of HTML components for proposal management
 */
class FormPropal
{
	var $db;
	var $error;


	/**
	 * Constructor
	 *
	 * @param		DoliDB		$db      Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

    /**
     *    Return combo list of differents status of a proposal
     *    Values are id of table c_propalst
     *
     *    @param	string	$selected   Preselected value
     *    @param	int		$short		Use short labels
     *    @return	void
     */
    function select_propal_statut($selected='',$short=0)
    {
        global $langs;

        $sql = "SELECT id, code, label, active FROM ".MAIN_DB_PREFIX."c_propalst";
        $sql .= " WHERE active = 1";

        dol_syslog(get_class($this)."::select_propal_statut sql=".$sql);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            print '<select class="flat" name="propal_statut">';
            print '<option value="">&nbsp;</option>';
            $num = $this->db->num_rows($resql);
            $i = 0;
            if ($num)
            {
                while ($i < $num)
                {
                    $obj = $this->db->fetch_object($resql);
                    if ($selected == $obj->id)
                    {
                        print '<option value="'.$obj->id.'" selected="selected">';
                    }
                    else
                    {
                        print '<option value="'.$obj->id.'">';
                    }
                    $key=$obj->code;
                    if ($langs->trans("PropalStatus".$key.($short?'Short':'')) != "PropalStatus".$key.($short?'Short':''))
                    {
                        print $langs->trans("PropalStatus".$key.($short?'Short':''));
                    }
                    else
                    {
                        $conv_to_new_code=array('PR_DRAFT'=>'Draft','PR_OPEN'=>'Opened','PR_CLOSED'=>'Closed','PR_SIGNED'=>'Signed','PR_NOTSIGNED'=>'NotSigned','PR_FAC'=>'Billed');
                        if (! empty($conv_to_new_code[$obj->code])) $key=$conv_to_new_code[$obj->code];
                        print ($langs->trans("PropalStatus".$key.($short?'Short':''))!="PropalStatus".$key.($short?'Short':''))?$langs->trans("PropalStatus".$key.($short?'Short':'')):$obj->label;
                    }
                    print '</option>';
                    $i++;
                }
            }
            print '</select>';
        }
        else
        {
            dol_print_error($this->db);
        }
    }

}

?>
