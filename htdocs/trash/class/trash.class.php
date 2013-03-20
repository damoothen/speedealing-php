<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2013	Herve Prot		<herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

if (!class_exists('nosqlDocument'))
	require DOL_DOCUMENT_ROOT . '/core/class/nosqlDocument.class.php';

/**
 * 	Class to manage all the elements put in the trash
 */
class Trash extends nosqlDocument {

	/**
	 *    Constructor.
	 */

	function __construct() {
		parent::__construct();

		$this->fk_extrafields = new ExtraFields();
		$this->fk_extrafields->fetch(get_class($this));
	}
}

?>
