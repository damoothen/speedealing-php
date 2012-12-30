<?php
/* Copyright (C) 2006-2007	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2012		Regis Houssin		<regis.houssin@capnetworks.com>
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
 * \file       htdocs/core/lib/bank.lib.php
 * \brief      Ensemble de fonctions de base pour le module banque
 * \ingroup    banque
 */

/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @return  array				Array of tabs to shoc
 */
function bank_prepare_head($object)
{
	global $langs, $conf, $user;
	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT.'/compta/bank/fiche.php?id='.$object->id;
	$head[$h][1] = $langs->trans("AccountCard");
	$head[$h][2] = 'bankname';
	$h++;

	if ($object->type == 0 || $object->type == 1)
	{
		$head[$h][0] = DOL_URL_ROOT.'/compta/bank/bankid_fr.php?id='.$object->id;
		$head[$h][1] = $langs->trans("RIB");
		$head[$h][2] = 'bankid';
		$h++;
	}

    $head[$h][0] = DOL_URL_ROOT."/compta/bank/account.php?id=".$object->id;
    $head[$h][1] = $langs->trans("Transactions");
    $head[$h][2] = 'journal';
    $h++;

//    if ($conf->global->MAIN_FEATURES_LEVEL >= 1)
//	{
		$head[$h][0] = DOL_URL_ROOT."/compta/bank/treso.php?account=".$object->id;
		$head[$h][1] = $langs->trans("PlannedTransactions");
		$head[$h][2] = 'cash';
		$h++;
//	}

    $head[$h][0] = DOL_URL_ROOT."/compta/bank/annuel.php?account=".$object->id;
    $head[$h][1] = $langs->trans("IOMonthlyReporting");
    $head[$h][2] = 'annual';
    $h++;

    $head[$h][0] = DOL_URL_ROOT."/compta/bank/graph.php?account=".$object->id;
    $head[$h][1] = $langs->trans("Graph");
    $head[$h][2] = 'graph';
    $h++;

    if ($object->courant != 2)
    {
    	$head[$h][0] = DOL_URL_ROOT."/compta/bank/releve.php?account=".$object->id;
	    $head[$h][1] = $langs->trans("AccountStatements");
	    $head[$h][2] = 'statement';
	    $h++;
	}

	return $head;
}


/**
 *		Check account number informations for a bank account
 *
 *		@param	Account		$account    A bank account
 *		@return int           			True if informations are valid, false otherwise
 */
function checkBanForAccount($account)
{
	$country_code=$account->getCountryCode();

	// For compatibility between
	// account of type CompanyBankAccount class (we use number, cle_rib)
	// account of type Account class (we use num_compte, cle)
	if (empty($account->number)) $account->number=$account->num_compte;
	if (empty($account->cle))    $account->cle=$account->cle_rib;

	dol_syslog("Bank.lib::checkBanForAccount account->code_banque=".$account->code_banque." account->code_guichet=".$account->code_guichet." account->number=".$account->number." account->cle=".$account->cle." account->iban=".$account->iban." country_code=".$country_code, LOG_DEBUG);

	if ($country_code == 'FR')	// France rules
	{
		$coef = array(62, 34, 3);
		// Concatenation des differents codes.
		$rib = strtolower(trim($account->code_banque).trim($account->code_guichet).trim($account->number).trim($account->cle));
		// On remplace les eventuelles lettres par des chiffres.
		//$rib = strtr($rib, "abcdefghijklmnopqrstuvwxyz","12345678912345678912345678");	//Ne marche pas
		$rib = strtr($rib, "abcdefghijklmnopqrstuvwxyz","12345678912345678923456789");
		// Separation du rib en 3 groupes de 7 + 1 groupe de 2.
		// Multiplication de chaque groupe par les coef du tableau
		for ($i=0, $s=0; $i<3; $i++)
		{
			$code = substr($rib, 7 * $i, 7);
			$s += (0 + $code) * $coef[$i] ;
		}
		// Soustraction du modulo 97 de $s a 97 pour obtenir la cle
		$cle_rib = 97 - ($s % 97);
		if ($cle_rib == $account->cle)
		{
			return true;
		}
		return false;
	}

	if ($country_code == 'BE')	// Belgium rules
	{
	}

	if ($country_code == 'ES')	// Spanish rules
	{
		$CCC = strtolower(trim($account->number));
		$rib = strtolower(trim($account->code_banque).trim($account->code_guichet));
    	$cle_rib=strtolower(checkES($rib,$CCC));
		if ($cle_rib == strtolower($account->cle))
    	{
    		return true;
		}
		return false;
    }
    if ($country_code == 'AU')  // Australian
    {
        if (strlen($account->code_banque) > 7) return false; // Sould be 6 but can be 123-456
        else if (strlen($account->code_banque) < 6) return false; // Sould be 6
        else return true;
    }

	// No particular rule
	// If account is CompanyBankAccount class, we use number
	// If account is Account class, we use num_compte
	if (empty($account->number))
	{
		return false;
	}

	return true;
}


/**
 * 	Returns the key for Spanish Banks Accounts
 *
 *  @param	string	$IentOfi	IentOfi
 *  @param	string	$InumCta	InumCta
 *  @return	string				Key
 */
function checkES($IentOfi,$InumCta)
{
	if (empty($IentOfi)||empty($InumCta)||strlen($IentOfi)!=8||strlen($InumCta)!=10)
	{
		$keycontrol ="";
		return $keycontrol;
	}

	$ccc= $IentOfi . $InumCta;
	$numbers = "1234567890";

	$i = 0;

	while ($i<=strlen($ccc)-1)
	{
		if (strpos($numbers,substr($ccc,$i,1)) === false)
		{
			$keycontrol ="";
			return $keycontrol;
		}
		$i++;
	}

	$values = array(1,2,4,8,5,10,9,7,3,6);
	$sum = 0;

	for($i=2; $i<10; $i++)

	{
		$sum += $values[$i] * substr($IentOfi, $i-2, 1);
	}

	$key = 11-$sum%11;

	if ($key==10) $key=1;
	if ($key==11) $key=0;

  	$keycontrol = $key;

	$sum = 0;

 	for($i=0; $i<11; $i++)

	{
		$sum += $values[$i] * substr($InumCta,$i, 1);
	}

 	$key = 11-$sum%11;

	if ($key==10) $key=1;
	if ($key==11) $key=0;

 	$keycontrol .= $key;
	return $keycontrol;
}

?>