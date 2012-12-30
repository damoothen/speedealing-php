<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Juanjo Menent		<jmenent@2byte.es>
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
 *  \file       htdocs/expedition/class/expeditionstats.class.php
 *  \ingroup    expedition
 *  \brief      Fichier des classes expedition
 */

/**
 *	\class      ExpeditionStats
 *	\brief      Class to manage shipment statistics
 */
class ExpeditionStats
{
    var $db;

    /**
     * Constructor
     *
     * @param		DoliDB		$db      Database handler
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Return expedition number by year
     *
     * @return	array	array with number by year
     */
    function getNbExpeditionByYear()
    {
        global $conf;

        $result = array();
        $sql = "SELECT count(*), date_format(date_expedition,'%Y') as dm";
        $sql.= " FROM ".MAIN_DB_PREFIX."expedition";
        $sql.= " WHERE fk_statut > 0";
        $sql.= " AND entity = ".$conf->entity;
        $sql.= " GROUP BY dm DESC";

        $resql=$this->db->query($sql);
        if ($resql)
        {
            $num = $this->db->num_rows($resql);
            $i = 0;
            while ($i < $num)
            {
                $row = $this->db->fetch_row($resql);
                $result[$i] = $row;

                $i++;
            }
            $this->db->free($resql);
        }
        return $result;
    }

    /**
     * Return the expeditions number by month for a year
     *
     * @param	int		$year		Year
     * @return	array				Array with number by month
     */
    function getNbExpeditionByMonth($year)
    {
        global $conf;

        $result = array();
        $sql = "SELECT count(*), date_format(date_expedition,'%m') as dm";
        $sql.= " FROM ".MAIN_DB_PREFIX."expedition";
        $sql.= " WHERE date_format(date_expedition,'%Y') = '".$year."'";
        $sql.= " AND fk_statut > 0";
        $sql.= " AND entity = ".$conf->entity;
        $sql.= " GROUP BY dm DESC";

        $resql=$this->db->query($sql);
        if ($resql)
        {
            $num = $this->db->num_rows($resql);
            $i = 0;
            while ($i < $num)
            {
                $row = $this->db->fetch_row($resql);
                $j = $row[0] * 1;
                $result[$j] = $row[1];
                $i++;
            }
            $this->db->free($resql);
        }
        for ($i = 1 ; $i < 13 ; $i++)
        {
            $res[$i] = $result[$i] + 0;
        }

        $data = array();

        for ($i = 1 ; $i < 13 ; $i++)
        {
            $data[$i-1] = array(dol_print_date(dol_mktime(12,0,0,$i,1,$year),"%b"), $res[$i]);
        }

        return $data;
    }


    /**
     * Return the expeditions number by month for a year
     *
     * @param	int		$year		Year
     * @return	array				Array with number by month
     */
    function getNbExpeditionByMonthWithPrevYear($year)
    {
        $data1 = $this->getNbExpeditionByMonth($year);
        $data2 = $this->getNbExpeditionByMonth($year - 1);

        $data = array();

        for ($i = 1 ; $i < 13 ; $i++)
        {
            $data[$i-1] = array(dol_print_date(dol_mktime(12,0,0,$i,1,$year),"%b"),
            $data1[$i][1],
            $data2[$i][1]);
        }
        return $data;
    }

}

?>
