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
 *      \file       htdocs/boutique/promotion/class/promotion.class.php
 *      \brief      File of class to manage discounts on online shop
 */

/**
 *      \class      Promotion
 *      \brief      Class to manage discounts on online shop
 */
class Promotion
{
	var $db;

	var $id;
	var $parent_id;
	var $oscid;
	var $ref;
	var $titre;
	var $description;
	var $price;
	var $status;

	/**
	 * 	Constructor
	 *
	 * 	@param		DoliDB		$db		Database handler
	 */
	function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 *	Create promotion
	 *
	 *	@param	User	$user		Object user
	 *	@param	int		$pid		Pid
	 *	@param	int		$percent	Percent
	 *	@return	int					<0 if KO, >0 if OK
	 */
	function create($user, $pid, $percent)
	{
		global $conf;

		$sql = "SELECT products_price ";
		$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products as p";
		$sql .= " WHERE p.products_id = ".$pid;

		$result = $this->db->query($sql);
		if ( $result )
		{
			$result = $this->db->fetch_array($result);
			$this->price_init = $result["products_price"];
		}

		$newprice = $percent * $this->price_init;

		$date_exp = "2003-05-01";  // TODO ????
		
		$now=dol_now();

		$sql = "INSERT INTO ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."specials ";
		$sql .= " (products_id, specials_new_products_price, specials_date_added, specials_last_modified, expires_date, date_status_change, status) ";
		$sql .= " VALUES ($pid, $newprice, '".$this->db->idate($now)."', NULL, '".$this->db->idate($now+3600*24*365)."', NULL, 1)";

		if ($this->db->query($sql) )
		{
			$id = $this->db->last_insert_id(OSC_DB_NAME.".specials");

			return $id;
		}
		else
		{
			print $this->db->error() . ' in ' . $sql;
		}
	}

	/**
	 * 	Update
	 *
	 *	@param	int		$id			id
	 *	@param	User	$user		Object user
	 *	@return	int					<0 if KO, >0 if OK
	 */
	function update($id, $user)
	{
		$sql = "UPDATE ".MAIN_DB_PREFIX."album ";
		$sql .= " SET title = '" . trim($this->titre) ."'";
		$sql .= ",description = '" . trim($this->description) ."'";

		$sql .= " WHERE rowid = " . $id;

		if ( $this->db->query($sql) ) {
			return 1;
		} else {
			print $this->db->error() . ' in ' . $sql;
		}
	}

	/**
	 * 	Set active
	 *
	 *	@param	int		$id			id
	 *	@return	int					<0 if KO, >0 if OK
	 */
	function set_active($id)
	{
		global $conf;

		$sql = "UPDATE ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."specials";
		$sql .= " SET status = 1";

		$sql .= " WHERE products_id = " . $id;

		if ( $this->db->query($sql) ) {
			return 1;
		} else {
			print $this->db->error() . ' in ' . $sql;
		}
	}

	/**
	 * 	Set inactive
	 *
	 *	@param	int		$id			id
	 *	@return	int					<0 if KO, >0 if OK
	 */
	function set_inactive($id)
	{
		global $conf;

		$sql = "UPDATE ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."specials";
		$sql .= " SET status = 0";

		$sql .= " WHERE products_id = " . $id;

		if ( $this->db->query($sql) ) {
			return 1;
		} else {
			print $this->db->error() . ' in ' . $sql;
		}
	}

	/**
	 * 	Fetch datas
	 *
	 *	@param	int		$id			id
	 *	@return	int					<0 if KO, >0 if OK
	 */
	function fetch($id)
	{
		global $conf;

		$sql = "SELECT c.categories_id, cd.categories_name, c.parent_id";
		$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."categories as c,".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."categories_description as cd";
		$sql .= " WHERE c.categories_id = cd.categories_id AND cd.language_id = ".$conf->global->OSC_LANGUAGE_ID;
		$sql .= " AND c.categories_id = ".$id;
		$result = $this->db->query($sql);

		if ( $result ) {
			$result = $this->db->fetch_array($result);

			$this->id          = $result["categories_id"];
			$this->parent_id   = $result["parent_id"];
			$this->name        = $result["categories_name"];
			$this->titre       = $result["title"];
			$this->description = $result["description"];
			$this->oscid       = $result["osc_id"];
		}
		$this->db->free($result);

		return $result;
	}


	/**
	 * 	Delete object
	 *
	 *	@param	User	$user		Object user
	 *	@return	int					<0 if KO, >0 if OK
	 */
	function delete($user)
	{
		global $conf;

		$sql = "DELETE FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products WHERE products_id = $idosc ";

		$sql = "DELETE FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products_to_categories WHERE products_id = $idosc";

		$sql = "DELETE FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products_description WHERE products_id = $idosc";

	}


}
?>
