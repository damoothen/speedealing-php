<?php
/* Copyright (C) 2007-2008 Jeremie Ollivier    <jeremie.o@laposte.net>
 * Copyright (C) 2008-2010 Laurent Destailleur <eldy@uers.sourceforge.net>
 * Copyright (C) 2011	   Juanjo Menent	   <jmenent@2byte.es>
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
 *
 * This page is called after submission of login page.
 * We set here login choices into session.
 */

include '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/include/environnement.php';
require_once DOL_DOCUMENT_ROOT.'/cashdesk/class/Auth.class.php';

$langs->load("main");
$langs->load("admin");
$langs->load("cashdesk");

$username = GETPOST("txtUsername");
$password = GETPOST("pwdPassword");
$thirdpartyid = (GETPOST('socid','int')!='')?GETPOST('socid','int'):$conf->global->CASHDESK_ID_THIRDPARTY;
$warehouseid = (GETPOST("warehouseid")!='')?GETPOST("warehouseid"):$conf->global->CASHDESK_ID_WAREHOUSE;
$bankid_cash = (GETPOST("CASHDESK_ID_BANKACCOUNT_CASH")!='')?GETPOST("CASHDESK_ID_BANKACCOUNT_CASH"):$conf->global->CASHDESK_ID_BANKACCOUNT_CASH;
$bankid_cheque = (GETPOST("CASHDESK_ID_BANKACCOUNT_CHEQUE")!='')?GETPOST("CASHDESK_ID_BANKACCOUNT_CHEQUE"):$conf->global->CASHDESK_ID_BANKACCOUNT_CHEQUE;
$bankid_cb = (GETPOST("CASHDESK_ID_BANKACCOUNT_CB")!='')?GETPOST("CASHDESK_ID_BANKACCOUNT_CB"):$conf->global->CASHDESK_ID_BANKACCOUNT_CB;

// Check username
if (empty($username))
{
	$retour=$langs->trans("ErrorFieldRequired",$langs->transnoentities("Login"));
	header('Location: '.DOL_URL_ROOT.'/cashdesk/index.php?err='.urlencode($retour).'&user='.$username.'&socid='.$thirdpartyid.'&warehouseid='.$warehouseid);
	exit;
}
// Check third party id
if (! ($thirdpartyid > 0))
{
    $retour=$langs->trans("ErrorFieldRequired",$langs->transnoentities("CashDeskThirdPartyForSell"));
    header('Location: '.DOL_URL_ROOT.'/cashdesk/index.php?err='.urlencode($retour).'&user='.$username.'&socid='.$thirdpartyid.'&warehouseid='.$warehouseid);
    exit;
}

// If we setup stock module to ask movement on invoices, we must not allow access if required setup not finished.
if (! empty($conf->stock->enabled) && $conf->global->STOCK_CALCULATE_ON_BILL &&  ! ($warehouseid > 0))
{
	$retour=$langs->trans("CashDeskSetupStock");
	header('Location: '.DOL_URL_ROOT.'/cashdesk/index.php?err='.urlencode($retour).'&user='.$username.'&socid='.$thirdpartyid.'&warehouseid='.$warehouseid);
	exit;
}

/*
if (! empty($_POST['txtUsername']) && ! empty($conf->banque->enabled) && (empty($conf_fkaccount_cash) && empty($conf_fkaccount_cheque) && empty($conf_fkaccount_cb)))
{
	$langs->load("errors");
	$retour=$langs->trans("ErrorModuleSetupNotComplete");
    header('Location: '.DOL_URL_ROOT.'/cashdesk/index.php?err='.urlencode($retour).'&user='.$username.'&socid='.$thirdpartyid.'&warehouseid='.$warehouseid);
    exit;
}
*/

// Check password
$auth = new Auth($db);
$retour = $auth->verif($username, $password);

if ( $retour >= 0 )
{
	$return=array();

	$sql = "SELECT rowid, name, firstname";
	$sql.= " FROM ".MAIN_DB_PREFIX."user";
	$sql.= " WHERE login = '".$username."'";
	$sql.= " AND entity IN (0,".$conf->entity.")";

	$result = $db->query($sql);
	if ($result)
	{
		$tab = $db->fetch_array($res);

		foreach ( $tab as $key => $value )
		{
			$return[$key] = $value;
		}

		$_SESSION['uid'] = $tab['rowid'];
		$_SESSION['uname'] = $username;
		$_SESSION['nom'] = $tab['name'];
		$_SESSION['prenom'] = $tab['firstname'];
		$_SESSION['CASHDESK_ID_THIRDPARTY'] = $thirdpartyid;
        $_SESSION['CASHDESK_ID_WAREHOUSE'] = $warehouseid;
        $_SESSION['CASHDESK_ID_BANKACCOUNT_CASH'] = ($bankid_cash > 0 ? $bankid_cash : '');
        $_SESSION['CASHDESK_ID_BANKACCOUNT_CHEQUE'] = ($bankid_cheque > 0 ? $bankid_cheque : '');
        $_SESSION['CASHDESK_ID_BANKACCOUNT_CB'] = ($bankid_cb > 0 ? $bankid_cb : '');
        //var_dump($_SESSION);exit;

		header('Location: '.DOL_URL_ROOT.'/cashdesk/affIndex.php?menu=facturation&id=NOUV');
		exit;
	}
	else
	{
		dol_print_error($db);
	}

}
else
{
	$langs->load("errors");
    $langs->load("other");
	$retour=$langs->trans("ErrorBadLoginPassword");
	header('Location: '.DOL_URL_ROOT.'/cashdesk/index.php?err='.urlencode($retour).'&user='.$username.'&socid='.$thirdpartyid.'&warehouseid='.$warehouseid);
	exit;
}

?>