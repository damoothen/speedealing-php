<?php
/* Copyright (C) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
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
 *
 */

/**
 *      \file       htdocs/boutique/client/class/boutiqueclient.class.php
 *      \brief      Classe permettant de gerer des clients de la boutique online
 *      \author	    Rodolphe Quiedeville
 */

/**
 *      \class      BoutiqueClient
 *      \brief      Classe permettant de gerer des clients de la boutique online
 */
class BoutiqueClient
{
    var $db ;

    var $id ;
    var $nom;


	/**
	 * 	Constructor
	 *
	 * 	@param		DoliDB	$db		Database handler
	 */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     *   Fonction permettant de recuperer les informations d'un clients de la boutique
     *
     *   @param		int		$id			Id du client
     *   @return	int					<0 if KO, >0 if OK
     */
    function fetch($id)
    {
		global $conf;

        $sql = "SELECT customers_id, customers_lastname, customers_firstname FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."customers WHERE customers_id = ".$id;

        $resql = $this->db->query($sql);
        if ( $resql )
        {
            $result = $this->db->fetch_array($resql);

            $this->id      = $result["customers_id"];
            $this->name    = $result["customers_firstname"] . " " . $result["customers_lastname"];

            $this->db->free($resql);
        	return 1;
        }
        else
        {
            print $this->db->error();
            return -1;
        }
    }

}
?>
