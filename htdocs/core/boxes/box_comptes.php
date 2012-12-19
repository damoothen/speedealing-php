<?php
/* Copyright (C) 2005      Christophe
 * Copyright (C) 2005-2008 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis@dolibarr.fr>
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
 *      \file       htdocs/core/boxes/box_comptes.php
 *      \ingroup    banque
 *      \brief      Module to generate box for bank accounts
 */
include_once DOL_DOCUMENT_ROOT.'/core/boxes/modules_boxes.php';
include_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';


/**
 * Class to manage the box to show last users
 */
class box_comptes extends ModeleBoxes
{
	var $boxcode="currentaccounts";
	var $boximg="object_bill";
	var $boxlabel;
	var $depends = array("banque");     // Box active if module banque active

	var $db;
	var $param;

	var $info_box_head = array();
	var $info_box_contents = array();


	/**
	 *  Constructor
	 */
	function __construct()
	{
		global $langs;
		$langs->load("boxes");

		$this->boxlabel=$langs->transnoentitiesnoconv('BoxCurrentAccounts');
	}

	/**
	 *  Load data into info_box_contents array to show array later.
	 *
	 *  @param	int		$max        Maximum number of records to load
     *  @return	void
	 */
	function loadBox($max=5)
	{
		global $user, $langs, $db, $conf;

		$this->max=$max;

		$this->info_box_head = array('text' => $langs->trans("BoxTitleCurrentAccounts"));

		if ($user->rights->banque->lire)
		{
			$sql = "SELECT rowid, ref, label, bank, number, courant, clos, rappro, url,";
			$sql.= " code_banque, code_guichet, cle_rib, bic, iban_prefix,";
			$sql.= " domiciliation, proprio, adresse_proprio,";
			$sql.= " account_number, currency_code,";
			$sql.= " min_allowed, min_desired, comment";
			$sql.= " FROM ".MAIN_DB_PREFIX."bank_account";
			$sql.= " WHERE entity = ".$conf->entity;
			$sql.= " AND clos = 0";
			$sql.= " AND courant = 1";
			$sql.= " ORDER BY label";
			$sql.= $db->plimit($max, 0);

			dol_syslog("Box_comptes::loadBox sql=".$sql);
			$result = $db->query($sql);
			if ($result)
			{
				$num = $db->num_rows($result);

				$i = 0;
				$solde_total = 0;

				$listofcurrencies=array();
				$account_static = new Account($db);
				while ($i < $num)
				{
					$objp = $db->fetch_object($result);

					$account_static->id = $objp->rowid;
					$solde=$account_static->solde(0);

					$solde_total += $solde;

					$this->info_box_contents[$i][0] = array('td' => 'align="left" width="16"',
					'logo' => $this->boximg,
					'url' => DOL_URL_ROOT."/compta/bank/account.php?account=".$objp->rowid);

					$this->info_box_contents[$i][1] = array('td' => 'align="left"',
					'text' => $objp->label,
					'url' => DOL_URL_ROOT."/compta/bank/account.php?account=".$objp->rowid);

					$this->info_box_contents[$i][2] = array('td' => 'align="left"',
					'text' => $objp->number
					);

					$this->info_box_contents[$i][3] = array('td' => 'align="right"',
					'text' => price($solde).' '.$langs->trans("Currency".$objp->currency_code)
					);

					$listofcurrencies[$objp->currency_code]=1;
					$i++;
				}

				// Total
				if (count($listofcurrencies) <= 1)
				{
					$this->info_box_contents[$i][0] = array('tr' => 'class="liste_total"', 'td' => 'align="right" class="liste_total"',
					'text' => $langs->trans('Total')
					);
					$this->info_box_contents[$i][1] = array('td' => 'align="right" class="liste_total"',
					'text' => '&nbsp;'
					);
					$this->info_box_contents[$i][2] = array('td' => 'align="right" class="liste_total"',
					'text' => '&nbsp;'
					);
					$totalamount=price($solde_total).' '.$langs->trans("Currency".$conf->currency);
					$this->info_box_contents[$i][3] = array('td' => 'align="right" class="liste_total"',
					'text' => $totalamount
					);
				}
			}
			else {
				$this->info_box_contents[0][0] = array(	'td' => 'align="left"',
    	        										'maxlength'=>500,
	            										'text' => ($db->error().' sql='.$sql));
			}
		}
		else {
			$this->info_box_contents[0][0] = array('td' => 'align="left"',
			'text' => $langs->trans("ReadPermissionNotAllowed"));
		}

	}

	/**
	 *	Method to show box
	 *
	 *	@param	array	$head       Array with properties of box title
	 *	@param  array	$contents   Array with properties of box lines
	 *	@return	void
	 */
	function showBox($head = null, $contents = null)
	{
		parent::showBox($this->info_box_head, $this->info_box_contents);
	}

}

?>
