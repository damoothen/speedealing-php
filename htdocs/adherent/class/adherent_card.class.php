<?php

/* Copyright (C) 2002-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2009      Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2012      Herve Prot			<herve.prot@symeos.com>
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

require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent.class.php");

class AdherentCard extends Adherent {
	
	/**
	 * 	Constructor
	 *
	 * 	@param 		DoliDB		$db		Database handler
	 */
	function __construct($db) {
		parent::__construct($db);

		try {
			$this->couchdb->useDatabase('adherent');
			
			$fk_extrafields = new ExtraFields($db);
			$fk_extrafields->useDatabase('adherent');
			$this->fk_extrafields = $fk_extrafields->load("extrafields:" . get_class($this), true); // load and cache
		} catch (Exception $e) {
			dol_print_error('',$e->getMessage());
			exit;
		}

		return 1;
	}
}

?>
