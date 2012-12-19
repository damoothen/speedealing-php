<?php
/* Copyright (C) 2002      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2005 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *       \file       htdocs/product/class/service.class.php
 *       \ingroup    service
 *       \brief      Fichier de la classe des services predefinis
 */
require_once DOL_DOCUMENT_ROOT .'/core/class/commonobject.class.php';


/**
 *       \class      Service
 *       \brief      Classe permettant la gestion des services predefinis
 */
class Service extends CommonObject
{
	var $db;

	var $id;
	var $libelle;
	var $price;
	var $tms;
	var $debut;
	var $fin;

	var $debut_epoch;
	var $fin_epoch;

	/**
	*  Constructor
	*
	*  @param      DoliDB		$db      Database handler
	*/
	function __construct($db)
	{
		$this->db = $db;
	}


	/**
	 *	Charge indicateurs this->nb de tableau de bord
	 *
	 *	@return     int         <0 if KO, >0 if OK
	 */
	function load_state_board()
	{
		global $conf, $user;

		$this->nb=array();

		$sql = "SELECT count(p.rowid) as nb";
		$sql.= " FROM ".MAIN_DB_PREFIX."product as p";
		$sql.= ' WHERE p.entity IN ('.getEntity('product', 1).')';
		$sql.= " AND p.fk_product_type = 1";

		$resql=$this->db->query($sql);
		if ($resql)
		{
			while ($obj=$this->db->fetch_object($resql))
			{
				$this->nb["services"]=$obj->nb;
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

}
?>
