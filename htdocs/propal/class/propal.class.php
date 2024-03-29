<?php

/* Copyright (C) 2002-2004 Rodolphe Quiedeville		<rodolphe@quiedeville.org>
 * Copyright (C) 2004      Eric Seigne				<eric.seigne@ryxeo.com>
 * Copyright (C) 2004-2011 Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005      Marc Barilley			<marc@ocebo.com>
 * Copyright (C) 2005-2012 Regis Houssin			<regis.houssin@capnetworks.com>
 * Copyright (C) 2006      Andre Cianfarani			<acianfa@free.fr>
 * Copyright (C) 2008      Raphael Bertrand			<raphael.bertrand@resultic.fr>
 * Copyright (C) 2010-2012 Juanjo Menent			<jmenent@2byte.es>
 * Copyright (C) 2010-2011 Philippe Grand			<philippe.grand@atoo-net.com>
 * Copyright (C) 2012      Christophe Battarel  <christophe.battarel@altairis.fr>
 * Copyright (C) 2012      David Moothen  <dmoothen@websitti.fr>
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
 * 	\file       htdocs/comm/propal/class/propal.class.php
 * 	\brief      Fichier de la classe des propales
 */
require_once DOL_DOCUMENT_ROOT . '/core/class/abstractinvoice.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT . '/contact/class/contact.class.php';
require_once DOL_DOCUMENT_ROOT . '/margin/lib/margins.lib.php';

/**
 * 	\class      Propal
 * 	\brief      Classe permettant la gestion des propales
 */
class Propal extends AbstractInvoice {

    public $element = 'propal';
    public $table_element = 'propal';
//    public $table_element_line = 'propaldet';
//    public $fk_element = 'fk_propal';
    protected $ismultientitymanaged = 1; // 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
    var $id;
    var $client;  // Objet societe client (a charger par fetch_client)
    var $contactid;
    var $fk_project;
    var $author;
    var $ref;
    var $ref_client;
    //var $statut;     // 0 (draft), 1 (validated), 2 (signed), 3 (not signed), 4 (billed)
    public $Status;
    var $datec;      // Date of creation
    var $datev;      // Date of validation
    var $date;      // Date of proposal
    var $datep;      // Same than date
    var $date_livraison;
    public $duree_validite;
    var $fin_validite;
    var $user_author_id;
    var $user_valid_id;
    var $user_close_id;
    var $total_ht;     // Total net of tax
    var $total_tva;     // Total VAT
    var $total_localtax1;   // Total Local Taxes 1
    var $total_localtax2;   // Total Local Taxes 2
    var $total_ttc;     // Total with tax
    var $price;      // deprecated (for compatibility)
    var $tva;      // deprecated (for compatibility)
    var $total;      // deprecated (for compatibility)
    var $cond_reglement_code;
    var $mode_reglement_code;
    var $remise;
    var $remise_percent;
    var $remise_absolue;
    var $note;      // deprecated (for compatibility)
    var $note_private;
    var $note_public;
    var $fk_delivery_address;  // deprecated (for compatibility)
    var $fk_address;
    var $address_type;
    var $adresse;
    var $availability_code;
    var $demand_reason_code;
    var $products = array();
    var $extraparams = array();
    var $lines = array();
    var $line;
    var $origin;
    var $origin_id;
    var $labelstatut = array();
    var $labelstatut_short = array();
    // Pour board
    var $nbtodo;
    var $nbtodolate;
    var $specimen;

    /**
     * 	Constructor
     *
     * 	@param      DoliDB	$db         Database handler
     */
	function __construct($db) {
		parent::__construct($db);

		$this->no_save[] = 'thirdparty';
		$this->no_save[] = 'line';

		$this->fk_extrafields = new ExtraFields($db);
		$this->fk_extrafields->fetch(get_class($this));

		$this->remise = 0;
		$this->remise_percent = 0;

		$this->products = array();
	}

    /**
     * 	Add line into array products
     * 	$this->client doit etre charge
     *    public $modelpdf = 'marbre';

     * 	@param  int		$idproduct       	Product Id to add
     * 	@param  int		$qty             	Quantity
     * 	@param  int		$remise_percent  	Discount effected on Product
     *  @return	int							<0 if KO, >0 if OK
     *
     * 	TODO	Remplacer les appels a cette fonction par generation objet Ligne
     * 			insere dans tableau $this->products
     */
    function add_product($idproduct, $qty, $remise_percent = 0) {
        global $conf, $mysoc;

        if (!$qty)
            $qty = 1;

        dol_syslog(get_class($this) . "::add_product $idproduct, $qty, $remise_percent");
        if ($idproduct > 0) {
            $prod = new Product($this->db);
            $prod->fetch($idproduct);

            $productdesc = $prod->description;

            $tva_tx = get_default_tva($mysoc, $this->client, $prod->id);
            // local taxes
            $localtax1_tx = get_default_localtax($mysoc, $this->client, 1, $prod->tva_tx);
            $localtax2_tx = get_default_localtax($mysoc, $this->client, 2, $prod->tva_tx);

            // multiprix
            if ($conf->global->PRODUIT_MULTIPRICES && $this->client->price_level) {
                $price = $prod->multiprices[$this->client->price_level];
            } else {
                $price = $prod->price;
            }

            $line = new PropalLine($this->db);

            $line->fk_product = $idproduct;
            $line->desc = $productdesc;
            $line->qty = $qty;
            $line->subprice = $price;
            $line->remise_percent = $remise_percent;
            $line->tva_tx = $tva_tx;

            $this->products[] = $line;
        }
    }

    /**
     * 	Adding line of fixed discount in the proposal in DB
     *
     * 	@param     int		$idremise			Id of fixed discount
     *  @return    int          				>0 if OK, <0 if KO
     */
    function insert_discount($idremise) {
        global $langs;

        include_once DOL_DOCUMENT_ROOT . '/core/lib/price.lib.php';
        include_once DOL_DOCUMENT_ROOT . '/core/class/discount.class.php';

        $this->db->begin();

        $remise = new DiscountAbsolute($this->db);
        $result = $remise->fetch($idremise);

        if ($result > 0) {
            if ($remise->fk_facture) { // Protection against multiple submission
                $this->error = $langs->trans("ErrorDiscountAlreadyUsed");
                $this->db->rollback();
                return -5;
            }

            $propalligne = new PropalLine($this->db);
            $propalligne->fk_propal = $this->id;
            $propalligne->fk_remise_except = $remise->id;
            $propalligne->desc = $remise->description;    // Description ligne
            $propalligne->tva_tx = $remise->tva_tx;
            $propalligne->subprice = -$remise->amount_ht;
            $propalligne->fk_product = 0;     // Id produit predefini
            $propalligne->qty = 1;
            $propalligne->remise = 0;
            $propalligne->remise_percent = 0;
            $propalligne->rang = -1;
            $propalligne->info_bits = 2;

            // TODO deprecated
            $propalligne->price = -$remise->amount_ht;

            $propalligne->total_ht = -$remise->amount_ht;
            $propalligne->total_tva = -$remise->amount_tva;
            $propalligne->total_ttc = -$remise->amount_ttc;

            $result = $propalligne->insert();
            if ($result > 0) {
                $result = $this->update_price(1);
                if ($result > 0) {
                    $this->db->commit();
                    return 1;
                } else {
                    $this->db->rollback();
                    return -1;
                }
            } else {
                $this->error = $propalligne->error;
                $this->db->rollback();
                return -2;
            }
        } else {
            $this->db->rollback();
            return -2;
        }
    }
    
    /**
     *  Create commercial proposal into database
     * 	this->ref can be set or empty. If empty, we will use "(PROVid)"
     *
     * 	@param		User	$user		User that create
     * 	@param		int		$notrigger	1=Does not execute triggers, 0= execuete triggers
     *  @return     int     			<0 if KO, >=0 if OK
     */
    function create($user = '', $notrigger = 0) {
        global $langs, $conf, $mysoc, $user;
        $error = 0;

        $now = dol_now();

        // Clean parameters
        if (empty($this->date))
            $this->date = $this->datep;
        $this->fin_validite = $this->date + ($this->duree_validite * 24 * 3600);

        dol_syslog(get_class($this) . "::create");

        // Check parameters
        $soc = new Societe($this->db);
        $result = $soc->fetch($this->socid);
        if ($result < 0) {
            $this->error = "Failed to fetch company";
            dol_syslog(get_class($this) . "::create " . $this->error, LOG_ERR);
            return -3;
        }
        $this->client = new stdClass();
        $this->client->id = $soc->id;
        $this->client->name = $soc->name;

        // Author
        $this->author = new stdClass();
        $this->author->id = $user->id;
        $this->author->name = $user->login;

        if (empty($this->date)) {
            $this->error = "Date of proposal is required";
            dol_syslog(get_class($this) . "::create " . $this->error, LOG_ERR);
            return -4;
        }
        $this->ref = $this->getNextNumRef($soc);


//        $this->db->begin();
//
//        $this->fetch_thirdparty();
//
//        // Insert into database
//        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "propal (";
//        $sql.= "fk_soc";
//        $sql.= ", price";
//        $sql.= ", remise";
//        $sql.= ", remise_percent";
//        $sql.= ", remise_absolue";
//        $sql.= ", tva";
//        $sql.= ", total";
//        $sql.= ", datep";
//        $sql.= ", datec";
//        $sql.= ", ref";
//        $sql.= ", fk_user_author";
//        $sql.= ", note";
//        $sql.= ", note_public";
//        $sql.= ", model_pdf";
//        $sql.= ", fin_validite";
//        $sql.= ", fk_cond_reglement";
//        $sql.= ", fk_mode_reglement";
//        $sql.= ", ref_client";
//        $sql.= ", date_livraison";
//        $sql.= ", fk_availability";
//        $sql.= ", fk_input_reason";
//        $sql.= ", fk_projet";
//        $sql.= ", entity";
//        $sql.= ") ";
//        $sql.= " VALUES (";
//        $sql.= $this->socid;
//        $sql.= ", 0";
//        $sql.= ", " . $this->remise;
//        $sql.= ", " . ($this->remise_percent ? $this->remise_percent : 'null');
//        $sql.= ", " . ($this->remise_absolue ? $this->remise_absolue : 'null');
//        $sql.= ", 0";
//        $sql.= ", 0";
//        $sql.= ", '" . $this->db->idate($this->date) . "'";
//        $sql.= ", '" . $this->db->idate($now) . "'";
//        $sql.= ", '(PROV)'";
//        $sql.= ", " . ($user->id > 0 ? "'" . $user->id . "'" : "null");
//        $sql.= ", '" . $this->db->escape($this->note) . "'";
//        $sql.= ", '" . $this->db->escape($this->note_public) . "'";
//        $sql.= ", '" . $this->modelpdf . "'";
//        $sql.= ", " . ($this->fin_validite != '' ? "'" . $this->db->idate($this->fin_validite) . "'" : "null");
//        $sql.= ", " . $this->cond_reglement_id;
//        $sql.= ", " . $this->mode_reglement_id;
//        $sql.= ", '" . $this->db->escape($this->ref_client) . "'";
//        $sql.= ", " . ($this->date_livraison != '' ? "'" . $this->db->idate($this->date_livraison) . "'" : "null");
//        $sql.= ", " . $this->availability_id;
//        $sql.= ", " . $this->demand_reason_id;
//        $sql.= ", " . ($this->fk_project ? $this->fk_project : "null");
//        $sql.= ", " . $conf->entity;
//        $sql.= ")";
//
//        dol_syslog(get_class($this) . "::create sql=" . $sql, LOG_DEBUG);
//        $resql = $this->db->query($sql);
//        if ($resql) {
//            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "propal");
        $this->record();
        if (!$notrigger) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('PROPAL_CREATE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }
        return $this->id;
//        if ($this->id) {
//                if (empty($this->ref))
//                    $this->ref = '(PROV' . $this->id . ')';
//                $sql = 'UPDATE ' . MAIN_DB_PREFIX . "propal SET ref='" . $this->ref . "' WHERE rowid=" . $this->id;
//
//                dol_syslog(get_class($this) . "::create sql=" . $sql);
//                $resql = $this->db->query($sql);
//                if (!$resql)
//                    $error++;
//
//            /*
//             *  Insertion du detail des produits dans la base
//             */
//            if (!$error) {
//                $fk_parent_line = 0;
//                $num = count($this->lines);
//
//                for ($i = 0; $i < $num; $i++) {
//                    // Reset fk_parent_line for no child products and special product
//                    if (($this->lines[$i]->product_type != 9 && empty($this->lines[$i]->fk_parent_line)) || $this->lines[$i]->product_type == 9) {
//                        $fk_parent_line = 0;
//                    }
//
//                    $result = $this->addline(
//                            $this->id, $this->lines[$i]->desc, $this->lines[$i]->subprice, $this->lines[$i]->qty, $this->lines[$i]->tva_tx, $this->lines[$i]->localtax1_tx, $this->lines[$i]->localtax2_tx, $this->lines[$i]->fk_product, $this->lines[$i]->remise_percent, 'HT', 0, 0, $this->lines[$i]->product_type, $this->lines[$i]->rang, $this->lines[$i]->special_code, $fk_parent_line, $this->lines[$i]->fk_fournprice, $this->lines[$i]->pa_ht, $this->lines[$i]->label
//                    );
//
//                    if ($result < 0) {
//                        $error++;
//                        $this->error = $this->db->error;
//                        dol_print_error($this->db);
//                        break;
//                    }
//                    // Defined the new fk_parent_line
//                    if ($result > 0 && $this->lines[$i]->product_type == 9) {
//                        $fk_parent_line = $result;
//                    }
//                }
//            }
//
//            // Add linked object
//            if (!$error && $this->origin && $this->origin_id) {
//                $ret = $this->add_object_linked();
//                if (!$ret)
//                    dol_print_error($this->db);
//            }
//
//            // Set delivery address
//            if (!$error && $this->fk_delivery_address) {
//                $sql = "UPDATE " . MAIN_DB_PREFIX . "propal";
//                $sql.= " SET fk_adresse_livraison = " . $this->fk_delivery_address;
//                $sql.= " WHERE ref = '" . $this->ref . "'";
//                $sql.= " AND entity = " . $conf->entity;
//
//                $result = $this->db->query($sql);
//            }
//
//            if (!$error) {
//                // Mise a jour infos denormalisees
//                $resql = $this->update_price(1);
//                if ($resql) {
//                    if (!$notrigger) {
//                        // Appel des triggers
//                        include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
//                        $interface = new Interfaces($this->db);
//                        $result = $interface->run_triggers('PROPAL_CREATE', $this, $user, $langs, $conf);
//                        if ($result < 0) {
//                            $error++;
//                            $this->errors = $interface->errors;
//                        }
//                        // Fin appel triggers
//                    }
//                } else {
//                    $error++;
//                }
//            }
//        } else {
//            $error++;
//        }
//
//        if (!$error) {
//            $this->db->commit();
//            dol_syslog(get_class($this) . "::create done id=" . $this->id);
//            return $this->id;
//        } else {
//            $this->error = $this->db->error();
//            dol_syslog(get_class($this) . "::create -2 " . $this->error, LOG_ERR);
//            $this->db->rollback();
//            return -2;
//        }
//        } else {
//            $this->error = $this->db->error();
//            dol_syslog(get_class($this) . "::create -1 " . $this->error, LOG_ERR);
//            $this->db->rollback();
//            return -1;
//        }
    }

    /**
     * 	Insert into DB a proposal object completely defined by its data members (ex, results from copy).
     *
     * 	@param 		User	$user	User that create
     * 	@return    	int				Id of the new object if ok, <0 if ko
     * 	@see       	create
     */
    function create_from($user) {
        $this->products = $this->lines;

        return $this->create();
    }

    /**
     * 		Load an object from its id and create a new one in database
     *
     * 		@param		int				$socid			Id of thirdparty
     * 	 	@return		int								New id of clone
     */
    function createFromClone($socid = 0) {
        global $user, $langs, $conf, $hookmanager;

        $error = 0;
        $now = dol_now();

        // Load source object
        $objFrom = dol_clone($this);

        $objsoc = new Societe($this->db);

        // Change socid if needed
        if (!empty($socid) && $socid != $this->socid) {
            if ($objsoc->fetch($socid) > 0) {
                $this->socid = $objsoc->id;
                $this->cond_reglement_code = (!empty($objsoc->cond_reglement_code) ? $objsoc->cond_reglement_code : 'AV_NOW');
                $this->mode_reglement_code = (!empty($objsoc->mode_reglement_code) ? $objsoc->mode_reglement_code : 'TIP');
                $this->fk_project = '';
                $this->fk_delivery_address = '';
            }

            // TODO Change product price if multi-prices
        } else {
            $objsoc->fetch($this->socid);
        }

        unset($this->id);
        unset($this->_id);
        unset($this->_rev);
        $this->Status = 'DRAFT';

        if (empty($conf->global->PROPALE_ADDON) || !is_readable(DOL_DOCUMENT_ROOT . "/propal/core/modules/propale/" . $conf->global->PROPALE_ADDON . ".php")) {
            $this->error = 'ErrorSetupNotComplete';
            return -1;
        }

        // Clear fields
        $this->user_author = $user->id;
        $this->user_valid = '';
        $this->date = $now;
        $this->datep = $now;    // deprecated
        $this->fin_validite = $this->date + ($this->duree_validite * 24 * 3600);
        $this->ref_client = '';

        // Set ref
        require_once DOL_DOCUMENT_ROOT . "/propal/core/modules/propale/" . $conf->global->PROPALE_ADDON . '.php';
        $obj = $conf->global->PROPALE_ADDON;
        $modPropale = new $obj;
        $this->ref = $modPropale->getNextValue($objsoc, $this);

        // Create clone
        $result = $this->create($user);
        if (!empty($result))
            $error++;

        if (!$error) {
            // Hook of thirdparty module
            if (is_object($hookmanager)) {
                $parameters = array('objFrom' => $objFrom);
                $action = '';
                $reshook = $hookmanager->executeHooks('createFrom', $parameters, $this, $action);    // Note that $action and $object may have been modified by some hooks
                if ($reshook < 0)
                    $error++;
            }

            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('PROPAL_CLONE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }

        // End
        if (!$error) {
            return $this->id;
        } else {
            return -1;
        }
    }

    /**
     * 	Load a proposal from database and its ligne array
     *
     * 	@param      int			$rowid		id of object to load
     * 	@param		string		$ref		Ref of proposal
     * 	@return     int         			>0 if OK, <0 if KO
     */
    function fetch($rowid, $ref = '') {
        global $conf;

        return parent::fetch($rowid);

        
//        $sql = "SELECT p.rowid, p.ref, p.remise, p.remise_percent, p.remise_absolue, p.fk_soc";
//        $sql.= ", p.total, p.tva, p.localtax1, p.localtax2, p.total_ht";
//        $sql.= ", p.datec";
//        $sql.= ", p.date_valid as datev";
//        $sql.= ", p.datep as dp";
//        $sql.= ", p.fin_validite as dfv";
//        $sql.= ", p.date_livraison as date_livraison";
//        $sql.= ", p.model_pdf, p.ref_client, p.extraparams";
//        $sql.= ", p.note as note_private, p.note_public";
//        $sql.= ", p.fk_projet, p.fk_statut";
//        $sql.= ", p.fk_user_author, p.fk_user_valid, p.fk_user_cloture";
//        $sql.= ", p.fk_adresse_livraison";
//        $sql.= ", p.fk_availability";
//        $sql.= ", p.fk_input_reason";
//        $sql.= ", p.fk_cond_reglement";
//        $sql.= ", p.fk_mode_reglement";
//        $sql.= ", c.label as statut_label";
//        $sql.= ", ca.code as availability_code, ca.label as availability";
//        $sql.= ", dr.code as demand_reason_code, dr.label as demand_reason";
//        $sql.= ", cr.code as cond_reglement_code, cr.libelle as cond_reglement, cr.libelle_facture as cond_reglement_libelle_doc";
//        $sql.= ", cp.code as mode_reglement_code, cp.libelle as mode_reglement";
//        $sql.= " FROM " . MAIN_DB_PREFIX . "c_propalst as c, " . MAIN_DB_PREFIX . "propal as p";
//        $sql.= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_paiement as cp ON p.fk_mode_reglement = cp.id';
//        $sql.= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_payment_term as cr ON p.fk_cond_reglement = cr.rowid';
//        $sql.= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_availability as ca ON p.fk_availability = ca.rowid';
//        $sql.= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'c_input_reason as dr ON p.fk_input_reason = dr.rowid';
//        $sql.= " WHERE p.fk_statut = c.id";
//        $sql.= " AND p.entity = " . $conf->entity;
//        if ($ref)
//            $sql.= " AND p.ref='" . $ref . "'";
//        else
//            $sql.= " AND p.rowid=" . $rowid;
//
//        dol_syslog(get_class($this) . "::fetch sql=" . $sql, LOG_DEBUG);
//        $resql = $this->db->query($sql);
//        if ($resql) {
//            if ($this->db->num_rows($resql)) {
//                $obj = $this->db->fetch_object($resql);
//
//                $this->id = $obj->rowid;
//
//                $this->ref = $obj->ref;
//                $this->ref_client = $obj->ref_client;
//                $this->remise = $obj->remise;
//                $this->remise_percent = $obj->remise_percent;
//                $this->remise_absolue = $obj->remise_absolue;
//                $this->total = $obj->total; // TODO obsolete
//                $this->total_ht = $obj->total_ht;
//                $this->total_tva = $obj->tva;
//                $this->total_localtax1 = $obj->localtax1;
//                $this->total_localtax2 = $obj->localtax2;
//                $this->total_ttc = $obj->total;
//                $this->socid = $obj->fk_soc;
//                $this->fk_project = $obj->fk_projet;
//                $this->modelpdf = $obj->model_pdf;
//                $this->note = $obj->note_private; // TODO obsolete
//                $this->note_private = $obj->note_private;
//                $this->note_public = $obj->note_public;
//                $this->statut = $obj->fk_statut;
//                $this->statut_libelle = $obj->statut_label;
//
//                $this->datec = $this->db->jdate($obj->datec); // TODO obsolete
//                $this->datev = $this->db->jdate($obj->datev); // TODO obsolete
//                $this->date_creation = $this->db->jdate($obj->datec); //Creation date
//                $this->date_validation = $this->db->jdate($obj->datev); //Validation date
//                $this->date = $this->db->jdate($obj->dp); // Proposal date
//                $this->datep = $this->db->jdate($obj->dp);    // deprecated
//                $this->fin_validite = $this->db->jdate($obj->dfv);
//                $this->date_livraison = $this->db->jdate($obj->date_livraison);
//                $this->availability_id = $obj->fk_availability;
//                $this->availability_code = $obj->availability_code;
//                $this->availability = $obj->availability;
//                $this->demand_reason_id = $obj->fk_input_reason;
//                $this->demand_reason_code = $obj->demand_reason_code;
//                $this->demand_reason = $obj->demand_reason;
//                $this->fk_delivery_address = $obj->fk_adresse_livraison; // TODO obsolete
//                $this->fk_address = $obj->fk_adresse_livraison;
//
//                $this->mode_reglement_id = $obj->fk_mode_reglement;
//                $this->mode_reglement_code = $obj->mode_reglement_code;
//                $this->mode_reglement = $obj->mode_reglement;
//                $this->cond_reglement_id = $obj->fk_cond_reglement;
//                $this->cond_reglement_code = $obj->cond_reglement_code;
//                $this->cond_reglement = $obj->cond_reglement;
//                $this->cond_reglement_doc = $obj->cond_reglement_libelle_doc;
//
//                $this->extraparams = (array) json_decode($obj->extraparams, true);
//
//                $this->user_author_id = $obj->fk_user_author;
//                $this->user_valid_id = $obj->fk_user_valid;
//                $this->user_close_id = $obj->fk_user_cloture;
//
//                if ($obj->fk_statut == 0) {
//                    $this->brouillon = 1;
//                }
//
//                $this->db->free($resql);
//
//                $this->lines = array();
//
//                /*
//                 * Lignes propales liees a un produit ou non
//                 */
//                $sql = "SELECT d.rowid, d.fk_propal, d.fk_parent_line, d.label as custom_label, d.description, d.price, d.tva_tx, d.localtax1_tx, d.localtax2_tx, d.qty, d.fk_remise_except, d.remise_percent, d.subprice, d.fk_product,";
//                $sql.= " d.info_bits, d.total_ht, d.total_tva, d.total_localtax1, d.total_localtax2, d.total_ttc, d.fk_product_fournisseur_price as fk_fournprice, d.buy_price_ht as pa_ht, d.special_code, d.rang, d.product_type,";
//                $sql.= ' p.ref as product_ref, p.description as product_desc, p.fk_product_type, p.label as product_label';
//                $sql.= " FROM " . MAIN_DB_PREFIX . "propaldet as d";
//                $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "product as p ON d.fk_product = p.rowid";
//                $sql.= " WHERE d.fk_propal = " . $this->id;
//                $sql.= " ORDER by d.rang";
//
//                $result = $this->db->query($sql);
//                if ($result) {
//                    $num = $this->db->num_rows($result);
//                    $i = 0;
//
//                    while ($i < $num) {
//                        $objp = $this->db->fetch_object($result);
//
//                        $line = new PropalLine($this->db);
//
//                        $line->rowid = $objp->rowid;
//                        $line->fk_propal = $objp->fk_propal;
//                        $line->fk_parent_line = $objp->fk_parent_line;
//                        $line->product_type = $objp->product_type;
//                        $line->label = $objp->custom_label;
//                        $line->desc = $objp->description;  // Description ligne
//                        $line->qty = $objp->qty;
//                        $line->tva_tx = $objp->tva_tx;
//                        $line->localtax1_tx = $objp->localtax1_tx;
//                        $line->localtax2_tx = $objp->localtax2_tx;
//                        $line->subprice = $objp->subprice;
//                        $line->fk_remise_except = $objp->fk_remise_except;
//                        $line->remise_percent = $objp->remise_percent;
//                        $line->price = $objp->price;  // TODO deprecated
//
//                        $line->info_bits = $objp->info_bits;
//                        $line->total_ht = $objp->total_ht;
//                        $line->total_tva = $objp->total_tva;
//                        $line->total_localtax1 = $objp->total_localtax1;
//                        $line->total_localtax2 = $objp->total_localtax2;
//                        $line->total_ttc = $objp->total_ttc;
//                        $line->fk_fournprice = $objp->fk_fournprice;
//                        $marginInfos = getMarginInfos($objp->subprice, $objp->remise_percent, $objp->tva_tx, $objp->localtax1_tx, $objp->localtax2_tx, $line->fk_fournprice, $objp->pa_ht);
//                        $line->pa_ht = $marginInfos[0];
//                        $line->marge_tx = $marginInfos[1];
//                        $line->marque_tx = $marginInfos[2];
//                        $line->special_code = $objp->special_code;
//                        $line->rang = $objp->rang;
//
//                        $line->fk_product = $objp->fk_product;
//
//                        $line->ref = $objp->product_ref;  // TODO deprecated
//                        $line->product_ref = $objp->product_ref;
//                        $line->libelle = $objp->product_label;  // TODO deprecated
//                        $line->product_label = $objp->product_label;
//                        $line->product_desc = $objp->product_desc;   // Description produit
//                        $line->fk_product_type = $objp->fk_product_type;
//
//                        $this->lines[$i] = $line;
//                        //dol_syslog("1 ".$line->fk_product);
//                        //print "xx $i ".$this->lines[$i]->fk_product;
//                        $i++;
//                    }
//                    $this->db->free($result);
//                } else {
//                    $this->error = $this->db->error();
//                    dol_syslog(get_class($this) . "::fetch Error " . $this->error, LOG_ERR);
//                    return -1;
//                }
//
//                return 1;
//            }
//
//            $this->error = "Record Not Found";
//            return 0;
//        } else {
//            $this->error = $this->db->error();
//            dol_syslog(get_class($this) . "::fetch Error " . $this->error, LOG_ERR);
//            return -1;
//        }
    }

        /**
     * 	Update propal
     *
     * 	@param		User	$user 		Objet user that make creation
     * 	@param		int		$notrigger	Disable all triggers
     * 	@return 	int					<0 if KO, >0 if OK
     */
    function update($user, $notrigger = 0) {
        global $conf, $langs, $mysoc;
        $error = 0;

        dol_syslog("Propal::update user=" . $user->id);

        // Clean parameters
        if (empty($this->date))
            $this->date = $this->datep;
        $this->fin_validite = $this->date + ($this->duree_validite * 24 * 3600);

        // Check parameters
        $soc = new Societe($this->db);
        $result = $soc->fetch($this->socid);
        if ($result < 0) {
            $this->error = "Failed to fetch company";
            dol_syslog(get_class($this) . "::create " . $this->error, LOG_ERR);
            return -3;
        }
        $this->client = new stdClass();
        $this->client->id = $soc->id;
        $this->client->name = $soc->name;

        if (empty($this->date)) {
            $this->error = "Date of proposal is required";
            dol_syslog(get_class($this) . "::create " . $this->error, LOG_ERR);
            return -4;
        }

        $this->record();
        if (!$notrigger) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('PROPAL_UPDATE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }
        return 1;

    }

    /**
     *  Set status to validated
     *
     *  @param	User	$user       Object user that validate
     *  @param	int		$notrigger	1=Does not execute triggers, 0= execuete triggers
     *  @return int         		<0 if KO, >=0 if OK
     */
    function valid($user, $notrigger = 0) {
        global $conf, $langs;

        $error = 0;
        $now = dol_now();

        if ($user->rights->propal->valider) {
            $this->Status = 'OPENED';
            $this->user_valid_login = $user->login;
            $this->datev = $now;
            $this->record();
            return 1;
//            $this->db->begin();
//
//            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal";
//            $sql.= " SET fk_statut = 1, date_valid='" . $this->db->idate($now) . "', fk_user_valid=" . $user->id;
//            $sql.= " WHERE rowid = " . $this->id . " AND fk_statut = 0";
//
//            dol_syslog(get_class($this) . '::valid sql=' . $sql);
//            if ($this->db->query($sql)) {
//                if (!$notrigger) {
//                    // Appel des triggers
//                    include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
//                    $interface = new Interfaces($this->db);
//                    $result = $interface->run_triggers('PROPAL_VALIDATE', $this, $user, $langs, $conf);
//                    if ($result < 0) {
//                        $error++;
//                        $this->errors = $interface->errors;
//                    }
//                    // Fin appel triggers
//                }
//
//                if (!$error) {
//                    $this->brouillon = 0;
//                    $this->statut = 1;
//                    $this->user_valid_id = $user->id;
//                    $this->datev = $now;
//                    $this->db->commit();
//                    return 1;
//                } else {
//                    $this->db->rollback();
//                    return -2;
//                }
        } else {
            $this->db->rollback();
            return -1;
        }
    }

    /**
     *  Define proposal date
     *
     *  @param  User		$user      		Object user that modify
     *  @param  timestamp	$date			Date
     *  @return	int         				<0 if KO, >0 if OK
     */
    function set_date($user, $date) {
        if (empty($date)) {
            $this->error = 'ErrorBadParameter';
            dol_syslog(get_class($this) . "::set_date " . $this->error, LOG_ERR);
            return -1;
        }

        if ($user->rights->propal->creer) {
            $this->date = $date;
            $this->datep = $date;
            $this->record();
            return 1;
//            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal SET datep = '" . $this->db->idate($date) . "'";
//            $sql.= " WHERE rowid = " . $this->id . " AND fk_statut = 0";
//
//            dol_syslog(get_class($this) . "::set_date sql=" . $sql);
//            if ($this->db->query($sql)) {
//                $this->date = $date;
//                $this->datep = $date;    // deprecated
//                return 1;
//            } else {
//                $this->error = $this->db->lasterror();
//                dol_syslog(get_class($this) . "::set_date " . $this->error, LOG_ERR);
//                return -1;
//            }
        }
    }

    /**
     * 	Define end validity date
     *
     * 	@param		User		$user        		Object user that modify
     * 	@param      timestamp	$date_fin_validite	End of validity date
     * 	@return     int         					<0 if KO, >0 if OK
     */
    function set_echeance($user, $date_fin_validite) {
        if ($user->rights->propal->creer) {
            $this->fin_validite = $date_fin_validite;
            $this->record();
            return 1;
//            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal SET fin_validite = " . ($date_fin_validite != '' ? "'" . $this->db->idate($date_fin_validite) . "'" : 'null');
//            $sql.= " WHERE rowid = " . $this->id . " AND fk_statut = 0";
//            if ($this->db->query($sql)) {
//                $this->fin_validite = $date_fin_validite;
//                return 1;
//            } else {
//                $this->error = $this->db->error();
//                dol_syslog(get_class($this) . "::set_echeance Erreur SQL" . $this->error, LOG_ERR);
//                return -1;
//            }
        }
    }

    /**
     * 	Set delivery date
     *
     * 	@param      User 		$user        		Object user that modify
     * 	@param      timestamp	$date_livraison     Delivery date
     * 	@return     int         					<0 if ko, >0 if ok
     */
    function set_date_livraison($user, $date_livraison) {
        if ($user->rights->propal->creer) {
            $this->date_livraison = $date_livraison;
            $this->record();
            return 1;
//            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal ";
//            $sql.= " SET date_livraison = " . ($date_livraison != '' ? "'" . $this->db->idate($date_livraison) . "'" : 'null');
//            $sql.= " WHERE rowid = " . $this->id;
//
//            if ($this->db->query($sql)) {
//                $this->date_livraison = $date_livraison;
//                return 1;
//            } else {
//                $this->error = $this->db->error();
//                dol_syslog(get_class($this) . "::set_date_livraison Erreur SQL");
//                return -1;
//            }
        }
    }

    /**
     *  Set delivery
     *
     *  @param		User	$user		  	Object user that modify
     *  @param      int		$id				Availability id
     *  @return     int           			<0 if KO, >0 if OK
     */
    function set_availability($user, $id) {
        if ($user->rights->propal->creer) {
            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal ";
            $sql.= " SET fk_availability = '" . $id . "'";
            $sql.= " WHERE rowid = " . $this->id;

            if ($this->db->query($sql)) {
                $this->fk_availability = $id;
                return 1;
            } else {
                $this->error = $this->db->error();
                dol_syslog(get_class($this) . "::set_availability Erreur SQL");
                return -1;
            }
        }
    }

    /**
     *  Set source of demand
     *
     *  @param		User	$user		Object user that modify
     *  @param      int		$id			Input reason id
     *  @return     int           		<0 if KO, >0 if OK
     */
    function set_demand_reason($user, $id) {
        if ($user->rights->propal->creer) {
            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal ";
            $sql.= " SET fk_input_reason = '" . $id . "'";
            $sql.= " WHERE rowid = " . $this->id;

            if ($this->db->query($sql)) {
                $this->fk_input_reason = $id;
                return 1;
            } else {
                $this->error = $this->db->error();
                dol_syslog(get_class($this) . "::set_demand_reason Erreur SQL");
                return -1;
            }
        }
    }

    /**
     * Set customer reference number
     *
     *  @param      User	$user			Object user that modify
     *  @param      string	$ref_client		Customer reference
     *  @return     int						<0 if ko, >0 if ok
     */
    function set_ref_client($user, $ref_client) {
        if ($user->rights->propal->creer) {
            $this->ref_client = $ref_client;
            $this->record();
            return 1;
//            dol_syslog('Propale::set_ref_client this->id=' . $this->id . ', ref_client=' . $ref_client);
//
//            $sql = 'UPDATE ' . MAIN_DB_PREFIX . 'propal SET ref_client = ' . (empty($ref_client) ? 'NULL' : '\'' . $this->db->escape($ref_client) . '\'');
//            $sql.= ' WHERE rowid = ' . $this->id;
//            if ($this->db->query($sql)) {
//                $this->ref_client = $ref_client;
//                return 1;
//            } else {
//                $this->error = $this->db->error();
//                dol_syslog('Propale::set_ref_client Erreur ' . $this->error . ' - ' . $sql);
//                return -2;
//            }
        } else {
            return -1;
        }
    }

    /**
     * 	Set an overall discount on the proposal
     *
     * 	@param      User	$user       Object user that modify
     * 	@param      double	$remise      Amount discount
     * 	@return     int         		<0 if ko, >0 if ok
     */
    function set_remise_percent($user, $remise) {
        $remise = trim($remise) ? trim($remise) : 0;

        if ($user->rights->propal->creer) {
            $remise = price2num($remise);

            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal SET remise_percent = " . $remise;
            $sql.= " WHERE rowid = " . $this->id . " AND fk_statut = 0";

            if ($this->db->query($sql)) {
                $this->remise_percent = $remise;
                $this->update_price(1);
                return 1;
            } else {
                $this->error = $this->db->error();
                dol_syslog(get_class($this) . "::set_remise_percent Error sql=$sql");
                return -1;
            }
        }
    }

    /**
     * 	Set an absolute overall discount on the proposal
     *
     * 	@param      User	$user        Object user that modify
     * 	@param      double	$remise      Amount discount
     * 	@return     int         		<0 if ko, >0 if ok
     */
    function set_remise_absolue($user, $remise) {
        $remise = trim($remise) ? trim($remise) : 0;

        if ($user->rights->propal->creer) {
            $remise = price2num($remise);

            $sql = "UPDATE " . MAIN_DB_PREFIX . "propal ";
            $sql.= " SET remise_absolue = " . $remise;
            $sql.= " WHERE rowid = " . $this->id . " AND fk_statut = 0";

            if ($this->db->query($sql)) {
                $this->remise_absolue = $remise;
                $this->update_price(1);
                return 1;
            } else {
                $this->error = $this->db->error();
                dol_syslog(get_class($this) . "::set_remise_absolue Error sql=$sql");
                return -1;
            }
        }
    }

    /**
     * 	Close the commercial proposal
     *
     * 	@param      User	$user		Object user that close
     * 	@param      int		$statut		Statut
     * 	@param      text	$note		Comment
     * 	@return     int         		<0 if KO, >0 if OK
     */
    function reopen($user, $statut, $note) {
        global $langs, $conf;

        $this->statut = $statut;
        $error = 0;
        $now = dol_now();

        $this->db->begin();

        $sql = "UPDATE " . MAIN_DB_PREFIX . "propal";
        $sql.= " SET fk_statut = " . $statut . ", note = '" . $this->db->escape($note) . "', date_cloture=" . $this->db->idate($now) . ", fk_user_cloture=" . $user->id;
        $sql.= " WHERE rowid = " . $this->id;

        $resql = $this->db->query($sql);
        if ($resql) {

        }
    }

    /**
     * 	Close the commercial proposal
     *
     * 	@param      User	$user		Object user that close
     * 	@param      int		$statut		Statut
     * 	@param      text	$note		Comment
     * 	@return     int         		<0 if KO, >0 if OK
     */
    function cloture($user, $statut, $note) {
        global $langs, $conf;

        $error = 0;
        $now = dol_now();

        $this->Status = $statut;
        $this->note = $note;
        $this->record();

        if ($this->Status == "SIGNED") {
             $soc = new Societe($this->db);
                $soc->id = $this->socid;
                $result = $soc->set_as_client();

                // Appel des triggers
                include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
                $interface = new Interfaces($this->db);
                $result = $interface->run_triggers('PROPAL_CLOSE_SIGNED', $this, $user, $langs, $conf);
                if ($result < 0) {
                    $error++;
                    $this->errors = $interface->errors;
                }
        } else {
            // Appel des triggers
                include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
                $interface = new Interfaces($this->db);
                $result = $interface->run_triggers('PROPAL_CLOSE_REFUSED', $this, $user, $langs, $conf);
                if ($result < 0) {
                    $error++;
                    $this->errors = $interface->errors;
                }
        }

//        $this->db->begin();
//
//        $sql = "UPDATE " . MAIN_DB_PREFIX . "propal";
//        $sql.= " SET fk_statut = " . $statut . ", note = '" . $this->db->escape($note) . "', date_cloture=" . $this->db->idate($now) . ", fk_user_cloture=" . $user->id;
//        $sql.= " WHERE rowid = " . $this->id;
//
//        $resql = $this->db->query($sql);
//        if ($resql) {
//            if ($statut == 2) {
//                // Classe la societe rattachee comme client
//                $soc = new Societe($this->db);
//                $soc->id = $this->socid;
//                $result = $soc->set_as_client();
//
//                if ($result < 0) {
//                    $this->error = $this->db->error();
//                    $this->db->rollback();
//                    return -2;
//                }
//
//                // Appel des triggers
//                include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
//                $interface = new Interfaces($this->db);
//                $result = $interface->run_triggers('PROPAL_CLOSE_SIGNED', $this, $user, $langs, $conf);
//                if ($result < 0) {
//                    $error++;
//                    $this->errors = $interface->errors;
//                }
//                // Fin appel triggers
//            } else {
//                // Appel des triggers
//                include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
//                $interface = new Interfaces($this->db);
//                $result = $interface->run_triggers('PROPAL_CLOSE_REFUSED', $this, $user, $langs, $conf);
//                if ($result < 0) {
//                    $error++;
//                    $this->errors = $interface->errors;
//                }
//                // Fin appel triggers
//            }
//
//            $this->db->commit();
//            return 1;
//        } else {
//            $this->error = $this->db->error();
//            $this->db->rollback();
//            return -1;
//        }
    }

    /**
     * 	Class invoiced the Propal
     *
     * 	@return     int     	<0 si ko, >0 si ok
     */
    function classifyBilled() {
        $sql = 'UPDATE ' . MAIN_DB_PREFIX . 'propal SET fk_statut = 4';
        $sql .= ' WHERE rowid = ' . $this->id . ' AND fk_statut > 0 ;';
        if ($this->db->query($sql)) {
            $this->statut = 4;
            return 1;
        } else {
            dol_print_error($this->db);
        }
    }

    /**
     * 	Class invoiced the Propal
     *
     * 	@return     int     	<0 si ko, >0 si ok
     */
    function classer_facturee() {
        return $this->classifyBilled();
    }

    /**
     * 	Set draft status
     *
     * 	@param		User	$user		Object user that modify
     * 	@return		int					<0 if KO, >0 if OK
     */
    function set_draft($user) {
        global $conf, $langs;
        $this->Status = "DRAFT";
        $this->record();
        return 1;

//        $sql = "UPDATE " . MAIN_DB_PREFIX . "propal SET fk_statut = 0";
//        $sql.= " WHERE rowid = " . $this->id;
//
//        if ($this->db->query($sql)) {
//            $this->statut = 0;
//            $this->brouillon = 1;
//            return 1;
//        } else {
//            return -1;
//        }
    }

    /**
     *    Return list of proposal (eventually filtered on user) into an array
     *
     *    @param	int		$shortlist			0=Return array[id]=ref, 1=Return array[](id=>id,ref=>ref)
     *    @param	int		$draft				0=not draft, 1=draft
     *    @param	int		$notcurrentuser		0=current user, 1=not current user
     *    @param    int		$socid				Id third pary
     *    @param    int		$limit				For pagination
     *    @param    int		$offset				For pagination
     *    @param    string	$sortfield			Sort criteria
     *    @param    string	$sortorder			Sort order
     *    @return	int		       				-1 if KO, array with result if OK
     */
    function liste_array($shortlist = 0, $draft = 0, $notcurrentuser = 0, $socid = 0, $limit = 0, $offset = 0, $sortfield = 'p.datep', $sortorder = 'DESC') {
        global $conf, $user;

        $ga = array();

        $sql = "SELECT s.nom, s.rowid, p.rowid as propalid, p.fk_statut, p.total_ht, p.ref, p.remise, ";
        $sql.= " p.datep as dp, p.fin_validite as datelimite";
        $sql.= " FROM " . MAIN_DB_PREFIX . "societe as s, " . MAIN_DB_PREFIX . "propal as p, " . MAIN_DB_PREFIX . "c_propalst as c";
        $sql.= " WHERE p.entity = " . $conf->entity;
        $sql.= " AND p.fk_soc = s.rowid";
        $sql.= " AND p.fk_statut = c.id";
        if ($socid)
            $sql.= " AND s.rowid = " . $socid;
        if ($draft)
            $sql.= " AND p.fk_statut = 0";
        if ($notcurrentuser)
            $sql.= " AND p.fk_user_author <> " . $user->id;
        $sql.= $this->db->order($sortfield, $sortorder);
        $sql.= $this->db->plimit($limit, $offset);

        $result = $this->db->query($sql);
        if ($result) {
            $num = $this->db->num_rows($result);
            if ($num) {
                $i = 0;
                while ($i < $num) {
                    $obj = $this->db->fetch_object($result);

                    if ($shortlist) {
                        $ga[$obj->propalid] = $obj->ref;
                    } else {
                        $ga[$i]['id'] = $obj->propalid;
                        $ga[$i]['ref'] = $obj->ref;
                    }

                    $i++;
                }
            }
            return $ga;
        } else {
            dol_print_error($this->db);
            return -1;
        }
    }

    /**
     *  Returns an array with the numbers of related invoices
     *
     * 	@return	array		Array of invoices
     */
    function getInvoiceArrayList() {
        return $this->InvoiceArrayList($this->id);
    }

    /**
     *  Returns an array with id and ref of related invoices
     *
     * 	@param		int		$id			Id propal
     * 	@return		array				Array of invoices id
     */
    function InvoiceArrayList($id) {
        $ga = array();
        $linkedInvoices = array();

        $this->fetchObjectLinked($id, $this->element);
        foreach ($this->linkedObjectsIds as $objecttype => $objectid) {
            $numi = count($objectid);
            for ($i = 0; $i < $numi; $i++) {
                // Cas des factures liees directement
                if ($objecttype == 'facture') {
                    $linkedInvoices[] = $objectid[$i];
                }
                // Cas des factures liees via la commande
                else {
                    $this->fetchObjectLinked($objectid[$i], $objecttype);
                    foreach ($this->linkedObjectsIds as $subobjecttype => $subobjectid) {
                        $numj = count($subobjectid);
                        for ($j = 0; $j < $numj; $j++) {
                            $linkedInvoices[] = $subobjectid[$j];
                        }
                    }
                }
            }
        }

        if (count($linkedInvoices) > 0) {
            $sql = "SELECT rowid as facid, facnumber, total, datef as df, fk_user_author, fk_statut, paye";
            $sql.= " FROM " . MAIN_DB_PREFIX . "facture";
            $sql.= " WHERE rowid IN (" . implode(',', $linkedInvoices) . ")";

            dol_syslog(get_class($this) . "::InvoiceArrayList sql=" . $sql);
            $resql = $this->db->query($sql);

            if ($resql) {
                $tab_sqlobj = array();
                $nump = $this->db->num_rows($resql);
                for ($i = 0; $i < $nump; $i++) {
                    $sqlobj = $this->db->fetch_object($resql);
                    $tab_sqlobj[] = $sqlobj;
                }
                $this->db->free($resql);

                $nump = count($tab_sqlobj);

                if ($nump) {
                    $i = 0;
                    while ($i < $nump) {
                        $obj = array_shift($tab_sqlobj);

                        $ga[$i] = $obj;

                        $i++;
                    }
                }
                return $ga;
            } else {
                return -1;
            }
        }
        else
            return $ga;
    }

    /**
     * 	Delete proposal
     *
     * 	@param	User	$user        	Object user that delete
     * 	@param	int		$notrigger		1=Does not execute triggers, 0= execuete triggers
     * 	@return	int						1 if ok, otherwise if error
     */
    function delete($user, $notrigger = 0) {
        global $conf, $langs;
        require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

        $error = 0;

//        $this->db->begin();

        if (!$error && !$notrigger) {
            // Call triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('PROPAL_DELETE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // End call triggers
        }

        if (!$error) {

            // Delete linked object
            // Delete linked contacts
            // Remove directory
            $ref = dol_sanitizeFileName($this->ref);
            if ($conf->propal->dir_output) {
                $dir = $conf->propal->dir_output . "/" . $ref;
                $file = $dir . "/" . $ref . ".pdf";
                if (file_exists($file)) {
                    dol_delete_preview($this);

                    if (!dol_delete_file($file, 0, 0, 0, $this)) { // For triggers
                        $this->error = 'ErrorFailToDeleteFile';
                        $this->errors = array('ErrorFailToDeleteFile');
                        return 0;
                    }
                }
                if (file_exists($dir)) {
                    $res = @dol_delete_dir_recursive($dir);
                    if (!$res) {
                        $this->error = 'ErrorFailToDeleteDir';
                        $this->errors = array('ErrorFailToDeleteDir');
                        return 0;
                    }
                }
            }

            // Remove propal lines
            $this->getLinesArray();
            foreach ($this->lines as $line)
                $line->delete();

            // Remove propal
            $this->deleteDoc();

            return 1;

//            $sql = "DELETE FROM " . MAIN_DB_PREFIX . "propaldet WHERE fk_propal = " . $this->id;
//            if ($this->db->query($sql)) {
//                $sql = "DELETE FROM " . MAIN_DB_PREFIX . "propal WHERE rowid = " . $this->id;
//                if ($this->db->query($sql)) {
//                    // Delete linked object
//                    $res = $this->deleteObjectLinked();
//                    if ($res < 0)
//                        $error++;
//
//                    // Delete linked contacts
//                    $res = $this->delete_linked_contact();
//                    if ($res < 0)
//                        $error++;
//
//                    if (!$error) {
//                        // We remove directory
//                        $ref = dol_sanitizeFileName($this->ref);
//                        if ($conf->propal->dir_output) {
//                            $dir = $conf->propal->dir_output . "/" . $ref;
//                            $file = $dir . "/" . $ref . ".pdf";
//                            if (file_exists($file)) {
//                                dol_delete_preview($this);
//
//                                if (!dol_delete_file($file, 0, 0, 0, $this)) { // For triggers
//                                    $this->error = 'ErrorFailToDeleteFile';
//                                    $this->errors = array('ErrorFailToDeleteFile');
//                                    $this->db->rollback();
//                                    return 0;
//                                }
//                            }
//                            if (file_exists($dir)) {
//                                $res = @dol_delete_dir_recursive($dir);
//                                if (!$res) {
//                                    $this->error = 'ErrorFailToDeleteDir';
//                                    $this->errors = array('ErrorFailToDeleteDir');
//                                    $this->db->rollback();
//                                    return 0;
//                                }
//                            }
//                        }
//                    }
//
//                    if (!$error) {
//                        dol_syslog(get_class($this) . "::delete $this->id by $user->id", LOG_DEBUG);
//                        $this->db->commit();
//                        return 1;
//                    } else {
//                        $this->error = $this->db->lasterror();
//                        dol_syslog(get_class($this) . "::delete " . $this->error, LOG_ERR);
//                        $this->db->rollback();
//                        return 0;
//                    }
//                } else {
//                    $this->error = $this->db->lasterror();
//                    dol_syslog(get_class($this) . "::delete " . $this->error, LOG_ERR);
//                    $this->db->rollback();
//                    return -3;
//                }
//            } else {
//                $this->error = $this->db->lasterror();
//                dol_syslog(get_class($this) . "::delete " . $this->error, LOG_ERR);
//                $this->db->rollback();
//                return -2;
//            }
        } else {
            $this->error = $this->db->lasterror();
            dol_syslog(get_class($this) . "::delete " . $this->error, LOG_ERR);
//            $this->db->rollback();
            return -1;
        }
    }

    /**
     *  Change the delivery time
     *
     *  @param	int	$availability_code	code of new delivery time
     *  @return int                  	>0 if OK, <0 if KO
     */
    function availability($availability_code) {
        dol_syslog('Propale::availability(' . $availability_code . ')');
        if ($this->Status == "DRAFT") {
            $this->availability_code = $availability_code;
            $this->record();
            return 1;
//            $sql = 'UPDATE ' . MAIN_DB_PREFIX . 'propal';
//            $sql .= ' SET fk_availability = ' . $availability_id;
//            $sql .= ' WHERE rowid=' . $this->id;
//            if ($this->db->query($sql)) {
//                $this->availability_id = $availability_id;
//                return 1;
//            } else {
//                dol_syslog('Propale::availability Erreur ' . $sql . ' - ' . $this->db->error());
//                $this->error = $this->db->error();
//                return -1;
//            }
        } else {
            dol_syslog('Propale::availability, etat propale incompatible');
            $this->error = 'Etat propale incompatible ' . $this->statut;
            return -2;
        }
    }

    /**
     * 	Change source demand
     *
     * 	@param	int $demand_reason_code 	Code of new source demand
     * 	@return int						>0 si ok, <0 si ko
     */
    function demand_reason($demand_reason_code) {
        dol_syslog('Propale::demand_reason(' . $demand_reason_code . ')');
        if ($this->Status == "DRAFT") {
            $this->demand_reason_code = $demand_reason_code;
            $this->record();
            return 1;
//            $sql = 'UPDATE ' . MAIN_DB_PREFIX . 'propal';
//            $sql .= ' SET fk_input_reason = ' . $demand_reason_id;
//            $sql .= ' WHERE rowid=' . $this->id;
//            if ($this->db->query($sql)) {
//                $this->demand_reason_id = $demand_reason_id;
//                return 1;
//            } else {
//                dol_syslog('Propale::demand_reason Erreur ' . $sql . ' - ' . $this->db->error());
//                $this->error = $this->db->error();
//                return -1;
//            }
        } else {
            dol_syslog('Propale::demand_reason, etat propale incompatible');
            $this->error = 'Etat propale incompatible ' . $this->statut;
            return -2;
        }
    }

        /**
     * 	Change payment terms
     *
     * 	@param	string $code_reglement_code 	Code of new payment term
     * 	@return int						>0 si ok, <0 si ko
     */
    function setPaymentTerms($cond_reglement_code) {
        if ($this->Status == "DRAFT") {
            $this->cond_reglement_code = $cond_reglement_code;
            $this->record();
            return 1;
        }
        return -2;
    }

    /**
     * 	Change payment methods
     *
     * 	@param	string $mode_reglement_code 	Code of new payment term
     * 	@return int						>0 si ok, <0 si ko
     */
    function setPaymentMethods($mode_reglement_code) {
        dol_syslog('Propale::setPaymentMethods(' . $mode_reglement_code . ')');
        if ($this->Status == "DRAFT") {
            $this->mode_reglement_code = $mode_reglement_code;
            $this->record();
            return 1;
//            $sql = 'UPDATE ' . MAIN_DB_PREFIX . 'propal';
//            $sql .= ' SET fk_input_reason = ' . $demand_reason_id;
//            $sql .= ' WHERE rowid=' . $this->id;
//            if ($this->db->query($sql)) {
//                $this->demand_reason_id = $demand_reason_id;
//                return 1;
//            } else {
//                dol_syslog('Propale::demand_reason Erreur ' . $sql . ' - ' . $this->db->error());
//                $this->error = $this->db->error();
//                return -1;
//            }
        } else {
            dol_syslog('Propale::demand_reason, etat propale incompatible');
            $this->error = 'Etat propale incompatible ' . $this->statut;
            return -2;
        }
    }

    /**
     * 	Object Proposal Information
     *
     * 	@param	int		$id		Proposal id
     *  @return	void
     */
    function info($id) {
        $sql = "SELECT c.rowid, ";
        $sql.= " c.datec, c.date_valid as datev, c.date_cloture as dateo,";
        $sql.= " c.fk_user_author, c.fk_user_valid, c.fk_user_cloture";
        $sql.= " FROM " . MAIN_DB_PREFIX . "propal as c";
        $sql.= " WHERE c.rowid = " . $id;

        $result = $this->db->query($sql);

        if ($result) {
            if ($this->db->num_rows($result)) {
                $obj = $this->db->fetch_object($result);

                $this->id = $obj->rowid;

                $this->date_creation = $this->db->jdate($obj->datec);
                $this->date_validation = $this->db->jdate($obj->datev);
                $this->date_cloture = $this->db->jdate($obj->dateo);

                $cuser = new User($this->db);
                $cuser->fetch($obj->fk_user_author);
                $this->user_creation = $cuser;

                if ($obj->fk_user_valid) {
                    $vuser = new User($this->db);
                    $vuser->fetch($obj->fk_user_valid);
                    $this->user_validation = $vuser;
                }

                if ($obj->fk_user_cloture) {
                    $cluser = new User($this->db);
                    $cluser->fetch($obj->fk_user_cloture);
                    $this->user_cloture = $cluser;
                }
            }
            $this->db->free($result);
        } else {
            dol_print_error($this->db);
        }
    }

    /**
     *    	Return label of status of proposal (draft, validated, ...)
     *
     *    	@param      int			$mode        0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto
     *    	@return     string		Label
     */
    function getLibStatut($mode = 0) {
        return $this->LibStatut($this->statut, $mode);
    }

    /**
     *    	Return label of a status (draft, validated, ...)
     *
     *    	@param      int			$statut		id statut
     *    	@param      int			$mode      	0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto
     *    	@return     string		Label
     */
    function LibStatut($statut, $mode = 1) {
        global $langs;
        $langs->load("propal");

        if ($mode == 0) {
            return $this->labelstatut[$statut];
        }
        if ($mode == 1) {
            return $this->labelstatut_short[$statut];
        }
        if ($mode == 2) {
            if ($statut == 0)
                return img_picto($langs->trans('PropalStatusDraftShort'), 'statut0') . ' ' . $this->labelstatut_short[$statut];
            if ($statut == 1)
                return img_picto($langs->trans('PropalStatusOpenedShort'), 'statut1') . ' ' . $this->labelstatut_short[$statut];
            if ($statut == 2)
                return img_picto($langs->trans('PropalStatusSignedShort'), 'statut3') . ' ' . $this->labelstatut_short[$statut];
            if ($statut == 3)
                return img_picto($langs->trans('PropalStatusNotSignedShort'), 'statut5') . ' ' . $this->labelstatut_short[$statut];
            if ($statut == 4)
                return img_picto($langs->trans('PropalStatusBilledShort'), 'statut6') . ' ' . $this->labelstatut_short[$statut];
        }
        if ($mode == 3) {
            if ($statut == 0)
                return img_picto($langs->trans('PropalStatusDraftShort'), 'statut0');
            if ($statut == 1)
                return img_picto($langs->trans('PropalStatusOpenedShort'), 'statut1');
            if ($statut == 2)
                return img_picto($langs->trans('PropalStatusSignedShort'), 'statut3');
            if ($statut == 3)
                return img_picto($langs->trans('PropalStatusNotSignedShort'), 'statut5');
            if ($statut == 4)
                return img_picto($langs->trans('PropalStatusBilledShort'), 'statut6');
        }
        if ($mode == 4) {
            if ($statut == 0)
                return img_picto($langs->trans('PropalStatusDraft'), 'statut0') . ' ' . $this->labelstatut[$statut];
            if ($statut == 1)
                return img_picto($langs->trans('PropalStatusOpened'), 'statut1') . ' ' . $this->labelstatut[$statut];
            if ($statut == 2)
                return img_picto($langs->trans('PropalStatusSigned'), 'statut3') . ' ' . $this->labelstatut[$statut];
            if ($statut == 3)
                return img_picto($langs->trans('PropalStatusNotSigned'), 'statut5') . ' ' . $this->labelstatut[$statut];
            if ($statut == 4)
                return img_picto($langs->trans('PropalStatusBilled'), 'statut6') . ' ' . $this->labelstatut[$statut];
        }
        if ($mode == 5) {
            if ($statut == 0)
                return $this->labelstatut_short[$statut] . ' ' . img_picto($langs->trans('PropalStatusDraftShort'), 'statut0');
            if ($statut == 1)
                return $this->labelstatut_short[$statut] . ' ' . img_picto($langs->trans('PropalStatusOpenedShort'), 'statut1');
            if ($statut == 2)
                return $this->labelstatut_short[$statut] . ' ' . img_picto($langs->trans('PropalStatusSignedShort'), 'statut3');
            if ($statut == 3)
                return $this->labelstatut_short[$statut] . ' ' . img_picto($langs->trans('PropalStatusNotSignedShort'), 'statut5');
            if ($statut == 4)
                return $this->labelstatut_short[$statut] . ' ' . img_picto($langs->trans('PropalStatusBilledShort'), 'statut6');
        }
    }

    /**
     *      Load indicators for dashboard (this->nbtodo and this->nbtodolate)
     *
     *      @param          User	$user   Object user
     *      @param          int		$mode   "opened" for proposal to close, "signed" for proposal to invoice
     *      @return         int     		<0 if KO, >0 if OK
     */
    function load_board($user, $mode) {
        global $conf, $user;

        $now = dol_now();

        $this->nbtodo = $this->nbtodolate = 0;
        $clause = " WHERE";

        $sql = "SELECT p.rowid, p.ref, p.datec as datec, p.fin_validite as datefin";
        $sql.= " FROM " . MAIN_DB_PREFIX . "propal as p";
        if (!$user->rights->societe->client->voir && !$user->societe_id) {
            $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe_commerciaux as sc ON p.fk_soc = sc.fk_soc";
            $sql.= " WHERE sc.fk_user = " . $user->id;
            $clause = " AND";
        }
        $sql.= $clause . " p.entity = " . $conf->entity;
        if ($mode == 'opened')
            $sql.= " AND p.fk_statut = 1";
        if ($mode == 'signed')
            $sql.= " AND p.fk_statut = 2";
        if ($user->societe_id)
            $sql.= " AND p.fk_soc = " . $user->societe_id;

        $resql = $this->db->query($sql);
        if ($resql) {
            if ($mode == 'opened')
                $delay_warning = $conf->propal->cloture->warning_delay;
            if ($mode == 'signed')
                $delay_warning = $conf->propal->facturation->warning_delay;

            while ($obj = $this->db->fetch_object($resql)) {
                $this->nbtodo++;
                if ($mode == 'opened') {
                    $datelimit = $this->db->jdate($obj->datefin);
                    if ($datelimit < ($now - $delay_warning)) {
                        $this->nbtodolate++;
                    }
                }
                // TODO Definir regle des propales a facturer en retard
                // if ($mode == 'signed' && ! count($this->FactureListeArray($obj->rowid))) $this->nbtodolate++;
            }
            return 1;
        } else {
            $this->error = $this->db->error();
            return -1;
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
        global $user, $langs, $conf;

        // Charge tableau des produits prodids
        $prodids = array();
        $sql = "SELECT rowid";
        $sql.= " FROM " . MAIN_DB_PREFIX . "product";
        $sql.= " WHERE entity IN (" . getEntity('product', 1) . ")";
        $resql = $this->db->query($sql);
        if ($resql) {
            $num_prods = $this->db->num_rows($resql);
            $i = 0;
            while ($i < $num_prods) {
                $i++;
                $row = $this->db->fetch_row($resql);
                $prodids[$i] = $row[0];
            }
        }

        // Initialise parametres
        $this->id = 0;
        $this->ref = 'SPECIMEN';
        $this->ref_client = 'NEMICEPS';
        $this->specimen = 1;
        $this->socid = 1;
        $this->date = time();
        $this->fin_validite = $this->date + 3600 * 24 * 30;
        $this->cond_reglement_id = 1;
        $this->cond_reglement_code = 'RECEP';
        $this->mode_reglement_id = 7;
        $this->mode_reglement_code = 'CHQ';
        $this->availability_id = 1;
        $this->availability_code = 'DSP';
        $this->demand_reason_id = 1;
        $this->demand_reason_code = 'SRC_00';
        $this->note_public = 'This is a comment (public)';
        $this->note = 'This is a comment (private)';
        // Lines
        $nbp = 5;
        $xnbp = 0;
        while ($xnbp < $nbp) {
            $line = new PropalLine($this->db);
            $line->desc = $langs->trans("Description") . " " . $xnbp;
            $line->qty = 1;
            $line->subprice = 100;
            $line->price = 100;
            $line->tva_tx = 19.6;
            $line->localtax1_tx = 0;
            $line->localtax2_tx = 0;
            if ($xnbp == 2) {
                $line->total_ht = 50;
                $line->total_ttc = 59.8;
                $line->total_tva = 9.8;
                $line->remise_percent = 50;
            } else {
                $line->total_ht = 100;
                $line->total_ttc = 119.6;
                $line->total_tva = 19.6;
                $line->remise_percent = 00;
            }

            $prodid = rand(1, $num_prods);
            $line->fk_product = $prodids[$prodid];

            $this->lines[$xnbp] = $line;

            $this->total_ht += $line->total_ht;
            $this->total_tva += $line->total_tva;
            $this->total_ttc += $line->total_ttc;

            $xnbp++;
        }
    }

    /**
     *      Charge indicateurs this->nb de tableau de bord
     *
     *      @return     int         <0 if ko, >0 if ok
     */
    function load_state_board() {
        global $conf, $user;

        $this->nb = array();
        $clause = "WHERE";

        $sql = "SELECT count(p.rowid) as nb";
        $sql.= " FROM " . MAIN_DB_PREFIX . "propal as p";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON p.fk_soc = s.rowid";
        if (!$user->rights->societe->client->voir && !$user->societe_id) {
            $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe_commerciaux as sc ON s.rowid = sc.fk_soc";
            $sql.= " WHERE sc.fk_user = " . $user->id;
            $clause = "AND";
        }
        $sql.= " " . $clause . " p.entity = " . $conf->entity;

        $resql = $this->db->query($sql);
        if ($resql) {
            while ($obj = $this->db->fetch_object($resql)) {
                $this->nb["proposals"] = $obj->nb;
            }
            return 1;
        } else {
            dol_print_error($this->db);
            $this->error = $this->db->error();
            return -1;
        }
    }

    /**
     *  Returns the reference to the following non used Proposal used depending on the active numbering module
     *  defined into PROPALE_ADDON
     *
     *  @param	Societe		$soc  	Object thirdparty
     *  @return string      		Reference libre pour la propale
     */
    function getNextNumRef($soc) {
        global $conf, $db, $langs;
        $langs->load("propal");

        $dir = DOL_DOCUMENT_ROOT . "/propal/core/modules/propale/";

        if (!empty($conf->global->PROPALE_ADDON)) {
            $file = $conf->global->PROPALE_ADDON . ".php";

            // Chargement de la classe de numerotation
            $classname = $conf->global->PROPALE_ADDON;
            require_once $dir . $file;

            $obj = new $classname();

            $numref = "";
            $numref = $obj->getNextValue($soc, $this);

            if ($numref != "") {
                return $numref;
            } else {
                $this->error = $obj->error;
                //dol_print_error($db,"Propale::getNextNumRef ".$obj->error);
                return "";
            }
        } else {
            $langs->load("errors");
            print $langs->trans("Error") . " " . $langs->trans("ErrorModuleSetupNotComplete");
            return "";
        }
    }

    /**
     * 	Return clicable link of object (with eventually picto)
     *
     * 	@param      int		$withpicto		Add picto into link
     * 	@param      string	$option			Where point the link ('compta', 'expedition', 'document', ...)
     * 	@param      string	$get_params    	Parametres added to url
     * 	@return     string          		String with URL
     */
    function getNomUrl($withpicto = 0, $option = '', $get_params = '') {
        global $langs;

        $result = '';
        if ($option == '') {
            $lien = '<a href="' . DOL_URL_ROOT . '/propal/propal.php?id=' . $this->id . $get_params . '">';
        }
        if ($option == 'compta') {   // deprecated
            $lien = '<a href="' . DOL_URL_ROOT . '/comm/propal.php?id=' . $this->id . $get_params . '">';
        }
        if ($option == 'expedition') {
            $lien = '<a href="' . DOL_URL_ROOT . '/expedition/propal.php?id=' . $this->id . $get_params . '">';
        }
        if ($option == 'document') {
            $lien = '<a href="' . DOL_URL_ROOT . '/propal/document.php?id=' . $this->id . $get_params . '">';
        }
        $lienfin = '</a>';

        $picto = 'propal';
        $label = $langs->trans("ShowPropal") . ': ' . $this->ref;

        if ($withpicto)
            $result.=($lien . img_object($label, $picto) . $lienfin);
        if ($withpicto && $withpicto != 2)
            $result.=' ';
        $result.=$lien . $this->ref . $lienfin;
        return $result;
    }

    /**
     * 	Retrieve an array of propal lines
     *
     * 	@return	int	<0 if ko, >0 if ok
     */
    function getLinesArray() {
        $this->lines = array();
        $result = $this->getView("linesPerPropal", array("key" => $this->id));
        foreach ($result->rows as $res) {
            $l = new PropalLine($db);
            $l->fetch($res->value->_id);
            $this->lines[] = $l;
        }
        return 1;
//        $sql = 'SELECT pt.rowid, pt.label as custom_label, pt.description, pt.fk_product, pt.fk_remise_except,';
//        $sql.= ' pt.qty, pt.tva_tx, pt.remise_percent, pt.subprice, pt.info_bits,';
//        $sql.= ' pt.total_ht, pt.total_tva, pt.total_ttc, pt.fk_product_fournisseur_price as fk_fournprice, pt.buy_price_ht as pa_ht, pt.special_code, pt.localtax1_tx, pt.localtax2_tx,';
//        $sql.= ' pt.date_start, pt.date_end, pt.product_type, pt.rang, pt.fk_parent_line,';
//        $sql.= ' p.label as product_label, p.ref, p.fk_product_type, p.rowid as prodid,';
//        $sql.= ' p.description as product_desc';
//        $sql.= ' FROM ' . MAIN_DB_PREFIX . 'propaldet as pt';
//        $sql.= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p ON pt.fk_product=p.rowid';
//        $sql.= ' WHERE pt.fk_propal = ' . $this->id;
//        $sql.= ' ORDER BY pt.rang ASC, pt.rowid';
//
//        $resql = $this->db->query($sql);
//        if ($resql) {
//            $num = $this->db->num_rows($resql);
//            $i = 0;
//
//            while ($i < $num) {
//                $obj = $this->db->fetch_object($resql);
//
//                $this->lines[$i] = new stdClass();
//                $this->lines[$i]->id = $obj->rowid; // for backward compatibility
//                $this->lines[$i]->rowid = $obj->rowid;
//                $this->lines[$i]->label = $obj->custom_label;
//                $this->lines[$i]->description = $obj->description;
//                $this->lines[$i]->fk_product = $obj->fk_product;
//                $this->lines[$i]->ref = $obj->ref;
//                $this->lines[$i]->product_label = $obj->product_label;
//                $this->lines[$i]->product_desc = $obj->product_desc;
//                $this->lines[$i]->fk_product_type = $obj->fk_product_type;  // deprecated
//                $this->lines[$i]->product_type = $obj->product_type;
//                $this->lines[$i]->qty = $obj->qty;
//                $this->lines[$i]->subprice = $obj->subprice;
//                $this->lines[$i]->fk_remise_except = $obj->fk_remise_except;
//                $this->lines[$i]->remise_percent = $obj->remise_percent;
//                $this->lines[$i]->tva_tx = $obj->tva_tx;
//                $this->lines[$i]->info_bits = $obj->info_bits;
//                $this->lines[$i]->total_ht = $obj->total_ht;
//                $this->lines[$i]->total_tva = $obj->total_tva;
//                $this->lines[$i]->total_ttc = $obj->total_ttc;
//                $this->lines[$i]->fk_fournprice = $obj->fk_fournprice;
//                $marginInfos = getMarginInfos($obj->subprice, $obj->remise_percent, $obj->tva_tx, $obj->localtax1_tx, $obj->localtax2_tx, $this->lines[$i]->fk_fournprice, $obj->pa_ht);
//                $this->lines[$i]->pa_ht = $marginInfos[0];
//                $this->lines[$i]->marge_tx = $marginInfos[1];
//                $this->lines[$i]->marque_tx = $marginInfos[2];
//                $this->lines[$i]->fk_parent_line = $obj->fk_parent_line;
//                $this->lines[$i]->special_code = $obj->special_code;
//                $this->lines[$i]->rang = $obj->rang;
//                $this->lines[$i]->date_start = $this->db->jdate($obj->date_start);
//                $this->lines[$i]->date_end = $this->db->jdate($obj->date_end);
//
//                $i++;
//            }
//            $this->db->free($resql);
//
//            return 1;
//        } else {
//            $this->error = $this->db->error();
//            dol_syslog(get_class($this) . "::getLinesArray Error sql=$sql, error=" . $this->error, LOG_ERR);
//            return -1;
//        }
    }

    public function getExtraFieldLabel($field) {
        global $langs;
        return $langs->trans($this->fk_extrafields->fields->{$field}->values->{$this->$field}->label);
    }

    function update_note_public($note_public) {
        $this->note_public = $note_public;
        $this->record();
        return 1;
    }

    /**
     * 		Set last model used by doc generator
     *
     * 		@param		User	$user		User object that make change
     * 		@param		string	$modelpdf	Modele name
     * 		@return		int					<0 if KO, >0 if OK
     */
    function setDocModel($user, $modelpdf) {
        $this->modelpdf = $modelpdf;
        $this->record();
        return 1;
    }

    function setStatut($status) {
        $this->Status = $status;
        $this->record();
        return 1;
    }

    public function getLinkedObject(){

        $objects = array();

        /* No variable $linked_objects ?
         *
         *
        // Object stored in $this->linked_objects;
        foreach ($this->linked_objects as $obj) {
            switch ($obj->type) {
                case 'commande':
                    $classname = 'Commande';
                    dol_include_once('commande/class/commande.class.php');
                    break;
            }
            $tmp = new $classname($this->db);
            $tmp->fetch($obj->id);
            $objects[$obj->type][] = $tmp;
        }
         *
         */

        // Objects that refer current propal in their $linked_objects variable.
        $res = $this->getView('listLinkedObjects', array('key' => $this->id));
        if (count($res->rows) > 0) {
            foreach( $res->rows as $r) {
                $classname = $r->value->class;
                if ($classname == 'Commande')
                    require_once(DOL_DOCUMENT_ROOT . '/commande/class/commande.class.php');
                $obj = new $classname($this->db);
                $obj->fetch($r->value->id);
                $objects[strtolower($classname)][] = $obj;
            }
        }

        return $objects;

    }

    public function printLinkedObjects(){

        global $langs;

        $objects = $this->getLinkedObject();

        // Displaying linked orders
        if (isset($objects['commande'])) {
            $this->printLinkedObjectsType('commande', $objects['commande']);
        }

    }

    public function printLinkedObjectsType($type, $data){

        global $langs;

        $title = 'LinkedObjects';
        if ($type == 'commande')
            $title = 'LinkedOrders';

        print start_box($langs->trans($title), "six", $this->fk_extrafields->ico, false);
        print '<table id="tablelines" class="noborder" width="100%">';
        print '<tr>';
        print '<th align="left">' . $langs->trans('Ref') . '</th>';
        print '<th align="left">' . $langs->trans('Date') . '</th>';
        print '<th align="left">' . $langs->trans('PriceHT') . '</th>';
        print '<th align="left">' . $langs->trans('Status') . '</th>';
        print '</tr>';
        foreach ($data as $p) {
            print '<tr>';
            print '<td>' . $p->getNomUrl(1) . '</td>';
            print '<td>' . dol_print_date($p->date) . '</td>';
            print '<td>' . price($p->total_ht) . '</td>';
            print '<td>' . $p->getExtraFieldLabel('Status') . '</td>';
            print '</tr>';
        }
        print '</table>';
        print end_box();

    }

    public function showLinkedObjects() {
        global $langs;

         print start_box($langs->trans("LinkedObjects"), "six", $this->fk_extrafields->ico, false);
           print '<table class="display dt_act" id="listlinkedobjects" >';
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
        print $langs->trans("Ref");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "ref";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
//        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("ref", "url");
        $i++;
        print'<th class="essential">';
        print $langs->trans('Date');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "date";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("date", "date");
        $i++;
        print'<th class="essential">';
        print $langs->trans('PriceHT');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "total_ht";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("total_ht", "price");
        $i++;
        print'<th class="essential">';
        print $langs->trans('Status');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "Status";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("Status", "status");

        $i++;
        print '</tr>';
        print '</thead>';
        print'<tfoot>';
        print'</tfoot>';
        print'<tbody>';
        print'</tbody>';
        print "</table>";

        $obj->iDisplayLength = $max;
        $obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listdatatables.php?json=listLinkedObjects&class=" . get_class($this) . "&key=" . $this->id;
        $this->datatablesCreate($obj, "listlinkedobjects", true);
        print end_box();

}

    public function addInPlace($obj){

        global $user;

        // Generating next ref
        $this->ref = $obj->ref = $this->getNextNumRef();

        // Converting date to timestamp
        $date = explode('/', $this->date);
        $this->date = $obj->date = dol_mktime(0, 0, 0, $date[1], $date[0], $date[2]);

        // Setting author of propal
        $this->author = new stdClass();
        $this->author->id = $user->id;
        $this->author->name = $user->login;

    }

    public function deleteInPlace($obj){

        global $user;

        // Delete lines of Propal
        $lines = $this->getView('linesPerPropal', array('key' => $this->id));
        foreach ($lines->rows as $l) {
            $this->deleteline($l->value->_id);
        }

    }

    public function fetch_thirdparty(){

        $thirdparty = new Societe($this->db);
        $thirdparty->fetch($this->client->id);
        $this->thirdparty = $thirdparty;

    }

    public function show($id) {

        global $langs;

        require_once(DOL_DOCUMENT_ROOT . '/propal/class/propal.class.php');
        $propal = new Propal($this->db);

        //print start_box($langs->trans("Proposals"), "twelve", $this->fk_extrafields->ico);
        //print column_start("six");
		print '<dt>';
		print show_title($langs->trans("Proposals"), "icon-chat no-margin-bottom");
		print '</dt>';
		
		print '<dd><div class="with-mid-padding">';
		print '<table class="display dt_act" id="listpropals" >';
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
        print $langs->trans("Ref");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "ref";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->fnRender = $propal->datatablesFnRender("ref", "url");
        $i++;
        print'<th class="essential">';
        print $langs->trans('Date');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "date";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $propal->datatablesFnRender("date", "date");
        $i++;
        print'<th class="essential">';
        print $langs->trans('PriceHT');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "total_ht";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $propal->datatablesFnRender("total_ht", "price");
        $i++;
        print'<th class="essential">';
        print $langs->trans('Status');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "Status";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $propal->datatablesFnRender("Status", "status");

        $i++;
        print '</tr>';
        print '</thead>';
        print'<tfoot>';
        print'</tfoot>';
        print'<tbody>';
        print'</tbody>';
        print "</table>";

        $obj->iDisplayLength = $max;
        $obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listdatatables.php?json=listBySociete&class=" . get_class($this) . "&key=" . $id;
        $this->datatablesCreate($obj, "listpropals", true);
        //print column_end();
		print '</div></dd>';
    }

}

/**
 * 	\class      PropalLine
 * 	\brief      Class to manage commercial proposal lines
 */
class PropalLine extends nosqlDocument {

    public $class = "PropalLine";
    var $db;
    var $error;
    var $oldline;
    // From llx_propaldet
    var $rowid;
    var $fk_propal;
    var $fk_parent_line;
    var $desc;           // Description ligne (deprecated)
    public $description;           // Description ligne
    var $fk_product;  // Id produit predefini
    var $product_type = 0; // Type 0 = product, 1 = Service
    var $qty;
    var $tva_tx;
    var $subprice;
    var $remise_percent;
    var $fk_remise_except;
    var $rang = 0;
    var $fk_fournprice;
    var $pa_ht;
    var $marge_tx;
    var $marque_tx;
    var $special_code; // Liste d'options non cumulabels:
    // 1: frais de port
    // 2: ecotaxe
    // 3: ??
    var $info_bits = 0; // Liste d'options cumulables:
    // Bit 0: 	0 si TVA normal - 1 si TVA NPR
    // Bit 1:	0 ligne normale - 1 si ligne de remise fixe
    var $total_ht;   // Total HT  de la ligne toute quantite et incluant la remise ligne
    var $total_tva;   // Total TVA  de la ligne toute quantite et incluant la remise ligne
    var $total_ttc;   // Total TTC de la ligne toute quantite et incluant la remise ligne
    // Ne plus utiliser
    var $remise;
    var $price;
    // From llx_product
    var $ref;      // Reference produit
    var $libelle;       // Label produit
    var $product_desc;  // Description produit
    var $localtax1_tx;
    var $localtax2_tx;
    var $total_localtax1;
    var $total_localtax2;
    var $skip_update_total; // Skip update price total for special lines

    /**
     * 	Class line Contructor
     *
     * 	@param	DoliDB	$db	Database handler
     */

    function __construct($db) {
        parent::__construct($db);
    }

    /**
     * 	Retrieve the propal line object
     *
     * 	@param	int		$rowid		Propal line id
     * 	@return	int					<0 if KO, >0 if OK
     */
    function fetch($rowid) {
        return parent::fetch($rowid);
//        $sql = 'SELECT pd.rowid, pd.fk_propal, pd.fk_parent_line, pd.fk_product, pd.label as custom_label, pd.description, pd.price, pd.qty, pd.tva_tx,';
//        $sql.= ' pd.remise, pd.remise_percent, pd.fk_remise_except, pd.subprice,';
//        $sql.= ' pd.info_bits, pd.total_ht, pd.total_tva, pd.total_ttc, pd.fk_product_fournisseur_price as fk_fournprice, pd.buy_price_ht as pa_ht, pd.special_code, pd.rang,';
//        $sql.= ' pd.localtax1_tx, pd.localtax2_tx, pd.total_localtax1, pd.total_localtax2,';
//        $sql.= ' p.ref as product_ref, p.label as product_label, p.description as product_desc';
//        $sql.= ' FROM ' . MAIN_DB_PREFIX . 'propaldet as pd';
//        $sql.= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'product as p ON pd.fk_product = p.rowid';
//        $sql.= ' WHERE pd.rowid = ' . $rowid;
//
//        $result = $this->db->query($sql);
//        if ($result) {
//            $objp = $this->db->fetch_object($result);
//
//            $this->rowid = $objp->rowid;
//            $this->fk_propal = $objp->fk_propal;
//            $this->fk_parent_line = $objp->fk_parent_line;
//            $this->label = $objp->custom_label;
//            $this->desc = $objp->description;
//            $this->qty = $objp->qty;
//            $this->price = $objp->price;  // deprecated
//            $this->subprice = $objp->subprice;
//            $this->tva_tx = $objp->tva_tx;
//            $this->remise = $objp->remise;
//            $this->remise_percent = $objp->remise_percent;
//            $this->fk_remise_except = $objp->fk_remise_except;
//            $this->fk_product = $objp->fk_product;
//            $this->info_bits = $objp->info_bits;
//
//            $this->total_ht = $objp->total_ht;
//            $this->total_tva = $objp->total_tva;
//            $this->total_ttc = $objp->total_ttc;
//
//            $this->fk_fournprice = $objp->fk_fournprice;
//
//            $marginInfos = getMarginInfos($objp->subprice, $objp->remise_percent, $objp->tva_tx, $objp->localtax1_tx, $objp->localtax2_tx, $this->fk_fournprice, $objp->pa_ht);
//            $this->pa_ht = $marginInfos[0];
//            $this->marge_tx = $marginInfos[1];
//            $this->marque_tx = $marginInfos[2];
//
//            $this->special_code = $objp->special_code;
//            $this->rang = $objp->rang;
//
//            $this->ref = $objp->product_ref;      // deprecated
//            $this->product_ref = $objp->product_ref;
//            $this->libelle = $objp->product_label;  // deprecated
//            $this->product_label = $objp->product_label;
//            $this->product_desc = $objp->product_desc;
//
//            $this->db->free($result);
//        } else {
//            dol_print_error($this->db);
//        }
    }

    /**
     *  Insert object line propal in database
     *
     * 	@param		int		$notrigger		1=Does not execute triggers, 0= execuete triggers
     * 	@return		int						<0 if KO, >0 if OK
     */
    function insert($notrigger = 0) {
        global $conf, $langs, $user;

        $error = 0;

        dol_syslog("PropalLine::insert rang=" . $this->rang);

        // Clean parameters
        if (empty($this->tva_tx))
            $this->tva_tx = 0;
        if (empty($this->localtax1_tx))
            $this->localtax1_tx = 0;
        if (empty($this->localtax2_tx))
            $this->localtax2_tx = 0;
        if (empty($this->total_localtax1))
            $this->total_localtax1 = 0;
        if (empty($this->total_localtax2))
            $this->total_localtax2 = 0;
        if (empty($this->rang))
            $this->rang = 0;
        if (empty($this->remise))
            $this->remise = 0;
        if (empty($this->remise_percent))
            $this->remise_percent = 0;
        if (empty($this->info_bits))
            $this->info_bits = 0;
        if (empty($this->special_code))
            $this->special_code = 0;
        if (empty($this->fk_parent_line))
            $this->fk_parent_line = 0;

        if (empty($this->pa_ht))
            $this->pa_ht = 0;

        // si prix d'achat non renseigne et utilise pour calcul des marges alors prix achat = prix vente
        if ($this->pa_ht == 0) {
            if ($this->subprice > 0 && (isset($conf->global->ForceBuyingPriceIfNull) && $conf->global->ForceBuyingPriceIfNull == 1))
                $this->pa_ht = $this->subprice * (1 - $this->remise_percent / 100);
        }

        // Check parameters
        if ($this->product_type < 0)
            return -1;

//        $this->db->begin();
//
//        // Insert line into database
//        $sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'propaldet';
//        $sql.= ' (fk_propal, fk_parent_line, label, description, fk_product, product_type, fk_remise_except, qty, tva_tx, localtax1_tx, localtax2_tx,';
//        $sql.= ' subprice, remise_percent, ';
//        $sql.= ' info_bits, ';
//        $sql.= ' total_ht, total_tva, total_localtax1, total_localtax2, total_ttc, fk_product_fournisseur_price, buy_price_ht, special_code, rang)';
//        $sql.= " VALUES (" . $this->fk_propal . ",";
//        $sql.= " " . ($this->fk_parent_line > 0 ? "'" . $this->fk_parent_line . "'" : "null") . ",";
//        $sql.= " " . (!empty($this->label) ? "'" . $this->db->escape($this->label) . "'" : "null") . ",";
//        $sql.= " '" . $this->db->escape($this->desc) . "',";
//        $sql.= " " . ($this->fk_product ? "'" . $this->fk_product . "'" : "null") . ",";
//        $sql.= " '" . $this->product_type . "',";
//        $sql.= " " . ($this->fk_remise_except ? "'" . $this->fk_remise_except . "'" : "null") . ",";
//        $sql.= " " . price2num($this->qty) . ",";
//        $sql.= " " . price2num($this->tva_tx) . ",";
//        $sql.= " " . price2num($this->localtax1_tx) . ",";
//        $sql.= " " . price2num($this->localtax2_tx) . ",";
//        $sql.= " " . ($this->subprice ? price2num($this->subprice) : 'null') . ",";
//        $sql.= " " . price2num($this->remise_percent) . ",";
//        $sql.= " '" . $this->info_bits . "',";
//        $sql.= " " . price2num($this->total_ht) . ",";
//        $sql.= " " . price2num($this->total_tva) . ",";
//        $sql.= " " . price2num($this->total_localtax1) . ",";
//        $sql.= " " . price2num($this->total_localtax2) . ",";
//        $sql.= " " . price2num($this->total_ttc) . ",";
//        $sql.= " " . (isset($this->fk_fournprice) ? "'" . $this->fk_fournprice . "'" : "null") . ",";
//        $sql.= " " . (isset($this->pa_ht) ? "'" . price2num($this->pa_ht) . "'" : "null") . ",";
//        $sql.= ' ' . $this->special_code . ',';
//        $sql.= ' ' . $this->rang;
//        $sql.= ')';
//
//        dol_syslog(get_class($this) . '::insert sql=' . $sql, LOG_DEBUG);
//        $resql = $this->db->query($sql);
//        if ($resql) {
//            $this->rowid = $this->db->last_insert_id(MAIN_DB_PREFIX . 'propaldet');
        $this->record();
        if (!$notrigger) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('LINEPROPAL_INSERT', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }

//            $this->db->commit();
        return 1;
//        } else {
//            $this->error = $this->db->error() . " sql=" . $sql;
//            dol_syslog(get_class($this) . '::insert Error ' . $this->error, LOG_ERR);
//            $this->db->rollback();
//            return -1;
//        }
    }

    /**
     * 	Delete line in database
     *
     * 	@return	 int  <0 if ko, >0 if ok
     */
    function delete() {
        global $conf, $langs, $user;

        $error = 0;
        $this->deleteDoc();
//        $this->db->begin();
//
//        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "propaldet WHERE rowid = " . $this->rowid;
//        dol_syslog("PropalLine::delete sql=" . $sql, LOG_DEBUG);
//        if ($this->db->query($sql)) {
        // Appel des triggers
        include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
        $interface = new Interfaces($this->db);
        $result = $interface->run_triggers('LINEPROPAL_DELETE', $this, $user, $langs, $conf);
        if ($result < 0) {
            $error++;
            $this->errors = $interface->errors;
        }
        // Fin appel triggers
//            $this->db->commit();

        return 1;
//        } else {
//            $this->error = $this->db->error() . " sql=" . $sql;
//            dol_syslog("PropalLine::delete Error " . $this->error, LOG_ERR);
//            $this->db->rollback();
//            return -1;
//        }
    }

    /**
     * 	Update propal line object into DB
     *
     * 	@param 	int		$notrigger	1=Does not execute triggers, 0= execuete triggers
     * 	@return	int					<0 if ko, >0 if ok
     */
    function update($notrigger = 0) {
        global $conf, $langs, $user;

        $error = 0;

        // Clean parameters
        if (empty($this->tva_tx))
            $this->tva_tx = 0;
        if (empty($this->localtax1_tx))
            $this->localtax1_tx = 0;
        if (empty($this->localtax2_tx))
            $this->localtax2_tx = 0;
        if (empty($this->total_localtax1))
            $this->total_localtax1 = 0;
        if (empty($this->total_localtax2))
            $this->total_localtax2 = 0;
        if (empty($this->marque_tx))
            $this->marque_tx = 0;
        if (empty($this->marge_tx))
            $this->marge_tx = 0;
        if (empty($this->remise))
            $this->remise = 0;
        if (empty($this->remise_percent))
            $this->remise_percent = 0;
        if (empty($this->info_bits))
            $this->info_bits = 0;
        if (empty($this->special_code))
            $this->special_code = 0;
        if (empty($this->fk_parent_line))
            $this->fk_parent_line = 0;

        if (empty($this->pa_ht))
            $this->pa_ht = 0;

        // si prix d'achat non renseigne et utilise pour calcul des marges alors prix achat = prix vente
        if ($this->pa_ht == 0) {
            if ($this->subprice > 0 && (isset($conf->global->ForceBuyingPriceIfNull) && $conf->global->ForceBuyingPriceIfNull == 1))
                $this->pa_ht = $this->subprice * (1 - $this->remise_percent / 100);
        }

        $this->record();
//        $this->db->begin();
//
//        // Mise a jour ligne en base
//        $sql = "UPDATE " . MAIN_DB_PREFIX . "propaldet SET";
//        $sql.= " description='" . $this->db->escape($this->desc) . "'";
//        $sql.= " , label=" . (!empty($this->label) ? "'" . $this->db->escape($this->label) . "'" : "null");
//        $sql.= " , tva_tx='" . price2num($this->tva_tx) . "'";
//        $sql.= " , localtax1_tx=" . price2num($this->localtax1_tx);
//        $sql.= " , localtax2_tx=" . price2num($this->localtax2_tx);
//        $sql.= " , qty='" . price2num($this->qty) . "'";
//        $sql.= " , subprice=" . price2num($this->subprice) . "";
//        $sql.= " , remise_percent=" . price2num($this->remise_percent) . "";
//        $sql.= " , price=" . price2num($this->price) . "";     // TODO A virer
//        $sql.= " , remise=" . price2num($this->remise) . "";    // TODO A virer
//        $sql.= " , info_bits='" . $this->info_bits . "'";
//        if (empty($this->skip_update_total)) {
//            $sql.= " , total_ht=" . price2num($this->total_ht) . "";
//            $sql.= " , total_tva=" . price2num($this->total_tva) . "";
//            $sql.= " , total_ttc=" . price2num($this->total_ttc) . "";
//            $sql.= " , total_localtax1=" . price2num($this->total_localtax1) . "";
//            $sql.= " , total_localtax2=" . price2num($this->total_localtax2) . "";
//        }
//        $sql.= " , fk_product_fournisseur_price='" . $this->fk_fournprice . "'";
//        $sql.= " , buy_price_ht='" . price2num($this->pa_ht) . "'";
//        $sql.= " , info_bits=" . $this->info_bits;
//        if (strlen($this->special_code))
//            $sql.= " , special_code=" . $this->special_code;
//        $sql.= " , fk_parent_line=" . ($this->fk_parent_line > 0 ? $this->fk_parent_line : "null");
//        if (!empty($this->rang))
//            $sql.= ", rang=" . $this->rang;
//        $sql.= " WHERE rowid = " . $this->rowid;
//
//        dol_syslog(get_class($this) . "::update sql=" . $sql, LOG_DEBUG);
//        $resql = $this->db->query($sql);
//        if ($resql) {
        if (!$notrigger) {
            // Appel des triggers
            include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('LINEPROPAL_UPDATE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }

//            $this->db->commit();
        return 1;
//        } else {
//            $this->error = $this->db->error();
//            dol_syslog(get_class($this) . "::update Error " . $this->error, LOG_ERR);
//            $this->db->rollback();
//            return -2;
//        }
    }

    /**
     * 	Update DB line fields total_xxx
     * 	Used by migration
     *
     * 	@return		int		<0 if ko, >0 if ok
     */
    function update_total() {
        $this->db->begin();

        // Mise a jour ligne en base
        $sql = "UPDATE " . MAIN_DB_PREFIX . "propaldet SET";
        $sql.= " total_ht=" . price2num($this->total_ht, 'MT') . "";
        $sql.= ",total_tva=" . price2num($this->total_tva, 'MT') . "";
        $sql.= ",total_ttc=" . price2num($this->total_ttc, 'MT') . "";
        $sql.= " WHERE rowid = " . $this->rowid;

        dol_syslog("PropalLine::update_total sql=$sql");

        $resql = $this->db->query($sql);
        if ($resql) {
            $this->db->commit();
            return 1;
        } else {
            $this->error = $this->db->error();
            dol_syslog("PropalLine::update_total Error " . $this->error, LOG_ERR);
            $this->db->rollback();
            return -2;
        }
    }

}

?>
