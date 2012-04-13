<?php
/* Copyright (C) 2004      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011      Herve Prot           <herve.prot@symeos.com>
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
 *   	\file       htdocs/comm/prospect/class/prospect.class.php
 *		\ingroup    societe
 *		\brief      Fichier de la classe des prospects
 */
include_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");


/**
 *      \class      Prospect
 *		\brief      Classe permettant la gestion des prospects
 */
class Prospect extends Societe
{
    var $db;


    /**
     *	Constructor
     *
     *	@param	DoliDB	$db		Databas handler
     */
    function Prospect($db)
    {
        global $config;

        $this->db = $db;

        return 0;
    }


    /**
     *  Charge indicateurs this->nb de tableau de bord
     *
     *  @return     int         <0 if KO, >0 if OK
     */
    function load_state_board()
    {
        global $conf, $user;

        $this->nb=array("customers" => 0,"prospects" => 0, "suspects" => 0);
        $clause = "WHERE";

        $sql = "SELECT count(s.rowid) as nb, st.type";
        $sql.= " FROM ".MAIN_DB_PREFIX."societe as s";
        $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."c_stcomm as st ON st.id = s.fk_stcomm";
        if (!$user->rights->societe->client->voir && !$user->societe_id)
        {
        	$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe_commerciaux as sc ON s.rowid = sc.fk_soc";
        	$sql.= " WHERE sc.fk_user = " .$user->id;
        	$clause = "AND";
        }
        $sql.= " ".$clause." s.client in (1,2,3)";
        $sql.= " AND s.entity IN (".getEntity($this->element, 1).")";
        //$sql.= " AND st.type = 0";
        $sql.= " GROUP BY st.type";
        
        //print $sql;

        $resql=$this->db->query($sql);
        if ($resql)
        {
            while ($obj=$this->db->fetch_object($resql))
            {
                if ($obj->type == 2) $this->nb["customers"]+=$obj->nb;
                if ($obj->type == 1) $this->nb["prospects"]+=$obj->nb;
                if ($obj->type == 0) $this->nb["suspects"]+=$obj->nb;
            }
            return 1;
        }
        else
        {
            dol_print_error($this->db);
            $this->error=$this->db->error();
            return -1;
        }
    }

    /**
     *     \brief      Return list icon of prospect
     */
    function getIconList($url)
    {
        global $langs;

        $sql = "SELECT id,libelle";
        $sql.= " FROM " .MAIN_DB_PREFIX."c_stcomm";
        $sql.= " WHERE id != ".$this->stcomm_id;
        $sql.= " AND active=1";
        $sql.= " AND ( ";
        //if($this->type==0 || $this->stcomm_id==-1)
            $sql.= " type >= ".$this->type;
        //if($this->type==1)
        //    $sql.= " OR type = ".($this->type+1);
        $sql.= " OR type = -1 )";
        $sql.= " ORDER BY id";
        
        //print $sql;exit;

        $out='';

        $resql=$this->db->query($sql);
        if ($resql)
        {
            $num = $this->db->num_rows($resql);
            if($num>5)
                $num=5;
            $i = 0;
            while ($i < $num)
            {
                $obj = $this->db->fetch_object($resql);

                //$out.='<a href="'.DOL_URL_ROOT.'/comm/prospect/fiche.php?socid='.$this->id.'&amp;stcomm='.$obj->id.'&amp;action=cstc'.(empty($backtopage)?'':'&amp;backtopage='.$backtopage).'">'.img_action($obj->libelle,$obj->id).'</a>';
                $out.='<a href="'.$url.'&amp;stcomm='.$obj->id.'">'.img_action($obj->libelle,$obj->id).'</a>';
                $i++;
            }
        }
        if($out=='')
            return "Error, mode/status not found";
        else
            return $out;
    }

	/**
	 *  Return status of prospect
	 *
	 *  @param	int		$mode       0=libelle long, 1=libelle court, 2=Picto + Libelle court, 3=Picto, 4=Picto + Libelle long
	 *  @return string        		Libelle
	 */
	function getLibProspStatut($mode=0)
	{
		return $this->LibProspStatut($this->stcomm_id,$mode);
	}

	/**
	 *  Return label of a given status
	 *
	 *  @param	int		$statut        	Id statut
	 *  @param  int		$mode          	0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto
	 *  @return string        			Libelle du statut
	 */
	function LibProspStatut($statut,$mode=0)
	{
		global $langs;
		$langs->load('customers');

                $sql = "SELECT libelle";
                $sql.= " FROM " .MAIN_DB_PREFIX."c_stcomm";
                $sql.= " WHERE id = ".$statut;

                $resql=$this->db->query($sql);
                if ($resql)
                {
                    $obj = $this->db->fetch_object($resql);
                    if ($mode == 2)
                        return img_action($langs->trans($obj->libelle),$statut).' '.$langs->trans($obj->libelle);
                    if ($mode == 3)
                        return img_action($langs->trans($obj->libelle),$statut);
                    if ($mode == 4)
                        return img_action($langs->trans($obj->libelle),$statut).' '.$langs->trans($obj->libelle);
                }

		return "Error, mode/status not found";
	}

	/**
	 *	Renvoi le libelle du niveau
	 *
	 *  @return     string        Libelle
	 */
	function getLibLevel()
	{
		return $this->LibLevel($this->fk_prospectlevel);
	}

	/**
	 *  Renvoi le libelle du niveau
	 *
	 *  @param	int		$fk_prospectlevel   	Prospect level
	 *  @return string        					Libelle du niveau
	 */
	function LibLevel($fk_prospectlevel)
	{
		global $langs;

		$lib=$langs->trans("ProspectLevel".$fk_prospectlevel);
		// If lib not found in language file, we get label from cache/databse
		if ($lib == $langs->trans("ProspectLevel".$fk_prospectlevel))
		{
			$lib=$langs->getLabelFromKey($this->db,$fk_prospectlevel,'c_prospectlevel','code','label');
		}
		return $lib;
	}

        /*
         * Prospects par status
         *
         */

        function ProspectStatus()
        {
            global $user, $conf, $langs, $bc;

            $sql = "SELECT count(s.rowid) as cc,st.libelle,st.type,st.id";
            $sql.= " FROM ".MAIN_DB_PREFIX."c_stcomm as st";
            //if (!$user->rights->societe->client->voir)
            //{
            //        $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."user as u on u.rowid = sc.fk_user";
            //}
            $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s on (s.fk_stcomm = st.id AND s.client IN (1,2,3) AND s.entity = ".$conf->entity.")";
            if (!$user->rights->societe->client->voir)
            {
                $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe_commerciaux as sc on (s.rowid = sc.fk_soc)";
            }
            //$sql.=")";
            $sql.= " WHERE st.active=1";
            if (!$user->rights->societe->client->voir)
                $sql.= " AND sc.fk_user = " .$user->id;
            
            $sql.= " GROUP BY st.id";
            $sql.= " ORDER BY st.id DESC";

            
            //print $sql;exit;

            $array=array();
            $color=array(-1=>"#A51B00",0=>"#CCC",1=>"#000",2=>"#FEF4AE",3=>"#666",4=>"#1f17c1",5=>"#DE7603",6=>"#D40000",7=>"#7ac52e",8=>"#1b651b",9=>"#66c18c",10=>"#2e99a0");
            $total=0;

            $resql=$this->db->query($sql);
            if ($resql)
            {
                $num = $this->db->num_rows($resql);
                $i = 0;

                while ($i < $num)
                {
                    $obj = $this->db->fetch_object($resql);

                    $element=array();
                    $element['id']=$obj->id;
                    $element['count']=$obj->cc;
                    $element['libelle']=$obj->libelle;
                    $element['type']=$obj->type;
                    $total+=$obj->cc;

                    $array[$i]=$element;

                    $i++;
                }
            }

            $var=false;

            print '<table class="noborder" width="100%">';
            print '<tr class="liste_titre">';
            print '<td colspan="2">'.$langs->trans("ProspectsByStatus").'</td></tr>';

                for($i=0, $size=sizeof($array); $i < $size; $i++)
                {
                    print '<tr '.$bc[$var].'><td><a href='.DOL_URL_ROOT.'/comm/list.php?pstcomm='.$array[$i]['id'].'>';
                    print img_action($langs->trans("Show"),$array[$i]['id']).' ';
                    print $langs->trans($array[$i]['libelle']);

                    print '</a></td><td align="right">'.$array[$i]['count'].'</td></tr>';
                   
                    $var=!$var;
                }

            print '</td></tr>';
            print "</table><br>";
       }

}
?>
