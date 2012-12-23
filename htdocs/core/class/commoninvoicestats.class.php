<?php

/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (c) 2005      Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (c) 2011      Juanjo Menent		<jmenent@2byte.es>
 * Copyright (c) 2011      David Moothen		<dmoothen@websitti.fr>
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
 * 	\file       htdocs/comm/propal/class/propalestats.class.php
 * 	\ingroup    propales
 * 	\brief      Fichier de la classe de gestion des stats des propales
 */
include_once DOL_DOCUMENT_ROOT . '/core/class/stats.class.php';

/**
 * 	\class      PropaleStats
 * 	\brief      Classe permettant la gestion des stats pour les modules de facturation
 */
class CommonInvoiceStats extends Stats {

    public $element;
    var $socid;
    var $userid;

    /**
     * Constructor
     *
     * @param 	DoliDB	$db		   Database handler
     * @param 	int		$socid	   Id third party
     * @param   int		$userid    Id user for filter
     */
    function __construct($db, $socid = 0, $userid = 0) {
        global $user, $conf;

        $this->db = $db;
        $this->socid = $socid;
        $this->userid = $userid;

    }
    
    public function count($timestampStart, $timestampEnd){
        
        $object = new $this->element($this->db);
        $res = $object->getView('countByDate', array('startkey' => $timestampStart, 'endkey' => $timestampEnd));
        if (empty($res->rows)) return 0;
        return $res->rows[0]->value;
        
    }
    
    public function amount($timestampStart, $timestampEnd){
        
        $object = new $this->element($this->db);
        $res = $object->getView('amountByDate', array('startkey' => $timestampStart, 'endkey' => $timestampEnd));
        if (empty($res->rows)) return 0;
        return $res->rows[0]->value;
        
    }
    
    public function average($timestampStart, $timestampEnd){
        
        $object = new $this->element($this->db);
        $res = $object->getView('averageByDate', array('startkey' => $timestampStart, 'endkey' => $timestampEnd));
        if (empty($res->rows)) return 0;
        return $res->rows[0]->value;
        
    }

    /**
     * Return propals number by month for a year
     * 
     * @param	int		$year	year for stats
     * @return	array			array with number by month
     */
    function getNbByMonth($year) {
        global $user;

        $data = array();
        for ($i = 1; $i < 13; $i++) {
            $timestampStart = dol_mktime(0, 0, 0, $i, 1, $year);
            $nbDays = date('t', $timestampStart);
            $timestampEnd = dol_mktime(0, 0, 0, $i, $nbDays, $year);
            $nbElements = $this->count($timestampStart, $timestampEnd);
            $month=dol_print_date(dol_mktime(12,0,0,$i,1,$year),"%b");
            $month=dol_substr($month,0,3);
            $data[$i-1] = array(ucfirst($month), $nbElements);
        }
        return $data;

//        $sql = "SELECT date_format(p.datep,'%m') as dm, count(*)";
//        $sql.= " FROM " . $this->from;
//        if (!$user->rights->societe->client->voir && !$user->societe_id)
//            $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
//        $sql.= " WHERE date_format(p.datep,'%Y') = '" . $year . "'";
//        $sql.= " AND " . $this->where;
//        $sql.= " GROUP BY dm";
//        $sql.= $this->db->order('dm', 'DESC');
//
//        return $this->_getNbByMonth($year, $sql);
    }

    /**
     * Return propals number by year
     * 
     * @return	array	array with number by year
     *
     */
//    function getNbByYear() {
//        global $user;
//
//        $sql = "SELECT date_format(p.datep,'%Y') as dm, count(*)";
//        $sql.= " FROM " . $this->from;
//        if (!$user->rights->societe->client->voir && !$user->societe_id)
//            $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
//        $sql.= " WHERE " . $this->where;
//        $sql.= " GROUP BY dm";
//        $sql.= $this->db->order('dm', 'DESC');
//
//        return $this->_getNbByYear($sql);
//    }

    /**
     * Return the propals amount by month for a year
     *
     * @param	int		$year	year for stats
     * @return	array			array with number by month
     */
    function getAmountByMonth($year) {
        global $user;
        
        $data = array();
        for ($i = 1; $i < 13; $i++) {
            $timestampStart = dol_mktime(0, 0, 0, $i, 1, $year);
            $nbDays = date('t', $timestampStart);
            $timestampEnd = dol_mktime(0, 0, 0, $i, $nbDays, $year);
            $amount = $this->amount($timestampStart, $timestampEnd);
            $month=dol_print_date(dol_mktime(12,0,0,$i,1,$year),"%b");
            $month=dol_substr($month,0,3);
            $data[$i-1] = array(ucfirst($month), $amount);
        }
        return $data;

//        $sql = "SELECT date_format(p.datep,'%m') as dm, sum(p." . $this->field . ")";
//        $sql.= " FROM " . $this->from;
//        if (!$user->rights->societe->client->voir && !$user->societe_id)
//            $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
//        $sql.= " WHERE date_format(p.datep,'%Y') = '" . $year . "'";
//        $sql.= " AND " . $this->where;
//        $sql.= " GROUP BY dm";
//        $sql.= $this->db->order('dm', 'DESC');
//
//        return $this->_getAmountByMonth($year, $sql);
    }

    /**
     * Return the propals amount average by month for a year
     *
     * @param	int		$year	year for stats
     * @return	array			array with number by month
     */
    function getAverageByMonth($year) {
        global $user;

        $data = array();
        for ($i = 1; $i < 13; $i++) {
            $timestampStart = dol_mktime(0, 0, 0, $i, 1, $year);
            $nbDays = date('t', $timestampStart);
            $timestampEnd = dol_mktime(0, 0, 0, $i, $nbDays, $year);
            $average = $this->average($timestampStart, $timestampEnd);
            $month=dol_print_date(dol_mktime(12,0,0,$i,1,$year),"%b");
            $month=dol_substr($month,0,3);
            $data[$i-1] = array(ucfirst($month), $average);
        }
        return $data;
        
//        $sql = "SELECT date_format(p.datep,'%m') as dm, avg(p." . $this->field . ")";
//        $sql.= " FROM " . $this->from;
//        if (!$user->rights->societe->client->voir && !$this->socid)
//            $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
//        $sql.= " WHERE date_format(p.datep,'%Y') = '" . $year . "'";
//        $sql.= " AND " . $this->where;
//        $sql.= " GROUP BY dm";
//        $sql.= $this->db->order('dm', 'DESC');
//
//        return $this->_getAverageByMonth($year, $sql);
    }

    /**
     * 	Return nb, total and average
     * 	
     * 	@return	array	Array of values
     */
    function getAllByYear() {
        global $user;
        
        $data = array();

        // get year of oldest element
        $object = new $this->element($this->db);
        $res = $object->getView('listByDate', array('limit' => 1));
        if (empty($res->rows)) return $data;

        $tmp = $res->rows[0]->key;
        $year = date('Y', $tmp);
        for ($i = date('Y'); $i >= $year; $i--) {
            $timestampStart = dol_mktime(0, 0, 0, 1, 1, $i);
            $timestampEnd = dol_mktime(23, 59, 59, 12, 31, $i);
            $count = $this->count($timestampStart, $timestampEnd);
            $amount = $this->amount($timestampStart, $timestampEnd);
            $average = $this->average($timestampStart, $timestampEnd);
            $tab = array(
                'year' =>$i, 
                'nb' =>$count, 
                'total' => $amount, 
                'avg' =>$average
            );
            $data[] = $tab;
        }
                
        return $data;

//        $sql = "SELECT date_format(p.datep,'%Y') as year, count(*) as nb, sum(" . $this->field . ") as total, avg(" . $this->field . ") as avg";
//        $sql.= " FROM " . $this->from;
//        if (!$user->rights->societe->client->voir && !$this->socid)
//            $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
//        $sql.= " WHERE " . $this->where;
//        $sql.= " GROUP BY year";
//        $sql.= $this->db->order('year', 'DESC');
//
//        return $this->_getAllByYear($sql);
    }

}

?>
