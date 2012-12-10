<?php
/* Copyright (C) 2006-2009 Laurent Destailleur   <eldy@users.sourceforge.net>
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
 *	\file       htdocs/accountancy/class/accountancyaccount.class.php
 * 	\ingroup    accounting
 * 	\brief      Fichier de la classe des comptes comptables
 */


/**
 * \class 		AccountancyAccount
 * \brief 		Classe permettant la gestion des comptes
 */
class AccountancyAccount
{
    var $db;
    var $error;

    var $rowid;
    var $fk_pcg_version;
    var $pcg_type;
    var $pcg_subtype;
    var $label;
    var $account_number;
    var $account_parent;


    /**
     *  Constructor
     *
     *  @param		DoliDB		$db		Database handler
     */
    function __construct($db)
    {
        $this->db = $db;
    }


    /**
     *    Insert account into database
     *
     *    @param  	User	$user 	User making add
     *    @return	int				<0 if KO, Id line added if OK
     */
    function create($user)
    {
    	$now=dol_now();
    	
        $sql = "INSERT INTO ".MAIN_DB_PREFIX."accountingaccount";
        $sql.= " (date_creation, fk_user_author, numero,intitule)";
        $sql.= " VALUES (".$this->db->idate($now).",".$user->id.",'".$this->numero."','".$this->intitule."')";

        $resql = $this->db->query($sql);
        if ($resql)
        {
            $id = $this->db->last_insert_id(MAIN_DB_PREFIX."accountingaccount");

            if ($id > 0)
            {
                $this->id = $id;
                $result = $this->id;
            }
            else
            {
                $result = -2;
                $this->error="AccountancyAccount::Create Erreur $result";
                dol_syslog($this->error, LOG_ERR);
            }
        }
        else
        {
            $result = -1;
            $this->error="AccountancyAccount::Create Erreur $result";
            dol_syslog($this->error, LOG_ERR);
        }

        return $result;
    }

}
?>
