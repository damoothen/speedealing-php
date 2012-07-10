<?php

/* Copyright (C) 2012      Herve Prot           <herve.prot@symeos.com>
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

require_once(DOL_DOCUMENT_ROOT . "/core/class/nosqlDocument.class.php");

/**
 * 	Class to manage Dolibarr users
 */
class Search extends nosqlDocument {

	/**
	 *    Constructor de la classe
	 *
	 *    @param   DoliDb  $db     Database handler
	 */

	function __construct($db) {
		$this->db = $db;

		parent::__construct($db);
	}
}

?>
