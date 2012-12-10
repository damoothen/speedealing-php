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
 */

/**
 *		\file       htdocs/boutique/critiques/class/critique.class.php
 *		\ingroup    osc
 *		\brief      Fichier de la classe des critiques OSCommerce
 */


/**
 *		Classe permettant la gestion des critiques OSCommerce
 */
class Critique
{
	var $db;

	var $id;
	var $nom;

	/**
	 * Constructor
	 *
	 * @param	DoliDB		$db		Database handler
	 */
	function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Load instance
	 *
	 *	@param	int		$id		Id to load
	 *	@return	int				<0 if KO, >0 if OK
	 */
	function fetch ($id)
	{
		global $conf;

		$sql = "SELECT r.reviews_id, r.reviews_rating, d.reviews_text, p.products_name";

		$sql .= " FROM ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."reviews as r, ".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."reviews_description as d";
		$sql .= " ,".$conf->global->OSC_DB_NAME.".".$conf->global->OSC_DB_TABLE_PREFIX."products_description as p";

		$sql .= " WHERE r.reviews_id = d.reviews_id AND r.products_id=p.products_id";
		$sql .= " AND p.language_id = ".$conf->global->OSC_LANGUAGE_ID. " AND d.languages_id=".$conf->global->OSC_LANGUAGE_ID;
		$sql .= " AND r.reviews_id=$id";

		$result = $this->db->query($sql);

		if ( $result )
		{
			$result = $this->db->fetch_array($result);

			$this->id           = $result["reviews_id"];
			$this->product_name = stripslashes($result["products_name"]);
			$this->text         = stripslashes($result["reviews_text"]);

			$this->db->free($result);
		}
		else
		{
			print $this->db->lasterror();
		}

		return $result;
	}

}
?>
