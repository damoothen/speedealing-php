<?php
/* Copyright (C) 2006-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2011      Regis Houssin        <regis@dolibarr.fr>
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

/**
 *  \file       htdocs/core/triggers/interface_50_modNotification_Notification.class.php
 *  \ingroup    notification
 *  \brief      File of class of triggers for notification module
 */


/**
 *  Class of triggers for notification module
 */
class InterfaceNotification
{
    var $db;
    var $listofmanagedevents=array('BILL_VALIDATE','ORDER_VALIDATE','PROPAL_VALIDATE',
                            'FICHEINTER_VALIDATE','ORDER_SUPPLIER_APPROVE','ORDER_SUPPLIER_REFUSE');

    /**
     *   Constructor
     *
     *   @param		DoliDB		$db      Database handler
     */
    function __construct($db)
    {
        $this->db = $db;

        $this->name = preg_replace('/^Interface/i','',get_class($this));
        $this->family = "notification";
        $this->description = "Triggers of this module send email notifications according to Notification module setup.";
        $this->version = 'dolibarr';                        // 'experimental' or 'dolibarr' or version
        $this->picto = 'email';
    }

    /**
     *   Return name of trigger file
     *
     *   @return     string      Name of trigger file
     */
    function getName()
    {
        return $this->name;
    }

    /**
     *   Return description of trigger file
     *
     *   @return     string      Description of trigger file
     */
    function getDesc()
    {
        return $this->description;
    }

    /**
     *   Return version of trigger file
     *
     *   @return     string      Version of trigger file
     */
    function getVersion()
    {
        global $langs;
        $langs->load("admin");

        if ($this->version == 'experimental') return $langs->trans("Experimental");
        elseif ($this->version == 'dolibarr') return DOL_VERSION;
        elseif ($this->version) return $this->version;
        else return $langs->trans("Unknown");
    }

    /**
     *      Function called when a Dolibarrr business event is done.
     *      All functions "run_trigger" are triggered if file is inside directory htdocs/core/triggers
     *
     *      @param	string		$action		Event action code
     *      @param  Object		$object     Object
     *      @param  User		$user       Object user
     *      @param  Translate	$langs      Object langs
     *      @param  conf		$conf       Object conf
     *      @return int         			<0 if KO, 0 if no triggered ran, >0 if OK
     */
	function run_trigger($action,$object,$user,$langs,$conf)
    {
		if (empty($conf->notification->enabled)) return 0;     // Module not active, we do nothing

		require_once DOL_DOCUMENT_ROOT .'/core/class/notify.class.php';

		if ($action == 'BILL_VALIDATE')
		{
            dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);

            $ref = dol_sanitizeFileName($object->ref);
            $filepdf = $conf->facture->dir_output . '/' . $ref . '/' . $ref . '.pdf';
            if (! file_exists($filepdf)) $filepdf='';
            $filepdf='';	// We can't add PDF as it is not generated yet.
            $langs->load("other");
			$mesg = $langs->transnoentitiesnoconv("EMailTextInvoiceValidated",$object->ref);

            $notify = new Notify($this->db);
            $notify->send($action, $object->socid, $mesg, 'facture', $object->id, $filepdf);
		}

		elseif ($action == 'ORDER_VALIDATE')
		{
            dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);

            $ref = dol_sanitizeFileName($object->ref);
            $filepdf = $conf->commande->dir_output . '/' . $ref . '/' . $ref . '.pdf';
            if (! file_exists($filepdf)) $filepdf='';
            $filepdf='';	// We can't add PDF as it is not generated yet.
            $langs->load("other");
			$mesg = $langs->transnoentitiesnoconv("EMailTextOrderValidated",$object->ref);

            $notify = new Notify($this->db);
            $notify->send($action, $object->socid, $mesg, 'order', $object->id, $filepdf);
		}

		elseif ($action == 'PROPAL_VALIDATE')
		{
            dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);

            $ref = dol_sanitizeFileName($object->ref);
            $filepdf = $conf->propal->dir_output . '/' . $ref . '/' . $ref . '.pdf';
            if (! file_exists($filepdf)) $filepdf='';
            $filepdf='';	// We can't add PDF as it is not generated yet.
            $langs->load("other");
			$mesg = $langs->transnoentitiesnoconv("EMailTextProposalValidated",$object->ref);

            $notify = new Notify($this->db);
            $notify->send($action, $object->socid, $mesg, 'propal', $object->id, $filepdf);
		}

		elseif ($action == 'FICHEINTER_VALIDATE')
		{
            dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);

            $ref = dol_sanitizeFileName($object->ref);
            $filepdf = $conf->facture->dir_output . '/' . $ref . '/' . $ref . '.pdf';
            if (! file_exists($filepdf)) $filepdf='';
            $filepdf='';	// We can't add PDF as it is not generated yet.
            $langs->load("other");
			$mesg = $langs->transnoentitiesnoconv("EMailTextInterventionValidated",$object->ref);

            $notify = new Notify($this->db);
            $notify->send($action, $object->socid, $mesg, 'ficheinter', $object->id, $filepdf);
		}

		elseif ($action == 'ORDER_SUPPLIER_APPROVE')
		{
            dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);

            $ref = dol_sanitizeFileName($object->ref);
            $filepdf = $conf->fournisseur->dir_output . '/commande/' . $ref . '/' . $ref . '.pdf';
            if (! file_exists($filepdf)) $filepdf='';
            $mesg = $langs->transnoentitiesnoconv("Hello").",\n\n";
			$mesg.= $langs->transnoentitiesnoconv("EMailTextOrderApprovedBy",$object->ref,$user->getFullName($langs));
			$mesg.= "\n\n".$langs->transnoentitiesnoconv("Sincerely").".\n\n";

            $notify = new Notify($this->db);
            $notify->send($action, $object->socid, $mesg, 'order_supplier', $object->id, $filepdf);
		}

		elseif ($action == 'ORDER_SUPPLIER_REFUSE')
		{
            dol_syslog("Trigger '".$this->name."' for action '$action' launched by ".__FILE__.". id=".$object->id);

            $ref = dol_sanitizeFileName($object->ref);
            $filepdf = $conf->fournisseur->dir_output . '/commande/' . $ref . '/' . $ref . '.pdf';
            if (! file_exists($filepdf)) $filepdf='';
			$mesg = $langs->transnoentitiesnoconv("Hello").",\n\n";
			$mesg.= $langs->transnoentitiesnoconv("EMailTextOrderRefusedBy",$object->ref,$user->getFullName($langs));
			$mesg.= "\n\n".$langs->transnoentitiesnoconv("Sincerely").".\n\n";

            $notify = new Notify($this->db);
            $notify->send($action, $object->socid, $mesg, 'order_supplier', $object->id, $filepdf);
		}

		// If not found
/*
        else
        {
            dol_syslog("Trigger '".$this->name."' for action '$action' was ran by ".__FILE__." but no handler found for this action.");
			return -1;
        }
*/
		return 0;
    }


    /**
     * Return list of events managed by notification module
     *
     * @return      array       Array of events managed by notification module
     */
    function getListOfManagedEvents()
    {
        global $conf,$langs;

        $ret=array();

        $sql = "SELECT rowid, code, label, description, elementtype";
        $sql.= " FROM ".MAIN_DB_PREFIX."c_action_trigger";
        $sql.= $this->db->order("elementtype, code");
        dol_syslog("Get list of notifications sql=".$sql);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            $num=$this->db->num_rows($resql);
            $i=0;
            while ($i < $num)
            {
                $obj=$this->db->fetch_object($resql);

                $qualified=0;
                // Check is this event is supported by notification module
                if (in_array($obj->code,$this->listofmanagedevents)) $qualified=1;
                // Check if module for this event is active
                if ($qualified)
                {
                    //print 'xx'.$obj->code;
                    $element=$obj->elementtype;
                    if ($element == 'order_supplier' && empty($conf->fournisseur->enabled)) $qualified=0;
                    elseif ($element == 'invoice_supplier' && empty($conf->fournisseur->enabled)) $qualified=0;
                    elseif ($element == 'withdraw' && empty($conf->prelevement->enabled)) $qualified=0;
                    elseif ($element == 'shipping' && empty($conf->expedition->enabled)) $qualified=0;
                    elseif ($element == 'member' && empty($conf->adherent->enabled)) $qualified=0;
                    elseif (! in_array($element,array('order_supplier','invoice_supplier','withdraw','shipping','member'))
                                 && empty($conf->$element->enabled)) $qualified=0;
                }

                if ($qualified)
                {
                    $ret[]=array('rowid'=>$obj->rowid,'code'=>$obj->code,'label'=>$obj->label,'description'=>$obj->description,'elementtype'=>$obj->elementtype);
                }

                $i++;
            }
        }
        else dol_print_error($this->db);

        return $ret;
    }

}
?>
