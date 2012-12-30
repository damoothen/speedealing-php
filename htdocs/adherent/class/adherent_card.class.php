<?php

/* Copyright (C) 2002-2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2009      Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2012      Herve Prot			<herve.prot@symeos.com>
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
			$fk_extrafields = new ExtraFields($db);
			$this->fk_extrafields = $fk_extrafields->load("extrafields:" . get_class($this), true); // load and cache
		} catch (Exception $e) {
			dol_print_error('', $e->getMessage());
			exit;
		}

		return 1;
	}

	/**
	 * Make substitution
	 *
	 * @param	$object				Adherent Object
	 * @return  string      		Value of input text string with substitutions done
	 */
	function makeSubstitution($object) {
		global $conf, $langs;

		$text = $this->body;

		$birthday = dol_print_date($object->naiss, 'day');

		$msgishtml = 0;
		if (dol_textishtml($text, 1))
			$msgishtml = 1;

		$infos = '';

		// Specific for Photo
		$photoName = $object->photo;
		$photoType = $object->_attachments->$photoName->content_type;
		$photoBase64 = $object->getFileBase64($object->photo);
		$photo = '<img alt="User name" class="ego-icon-inner"
                           src="data:' . $PhotoType . ';base64,' . $photoBase64 . '"/>';

		// Substitutions
		$substitutionarray = array(
			'__DOL_MAIN_URL_ROOT__' => DOL_MAIN_URL_ROOT,
			'__ID__' => $msgishtml ? dol_htmlentitiesbr($object->login) : $object->login,
			'__CIVILITE__' => $object->getCivilityLabel($msgishtml ? 0 : 1),
			'__FIRSTNAME__' => $msgishtml ? dol_htmlentitiesbr($object->Firstname) : $object->Firstname,
			'__LASTNAME__' => $msgishtml ? dol_htmlentitiesbr($object->Lastname) : $object->Lastname,
			'__FULLNAME__' => $msgishtml ? dol_htmlentitiesbr($object->getFullName($langs)) : $object->getFullName($langs),
			'__COMPANY__' => $msgishtml ? dol_htmlentitiesbr($object->societe) : $object->societe,
			'__ADDRESS__' => $msgishtml ? dol_htmlentitiesbr($object->address) : $object->address,
			'__ZIP__' => $msgishtml ? dol_htmlentitiesbr($object->zip) : $object->zip,
			'__TOWN__' => $msgishtml ? dol_htmlentitiesbr($object->town) : $object->town,
			'__COUNTRY__' => $msgishtml ? dol_htmlentitiesbr($object->country) : $object->country,
			'__EMAIL__' => $msgishtml ? dol_htmlentitiesbr($object->email) : $object->email,
			'__NAISS__' => $msgishtml ? dol_htmlentitiesbr($birthday) : $birthday,
			'__PHOTO__' => $photo,
			'__LOGIN__' => $msgishtml ? dol_htmlentitiesbr($object->login) : $object->login,
			'__PASSWORD__' => $msgishtml ? dol_htmlentitiesbr($object->pass) : $object->pass,
			'__STATUS__' => $object->getLibStatus(),
			// For backward compatibility
			'__INFOS__' => $msgishtml ? dol_htmlentitiesbr($infos) : $infos,
			'__PRENOM__' => $msgishtml ? dol_htmlentitiesbr($object->Firstname) : $object->Firstname,
			'__NOM__' => $msgishtml ? dol_htmlentitiesbr($object->Lastname) : $object->Lastname,
			'__SOCIETE__' => $msgishtml ? dol_htmlentitiesbr($object->societe) : $object->societe,
			'__ADRESSE__' => $msgishtml ? dol_htmlentitiesbr($object->address) : $object->address,
			'__CP__' => $msgishtml ? dol_htmlentitiesbr($object->zip) : $object->zip,
			'__VILLE__' => $msgishtml ? dol_htmlentitiesbr($object->town) : $object->town,
			'__PAYS__' => $msgishtml ? dol_htmlentitiesbr($object->country) : $object->country,
		);

		complete_substitutions_array($substitutionarray, $langs);

		$this->body = make_substitutions($text, $substitutionarray);
		return 1;
	}

}

?>
