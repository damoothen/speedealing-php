<?php

/* Copyright (C) 2003,2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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
 *      \defgroup   member     Module foundation
 *      \brief      Module to manage members of a foundation
 * 		\file       htdocs/core/modules/modAdherent.class.php
 *      \ingroup    member
 *      \brief      File descriptor or module Member
 */
include_once(DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php");

/**
 *  Classe de description et activation du module Adherent
 */
class modAdherent extends DolibarrModules {

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function modAdherent($db) {
		parent::__construct($db);

		$this->values->numero = 310;

		$this->values->family = "hr";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->values->name = preg_replace('/^mod/i', '', get_class($this));
		$this->values->description = "Gestion des adhérents d'une association";
		$this->values->version = 'dolibarr';						// 'experimental' or 'dolibarr' or version
		$this->values->const_name = 'MAIN_MODULE_' . strtoupper($this->values->name);
		$this->values->special = 0;
		$this->values->picto = 'user';

		// Data directories to create when module is enabled
		$this->values->dirs = array("/adherent/temp");

		// Config pages
		//-------------
		$this->values->config_page_url = array("adherent.php@adherents");

		// Dependances
		//------------
		$this->values->depends = array();
		$this->values->requiredby = array('modMailmanSpip');
		$this->values->langfiles = array("members", "companies");

		// Constantes
		//-----------
		$this->values->const = array();
		$this->values->const[2] = array("MAIN_SEARCHFORM_ADHERENT", "yesno", "1", "Show form for quick member search");
		$this->values->const[3] = array("ADHERENT_MAIL_RESIL", "texte", "Votre adhésion vient d'être résiliée.\r\nNous espérons vous revoir très bientôt", "Mail de résiliation");
		$this->values->const[4] = array("ADHERENT_MAIL_VALID", "texte", "Votre adhésion vient d'être validée. \r\nVoici le rappel de vos coordonnées (toute information erronée entrainera la non validation de votre inscription) :\r\n\r\n%INFOS%\r\n\r\n", "Mail de validation");
		$this->values->const[5] = array("ADHERENT_MAIL_VALID_SUBJECT", "chaine", "Votre adhésion a été validée", "Sujet du mail de validation");
		$this->values->const[6] = array("ADHERENT_MAIL_RESIL_SUBJECT", "chaine", "Résiliation de votre adhésion", "Sujet du mail de résiliation");
		$this->values->const[21] = array("ADHERENT_MAIL_FROM", "chaine", "", "From des mails");
		$this->values->const[22] = array("ADHERENT_MAIL_COTIS", "texte", "Bonjour %PRENOM%,\r\nCet email confirme que votre cotisation a été reçue\r\net enregistrée", "Mail de validation de cotisation");
		$this->values->const[23] = array("ADHERENT_MAIL_COTIS_SUBJECT", "chaine", "Reçu de votre cotisation", "Sujet du mail de validation de cotisation");
		$this->values->const[25] = array("ADHERENT_CARD_HEADER_TEXT", "chaine", "%ANNEE%", "Texte imprimé sur le haut de la carte adhérent");
		$this->values->const[26] = array("ADHERENT_CARD_FOOTER_TEXT", "chaine", "Association AZERTY", "Texte imprimé sur le bas de la carte adhérent");
		$this->values->const[27] = array("ADHERENT_CARD_TEXT", "texte", "%FULLNAME%\r\nID: %ID%\r\n%EMAIL%\r\n%ADDRESS%\r\n%ZIP% %TOWN%\r\n%COUNTRY%", "Text to print on member cards");
		$this->values->const[28] = array("ADHERENT_MAILMAN_ADMINPW", "chaine", "", "Mot de passe Admin des liste mailman");
		$this->values->const[31] = array("ADHERENT_BANK_USE_AUTO", "yesno", "", "Insertion automatique des cotisations dans le compte banquaire");
		$this->values->const[32] = array("ADHERENT_BANK_ACCOUNT", "chaine", "", "ID du Compte banquaire utilise");
		$this->values->const[33] = array("ADHERENT_BANK_CATEGORIE", "chaine", "", "ID de la catégorie banquaire des cotisations");
		$this->values->const[34] = array("ADHERENT_ETIQUETTE_TYPE", "chaine", "L7163", "Type of address sheets");
		$this->values->const[35] = array("ADHERENT_ETIQUETTE_TEXT", 'texte', "%FULLNAME%\n%ADDRESS%\n%ZIP% %TOWN%\n%COUNTRY%", "Text to print on member address sheets");

		// Boxes
		//-------
		$this->values->boxes = array();
		$r = 0;
		$this->values->boxes[$r][1] = "box_members.php";

		// Permissions
		//------------
		$this->values->rights = array();
		$this->values->rights_class = 'adherent';
		$r = 0;

		// $this->values->rights[$r][0]     Id permission (unique tous modules confondus)
		// $this->values->rights[$r][1]     Libelle par defaut si traduction de cle "PermissionXXX" non trouvee (XXX = Id permission)
		// $this->values->rights[$r][2]     Non utilise
		// $this->values->rights[$r][3]     1=Permis par defaut, 0=Non permis par defaut
		// $this->values->rights[$r][4]     Niveau 1 pour nommer permission dans code
		// $this->values->rights[$r][5]     Niveau 2 pour nommer permission dans code

		$r++;
		$this->values->rights[$r][0] = 71;
		$this->values->rights[$r][1] = 'Read members\' card';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 72;
		$this->values->rights[$r][1] = 'Create/modify members (need also user module permissions if member linked to a user)';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'creer';

		$r++;
		$this->values->rights[$r][0] = 74;
		$this->values->rights[$r][1] = 'Remove members';
		$this->values->rights[$r][2] = 'd';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'supprimer';

		$r++;
		$this->values->rights[$r][0] = 76;
		$this->values->rights[$r][1] = 'Export members';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'export';

		$r++;
		$this->values->rights[$r][0] = 75;
		$this->values->rights[$r][1] = 'Setup types and attributes of members';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'configurer';

		$r++;
		$this->values->rights[$r][0] = 78;
		$this->values->rights[$r][1] = 'Read subscriptions';
		$this->values->rights[$r][2] = 'r';
		$this->values->rights[$r][3] = 1;
		$this->values->rights[$r][4] = 'cotisation';
		$this->values->rights[$r][5] = 'lire';

		$r++;
		$this->values->rights[$r][0] = 79;
		$this->values->rights[$r][1] = 'Create/modify/remove subscriptions';
		$this->values->rights[$r][2] = 'w';
		$this->values->rights[$r][3] = 0;
		$this->values->rights[$r][4] = 'cotisation';
		$this->values->rights[$r][5] = 'creer';

		// Exports
		//--------
		$r = 0;

		// $this->values->export_code[$r]          Code unique identifiant l'export (tous modules confondus)
		// $this->values->export_label[$r]         Libelle par defaut si traduction de cle "ExportXXX" non trouvee (XXX = Code)
		// $this->values->export_permission[$r]    Liste des codes permissions requis pour faire l'export
		// $this->values->export_fields_sql[$r]    Liste des champs exportables en codif sql
		// $this->values->export_fields_name[$r]   Liste des champs exportables en codif traduction
		// $this->values->export_sql[$r]           Requete sql qui offre les donnees a l'export

		$r++;
		$this->values->export_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->export_label[$r] = 'MembersAndSubscriptions';
		$this->values->export_permission[$r] = array(array("adherent", "export"));
		$this->values->export_fields_array[$r] = array('a.rowid' => 'Id', 'a.civilite' => "UserTitle", 'a.nom' => "Lastname", 'a.prenom' => "Firstname", 'a.login' => "Login", 'a.morphy' => 'MorPhy', 'a.societe' => 'Company', 'a.adresse' => "Address", 'a.cp' => "Zip", 'a.ville' => "Town", 'a.pays' => "Country", 'a.phone' => "PhonePro", 'a.phone_perso' => "PhonePerso", 'a.phone_mobile' => "PhoneMobile", 'a.email' => "Email", 'a.naiss' => "Birthday", 'a.statut' => "Status", 'a.photo' => "Photo", 'a.note' => "Note", 'a.datec' => 'DateCreation', 'a.datevalid' => 'DateValidation', 'a.tms' => 'DateLastModification', 'a.datefin' => 'DateEndSubscription', 'ta.rowid' => 'MemberTypeId', 'ta.libelle' => 'MemberTypeLabel', 'c.rowid' => 'SubscriptionId', 'c.dateadh' => 'DateSubscription', 'c.cotisation' => 'Amount');
		$this->values->export_entities_array[$r] = array('a.rowid' => 'member', 'a.civilite' => "member", 'a.nom' => "member", 'a.prenom' => "member", 'a.login' => "member", 'a.morphy' => 'member', 'a.societe' => 'member', 'a.adresse' => "member", 'a.cp' => "member", 'a.ville' => "member", 'a.pays' => "member", 'a.phone' => "member", 'a.phone_perso' => "member", 'a.phone_mobile' => "member", 'a.email' => "member", 'a.naiss' => "member", 'a.statut' => "member", 'a.photo' => "member", 'a.note' => "member", 'a.datec' => 'member', 'a.datevalid' => 'member', 'a.tms' => 'member', 'a.datefin' => 'member', 'ta.rowid' => 'member_type', 'ta.libelle' => 'member_type', 'c.rowid' => 'subscription', 'c.dateadh' => 'subscription', 'c.cotisation' => 'subscription');
		// Add extra fields
		$sql = "SELECT name, label FROM " . MAIN_DB_PREFIX . "extrafields WHERE elementtype = 'member'";
		$resql = $this->db->query($sql);
		while ($obj = $this->db->fetch_object($resql)) {
			$fieldname = 'extra.' . $obj->name;
			$fieldlabel = ucfirst($obj->label);
			$this->values->export_fields_array[$r][$fieldname] = $fieldlabel;
			$this->values->export_entities_array[$r][$fieldname] = 'member';
		}
		// End add axtra fields
		$this->values->export_sql_start[$r] = 'SELECT DISTINCT ';
		$this->values->export_sql_end[$r] = ' FROM (' . MAIN_DB_PREFIX . 'adherent_type as ta, ' . MAIN_DB_PREFIX . 'adherent as a)';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'adherent_extrafields as extra ON a.rowid = extra.fk_object';
		$this->values->export_sql_end[$r] .=' LEFT JOIN ' . MAIN_DB_PREFIX . 'cotisation as c ON c.fk_adherent = a.rowid';
		$this->values->export_sql_end[$r] .=' WHERE a.fk_adherent_type = ta.rowid';

		// Imports
		//--------
		$r = 0;

		$now = dol_now();
		require_once(DOL_DOCUMENT_ROOT . "/core/lib/date.lib.php");

		$r++;
		$this->values->import_code[$r] = $this->values->rights_class . '_' . $r;
		$this->values->import_label[$r] = "Members"; // Translation key
		$this->values->import_icon[$r] = $this->values->picto;
		$this->values->import_entities_array[$r] = array();  // We define here only fields that use another icon that the one defined into import_icon
		$this->values->import_tables_array[$r] = array('a' => MAIN_DB_PREFIX . 'adherent', 'extra' => MAIN_DB_PREFIX . 'adherent_extrafields');
		$this->values->import_tables_creator_array[$r] = array('a' => 'fk_user_author');	// Fields to store import user id
		$this->values->import_fields_array[$r] = array('a.civilite' => "Civility", 'a.nom' => "Lastname*", 'a.prenom' => "Firstname", 'a.login' => "Login*", "a.pass" => "Password", "a.fk_adherent_type" => "MemberType*", 'a.morphy' => 'MorPhy*', 'a.societe' => 'Company', 'a.adresse' => "Address", 'a.cp' => "Zip", 'a.ville' => "Town", 'a.pays' => "Country", 'a.phone' => "PhonePro", 'a.phone_perso' => "PhonePerso", 'a.phone_mobile' => "PhoneMobile", 'a.email' => "Email", 'a.naiss' => "Birthday", 'a.statut' => "Status*", 'a.photo' => "Photo", 'a.note' => "Note", 'a.datec' => 'DateCreation', 'a.datefin' => 'DateEndSubscription');
		// Add extra fields
		$sql = "SELECT name, label FROM " . MAIN_DB_PREFIX . "extrafields WHERE elementtype = 'member'";
		$resql = $this->db->query($sql);
		if ($resql) {	// This can fail when class is used on old database (during migration for example)
			while ($obj = $this->db->fetch_object($resql)) {
				$fieldname = 'extra.' . $obj->name;
				$fieldlabel = ucfirst($obj->label);
				$this->values->import_fields_array[$r][$fieldname] = $fieldlabel;
			}
		}
		// End add extra fields
		$this->values->import_fieldshidden_array[$r] = array('extra.fk_object' => 'lastrowid-' . MAIN_DB_PREFIX . 'adherent');	// aliastable.field => ('user->id' or 'lastrowid-'.tableparent)
		$this->values->import_regex_array[$r] = array('a.civilite' => 'code@' . MAIN_DB_PREFIX . 'c_civilite', 'a.fk_adherent_type' => 'rowid@' . MAIN_DB_PREFIX . 'adherent_type', 'a.morphy' => '(phy|mor)', 'a.statut' => '^[0|1]', 'a.datec' => '^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$', 'a.datefin' => '^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$');
		$this->values->import_examplevalues_array[$r] = array('a.civilite' => "MR", 'a.nom' => 'Smith', 'a.prenom' => 'John', 'a.login' => 'jsmith', 'a.pass' => 'passofjsmith', 'a.fk_adherent_type' => '1', 'a.morphy' => '"mor" or "phy"', 'a.societe' => 'JS company', 'a.adresse' => '21 jump street', 'a.cp' => '55000', 'a.ville' => 'New York', 'a.pays' => '1', 'a.email' => 'jsmith@example.com', 'a.naiss' => '1972-10-10', 'a.statut' => "0 or 1", 'a.note' => "This is a comment on member", 'a.datec' => dol_print_date($now, '%Y-%m-%d'), 'a.datefin' => dol_print_date(dol_time_plus_duree($now, 1, 'y'), '%Y-%m-%d'));
	}

	/**
	 * 		Function called when module is enabled.
	 * 		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 * 		It also creates data directories
	 *
	 *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options = '') {

		$sql = array();

		return $this->values->_init($sql, $options);
	}

	/**
	 * 		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 * 		Data directories are not deleted
	 *
	 *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function remove($options = '') {
		$sql = array();

		return $this->values->_remove($sql, $options);
	}

}

?>
