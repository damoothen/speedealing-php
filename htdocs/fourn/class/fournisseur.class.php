<?php
/* Copyright (C) 2004-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2006      Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011	   Juanjo Menent		<jmenent@2byte.es>
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
 *	\file       htdocs/fourn/class/fournisseur.class.php
 *	\ingroup    fournisseur,societe
 *	\brief      File of class to manage suppliers
 */
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.commande.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.product.class.php';


/**
 *	\class 	Fournisseur
 *	\brief 	Class to manage suppliers
 */
class Fournisseur extends Societe
{
	var $db;

	/**
	 *	Constructor
	 *
	 *  @param	DoliDB	$db		Database handler
	 */
	function __construct($db)
	{
		$this->db = $db;
		$this->client = 0;
		$this->fournisseur = 0;
		$this->effectif_id  = 0;
		$this->forme_juridique_code  = 0;
	}


	/**
	 * Return nb of orders
	 *
	 * @return 	int		Nb of orders
	 */
	function getNbOfOrders()
	{
		$sql = "SELECT rowid";
		$sql .= " FROM ".MAIN_DB_PREFIX."commande_fournisseur as cf";
		$sql .= " WHERE cf.fk_soc = ".$this->id;

		$resql = $this->db->query($sql);
		if ($resql)
		{
			$num = $this->db->num_rows($resql);

			if ($num == 1)
			{
				$row = $this->db->fetch_row($resql);

				$this->single_open_commande = $row[0];
			}
		}
		return $num;
	}

	/**
	 * Returns number of ref prices (not number of products).
	 *
	 * @return	int		Nb of ref prices, or <0 if error
	 */
	function nbOfProductRefs()
	{
		global $conf;

		$sql = "SELECT count(pfp.rowid) as nb";
		$sql.= " FROM ".MAIN_DB_PREFIX."product_fournisseur_price as pfp";
		$sql.= " WHERE pfp.entity = ".$conf->entity;
		$sql.= " AND pfp.fk_soc = ".$this->id;

		$resql = $this->db->query($sql);
		if ( $resql )
		{
			$obj = $this->db->fetch_object($resql);
			return $obj->nb;
		}
		else
		{
			return -1;
		}
	}

	/**
	 * Load statistics indicators
	 *
	 * @return     int         <0 if KO, >0 if OK
	 */
	function load_state_board()
	{
		global $conf, $user;

		$this->nb=array();
		$clause = "WHERE";

		$sql = "SELECT count(s.rowid) as nb";
		$sql.= " FROM ".MAIN_DB_PREFIX."societe as s";
		if (!$user->rights->societe->client->voir && !$user->societe_id)
		{
			$sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe_commerciaux as sc ON s.rowid = sc.fk_soc";
			$sql.= " WHERE sc.fk_user = " .$user->id;
			$clause = "AND";
		}
		$sql.= " ".$clause." s.fournisseur = 1";
		$sql.= " AND s.entity IN (".getEntity('societe', 1).")";

		$resql=$this->db->query($sql);
		if ($resql)
		{
			while ($obj=$this->db->fetch_object($resql))
			{
				$this->nb["suppliers"]=$obj->nb;
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
	 *  Create a supplier category
	 *
	 *  @param      User	$user       User asking creation
	 *	@param		string	$name		Category name
	 *  @return     int         		<0 if KO, 0 if OK
	 */
	function CreateCategory($user, $name)
	{
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."categorie (label,visible,type)";
		$sql.= " VALUES ";
		$sql.= " ('".$this->db->escape($name)."',1,1)";

		dol_syslog("Fournisseur::CreateCategory sql=".$sql);
		$resql = $this->db->query($sql);
		if ($resql)
		{
			dol_syslog("Fournisseur::CreateCategory : Success");
			return 0;
		}
		else
		{
			$this->error=$this->db->lasterror();
			dol_syslog("Fournisseur::CreateCategory : Failed (".$this->error.")");
			return -1;
		}
	}

	/**
	 * 	Return the suppliers list
	 *
	 *	@return		array		Array of suppliers
	 */
	function ListArray()
	{
		global $conf;

		$arr = array();

		$sql = "SELECT s.rowid, s.nom";
		$sql.= " FROM ".MAIN_DB_PREFIX."societe as s";
		if (!$this->user->rights->societe->client->voir && !$this->user->societe_id) $sql.= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
		$sql.= " WHERE s.fournisseur = 1";
		$sql.= " AND s.entity IN (".getEntity('societe', 1).")";
		if (!$this->user->rights->societe->client->voir && !$this->user->societe_id) $sql.= " AND s.rowid = sc.fk_soc AND sc.fk_user = " .$this->user->id;

		$resql=$this->db->query($sql);

		if ($resql)
		{
			while ($obj=$this->db->fetch_object($resql))
	  {
	  	$arr[$obj->rowid] = stripslashes($obj->nom);
	  }

		}
		else
		{
			dol_print_error($this->db);
			$this->error=$this->db->error();

		}
		return $arr;
	}

}

?>
