<?php

/* Copyright (C) 2006-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2006      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2007      Patrick Raguin       <patrick.raguin@gmail.com>
 * Copyright (C) 2010      Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2010-2012 Herve Prot           <herve.prot@symeos.com>
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
 * or see http://www.gnu.org/
 */

/**
 * 	\file       htdocs/core/lib/company.lib.php
 * 	\brief      Ensemble de fonctions de base pour le module societe
 * 	\ingroup    societe
 */

/**
 * Return array of tabs to used on pages for third parties cards.
 *
 * @param 	Object	$object		Object company shown
 * @return 	array				Array of tabs
 */
function societe_prepare_head($object) {
    global $langs, $conf, $user;
    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . '/societe/fiche.php?socid=' . $object->id;
    $head[$h][1] = $langs->trans("Card");
    $head[$h][2] = 'card';
    $h++;

    // TODO Remove tests on object->object. Functions must be called with a company object directly
    if (($object->client == 2 || $object->client == 3
            || (isset($object->object) && $object->object->client == 2) || (isset($object->object) && $object->object->client == 3)) && empty($conf->global->SOCIETE_DISABLE_PROSPECTS)) {
        $head[$h][0] = DOL_URL_ROOT . '/comm/prospect/fiche.php?socid=' . $object->id;
        $head[$h][1] = $langs->trans("Prospect");
        $head[$h][2] = 'prospect';
        $h++;
    }
    if ($object->client == 1 || $object->client == 3 || (isset($object->object) && $object->object->client == 1) || (isset($object->object) && $object->object->client == 3)) {
        $head[$h][0] = DOL_URL_ROOT . '/comm/fiche.php?socid=' . $object->id;
        $head[$h][1] = $langs->trans("Customer");
        $head[$h][2] = 'customer';
        $h++;
    }
    if (!empty($conf->fournisseur->enabled) && ($object->fournisseur || (isset($object->object) && $object->object->fournisseur)) && !empty($user->rights->fournisseur->lire)) {
        $head[$h][0] = DOL_URL_ROOT . '/fourn/fiche.php?socid=' . $object->id;
        $head[$h][1] = $langs->trans("Supplier");
        $head[$h][2] = 'supplier';
        $h++;
    }

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'thirdparty');

    if ($user->societe_id == 0) {
        // Notes
        $head[$h][0] = DOL_URL_ROOT . '/societe/note.php?socid=' . $object->id;
        $head[$h][1] = $langs->trans("Note");
        $head[$h][2] = 'note';
        $h++;

        if (!empty($conf->ecm->enabled)) {
            // Attached files
            $head[$h][0] = DOL_URL_ROOT . '/societe/document.php?socid=' . $object->id;
            $head[$h][1] = $langs->trans("Documents");
            $head[$h][2] = 'document';
            $h++;
        }

//Map module
        if ($conf->map->enabled && $user->societe_id == 0 && $object->lat && $object->lng) {
            $langs->load("map@map");
            $head[$h][0] = DOL_URL_ROOT . '/map/map.php?socid=' . $object->id;
            $head[$h][1] = $langs->trans("Map");
            $head[$h][2] = 'map';
            $h++;
        }

        // Notifications
        if (!empty($conf->notification->enabled)) {
            $head[$h][0] = DOL_URL_ROOT . '/societe/notify/fiche.php?socid=' . $object->id;
            $head[$h][1] = $langs->trans("Notifications");
            $head[$h][2] = 'notify';
            $h++;
        }
    }

    complete_head_from_modules($conf, $langs, $object, $head, $h, 'thirdparty', 'remove');

    return $head;
}

/**
 * Return array of tabs to used on page
 *
 * @param	Object	$object		Object for tabs
 * @return	array				Array of tabs
 */
function societe_prepare_head2($object) {
    global $langs, $conf, $user;
    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . '/societe/soc.php?socid=' . $object->id;
    $head[$h][1] = $langs->trans("Card");
    $head[$h][2] = 'company';
    $h++;

    if (empty($conf->global->SOCIETE_DISABLE_BANKACCOUNT)) {
        $head[$h][0] = DOL_URL_ROOT . '/societe/rib.php?socid=' . $object->id;
        $head[$h][1] = $langs->trans("BankAccount") . " $account->number";
        $head[$h][2] = 'rib';
        $h++;
    }

    if (empty($conf->global->SOCIETE_DISABLE_PARENTCOMPANY)) {
        $head[$h][0] = 'lien.php?socid=' . $object->id;
        $head[$h][1] = $langs->trans("ParentCompany");
        $head[$h][2] = 'links';
        $h++;
    }

    $head[$h][0] = 'commerciaux.php?socid=' . $object->id;
    $head[$h][1] = $langs->trans("SalesRepresentative");
    $head[$h][2] = 'salesrepresentative';
    $h++;

    return $head;
}

/**
 *  Return array head with list of tabs to view object informations.
 *
 *  @param	Object	$object		Thirdparty
 *  @return	array   	        head array with tabs
 */
function societe_admin_prepare_head($object) {
    global $langs, $conf, $user;

    $h = 0;
    $head = array();

    $head[$h][0] = DOL_URL_ROOT . '/societe/admin/societe.php';
    $head[$h][1] = $langs->trans("Miscellanous");
    $head[$h][2] = 'general';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    // $this->tabs = array('entity:+tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to add new tab
    // $this->tabs = array('entity:-tabname:Title:@mymodule:/mymodule/mypage.php?id=__ID__');   to remove a tab
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'company_admin');

    $head[$h][0] = DOL_URL_ROOT . '/societe/admin/societe_extrafields.php';
    $head[$h][1] = $langs->trans("ExtraFieldsThirdParties");
    $head[$h][2] = 'attributes';
    $h++;

    $head[$h][0] = DOL_URL_ROOT . '/societe/admin/contact_extrafields.php';
    $head[$h][1] = $langs->trans("ExtraFieldsContacts");
    $head[$h][2] = 'attributes_contacts';
    $h++;

    complete_head_from_modules($conf, $langs, $object, $head, $h, 'company_admin', 'remove');

    return $head;
}

/**
 *    Return country label, code or id from an id or a code
 *
 *    @param      int		$id            	Id or code of country
 *    @param      int		$withcode   	'0'=Return label,
 *    										'1'=Return code + label,
 *    										'2'=Return code from id,
 *    										'3'=Return id from code,
 * 	   										'all'=Return array('id'=>,'code'=>,'label'=>)
 *    @param      DoliDB	$dbtouse       	Database handler (using in global way may fail because of conflicts with some autoload features)
 *    @param      Translate	$outputlangs	Langs object for output translation
 *    @param      int		$entconv       	0=Return value without entities and not converted to output charset
 *    @return     mixed       				String with country code or translated country name or Array('id','code','label')
 */
/*function getCountry($id, $withcode = '', $dbtouse = 0, $outputlangs = '', $entconv = 1) {
    global $db, $langs;

    // Check parameters
    if (empty($id)) {
        if ($withcode === 'all')
            return array('id' => '', 'code' => '', 'label' => '');
        else
            return '';
    }
    if (!is_object($dbtouse))
        $dbtouse = $db;
    if (!is_object($outputlangs))
        $outputlangs = $langs;

    $sql = "SELECT rowid, code, libelle FROM " . MAIN_DB_PREFIX . "c_pays";
    if (is_numeric($id))
        $sql.= " WHERE rowid=" . $id;
    else
        $sql.= " WHERE code='" . $db->escape($id) . "'";

    dol_syslog("Company.lib::getCountry sql=" . $sql);
    $resql = $dbtouse->query($sql);
    if ($resql) {
        $obj = $dbtouse->fetch_object($resql);
        if ($obj) {
            $label = ((!empty($obj->libelle) && $obj->libelle != '-') ? $obj->libelle : '');
            if (is_object($outputlangs)) {
                $outputlangs->load("dict");
                if ($entconv)
                    $label = ($obj->code && ($outputlangs->trans("Country" . $obj->code) != "Country" . $obj->code)) ? $outputlangs->trans("Country" . $obj->code) : $label;
                else
                    $label = ($obj->code && ($outputlangs->transnoentitiesnoconv("Country" . $obj->code) != "Country" . $obj->code)) ? $outputlangs->transnoentitiesnoconv("Country" . $obj->code) : $label;
            }
            if ($withcode == 1)
                return $label ? "$obj->code - $label" : "$obj->code";
            else if ($withcode == 2)
                return $obj->code;
            else if ($withcode == 3)
                return $obj->rowid;
            else if ($withcode === 'all')
                return array('id' => $obj->rowid, 'code' => $obj->code, 'label' => $label);
            else
                return $label;
        }
        else {
            return 'NotDefined';
        }
    }
    else
        dol_print_error($dbtouse, '');
    return 'Error';
}*/

/**
 *    Return state translated from an id
 *
 *    @param	int		$id         id of state (province/departement)
 *    @param    int		$withcode   '0'=Return label,
 *    								'1'=Return string code + label,
 *    						  		'2'=Return code,
 *    						  		'all'=return array('id'=>,'code'=>,'label'=>)
 *    @param	DoliDB	$dbtouse	Database handler (using in global way may fail because of conflicts with some autoload features)
 *    @return   string      		String with state code or translated state name
 */
function getState($id, $withcode = '', $dbtouse = 0) {
    global $db, $langs;

    if (!is_object($dbtouse))
        $dbtouse = $db;

    $sql = "SELECT rowid, code_departement as code, nom as label FROM " . MAIN_DB_PREFIX . "c_departements";
    $sql.= " WHERE rowid=" . $id;

    dol_syslog("Company.lib::getState sql=" . $sql);
    $resql = $dbtouse->query($sql);
    if ($resql) {
        $obj = $dbtouse->fetch_object($resql);
        if ($obj) {
            $label = $obj->label;
            if ($withcode == '1')
                return $label = $obj->code ? "$obj->code" : "$obj->code - $label";
            else if ($withcode == '2')
                return $label = $obj->code;
            else if ($withcode == 'all')
                return array('id' => $obj->rowid, 'code' => $obj->code, 'label' => $label);
            else
                return $label;
        }
        else {
            return $langs->trans("NotDefined");
        }
    }
    else
        dol_print_error($dbtouse, '');
}

/**
 *    Retourne le nom traduit ou code+nom d'une devise
 *
 *    @param      string	$code_iso       Code iso de la devise
 *    @param      int		$withcode       '1'=affiche code + nom
 *    @return     string     			    Nom traduit de la devise
 */
function currency_name($code_iso, $withcode = '') {
    global $langs, $db;

    // Si il existe une traduction, on peut renvoyer de suite le libelle
    if ($langs->trans("Currency" . $code_iso) != "Currency" . $code_iso) {
        return $langs->trans("Currency" . $code_iso);
    }

    // Si pas de traduction, on consulte le libelle par defaut en table
    $sql = "SELECT label FROM " . MAIN_DB_PREFIX . "c_currencies";
    $sql.= " WHERE code_iso='" . $code_iso . "'";

    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);

        if ($num) {
            $obj = $db->fetch_object($resql);
            $label = ($obj->label != '-' ? $obj->label : '');
            if ($withcode)
                return ($label == $code_iso) ? "$code_iso" : "$code_iso - $label";
            else
                return $label;
        }
        else {
            return $code_iso;
        }
    }
}

/**
 *    Retourne le nom traduit de la forme juridique
 *
 *    @param      string	$code       Code de la forme juridique
 *    @return     string     			Nom traduit du pays
 */
function getFormeJuridiqueLabel($code) {
    global $db, $langs;

    if (!$code)
        return '';

    $sql = "SELECT libelle FROM " . MAIN_DB_PREFIX . "c_forme_juridique";
    $sql.= " WHERE code='$code'";

    dol_syslog("Company.lib::getFormeJuridiqueLabel sql=" . $sql);
    $resql = $db->query($sql);
    if ($resql) {
        $num = $db->num_rows($resql);

        if ($num) {
            $obj = $db->fetch_object($resql);
            $label = ($obj->libelle != '-' ? $obj->libelle : '');
            return $label;
        } else {
            return $langs->trans("NotDefined");
        }
    }
}

/**
 * 		Show html area for list of projects
 *
 * 		@param	Conf		$conf			Object conf
 * 		@param	Translate	$langs			Object langs
 * 		@param	DoliDB		$db				Database handler
 * 		@param	Object		$object			Third party object
 *      @param  string		$backtopage		Url to go once contact is created
 *      @return	void
 */
function show_projects($conf, $langs, $db, $object, $backtopage = '') {
    global $user;
    global $bc;

    $i = -1;

    if (!empty($conf->projet->enabled) && $user->rights->projet->lire) {
        $langs->load("projects");

        $buttoncreate = '';
        if (!empty($conf->projet->enabled) && $user->rights->projet->creer) {
            //$buttoncreate='<a class="butAction" href="'.DOL_URL_ROOT.'/projet/fiche.php?socid='.$object->id.'&action=create&amp;backtopage='.urlencode($backtopage).'">'.$langs->trans("AddProject").'</a>';
            $buttoncreate = '<a class="addnewrecord" href="' . DOL_URL_ROOT . '/projet/fiche.php?socid=' . $object->id . '&amp;action=create&amp;backtopage=' . urlencode($backtopage) . '">' . $langs->trans("AddProject") . ' ' . img_picto($langs->trans("AddProject"), 'filenew') . '</a>' . "\n";
        }

        print "\n";
        print_fiche_titre($langs->trans("ProjectsDedicatedToThisThirdParty"), $buttoncreate, '');
        print "\n" . '<table class="noborder" width=100%>';

        $sql = "SELECT p.rowid,p.title,p.ref,p.public, p.dateo as do, p.datee as de";
        $sql .= " FROM " . MAIN_DB_PREFIX . "projet as p";
        $sql .= " WHERE p.fk_soc = " . $object->id;
        $sql .= " ORDER BY p.dateo DESC";

        $result = $db->query($sql);
        if ($result) {
            $num = $db->num_rows($result);

            print '<tr class="liste_titre">';
            print '<td>' . $langs->trans("Ref") . '</td><td>' . $langs->trans("Name") . '</td><td align="center">' . $langs->trans("DateStart") . '</td><td align="center">' . $langs->trans("DateEnd") . '</td>';
            print '</tr>';

            if ($num > 0) {
                require_once DOL_DOCUMENT_ROOT . '/projet/class/project.class.php';

                $projectstatic = new Project($db);

                $i = 0;
                $var = true;
                while ($i < $num) {
                    $obj = $db->fetch_object($result);
                    $projectstatic->fetch($obj->rowid);

                    // To verify role of users
                    $userAccess = $projectstatic->restrictedProjectArea($user);

                    if ($user->rights->projet->lire && $userAccess > 0) {
                        $var = !$var;
                        print "<tr $bc[$var]>";

                        // Ref
                        print '<td><a href="' . DOL_URL_ROOT . '/projet/fiche.php?id=' . $obj->rowid . '">' . img_object($langs->trans("ShowProject"), ($obj->public ? 'projectpub' : 'project')) . " " . $obj->ref . '</a></td>';
                        // Label
                        print '<td>' . $obj->title . '</td>';
                        // Date start
                        print '<td align="center">' . dol_print_date($db->jdate($obj->do), "day") . '</td>';
                        // Date end
                        print '<td align="center">' . dol_print_date($db->jdate($obj->de), "day") . '</td>';

                        print '</tr>';
                    }
                    $i++;
                }
            } else {
                print '<tr><td colspan="3">' . $langs->trans("None") . '</td></tr>';
            }
            $db->free($result);
        } else {
            dol_print_error($db);
        }
        print "</table>";

        print "<br>\n";
    }

    return $i;
}

/**
 * 		Show html area for list of addresses
 *
 * 		@param	Conf		$conf		Object conf
 * 		@param	Translate	$langs		Object langs
 * 		@param	DoliDB		$db			Database handler
 * 		@param	Object		$object		Third party object
 *      @param  string		$backtopage	Url to go once address is created
 *      @return	void
 */
function show_addresses($conf, $langs, $db, $object, $backtopage = '') {
    global $user;
    global $bc;

    require_once DOL_DOCUMENT_ROOT . '/societe/class/address.class.php';

    $addressstatic = new Address($db);
    $num = $addressstatic->fetch_lines($object->id);

    $buttoncreate = '';
    if ($user->rights->societe->creer) {
        $buttoncreate = '<a class="addnewrecord" href="' . DOL_URL_ROOT . '/comm/address.php?socid=' . $object->id . '&amp;action=create&amp;backtopage=' . urlencode($backtopage) . '">' . $langs->trans("AddAddress") . ' ' . img_picto($langs->trans("AddAddress"), 'filenew') . '</a>' . "\n";
    }

    print "\n";
    print_fiche_titre($langs->trans("AddressesForCompany"), $buttoncreate, '');

    print "\n" . '<table class="noborder" width="100%">' . "\n";

    print '<tr class="liste_titre"><td>' . $langs->trans("Label") . '</td>';
    print '<td>' . $langs->trans("CompanyName") . '</td>';
    print '<td>' . $langs->trans("Town") . '</td>';
    print '<td>' . $langs->trans("Country") . '</td>';
    print '<td>' . $langs->trans("Tel") . '</td>';
    print '<td>' . $langs->trans("Fax") . '</td>';
    print "<td>&nbsp;</td>";
    print "</tr>";

    if ($num > 0) {
        $var = true;

        foreach ($addressstatic->lines as $address) {
            $var = !$var;

            print "<tr " . $bc[$var] . ">";

            print '<td>';
            $addressstatic->id = $address->id;
            $addressstatic->label = $address->label;
            print $addressstatic->getNomUrl(1);
            print '</td>';

            print '<td>' . $address->name . '</td>';

            print '<td>' . $address->town . '</td>';

            $img = picto_from_langcode($address->country_code);
            print '<td>' . ($img ? $img . ' ' : '') . $address->country . '</td>';

            // Lien click to dial
            print '<td>';
            print dol_print_phone($address->phone, $address->country_code, $address->id, $object->id, 'AC_TEL');
            print '</td>';
            print '<td>';
            print dol_print_phone($address->fax, $address->country_code, $address->id, $object->id, 'AC_FAX');
            print '</td>';

            if ($user->rights->societe->creer) {
                print '<td align="right">';
                print '<a href="' . DOL_URL_ROOT . '/comm/address.php?action=edit&amp;id=' . $address->id . '&amp;socid=' . $object->id . '&amp;backtopage=' . urlencode($backtopage) . '">';
                print img_edit();
                print '</a></td>';
            }

            print "</tr>\n";
        }
    } else {
        //print "<tr ".$bc[$var].">";
        //print '<td>'.$langs->trans("NoAddressYetDefined").'</td>';
        //print "</tr>\n";
    }
    print "\n</table>\n";

    print "<br>\n";

    return $num;
}

/**
 * 		Show html area for list of subsidiaries
 *
 * 		@param	Conf		$conf		Object conf
 * 		@param	Translate	$langs		Object langs
 * 		@param	DoliDB		$db			Database handler
 * 		@param	Societe		$object		Third party object
 * 		@return	void
 */
function show_subsidiaries($conf, $langs, $db, $object) {
    global $user;
    global $bc;

    $i = -1;

    $sql = "SELECT s.rowid, s.nom as name, s.address, s.cp as zip, s.ville as town, s.code_client, s.canvas";
    $sql.= " FROM " . MAIN_DB_PREFIX . "societe as s";
    $sql.= " WHERE s.parent = " . $object->id;
    $sql.= " AND s.entity IN (" . getEntity('societe', 1) . ")";
    $sql.= " ORDER BY s.nom";

    $result = $db->query($sql);
    $num = $db->num_rows($result);

    if ($num) {
        $socstatic = new Societe($db);

        print_titre($langs->trans("Subsidiaries"));
        print "\n" . '<table class="noborder" width="100%">' . "\n";

        print '<tr class="liste_titre"><td>' . $langs->trans("Company") . '</td>';
        print '<td>' . $langs->trans("Address") . '</td><td>' . $langs->trans("Zip") . '</td>';
        print '<td>' . $langs->trans("Town") . '</td><td>' . $langs->trans("CustomerCode") . '</td>';
        print "<td>&nbsp;</td>";
        print "</tr>";

        $i = 0;
        $var = true;

        while ($i < $num) {
            $obj = $db->fetch_object($result);
            $var = !$var;

            print "<tr " . $bc[$var] . ">";

            print '<td>';
            $socstatic->id = $obj->rowid;
            $socstatic->name = $obj->name;
            $socstatic->canvas = $obj->canvas;
            print $socstatic->getNomUrl(1);
            print '</td>';

            print '<td>' . $obj->address . '</td>';
            print '<td>' . $obj->zip . '</td>';
            print '<td>' . $obj->town . '</td>';
            print '<td>' . $obj->code_client . '</td>';

            print '<td align="center">';
            print '<a href="' . DOL_URL_ROOT . '/societe/soc.php?socid=' . $obj->rowid . '&amp;action=edit">';
            print img_edit();
            print '</a></td>';

            print "</tr>\n";
            $i++;
        }
        print "\n</table>\n";
    }

    print "<br>\n";

    return $i;
}

?>
