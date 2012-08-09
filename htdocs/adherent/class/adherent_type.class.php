<?php

/* Copyright (C) 2002      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
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

/**
 * 	\class      AdherentType
 * 	\brief      Class to manage members type
 */
class AdherentType extends nosqlDocument {

	public $table_element = 'adherent_type';
	var $id;
	var $libelle;
	var $Status;
	var $cotisation;  // Soumis a la cotisation
	var $vote; // droit de vote
	var $note;  // commentaire
	var $mail_valid;  //mail envoye lors de la validation

	/**
	 * 	Constructor
	 *
	 * 	@param 		DoliDB		$DB		Database handler
	 */

	function __construct($DB) {
		parent::__construct($db);

		//$fk_extrafields = new ExtraFields($db);
		//$this->fk_extrafields = $fk_extrafields->load("extrafields:" . get_class($this), true); // load and cache

		try {
			$this->couchdb->useDatabase('adherent');
		} catch (Exception $e) {
			$error = "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
			print $error;
			exit;
		}
		$this->Status = 1;
	}

	/**
	 *  Fonction qui permet de creer le status de l'adherent
	 *
	 *  @param      User		$user		User making creation
	 *  @return     						>0 if OK, < 0 if KO
	 */
	function create($user) {
		global $conf;

		$this->Status = trim($this->Status);

		dol_syslog("Adherent_type::create sql=" . $sql);
		return $this->update($user);
	}

	/**
	 *  Met a jour en base donnees du type
	 *
	 * 	@param		User	$user	Object user making change
	 *  @return		int				>0 if OK, < 0 if KO
	 */
	function update($user) {
		$this->libelle = trim($this->libelle);

		$result = parent::update($user); // save

		if ($result) {
			return 1;
		} else {
			return -1;
		}
	}

	/**
	 * 	Fonction qui permet de supprimer le status de l'adherent
	 *
	 * 	@param      int		$rowid		Id of member type to delete
	 *  @return		int					>0 if OK, < 0 if KO
	 */
	function delete() {
		return $this->deleteDoc();
	}

	/**
	 *  Fonction qui permet de recuperer le status de l'adherent
	 *
	 *  @param 		int		$rowid		Id of member type to load
	 *  @return		int					<0 if KO, >0 if OK
	 */
	function fetch($rowid) {
		$result = $this->load($rowid);
		dol_syslog("Adherent_type::fetch sql=" . $sql);
		return $result;
	}

	/**
	 *  Return list of members' type
	 *
	 *  @return 	array	List of types of members
	 */
	function liste_array() {
		global $conf, $langs;

		$projets = array();

		$result = $this->getView('list');

		if (count($result->rows) > 0)
			foreach ($result->rows as $aRow) {
				$obj = $aRow->value;
				$projets[$obj->_id] = $langs->trans($obj->libelle);
			}

		return $projets;
	}

	/**
	 *    	Renvoie nom clicable (avec eventuellement le picto)
	 *
	 * 		@param		int		$withpicto		0=Pas de picto, 1=Inclut le picto dans le lien, 2=Picto seul
	 * 		@param		int		$maxlen			length max libelle
	 * 		@return		string					String with URL
	 */
	function getNomUrl($withpicto = 0, $maxlen = 0) {
		global $langs;

		$result = '';

		if (!empty($this->id)) {
			$lien = '<a href="' . DOL_URL_ROOT . '/adherent/type.php?id=' . $this->id . '">';
			$lienfin = '</a>';
		}

		$picto = 'group';
		$label = $langs->trans("ShowTypeCard", $this->libelle);

		if ($withpicto)
			$result.=($lien . img_object($label, $picto) . $lienfin);
		if ($withpicto && $withpicto != 2)
			$result.=' ';
		$result.=$lien . ($maxlen ? dol_trunc($this->libelle, $maxlen) : $this->libelle) . $lienfin;
		return $result;
	}

	/**
	 *     getMailOnValid
	 *
	 *     @return     Return mail model
	 */
	function getMailOnValid() {
		global $conf;

		if (!empty($this->mail_valid) && trim(dol_htmlentitiesbr_decode($this->mail_valid))) {
			return $this->mail_valid;
		} else {
			return $conf->global->ADHERENT_MAIL_VALID;
		}
	}

	/**
	 *     getMailOnSubscription
	 *
	 *     @return     Return mail model
	 */
	function getMailOnSubscription() {
		global $conf;

		if (!empty($this->mail_subscription) && trim(dol_htmlentitiesbr_decode($this->mail_subscription))) {  // Property not yet defined
			return $this->mail_subscription;
		} else {
			return $conf->global->ADHERENT_MAIL_COTIS;
		}
	}

	/**
	 *     getMailOnResiliate
	 *
	 *     @return     Return mail model
	 */
	function getMailOnResiliate() {
		global $conf;

		if (!empty($this->mail_resiliate) && trim(dol_htmlentitiesbr_decode($this->mail_resiliate))) {  // Property not yet defined
			return $this->mail_resiliate;
		} else {
			return $conf->global->ADHERENT_MAIL_RESIL;
		}
	}

}

?>
