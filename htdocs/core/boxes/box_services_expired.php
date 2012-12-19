<?php
/* Copyright (C) 2011 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 * 		\file       htdocs/core/boxes/box_services_expired.php
 * 		\ingroup    contracts
 * 		\brief      Module to show the box of last expired services
 */

include_once DOL_DOCUMENT_ROOT.'/core/boxes/modules_boxes.php';


/**
 * Class to manage the box to show expired services
 */
class box_services_expired extends ModeleBoxes
{

    var $boxcode="expiredservices";     // id of box
    var $boximg="object_contract";
    var $boxlabel;
    var $depends = array("contrat");	// conf->propal->enabled

    var $db;
    var $param;

    var $info_box_head = array();
    var $info_box_contents = array();


    /**
     *  Constructor
     */
    function __construct()
    {
    	global $langs;

    	$langs->load("contracts");

    	$this->boxlabel=$langs->transnoentitiesnoconv("BoxOldestExpiredServices");
    }

    /**
     *  Load data for box to show them later
     *
     *  @param	int		$max        Maximum number of records to load
     *  @return	void
     */
    function loadBox($max=5)
    {
    	global $user, $langs, $db, $conf;

    	$this->max=$max;

    	$now=dol_now();

    	$this->info_box_head = array('text' => $langs->trans("BoxLastExpiredServices",$max));

    	if ($user->rights->contrat->lire)
    	{
    	    // Select contracts with at least one expired service
			$sql = "SELECT ";
    		$sql.= " c.rowid, c.ref, c.statut as fk_statut, c.date_contrat,";
			$sql.= " s.nom, s.rowid as socid,";
			$sql.= " MIN(cd.date_fin_validite) as date_line, COUNT(cd.rowid) as nb_services";
    		$sql.= " FROM ".MAIN_DB_PREFIX."contrat as c, ".MAIN_DB_PREFIX."societe s, ".MAIN_DB_PREFIX."contratdet as cd";
            if (!$user->rights->societe->client->voir && !$user->societe_id) $sql.= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
    		$sql.= " WHERE cd.statut = 4 AND cd.date_fin_validite <= '".$db->idate($now)."'";
    		$sql.= " AND c.fk_soc=s.rowid AND cd.fk_contrat=c.rowid AND c.statut > 0";
            if ($user->societe_id) $sql.=' AND c.fk_soc = '.$user->societe_id;
            if (!$user->rights->societe->client->voir  && !$user->societe_id) $sql.= " AND s.rowid = sc.fk_soc AND sc.fk_user = " .$user->id;
    		$sql.= " GROUP BY c.rowid, c.ref, c.statut, c.date_contrat, s.nom, s.rowid";
    		$sql.= " ORDER BY date_line ASC";
    		$sql.= $db->plimit($max, 0);

    		$resql = $db->query($sql);
    		if ($resql)
    		{
    			$num = $db->num_rows($resql);

    			$i = 0;

    			while ($i < $num)
    			{
    			    $late='';

    				$objp = $db->fetch_object($resql);

					$dateline=$db->jdate($objp->date_line);
					if (($dateline + $conf->contrat->services->expires->warning_delay) < $now) $late=img_warning($langs->trans("Late"));

    				$this->info_box_contents[$i][0] = array('td' => 'align="left" width="16"',
    				'logo' => $this->boximg,
    				'url' => DOL_URL_ROOT."/contrat/fiche.php?id=".$objp->rowid);

    				$this->info_box_contents[$i][1] = array('td' => 'align="left"',
    				'text' => ($objp->ref?$objp->ref:$objp->rowid),	// Some contracts have no ref
    				'url' => DOL_URL_ROOT."/contrat/fiche.php?id=".$objp->rowid);

    				$this->info_box_contents[$i][2] = array('td' => 'align="left" width="16"',
    				'logo' => 'company',
    				'url' => DOL_URL_ROOT."/comm/fiche.php?socid=".$objp->socid);

    				$this->info_box_contents[$i][3] = array('td' => 'align="left"',
    				'text' => dol_trunc($objp->nom,40),
    				'url' => DOL_URL_ROOT."/comm/fiche.php?socid=".$objp->socid);

    				$this->info_box_contents[$i][4] = array('td' => 'align="center"',
    				'text' => dol_print_date($dateline,'day'),
    				'text2'=> $late);

    				$this->info_box_contents[$i][5] = array('td' => 'align="right"',
    				'text' => $objp->nb_services);


    				$i++;
    			}

    			if ($num==0) $this->info_box_contents[$i][0] = array('td' => 'align="center"','text'=>$langs->trans("NoExpiredServices"));
    		}
    		else
    		{
    			$this->info_box_contents[0][0] = array(  'td' => 'align="left"',
                                                        'maxlength'=>500,
                                                        'text' => ($db->error().' sql='.$sql));
    		}


    	}
    	else
    	{
    		$this->info_box_contents[0][0] = array('td' => 'align="left"',
    		'text' => $langs->trans("ReadPermissionNotAllowed"));
    	}
    }

	/**
	 *	Method to show box
	 *
	 *	@param	array	$head       Array with properties of box title
	 *	@param  array	$contents   Array with properties of box lines
	 *	@return	void
	 */
    function showBox($head = null, $contents = null)
    {
        parent::showBox($this->info_box_head, $this->info_box_contents);
    }

 }

?>
