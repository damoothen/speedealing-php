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
 *	\file       htdocs/core/class/html.formbank.class.php
 *  \ingroup    core
 *	\brief      File of class with all html predefined components
 */


/**
 *	Class to manage generation of HTML components for bank module
 */
class FormBank
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
     *  Retourne la liste des types de comptes financiers
     *
     *  @param	string	$selected        Type pre-selectionne
     *  @param  string	$htmlname        Nom champ formulaire
     *  @return	void
     */
    function select_type_comptes_financiers($selected=1,$htmlname='type')
    {
        global $langs;
        $langs->load("banks");

        $type_available=array(0,1,2);

        print '<select class="flat" name="'.$htmlname.'">';
        $num = count($type_available);
        $i = 0;
        if ($num)
        {
            while ($i < $num)
            {
                if ($selected == $type_available[$i])
                {
                    print '<option value="'.$type_available[$i].'" selected="selected">'.$langs->trans("BankType".$type_available[$i]).'</option>';
                }
                else
                {
                    print '<option value="'.$type_available[$i].'">'.$langs->trans("BankType".$type_available[$i]).'</option>';
                }
                $i++;
            }
        }
        print '</select>';
    }

}

?>
