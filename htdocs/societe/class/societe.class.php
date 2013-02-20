<?php
/* Copyright (C) 2002-2006 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2003      Brian Fraval         <brian@fraval.org>
 * Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2008      Patrick Raguin       <patrick.raguin@auguria.net>
 * Copyright (C) 2010-2011 Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2010-2012 Herve Prot           <herve.prot@symeos.com>
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

require_once DOL_DOCUMENT_ROOT . "/core/class/extrafields.class.php";

/**
 * 	Class to manage third parties objects (customers, suppliers, prospects...)
 */
class Societe extends nosqlDocument {

	public $element = 'societe';
	public $table_element = 'societe';
	public $fk_element = 'fk_soc';
	protected $childtables = array("propal", "commande", "facture", "contrat", "facture_fourn", "commande_fournisseur");	// To test if we can delete object
	protected $ismultientitymanaged = 1; // 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
	var $id;
	var $name;
	var $nom;	  // TODO obsolete
	var $firstname;
	var $particulier;
	var $civility_id;
	var $address;
	var $adresse;  // TODO obsolete
	var $cp;	   // TODO obsolete
	var $zip;
	var $ville;	// TODO obsolete
	var $town;
	var $status;   // 0=activity ceased, 1= in activity
	var $state_id;
	var $state_code;
	var $state;
	var $departement_id;	 // deprecated
	var $departement_code;   // deprecated
	var $departement;		// deprecated
	var $pays_id;   // deprecated
	var $pays_code; // deprecated
	var $pays;	 // deprecated
	var $country_id;
	var $country_code;
	var $country;
	var $tel;		// deprecated
	var $phone;
	var $fax;
	var $email;
	var $url;
	//! barcode
	var $barcode;			   // value
	var $barcode_type;		  // id
	var $barcode_type_code;	 // code (loaded by fetch_barcode)
	var $barcode_type_label;	// label (loaded by fetch_barcode)
	var $barcode_type_coder;	// coder (loaded by fetch_barcode)
	// 4 professional id (usage depend on country)
	var $idprof1; // IdProf1 (Ex: Siren in France)
	var $idprof2; // IdProf2 (Ex: Siret in France)
	var $idprof3; // IdProf3 (Ex: Ape in France)
	var $idprof4; // IdProf4 (Ex: RCS in France)
	var $prefix_comm;
	var $tva_assuj;
	var $tva_intra;
	// Local taxes
	var $localtax1_assuj;
	var $localtax2_assuj;
	var $capital;
	var $typent_id;
	var $typent_code;
	var $effectif_id;
	var $forme_juridique_code;
	var $forme_juridique;
	var $remise_percent;
	var $mode_reglement_id;
	var $cond_reglement_id;
	var $remise_client;  // TODO obsolete
	var $mode_reglement; // TODO obsolete
	var $cond_reglement; // TODO obsolete
	var $client;	 // 0=no customer, 1=customer, 2=prospect, 3=customer and prospect
	var $prospect;	 // 0=no prospect, 1=prospect
	var $fournisseur;	// 0=no supplier, 1=supplier
	var $code_client;
	var $code_fournisseur;
	var $code_compta;
	var $code_compta_fournisseur;
	var $note;
	//! code statut prospect
	var $stcomm_id;
	var $statut_commercial;
	var $price_level;
	var $datec;
	var $date_update;
	var $commercial_id;  // Id of sales representative to link (used for thirdparty creation). Not filled by a fetch, because we can have several sales representatives.
	var $default_lang;
	var $ref_int;
	var $import_key;
	var $logo;

	/**
	 *    Constructor
	 *
	 *    @param	DoliDB		$db		Database handler
	 */
	public function __construct($db) {
		parent::__construct($db);

		$this->fk_extrafields = new ExtraFields($db);
		$this->fk_extrafields->fetch(get_class($this));

		$this->commercial_id = new stdClass();

		return 1;
	}

	/**
	 *    Create third party in database
	 *
	 *    @param	User	$user       Object of user that ask creation
	 *    @return   int         		>= 0 if OK, < 0 if KO
	 */
	function create($user = '') {
		global $langs, $conf, $user;

		$error = 0;

		$this->name = trim($this->name);
		$this->name = ucwords($this->name);

		if (empty($this->client))
			$this->client = 0;
		if (empty($this->fournisseur))
			$this->fournisseur = 0;
		$this->import_key = trim($this->import_key);

		// Check parameters
		if (!empty($conf->global->SOCIETE_MAIL_REQUIRED) && !isValidEMail($this->email)) {
			$langs->load("errors");
			$this->error = $langs->trans("ErrorBadEMail", $this->email);
			return -1;
		}

		$now = dol_now();
		$this->datec = $now;

		// For automatic creation during create action (not used by Dolibarr GUI, can be used by scripts)
		if ($this->code_client == -1)
			$this->get_codeclient($this->prefix_comm, 0);
		if ($this->code_fournisseur == -1)
			$this->get_codefournisseur($this->prefix_comm, 1);

		// Check more parameters
		// If error, this->errors[] is filled
		$result = $this->verify();

		$staticuser = new User($db);

		if ($result >= 0) {

			// Ajout du commercial affecte
			if ($this->commercial_id->id != '' && $this->commercial_id->id != "-1") {
				$this->commercial_id->id = trim($this->commercial_id->id);
				$staticuser->load($this->commercial_id->id);
				$this->commercial_id->name = $staticuser->name;
			}
			// si un commercial cree un client il lui est affecte automatiquement
			else {
				$this->commercial_id->id = $user->id;
				$this->commercial_id->name = $user->name;
			}

			$ret = $this->update('', $user, 0, 1, 1, 'add'); // Record
			// si le fournisseur est classe on l'ajoute
			$this->AddFournisseurInCategory($this->fournisseur_categorie);

			if ($ret >= 0) {
				// Appel des triggers
				include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
				$interface = new Interfaces($this->db);
				$result = $interface->run_triggers('COMPANY_CREATE', $this, $user, $langs, $conf);
				if ($result < 0) {
					$error++;
					$this->errors = $interface->errors;
				}
				// Fin appel triggers

				return $this->id;
			} else {
				return -3;
			}
		} else {
			return -3;
		}
	}

	/**
	 *    Check properties of third party are ok (like name, third party codes, ...)
	 *
	 *    @return     int		0 if OK, <0 if KO
	 */
	function verify() {
		$this->errors = array();

		$result = 0;
		$this->name = trim($this->name);

		if (!$this->name) {
			$this->errors[] = 'ErrorBadThirdPartyName';
			$result = -2;
		}

		if ($this->client && $this->codeclient_modifiable()) {
			// On ne verifie le code client que si la societe est un client / prospect et que le code est modifiable
			// Si il n'est pas modifiable il n'est pas mis a jour lors de l'update
			$rescode = $this->check_codeclient();
			if ($rescode <> 0) {
				if ($rescode == -1) {
					$this->errors[] = 'ErrorBadCustomerCodeSyntax';
				}
				if ($rescode == -2) {
					$this->errors[] = 'ErrorCustomerCodeRequired';
				}
				if ($rescode == -3) {
					$this->errors[] = 'ErrorCustomerCodeAlreadyUsed';
				}
				if ($rescode == -4) {
					$this->errors[] = 'ErrorPrefixRequired';
				}
				$result = -3;
			}
		}

		if ($this->fournisseur && $this->codefournisseur_modifiable()) {
			// On ne verifie le code fournisseur que si la societe est un fournisseur et que le code est modifiable
			// Si il n'est pas modifiable il n'est pas mis a jour lors de l'update
			$rescode = $this->check_codefournisseur();
			if ($rescode <> 0) {
				if ($rescode == -1) {
					$this->errors[] = 'ErrorBadSupplierCodeSyntax';
				}
				if ($rescode == -2) {
					$this->errors[] = 'ErrorSupplierCodeRequired';
				}
				if ($rescode == -3) {
					$this->errors[] = 'ErrorSupplierCodeAlreadyUsed';
				}
				if ($rescode == -5) {
					$this->errors[] = 'ErrorprefixRequired';
				}
				$result = -3;
			}
		}

		return $result;
	}

	/**
	 *  Update GPS coordonnate
	 *
	 */
	function updateGPS() {
		global $conf;

		// Calcul des coordonnées GPS
		if ($conf->map->enabled) {
			//Retire le CEDEX de la ville :
			$town = strtolower($this->town);
			$find = "cedex";
			$pos = strpos($town, $find);
			if ($pos != false) {
				$town = substr($town, 0, $pos);
				//print $town;exit;
			}
			$apiUrl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=" . urlencode($this->address . "," . $this->zip . "," . $this->town);
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $apiUrl);
			curl_setopt($c, CURLOPT_HEADER, false);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			// make the call
			$json = curl_exec($c);
			$response = json_decode($json);
			curl_close($c);
			if ($response->status == "OK") {
				$this->gps = array($response->results[0]->geometry->location->lat,
					$response->results[0]->geometry->location->lng);
			} else {
				unset($this->gps);
			}
		}

		return $this->gps;
	}

	/**
	 *      Update parameters of third party
	 *
	 *      @param	string		$id              			id societe
	 *      @param  User	$user            			Utilisateur qui demande la mise a jour
	 *      @param  int		$call_trigger    			0=non, 1=oui
	 *      @param	int		$allowmodcodeclient			Inclut modif code client et code compta
	 *      @param	int		$allowmodcodefournisseur	Inclut modif code fournisseur et code compta fournisseur
	 *      @param	string	$action						'create' or 'update'
	 *      @return int 		           			<0 if KO, >=0 if OK
	 */
	function update($id, $user = '', $call_trigger = 1, $allowmodcodeclient = 0, $allowmodcodefournisseur = 0, $action = 'update') {
		global $langs, $conf, $hookmanager;

		require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';

		$error = 0;
		$now = dol_now();

		// Clean parameters
		$this->id = $id;
		$this->name = trim($this->name);
		$this->ref_ext = trim($this->ref_ext);
		$this->address = trim($this->address);
		$this->zip = trim($this->zip);
		$this->town = trim($this->town);
		$this->state = trim($this->state_id);
		$this->country = $this->country_id;
		$this->phone = trim($this->phone);
		$this->phone = preg_replace("/\s/", "", $this->phone);
		$this->phone = preg_replace("/\./", "", $this->phone);
		$this->fax = trim($this->fax);
		$this->fax = preg_replace("/\s/", "", $this->fax);
		$this->fax = preg_replace("/\./", "", $this->fax);
		$this->email = trim($this->email);
		$this->url = $this->url ? clean_url($this->url, 0) : '';
		$this->idprof1 = trim($this->idprof1);
		$this->idprof2 = trim($this->idprof2);
		$this->idprof3 = trim($this->idprof3);
		$this->idprof4 = trim($this->idprof4);
		$this->idprof5 = (!empty($this->idprof5) ? trim($this->idprof5) : '');
		$this->idprof6 = (!empty($this->idprof6) ? trim($this->idprof6) : '');
		$this->prefix_comm = trim($this->prefix_comm);

		$this->tva_assuj = trim($this->tva_assuj);
		$this->tva_intra = dol_sanitizeFileName($this->tva_intra, '');

		$this->tms = $now;

		if (empty($this->Status))
			$this->Status = $this->fk_extrafields->fields->Status->default;

		// Local taxes
		$this->localtax1_assuj = trim($this->localtax1_assuj);
		$this->localtax2_assuj = trim($this->localtax2_assuj);

		$this->capital = price2num(trim($this->capital), 'MT');
		if (empty($this->capital))
			$this->capital = 0;

		$this->effectif_id = trim($this->effectif_id);
		$this->forme_juridique_code = trim($this->forme_juridique_code);

		//Gencod
		$this->barcode = trim($this->barcode);

		// For automatic creation
		if ($this->code_client == -1)
			$this->get_codeclient($this->prefix_comm, 0);
		if ($this->code_fournisseur == -1)
			$this->get_codefournisseur($this->prefix_comm, 1);

		$this->code_compta = trim($this->code_compta);
		$this->code_compta_fournisseur = trim($this->code_compta_fournisseur);

		// Check parameters
		if (!empty($conf->global->SOCIETE_MAIL_REQUIRED) && !isValidEMail($this->email)) {
			$langs->load("errors");
			$this->error = $langs->trans("ErrorBadEMail", $this->email);
			return -1;
		}
		if (!is_numeric($this->client) && !is_numeric($this->fournisseur)) {
			$langs->load("errors");
			$this->error = $langs->trans("BadValueForParameterClientOrSupplier");
			return -1;
		}

		$customer = false;
		if (!empty($allowmodcodeclient) && !empty($this->client)) {
			// Attention get_codecompta peut modifier le code suivant le module utilise
			if (empty($this->code_compta)) {
				$ret = $this->get_codecompta('customer');
				if ($ret < 0)
					return -1;
			}

			$customer = true;
		}

		$supplier = false;
		if (!empty($allowmodcodefournisseur) && !empty($this->fournisseur)) {
			// Attention get_codecompta peut modifier le code suivant le module utilise
			if (empty($this->code_compta_fournisseur)) {
				$ret = $this->get_codecompta('supplier');
				if ($ret < 0)
					return -1;
			}

			$supplier = true;
		}

		// Calcul des coordonnées GPS
		$this->updateGPS();

		// Check name is required and codes are ok or unique.
		// If error, this->errors[] is filled
		$result = $this->verify();

		if ($result >= 0) {

			$resql = $this->record();

			if ($resql) {
				unset($this->country_code);
				unset($this->country);
				unset($this->state_code);
				unset($this->state);

				// Si le fournisseur est classe on l'ajoute
				$this->AddFournisseurInCategory($this->fournisseur_categorie);

				// Actions on extra fields (by external module or standard code)
				$hookmanager->initHooks(array('thirdpartydao'));
				$parameters = array('socid' => $this->id);
				$reshook = $hookmanager->executeHooks('insertExtraFields', $parameters, $this, $action);	// Note that $action and $object may have been modified by some hooks
				if (empty($reshook)) {
					$result = $this->insertExtraFields();
					if ($result < 0) {
						$error++;
					}
				} else if ($reshook < 0)
					$error++;

				if (!$error && $call_trigger) {
					// Appel des triggers
					include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
					$interface = new Interfaces($this->db);
					$result = $interface->run_triggers('COMPANY_MODIFY', $this, $user, $langs, $conf);
					if ($result < 0) {
						$error++;
						$this->errors = $interface->errors;
					}
					// Fin appel triggers
				}

				if (!$error) {
					return 1;
				} else {
					return -1;
				}
			} else {
				if ($this->db->errno() == 'DB_ERROR_RECORD_ALREADY_EXISTS') {
					// Doublon
					$this->error = $langs->trans("ErrorDuplicateField");
					$result = -1;
				} else {

					$this->error = $langs->trans("Error sql=" . $sql);
					$result = -2;
				}
				return $result;
			}
		} else {
			return -3;
		}
	}

	/**
	 *    Delete a third party from database and all its dependencies (contacts, rib...)
	 *
	 *    @param	int		$id     Id of third party to delete
	 *    @return	int				<0 if KO, 0 if nothing done, >0 if OK
	 */
	function delete($id) {
		global $user, $langs, $conf, $hookmanager;

		require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

		$error = 0;

		// Test if child exists
		$objectisused = $this->isObjectUsed($id);
		if (empty($objectisused)) {

			require_once DOL_DOCUMENT_ROOT . '/categories/class/categorie.class.php';
			$static_cat = new Categorie($this->db);
			$toute_categs = array();

			// Fill $toute_categs array with an array of (type => array of ("Categorie" instance))
			if ($this->client || $this->prospect) {
				$toute_categs ['societe'] = $static_cat->containing($this->id, 2);
			}
			if ($this->fournisseur) {
				$toute_categs ['fournisseur'] = $static_cat->containing($this->id, 1);
			}

			// Remove each "Categorie"
			foreach ($toute_categs as $type => $categs_type) {
				foreach ($categs_type as $cat) {
					$cat->del_type($this, $type);
				}
			}

			return parent::delete();
/*
			// TODO Supprimer les contacts
			// Remove contacts
			if (!$error) {
				$sql = "DELETE FROM " . MAIN_DB_PREFIX . "socpeople";
				$sql.= " WHERE fk_soc = " . $id;
				if (!$this->db->query($sql)) {
					$error++;
					$this->error .= $this->db->lasterror();
				}
			}

			// Update link in member table
			if (!$error) {
				$sql = "UPDATE " . MAIN_DB_PREFIX . "adherent";
				$sql.= " SET fk_soc = NULL WHERE fk_soc = " . $id;
				if (!$this->db->query($sql)) {
					$error++;
					$this->error .= $this->db->lasterror();
				}
			}

			// Remove ban
			if (!$error) {
				$sql = "DELETE FROM " . MAIN_DB_PREFIX . "societe_rib";
				$sql.= " WHERE fk_soc = " . $id;
				if (!$this->db->query($sql)) {
					$error++;
					$this->error = $this->db->lasterror();
				}
			}
*/
			// Removed extrafields
			//$result=$this->deleteExtraFields($this);
			//if ($result < 0) $error++;

			if (!$error) {
				// Additionnal action by hooks
				$hookmanager->initHooks(array('thirdpartydao'));
				$parameters = array();
				$action = 'delete';
				$reshook = $hookmanager->executeHooks('deleteThirdparty', $parameters, $this, $action);	// Note that $action and $object may have been modified by some hooks
				if (!empty($hookmanager->error)) {
					$error++;
					$this->error = $hookmanager->error;
				}
			}
/*
			// Remove third party
			if (!$error) {
				$sql = "DELETE FROM " . MAIN_DB_PREFIX . "societe";
				$sql.= " WHERE rowid = " . $id;
				if (!$this->db->query($sql)) {
					$error++;
					$this->error = $this->db->lasterror();
				}
			}
*/
			if (!$error) {
				// Appel des triggers
				include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
				$interface = new Interfaces($this->db);
				$result = $interface->run_triggers('COMPANY_DELETE', $this, $user, $langs, $conf);
				if ($result < 0) {
					$error++;
					$this->errors = $interface->errors;
				}
				// Fin appel triggers
			}

			if (!$error) {
				$this->db->commit();

				// Delete directory
				$docdir = $conf->societe->multidir_output[$this->entity] . "/" . $id;
				if (file_exists($docdir)) {
					dol_delete_dir_recursive($docdir);
				}

				return 1;
			} else {
				$this->db->rollback();
				return -1;
			}
		}
	}

	/**
	 *  Define third party as a customer
	 *
	 * 	@return		int		<0 if KO, >0 if OK
	 */
	function set_as_client() {
		if ($this->id) {
			$newclient = 1;
			if ($this->client == 2 || $this->client == 3)
				$newclient = 3; //If prospect, we keep prospect tag
			$sql = "UPDATE " . MAIN_DB_PREFIX . "societe";
			$sql.= " SET client = " . $newclient;
			$sql.= " WHERE rowid = " . $this->id;

			$resql = $this->db->query($sql);
			if ($resql) {
				$this->client = $newclient;
				return 1;
			}
			else
				return -1;
		}
		return 0;
	}

	/**
	 *  Definit la societe comme un client
	 *
	 *  @param	float	$remise		Valeur en % de la remise
	 *  @param  string	$note		Note/Motif de modification de la remise
	 *  @param  User	$user		Utilisateur qui definie la remise
	 * 	@return	int					<0 if KO, >0 if OK
	 */
	function set_remise_client($remise, $note, $user) {
		global $langs;

		// Nettoyage parametres
		$note = trim($note);
		if (!$note) {
			$this->error = $langs->trans("ErrorFieldRequired", $langs->trans("Note"));
			return -2;
		}

		if ($this->id) {
			$this->db->begin();

			$now = dol_now();

			// Positionne remise courante
			$sql = "UPDATE " . MAIN_DB_PREFIX . "societe ";
			$sql.= " SET remise_client = '" . $remise . "'";
			$sql.= " WHERE rowid = " . $this->id . ";";
			$resql = $this->db->query($sql);
			if (!$resql) {
				$this->db->rollback();
				$this->error = $this->db->error();
				return -1;
			}

			// Ecrit trace dans historique des remises
			$sql = "INSERT INTO " . MAIN_DB_PREFIX . "societe_remise ";
			$sql.= " (datec, fk_soc, remise_client, note, fk_user_author)";
			$sql.= " VALUES (" . $this->db->idate($now) . ", " . $this->id . ", '" . $remise . "',";
			$sql.= " '" . $this->db->escape($note) . "',";
			$sql.= " " . $user->id;
			$sql.= ")";

			$resql = $this->db->query($sql);
			if (!$resql) {
				$this->db->rollback();
				$this->error = $this->db->error();
				return -1;
			}

			$this->db->commit();
			return 1;
		}
	}

	/**
	 *    	Add a discount for third party
	 *
	 *    	@param	float	$remise     Amount of discount
	 *    	@param  User	$user       User adding discount
	 *    	@param  string	$desc		Reason of discount
	 *      @param  float	$tva_tx     VAT rate
	 * 		@return	int					<0 if KO, id of discount record if OK
	 */
	function set_remise_except($remise, $user, $desc, $tva_tx = 0) {
		global $langs;

		// Clean parameters
		$remise = price2num($remise);
		$desc = trim($desc);

		// Check parameters
		if (!$remise > 0) {
			$this->error = $langs->trans("ErrorWrongValueForParameter", "1");
			return -1;
		}
		if (!$desc) {
			$this->error = $langs->trans("ErrorWrongValueForParameter", "3");
			return -2;
		}

		if ($this->id) {
			require_once DOL_DOCUMENT_ROOT . '/core/class/discount.class.php';

			$discount = new DiscountAbsolute($this->db);
			$discount->fk_soc = $this->id;
			$discount->amount_ht = price2num($remise, 'MT');
			$discount->amount_tva = price2num($remise * $tva_tx / 100, 'MT');
			$discount->amount_ttc = price2num($discount->amount_ht + $discount->amount_tva, 'MT');
			$discount->tva_tx = price2num($tva_tx, 'MT');
			$discount->description = $desc;
			$result = $discount->create($user);
			if ($result > 0) {
				return $result;
			} else {
				$this->error = $discount->error;
				return -3;
			}
		}
		else
			return 0;
	}

	/**
	 *  Renvoie montant TTC des reductions/avoirs en cours disponibles de la societe
	 *
	 * 	@param	User	$user		Filtre sur un user auteur des remises
	 * 	@param	string	$filter		Filtre autre
	 * 	@param	string	$maxvalue	Filter on max value for discount
	 * 	@return	int					<0 if KO, Credit note amount otherwise
	 */
	function getAvailableDiscounts($user = '', $filter = '', $maxvalue = 0) {
		require_once DOL_DOCUMENT_ROOT . '/core/class/discount.class.php';

		$discountstatic = new DiscountAbsolute($this->db);
		$result = $discountstatic->getAvailableDiscounts($this, $user, $filter, $maxvalue);
		if ($result >= 0) {
			return $result;
		} else {
			$this->error = $discountstatic->error;
			return -1;
		}
	}

	/**
	 *  Return array of sales representatives
	 *
	 *  @param	User	$user		Object user
	 *  @return array       		Array of sales representatives of third party
	 */
	function getSalesRepresentatives($user = '') {
		global $conf;

		$reparray = array();

		$num = count($this->commerciaux);
		$i = 0;
		if ($num > 0)
			foreach ($this->commerciaux as $aRow) {
				$reparray[$i]['id'] = $aRow;
				$i++;
			}
		return $reparray;
	}

	/**
	 * Set the price level
	 *
	 * @param 	int		$price_level	Level of price
	 * @param 	User	$user			Use making change
	 * @return	int						<0 if KO, >0 if OK
	 */
	function set_price_level($price_level, $user) {
		if ($this->id) {
			$now = dol_now();

			$sql = "UPDATE " . MAIN_DB_PREFIX . "societe ";
			$sql .= " SET price_level = '" . $price_level . "'";
			$sql .= " WHERE rowid = " . $this->id;

			$this->db->query($sql);

			$sql = "INSERT INTO " . MAIN_DB_PREFIX . "societe_prices ";
			$sql .= " ( datec, fk_soc, price_level, fk_user_author )";
			$sql .= " VALUES ('" . $this->db->idate($now) . "'," . $this->id . ",'" . $price_level . "'," . $user->id . ")";

			if (!$this->db->query($sql)) {
				dol_print_error($this->db);
				return -1;
			}
			return 1;
		}
		return -1;
	}

	/**
	 *    	Return a link on thirdparty (with picto)
	 *
	 * 		@param	int		$withpicto		Add picto into link (0=No picto, 1=Include picto with link, 2=Picto only)
	 * 		@param	string	$option			Target of link ('', 'customer', 'prospect', 'supplier')
	 * 		@param	int		$maxlen			Max length of text
	 * 		@return	string					String with URL
	 */
	function getNomUrl($withpicto = 0, $option = '', $maxlen = 0) {
		global $conf, $langs;

		$name = $this->name;

		$result = '';
		$lien = $lienfin = '';

		$lien = '<a href="' . DOL_URL_ROOT . '/societe/fiche.php?id=' . $this->id();

		// Add type of canvas
		$lien.=(!empty($this->canvas) ? '&amp;canvas=' . $this->canvas : '') . '">';
		$lienfin = '</a>';

		if ($withpicto)
			$result.=($lien . img_object($langs->trans("ShowCompany") . ': ' . $name, 'company') . $lienfin);
		if ($withpicto && $withpicto != 2)
			$result.=' ';
		$result.=$lien . ($maxlen ? dol_trunc($name, $maxlen) : $name) . $lienfin;

		return $result;
	}

	/**
	 * 	Return full address of third party
	 *
	 * 	@param		int			$withcountry		1=Add country into address string
	 *  @param		string		$sep				Separator to use to build string
	 * 	@return		string							Full address string
	 */
	function getFullAddress($withcountry = 0, $sep = "\n") {
		$ret = '';
		if ($withcountry && $this->country_id && (empty($this->country_code) || empty($this->country))) {
			require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
			$tmparray = getCountry($this->country_id, 'all');
			$this->country_code = $tmparray['code'];
			$this->country = $tmparray['label'];
		}

		if (in_array($this->country_code, array('US'))) {
			$ret.=($this->address ? $this->address . $sep : '');
			$ret.=trim($this->zip . ' ' . $this->town);
			if ($withcountry)
				$ret.=($this->country ? $sep . $this->country : '');
		}
		else {
			$ret.=($this->address ? $this->address . $sep : '');
			$ret.=trim($this->zip . ' ' . $this->town);
			if ($withcountry)
				$ret.=($this->country ? $sep . $this->country : '');
		}
		return trim($ret);
	}

	/**
	 *    Return list of contacts emails existing for third party
	 *
	 * 	  @param	  int		$addthirdparty		1=Add also a record for thirdparty email
	 *    @return     array       					Array of contacts emails
	 */
	function thirdparty_and_contact_email_array($addthirdparty = 0) {
		global $langs;

		$contact_emails = $this->contact_property_array('email');
		if ($this->email && $addthirdparty) {
			if (empty($this->name))
				$this->name = $this->nom;
			// TODO: Tester si email non deja present dans tableau contact
			$contact_emails['thirdparty'] = $langs->trans("ThirdParty") . ': ' . dol_trunc($this->name, 16) . " &lt;" . $this->email . "&gt;";
		}
		return $contact_emails;
	}

	/**
	 *    Return list of contacts mobile phone existing for third party
	 *
	 *    @return     array       Array of contacts emails
	 */
	function thirdparty_and_contact_phone_array() {
		global $langs;

		if (empty($this->phone) && !empty($this->tel))
			$this->phone = $this->tel;

		$contact_phone = $this->contact_property_array('mobile');
		if ($this->phone) {
			if (empty($this->name))
				$this->name = $this->nom;
			// TODO: Tester si tel non deja present dans tableau contact
			$contact_phone['thirdparty'] = $langs->trans("ThirdParty") . ': ' . dol_trunc($this->name, 16) . " &lt;" . $this->phone . "&gt;";
		}
		return $contact_phone;
	}

	/**
	 *    Return list of contacts emails or mobile existing for third party
	 *
	 *    @param	string	$mode       'email' or 'mobile'
	 *    @return   array       		Array of contacts emails or mobile
	 */
	function contact_property_array($mode = 'email') {
		$contact_property = array();

		$sql = "SELECT rowid, email, phone_mobile, name, firstname";
		$sql.= " FROM " . MAIN_DB_PREFIX . "socpeople";
		$sql.= " WHERE fk_soc = '" . $this->id . "'";

		$resql = $this->db->query($sql);
		if ($resql) {
			$nump = $this->db->num_rows($resql);
			if ($nump) {
				$i = 0;
				while ($i < $nump) {
					$obj = $this->db->fetch_object($resql);
					if ($mode == 'email')
						$property = $obj->email;
					else if ($mode == 'mobile')
						$property = $obj->phone_mobile;
					$contact_property[$obj->rowid] = trim($obj->firstname . " " . $obj->name) . " &lt;" . $property . "&gt;";
					$i++;
				}
			}
		}
		else {
			dol_print_error($this->db);
		}
		return $contact_property;
	}

	/**
	 *    Renvoie la liste des contacts de cette societe
	 *
	 *    @return     array      tableau des contacts
	 */
	function contact_array() {
		$contacts = array();

		$sql = "SELECT rowid, name, firstname FROM " . MAIN_DB_PREFIX . "socpeople WHERE fk_soc = '" . $this->id . "'";
		$resql = $this->db->query($sql);
		if ($resql) {
			$nump = $this->db->num_rows($resql);
			if ($nump) {
				$i = 0;
				while ($i < $nump) {
					$obj = $this->db->fetch_object($resql);
					$contacts[$obj->rowid] = $obj->firstname . " " . $obj->name;
					$i++;
				}
			}
		} else {
			dol_print_error($this->db);
		}
		return $contacts;
	}

	/**
	 *  Return property of contact from its id
	 *
	 *  @param	int		$rowid      id of contact
	 *  @param  string	$mode       'email' or 'mobile'
	 *  @return string  			email of contact
	 */
	function contact_get_property($rowid, $mode) {
		$contact_property = '';

		$sql = "SELECT rowid, email, phone_mobile, name, firstname";
		$sql.= " FROM " . MAIN_DB_PREFIX . "socpeople";
		$sql.= " WHERE rowid = '" . $rowid . "'";

		$resql = $this->db->query($sql);
		if ($resql) {
			$nump = $this->db->num_rows($resql);

			if ($nump) {
				$obj = $this->db->fetch_object($resql);

				if ($mode == 'email')
					$contact_property = "$obj->firstname $obj->name <$obj->email>";
				else if ($mode == 'mobile')
					$contact_property = $obj->phone_mobile;
			}
			return $contact_property;
		}
		else {
			dol_print_error($this->db);
		}
	}

	/**
	 *    Return bank number property of thirdparty
	 *
	 *    @return	string		Bank number
	 */
	function display_rib() {
		global $langs;

		if ($this->bac->code_banque || $this->bac->code_guichet || $this->bac->number || $this->bac->cle_rib) {
			$rib = $this->bac->code_banque . " " . $this->bac->code_guichet . " " . $this->bac->number;
			$rib.=($this->bac->cle_rib ? " (" . $this->bac->cle_rib . ")" : "");
		} else {
			$rib = $langs->trans("NoRIB");
		}
		return $rib;
	}

	/**
	 * Load this->bank_account attribut
	 *
	 * @return	int		1
	 */
	function load_ban() {
		require_once DOL_DOCUMENT_ROOT . '/societe/class/companybankaccount.class.php';

		$bac = new CompanyBankAccount($this->db);
		$bac->fetch(0, $this->id);

		$this->bank_account = $bac;
		return 1;
	}

	/**
	 * Check bank numbers
	 *
	 * @return int		<0 if KO, >0 if OK
	 */
	function verif_rib() {
		$this->load_ban();
		return $this->bank_account->verif();
	}

	/**
	 *  Attribut un code client a partir du module de controle des codes.
	 *  Return value is stored into this->code_client
	 *
	 * 	@param	Societe		$objsoc		Object thirdparty
	 * 	@param	int			$type		Should be 0 to say customer
	 *  @return void
	 */
	function get_codeclient($objsoc = 0, $type = 0) {
		global $conf;
		if (!empty($conf->global->SOCIETE_CODECLIENT_ADDON)) {
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				$res = dol_include_once($dirroot . $conf->global->SOCIETE_CODECLIENT_ADDON . '.php');
				if ($res)
					break;
			}
			$var = $conf->global->SOCIETE_CODECLIENT_ADDON;
			$mod = new $var;

			$this->code_client = $mod->getNextValue($objsoc, $type);
			$this->prefixCustomerIsRequired = $mod->prefixIsRequired;
		}
	}

	/**
	 *  Attribut un code fournisseur a partir du module de controle des codes.
	 *  Return value is stored into this->code_fournisseur
	 *
	 * 	@param	Societe		$objsoc		Object thirdparty
	 * 	@param	int			$type		Should be 1 to say supplier
	 *  @return void
	 */
	function get_codefournisseur($objsoc = 0, $type = 1) {
		global $conf;
		if (!empty($conf->global->SOCIETE_CODEFOURNISSEUR_ADDON)) {
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				$res = dol_include_once($dirroot . $conf->global->SOCIETE_FOURNISSEUR_ADDON . '.php');
				if ($res)
					break;
			}
			$var = $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;
			$mod = new $var;

			$this->code_fournisseur = $mod->getNextValue($objsoc, $type);
		}
	}

	/**
	 *    Verifie si un code client est modifiable en fonction des parametres
	 *    du module de controle des codes.
	 *
	 *    @return     int		0=Non, 1=Oui
	 */
	function codeclient_modifiable() {
		global $conf;
		if (!empty($conf->global->SOCIETE_CODECLIENT_ADDON)) {
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				$res = dol_include_once($dirroot . $conf->global->SOCIETE_CODECLIENT_ADDON . '.php');
				if ($res)
					break;
			}

			$var = $conf->global->SOCIETE_CODECLIENT_ADDON;

			$mod = new $var;

			if ($mod->code_modifiable_null && !$this->code_client)
				return 1;
			if ($mod->code_modifiable_invalide && $this->check_codeclient() < 0)
				return 1;
			if ($mod->code_modifiable)
				return 1; // A mettre en dernier
			return 0;
		}
		else {
			return 0;
		}
	}

	/**
	 *    Verifie si un code fournisseur est modifiable dans configuration du module de controle des codes
	 *
	 *    @return     int		0=Non, 1=Oui
	 */
	function codefournisseur_modifiable() {
		global $conf;
		if (!empty($conf->global->SOCIETE_CODEFOURNISSEUR_ADDON)) {
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				$res = dol_include_once($dirroot . $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON . '.php');
				if ($res)
					break;
			}

			$var = $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;

			$mod = new $var;

			if ($mod->code_modifiable_null && !$this->code_fournisseur)
				return 1;
			if ($mod->code_modifiable_invalide && $this->check_codefournisseur() < 0)
				return 1;
			if ($mod->code_modifiable)
				return 1; // A mettre en dernier
			return 0;
		}
		else {
			return 0;
		}
	}

	/**
	 *    Check customer code
	 *
	 *    @return     int		0 if OK
	 * 							-1 ErrorBadCustomerCodeSyntax
	 * 							-2 ErrorCustomerCodeRequired
	 * 							-3 ErrorCustomerCodeAlreadyUsed
	 * 							-4 ErrorPrefixRequired
	 */
	function check_codeclient() {
		global $conf;
		if (!empty($conf->global->SOCIETE_CODECLIENT_ADDON)) {
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				$res = dol_include_once($dirroot . $conf->global->SOCIETE_CODECLIENT_ADDON . '.php');
				if ($res)
					break;
			}

			$var = $conf->global->SOCIETE_CODECLIENT_ADDON;

			$mod = new $var;

			$result = $mod->verif($this->db, $this->code_client, $this, 0);
			return $result;
		}
		else {
			return 0;
		}
	}

	/**
	 *    Check supplier code
	 *
	 *    @return     int		0 if OK
	 * 							-1 ErrorBadCustomerCodeSyntax
	 * 							-2 ErrorCustomerCodeRequired
	 * 							-3 ErrorCustomerCodeAlreadyUsed
	 * 							-4 ErrorPrefixRequired
	 */
	function check_codefournisseur() {
		global $conf;
		if (!empty($conf->global->SOCIETE_CODEFOURNISSEUR_ADDON)) {
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				$res = dol_include_once($dirroot . $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON . '.php');
				if ($res)
					break;
			}

			$var = $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;

			$mod = new $var;

			$result = $mod->verif($this->db, $this->code_fournisseur, $this, 1);
			return $result;
		}
		else {
			return 0;
		}
	}

	/**
	 *    	Renvoie un code compta, suivant le module de code compta.
	 *      Peut etre identique a celui saisit ou genere automatiquement.
	 *      A ce jour seule la generation automatique est implementee
	 *
	 *    	@param	string	$type		Type of thirdparty ('customer' or 'supplier')
	 * 		@return	string				Code compta si ok, 0 si aucun, <0 si ko
	 */
	function get_codecompta($type) {
		global $conf;

		if (!empty($conf->global->SOCIETE_CODECOMPTA_ADDON)) {
			$file = '';
			$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
			foreach ($dirsociete as $dirroot) {
				if (file_exists(DOL_DOCUMENT_ROOT . '/' . $dirroot . $conf->global->SOCIETE_CODECOMPTA_ADDON . ".php")) {
					$file = $dirroot . $conf->global->SOCIETE_CODECOMPTA_ADDON . ".php";
					break;
				}
			}

			if (!empty($file)) {
				dol_include_once($file);

				$classname = $conf->global->SOCIETE_CODECOMPTA_ADDON;
				$mod = new $classname;

				// Defini code compta dans $mod->code
				$result = $mod->get_code($this->db, $this, $type);

				if ($type == 'customer')
					$this->code_compta = $mod->code;
				else if ($type == 'supplier')
					$this->code_compta_fournisseur = $mod->code;

				return $result;
			}
			else {
				$this->error = 'ErrorAccountancyCodeNotDefined';
				return -1;
			}
		} else {
			if ($type == 'customer')
				$this->code_compta = '';
			else if ($type == 'supplier')
				$this->code_compta_fournisseur = '';

			return 0;
		}
	}

	/**
	 *    Defini la societe mere pour les filiales
	 *
	 *    @param	int		$id     id compagnie mere a positionner
	 *    @return	int     		<0 if KO, >0 if OK
	 */
	function set_parent($id) {
		if ($this->id) {
			$sql = "UPDATE " . MAIN_DB_PREFIX . "societe ";
			$sql .= " SET parent = " . $id;
			$sql .= " WHERE rowid = " . $this->id . ";";

			if ($this->db->query($sql)) {
				return 1;
			} else {
				return -1;
			}
		}
	}

	/**
	 *  Supprime la societe mere
	 *
	 *  @param	int		$id     id compagnie mere a effacer
	 *  @return int     		<0 if KO, >0 if KO
	 */
	function remove_parent($id) {
		if ($this->id) {
			$sql = "UPDATE " . MAIN_DB_PREFIX . "societe ";
			$sql .= " SET parent = null";
			$sql .= " WHERE rowid = " . $this->id . ";";

			if ($this->db->query($sql)) {
				return 1;
			} else {
				return -1;
			}
		}
	}

	/**
	 *  Returns if a profid sould be verified
	 *
	 *  @param	int		$idprof		1,2,3,4 (Exemple: 1=siren,2=siret,3=naf,4=rcs/rm)
	 *  @return boolean         	true , false
	 */
	function id_prof_verifiable($idprof) {
		global $conf;

		switch ($idprof) {
			case 1:
				$ret = (!$conf->global->SOCIETE_IDPROF1_UNIQUE ? false : true);
				break;
			case 2:
				$ret = (!$conf->global->SOCIETE_IDPROF2_UNIQUE ? false : true);
				break;
			case 3:
				$ret = (!$conf->global->SOCIETE_IDPROF3_UNIQUE ? false : true);
				break;
			case 4:
				$ret = (!$conf->global->SOCIETE_IDPROF4_UNIQUE ? false : true);
				break;
			default:
				$ret = false;
		}

		return $ret;
	}

	/**
	 *    Verify if a profid exists into database for others thirds
	 *
	 *    @param	int		$idprof		1,2,3,4 (Example: 1=siren,2=siret,3=naf,4=rcs/rm)
	 *    @param	string	$value		Value of profid
	 *    @param	int		$socid		Id of society if update
	 *    @return   boolean				true if exists, false if not
	 */
	function id_prof_exists($idprof, $value, $socid = 0) {
		switch ($idprof) {
			case 1:
				$field = "siren";
				break;
			case 2:
				$field = "siret";
				break;
			case 3:
				$field = "ape";
				break;
			case 4:
				$field = "idprof4";
				break;
		}

		//Verify duplicate entries
		$sql = "SELECT COUNT(*) as idprof FROM " . MAIN_DB_PREFIX . "societe WHERE " . $field . " = '" . $value . "'";
		if ($socid)
			$sql .= " AND rowid <> " . $socid;
		$resql = $this->db->query($sql);
		if ($resql) {
			$nump = $this->db->num_rows($resql);
			$obj = $this->db->fetch_object($resql);
			$count = $obj->idprof;
		} else {
			$count = 0;
			print $this->db->error();
		}
		$this->db->free($resql);

		if ($count > 0)
			return true;
		else
			return false;
	}

	/**
	 *  Verifie la validite d'un identifiant professionnel en fonction du pays de la societe (siren, siret, ...)
	 *
	 *  @param	int			$idprof         1,2,3,4 (Exemple: 1=siren,2=siret,3=naf,4=rcs/rm)
	 *  @param  Societe		$soc            Objet societe
	 *  @return int             			<=0 if KO, >0 if OK
	 *  TODO not in business class
	 */
	function id_prof_check($idprof, $soc) {
		global $conf;

		$ok = 1;

		if (!empty($conf->global->MAIN_DISABLEPROFIDRULES))
			return 1;

		// Verifie SIREN si pays FR
		if ($idprof == 1 && $soc->country_code == 'FR') {
			$chaine = trim($this->idprof1);
			$chaine = preg_replace('/(\s)/', '', $chaine);

			if (dol_strlen($chaine) != 9)
				return -1;

			$sum = 0;

			for ($i = 0; $i < 10; $i = $i + 2) {
				$sum = $sum + substr($this->idprof1, (8 - $i), 1);
			}

			for ($i = 1; $i < 9; $i = $i + 2) {
				$ps = 2 * substr($this->idprof1, (8 - $i), 1);

				if ($ps > 9) {
					$ps = substr($ps, 0, 1) + substr($ps, 1, 1);
				}
				$sum = $sum + $ps;
			}

			if (substr($sum, -1) != 0)
				return -1;
		}

		// Verifie SIRET si pays FR
		if ($idprof == 2 && $soc->country_code == 'FR') {
			$chaine = trim($this->idprof2);
			$chaine = preg_replace('/(\s)/', '', $chaine);

			if (dol_strlen($chaine) != 14)
				return -1;
		}

		//Verify CIF/NIF/NIE if pays ES
		//Returns: 1 if NIF ok, 2 if CIF ok, 3 if NIE ok, -1 if NIF bad, -2 if CIF bad, -3 if NIE bad, 0 if unexpected bad
		if ($idprof == 1 && $soc->country_code == 'ES') {
			$string = trim($this->idprof1);
			$string = preg_replace('/(\s)/', '', $string);
			$string = strtoupper($string);

			for ($i = 0; $i < 9; $i++)
				$num[$i] = substr($string, $i, 1);

			//Check format
			if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $string))
				return 0;

			//Check NIF
			if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $string))
				if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($string, 0, 8) % 23, 1))
					return 1;
				else
					return -1;

			//algorithm checking type code CIF
			$sum = $num[2] + $num[4] + $num[6];
			for ($i = 1; $i < 8; $i += 2)
				$sum += substr((2 * $num[$i]), 0, 1) + substr((2 * $num[$i]), 1, 1);
			$n = 10 - substr($sum, strlen($sum) - 1, 1);

			//Chek special NIF
			if (preg_match('/^[KLM]{1}/', $string))
				if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($string, 1, 8) % 23, 1))
					return 1;
				else
					return -1;

			//Check CIF
			if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $string))
				if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1))
					return 2;
				else
					return -2;

			//Check NIE T
			if (preg_match('/^[T]{1}/', $string))
				if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $string))
					return 3;
				else
					return -3;

			//Check NIE XYZ
			if (preg_match('/^[XYZ]{1}/', $string))
				if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X', 'Y', 'Z'), array('0', '1', '2'), $string), 0, 8) % 23, 1))
					return 3;
				else
					return -3;

			//Can not be verified
			return -4;
		}

		return $ok;
	}

	/**
	 *   Renvoi url de verification d'un identifiant professionnal
	 *
	 *   @param		int		$idprof         1,2,3,4 (Exemple: 1=siren,2=siret,3=naf,4=rcs/rm)
	 *   @param 	Societe	$soc            Objet societe
	 *   @return	string          		url ou chaine vide si aucune url connue
	 *   TODO not in business class
	 */
	function id_prof_url($idprof, $soc) {
		global $conf, $langs;

		if (!empty($conf->global->MAIN_DISABLEPROFIDRULES))
			return '';

		$url = '';

		if ($idprof == 1 && $soc->country_code == 'FR')
			$url = 'http://www.societe.com/cgi-bin/recherche?rncs=' . $soc->idprof1;
		if ($idprof == 1 && $soc->country_code == 'GB')
			$url = 'http://www.companieshouse.gov.uk/WebCHeck/findinfolink/';
		if ($idprof == 1 && $soc->country_code == 'ES')
			$url = 'http://www.e-informa.es/servlet/app/portal/ENTP/screen/SProducto/prod/ETIQUETA_EMPRESA/nif/' . $soc->idprof1;

		if ($url)
			return '<a target="_blank" href="' . $url . '">[' . $langs->trans("Check") . ']</a>';
		return '';
	}

	/**
	 *   Indique si la societe a des projets
	 *
	 *   @return     bool	   true si la societe a des projets, false sinon
	 */
	function has_projects() {
		$sql = 'SELECT COUNT(*) as numproj FROM ' . MAIN_DB_PREFIX . 'projet WHERE fk_soc = ' . $this->id;
		$resql = $this->db->query($sql);
		if ($resql) {
			$nump = $this->db->num_rows($resql);
			$obj = $this->db->fetch_object($resql);
			$count = $obj->numproj;
		} else {
			$count = 0;
			print $this->db->error();
		}
		$this->db->free($resql);
		return ($count > 0);
	}

	/**
	 *  Charge les informations d'ordre info dans l'objet societe
	 *
	 *  @param  int		$id     Id de la societe a charger
	 *  @return	void
	 */
	function info($id) {
		$sql = "SELECT s.rowid, s.nom as name, s.datec, s.datea,";
		$sql.= " fk_user_creat, fk_user_modif";
		$sql.= " FROM " . MAIN_DB_PREFIX . "societe as s";
		$sql.= " WHERE s.rowid = " . $id;

		$result = $this->db->query($sql);
		if ($result) {
			if ($this->db->num_rows($result)) {
				$obj = $this->db->fetch_object($result);

				$this->id = $obj->rowid;

				if ($obj->fk_user_creat) {
					$cuser = new User($this->db);
					$cuser->fetch($obj->fk_user_creat);
					$this->user_creation = $cuser;
				}

				if ($obj->fk_user_modif) {
					$muser = new User($this->db);
					$muser->fetch($obj->fk_user_modif);
					$this->user_modification = $muser;
				}
				$this->ref = $obj->name;
				$this->date_creation = $this->db->jdate($obj->datec);
				$this->date_modification = $this->db->jdate($obj->datea);
			}

			$this->db->free($result);
		} else {
			dol_print_error($this->db);
		}
	}

	/**
	 *  Return if third party is a company (Business) or an end user (Consumer)
	 *
	 *  @return    boolean     true=is a company, false=a and user
	 */
	function isACompany() {
		global $conf;

		// Define if third party is treated as company of not when nature is unknown
		$isacompany = empty($conf->global->MAIN_UNKNOWN_CUSTOMERS_ARE_COMPANIES) ? 0 : 1; // 0 by default
		if (!empty($this->tva_intra))
			$isacompany = 1;
		else if (!empty($this->typent_code) && in_array($this->typent_code, array('TE_PRIVATE')))
			$isacompany = 0;
		else if (!empty($this->typent_code) && in_array($this->typent_code, array('TE_SMALL', 'TE_MEDIUM', 'TE_LARGE')))
			$isacompany = 1;

		return $isacompany;
	}

	/**
	 *  Charge la liste des categories fournisseurs
	 *
	 *  @return    int      0 if success, <> 0 if error
	 */
	function LoadSupplierCateg() {
		$this->SupplierCategories = array();
		$sql = "SELECT rowid, label";
		$sql.= " FROM " . MAIN_DB_PREFIX . "categorie";
		$sql.= " WHERE type = 1";

		$resql = $this->db->query($sql);
		if ($resql) {
			while ($obj = $this->db->fetch_object($resql)) {
				$this->SupplierCategories[$obj->rowid] = $obj->label;
			}
			return 0;
		} else {
			return -1;
		}
	}

	/**
	 *  Charge la liste des categories fournisseurs
	 *
	 * 	@param	int		$categorie_id		Id of category
	 *  @return int      					0 if success, <> 0 if error
	 */
	function AddFournisseurInCategory($categorie_id) {
		if ($categorie_id > 0) {
			$sql = "INSERT INTO " . MAIN_DB_PREFIX . "categorie_fournisseur (fk_categorie, fk_societe) ";
			$sql.= " VALUES ('" . $categorie_id . "','" . $this->id . "');";

			if ($resql = $this->db->query($sql))
				return 0;
		}
		else {
			return 0;
		}
		return -1;
	}

	/**
	 *  Create a third party into database from a member object
	 *
	 *  @param	Member	$member		Object member
	 * 	@param	string	$socname	Name of third party to force
	 *  @return int					<0 if KO, id of created account if OK
	 */
	function create_from_member($member, $socname = '') {
		global $conf, $user, $langs;

		$name = $socname ? $socname : $member->societe;
		if (empty($name))
			$name = $member->getFullName($langs);

		// Positionne parametres
		$this->nom = $name;	// TODO obsolete
		$this->name = $name;
		$this->adresse = $member->adresse; // TODO obsolete
		$this->address = $member->adresse;
		$this->cp = $member->cp;   // TODO obsolete
		$this->zip = $member->cp;
		$this->ville = $member->ville; // TODO obsolete
		$this->town = $member->ville;
		$this->pays_code = $member->country_code; // TODO obsolete
		$this->country_code = $member->country_code;
		$this->pays_id = $member->country_id; // TODO obsolete
		$this->country_id = $member->country_id;
		$this->tel = $member->phone;	// deprecated
		$this->phone = $member->phone;	   // Prof phone
		$this->email = $member->email;

		$this->client = 1;	// A member is a customer by default
		$this->code_client = -1;
		$this->code_fournisseur = -1;

		$this->db->begin();

		// Cree et positionne $this->id
		$result = $this->create($user);
		if ($result >= 0) {
			$sql = "UPDATE " . MAIN_DB_PREFIX . "adherent";
			$sql.= " SET fk_soc=" . $this->id;
			$sql.= " WHERE rowid=" . $member->id;

			$resql = $this->db->query($sql);
			if ($resql) {
				$this->db->commit();
				return $this->id;
			} else {
				$this->error = $this->db->error();

				$this->db->rollback();
				return -1;
			}
		} else {
			$this->db->rollback();
			return $result;
		}
	}

	/**
	 *  Initialise an instance with random values.
	 *  Used to build previews or test instances.
	 * 	id must be 0 if object instance is a specimen.
	 *
	 *  @return	void
	 */
	function initAsSpecimen() {
		global $user, $langs, $conf, $mysoc;

		$now = dol_now();

		// Initialize parameters
		$this->id = 0;
		$this->name = 'THIRDPARTY SPECIMEN ' . dol_print_date($now, 'dayhourlog');
		$this->specimen = 1;
		$this->zip = '99999';
		$this->town = 'MyTown';
		$this->country_id = 'FR';

		$this->code_client = 'CC-' . dol_print_date($now, 'dayhourlog');
		$this->code_fournisseur = 'SC-' . dol_print_date($now, 'dayhourlog');
		$this->capital = 10000;
		$this->client = 1;
		$this->prospect = 1;
		$this->fournisseur = 1;
		$this->tva_assuj = 1;
		$this->tva_intra = 'EU1234567';
		$this->note = 'This is a comment';

		$this->idprof1 = 'idprof1';
		$this->idprof2 = 'idprof2';
		$this->idprof3 = 'idprof3';
		$this->idprof4 = 'idprof4';
	}

	/**
	 *  Charge indicateurs this->nb de tableau de bord
	 *
	 *  @return     int         <0 if KO, >0 if OK
	 */
	function load_state_board() {
		global $conf, $user;

		$this->nb = array("customer" => 0, "prospect" => 0, "suspect" => 0);

		$result = $this->getView("count_status", array("group" => "exact"));

		foreach ($result->rows as $aRow) {
			//print_r($aRow);exit;
			$key = $aRow->key;
			$this->nb[$this->fk_status->values->$key->type] = $aRow->value;

			return 1;
		}
	}

	/**
	 *    Return label of status (activity, closed)
	 *
	 *    @return   string        		Libelle
	 */
	function getLibStatus() {
		return $this->LibStatus($this->Status);
	}

	/**
	 * 	Build and get $mysoc object
	 *
	 * 	@param	Conf	$conf		Conf object (possibility to use another entity)
	 * 	@return	void
	 */
	function getMysoc($conf) {
		global $langs;

		$this->id = 0;
		$this->name = (!empty($conf->global->MAIN_INFO_SOCIETE_NOM)) ? $conf->global->MAIN_INFO_SOCIETE_NOM : '';
		$this->nom = $this->name;		  // deprecated
		$this->address = (!empty($conf->global->MAIN_INFO_SOCIETE_ADRESSE)) ? $conf->global->MAIN_INFO_SOCIETE_ADRESSE : '';
		$this->adresse = $this->address;		// deprecated
		$this->zip = (!empty($conf->global->MAIN_INFO_SOCIETE_CP)) ? $conf->global->MAIN_INFO_SOCIETE_CP : '';
		$this->cp = $this->zip;		  // deprecated
		$this->town = (!empty($conf->global->MAIN_INFO_SOCIETE_VILLE)) ? $conf->global->MAIN_INFO_SOCIETE_VILLE : '';
		$this->ville = $this->town;		 // deprecated
		$this->state_id = $conf->global->MAIN_INFO_SOCIETE_DEPARTEMENT;
		$this->note = empty($conf->global->MAIN_INFO_SOCIETE_NOTE) ? '' : $conf->global->MAIN_INFO_SOCIETE_NOTE;

		// We define country_id, country_code and country
		$country_id = $country_code = $country_label = '';
		if (!empty($conf->global->MAIN_INFO_SOCIETE_PAYS)) {
			$tmp = explode(':', $conf->global->MAIN_INFO_SOCIETE_PAYS);
			$country_id = $tmp[0];
			if (!empty($tmp[1])) {   // If $conf->global->MAIN_INFO_SOCIETE_PAYS is "id:code:label"
				$country_code = $tmp[1];
				$country_label = $tmp[2];
			} else {					// For backward compatibility
				include_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
				$country_code = getCountry($country_id, 2, $db);  // This need a SQL request, but it's the old feature
				$country_label = getCountry($country_id, 0, $db);  // This need a SQL request, but it's the old feature
			}
		}
		$this->pays_id = $country_id;  // TODO deprecated
		$this->country_id = $country_id;
		$this->pays_code = $country_code; // TODO deprecated
		$this->country_code = $country_code;
		$this->country = $country_label;
		if (is_object($langs))
			$this->country = ($langs->trans('Country' . $country_code) != 'Country' . $country_code) ? $langs->trans('Country' . $country_code) : $country_label;
		$this->pays = $this->country;	 // TODO deprecated

		$this->tel = empty($conf->global->MAIN_INFO_SOCIETE_TEL) ? '' : $conf->global->MAIN_INFO_SOCIETE_TEL;   // TODO deprecated
		$this->phone = empty($conf->global->MAIN_INFO_SOCIETE_TEL) ? '' : $conf->global->MAIN_INFO_SOCIETE_TEL;
		$this->fax = empty($conf->global->MAIN_INFO_SOCIETE_FAX) ? '' : $conf->global->MAIN_INFO_SOCIETE_FAX;
		$this->url = empty($conf->global->MAIN_INFO_SOCIETE_WEB) ? '' : $conf->global->MAIN_INFO_SOCIETE_WEB;
		// Id prof generiques
		$this->idprof1 = empty($conf->global->MAIN_INFO_SIREN) ? '' : $conf->global->MAIN_INFO_SIREN;
		$this->idprof2 = empty($conf->global->MAIN_INFO_SIRET) ? '' : $conf->global->MAIN_INFO_SIRET;
		$this->idprof3 = empty($conf->global->MAIN_INFO_APE) ? '' : $conf->global->MAIN_INFO_APE;
		$this->idprof4 = empty($conf->global->MAIN_INFO_RCS) ? '' : $conf->global->MAIN_INFO_RCS;
		$this->idprof5 = empty($conf->global->MAIN_INFO_PROFID5) ? '' : $conf->global->MAIN_INFO_PROFID5;
		$this->idprof6 = empty($conf->global->MAIN_INFO_PROFID6) ? '' : $conf->global->MAIN_INFO_PROFID6;
		$this->tva_intra = (!empty($conf->global->MAIN_INFO_TVAINTRA)) ? $conf->global->MAIN_INFO_TVAINTRA : ''; // VAT number, not necessarly INTRA.
		$this->capital = (!empty($conf->global->MAIN_INFO_CAPITAL)) ? $conf->global->MAIN_INFO_CAPITAL : '';
		$this->forme_juridique_code = $conf->global->MAIN_INFO_SOCIETE_FORME_JURIDIQUE;
		$this->email = (!empty($conf->global->MAIN_INFO_SOCIETE_MAIL)) ? $conf->global->MAIN_INFO_SOCIETE_MAIL : '';
		$this->logo = (!empty($conf->global->MAIN_INFO_SOCIETE_LOGO)) ? $conf->global->MAIN_INFO_SOCIETE_LOGO : '';
		$this->logo_small = (!empty($conf->global->MAIN_INFO_SOCIETE_LOGO_SMALL)) ? $conf->global->MAIN_INFO_SOCIETE_LOGO_SMALL : '';
		$this->logo_mini = (!empty($conf->global->MAIN_INFO_SOCIETE_LOGO_MINI)) ? $conf->global->MAIN_INFO_SOCIETE_LOGO_MINI : '';

		// Define if company use vat or not (Do not use conf->global->FACTURE_TVAOPTION anymore)
		$this->tva_assuj = ((isset($conf->global->FACTURE_TVAOPTION) && $conf->global->FACTURE_TVAOPTION == 'franchise') ? 0 : 1);

		// Define if company use local taxes
		$this->localtax1_assuj = ((isset($conf->global->FACTURE_LOCAL_TAX1_OPTION) && $conf->global->FACTURE_LOCAL_TAX1_OPTION == 'localtax1on') ? 1 : 0);
		$this->localtax2_assuj = ((isset($conf->global->FACTURE_LOCAL_TAX2_OPTION) && $conf->global->FACTURE_LOCAL_TAX2_OPTION == 'localtax2on') ? 1 : 0);
	}

	/*
	 * Graph comptes by status
	 *
	 */

	function graphPieStatus($json = false) {
		global $user, $conf, $langs;

		//$color = array(-1 => "#A51B00", 0 => "#CCC", 1 => "#000", 2 => "#FEF4AE", 3 => "#666", 4 => "#1f17c1", 5 => "#DE7603", 6 => "#D40000", 7 => "#7ac52e", 8 => "#1b651b", 9 => "#66c18c", 10 => "#2e99a0");

		if ($json) { // For Data see viewgraph.php
			$langs->load("companies");

			$params = array('group' => true);
			if ($user->rights->societe->client->voir) { // See ALL
				$params = array('group' => true);
				$result = $this->getView("count_status", $params);
				$filter = false;
			} else {
				$params = array('group' => true, "startkey" => array($user->name), "endkey" => array($user->name, new stdClass()));
				$result = $this->getView("commercial_status", $params);
				$filter = true;
			}

			//print_r($result);
			$i = 0;

			foreach ($result->rows as $aRow) {
				if ($filter)
					$key = $aRow->key[1];
				else
					$key = $aRow->key;
				$label = $langs->trans($this->fk_extrafields->fields->Status->values->$key->label);
				if (empty($label))
					$label = $langs->trans($aRow->key);

				if ($i == 0) { // first element
					$output[$i] = new stdClass();
					$output[$i]->name = $label;
					$output[$i]->y = $aRow->value;
					$output[$i]->sliced = true;
					$output[$i]->selected = true;
				}
				else
					$output[$i] = array($label, $aRow->value);
				$i++;
			}
			return $output;
		} else {
			$total = 0;
			$i = 0;
			?>
			<div id="pie-status" style="min-width: 100px; height: 280px; margin: 0 auto"></div>
			<script type="text/javascript">
				(function($){ // encapsulate jQuery

					$(function() {
						var seriesOptions = [],
						yAxisOptions = [],
						seriesCounter = 0,
						colors = Highcharts.getOptions().colors;

						$.getJSON('<?php echo DOL_URL_ROOT . '/core/ajax/viewgraph.php'; ?>?json=graphPieStatus&class=<?php echo get_class($this); ?>&callback=?',	function(data) {

							seriesOptions = data;

							createChart();

						});


						// create the chart when all data is loaded
						function createChart() {
							var chart;

							chart = new Highcharts.Chart({
								chart: {
									renderTo: "pie-status",
									//defaultSeriesType: "bar",
									margin: 0,
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false
								},
								legend: {
									layout: "vertical", backgroundColor: Highcharts.theme.legendBackgroundColor || "#FFFFFF", align: "left", verticalAlign: "bottom", x: 0, y: 20, floating: true, shadow: true,
									enabled: false
								},
								title: {
									text: null
								},

								tooltip: {
									enabled:true,
									pointFormat: '{series.name}: <b>{point.percentage}%</b>',
									percentageDecimals: 2
								},
								navigator: {
									margin: 30
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: true,
											color: '#FFF',
											connectorColor: '#FFF',
											distance : 30,
											formatter: function() {
												return '<b>'+ this.point.name +'</b><br> '+ Math.round(this.percentage) +' %';
											}
										}
									}
								},
								series: [{
										type: "pie",
										name: "<?php echo $langs->trans("Quantity"); ?>",
										size: 100,
										data: seriesOptions
									}]
							});
						}

					});
				})(jQuery);
			</script>
			<?php
		}
	}

	/*
	 * Graph comptes by status
	 *
	 */

	function graphBarStatus($json = false) {
		global $user, $conf, $langs;

		$langs->load("companies");

		if ($json) { // For Data see viewgraph.php
			$keystart[0] = $_GET["name"];
			$keyend[0] = $_GET["name"];
			$keyend[1] = new stdClass();

			$params = array('group' => true, 'group_level' => 2, 'startkey' => $keystart, 'endkey' => $keyend);
			$result = $this->getView("commercial_status", $params);

			foreach ($this->fk_extrafields->fields->Status->values as $key => $aRow) {
				//print_r($aRow);exit;
				$label = $langs->trans($key);
				if ($aRow->enable) {
					$tab[$key] = new stdClass();
					$tab[$key]->label = $label;
					$tab[$key]->value = 0;
				}
			}

			foreach ($result->rows as $aRow) { // Update counters from view
				if (!is_object($tab[$aRow->key[1]]))
					$tab[$aRow->key[1]] = new stdClass();
				if (isset($tab[$aRow->key[1]]->value))
					$tab[$aRow->key[1]]->value = 0;
				$tab[$aRow->key[1]]->value+=$aRow->value;
			}

			foreach ($tab as $aRow)
				$output[] = array($aRow->label, $aRow->value);

			return $output;
		} else {
			$total = 0;
			$i = 0;
			?>
			<div id="bar-status" style="min-width: 100px; height: 280px; margin: 0 auto"></div>
			<script type="text/javascript">
				(function($){ // encapsulate jQuery

					$(function() {
						var seriesOptions = [],
						yAxisOptions = [],
						seriesCounter = 0,
						names = [<?php
			if ($user->rights->societe->client->voir) { // See ALL
				$params = array('group' => true, 'group_level' => 1);
				$result = $this->getView("commercial_status", $params);
			} else {
				$result->rows[0]->key = array($user->name);
			}


			if (count($result->rows)) {
				foreach ($result->rows as $aRow) {
					if ($i == 0)
						echo "'" . $aRow->key[0] . "'";
					else
						echo ",'" . $aRow->key[0] . "'";
					$i++;
				}
			}
			?>],
							colors = Highcharts.getOptions().colors;
							$.each(names, function(i, name) {

								$.getJSON('<?php echo DOL_URL_ROOT . '/core/ajax/viewgraph.php'; ?>?json=graphBarStatus&class=<?php echo get_class($this); ?>&name='+ name.toString() +'&callback=?',	function(data) {

									seriesOptions[i] = {
										name: name,
										data: data
									};

									// As we're loading the data asynchronously, we don't know what order it will arrive. So
									// we keep a counter and create the chart when all the data is loaded.
									seriesCounter++;

									if (seriesCounter == names.length) {
										createChart();
									}
								});
							});


							// create the chart when all data is loaded
							function createChart() {
								var chart;

								chart = new Highcharts.Chart({
									chart: {
										renderTo: 'bar-status',
										defaultSeriesType: "column",
										zoomType: "x",
										marginBottom: 30
									},
									credits: {
										enabled:false
									},
									xAxis: {
										categories: [<?php
			$i = 0;
			foreach ($this->fk_extrafields->fields->Status->values as $key => $aRow) {
				$label = $langs->trans($aRow->label);
				if (empty($label))
					$label = $langs->trans($key);
				if ($aRow->enable) {
					if ($i == 0)
						echo "'" . $label . "'";
					else
						echo ",'" . $label . "'";

					$i++;
				}
			}
			?>],
											maxZoom: 1
											//labels: {rotation: 90, align: "left"}
										},
										yAxis: {
											title: {text: "Total"},
											allowDecimals: false,
											min: 0
										},
										title: {
											//text: "<?php echo $langs->trans("SalesRepresentatives"); ?>"
											text: null
										},
										legend: {
											layout: 'vertical',
											align: 'right',
											verticalAlign: 'top',
											x: -5,
											y: 5,
											floating: true,
											borderWidth: 1,
											backgroundColor: Highcharts.theme.legendBackgroundColor || '#FFFFFF',
											shadow: true
										},
										tooltip: {
											enabled:true,
											formatter: function() {
												//return this.point.name + ' : ' + this.y;
												return '<b>'+ this.x +'</b><br/>'+
													this.series.name +': '+ this.y;
											}
										},
										series: seriesOptions
									});
								}

							});
						})(jQuery);
			</script>
			<?php
		}
	}

}
?>
