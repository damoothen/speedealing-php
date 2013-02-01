<?php
/* Copyright (C) 2002-2004 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011	   Juanjo Menent        <jmenent@2byte.es>
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

require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';

/**     \class      ActionComm
 * 	    \brief      Class to manage agenda events (actions)
 */
class Planning extends nosqlDocument {

    var $id;
    var $notes;          // Description
    var $societe;  // Company linked to action (optionnal)
    var $contact;  // Contact linked tot action (optionnal)
    var $Status = "TODO"; // Status of the action

    /**
     *      Constructor
     *
     *      @param		DoliDB		$db      Database handler
     */

    function __construct($db) {
        parent::__construct($db);

        $this->fk_extrafields = new ExtraFields($db);
        $this->fk_extrafields->fetch(get_class($this));

        $this->Status = "TODO";
        $this->societe = new stdClass();
        $this->contact = new stdClass();
    }

    /**
     *    Load object from database
     *
     *    @param	int		$id     Id of action to get
     *    @return	int				<0 if KO, >0 if OK
     */
    function fetch($id) {
        global $langs;

        try {
            $this->load($id);
            return 1;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return -1;
        }
    }

    /**
     *    Delete event from database
     *
     *    @param    int		$notrigger		1 = disable triggers, 0 = enable triggers
     *    @return   int 					<0 if KO, >0 if OK
     */
    function delete($notrigger = 0) {
        global $user, $langs, $conf;

        $error = 0;
        $this->deleteDoc();

        if (!$notrigger) {
            // Appel des triggers
            include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
            $interface = new Interfaces($this->db);
            $result = $interface->run_triggers('ACTION_DELETE', $this, $user, $langs, $conf);
            if ($result < 0) {
                $error++;
                $this->errors = $interface->errors;
            }
            // Fin appel triggers
        }

        if (!$error) {
            return 1;
        } else {
            return -2;
        }
    }

    /**
     *      Load indicators for dashboard (this->nbtodo and this->nbtodolate)
     *
     *      @param	User	$user   Objet user
     *      @return int     		<0 if KO, >0 if OK
     */
    function load_board($user) {
        global $conf, $user;

        $now = dol_now();

        $this->nbtodo = $this->nbtodolate = 0;
        $sql = "SELECT a.id, a.datep as dp";
        $sql.= " FROM (" . MAIN_DB_PREFIX . "actioncomm as a";
        if (!$user->rights->societe->client->voir && !$user->societe_id)
            $sql.= ", " . MAIN_DB_PREFIX . "societe_commerciaux as sc";
        $sql.= ")";
        $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s ON a.fk_soc = s.rowid";
        $sql.= " WHERE a.percent >= 0 AND a.percent < 100";
        $sql.= " AND a.entity = " . $conf->entity;
        if (!$user->rights->societe->client->voir && !$user->societe_id)
            $sql.= " AND a.fk_soc = sc.fk_soc AND sc.fk_user = " . $user->id;
        if ($user->societe_id)
            $sql.=" AND a.fk_soc = " . $user->societe_id;
        //print $sql;

        $resql = $this->db->query($sql);
        if ($resql) {
            while ($obj = $this->db->fetch_object($resql)) {
                $this->nbtodo++;
                if (isset($obj->dp) && $this->db->jdate($obj->dp) < ($now - $conf->actions->warning_delay))
                    $this->nbtodolate++;
            }
            return 1;
        }
        else {
            $this->error = $this->db->error();
            return -1;
        }
    }

    /**
     *      Charge les informations d'ordre info dans l'objet facture
     *
     *      @param	int		$id       	Id de la facture a charger
     * 		@return	void
     */
    function info($id) {
        $sql = 'SELECT ';
        $sql.= ' a.id,';
        $sql.= ' datec,';
        $sql.= ' tms as datem,';
        $sql.= ' fk_user_author,';
        $sql.= ' fk_user_mod';
        $sql.= ' FROM ' . MAIN_DB_PREFIX . 'actioncomm as a';
        $sql.= ' WHERE a.id = ' . $id;

        dol_syslog(get_class($this) . "::info sql=" . $sql);
        $result = $this->db->query($sql);
        if ($result) {
            if ($this->db->num_rows($result)) {
                $obj = $this->db->fetch_object($result);
                $this->id = $obj->id;
                if ($obj->fk_user_author) {
                    $cuser = new User($this->db);
                    $cuser->fetch($obj->fk_user_author);
                    $this->user_creation = $cuser;
                }
                if ($obj->fk_user_mod) {
                    $muser = new User($this->db);
                    $muser->fetch($obj->fk_user_mod);
                    $this->user_modification = $muser;
                }

                $this->date_creation = $this->db->jdate($obj->datec);
                $this->date_modification = $this->db->jdate($obj->datem);
            }
            $this->db->free($result);
        } else {
            dol_print_error($this->db);
        }
    }

    /**
     *    	Renvoie nom clicable (avec eventuellement le picto)
     *      Utilise $this->id, $this->code et $this->label
     *
     * 		@param	int		$withpicto			0=Pas de picto, 1=Inclut le picto dans le lien, 2=Picto seul
     * 		@param	int		$maxlength			Nombre de caracteres max dans libelle
     * 		@param	string	$classname			Force style class on a link
     * 		@param	string	$option				''=Link to action,'birthday'=Link to contact
     * 		@param	int		$overwritepicto		1=Overwrite picto
     * 		@return	string						Chaine avec URL
     */
    function getNomUrl($withpicto = 0, $maxlength = 0, $classname = '', $option = '', $overwritepicto = '') {
        global $langs;

        $result = '';
        if ($option == 'birthday')
            $lien = '<a ' . ($classname ? 'class="' . $classname . '" ' : '') . 'href="' . DOL_URL_ROOT . '/contact/perso.php?id=' . $this->id . '">';
        else
            $lien = '<a ' . ($classname ? 'class="' . $classname . '" ' : '') . 'href="' . DOL_URL_ROOT . '/comm/action/fiche.php?id=' . $this->id . '">';
        $lienfin = '</a>';
        //print $this->libelle;
        if ($withpicto == 2) {
            $libelle = $langs->trans("Action" . $this->type_code);
            $libelleshort = '';
        } else if (empty($this->libelle)) {
            $libelle = $langs->trans("Action" . $this->type_code);
            $libelleshort = $langs->trans("Action" . $this->type_code, '', '', '', '', $maxlength);
        } else {
            $libelle = $this->libelle;
            $libelleshort = dol_trunc($this->libelle, $maxlength);
        }

        if ($withpicto) {
            $libelle.=(($this->type_code && $libelle != $langs->trans("Action" . $this->type_code) && $langs->trans("Action" . $this->type_code) != "Action" . $this->type_code) ? ' (' . $langs->trans("Action" . $this->type_code) . ')' : '');
            $result.=$lien . img_object($langs->trans("ShowAction") . ': ' . $libelle, ($overwritepicto ? $overwritepicto : 'action')) . $lienfin;
        }
        if ($withpicto == 1)
            $result.=' ';
        $result.=$lien . $libelleshort . $lienfin;
        return $result;
    }

    /*
     * Ajouter une tache automatisé suite a une action. Exemple validation d'une facture, création d'une commande, ...
     * param    type
     * param
     *
     */

    function addAutoTask($type, $label, $socid, $leadid, $projetid, $contactid = '') {
        global $user;

        $now = dol_now();

        $this->fk_lead = $leadid;
        $this->fk_project = $projectid;
        $this->label = $label;
        $this->type_code = $type;
        $this->datep = $now;
        $this->datef = $now;
        $this->societe->id = $socid;
        $this->contact = $contactid;
        $this->percentage = 100;
        $this->userdone = $user;
        $this->usertodo = $user;
        $this->type = 2;

        $this->add($user);
    }

    /**
     * 		Export events from database into a cal file.
     *
     * 		@param	string		$format			'vcal', 'ical/ics', 'rss'
     * 		@param	string		$type			'event' or 'journal'
     * 		@param	int			$cachedelay		Do not rebuild file if date older than cachedelay seconds
     * 		@param	string		$filename		Force filename
     * 		@param	array		$filters		Array of filters
     * 		@return int     					<0 if error, nb of events in new file if ok
     */
    function build_exportfile($format, $type, $cachedelay, $filename, $filters) {
        global $conf, $langs, $dolibarr_main_url_root, $mysoc;

        require_once (DOL_DOCUMENT_ROOT . "/core/lib/xcal.lib.php");
        require_once (DOL_DOCUMENT_ROOT . "/core/lib/date.lib.php");

        dol_syslog(get_class($this) . "::build_exportfile Build export file format=" . $format . ", type=" . $type . ", cachedelay=" . $cachedelay . ", filename=" . $filename . ", filters size=" . count($filters), LOG_DEBUG);

        // Check parameters
        if (empty($format))
            return -1;

        // Clean parameters
        if (!$filename) {
            $extension = 'vcs';
            if ($format == 'ical')
                $extension = 'ics';
            $filename = $format . '.' . $extension;
        }

        // Create dir and define output file (definitive and temporary)
        $result = dol_mkdir($conf->agenda->dir_temp);
        $outputfile = $conf->agenda->dir_temp . '/' . $filename;

        $result = 0;

        $buildfile = true;
        $login = '';
        $logina = '';
        $logind = '';
        $logint = '';

        $now = dol_now();

        if ($cachedelay) {
            $nowgmt = dol_now();
            include_once(DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php');
            if (dol_filemtime($outputfile) > ($nowgmt - $cachedelay)) {
                dol_syslog(get_class($this) . "::build_exportfile file " . $outputfile . " is not older than now - cachedelay (" . $nowgmt . " - " . $cachedelay . "). Build is canceled");
                $buildfile = false;
            }
        }

        if ($buildfile) {
            // Build event array
            $eventarray = array();

            $sql = "SELECT a.id,";
            $sql.= " a.datep,";  // Start
            $sql.= " a.datep2,"; // End
            $sql.= " a.durationp,";
            $sql.= " a.datec, a.tms as datem,";
            $sql.= " a.note, a.label, a.fk_action as type_id,";
            $sql.= " a.fk_soc,";
            $sql.= " a.fk_user_author, a.fk_user_mod,";
            $sql.= " a.fk_user_action, a.fk_user_done,";
            $sql.= " a.fk_contact, a.percent as percentage,";
            $sql.= " a.fk_element, a.elementtype,";
            $sql.= " a.priority, a.fulldayevent, a.location,";
            $sql.= " u.firstname, u.name,";
            $sql.= " s.nom as socname,";
            $sql.= " c.id as type_id, c.code as type_code, c.libelle";
            $sql.= " FROM (" . MAIN_DB_PREFIX . "c_actioncomm as c, " . MAIN_DB_PREFIX . "actioncomm as a)";
            $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "user as u on u.rowid = a.fk_user_author";
            $sql.= " LEFT JOIN " . MAIN_DB_PREFIX . "societe as s on s.rowid = a.fk_soc";
            $sql.= " WHERE a.fk_action=c.id";
            $sql.= " AND a.entity = " . $conf->entity;
            foreach ($filters as $key => $value) {
                if ($key == 'notolderthan')
                    $sql.=" AND a.datep >= '" . $this->db->idate($now - ($value * 24 * 60 * 60)) . "'";
                if ($key == 'year')
                    $sql.=" AND a.datep BETWEEN '" . $this->db->idate(dol_get_first_day($value, 1)) . "' AND '" . $this->db->idate(dol_get_last_day($value, 12)) . "'";
                if ($key == 'id')
                    $sql.=" AND a.id=" . (is_numeric($value) ? $value : 0);
                if ($key == 'idfrom')
                    $sql.=" AND a.id >= " . (is_numeric($value) ? $value : 0);
                if ($key == 'idto')
                    $sql.=" AND a.id <= " . (is_numeric($value) ? $value : 0);
                if ($key == 'login') {
                    $login = $value;
                    $userforfilter = new User($this->db);
                    $result = $userforfilter->fetch('', $value);
                    $sql.= " AND (";
                    $sql.= " a.fk_user_author = " . $userforfilter->id;
                    $sql.= " OR a.fk_user_action = " . $userforfilter->id;
                    $sql.= " OR a.fk_user_done = " . $userforfilter->id;
                    $sql.= ")";
                }
                if ($key == 'logina') {
                    $logina = $value;
                    $userforfilter = new User($this->db);
                    $result = $userforfilter->fetch('', $value);
                    $sql.= " AND a.fk_user_author = " . $userforfilter->id;
                }
                if ($key == 'logint') {
                    $logint = $value;
                    $userforfilter = new User($this->db);
                    $result = $userforfilter->fetch('', $value);
                    $sql.= " AND a.fk_user_action = " . $userforfilter->id;
                }
                if ($key == 'logind') {
                    $logind = $value;
                    $userforfilter = new User($this->db);
                    $result = $userforfilter->fetch('', $value);
                    $sql.= " AND a.fk_user_done = " . $userforfilter->id;
                }
            }
            $sql.= " AND a.datep IS NOT NULL";  // To exclude corrupted events and avoid errors in lightning/sunbird import
            $sql.= " ORDER by datep";
            //print $sql;exit;

            dol_syslog(get_class($this) . "::build_exportfile select events sql=" . $sql);
            $resql = $this->db->query($sql);
            if ($resql) {
                // Note: Output of sql request is encoded in $conf->file->character_set_client
                while ($obj = $this->db->fetch_object($resql)) {
                    $qualified = true;

                    // 'eid','startdate','duration','enddate','title','summary','category','email','url','desc','author'
                    $event = array();
                    $event['uid'] = 'dolibarragenda-' . $this->db->database_name . '-' . $obj->id . "@" . $_SERVER["SERVER_NAME"];
                    $event['type'] = $type;
                    //$datestart=$obj->datea?$obj->datea:$obj->datep;
                    //$dateend=$obj->datea2?$obj->datea2:$obj->datep2;
                    //$duration=$obj->durationa?$obj->durationa:$obj->durationp;
                    $datestart = $this->db->jdate($obj->datep);
                    //print $datestart.'x'; exit;
                    $dateend = $this->db->jdate($obj->datep2);
                    $duration = $obj->durationp;
                    $event['summary'] = $obj->label . ($obj->socname ? " (" . $obj->socname . ")" : "");
                    $event['desc'] = $obj->notes;
                    $event['startdate'] = $datestart;
                    $event['duration'] = $duration; // Not required with type 'journal'
                    $event['enddate'] = $dateend;  // Not required with type 'journal'
                    $event['author'] = $obj->firstname . ($obj->name ? " " . $obj->name : "");
                    $event['priority'] = $obj->priority;
                    $event['fulldayevent'] = $obj->fulldayevent;
                    $event['location'] = $obj->location;
                    $event['transparency'] = 'TRANSPARENT';  // OPAQUE (busy) or TRANSPARENT (not busy)
                    $event['category'] = $obj->libelle; // libelle type action
                    $urlwithouturlroot = preg_replace('/' . preg_quote(DOL_URL_ROOT, '/') . '$/i', '', $dolibarr_main_url_root);
                    $url = $urlwithouturlroot . DOL_URL_ROOT . '/comm/action/fiche.php?id=' . $obj->id;
                    $event['url'] = $url;
                    $event['created'] = $this->db->jdate($obj->datec);
                    $event['modified'] = $this->db->jdate($obj->datem);

                    if ($qualified && $datestart) {
                        $eventarray[$datestart] = $event;
                    }
                }
            } else {
                $this->error = $this->db->lasterror();
                dol_syslog(get_class($this) . "::build_exportfile " . $this->db->lasterror(), LOG_ERR);
                return -1;
            }

            $langs->load("agenda");

            // Define title and desc
            $more = '';
            if ($login)
                $more = $langs->transnoentities("User") . ' ' . $login;
            if ($logina)
                $more = $langs->transnoentities("ActionsAskedBy") . ' ' . $logina;
            if ($logint)
                $more = $langs->transnoentities("ActionsToDoBy") . ' ' . $logint;
            if ($logind)
                $more = $langs->transnoentities("ActionsDoneBy") . ' ' . $logind;
            if ($more) {
                $title = 'Dolibarr actions ' . $mysoc->name . ' - ' . $more;
                $desc = $more;
                $desc.=' (' . $mysoc->name . ' - built by Dolibarr)';
            } else {
                $title = 'Dolibarr actions ' . $mysoc->name;
                $desc = $langs->transnoentities('ListOfActions');
                $desc.=' (' . $mysoc->name . ' - built by Dolibarr)';
            }

            // Create temp file
            $outputfiletmp = tempnam($conf->agenda->dir_temp, 'tmp');  // Temporary file (allow call of function by different threads
            @chmod($outputfiletmp, octdec($conf->global->MAIN_UMASK));

            // Write file
            if ($format == 'vcal')
                $result = build_calfile($format, $title, $desc, $eventarray, $outputfiletmp);
            if ($format == 'ical')
                $result = build_calfile($format, $title, $desc, $eventarray, $outputfiletmp);
            if ($format == 'rss')
                $result = build_rssfile($format, $title, $desc, $eventarray, $outputfiletmp);

            if ($result >= 0) {
                if (rename($outputfiletmp, $outputfile))
                    $result = 1;
                else {
                    dol_syslog(get_class($this) . "::build_exportfile failed to rename " . $outputfiletmp . " to " . $outputfile, LOG_ERR);
                    dol_delete_file($outputfiletmp, 0, 1);
                    $result = -1;
                }
            } else {
                dol_syslog(get_class($this) . "::build_exportfile build_xxxfile function fails to for format=" . $format . " outputfiletmp=" . $outputfile, LOG_ERR);
                dol_delete_file($outputfiletmp, 0, 1);
                $langs->load("errors");
                $this->error = $langs->trans("ErrorFailToCreateFile", $outputfile);
            }
        }

        return $result;
    }

    /**
     *  Show actions
     *
     *  @param	int		$max		Max nb of records
     *  @return	void
     */
    function show($max = 5, $id = 0) {
        global $langs, $conf, $user, $db, $bc;

        $h = 0;
        $head[$h][0] = "#";
        $head[$h][1] = $langs->trans("StatusActionToDo");
        $head[$h][2] = "TODO";
        $h++;
        $head[$h][0] = "#";
        $head[$h][1] = $langs->trans("StatusActionDone");
        $head[$h][2] = "DONE";

        $langs->load("agenda");

        $titre = $langs->trans("Actions");
        print start_box($titre, "six", "16-Mail.png", false, $head);

        $i = 0;
        $obj = new stdClass();
        $societe = new Societe($this->db);

        /*
         * Barre d'actions
         *
         */

        print '<p class="button-height right">';
        print '<span class="button-group">';
        print '<a class="button compact icon-star" href="' . strtolower(get_class($this)) . '/fiche.php?action=create&socid=' . $id . '&backtopage=' . $_SERVER['PHP_SELF'] . '?id=' . $id . '">' . $langs->trans("NewAction") . '</a>';
        print "</span>";
        print "</p>";

        print '<table class="display dt_act" id="actions_datatable" >';
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
        print $langs->trans("Titre");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "label";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->bSearchable = true;
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("label", "url");
        $i++;
        print'<th class="essential">';
        print $langs->trans('DateEchAction');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "datep";
        $obj->aoColumns[$i]->sClass = "center";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->bUseRendered = false;
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("datep", "date");
        $i++;
        print'<th class="essential">';
        print $langs->trans('Company');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "societe.name";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $obj->aoColumns[$i]->fnRender = $societe->datatablesFnRender("societe.name", "url", array('id' => "societe.id"));
        $i++;
        print'<th class="essential">';
        print $langs->trans('AffectedTo');
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "usertodo.name";
        $obj->aoColumns[$i]->sDefaultContent = "";
        $i++;
        print'<th class="essential">';
        print $langs->trans("Status");
        print'</th>';
        $obj->aoColumns[$i] = new stdClass();
        $obj->aoColumns[$i]->mDataProp = "Status";
        $obj->aoColumns[$i]->sClass = "center";
        $obj->aoColumns[$i]->sDefaultContent = "TODO";
        $obj->aoColumns[$i]->fnRender = $this->datatablesFnRender("Status", "status", array("dateEnd" => "datep"));
        $i++;
        print '</tr>';
        print '</thead>';
        print'<tfoot>';
        print'</tfoot>';
        print'<tbody>';
        print'</tbody>';
        print "</table>";

        $obj->iDisplayLength = $max;
        $obj->aaSorting = array(array(2, 'desc'));
        $obj->sAjaxSource = DOL_URL_ROOT . "/core/ajax/listdatatables.php?json=actionsTODO&class=" . get_class($this) . "&key=" . $id;
        $this->datatablesCreate($obj, "actions_datatable", true);

        foreach ($head as $aRow) {
            ?>
            <script>
                $(document).ready(function() {
                    var js = "var oTable = $('#actions_datatable').dataTable(); oTable.fnReloadAjax(\"<?php echo DOL_URL_ROOT . "/core/ajax/listdatatables.php?json=actions" . $aRow[2] . "&class=" . get_class($this) . "&key=" . $id; ?>\")";
                    $("#<?php echo $aRow[2]; ?>").attr("onclick", js);
                } );
            </script>
            <?php
        }
        print end_box();
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

        $now = dol_now();

        // Initialise parametres
        $this->id = 0;
        $this->specimen = 1;

        $this->type_code = 'AC_OTH';
        $this->label = 'Label of event Specimen';
        $this->datec = $now;
        $this->datem = $now;
        $this->datep = $now;
        $this->datef = $now;
        $this->author = $user;
        $this->usermod = $user;
        $this->fulldayevent = 0;
        $this->punctual = 0;
        $this->percentage = 0;
        $this->location = 'Location';
        $this->priority = 'Priority X';
        $this->notes = 'Note';
    }

    function print_calendar($date) {
        global $db, $langs, $user;

        $nbDaysInMonth = date('t', $date);
        $firstDayTimestamp = dol_mktime(-1, -1, -1, date('n', $date), 1, date('Y', $date));
        $lastDayTimestamp = dol_mktime(23, 59, 59, date('n', $date), $nbDaysInMonth, date('Y', $date));
        $todayTimestamp = dol_mktime(-1, -1, -1, date('n'), date('j'), date('Y'));
        $firstDayOfMonth = date('w', $firstDayTimestamp);

        $object = new Agenda($db);
        $events = $object->getView("listMyTasks", array("startkey" => array($user->id, $firstDayTimestamp), "endkey" => array($user->id, $lastDayTimestamp)));

        print '<table class="calendar fluid large-margin-bottom with-events">';

        // Month an scroll arrows
        print '<caption>';
        print '<span class="cal-prev" >◄</span>';
        print '<a class="cal-next" href="#">►</a>';
        print $langs->trans(date('F', $date)) . ' ' . date('Y', $date);
        print '</caption>';

        // Days names
        print '<thead>';
        print '<tr>';
        print '<th scope="col">Sun</th>';
        print '<th scope="col">Mon</th>';
        print '<th scope="col">Tue</th>';
        print '<th scope="col">Wed</th>';
        print '<th scope="col">Thu</th>';
        print '<th scope="col">Fri</th>';
        print '<th scope="col">Sat</th>';
        print '</tr>';
        print '</thead>';
        print '<tbody>';
        print '<tr>';

        $calendarCounter = 1;
        for ($i = $firstDayOfMonth; $i > 0; $i--, $calendarCounter++) {
            $previousTimestamp = strtotime($i . " day ago", $firstDayTimestamp);
            print '<td class="prev-month"><span class="cal-day">' . date('d', $previousTimestamp) . '</span></td>';
        }

        $cursor = 0;
        for ($i = 1; $i <= $nbDaysInMonth; $i++, $calendarCounter++) {
            $dayTimestamp = dol_mktime(-1, -1, -1, date('n', $date), $i, date('Y', $date));
            if ($calendarCounter > 1 && ($calendarCounter - 1) % 7 == 0)
                print '</tr><tr>';
            print '<td class="' . ((date('w', $dayTimestamp) == 0 || date('w', $dayTimestamp) == 6) ? 'week-end ' : '') . ' ' . (($dayTimestamp == $todayTimestamp) ? 'today ' : '') . '"><span class="cal-day">' . $i . '</span>';
            print '<ul class="cal-events">';

            if (!empty($events->rows[$cursor])) {
                for ($j = 0; $j < count($events->rows); $j++) {
                    if ($events->rows[$cursor]->key[1] >= $dayTimestamp && $events->rows[$cursor]->key[1] < $dayTimestamp + 3600 * 24) {
                        print '<li><a href="agenda/fiche.php?id=' . $events->rows[$cursor]->id . '" >' . "[" . $events->rows[$cursor]->value->societe->name . "] " . $events->rows[$cursor]->value->label . '</a></li>';
                        $cursor++;
                    } else
                        break;
                }
            }

            print '</ul>';
            print '</td>';
        }

        $calendarCounter--;
        $i = 1;
        while ($calendarCounter++ % 7 != 0) {
            print '<td class="next-month"><span class="cal-day">' . $i++ . '</span></td>';
        }

        print '</tr>';

        print '</tbody>';
        print '</table>';
    }

    function print_week($date) {

        global $db, $langs, $user;

        $timestamps = array();
        $dayOfWeek = date('w', $date);
        for ($i = 0, $d = -$dayOfWeek; $i < 7; $i++, $d++) {
            $tmpTimestamp = strtotime($d . " day", $date);
            $timestamps[$i] = array(
                'start' => dol_mktime(0, 0, 0, date('n', $tmpTimestamp), date('j', $tmpTimestamp), date('Y', $tmpTimestamp)),
                'end' => dol_mktime(23, 59, 59, date('n', $tmpTimestamp), date('j', $tmpTimestamp), date('Y', $tmpTimestamp)),
            );
        }

        $object = new Agenda($db);
        $events = $object->getView("listMyTasks", array("startkey" => array($user->id, $timestamps[0]['start']), "endkey" => array($user->id, $timestamps[6]['end'])));

        $styles = array(
            0 => 'left: 0%; right: 85.7143%; margin-left: -1px;',
            1 => 'left: 14.2857%; right: 71.4286%; margin-left: 0px;',
            2 => 'left: 28.5714%; right: 57.1429%; margin-left: 0px;',
            3 => 'left: 42.8571%; right: 42.8571%; margin-left: 0px;',
            4 => 'left: 57.1429%; right: 28.5714%; margin-left: 0px;',
            5 => 'left: 71.4286%; right: 14.2857%; margin-left: 0px;',
            6 => 'left: 85.7143%; right: 0%; margin-left: 0px;'
        );

        $days = array(
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        );

        print '<div class="agenda with-header auto-scroll scrolling-agenda">';
        print '<ul class="agenda-time">
					<li class="from-7 to-8"><span>7 AM</span></li>
					<li class="from-8 to-9"><span>8 AM</span></li>
					<li class="from-9 to-10"><span>9 AM</span></li>
					<li class="from-10 to-11"><span>10 AM</span></li>
					<li class="from-11 to-12"><span>11 AM</span></li>
					<li class="from-12 to-13 blue"><span>12 AM</span></li>
					<li class="from-13 to-14"><span>1 PM</span></li>
					<li class="from-14 to-15"><span>2 PM</span></li>
					<li class="from-15 to-16"><span>3 PM</span></li>
					<li class="from-16 to-17"><span>4 PM</span></li>
					<li class="from-17 to-18"><span>5 PM</span></li>
					<li class="from-18 to-19"><span>6 PM</span></li>
					<li class="from-19 to-20"><span>7 PM</span></li>
					<li class="at-20"><span>8 PM</span></li>
				</ul>';

        print '<div class="agenda-wrapper">';

        $cursor = 0;
        for ($i = 0; $i < 7; $i++) {
            $extraClass = '';
            if ($i == 0)
                $extraClass = 'agenda-visible-first';
            else if ($i == 6)
                $extraClass = 'agenda-visible-last';
            print '<div class="agenda-events agenda-day' . ($i + 1) . ' agenda-visible-column ' . $extraClass . '" style="' . $styles[$i] . '">';
            print '<div class="agenda-header">';
            print $langs->trans($days[$i]);
            print '</div>';

            if (!empty($events->rows[$cursor])) {
                for ($j = 0; $j < count($events->rows); $j++) {
                    if ($events->rows[$cursor]->key[1] >= $timestamps[$i]['start'] && $events->rows[$cursor]->key[1] < $timestamps[$i]['end']) {
                        $dateStart = $events->rows[$cursor]->value->datep;
                        $dateEnd = $events->rows[$cursor]->value->datef;
                        if ($events->rows[$cursor]->value->type_code != 'AC_RDV')
                            $dateEnd = $dateStart + $events->rows[$cursor]->value->durationp;
                        $hourStart = date('G', $dateStart);
                        $hourEnd = date('G', $dateEnd);

                        print '<a class="agenda-event from-' . $hourStart . ' to-' . $hourEnd . ' anthracite-gradient" href="agenda/fiche.php?id=' . $events->rows[$cursor]->id . '">';
                        print '<time>' . $hourStart . 'h - ' . $hourEnd . 'h</time>';
                        if (isset($events->rows[$cursor]->value->societe->name))
                            print "[" . $events->rows[$cursor]->value->societe->name . "] ";
                        print $events->rows[$cursor]->value->label;
                        print '</a>';
                        $cursor++;
                    } else
                        break;
                }
            }

            print '</div>';
        }

        print '</div>';
        print '</div>';
    }

    /*
     * Graph Eisenhower matrix
     *
     */

    function graphEisenhower($json = false) {
        global $user, $conf, $langs;

        $langs->load("companies");

        if ($json) {
            // For Data see viewgraph.php
            $params = array('startkey' => array($user->id, mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"))),
                'endkey' => array($user->id, mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"))));
            $result = $this->getView("list" . $_GET["name"], $params);

            //error_log(print_r($result,true));
            $output = array();

            if (count($result->rows))
                foreach ($result->rows as $aRow) {
                    $type_code = $aRow->value->type_code;
                    $priority = $this->fk_extrafields->fields->type_code->values->$type_code->priority;

                    $obj = new stdClass();
                    $obj->x = $aRow->value->datep * 1000;
                    $obj->y = $priority;
                    $obj->name = $aRow->value->label;
                    $obj->id = $aRow->value->_id;
                    if (!isset($aRow->value->societe->name))
                        $obj->soc = $langs->trans("None");
                    else
                        $obj->soc = $aRow->value->societe->name;
                    $obj->usertodo = $aRow->value->usertodo->name;

                    $output[] = clone $obj;
                }

            return $output;
        } else {
            $total = 0;
            $i = 0;
            ?>
            <div id="eisenhower" style="min-width: 100px; height: 280px; margin: 0 auto"></div>
            <script type="text/javascript">
                (function($){ // encapsulate jQuery

                    $(function() {
                        var seriesOptions = [],
                        yAxisOptions = [],
                        seriesCounter = 0,
                        names = ['MyTasks','MyDelegatedTasks'],
                        colors = Highcharts.getOptions().colors;
                        var translate = [];
                        translate['MyTasks'] = "<?php echo $langs->trans('MyTasks'); ?>";
                        translate['MyDelegatedTasks'] = "<?php echo $langs->trans('MyDelegatedTasks'); ?>";
                        $.each(names, function(i, name) {

                            $.getJSON('<?php echo DOL_URL_ROOT . '/core/ajax/viewgraph.php'; ?>?json=graphEisenhower&class=<?php echo get_class($this); ?>&name='+ name.toString() +'&callback=?',	function(data) {

                                seriesOptions[i] = {
                                    type: 'scatter',
                                    name: translate[name],
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
                                    renderTo: 'eisenhower',
                                    defaultSeriesType: "columnrange",
                                    marginBottom: 35
                                },
                                credits: {
                                    enabled:false
                                },
                                xAxis: {
                                    title: {text: "Urgence"},
                                    min: <?php echo ((dol_now() * 1000) - (37 * 24 * 3600 * 1000)); ?>,
                                    max: <?php echo ((dol_now() * 1000) + (30 * 24 * 3600 * 1000)); ?>,
                                    type: "datetime",
                                    tickInterval: 7 * 24 * 3600 * 1000 * 2,
                                    tickWidth: 0,
                                    gridLineWidth: 1,
                                    labels: {
                                        align: "left",
                                        x: 3,
                                        y: -3
                                    },
                                    plotBands: [{
                                            from: <?php echo ((dol_now() * 1000) - (7 * 24 * 3600 * 1000)); ?>,
                                            to: <?php echo (dol_now() * 1000); ?>,
                                            color: "#edc9c9"
                                        }],
                                    plotLines: [{
                                            value: <?php echo (dol_now() * 1000); ?>,
                                            width: 4,
                                            color: "red",
                                            label: {
                                                text: Highcharts.dateFormat("%e. %b %H:%M",<?php echo (dol_now() * 1000); ?>),
                                                style: { color: "white" }
                                            }
                                        }]
                                },
                                yAxis: {
                                    min: 0,
                                    max: <?php echo 10; ?>,
                                    title: {text: "Importance"},
                                    plotLines: [{
                                            value: <? echo 5; ?>,
                                            width: 2,
                                            color: "red"
                                        }],
                                    labels: {
                                        enabled: false
                                    }
                                },
                                title: {
                                    text: null
                                },
                                tooltip: {
                                    enabled:true,
                                    formatter: function() {
                                        return '<b>' + this.point.soc + "</b><br><i>" + this.point.name + "</i><br>" + Highcharts.dateFormat("%e. %b",this.x) + "<br><i>" + this.point.usertodo + "</i>";
                                    }
                                },
                                plotOptions: {
                                    series: { cursor: "pointer",
                                        point: {
                                            events: {click: function() {location.href = 'agenda/fiche.php?id=' + this.options.id;}}
                                        }
                                    }
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
                                    shadow: true,
                                    enabled:true
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

    /*
     * Calcul des priorités
     *
     */

    function fibonacci($n) {
        if ($n <= 1)
            return $n;
        else
            return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
    }

}
?>
