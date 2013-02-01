<?php

/* Copyright (c) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (c) 2002-2003 Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (c) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2005      Lionel Cousteix      <etm_ltd@tiscali.co.uk>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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

require_once DOL_DOCUMENT_ROOT . '/core/class/nosqlDocument.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/db/couchdb/lib/couchAdmin.php';
require_once DOL_DOCUMENT_ROOT . '/user/class/userdatabase.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

/**
 * 	Class to manage Dolibarr users
 */
class User extends nosqlDocument {

    public $element = 'user';
    public $table_element = 'user';
    protected $ismultientitymanaged = 1; // 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
    protected $couchAdmin;
    var $id;
    var $Lastname;
    var $Firstname;
    var $note;
    var $email;
    var $Signature;
    var $office_phone;
    var $office_fax;
    var $user_mobile;
    var $admin;
    var $login;
    //! Clear password in memory
    var $pass;
    //! Clear password in database (defined if DATABASE_PWD_ENCRYPTED=0)
    var $pass_indatabase;
    //! Encrypted password in database (always defined)
    var $pass_indatabase_crypted;
    var $datec;
    var $datem;
    //! If this is defined, it is an external user
    var $societe_id;
    var $contact_id;
    var $fk_member;
    var $datelastlogin;
    var $datepreviouslogin;
    var $Status;
    var $Photo;
    var $Lang;
    //! Liste des entrepots auquel a acces l'utilisateur
    var $entrepots;
    var $rights;   // Array of permissions user->rights->permx
    var $all_permissions_are_loaded; /*     * < \private all_permissions_are_loaded */
    private $_tab_loaded = array();  // Array of cache of already loaded permissions
    var $conf;  // To store personal config

    /**
     *    Constructor de la classe
     *
     *    @param   DoliDb  $db     Database handler
     */

    function __construct($db) {
        $this->db = $db;

        parent::__construct($db);

        $this->fk_extrafields = new ExtraFields($db);
        $this->fk_extrafields->fetch(get_class($this));

        $this->couchAdmin = new couchAdmin($this->couchdb);

        // Preference utilisateur
        $this->liste_limit = 0;
        $this->clicktodial_loaded = 0;

        $this->all_permissions_are_loaded = 0;
        $this->admin = 0;

        $this->conf = new stdClass();
        $this->rights = new stdClass();
        $this->rights->user = new stdClass();
        $this->rights->user->user = new stdClass();
        $this->rights->user->self = new stdClass();
    }

    /**
     * 	Load a user from database with its id or ref (login)
     *
     * 	@param	string	$id		       		Si defini, id a utiliser pour recherche
     * 	@param  string	$login       		Si defini, login a utiliser pour recherche
     * 	@param  strinf	$sid				Si defini, sid a utiliser pour recherche
     * 	@param	int		$loadpersonalconf	Also load personal conf of user (in $user->conf->xxx)
     * 	@return	int							<0 if KO, 0 not found, >0 if OK
     */
    function fetch($login = "") {
        global $conf, $couch;

        // Clean parametersadmin
        $login = trim($login);

        if (empty($login)) {

            $login = $this->couchAdmin->getLoginSession();
            if (empty($login))
                return 0;
        }

        if ($conf->Couchdb->name == '_users') {
            require_once(DOL_DOCUMENT_ROOT . "/useradmin/class/useradmin.class.php");

            $user_config = new UserAdmin($this->db);
            $user_config->fetch("org.couchdb.user:" . $login); // Load for default entity
            $user_config->LastConnection = $user_config->NewConnection;
            $user_config->NewConnection = dol_now();
            //$user_config->record(); // FIXME no record method in fetch method
            //print_r($login);
            //exit;
            $couch->useDatabase($user_config->entity);
            $conf->Couchdb->name = $user_config->entity;
            dol_setcache("dol_entity", $user_config->entity);
            $this->useDatabase($user_config->entity);
            unset($user_config);

            if (!$conf->urlrewrite) {
                $this->LastConnection = $this->NewConnection;
                $this->NewConnection = dol_now();
                //$this->record(true); // FIXME no record method in fetch method
            }
        }

        try {
            if (isValidEmail($login)) {
                $result = $this->getView("login", array("key" => $login));
                $login = $result->rows[0]->value;
            }

            $this->load($login, true);
        } catch (Exception $e) {
            return 0;
        }

        // Test if User is a global administrator
        try {
            $admins = $this->couchAdmin->getUserAdmins();
            $name = $this->couchAdmin->getLoginSession();
            if (isset($admins->$name) && $this->email == $name)
                $this->superadmin = true;
            else
                $this->superadmin = false;
        } catch (Exception $e) {
            $this->superadmin = false;
        }

        // Test if User is a local administrator for a specific databses
        if ($this->superadmin) {
            $this->admin = true;
        } else {
            $membersAdmin = $this->couchAdmin->getDatabaseAdminUsers();
            if (in_array($this->email, $membersAdmin))
                $this->admin = true;
            else
                $this->admin = false;
        }

        /* try {
          $database = new UserDatabase($this->db);
          $database->fetch($conf->Couchdb->name);
          $result = $database->couchAdmin->getDatabaseAdminUsers(); // Administrateur local de la bd

          if (in_array($this->name, $result)) {
          $this->admin = true;
          }
          } catch (Exception $e) {

          } */

        $this->id = $this->_id;

        return 1;
    }

    /**
     *  Ajoute un droit a l'utilisateur
     *
     * 	@param	int		$rid         id du droit a ajouter
     *  @param  string	$allmodule   Ajouter tous les droits du module allmodule
     *  @param  string	$allperms    Ajouter tous les droits du module allmodule, perms allperms
     *  @return int   			     > 0 if OK, < 0 if KO
     */
    function addrights($rid, $allmodule = '', $allperms = '') {
        global $conf;

        dol_syslog(get_class($this) . "::addrights $rid, $allmodule, $allperms");
        $err = 0;
        $whereforadd = '';

        $this->db->begin();

        if ($rid) {
            // Si on a demande ajout d'un droit en particulier, on recupere
            // les caracteristiques (module, perms et subperms) de ce droit.
            $sql = "SELECT module, perms, subperms";
            $sql.= " FROM " . MAIN_DB_PREFIX . "rights_def";
            $sql.= " WHERE id = '" . $rid . "'";
            $sql.= " AND entity = " . $conf->entity;

            $result = $this->db->query($sql);
            if ($result) {
                $obj = $this->db->fetch_object($result);
                $module = $obj->module;
                $perms = $obj->perms;
                $subperms = $obj->subperms;
            } else {
                $err++;
                dol_print_error($this->db);
            }

            // Where pour la liste des droits a ajouter
            $whereforadd = "id=" . $rid;
            // Ajout des droits induits
            if ($subperms)
                $whereforadd.=" OR (module='$module' AND perms='$perms' AND (subperms='lire' OR subperms='read'))";
            else if ($perms)
                $whereforadd.=" OR (module='$module' AND (perms='lire' OR perms='read') AND subperms IS NULL)";
        }
        else {
            // On a pas demande un droit en particulier mais une liste de droits
            // sur la base d'un nom de module de de perms
            // Where pour la liste des droits a ajouter
            if ($allmodule)
                $whereforadd = "module='$allmodule'";
            if ($allperms)
                $whereforadd = " AND perms='$allperms'";
        }

        // Ajout des droits trouves grace au critere whereforadd
        if ($whereforadd) {
            //print "$module-$perms-$subperms";
            $sql = "SELECT id";
            $sql.= " FROM " . MAIN_DB_PREFIX . "rights_def";
            $sql.= " WHERE " . $whereforadd;
            $sql.= " AND entity = " . $conf->entity;

            $result = $this->db->query($sql);
            if ($result) {
                $num = $this->db->num_rows($result);
                $i = 0;
                while ($i < $num) {
                    $obj = $this->db->fetch_object($result);
                    $nid = $obj->id;

                    $sql = "DELETE FROM " . MAIN_DB_PREFIX . "user_rights WHERE fk_user = " . $this->id . " AND fk_id=" . $nid;
                    if (!$this->db->query($sql))
                        $err++;
                    $sql = "INSERT INTO " . MAIN_DB_PREFIX . "user_rights (fk_user, fk_id) VALUES (" . $this->id . ", " . $nid . ")";
                    if (!$this->db->query($sql))
                        $err++;

                    $i++;
                }
            }
            else {
                $err++;
                dol_print_error($this->db);
            }
        }

        if ($err) {
            $this->db->rollback();
            return -$err;
        } else {
            $this->db->commit();
            return 1;
        }
    }

    /**
     *  Retire un droit a l'utilisateur
     *
     *  @param	int		$rid        Id du droit a retirer
     *  @param  string	$allmodule  Retirer tous les droits du module allmodule
     *  @param  string	$allperms   Retirer tous les droits du module allmodule, perms allperms
     *  @return int         		> 0 if OK, < 0 if OK
     */
    function delrights($rid, $allmodule = '', $allperms = '') {
        global $conf;

        $err = 0;
        $wherefordel = '';

        $this->db->begin();

        if ($rid) {
            // Si on a demande supression d'un droit en particulier, on recupere
            // les caracteristiques module, perms et subperms de ce droit.
            $sql = "SELECT module, perms, subperms";
            $sql.= " FROM " . MAIN_DB_PREFIX . "rights_def";
            $sql.= " WHERE id = '" . $rid . "'";
            $sql.= " AND entity = " . $conf->entity;

            $result = $this->db->query($sql);
            if ($result) {
                $obj = $this->db->fetch_object($result);
                $module = $obj->module;
                $perms = $obj->perms;
                $subperms = $obj->subperms;
            } else {
                $err++;
                dol_print_error($this->db);
            }

            // Where pour la liste des droits a supprimer
            $wherefordel = "id=" . $rid;
            // Suppression des droits induits
            if ($subperms == 'lire' || $subperms == 'read')
                $wherefordel.=" OR (module='$module' AND perms='$perms' AND subperms IS NOT NULL)";
            if ($perms == 'lire' || $perms == 'read')
                $wherefordel.=" OR (module='$module')";
        }
        else {
            // On a demande suppression d'un droit sur la base d'un nom de module ou perms
            // Where pour la liste des droits a supprimer
            if ($allmodule)
                $wherefordel = "module='$allmodule'";
            if ($allperms)
                $wherefordel = " AND perms='$allperms'";
        }

        // Suppression des droits selon critere defini dans wherefordel
        if ($wherefordel) {
            //print "$module-$perms-$subperms";
            $sql = "SELECT id";
            $sql.= " FROM " . MAIN_DB_PREFIX . "rights_def";
            $sql.= " WHERE $wherefordel";
            $sql.= " AND entity = " . $conf->entity;

            $result = $this->db->query($sql);
            if ($result) {
                $num = $this->db->num_rows($result);
                $i = 0;
                while ($i < $num) {
                    $obj = $this->db->fetch_object($result);
                    $nid = $obj->id;

                    $sql = "DELETE FROM " . MAIN_DB_PREFIX . "user_rights";
                    $sql.= " WHERE fk_user = " . $this->id . " AND fk_id=" . $nid;
                    if (!$this->db->query($sql))
                        $err++;

                    $i++;
                }
            }
            else {
                $err++;
                dol_print_error($this->db);
            }
        }

        if ($err) {
            $this->db->rollback();
            return -$err;
        } else {
            $this->db->commit();
            return 1;
        }
    }

    /**
     *  Clear all permissions array of user
     *
     *  @return	void
     */
    function clearrights() {
        dol_syslog(get_class($this) . "::clearrights reset user->rights");
        $this->rights = '';
        $this->all_permissions_are_loaded = false;
        $this->_tab_loaded = array();
    }

    /**
     * 	Load permissions granted to user into object user
     *
     * 	@param  string	$moduletag    Limit permission for a particular module ('' by default means load all permissions)
     * 	@return	void
     */
    function getrights($moduletag = '') {
        global $conf;

        if ($moduletag && isset($this->_tab_loaded[$moduletag]) && $this->_tab_loaded[$moduletag]) {
            // Le fichier de ce module est deja charge
            return;
        }

        if ($this->all_permissions_are_loaded) {
            // Si les permissions ont deja ete charge pour ce user, on quitte
            return;
        }

        $object = new DolibarrModules($this->db);

        try {
            $result = $object->getView("default_right", '', true);
            foreach ($this->group as $aRow) // load groups
                $groups[] = $object->load("group:" . $aRow, true);
        } catch (Exception $exc) {
            print $exc->getMessage();
        }

        if (count($result->rows)) {
            foreach ($result->rows as $aRow) {
                //$object->name = $aRow->value->name;
                //$object->numero = $aRow->value->numero;
                $rights_class = $aRow->value->rights_class;
                //$object->id = $aRow->value->id;
                $perm = $aRow->value->perm;

                // Add default rights
                if (! is_object($this->rights->$rights_class))
                	$this->rights->$rights_class = new stdClass();
                if (count($perm) == 1)
                    $this->rights->$rights_class->$perm[0] = $aRow->value->Status;
                elseif (count($perm) == 2) {
                	if (! is_object($this->rights->$rights_class->$perm[0]))
                		$this->rights->$rights_class->$perm[0] = new stdClass();
                    if (isset($this->rights->$rights_class->$perm[0]))
                        $this->rights->$rights_class->$perm[0]->$perm[1] = $aRow->value->Status;
                    else
                        $this->rights->$rights_class->$perm[0]->$perm[1] = $aRow->value->Status;
                }

                // Add user rights

                if ((is_array($this->rights) && isset($this->rights->$key)) || (is_array($this->own_rights) && isset($this->own_rights->$key)) || $this->admin) {
                    if (count($perm) == 1)
                        $this->rights->$rights_class->$perm[0] = true;
                    elseif (count($perm) == 2)
                        $this->rights->$rights_class->$perm[0]->$perm[1] = true;
                }

                // Add groups rights
                for ($i = 0; $i < count($groups); $i++) {
                    $key = $aRow->value->id;
                    if (isset($groups[$i]->rights->$key)) {
                        if (count($perm) == 1)
                            $this->rights->$rights_class->$perm[0] = true;
                        elseif (count($perm) == 2)
                            $this->rights->$rights_class->$perm[0]->$perm[1] = true;
                    }
                }
            }
        }

        //print_r($this->rights);

        // Convert for old right definition
        if (! empty($this->rights->societe->creer))
            $this->rights->societe->edit = true;
        if (! empty($this->rights->societe->supprimer))
            $this->rights->societe->delete = true;
        if (! empty($this->rights->societe->contact->creer)) {
        	if (! is_object($this->rights->contact))
        		$this->rights->contact = new stdClass(); // For avoid error
            $this->rights->contact->edit = true;
        }
        if (! empty($this->rights->societe->contact->supprimer)) {
        	if (! is_object($this->rights->contact))
        		$this->rights->contact = new stdClass(); // For avoid error
            $this->rights->contact->delete = true;
        }
        if (! empty($this->rights->agenda->myactions->write))
            $this->rights->agenda->edit = true;
        if (! empty($this->rights->agenda->myactions->delete))
            $this->rights->agenda->delete = true;
        if (! empty($this->rights->commande->creer))
            $this->rights->commande->edit = true;
        if (! empty($this->rights->commande->supprimer))
            $this->rights->commande->delete = true;



        if (!$moduletag) {
            // Si module etait non defini, alors on a tout charge, on peut donc considerer
            // que les droits sont en cache (car tous charges) pour cet instance de user
            $this->all_permissions_are_loaded = 1;
        } else {
            // Si module defini, on le marque comme charge en cache
            $this->_tab_loaded[$moduletag] = 1;
        }
    }

    /**
     *  Change status of a user
     *
     * 	@param	int		$statut		Status to set
     *  @return int     			<0 if KO, 0 if nothing is done, >0 if OK
     */
    function setstatus($status) {
        $error = 0;

        if ($status == 0)
            $status = "DISABLE";
        else
            $status = "ENABLE";

        // Check parameters
        if ($this->Status == $status)
            return 0;
        else {
            $userid = $this->email;

            if ($status == 'ENABLE') {
                if ($this->admin == true)
                    $this->couchAdmin->addDatabaseAdminUser($userid);
                else
                    $this->couchAdmin->addDatabaseReaderUser($userid);
            }
            elseif ($status == 'DISABLE') {
                $this->couchAdmin->removeDatabaseAdminUser($userid);
                $this->couchAdmin->removeDatabaseReaderUser($userid);
            }

            $this->set("Status", $status);
            dol_delcache($this->id);
        }

        return 1;
    }

    /**
     *    	Delete the user
     *
     * 		@return		int		<0 if KO, >0 if OK
     */
    function delete() {
        global $user, $conf, $langs;

        $error = 0;

        $this->db->begin();

        $this->fetch($this->id);

        // Supprime droits
        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "user_rights WHERE fk_user = " . $this->id;
        if ($this->db->query($sql)) {

        }

        // Remove group
        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "usergroup_user WHERE fk_user  = " . $this->id;
        if ($this->db->query($sql)) {

        }

        // Si contact, supprime lien
        if ($this->contact_id) {
            $sql = "UPDATE " . MAIN_DB_PREFIX . "socpeople SET fk_user_creat = null WHERE rowid = " . $this->contact_id;
            if ($this->db->query($sql)) {

            }
        }

        // Supprime utilisateur
        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "user WHERE rowid = $this->id";
        $result = $this->db->query($sql);

        if ($result) {
            // Appel des triggers
            include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('USER_DELETE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers

            $this->db->commit();
            return 1;
        } else {
            $this->db->rollback();
            return -1;
        }
    }

    /**
     *  Create or Update an user into database
     *
     *  @param	User	$user        	Objet user qui demande la creation
     *  @param  int		$notrigger		1 ne declenche pas les triggers, 0 sinon
     *  @return int			         	<0 si KO, id compte cree si OK
     */
    function update($user, $notrigger = 0, $action) {
        global $conf, $langs;
        global $mysoc;

        // Clean parameters
        $this->name = trim($this->name);

        dol_syslog(get_class($this) . "::create login=" . $this->name . ", user=" . (is_object($user) ? $user->id : ''), LOG_DEBUG);

        // Check parameters
        if (!isValidEMail($this->email)) {
            $langs->load("errors");
            $this->error = $langs->trans("ErrorBadEMail", $this->email);
            return -1;
        }

        $this->CreateDate = dol_now();
        trim($this->pass);

        $error = 0;

        if ($action == 'add') {
            $this->Status = "DISABLE";
            $this->_id = "user:" . $this->name;
        }


        try {
            //print_r($this);
            $result = $this->record(true); // Save all specific parameters
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            dol_syslog(get_class($this) . "::create " . $this->error, LOG_ERR);
            dol_print_error("", $this->error);
            exit;
            return -3;
        }

        if ($result) {
            $this->id = $this->name;
            $this->_id = $result->id;
            $this->_rev = $result->rev;

            if (!$notrigger) {
                // Appel des triggers
                include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
                $interface = new Interfaces($this->db);
                $result = $interface->run_triggers('USER_CREATE', $this, $user, $langs, $conf);
                if ($result < 0) {
                    $error++;
                    $this->errors = $interface->errors;
                }
                // Fin appel triggers
            }
        } else {
            $this->error = $this->db->lasterror();
            dol_syslog(get_class($this) . "::create " . $this->error, LOG_ERR);
            return -2;
        }

        return $this->id;
    }

    /**
     *  Create a user from a contact object. User will be internal but if contact is linked to a third party, user will be external
     *
     *  @param	Contact	$contact    Object for source contact
     * 	@param  string	$login      Login to force
     *  @param  string	$password   Password to force
     *  @return int 				<0 if error, if OK returns id of created user
     */
    function create_from_contact($contact, $login = '', $password = '') {
        global $conf, $user, $langs;

        $error = 0;

        // Positionne parametres
        $this->admin = 0;
        $this->nom = $contact->nom;   // TODO deprecated
        $this->prenom = $contact->prenom; // TODO deprecated
        $this->lastname = $contact->nom;
        $this->firstname = $contact->prenom;
        $this->email = $contact->email;
        $this->office_phone = $contact->phone_pro;
        $this->office_fax = $contact->fax;
        $this->user_mobile = $contact->phone_mobile;

        if (empty($login))
            $login = strtolower(substr($contact->prenom, 0, 4)) . strtolower(substr($contact->nom, 0, 4));
        $this->login = $login;

        $this->db->begin();

        // Cree et positionne $this->id
        $result = $this->create($user);
        if ($result > 0) {
            $sql = "UPDATE " . MAIN_DB_PREFIX . "user";
            $sql.= " SET fk_socpeople=" . $contact->id;
            if ($contact->socid)
                $sql.=", fk_societe=" . $contact->socid;
            $sql.= " WHERE rowid=" . $this->id;
            $resql = $this->db->query($sql);

            dol_syslog(get_class($this) . "::create_from_contact sql=" . $sql, LOG_DEBUG);
            if ($resql) {
                // Appel des triggers
                include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
                $interface = new Interfaces($this->db);
                $result = $interface->run_triggers('USER_CREATE_FROM_CONTACT', $this, $user, $langs, $conf);
                if ($result < 0) {
                    $error++;
                    $this->errors = $interface->errors;
                }
                // Fin appel triggers

                $this->db->commit();
                return $this->id;
            } else {
                $this->error = $this->db->error();
                dol_syslog(get_class($this) . "::create_from_contact " . $this->error, LOG_ERR);

                $this->db->rollback();
                return -1;
            }
        } else {
            // $this->error deja positionne
            dol_syslog(get_class($this) . "::create_from_contact - 0");

            $this->db->rollback();
            return $result;
        }
    }

    /**
     *  Create a user into database from a member object
     *
     *  @param	Adherent	$member		Object member source
     * 	@param	string		$login		Login to force
     *  @return int						<0 if KO, if OK, return id of created account
     */
    function create_from_member($member, $login = '') {
        global $conf, $user, $langs;

        // Positionne parametres
        $this->admin = 0;
        $this->lastname = $member->lastname;
        $this->firstname = $member->firstname;
        $this->email = $member->email;
        $this->pass = $member->pass;

        if (empty($login))
            $login = strtolower(substr($member->firstname, 0, 4)) . strtolower(substr($member->lastname, 0, 4));
        $this->login = $login;

        $this->db->begin();

        // Cree et positionne $this->id
        $result = $this->create($user);
        if ($result > 0) {
            $result = $this->setPassword($user, $this->pass);

            $sql = "UPDATE " . MAIN_DB_PREFIX . "user";
            $sql.= " SET fk_member=" . $member->id;
            if ($member->fk_soc)
                $sql.= ", fk_societe=" . $member->fk_soc;
            $sql.= " WHERE rowid=" . $this->id;

            dol_syslog(get_class($this) . "::create_from_member sql=" . $sql, LOG_DEBUG);
            $resql = $this->db->query($sql);
            if ($resql) {
                $this->db->commit();
                return $this->id;
            } else {
                $this->error = $this->db->error();
                dol_syslog(get_class($this) . "::create_from_member - 1 - " . $this->error, LOG_ERR);

                $this->db->rollback();
                return -1;
            }
        } else {
            // $this->error deja positionne
            dol_syslog(get_class($this) . "::create_from_member - 2 - " . $this->error, LOG_ERR);

            $this->db->rollback();
            return $result;
        }
    }

    /**
     *    Affectation des permissions par defaut
     *
     *    @return     Si erreur <0, si ok renvoi le nbre de droits par defaut positionnes
     */
    function set_default_rights() {
        global $conf;

        $sql = "SELECT id FROM " . MAIN_DB_PREFIX . "rights_def";
        $sql.= " WHERE bydefault = 1";
        $sql.= " AND entity = " . $conf->entity;

        $resql = $this->db->query($sql);
        if ($resql) {
            $num = $this->db->num_rows($resql);
            $i = 0;
            $rd = array();
            while ($i < $num) {
                $row = $this->db->fetch_row($resql);
                $rd[$i] = $row[0];
                $i++;
            }
            $this->db->free($resql);
        }
        $i = 0;
        while ($i < $num) {

            $sql = "DELETE FROM " . MAIN_DB_PREFIX . "user_rights WHERE fk_user = $this->id AND fk_id=$rd[$i]";
            $result = $this->db->query($sql);

            $sql = "INSERT INTO " . MAIN_DB_PREFIX . "user_rights (fk_user, fk_id) VALUES ($this->id, $rd[$i])";
            $result = $this->db->query($sql);
            if (!$result)
                return -1;
            $i++;
        }

        return $i;
    }

    /**
     *    Mise e jour en base de la date de deniere connexion d'un utilisateur
     * 	  Fonction appelee lors d'une nouvelle connexion
     *
     *    @return     <0 si echec, >=0 si ok
     */
    function update_last_login_date() {
        $now = dol_now();

        $this->LastConnection = $this->NewConnection;
        $this->NewConnection = $now;
        $this->record(true);
    }

    /**
     *  Change password of a user
     *
     *  @param	User	$user             		Object user of user making change
     *  @param  string	$password         		New password in clear text (to generate if not provided)
     * 	@param	int		$changelater			1=Change password only after clicking on confirm email
     * 	@param	int		$notrigger				1=Does not launch triggers
     * 	@param	int		$nosyncmember	        Do not synchronize linked member
     *  @return string 			          		If OK return clear password, 0 if no change, < 0 if error
     */
    function setPassword($user, $password = '', $changelater = 0, $notrigger = 0, $nosyncmember = 0) {
        global $conf, $langs;
        require_once(DOL_DOCUMENT_ROOT . "/core/lib/security2.lib.php");

        $error = 0;

        dol_syslog(get_class($this) . "::setPassword user=" . $user->id . " password=" . preg_replace('/./i', '*', $password) . " changelater=" . $changelater . " notrigger=" . $notrigger . " nosyncmember=" . $nosyncmember, LOG_DEBUG);

        // If new password not provided, we generate one
        if (!$password) {
            $password = getRandomPassword('');
        }

        // Crypte avec md5
        $password_crypted = dol_hash($password);

        // Mise a jour
        if (!$changelater) {
            if (!is_object($this->oldcopy))
                $this->oldcopy = dol_clone($this);

            $sql = "UPDATE " . MAIN_DB_PREFIX . "user";
            $sql.= " SET pass_crypted = '" . $this->db->escape($password_crypted) . "',";
            $sql.= " pass_temp = null";
            if (!empty($conf->global->DATABASE_PWD_ENCRYPTED)) {
                $sql.= ", pass = null";
            } else {
                $sql.= ", pass = '" . $this->db->escape($password) . "'";
            }
            $sql.= " WHERE rowid = " . $this->id;

            dol_syslog(get_class($this) . "::setPassword sql=hidden", LOG_DEBUG);
            $result = $this->db->query($sql);
            if ($result) {
                if ($this->db->affected_rows($result)) {
                    $this->pass = $password;
                    $this->pass_indatabase = $password;
                    $this->pass_indatabase_crypted = $password_crypted;

                    if ($this->fk_member && !$nosyncmember) {
                        require_once(DOL_DOCUMENT_ROOT . "/adherents/class/adherent.class.php");

                        // This user is linked with a member, so we also update members informations
                        // if this is an update.
                        $adh = new Adherent($this->db);
                        $result = $adh->fetch($this->fk_member);

                        if ($result >= 0) {
                            $result = $adh->setPassword($user, $this->pass, 0, 1); // Cryptage non gere dans module adherent
                            if ($result < 0) {
                                $this->error = $adh->error;
                                dol_syslog(get_class($this) . "::setPassword " . $this->error, LOG_ERR);
                                $error++;
                            }
                        } else {
                            $this->error = $adh->error;
                            $error++;
                        }
                    }

                    dol_syslog(get_class($this) . "::setPassword notrigger=" . $notrigger . " error=" . $error, LOG_DEBUG);

                    if (!$error && !$notrigger) {
                        // Appel des triggers
                        include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
                        $interface = new Interfaces($this->db);
                        $result = $interface->run_triggers('USER_NEW_PASSWORD', $this, $user, $langs, $conf);
                        if ($result < 0)
                            $this->errors = $interface->errors;
                        // Fin appel triggers
                    }

                    return $this->pass;
                }
                else {
                    return 0;
                }
            } else {
                dol_print_error($this->db);
                return -1;
            }
        } else {
            // We store clear password in password temporary field.
            // After receiving confirmation link, we will crypt it and store it in pass_crypted
            $sql = "UPDATE " . MAIN_DB_PREFIX . "user";
            $sql.= " SET pass_temp = '" . $this->db->escape($password) . "'";
            $sql.= " WHERE rowid = " . $this->id;

            dol_syslog(get_class($this) . "::setPassword sql=hidden", LOG_DEBUG); // No log
            $result = $this->db->query($sql);
            if ($result) {
                return $password;
            } else {
                dol_print_error($this->db);
                return -3;
            }
        }
    }

    /**
     *  Envoie mot de passe par mail
     *
     *  @param	User	$user           Object user de l'utilisateur qui fait l'envoi
     *  @param	string	$password       Nouveau mot de passe
     * 	@param	int		$changelater	1=Change password only after clicking on confirm email
     *  @return int 		            < 0 si erreur, > 0 si ok
     */
    function send_password($user, $password = '', $changelater = 0) {
        global $conf, $langs;
        global $dolibarr_main_url_root;

        require_once DOL_DOCUMENT_ROOT . "/core/class/CMailFile.class.php";

        $subject = $langs->trans("SubjectNewPassword");
        $msgishtml = 0;

        // Define $msg
        $mesg = '';

        $outputlangs = new Translate("", $conf);
        if (isset($this->conf->MAIN_LANG_DEFAULT)
                && $this->conf->MAIN_LANG_DEFAULT != 'auto') { // If user has defined its own language (rare because in most cases, auto is used)
            $outputlangs->getDefaultLang($this->conf->MAIN_LANG_DEFAULT);
        } else { // If user has not defined its own language, we used current language
            $outputlangs = $langs;
        }

        // Define urlwithouturlroot
        if (!empty($_SERVER["HTTP_HOST"])) { // Autodetect main url root
            $urlwithouturlroot = 'http://' . preg_replace('/' . preg_quote(DOL_URL_ROOT, '/') . '$/i', '', $_SERVER["HTTP_HOST"]);
        } else {
            $urlwithouturlroot = preg_replace('/' . preg_quote(DOL_URL_ROOT, '/') . '$/i', '', $dolibarr_main_url_root);
        }
        if (!empty($dolibarr_main_force_https))
            $urlwithouturlroot = preg_replace('/http:/i', 'https:', $urlwithouturlroot);

        // TODO Use outputlangs to translate messages
        if (!$changelater) {
            $mesg.= "A request to change your Dolibarr password has been received.\n";
            $mesg.= "This is your new keys to login:\n\n";
            $mesg.= $langs->trans("Login") . " : $this->login\n";
            $mesg.= $langs->trans("Password") . " : $password\n\n";
            $mesg.= "\n";
            $url = $urlwithouturlroot . DOL_URL_ROOT;
            $mesg.= 'Click here to go to Dolibarr: ' . $url . "\n\n";
            $mesg.= "--\n";
            $mesg.= $user->getFullName($langs); // Username that make then sending
        } else {
            $mesg.= "A request to change your Dolibarr password has been received.\n";
            $mesg.= "Your new key to login will be:\n\n";
            $mesg.= $langs->trans("Login") . " : $this->login\n";
            $mesg.= $langs->trans("Password") . " : $password\n\n";
            $mesg.= "\n";
            $mesg.= "You must click on the folowing link to validate its change.\n";
            $url = $urlwithouturlroot . DOL_URL_ROOT . '/user/passwordforgotten.php?action=validatenewpassword&username=' . $this->login . "&passwordmd5=" . dol_hash($password);
            $mesg.= $url . "\n\n";
            $mesg.= "If you didn't ask anything, just forget this email\n\n";
            dol_syslog(get_class($this) . "::send_password url=" . $url);
        }
        $mailfile = new CMailFile(
                        $subject,
                        $this->email,
                        $conf->notification->email_from,
                        $mesg,
                        array(),
                        array(),
                        array(),
                        '',
                        '',
                        0,
                        $msgishtml
        );

        if ($mailfile->sendfile()) {
            return 1;
        } else {
            $langs->trans("errors");
            $this->error = $langs->trans("ErrorFailedToSendPassword") . ' ' . $mailfile->error;
            return -1;
        }
    }

    /**
     * 		Renvoie la derniere erreur fonctionnelle de manipulation de l'objet
     *
     * 		@return    string      chaine erreur
     */
    function error() {
        return $this->error;
    }

    /**
     *    	Read clicktodial information for user
     *
     * 		@return		<0 if KO, >0 if OK
     */
    function fetch_clicktodial() {
        $sql = "SELECT login, pass, poste ";
        $sql.= " FROM " . MAIN_DB_PREFIX . "user_clicktodial as u";
        $sql.= " WHERE u.fk_user = " . $this->id;

        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql)) {
                $obj = $this->db->fetch_object($resql);

                $this->clicktodial_login = $obj->login;
                $this->clicktodial_password = $obj->pass;
                $this->clicktodial_poste = $obj->poste;
            }

            $this->clicktodial_loaded = 1; // Data loaded (found or not)

            $this->db->free($resql);
            return 1;
        } else {
            $this->error = $this->db->error();
            return -1;
        }
    }

    /**
     *  Update clicktodial info
     *
     *  @return	void
     */
    function update_clicktodial() {
        $this->db->begin();

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "user_clicktodial";
        $sql .= " WHERE fk_user = " . $this->id;

        $result = $this->db->query($sql);

        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "user_clicktodial";
        $sql .= " (fk_user,login,pass,poste)";
        $sql .= " VALUES (" . $this->id;
        $sql .= ", '" . $this->clicktodial_login . "'";
        $sql .= ", '" . $this->clicktodial_password . "'";
        $sql .= ", '" . $this->clicktodial_poste . "')";

        $result = $this->db->query($sql);

        if ($result) {
            $this->db->commit();
            return 0;
        } else {
            $this->db->rollback();
            $this->error = $this->db->error();
            return -1;
        }
    }

    /**
     *  Add user into a group
     *
     *  @param	Group	$group      Id of group
     *  @param  int		$entity     Entity
     *  @param  int		$notrigger  Disable triggers
     *  @return int  				<0 if KO, >0 if OK
     */
    function SetInGroup($group, $entity, $notrigger = 0) {
        global $conf, $langs, $user;

        $error = 0;

        $this->db->begin();

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "usergroup_user";
        $sql.= " WHERE fk_user  = " . $this->id;
        $sql.= " AND fk_usergroup = " . $group;
        $sql.= " AND entity = " . $entity;

        $result = $this->db->query($sql);

        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "usergroup_user (entity, fk_user, fk_usergroup)";
        $sql.= " VALUES (" . $entity . "," . $this->id . "," . $group . ")";

        $result = $this->db->query($sql);
        if ($result) {
            if (!$error && !$notrigger) {
                $this->newgroupid = $group;

                // Appel des triggers
                include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
                $interface = new Interfaces($this->db);
                $result = $interface->run_triggers('USER_SETINGROUP', $this, $user, $langs, $conf);
                if ($result < 0) {
                    $error++;
                    $this->errors = $interface->errors;
                }
                // Fin appel triggers
            }

            if (!$error) {
                $this->db->commit();
                return 1;
            } else {
                $this->error = $interface->error;
                dol_syslog(get_class($this) . "::SetInGroup " . $this->error, LOG_ERR);
                $this->db->rollback();
                return -2;
            }
        } else {
            $this->error = $this->db->lasterror();
            dol_syslog(get_class($this) . "::SetInGroup " . $this->error, LOG_ERR);
            $this->db->rollback();
            return -1;
        }
    }

    /**
     *  Remove a user from a group
     *
     *  @param	Group   $group       Id of group
     *  @param  int		$entity      Entity
     *  @param  int		$notrigger   Disable triggers
     *  @return int  			     <0 if KO, >0 if OK
     */
    function RemoveFromGroup($group, $entity, $notrigger = 0) {
        global $conf, $langs, $user;

        $error = 0;

        $this->db->begin();

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "usergroup_user";
        $sql.= " WHERE fk_user  = " . $this->id;
        $sql.= " AND fk_usergroup = " . $group;
        $sql.= " AND entity = " . $entity;

        $result = $this->db->query($sql);
        if ($result) {
            if (!$error && !$notrigger) {
                $this->oldgroupid = $group;

                // Appel des triggers
                include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
                $interface = new Interfaces($this->db);
                $result = $interface->run_triggers('USER_REMOVEFROMGROUP', $this, $user, $langs, $conf);
                if ($result < 0) {
                    $error++;
                    $this->errors = $interface->errors;
                }
                // Fin appel triggers
            }

            if (!$error) {
                $this->db->commit();
                return 1;
            } else {
                $this->error = $interface->error;
                dol_syslog(get_class($this) . "::RemoveFromGroup " . $this->error, LOG_ERR);
                $this->db->rollback();
                return -2;
            }
        } else {
            $this->error = $this->db->lasterror();
            dol_syslog(get_class($this) . "::RemoveFromGroup " . $this->error, LOG_ERR);
            $this->db->rollback();
            return -1;
        }
    }

    /**
     *  Return a link to the user card (with optionnaly the picto)
     * 	Use this->id,this->nom, this->prenom
     *
     * 	@param	int		$withpicto		Include picto in link (0=No picto, 1=Inclut le picto dans le lien, 2=Picto seul)
     * 	@param	string	$option			On what the link point to
     * 	@return	string					String with URL
     */
    function getNomUrl($withpicto = 0, $option = '') {
        global $langs;

        $result = '';

        $lien = '<a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $this->id . '">';
        $lienfin = '</a>';

        if ($option == 'xxx') {
            $lien = '<a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $this->id . '">';
            $lienfin = '</a>';
        }

        if ($withpicto)
            $result.=($lien . img_object($langs->trans("ShowUser"), 'user') . $lienfin);
        if ($withpicto && $withpicto != 2)
            $result.=' ';
        $result.=$lien . $this->getFullName($langs) . $lienfin;
        return $result;
    }

    /**
     *  Renvoie login clicable (avec eventuellement le picto)
     *
     * 	@param	int		$withpicto		Inclut le picto dans le lien
     * 	@param	string	$option			Sur quoi pointe le lien
     * 	@return	string					Chaine avec URL
     */
    function getLoginUrl($withpicto = 0, $option = '') {
        global $langs;

        $result = '';

        $lien = '<a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $this->id . '">';
        $lienfin = '</a>';

        if ($option == 'xxx') {
            $lien = '<a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $this->id . '">';
            $lienfin = '</a>';
        }

        if ($withpicto)
            $result.=($lien . img_object($langs->trans("ShowUser"), 'user') . $lienfin . ' ');
        $result.=$lien . $this->login . $lienfin;
        return $result;
    }

    /**
     * 	Retourne chaine DN complete dans l'annuaire LDAP pour l'objet
     *
     * 	@param	string	$info		Info string loaded by _load_ldap_info
     * 	@param	int		$mode		0=Return full DN (uid=qqq,ou=xxx,dc=aaa,dc=bbb)
     * 								1=
     * 								2=Return key only (uid=qqq)
     * 	@return	string				DN
     */
    function _load_ldap_dn($info, $mode = 0) {
        global $conf;
        $dn = '';
        if ($mode == 0)
            $dn = $conf->global->LDAP_KEY_USERS . "=" . $info[$conf->global->LDAP_KEY_USERS] . "," . $conf->global->LDAP_USER_DN;
        if ($mode == 1)
            $dn = $conf->global->LDAP_USER_DN;
        if ($mode == 2)
            $dn = $conf->global->LDAP_KEY_USERS . "=" . $info[$conf->global->LDAP_KEY_USERS];
        return $dn;
    }

    /**
     * 	Initialize the info array (array of LDAP values) that will be used to call LDAP functions
     *
     * 	@return		array		Tableau info des attributs
     */
    function _load_ldap_info() {
        global $conf, $langs;

        $info = array();

        // Object classes
        $info["objectclass"] = explode(',', $conf->global->LDAP_USER_OBJECT_CLASS);

        $this->fullname = $this->getFullName($langs);

        // Champs
        if ($this->fullname && $conf->global->LDAP_FIELD_FULLNAME)
            $info[$conf->global->LDAP_FIELD_FULLNAME] = $this->fullname;
        if ($this->lastname && $conf->global->LDAP_FIELD_NAME)
            $info[$conf->global->LDAP_FIELD_NAME] = $this->lastname;
        if ($this->firstname && $conf->global->LDAP_FIELD_FIRSTNAME)
            $info[$conf->global->LDAP_FIELD_FIRSTNAME] = $this->firstname;
        if ($this->login && $conf->global->LDAP_FIELD_LOGIN)
            $info[$conf->global->LDAP_FIELD_LOGIN] = $this->login;
        if ($this->login && $conf->global->LDAP_FIELD_LOGIN_SAMBA)
            $info[$conf->global->LDAP_FIELD_LOGIN_SAMBA] = $this->login;
        if ($this->pass && $conf->global->LDAP_FIELD_PASSWORD)
            $info[$conf->global->LDAP_FIELD_PASSWORD] = $this->pass; // this->pass = mot de passe non crypte
        if ($this->ldap_sid && $conf->global->LDAP_FIELD_SID)
            $info[$conf->global->LDAP_FIELD_SID] = $this->ldap_sid;
        if ($this->societe_id > 0) {
            $soc = new Societe($this->db);
            $soc->fetch($this->societe_id);

            $info["o"] = $soc->nom;
            if ($soc->client == 1)
                $info["businessCategory"] = "Customers";
            if ($soc->client == 2)
                $info["businessCategory"] = "Prospects";
            if ($soc->fournisseur == 1)
                $info["businessCategory"] = "Suppliers";
        }
        if ($this->address && $conf->global->LDAP_FIELD_ADDRESS)
            $info[$conf->global->LDAP_FIELD_ADDRESS] = $this->address;
        if ($this->zip && $conf->global->LDAP_FIELD_ZIP)
            $info[$conf->global->LDAP_FIELD_ZIP] = $this->zip;
        if ($this->town && $conf->global->LDAP_FIELD_TOWN)
            $info[$conf->global->LDAP_FIELD_TOWN] = $this->town;
        if ($this->office_phone && $conf->global->LDAP_FIELD_PHONE)
            $info[$conf->global->LDAP_FIELD_PHONE] = $this->office_phone;
        if ($this->user_mobile && $conf->global->LDAP_FIELD_MOBILE)
            $info[$conf->global->LDAP_FIELD_MOBILE] = $this->user_mobile;
        if ($this->office_fax && $conf->global->LDAP_FIELD_FAX)
            $info[$conf->global->LDAP_FIELD_FAX] = $this->office_fax;
        if ($this->note && $conf->global->LDAP_FIELD_DESCRIPTION)
            $info[$conf->global->LDAP_FIELD_DESCRIPTION] = $this->note;
        if ($this->email && $conf->global->LDAP_FIELD_MAIL)
            $info[$conf->global->LDAP_FIELD_MAIL] = $this->email;

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
     *  Initialise an instance with random values.
     *  Used to build previews or test instances.
     * 	id must be 0 if object instance is a specimen.
     *
     *  @return	void
     */
    function initAsSpecimen() {
        global $user, $langs;

        // Initialise parametres
        $this->id = 0;
        $this->ref = 'SPECIMEN';
        $this->specimen = 1;

        $this->nom = 'DOLIBARR';  // deprecated
        $this->prenom = 'SPECIMEN';  // deprecated
        $this->lastname = 'DOLIBARR';
        $this->firstname = 'SPECIMEN';
        $this->note = 'This is a note';
        $this->email = 'email@specimen.com';
        $this->office_phone = '0999999999';
        $this->office_fax = '0999999998';
        $this->user_mobile = '0999999997';
        $this->admin = 0;
        $this->login = 'dolibspec';
        $this->pass = 'dolibspec';
        $this->datec = time();
        $this->datem = time();
        $this->webcal_login = 'dolibspec';

        $this->datelastlogin = time();
        $this->datepreviouslogin = time();
        $this->statut = 1;

        $this->societe_id = 1;
    }

    /**
     *  Load info of user object
     *
     *  @param  int		$id     Id of user to load
     *  @return	void
     */
    function info($id) {
        $sql = "SELECT u.rowid, u.login as ref, u.datec,";
        $sql.= " u.tms as date_modification, u.entity";
        $sql.= " FROM " . MAIN_DB_PREFIX . "user as u";
        $sql.= " WHERE u.rowid = " . $id;

        $result = $this->db->query($sql);
        if ($result) {
            if ($this->db->num_rows($result)) {
                $obj = $this->db->fetch_object($result);

                $this->id = $obj->rowid;

                $this->ref = (!$obj->ref) ? $obj->rowid : $obj->ref;
                $this->date_creation = $this->db->jdate($obj->datec);
                $this->date_modification = $this->db->jdate($obj->date_modification);
                $this->entity = $obj->entity;
            }

            $this->db->free($result);
        } else {
            dol_print_error($this->db);
        }
    }

    /**
     *    Return number of mass Emailing received by this contacts with its email
     *
     *    @return       int     Number of EMailings
     */
    function getNbOfEMailings() {
        $sql = "SELECT count(mc.email) as nb";
        $sql.= " FROM " . MAIN_DB_PREFIX . "mailing_cibles as mc";
        $sql.= " WHERE mc.email = '" . $this->db->escape($this->email) . "'";
        $sql.= " AND mc.statut=1";   // -1 erreur, 0 non envoye, 1 envoye avec succes
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
     *  Return number of existing users
     *
     *  @param	string	$limitTo	Limit to 'active' or 'superadmin' users
     *  @param	int		$all		Return for all entities
     *  @return int  				Number of users
     */
    function getNbOfUsers($limitTo = '') {
        global $conf;

        try {
            $result = $this->couchAdmin->getAllUsers();
        } catch (Exception $e) {
            return 0;
        }

        return count($result);
    }

    function getAllUsers($include_docs) {
        return $this->getView('list');
    }

    function getUserAdmins() {
        return $this->couchAdmin->getUserAdmins();
    }

    function getDatabaseAdminUsers() {
        return $this->couchAdmin->getDatabaseAdminUsers();
    }

    function getDatabaseReaderUsers() {
        return $this->couchAdmin->getDatabaseReaderUsers();
    }

    function getLibStatus() {
        return $this->LibStatus($this->Status);
    }

    /**
     *    Return label of status (activity, closed)
     *
     *    @return   string        		Libelle
     */
    function LibStatus($status) {
        global $langs;

        $admins = $this->getDatabaseAdminUsers();
        $enabled = $this->getDatabaseReaderUsers();

        //print_r($enabled);
        //print_r($admins);

        $name = $this->email;
        if (in_array($name, $admins)) // Is Localadministrator
            $this->admin = true;
        else
            $this->admin = false;

        if (in_array($name, $enabled)) // Is Status = ENABLE
            $status = "ENABLE";
        else {
            if ($this->admin)
                $status = "ENABLE";
            else
                $status = "DISABLE";
        }

        $this->Status = $status;

        if ($this->admin)
            $out = img_picto($langs->trans("Administrator"), 'star');

        return parent::LibStatus($status) . " " . $out;
    }

}

?>
