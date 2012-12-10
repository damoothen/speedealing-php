<?php

/* Copyright (C) 2012      Herve Prot           <herve.prot@symeos.com>
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

require_once(DOL_DOCUMENT_ROOT . "/core/class/nosqlDocument.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/extrafields.class.php");

/**
 * 	Class to manage Dolibarr users
 */
class Dict extends nosqlDocument {

	public $element = 'dict';
	public $table_element = 'dict';
	
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
