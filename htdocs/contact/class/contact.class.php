<?php

/* Copyright (C) 2002-2004 Rodolphe Quiedeville         <rodolphe@quiedeville.org>
 * Copyright (C) 2004      Benoit Mortier               <benoit.mortier@opensides.be>
 * Copyright (C) 2004-2010 Laurent Destailleur          <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin                <regis.houssin@capnetworks.com>
 * Copyright (C) 2007      Franky Van Liedekerke        <franky.van.liedekerker@telenet.be>
 * Copyright (C) 2008      Raphael Bertrand (Resultic)  <raphael.bertrand@resultic.fr>
 * Copyright (C) 2012      Herve Prot                   <herve.prot@symeos.com>
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

/**
 * 	\file       htdocs/contact/class/contact.class.php
 * 	\ingroup    societe
 * 	\brief      File of contacts class
 */
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';

/**
 * 	\class      Contact
 * 	\brief      Classe permettant la gestion des contacts
 */
class Contact extends nosqlDocument {

    public $element = 'contact';
    public $table_element = 'socpeople';
    var $id;
    var $civilite_id;  // In fact we stor civility_code
    var $lastname;
    var $name;         // TODO deprecated
    var $firstname;
    var $address;
    var $zip;
    var $town;
    var $state_id;          // Id of department
    var $country_id;   // Id of country
    var $societe;     // fk_soc
    var $status;    // 0=brouillon, 1=4=actif, 5=inactif
    var $code;
    var $email;
    var $birthday;
    var $default_lang;
    var $notes;                  // Private note
    var $no_email;    // 1=Don't send e-mail to this contact, 0=do
    var $ref_facturation;       // Nb de reference facture pour lequel il est contact
    var $ref_contrat;           // Nb de reference contrat pour lequel il est contact
    var $ref_commande;          // Nb de reference commande pour lequel il est contact
    var $ref_propal;            // Nb de reference propal pour lequel il est contact
    var $user_login;
    var $import_key;
    var $oldcopy;  // To contains a clone of this when we need to save old properties of object

    /**
     * 	Constructor
     *
     *  @param		DoliDB		$db      Database handler
     */

    function __construct($db) {
        parent::__construct($db);


        $this->fk_extrafields = new ExtraFields($db);
        $this->fk_extrafields->fetch(get_class($this));


        $this->societe = new stdClass();

        return 1;
    }

    /**
     *  Add a contact into database
     *
     *  @param      User	$user       Object user that create
     *  @return     int      			<0 if KO, >0 if OK
     */
    function create($user) {
        global $conf, $langs;

        $error = 0;
        $now = dol_now();

        $this->db->begin();

        // Clean parameters
        $this->lastname = $this->lastname ? trim($this->lastname) : $this->name;
        $this->firstname = trim($this->firstname);
        if (!empty($conf->global->MAIN_FIRST_TO_UPPER))
            $this->lastname = ucwords($this->lastname);
        if (!empty($conf->global->MAIN_FIRST_TO_UPPER))
            $this->firstname = ucwords($this->firstname);

        $result = $this->update($this->id, $user, 1);
        if ($result < 0) {
            $error++;
            $this->error = $this->db->lasterror();
        }

        $this->date_create = dol_now();

        if (!$error) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('CONTACT_CREATE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }

        return $this->id;
    }

    /**
     *      Update informations into database
     *
     *      @param      int		$id          	Id of contact/address to update
     *      @param      User	$user        	Objet user making change
     *      @param      int		$notrigger	    0=no, 1=yesi
     *      @return     int      			   	<0 if KO, >0 if OK
     */
    function update($id, $user = 0, $notrigger = 0) {
        global $conf, $langs, $hookmanager;

        $error = 0;

        $this->id = $id;

        // Clean parameters
        $this->lastname = trim($this->lastname) ? trim($this->lastname) : trim($this->name);
        $this->firstname = trim($this->firstname);
        $this->email = trim($this->email);
        $this->phone_pro = trim($this->phone_pro);
        $this->phone_perso = trim($this->phone_perso);
        $this->phone_mobile = trim($this->phone_mobile);
        $this->fax = trim($this->fax);
        $this->zip = $this->zip;
        $this->town = $this->town;
        $this->country_id = $this->country_id;
        $this->state_id = $this->state_id;

        $this->fk_user_modif = $user->login;
        $this->tms = dol_now();

        $this->name = $this->firstname . " " . $this->lastname;

        if (!empty($this->societe->id)) {
            $object = new Societe($this->db);
            $object->load($this->societe->id);
            $this->societe->name = $object->name;
        } else {
            unset($this->societe->name);
        }

        $this->record();

        // Actions on extra fields (by external module or standard code)
        // TODO deprecated, replace with card builder
        $hookmanager->initHooks(array('contactdao'));
        $parameters = array('socid' => $this->id);
        $reshook = $hookmanager->executeHooks('insertExtraFields', $parameters, $this, $action);    // Note that $action and $object may have been modified by some hooks
        if (empty($reshook)) {
            $result = $this->insertExtraFields();
            if ($result < 0) {
                $error++;
            }
        } else if ($reshook < 0)
            $error++;

        if (!$error && !$notrigger) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('CONTACT_MODIFY', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers

            return 1;
        }
    }

    /**
     * 	Retourne chaine DN complete dans l'annuaire LDAP pour l'objet
     *
     * 	@param		array	$info		Info string loaded by _load_ldap_info
     * 	@param		int		$mode		0=Return full DN (uid=qqq,ou=xxx,dc=aaa,dc=bbb)
     * 									1=Return DN without key inside (ou=xxx,dc=aaa,dc=bbb)
     * 									2=Return key only (uid=qqq)
     * 	@return		string				DN
     */
    function _load_ldap_dn($info, $mode = 0) {
        global $conf;
        $dn = '';
        if ($mode == 0)
            $dn = $conf->global->LDAP_KEY_CONTACTS . "=" . $info[$conf->global->LDAP_KEY_CONTACTS] . "," . $conf->global->LDAP_CONTACT_DN;
        if ($mode == 1)
            $dn = $conf->global->LDAP_CONTACT_DN;
        if ($mode == 2)
            $dn = $conf->global->LDAP_KEY_CONTACTS . "=" . $info[$conf->global->LDAP_KEY_CONTACTS];
        return $dn;
    }

    /**
     * 	Initialise tableau info (tableau des attributs LDAP)
     *
     * 	@return		array		Tableau info des attributs
     */
    function _load_ldap_info() {
        global $conf, $langs;

        // Object classes
        $info["objectclass"] = explode(',', $conf->global->LDAP_CONTACT_OBJECT_CLASS);

        $this->fullname = $this->getFullName($langs);

        // Fields
        if ($this->fullname && !empty($conf->global->LDAP_CONTACT_FIELD_FULLNAME))
            $info[$conf->global->LDAP_CONTACT_FIELD_FULLNAME] = $this->fullname;
        if ($this->lastname && !empty($conf->global->LDAP_CONTACT_FIELD_NAME))
            $info[$conf->global->LDAP_CONTACT_FIELD_NAME] = $this->lastname;
        if ($this->firstname && !empty($conf->global->LDAP_CONTACT_FIELD_FIRSTNAME))
            $info[$conf->global->LDAP_CONTACT_FIELD_FIRSTNAME] = $this->firstname;

        if ($this->poste)
            $info["title"] = $this->poste;
        if (isset($this->societe->id)) {
            $soc = new Societe($this->db);
            $soc->fetch($this->socete->id);

            $info[$conf->global->LDAP_CONTACT_FIELD_COMPANY] = $soc->nom;
            if ($soc->client == 1)
                $info["businessCategory"] = "Customers";
            if ($soc->client == 2)
                $info["businessCategory"] = "Prospects";
            if ($soc->fournisseur == 1)
                $info["businessCategory"] = "Suppliers";
        }
        if ($this->address && !empty($conf->global->LDAP_CONTACT_FIELD_ADDRESS))
            $info[$conf->global->LDAP_CONTACT_FIELD_ADDRESS] = $this->address;
        if ($this->cp && !empty($conf->global->LDAP_CONTACT_FIELD_ZIP))
            $info[$conf->global->LDAP_CONTACT_FIELD_ZIP] = $this->cp;
        if ($this->ville && !empty($conf->global->LDAP_CONTACT_FIELD_TOWN))
            $info[$conf->global->LDAP_CONTACT_FIELD_TOWN] = $this->ville;
        if ($this->country_code && !empty($conf->global->LDAP_CONTACT_FIELD_COUNTRY))
            $info[$conf->global->LDAP_CONTACT_FIELD_COUNTRY] = $this->country_code;
        if ($this->phone_pro && !empty($conf->global->LDAP_CONTACT_FIELD_PHONE))
            $info[$conf->global->LDAP_CONTACT_FIELD_PHONE] = $this->phone_pro;
        if ($this->phone_perso && !empty($conf->global->LDAP_CONTACT_FIELD_HOMEPHONE))
            $info[$conf->global->LDAP_CONTACT_FIELD_HOMEPHONE] = $this->phone_perso;
        if ($this->phone_mobile && !empty($conf->global->LDAP_CONTACT_FIELD_MOBILE))
            $info[$conf->global->LDAP_CONTACT_FIELD_MOBILE] = $this->phone_mobile;
        if ($this->fax && !empty($conf->global->LDAP_CONTACT_FIELD_FAX))
            $info[$conf->global->LDAP_CONTACT_FIELD_FAX] = $this->fax;
        if ($this->note && !empty($conf->global->LDAP_CONTACT_FIELD_DESCRIPTION))
            $info[$conf->global->LDAP_CONTACT_FIELD_DESCRIPTION] = $this->note;
        if ($this->email && !empty($conf->global->LDAP_CONTACT_FIELD_MAIL))
            $info[$conf->global->LDAP_CONTACT_FIELD_MAIL] = $this->email;

        if ($conf->global->LDAP_SERVER_TYPE == 'egroupware') {
            $info["objectclass"][4] = "phpgwContact"; // compatibilite egroupware

            $info['uidnumber'] = $this->id;

            $info['phpgwTz'] = 0;
            $info['phpgwMailType'] = 'INTERNET';
            $info['phpgwMailHomeType'] = 'INTERNET';

            $info["phpgwContactTypeId"] = 'n';
            $info["phpgwContactCatId"] = 0;
            $info["phpgwContactAccess"] = "public";

            if (dol_strlen($this->egroupware_id) == 0) {
                $this->egroupware_id = 1;
            }

            $info["phpgwContactOwner"] = $this->egroupware_id;

            if ($this->email)
                $info["rfc822Mailbox"] = $this->email;
            if ($this->phone_mobile)
                $info["phpgwCellTelephoneNumber"] = $this->phone_mobile;
        }

        return $info;
    }

    /**
     *  Charge l'objet contact
     *
     *  @param      int		$id          id du contact
     *  @param      User	$user        Utilisateur (abonnes aux alertes) qui veut les alertes de ce contact
     *  @return     int     		    -1 if KO, 0 if OK but not found, 1 if OK
     */
    function fetch($id, $user = 0) {
        global $langs;

        $langs->load("companies");

        try {
            $this->load($id);
            return 1;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return -1;
        }

        $sql = "SELECT c.rowid, c.fk_soc, c.civilite as civilite_id, c.name as lastname, c.firstname,";
        $sql.= " c.address, c.cp as zip, c.ville as town,";
        $sql.= " c.fk_pays as country_id,";
        $sql.= " c.fk_departement,";
        $sql.= " c.birthday,";
        $sql.= " c.poste, c.phone, c.phone_perso, c.phone_mobile, c.fax, c.email, c.jabberid,";
        $sql.= " c.priv, c.note, c.default_lang, c.no_email, c.canvas,";
        $sql.= " c.import_key,";
        $sql.= " p.libelle as country, p.code as country_code,";
        $sql.= " d.nom as state, d.code_departement as state_code,";
        $sql.= " u.rowid as user_id, u.login as user_login,";
        $sql.= " s.nom as socname, s.address as socaddress, s.cp as soccp, s.ville as soccity, s.default_lang as socdefault_lang";
        $sql.= " FROM " . MAIN_DB_PREFIX . "socpeople as c";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_pays as p ON c.fk_pays = p.rowid";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "c_departements as d ON c.fk_departement = d.rowid";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "user as u ON c.rowid = u.fk_socpeople";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON c.fk_soc = s.rowid";
        $sql.= " WHERE c.rowid = " . $id;

        dol_syslog(get_class($this) . "::fetch sql=" . $sql);
        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql)) {
                $obj = $this->db->fetch_object($resql);

                $this->id = $obj->rowid;
                $this->ref = $obj->rowid;
                $this->civilite_id = $obj->civilite_id;
                $this->lastname = $obj->lastname;
                $this->name = $obj->lastname;       // TODO deprecated
                $this->firstname = $obj->firstname;
                $this->nom = $obj->lastname;  // TODO deprecated
                $this->prenom = $obj->firstname;  // TODO deprecated

                $this->address = $obj->address;
                $this->adresse = $obj->address;   // TODO deprecated
                $this->cp = $obj->zip;   // TODO deprecated
                $this->zip = $obj->zip;
                $this->ville = $obj->town;   // TODO deprecated
                $this->town = $obj->town;

                $this->fk_departement = $obj->fk_departement;    // deprecated
                $this->state_id = $obj->fk_departement;
                $this->departement_code = $obj->state_code;        // deprecated
                $this->state_code = $obj->state_code;
                $this->departement = $obj->state;            // deprecated
                $this->state = $obj->state;

                $this->fk_pays = $obj->country_id;
                $this->country_id = $obj->country_id;
                $this->pays_code = $obj->country_id ? $obj->country_code : '';
                $this->country_code = $obj->country_id ? $obj->country_code : '';
                $this->pays = ($obj->country_id > 0) ? $langs->transnoentitiesnoconv("Country" . $obj->country_code) : '';
                $this->country = ($obj->country_id > 0) ? $langs->transnoentitiesnoconv("Country" . $obj->country_code) : '';

                $this->socname = $obj->socname;
                $this->poste = $obj->poste;

                $this->phone_pro = trim($obj->phone);
                $this->fax = trim($obj->fax);
                $this->phone_perso = trim($obj->phone_perso);
                $this->phone_mobile = trim($obj->phone_mobile);

                $this->email = $obj->email;
                $this->jabberid = $obj->jabberid;
                $this->mail = $obj->email;

                $this->birthday = $this->db->jdate($obj->birthday);
                $this->note = $obj->note;
                $this->default_lang = $obj->default_lang;
                $this->no_email = $obj->no_email;
                $this->user_id = $obj->user_id;
                $this->user_login = $obj->user_login;
                $this->canvas = $obj->canvas;

                $this->import_key = $obj->import_key;

                // Recherche le user Dolibarr lie a ce contact
                $sql = "SELECT u.rowid ";
                $sql .= " FROM " . MAIN_DB_PREFIX . "user as u";
                $sql .= " WHERE u.fk_socpeople = " . $this->id;

                $resql = $this->db->query($sql);
                if ($resql) {
                    if ($this->db->num_rows($resql)) {
                        $uobj = $this->db->fetch_object($resql);

                        $this->user_id = $uobj->rowid;
                    }
                    $this->db->free($resql);
                } else {
                    $this->error = $this->db->error();
                    dol_syslog(get_class($this) . "::fetch " . $this->error, LOG_ERR);
                    return -1;
                }

                // Charge alertes du user
                if ($user) {
                    $sql = "SELECT fk_user";
                    $sql .= " FROM " . MAIN_DB_PREFIX . "user_alert";
                    $sql .= " WHERE fk_user = " . $user->id . " AND fk_contact = " . $id;

                    $resql = $this->db->query($sql);
                    if ($resql) {
                        if ($this->db->num_rows($resql)) {
                            $obj = $this->db->fetch_object($resql);

                            $this->birthday_alert = 1;
                        }
                        $this->db->free($resql);
                    } else {
                        $this->error = $this->db->error();
                        dol_syslog(get_class($this) . "::fetch " . $this->error, LOG_ERR);
                        return -1;
                    }
                }

                return 1;
            } else {
                $this->error = $langs->trans("RecordNotFound");
                return 0;
            }
        } else {
            $this->error = $this->db->error();
            dol_syslog(get_class($this) . "::fetch " . $this->error, LOG_ERR);
            return -1;
        }
    }

    /**
     *  Charge le nombre d'elements auquel est lie ce contact
     *  ref_facturation
     *  ref_contrat
     *  ref_commande
     *  ref_propale
     *
     *  @return     int             					<0 if KO, >=0 if OK
     */
    function load_ref_elements() {
        // Compte les elements pour lesquels il est contact
        $sql = "SELECT tc.element, count(ec.rowid) as nb";
        $sql.=" FROM " . MAIN_DB_PREFIX . "element_contact as ec, " . MAIN_DB_PREFIX . "c_type_contact as tc";
        $sql.=" WHERE ec.fk_c_type_contact = tc.rowid";
        $sql.=" AND fk_socpeople = " . $this->id;
        $sql.=" GROUP BY tc.element";

        dol_syslog(get_class($this) . "::load_ref_elements sql=" . $sql);

        $resql = $this->db->query($sql);
        if ($resql) {
            while ($obj = $this->db->fetch_object($resql)) {
                if ($obj->nb) {
                    if ($obj->element == 'facture')
                        $this->ref_facturation = $obj->nb;
                    if ($obj->element == 'contrat')
                        $this->ref_contrat = $obj->nb;
                    if ($obj->element == 'commande')
                        $this->ref_commande = $obj->nb;
                    if ($obj->element == 'propal')
                        $this->ref_propal = $obj->nb;
                }
            }
            $this->db->free($resql);
            return 0;
        }
        else {
            $this->error = $this->db->error() . " - " . $sql;
            dol_syslog(get_class($this) . "::load_ref_elements Error " . $this->error, LOG_ERR);
            return -1;
        }
    }

    /**
     *   	Efface le contact de la base
     *
     *   	@param		int		$notrigger		Disable all trigger
     * 		@return		int						<0 if KO, >0 if OK
     */
    function delete($notrigger = 0) {
        global $conf, $langs, $user;

        $error = 0;

        $this->old_name = $obj->name;
        $this->old_firstname = $obj->firstname;

        $this->db->begin();

        if (!$error) {
            // Get all rowid of element_contact linked to a type that is link to llx_socpeople
            $sql = "SELECT ec.rowid";
            $sql.= " FROM " . MAIN_DB_PREFIX . "element_contact ec,";
            $sql.= " " . MAIN_DB_PREFIX . "c_type_contact tc";
            $sql.= " WHERE ec.fk_socpeople=" . $this->id;
            $sql.= " AND ec.fk_c_type_contact=tc.rowid";
            $sql.= " AND tc.source='external'";
            dol_syslog(get_class($this) . "::delete sql=" . $sql);
            $resql = $this->db->query($sql);
            if ($resql) {
                $num = $this->db->num_rows($resql);

                $i = 0;
                while ($i < $num && !$error) {
                    $obj = $this->db->fetch_object($resql);

                    $sqldel = "DELETE FROM " . MAIN_DB_PREFIX . "element_contact";
                    $sqldel.=" WHERE rowid = " . $obj->rowid;
                    dol_syslog(get_class($this) . "::delete sql=" . $sqldel);
                    $result = $this->db->query($sqldel);
                    if (!$result) {
                        $error++;
                        $this->error = $this->db->error() . ' sql=' . $sqldel;
                    }

                    $i++;
                }
            } else {
                $error++;
                $this->error = $this->db->error() . ' sql=' . $sql;
            }
        }

        if (!$error) {
            $sql = "DELETE FROM " . MAIN_DB_PREFIX . "socpeople";
            $sql .= " WHERE rowid=" . $this->id;
            dol_syslog(get_class($this) . "::delete sql=" . $sql);
            $result = $this->db->query($sql);
            if (!$result) {
                $error++;
                $this->error = $this->db->error() . ' sql=' . $sql;
            }
        }

        if (!$error && !$notrigger) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('CONTACT_DELETE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers

            if ($error)
                $this->error = join(',', $this->errors);
        }

        if (!$error) {

            $this->db->commit();
            return 1;
        } else {
            $this->db->rollback();
            dol_syslog("Error " . $this->error, LOG_ERR);
            return -1;
        }
    }

    /**
     *  Charge les informations sur le contact, depuis la base
     *
     *  @param		int		$id      Id du contact a charger
     *  @return		void
     */
    function info($id) {
        $sql = "SELECT c.rowid, c.datec as datec, c.fk_user_creat,";
        $sql.= " c.tms as tms, c.fk_user_modif";
        $sql.= " FROM " . MAIN_DB_PREFIX . "socpeople as c";
        $sql.= " WHERE c.rowid = " . $id;

        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql)) {
                $obj = $this->db->fetch_object($resql);

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

                $this->date_creation = $this->db->jdate($obj->datec);
                $this->date_modification = $this->db->jdate($obj->tms);
            }

            $this->db->free($resql);
        } else {
            print $this->db->error();
        }
    }

    /**
     *  Return number of mass Emailing received by this contacts with its email
     *
     *  @return       int     Number of EMailings
     */
    function getNbOfEMailings() {
        $sql = "SELECT count(mc.email) as nb";
        $sql.= " FROM " . MAIN_DB_PREFIX . "mailing_cibles as mc";
        $sql.= " WHERE mc.email = '" . $this->db->escape($this->email) . "'";
        $sql.= " AND mc.statut NOT IN (-1,0)";      // -1 erreur, 0 non envoye, 1 envoye avec succes

        dol_syslog(get_class($this) . "::getNbOfEMailings sql=" . $sql, LOG_DEBUG);

        $resql = $this->db->query($sql);

        if ($resql) {
            $obj = $this->db->fetch_object($resql);
            $nb = $obj->nb;

            $this->db->free($resql);
            return $nb;
        } else {
            $this->error = $this->db->error();
            return -1;
        }
    }

    /**
     *  Return name of contact with link (and eventually picto)
     * 	Use $this->id, $this->name, $this->firstname, this->civilite_id
     *
     * 	@param		int			$withpicto		Include picto with link
     * 	@param		string		$option			Where the link point to
     * 	@param		int			$maxlen			Max length of
     * 	@return		string						String with URL
     */
    function getNomUrl($withpicto = 0, $option = '', $maxlen = 0) {
        global $langs;

        $result = '';

        $lien = '<a href="' . DOL_URL_ROOT . '/contact/fiche.php?id=' . $this->id . '">';
        $lienfin = '</a>';

        if ($option == 'xxx') {
            $lien = '<a href="' . DOL_URL_ROOT . '/contact/fiche.php?id=' . $this->id . '">';
            $lienfin = '</a>';
        }

        if ($withpicto)
            $result.=($lien . img_object($langs->trans("ShowContact") . ': ' . $this->getFullName($langs), 'contact') . $lienfin . ' ');
        $result.=$lien . ($maxlen ? dol_trunc($this->getFullName($langs), $maxlen) : $this->getFullName($langs)) . $lienfin;
        return $result;
    }

    /**
     * 	Return full address of contact
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
     * 	Renvoi le libelle d'un statut donne
     *
     *  @param      int			$statut     Id statut
     *  @param      int			$mode       0=libelle long, 1=libelle court, 2=Picto + Libelle court, 3=Picto, 4=Picto + Libelle long, 5=Libelle court + Picto
     *  @return     string					Libelle
     */
    /* function LibStatut($statut, $mode) {
      global $langs;

      if ($mode == 0) {
      if ($statut == 0)
      return $langs->trans('StatusContactDraft');
      elseif ($statut == 1)
      return $langs->trans('StatusContactValidated');
      elseif ($statut == 4)
      return $langs->trans('StatusContactValidated');
      elseif ($statut == 5)
      return $langs->trans('StatusContactValidated');
      }
      elseif ($mode == 1) {
      if ($statut == 0)
      return $langs->trans('StatusContactDraftShort');
      elseif ($statut == 1)
      return $langs->trans('StatusContactValidatedShort');
      elseif ($statut == 4)
      return $langs->trans('StatusContactValidatedShort');
      elseif ($statut == 5)
      return $langs->trans('StatusContactValidatedShort');
      }
      elseif ($mode == 2) {
      if ($statut == 0)
      return img_picto($langs->trans('StatusContactDraftShort'), 'statut0') . ' ' . $langs->trans('StatusContactDraft');
      elseif ($statut == 1)
      return img_picto($langs->trans('StatusContactValidatedShort'), 'statut1') . ' ' . $langs->trans('StatusContactValidated');
      elseif ($statut == 4)
      return img_picto($langs->trans('StatusContactValidatedShort'), 'statut4') . ' ' . $langs->trans('StatusContactValidated');
      elseif ($statut == 5)
      return img_picto($langs->trans('StatusContactValidatedShort'), 'statut5') . ' ' . $langs->trans('StatusContactValidated');
      }
      elseif ($mode == 3) {
      if ($statut == 0)
      return img_picto($langs->trans('StatusContactDraft'), 'statut0');
      elseif ($statut == 1)
      return img_picto($langs->trans('StatusContactValidated'), 'statut1');
      elseif ($statut == 4)
      return img_picto($langs->trans('StatusContactValidated'), 'statut4');
      elseif ($statut == 5)
      return img_picto($langs->trans('StatusContactValidated'), 'statut5');
      }
      elseif ($mode == 4) {
      if ($statut == 0)
      return img_picto($langs->trans('StatusContactDraft'), 'statut0') . ' ' . $langs->trans('StatusContactDraft');
      elseif ($statut == 1)
      return img_picto($langs->trans('StatusContactValidated'), 'statut1') . ' ' . $langs->trans('StatusContactValidated');
      elseif ($statut == 4)
      return img_picto($langs->trans('StatusContactValidated'), 'statut4') . ' ' . $langs->trans('StatusContactValidated');
      elseif ($statut == 5)
      return img_picto($langs->trans('StatusContactValidated'), 'statut5') . ' ' . $langs->trans('StatusContactValidated');
      }
      elseif ($mode == 5) {
      if ($statut == 0)
      return $langs->trans('StatusContactDraftShort') . ' ' . img_picto($langs->trans('StatusContactDraftShort'), 'statut0');
      elseif ($statut == 1)
      return $langs->trans('StatusContactValidatedShort') . ' ' . img_picto($langs->trans('StatusContactValidatedShort'), 'statut1');
      elseif ($statut == 4)
      return $langs->trans('StatusContactValidatedShort') . ' ' . img_picto($langs->trans('StatusContactValidatedShort'), 'statut4');
      elseif ($statut == 5)
      return $langs->trans('StatusContactValidatedShort') . ' ' . img_picto($langs->trans('StatusContactValidatedShort'), 'statut5');
      }
      } */

    /**
     *  Initialise an instance with random values.
     *  Used to build previews or test instances.
     * 	id must be 0 if object instance is a specimen.
     *
     *  @return	void
     */
    function initAsSpecimen() {
        global $user, $langs;

        // Charge tableau des id de societe socids
        $socids = array();
        $sql = "SELECT rowid FROM " . MAIN_DB_PREFIX . "societe LIMIT 10";
        $resql = $this->db->query($sql);
        if ($resql) {
            $num_socs = $this->db->num_rows($resql);
            $i = 0;
            while ($i < $num_socs) {
                $i++;

                $row = $this->db->fetch_row($resql);
                $socids[$i] = $row[0];
            }
        }

        // Initialise parameters
        $this->id = 0;
        $this->specimen = 1;
        $this->lastname = 'DOLIBARR';
        $this->firstname = 'SPECIMEN';
        $this->address = '61 jump street';
        $this->zip = '75000';
        $this->town = 'Paris';
        $this->country_id = 1;
        $this->country_code = 'FR';
        $this->country = 'France';
        $this->email = 'specimen@specimen.com';
        $socid = rand(1, $num_socs);
        $this->socid = $socids[$socid];
    }

    /**
     * 	Show html area for list of contacts
     *
     * 	@param	Conf		$conf		Object conf
     * 	@param	Translate	$langs		Object langs
     * 	@param	DoliDB		$db			Database handler
     * 	@param	Object		$object		Third party object
     *  @param  string		$backtopage	Url to go once contact is created
     *  @return	void
     */
    function show($max = 5, $id = 0) {
        global $langs, $conf, $user, $db, $bc;

        $titre = $langs->trans("ContactsForCompany");
        print start_box($titre, "six", "16-Users.png");

        $i = 0;
        $obj = new stdClass();
        $societe = new Societe($this->db);

        /*
         * Barre d'actions
         *
         */

        //print $this->datatablesEdit("contacts_datatable");
        if ($user->rights->societe->contact->creer) {
            print '<p class="button-height right">';
            print '<span class="button-group">';
            print '<a class="button compact icon-star" href="contact/fiche.php?action=create&socid='.$id.'">' . $langs->trans("NewContact") . '</a>';
            print "</span>";
            print "</p>";
        }

        print '<table class="display dt_act" id="contacts_datatable" >';
        // Ligne des titres

        print '<thead>';
        print'<tr>';
        print'<th>';
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "_id";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = false;
        $obj->aoColumns[$i]->bVisible = false;
        $i++;
        print'<th class="essential">';
        print $langs->trans("Name");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "name";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("name", "url");
        $i++;
        print'<th class="essential">';
        print $langs->trans('PostOrFunction');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "poste";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $i++;
        print'<th class="essential">';
        print $langs->trans('Mobile');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "phone_mobile";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $i++;
        print'<th class="essential">';
        print $langs->trans('EMail');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "email";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $i++;
        print '</tr>';
        print '</thead>';
        print'<tfoot>';
        print'</tfoot>';
        print'<tbody>';
        print'</tbody>';
        print "</table>";

        $obj->iDisplayLength = $max;
        $obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listdatatables.php?json=listSociete&class=" . get_class($this) . "&key=" . $id;
        $this->datatablesCreate($obj, "contacts_datatable", true);

        print end_box();
    }

    /* function show_contacts($conf, $langs, $db, $object, $backtopage = '') {
      global $user, $couch;
      global $bc;

      $i = -1;

      $titre = $langs->trans("ContactsForCompany");
      print start_box($titre, "twelve", "16-Users.png");

      $contactstatic = new Contact($db);

      if (!empty($conf->clicktodial->enabled)) {
      $user->fetch_clicktodial(); // lecture des infos de clicktodial
      }

      $buttoncreate = '';
      if ($user->rights->societe->contact->creer) {
      $addcontact = (!empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT) ? $langs->trans("AddContact") : $langs->trans("AddContactAddress"));
      $buttoncreate = '<a class="addnewrecord" href="' . DOL_URL_ROOT . '/contact/fiche.php?socid=' . $object->id . '&amp;action=create&amp;backtopage=' . urlencode($backtopage) . '">' . $addcontact . ' ' . img_picto($addcontact, 'filenew') . '</a>' . "\n";
      }

      print "\n";

      $title = (!empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT) ? $langs->trans("ContactsForCompany") : $langs->trans("ContactsAddressesForCompany"));
      //print_fiche_titre($title,$buttoncreate,'');

      print "\n" . '<table class="noborder" width="100%">' . "\n";

      print '<tr class="liste_titre"><td>' . $langs->trans("Name") . '</td>';
      print '<td>' . $langs->trans("Poste") . '</td><td>' . $langs->trans("Tel") . '</td>';
      print '<td>' . $langs->trans("Fax") . '</td><td>' . $langs->trans("EMail") . '</td>';
      print "<td>&nbsp;</td>";
      if (!empty($conf->agenda->enabled) && $user->rights->agenda->myactions->create) {
      print '<td>&nbsp;</td>';
      }
      print "</tr>";

      $sql = "SELECT p.rowid, p.name, p.firstname, p.fk_pays, p.poste, p.phone, p.fax, p.email, p.note ";
      $sql .= " FROM " . MAIN_DB_PREFIX . "socpeople as p";
      $sql .= " WHERE p.fk_soc = " . $object->id;
      $sql .= " ORDER by p.datec";

      $result = $db->query($sql);
      $num = $db->num_rows($result);

      if ($num) {
      $i = 0;
      $var = true;

      while ($i < $num) {
      $obj = $db->fetch_object($result);
      $var = !$var;

      print "<tr " . $bc[$var] . ">";

      print '<td>';
      $contactstatic->id = $obj->rowid;
      $contactstatic->name = $obj->name;
      $contactstatic->firstname = $obj->firstname;
      print $contactstatic->getNomUrl(1);
      print '</td>';

      print '<td>' . $obj->poste . '</td>';

      $country_code = getCountry($obj->fk_pays, 2);

      // Lien click to dial
      print '<td>';
      print dol_print_phone($obj->phone, $country_code, $obj->rowid, $object->id, 'AC_TEL');
      print '</td>';
      print '<td>';
      print dol_print_phone($obj->fax, $country_code, $obj->rowid, $object->id, 'AC_FAX');
      print '</td>';
      print '<td>';
      print dol_print_email($obj->email, $obj->rowid, $object->id, 'AC_EMAIL');
      print '</td>';

      if (!empty($conf->agenda->enabled) && $user->rights->agenda->myactions->create) {
      print '<td align="center"><a href="' . DOL_URL_ROOT . '/comm/action/fiche.php?action=create&actioncode=AC_RDV&contactid=' . $obj->rowid . '&socid=' . $object->id . '&backtopage=' . urlencode($backtopage) . '">';
      print img_object($langs->trans("Rendez-Vous"), "action");
      print '</a></td>';
      }

      if ($user->rights->societe->contact->creer) {
      print '<td align="right">';
      print '<a href="' . DOL_URL_ROOT . '/contact/fiche.php?action=edit&amp;id=' . $obj->rowid . '&amp;backtopage=' . urlencode($backtopage) . '">';
      print img_edit();
      print '</a></td>';
      }

      print "</tr>\n";
      $i++;
      }
      } else {
      //print "<tr ".$bc[$var].">";
      //print '<td>'.$langs->trans("NoContactsYetDefined").'</td>';
      //print "</tr>\n";
      }
      print "\n</table>\n";

      print end_box();

      return $i;
      } */
}

?>
