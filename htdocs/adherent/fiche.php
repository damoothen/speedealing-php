<?php
/* Copyright (C) 2001-2004 Rodolphe Quiedeville		<rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003 Jean-Louis Bergamo		<jlb@j1b.org>
 * Copyright (C) 2004-2011 Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin			<regis@dolibarr.fr>
 * Copyright (C) 2011-2012	Herve Prot				<herve.prot@symeos.com>
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

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/lib/member.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/company.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/images.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent.class.php");
require_once(DOL_DOCUMENT_ROOT . "/adherent/class/adherent_card.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/extrafields.class.php");
require_once(DOL_DOCUMENT_ROOT . "/compta/bank/class/account.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formcompany.class.php");
if ($conf->mips->enabled)
	dol_include_once("/mips/class/mips.class.php");

$langs->load("companies");
$langs->load("bills");
$langs->load("members");
$langs->load("users");


$action = GETPOST('action', 'alpha');
$backtopage = GETPOST('backtopage', 'alpha');
$confirm = GETPOST('confirm', 'alpha');
$rowid = GETPOST('id', 'alpha');
$typeid = GETPOST('typeid', 'alpha');
$userid = GETPOST('userid', 'alpha');
$socid = GETPOST('socid', 'alpha');

$defaultdelay = 1;
$defaultdelayunit = 'y';

// Security check
$result = restrictedArea($user, 'adherent', $rowid);

$object = new Adherent($db);
$extrafields = new ExtraFields($db);

$errmsg = '';
$errmsgs = array();

if (!empty($rowid)) {
	// Load member
	$result = $object->fetch($rowid);

	// Define variables to know what current user can do on users
	$canadduser = ($user->admin || $user->rights->user->user->creer);
	// Define variables to know what current user can do on properties of user linked to edited member
	if ($object->user_id) {
		// $user est le user qui edite, $object->user_id est l'id de l'utilisateur lies au membre edite
		$caneditfielduser = ((($user->id == $object->user_id) && $user->rights->user->self->creer)
				|| (($user->id != $object->user_id) && $user->rights->user->user->creer));
		$caneditpassworduser = ((($user->id == $object->user_id) && $user->rights->user->self->password)
				|| (($user->id != $object->user_id) && $user->rights->user->user->password));
	}
}

// Define variables to know what current user can do on members
$canaddmember = $user->rights->adherent->creer;
// Define variables to know what current user can do on properties of a member
if ($rowid) {
	$caneditfieldmember = $user->rights->adherent->creer;
}

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
include_once(DOL_DOCUMENT_ROOT . '/core/class/hookmanager.class.php');
$hookmanager = new HookManager($db);
$hookmanager->initHooks(array('membercard'));


/*
 * 	Actions
 */

$parameters = array('socid' => $socid);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks

if ($action == 'setuserid' && ($user->rights->user->self->creer || $user->rights->user->user->creer)) {
	$error = 0;
	if (empty($user->rights->user->user->creer)) { // If can edit only itself user, we can link to itself only
		if ($userid != $user->id && $userid != $object->user_id) {
			$error++;
			$mesg = '<div class="error">' . $langs->trans("ErrorUserPermissionAllowsToLinksToItselfOnly") . '</div>';
		}
	}

	if (!$error) {
		if ($userid != $object->user_id) { // If link differs from currently in database
			$result = $object->setUserId($userid);
			if ($result < 0)
				dol_print_error($object->db, $object->error);
			$action = '';
		}
	}
}
if ($action == 'setsocid') {
	$error = 0;
	if (!$error) {
		if ($socid != $object->fk_soc) { // If link differs from currently in database
			$result = $object->setThirdPartyId($socid);
			if ($result < 0)
				dol_print_error($object->db, $object->error);
			$action = '';
		}
	}
}

// Create user from a member
if ($action == 'confirm_create_user' && $confirm == 'yes' && $user->rights->user->user->creer) {
	if ($result > 0) {
		// Creation user
		$nuser = new User($db);
		$result = $nuser->create_from_member($object, GETPOST('login'));

		if ($result < 0) {
			$langs->load("errors");
			$errmsg = $langs->trans($nuser->error);
		}
	} else {
		$errmsg = $object->error;
	}
}

// Create third party from a member
if ($action == 'confirm_create_thirdparty' && $confirm == 'yes' && $user->rights->societe->creer) {
	if ($result > 0) {
		// Creation user
		$company = new Societe($db);
		$result = $company->create_from_member($object, GETPOST('companyname'));

		if ($result < 0) {
			$langs->load("errors");
			$errmsg = $langs->trans($company->error);
			$errmsgs = $company->errors;
		}
	} else {
		$errmsg = $object->error;
	}
}

if ($action == 'confirm_sendinfo' && $confirm == 'yes') {
	/* if ($object->email) {
	  $from = $conf->email_from;
	  if ($conf->global->ADHERENT_MAIL_FROM)
	  $from = $conf->global->ADHERENT_MAIL_FROM;

	  $result = $object->send_an_email($langs->transnoentitiesnoconv("ThisIsContentOfYourCard") . "\n\n%INFOS%\n\n", $langs->transnoentitiesnoconv("CardContent"));

	  $langs->load("mails");
	  $mesg = $langs->trans("MailSuccessfulySent", $from, $object->email);
	  } */

	$model = new AdherentCard($db);
	$model->load('licenseCard');
	try {
		$card = new AdherentCard($db);
		$card->load($object->login);
	} catch (Exception $e) {
		$card->_id = $object->login;
	}

	$card->resume = $model->resume;
	$card->title = $model->title;
	$card->body = $model->body;

	$card->tms = dol_now();

	$card->makeSubstitution($object);

	$card->record(); // Save the Card
	// Send the card
	$send = new Mips($db);

	$send->egoTitle = $card->title;
	$send->egoBody = $card->body;
	$send->egoMod = "licence";

	$send->send(array("aaaa1234"), $object->login);

	Header("Location:" . $_SERVER["PHP_SELF"] . "?id=" . $object->id);
}

if ($action == 'update' && !$_POST["cancel"] && $user->rights->adherent->creer) {
	require_once(DOL_DOCUMENT_ROOT . "/core/lib/files.lib.php");

	$datenaiss = '';
	if (isset($_POST["naissday"]) && $_POST["naissday"]
			&& isset($_POST["naissmonth"]) && $_POST["naissmonth"]
			&& isset($_POST["naissyear"]) && $_POST["naissyear"]) {
		$datenaiss = dol_mktime(12, 0, 0, $_POST["naissmonth"], $_POST["naissday"], $_POST["naissyear"]);
	}

	// Create new object
	if ($result > 0) {
		$oldcopy = dol_clone($object);

		// Change values
		$object->civilite_id = trim($_POST["civilite_id"]);
		$object->Firstname = trim($_POST["prenom"]);
		$object->Lastname = trim($_POST["nom"]);
		$object->login = trim($_POST["login"]);
		$object->pass = trim($_POST["pass"]);

		$object->societe = trim($_POST["societe"]);
		$object->address = trim($_POST["address"]);
		$object->zip = trim($_POST["zipcode"]);
		$object->town = trim($_POST["town"]);
		$object->state_id = $_POST["departement_id"];
		$object->country_id = $_POST["country_id"];

		$object->phone = trim($_POST["phone"]);
		$object->phone_perso = trim($_POST["phone_perso"]);
		$object->phone_mobile = trim($_POST["phone_mobile"]);
		$object->email = trim($_POST["email"]);
		$object->naiss = $datenaiss;
		
		//$object->note        = trim($_POST["comment"]);
		$object->morphy = $_POST["morphy"];

		$object->amount = $_POST["amount"];

		if (GETPOST('deletephoto')) {
			unset($object->photo);
		} elseif (!empty($_FILES['photo']['name']))
			$object->photo = dol_sanitizeFileName($_FILES['photo']['name']);

		// Get status and public property
		$object->Status = $_POST["statut"];
		$object->public = $_POST["public"];

		// Get extra fields
		if (!is_object($object->array_options))
			$object->array_options = new stdClass();
		foreach ($_POST as $key => $value) {
			if (preg_match("/^options_/", $key)) {
				$object->array_options->$key = $_POST[$key];
			}
		}

		// Check if we need to also synchronize user information
		$nosyncuser = 0;
		if ($object->user_id) { // If linked to a user
			if ($user->id != $object->user_id && empty($user->rights->user->user->creer))
				$nosyncuser = 1;  // Disable synchronizing
		}

		// Check if we need to also synchronize password information
		$nosyncuserpass = 0;
		if ($object->user_id) { // If linked to a user
			if ($user->id != $object->user_id && empty($user->rights->user->user->password))
				$nosyncuserpass = 1; // Disable synchronizing
		}

		$result = $object->update($user, 0, $nosyncuser, $nosyncuserpass);
		if ($result >= 0 && !count($object->errors)) {
			$file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);

			if (GETPOST('deletephoto')) {
				$object->deleteFile($oldcopy->photo);
			}
			if ($file_OK) {
				if (image_format_supported($_FILES['photo']['name']) > 0) {
					$object->storeFile('photo');
				} else {
					$errmsgs[] = "ErrorBadImageFormat";
				}
			}

			// Rajoute l'utilisateur dans les divers abonnements (mailman, spip, etc...)
			if (($oldcopy->email != $object->email) || ($oldcopy->typeid != $object->typeid)) {
				if ($oldcopy->email != $object->email) { // If email has changed we delete mailman subscription for old email
					if ($oldcopy->del_to_abo() < 0) {
						// error
						$errmsgs[] = $langs->trans("FailedToCleanMailmanList") . ': ' . $object->error . "<br>\n";
					}
				}
				if ($object->add_to_abo() < 0) { // We add subscription if new email or new type (new type may means more mailing-list to subscribe)
					// error
					$errmsgs[] = $langs->trans("FailedToAddToMailmanList") . ': ' . $object->error . "<br>\n";
				}
			}

			$rowid = $object->id;
			$action = '';

			if (!empty($backtopage)) {
				header("Location: " . $backtopage);
				exit;
			}
		} else {
			if ($object->error)
				$errmsg = $object->error;
			else
				$errmsgs = $object->errors;
			$action = '';
		}
	}
}

if ($action == 'add' && $user->rights->adherent->creer) {
	$datenaiss = '';
	if (isset($_POST["naissday"]) && $_POST["naissday"]
			&& isset($_POST["naissmonth"]) && $_POST["naissmonth"]
			&& isset($_POST["naissyear"]) && $_POST["naissyear"]) {
		$datenaiss = dol_mktime(12, 0, 0, $_POST["naissmonth"], $_POST["naissday"], $_POST["naissyear"]);
	}
	$datecotisation = '';
	if (isset($_POST["reday"]) && isset($_POST["remonth"]) && isset($_POST["reyear"])) {
		$datecotisation = dol_mktime(12, 0, 0, $_POST["remonth"], $_POST["reday"], $_POST["reyear"]);
	}

	$typeid = $_POST["typeid"];
	$civilite_id = $_POST["civilite_id"];
	$nom = $_POST["nom"];
	$prenom = $_POST["prenom"];
	$societe = $_POST["societe"];
	$address = $_POST["address"];
	$zip = $_POST["zipcode"];
	$town = $_POST["town"];
	$state_id = $_POST["departement_id"];
	$country_id = $_POST["country_id"];

	$phone = $_POST["phone"];
	$phone_perso = $_POST["phone_perso"];
	$phone_mobile = $_POST["phone_mobile"];
	$email = $_POST["member_email"];
	$login = $_POST["member_login"];
	$pass = $_POST["password"];
	$photo = $_POST["photo"];
	//$comment=$_POST["comment"];
	$morphy = $_POST["morphy"];
	$cotisation = $_POST["cotisation"];
	$public = $_POST["public"];

	$userid = $_POST["userid"];
	$socid = $_POST["socid"];

	$object->civilite_id = $civilite_id;
	$object->Firstname = $prenom;
	$object->Lastname = $nom;
	$object->societe = $societe;
	$object->address = $address;
	$object->zip = $zip;
	$object->town = $town;
	$object->fk_departement = $state_id;
	$object->state_id = $state_id;
	$object->pays_id = $country_id;
	$object->country_id = $country_id;
	$object->phone = $phone;
	$object->phone_perso = $phone_perso;
	$object->phone_mobile = $phone_mobile;
	$object->email = $email;
	$object->login = $login;
	$object->pass = $pass;
	$object->naiss = $datenaiss;
	$object->photo = $photo;
	$object->Tag = array($typeid);
	//$object->note        = $comment;
	$object->morphy = $morphy;
	$object->user_id = $userid;
	$object->fk_soc = $socid;
	$object->public = $public;

	// Get extra fields
	foreach ($_POST as $key => $value) {
		if (preg_match("/^options_/", $key)) {
			$object->array_options->$key = $_POST[$key];
		}
	}

	// Check parameters
	if (empty($morphy) || $morphy == "-1") {
		$error++;
		$errmsg .= $langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Nature")) . "<br>\n";
	}
	// Test si le login existe deja
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) {
		if (empty($login)) {
			$error++;
			$errmsg .= $langs->trans("ErrorFieldRequired", $langs->trans("Login")) . "<br>\n";
		} else {
			$sql = "SELECT login FROM " . MAIN_DB_PREFIX . "adherent WHERE login='" . $db->escape($login) . "'";
			$result = $db->query($sql);
			if ($result) {
				$num = $db->num_rows($result);
			}
			if ($num) {
				$error++;
				$langs->load("errors");
				$errmsg .= $langs->trans("ErrorLoginAlreadyExists", $login) . "<br>\n";
			}
		}
		if (empty($pass)) {
			$error++;
			$errmsg .= $langs->trans("ErrorFieldRequired", $langs->transnoentities("Password")) . "<br>\n";
		}
	}
	if (empty($nom)) {
		$error++;
		$langs->load("errors");
		$errmsg .= $langs->trans("ErrorFieldRequired", $langs->transnoentities("Lastname")) . "<br>\n";
	}
	if ($morphy != 'mor' && (!isset($prenom) || $prenom == '')) {
		$error++;
		$langs->load("errors");
		$errmsg .= $langs->trans("ErrorFieldRequired", $langs->transnoentities("Firstname")) . "<br>\n";
	}
	if (!($typeid > 0)) { // Keep () before !
		$error++;
		$errmsg .= $langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Type")) . "<br>\n";
	}
	if ($conf->global->ADHERENT_MAIL_REQUIRED && !isValidEMail($email)) {
		$error++;
		$langs->load("errors");
		$errmsg .= $langs->trans("ErrorBadEMail", $email) . "<br>\n";
	}
	$public = 0;
	if (isset($public))
		$public = 1;

	if (!$error) {
		$db->begin();

		// Email a peu pres correct et le login n'existe pas
		$result = $object->create($user);
		if ($result > 0) {
			$db->commit();
			$rowid = $object->id;
			$action = '';
		} else {
			$db->rollback();

			if ($object->error)
				$errmsg = $object->error;
			else
				$errmsgs = $object->errors;

			$action = 'create';
		}
	}
	else {
		$action = 'create';
	}
}

if ($user->rights->adherent->supprimer && $action == 'confirm_delete' && $confirm == 'yes') {
	$result = $object->delete($rowid);
	if ($result > 0) {
		if (!empty($backtopage)) {
			header("Location: " . $backtopage);
			exit;
		} else {
			Header("Location: liste.php");
			exit;
		}
	} else {
		$errmesg = $object->error;
	}
}

if ($user->rights->adherent->creer && $action == 'confirm_valid' && $confirm == 'yes') {
	$result = $object->validate($user);

	$adht = new AdherentType($db);
	$result = $adht->getView('list', array("key" => $object->typeid, 'limit' => 1));
	if (count($result->rows))
		$adht->fetch($result->rows[0]->value->_id);

	if ($result >= 0 && !count($object->errors)) {
		// Send confirmation Email (selon param du type adherent sinon generique)
		if ($object->email && $_POST["send_mail"]) {
			$result = $object->send_an_email($adht->getMailOnValid(), $conf->global->ADHERENT_MAIL_VALID_SUBJECT, array(), array(), array(), "", "", 0, 2);
			if ($result < 0) {
				$errmsg.=$object->error;
			}
		}

		// Rajoute l'utilisateur dans les divers abonnements (mailman, spip, etc...)
		if ($object->add_to_abo() < 0) {
			// error
			$errmsg.= $langs->trans("ErrorFailedToAddToMailmanList") . ': ' . $object->error . "<br>\n";
		}
	} else {
		if ($object->error)
			$errmsg = $object->error;
		else
			$errmsgs = $object->errors;
		$action = '';
	}
}

if ($user->rights->adherent->supprimer && $action == 'confirm_resign') {
	if ($confirm == 'yes') {
		$adht = new AdherentType($db);
		$result = $adht->getView('list', array("key" => $object->typeid, 'limit' => 1));
		if (count($result->rows))
			$adht->fetch($result->rows[0]->value->_id);

		$result = $object->resiliate($user);

		if ($result >= 0 && !count($object->errors)) {
			if ($object->email && $_POST["send_mail"]) {
				$result = $object->send_an_email($adht->getMailOnResiliate(), $conf->global->ADHERENT_MAIL_RESIL_SUBJECT, array(), array(), array(), "", "", 0, -1);
			}
			if ($result < 0) {
				$errmsg.=$object->error;
			}

			// supprime l'utilisateur des divers abonnements ..
			if ($object->del_to_abo() < 0) {
				// error
				$errmsg.=$langs->trans("FaildToRemoveFromMailmanList") . ': ' . $object->error . "<br>\n";
			}
		} else {
			if ($object->error)
				$errmsg = $object->error;
			else
				$errmsgs = $object->errors;
			$action = '';
		}
	}
	if (!empty($backtopage) && !$errmsg) {
		header("Location: " . $backtopage);
		exit;
	}
}

// SPIP Management
if ($user->rights->adherent->supprimer && $action == 'confirm_del_spip' && $confirm == 'yes') {
	if (!count($object->errors)) {
		if (!$object->del_to_spip()) {
			$errmsg.="Echec de la suppression de l'utilisateur dans spip: " . $object->error . "<BR>\n";
		}
	}
}

if ($user->rights->adherent->creer && $action == 'confirm_add_spip' && $confirm == 'yes') {
	if (!count($object->errors)) {
		if (!$object->add_to_spip()) {
			$errmsg.="Echec du rajout de l'utilisateur dans spip: " . $object->error . "<BR>\n";
		}
	}
}

if ($user->rights->adherent->cotisation->creer && $action == 'cotisation' && !$_POST["cancel"]) {
	$error = 0;

	$langs->load("banks");

	$result = $object->fetch($rowid);
	$adht = new AdherentType($db);
	$result = $adht->getView('list', array("key" => $object->typeid, 'limit' => 1));
	if (count($result->rows))
		$adht->fetch($result->rows[0]->value->_id);

	// Subscription informations
	$datecotisation = 0;
	$datesubend = 0;
	$paymentdate = 0;
	if ($_POST["reyear"] && $_POST["remonth"] && $_POST["reday"]) {
		$datecotisation = dol_mktime(0, 0, 0, $_POST["remonth"], $_POST["reday"], $_POST["reyear"]);
	}
	if ($_POST["endyear"] && $_POST["endmonth"] && $_POST["endday"]) {
		$datesubend = dol_mktime(0, 0, 0, $_POST["endmonth"], $_POST["endday"], $_POST["endyear"]);
	}
	if ($_POST["paymentyear"] && $_POST["paymentmonth"] && $_POST["paymentday"]) {
		$paymentdate = dol_mktime(0, 0, 0, $_POST["paymentmonth"], $_POST["paymentday"], $_POST["paymentyear"]);
	}
	$cotisation = $_POST["cotisation"]; // Amount of subscription
	$label = $_POST["label"];

	// Payment informations
	$accountid = $_POST["accountid"];
	$operation = $_POST["operation"]; // Payment mode
	$num_chq = $_POST["num_chq"];
	$emetteur_nom = $_POST["chqemetteur"];
	$emetteur_banque = $_POST["chqbank"];
	$option = $_POST["paymentsave"];
	if (empty($option))
		$option = 'none';

	// Check parameters
	if (!$datecotisation) {
		$error++;
		$langs->load("errors");
		$errmsg = $langs->trans("ErrorBadDateFormat", $langs->transnoentitiesnoconv("DateSubscription"));
		$action = 'addsubscription';
	}
	if (GETPOST('end') && !$datesubend) {
		$error++;
		$langs->load("errors");
		$errmsg = $langs->trans("ErrorBadDateFormat", $langs->transnoentitiesnoconv("DateEndSubscription"));
		$action = 'addsubscription';
	}
	if (!$datesubend) {
		$datesubend = dol_time_plus_duree(dol_time_plus_duree($datecotisation, $defaultdelay, $defaultdelayunit), -1, 'd');
	}
	if (($option == 'bankviainvoice' || $option == 'bankdirect') && !$paymentdate) {
		$error++;
		$errmsg = $langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("DatePayment"));
		$action = 'addsubscription';
	}

	// Check if a payment is mandatory or not
	if (!$error && $adht->cotisation) { // Type adherent soumis a cotisation
		if (!is_numeric($_POST["cotisation"])) {
			// If field is '' or not a numeric value
			$errmsg = $langs->trans("ErrorFieldRequired", $langs->transnoentities("Amount"));
			$error++;
			$action = 'addsubscription';
		} else {
			if ($conf->banque->enabled && $_POST["paymentsave"] != 'none') {
				if ($_POST["cotisation"]) {
					if (!$_POST["label"])
						$errmsg = $langs->trans("ErrorFieldRequired", $langs->transnoentities("Label"));
					if ($_POST["paymentsave"] != 'invoiceonly' && !$_POST["operation"])
						$errmsg = $langs->trans("ErrorFieldRequired", $langs->transnoentities("PaymentMode"));
					if ($_POST["paymentsave"] != 'invoiceonly' && !$_POST["accountid"])
						$errmsg = $langs->trans("ErrorFieldRequired", $langs->transnoentities("FinancialAccount"));
				}
				else {
					if ($_POST["accountid"])
						$errmsg = $langs->trans("ErrorDoNotProvideAccountsIfNullAmount");
				}
				if ($errmsg)
					$action = 'addsubscription';
			}
		}
	}

	if (!$error && $action == 'cotisation') {

		// Create subscription
		$crowid = $object->cotisation($datecotisation, $cotisation, $accountid, $operation, $label, $num_chq, $emetteur_nom, $emetteur_banque, $datesubend, $option);
		if ($crowid <= 0) {
			$error++;
			$errmsg = $object->error;
			$errmsgs = $object->errors;
		}

		if (!$error) {
			// Insert into bank account directlty (if option choosed for) + link to llx_cotisation if option is 'bankdirect'
			if ($option == 'bankdirect' && $accountid) {
				require_once(DOL_DOCUMENT_ROOT . '/compta/bank/class/account.class.php');

				$acct = new Account($db);
				$result = $acct->fetch($accountid);

				$dateop = $paymentdate;

				$insertid = $acct->addline($dateop, $operation, $label, $cotisation, $num_chq, '', $user, $emetteur_nom, $emetteur_banque);
				if ($insertid > 0) {
					$inserturlid = $acct->add_url_line($insertid, $object->id, DOL_URL_ROOT . '/adherents/fiche.php?rowid=', $object->getFullname($langs), 'member');
					if ($inserturlid > 0) {
						// Met a jour la table cotisation
						$sql = "UPDATE " . MAIN_DB_PREFIX . "cotisation SET fk_bank=" . $insertid;
						$sql.=" WHERE rowid=" . $crowid;

						dol_syslog("card_subscriptions::cotisation sql=" . $sql);
						$resql = $db->query($sql);
						if (!$resql) {
							$error++;
							$errmsg = $db->lasterror();
						}
					} else {
						$error++;
						$errmsg = $acct->error;
					}
				} else {
					$error++;
					$errmsg = $acct->error;
				}
			}

			// If option choosed, we create invoice
			if (($option == 'bankviainvoice' && $accountid) || $option == 'invoiceonly') {
				require_once(DOL_DOCUMENT_ROOT . "/compta/facture/class/facture.class.php");
				require_once(DOL_DOCUMENT_ROOT . "/compta/facture/class/paymentterm.class.php");

				$invoice = new Facture($db);
				$customer = new Societe($db);
				$result = $customer->fetch($object->fk_soc);
				if ($result <= 0) {
					$errmsg = $customer->error;
					$error++;
				}

				// Create draft invoice
				$invoice->type = 0;
				$invoice->cond_reglement_id = $customer->cond_reglement_id;
				if (empty($invoice->cond_reglement_id)) {
					$paymenttermstatic = new PaymentTerm($db);
					$invoice->cond_reglement_id = $paymenttermstatic->getDefaultId();
					if (empty($invoice->cond_reglement_id)) {
						$error++;
						$errmsg = 'ErrorNoPaymentTermRECEPFound';
					}
				}
				$invoice->socid = $object->fk_soc;
				$invoice->date = $datecotisation;

				$result = $invoice->create($user);
				if ($result <= 0) {
					$errmsg = $invoice->error;
					$error++;
				}

				// Add line to draft invoice
				$idprodsubscription = 0;
				$vattouse = get_default_tva($mysoc, $customer, $idprodsubscription);
				//print xx".$vattouse." - ".$mysoc." - ".$customer;exit;
				$result = $invoice->addline($invoice->id, $label, 0, 1, $vattouse, 0, 0, $idprodsubscription, 0, $datecotisation, $datesubend, 0, 0, '', 'TTC', $cotisation, 1);
				if ($result <= 0) {
					$errmsg = $invoice->error;
					$error++;
				}

				// Validate invoice
				$result = $invoice->validate($user);

				// Add payment onto invoice
				if ($option == 'bankviainvoice' && $accountid) {
					require_once(DOL_DOCUMENT_ROOT . '/compta/paiement/class/paiement.class.php');
					require_once(DOL_DOCUMENT_ROOT . '/compta/bank/class/account.class.php');
					require_once(DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php');

					// Creation de la ligne paiement
					$amounts[$invoice->id] = price2num($cotisation);
					$paiement = new Paiement($db);
					$paiement->datepaye = $paymentdate;
					$paiement->amounts = $amounts;
					$paiement->paiementid = dol_getIdFromCode($db, $operation, 'c_paiement');
					$paiement->num_paiement = $num_chq;
					$paiement->note = $label;

					if (!$error) {
						$paiement_id = $paiement->create($user);
						if (!$paiement_id > 0) {
							$errmsg = $paiement->error;
							$error++;
						}
					}

					if (!$error) {
						$bank_line_id = $paiement->addPaymentToBank($user, 'payment', '(SubscriptionPayment)', $accountid, $emetteur_nom, $emetteur_banque);
						if (!$bank_line_id > 0) {
							$errmsg = $paiement->error;
							$error++;
						}

						// Update fk_bank for subscriptions
						$sql = 'UPDATE ' . MAIN_DB_PREFIX . 'cotisation SET fk_bank=' . $bank_line_id;
						$sql.= ' WHERE rowid=' . $crowid;
						dol_syslog('sql=' . $sql);
						$result = $db->query($sql);
						if (!$result) {
							$error++;
						}
					}
				}
			}
		}

		// Send email
		if (!$error) {
			// Send confirmation Email
			if ($object->email && $_POST["sendmail"]) {
				$subjecttosend = $object->makeSubstitution($conf->global->ADHERENT_MAIL_COTIS_SUBJECT);
				$texttosend = $object->makeSubstitution($adht->getMailOnSubscription());

				$result = $object->send_an_email($texttosend, $subjecttosend, array(), array(), array(), "", "", 0, -1);
				if ($result < 0)
					$errmsg = $object->error;
			}

			$_POST["cotisation"] = '';
			$_POST["accountid"] = '';
			$_POST["operation"] = '';
			$_POST["label"] = '';
			$_POST["num_chq"] = '';
		}
	}
	$action = '';
}



/*
 * View
 */

$form = new Form($db);
$formcompany = new FormCompany($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label('member');

$help_url = 'EN:Module_Foundations|FR:Module_Adh&eacute;rents|ES:M&oacute;dulo_Miembros';
llxHeader('', $langs->trans("Member"), $help_url);

$countrynotdefined = $langs->trans("ErrorSetACountryFirst") . ' (' . $langs->trans("SeeAbove") . ')';

if ($action == 'create') {
	/*	 * ************************************************************************* */
	/*                                                                            */
	/* Fiche creation                                                             */
	/*                                                                            */
	/*	 * ************************************************************************* */
	$object->fk_departement = $_POST["departement_id"];

	// We set country_id, country_code and country for the selected country
	$object->country_id = GETPOST('country_id', 'int') ? GETPOST('country_id', 'int') : $mysoc->country_id;
	if ($object->country_id) {
		$tmparray = getCountry($object->country_id, 'all');
		$object->pays_code = $tmparray['code'];
		$object->pays = $tmparray['code'];
		$object->country_code = $tmparray['code'];
		$object->country = $tmparray['label'];
	}

	print_fiche_titre($langs->trans("NewMember"));

	dol_htmloutput_mesg($errmsg, $errmsgs, 'error');
	dol_htmloutput_mesg($mesg, $mesgs);

	if ($conf->use_javascript_ajax) {
		print "\n" . '<script type="text/javascript" language="javascript">';
		print 'jQuery(document).ready(function () {
                    jQuery("#selectcountry_id").change(function() {
                        document.formsoc.action.value="create";
                        document.formsoc.submit();
                    });
               })';
		print '</script>' . "\n";
	}

	print '<form name="formsoc" action="' . $_SERVER["PHP_SELF"] . '" method="post" enctype="multipart/form-data">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<input type="hidden" name="action" value="add">';

	print '<table class="border" width="100%">';

	// Login
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) {
		print '<tr><td><span class="fieldrequired">' . $langs->trans("Login") . ' / ' . $langs->trans("Id") . '</span></td><td><input type="text" name="member_login" size="40" value="' . (isset($_POST["member_login"]) ? $_POST["member_login"] : $object->login) . '"></td></tr>';
	}

	// Moral-Physique
	$morphys["phy"] = $langs->trans("Physical");
	$morphys["mor"] = $langs->trans("Moral");
	print '<tr><td><span class="fieldrequired">' . $langs->trans("Nature") . "</span></td><td>\n";
	print $form->selectarray("morphy", $morphys, GETPOST('morphy', 'alpha') ? GETPOST('morphy', 'alpha') : $object->morphy, 1);
	print "</td>\n";

	// Type
	if ($typeid) {
		print '<tr><td><span class="fieldrequired">' . $langs->trans("MemberType") . '</span></td><td>';
		print '<input type="hidden" name="typeid" value="' . $typeid . '">';
		print $typeid;
		print "</td>\n";
	}

	// Company
	print '<tr><td>' . $langs->trans("Company") . '</td><td><input type="text" name="societe" size="40" value="' . (GETPOST('societe', 'alpha') ? GETPOST('societe', 'alpha') : $object->societe) . '"></td></tr>';

	// Civility
	print '<tr><td>' . $langs->trans("UserTitle") . '</td><td>';
	print $formcompany->select_civility(GETPOST('civilite_id', 'int') ? GETPOST('civilite_id', 'int') : $object->civilite_id, 'civilite_id') . '</td>';
	print '</tr>';

	// Lastname
	print '<tr><td><span class="fieldrequired">' . $langs->trans("Lastname") . '</span></td><td><input type="text" name="nom" value="' . (GETPOST('nom', 'alpha') ? GETPOST('nom', 'alpha') : $object->Lastname) . '" size="40"></td>';
	print '</tr>';

	// Firstname
	print '<tr><td><span class="fieldrequired">' . $langs->trans("Firstname") . '</td><td><input type="text" name="prenom" size="40" value="' . (GETPOST('prenom', 'alpha') ? GETPOST('prenom', 'alpha') : $object->Firstname) . '"></td>';
	print '</tr>';

	// Password
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) {
		require_once(DOL_DOCUMENT_ROOT . "/core/lib/security2.lib.php");
		$generated_password = getRandomPassword('');
		print '<tr><td><span class="fieldrequired">' . $langs->trans("Password") . '</span></td><td>';
		print '<input size="30" maxsize="32" type="text" name="password" value="' . $generated_password . '">';
		print '</td></tr>';
	}

	// Address
	print '<tr><td valign="top">' . $langs->trans("Address") . '</td><td>';
	print '<textarea name="address" wrap="soft" cols="40" rows="2">' . (GETPOST('address', 'alpha') ? GETPOST('address', 'alpha') : $object->address) . '</textarea>';
	print '</td></tr>';

	// Zip / Town
	print '<tr><td>' . $langs->trans("Zip") . ' / ' . $langs->trans("Town") . '</td><td>';
	print $formcompany->select_ziptown((GETPOST('zipcode', 'alpha') ? GETPOST('zipcode', 'alpha') : $object->zip), 'zipcode', array('town', 'selectcountry_id', 'departement_id'), 6);
	print ' ';
	print $formcompany->select_ziptown((GETPOST('town', 'alpha') ? GETPOST('town', 'alpha') : $object->town), 'town', array('zipcode', 'selectcountry_id', 'departement_id'));
	print '</td></tr>';

	// Country
	$object->country_id = $object->country_id ? $object->country_id : $mysoc->country_id;
	print '<tr><td width="25%">' . $langs->trans('Country') . '</td><td>';
	print $form->select_country(GETPOST('country_id', 'alpha') ? GETPOST('country_id', 'alpha') : $object->country_id, 'country_id');
	if ($user->admin)
		print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
	print '</td></tr>';

	// State
	if (empty($conf->global->MEMBER_DISABLE_STATE)) {
		print '<tr><td>' . $langs->trans('State') . '</td><td>';
		if ($object->country_id) {
			print $formcompany->select_state(GETPOST('departement_id', 'int') ? GETPOST('departement_id', 'int') : $object->fk_departement, $object->country_code);
		} else {
			print $countrynotdefined;
		}
		print '</td></tr>';
	}

	// Tel pro
	print '<tr><td>' . $langs->trans("PhonePro") . '</td><td><input type="text" name="phone" size="20" value="' . (GETPOST('phone', 'alpha') ? GETPOST('phone', 'alpha') : $object->phone) . '"></td></tr>';

	// Tel perso
	print '<tr><td>' . $langs->trans("PhonePerso") . '</td><td><input type="text" name="phone_perso" size="20" value="' . (GETPOST('phone_perso', 'alpha') ? GETPOST('phone_perso', 'alpha') : $object->phone_perso) . '"></td></tr>';

	// Tel mobile
	print '<tr><td>' . $langs->trans("PhoneMobile") . '</td><td><input type="text" name="phone_mobile" size="20" value="' . (GETPOST('phone_mobile', 'alpha') ? GETPOST('phone_mobile', 'alpha') : $object->phone_mobile) . '"></td></tr>';

	// EMail
	print '<tr><td>' . ($conf->global->ADHERENT_MAIL_REQUIRED ? '<span class="fieldrequired">' : '') . $langs->trans("EMail") . ($conf->global->ADHERENT_MAIL_REQUIRED ? '</span>' : '') . '</td><td><input type="text" name="member_email" size="40" value="' . (GETPOST('member_email', 'alpha') ? GETPOST('member_email', 'alpha') : $object->email) . '"></td></tr>';

	// Birthday
	print "<tr><td>" . $langs->trans("Birthday") . "</td><td>\n";
	$form->select_date(($object->naiss ? $object->naiss : -1), 'naiss', '', '', 1, 'formsoc');
	print "</td></tr>\n";

	// Profil public
	print "<tr><td>" . $langs->trans("Public") . "</td><td>\n";
	print $form->selectyesno("public", $object->public, 1);
	print "</td></tr>\n";

	// Other attributes
	$parameters = array();
	$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action); // Note that $action and $object may have been modified by hook
	if (empty($reshook) && !empty($extrafields->attribute_label)) {
		foreach ($extrafields->attribute_label as $key => $label) {
			$value = (isset($_POST["options_" . $key]) ? GETPOST('options_' . $key, 'alpha') : $object->array_options["options_" . $key]);
			print '<tr><td>' . $label . '</td><td>';
			print $extrafields->showInputField($key, $value);
			print '</td></tr>' . "\n";
		}
	}

	/*
	  // Third party Dolibarr
	  if ($conf->societe->enabled)
	  {
	  print '<tr><td>'.$langs->trans("LinkedToDolibarrThirdParty").'</td><td class="valeur">';
	  print $form->select_company($object->fk_soc,'socid','',1);
	  print '</td></tr>';
	  }

	  // Login Dolibarr
	  print '<tr><td>'.$langs->trans("LinkedToDolibarrUser").'</td><td class="valeur">';
	  print $form->select_users($object->user_id,'userid',1);
	  print '</td></tr>';
	 */

	print "</table>\n";
	print '<br>';

	print '<center><input type="submit" class="button" value="' . $langs->trans("AddMember") . '"></center>';

	print "</form>\n";
}

if ($action == 'edit') {
	/*	 * ******************************************
	 *
	 * Fiche en mode edition
	 *
	 * ****************************************** */

	$res = $object->fetch($rowid);
	if ($res < 0) {
		dol_print_error($db, $object->error);
		exit;
	}
	//$res=$object->fetch_optionals($object->id,$extralabels);
	//if ($res < 0) { dol_print_error($db); exit; }

	//$adht = new AdherentType($db);
	//$adht->fetch($object->typeid);
	// We set country_id, and country_code, country of the chosen country
	if (isset($_POST["pays"]) || $object->country_id) {
		$sql = "SELECT rowid, code, libelle as label from " . MAIN_DB_PREFIX . "c_pays where rowid = " . (isset($_POST["pays"]) ? $_POST["pays"] : $object->country_id);
		$resql = $db->query($sql);
		if ($resql) {
			$obj = $db->fetch_object($resql);
		} else {
			dol_print_error($db);
		}
		$object->pays_id = $obj->rowid;
		$object->pays_code = $obj->code;
		$object->pays = $langs->trans("Country" . $obj->code) ? $langs->trans("Country" . $obj->code) : $obj->label;
		$object->country_id = $obj->rowid;
		$object->country_code = $obj->code;
		$object->country = $langs->trans("Country" . $obj->code) ? $langs->trans("Country" . $obj->code) : $obj->label;
	}

	$head = member_prepare_head($object);

	$titre = $langs->trans("Member");
	print_fiche_titre($titre);
	print '<div class="container">';
	print '<div class="row">';
	dol_fiche_head($head, 'general', $langs->trans("Member"), 0, 'user');

	dol_htmloutput_errors($errmsg, $errmsgs);
	dol_htmloutput_mesg($mesg);

	if ($conf->use_javascript_ajax) {
		print "\n" . '<script type="text/javascript" language="javascript">';
		print 'jQuery(document).ready(function () {
                    jQuery("#selectcountry_id").change(function() {
	               	    document.formsoc.action.value="edit";
                        document.formsoc.submit();
                    });
               })';
		print '</script>' . "\n";
	}

	$rowspan = 15;
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED))
		$rowspan++;
	if ($conf->societe->enabled)
		$rowspan++;

	print '<form name="formsoc" action="' . $_SERVER["PHP_SELF"] . '" method="post" enctype="multipart/form-data">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '" />';
	print '<input type="hidden" name="action" value="update" />';
	print '<input type="hidden" name="id" value="' . $rowid . '" />';
	print '<input type="hidden" name="statut" value="' . $object->Status . '" />';
	if ($backtopage)
		print '<input type="hidden" name="backtopage" value="' . ($backtopage != '1' ? $backtopage : $_SERVER["HTTP_REFERER"]) . '">';

	print '<table class="border" width="100%">';

	// Ref
	print '<tr><td>' . $langs->trans("Ref") . '</td><td class="valeur" colspan="2">' . $object->_rev . '</td></tr>';

	// Login
	if (empty($object->login)) {
		print '<tr><td><span class="fieldrequired">' . $langs->trans("Login") . ' / ' . $langs->trans("Id") . '</span></td><td colspan="2"><input type="text" name="login" size="30" value="' . (isset($_POST["login"]) ? $_POST["login"] : $object->login) . '"></td></tr>';
	} else {
		print '<tr><td><span class="fieldrequired">' . $langs->trans("Login") . ' / ' . $langs->trans("Id") . '</span></td><td colspan="2">' . $object->login . '</td></tr>';
		print '<input type="hidden" name="login" size="30" value="' . (isset($_POST["login"]) ? $_POST["login"] : $object->login) . '">';
	}

	// Physique-Moral
	$morphys["phy"] = $langs->trans("Physical");
	$morphys["mor"] = $langs->trans("Morale");
	print '<tr><td><span class="fieldrequired">' . $langs->trans("Nature") . '</span></td><td>';
	print $form->selectarray("morphy", $morphys, isset($_POST["morphy"]) ? $_POST["morphy"] : $object->morphy);
	print "</td>";
	// Photo
	print '<td align="center" valign="middle" width="25%" rowspan="' . $rowspan . '">';
	print $form->showphoto('memberphoto', $object) . "\n";
	if ($caneditfieldmember) {
		if ($object->photo)
			print "<br>\n";
		print '<table class="nobordernopadding">';
		if ($object->photo)
			print '<tr><td align="center"><input type="checkbox" class="flat" name="deletephoto" id="photodelete"> ' . $langs->trans("Delete") . '<br><br></td></tr>';
		print '<tr><td>' . $langs->trans("PhotoFile") . '</td></tr>';
		print '<tr><td><input type="file" class="flat" name="photo" id="photoinput"></td></tr>';
		print '</table>';
	}
	print '</td>';

	// Type
	print '<tr><td><span class="fieldrequired">' . $langs->trans("MemberType") . '</span></td><td>';
	if ($user->rights->adherent->creer) {
		print '<ul id="array_tag_handler"></ul>';
		?>
		<script>
			$(document).ready(function() {
				$("#array_tag_handler").tagHandler({
					getData: { id: '<?php echo $object->id; ?>', class: '<?php echo get_class($object); ?>' },
					getURL: '<?php echo DOL_URL_ROOT . '/core/ajax/loadtaghandler.php'; ?>',
					updateData: { id: '<?php echo $object->id; ?>',class: '<?php echo get_class($object); ?>' },
					updateURL: '<?php echo DOL_URL_ROOT . '/core/ajax/savetaghandler.php'; ?>',
					autocomplete: true,
					autoUpdate: true
				});
			});
		</script>
		<?php
		//print $form->selectarray("typeid", $adht->liste_array(), (isset($_POST["typeid"]) ? $_POST["typeid"] : $object->typeid), 0, 0, 1);
	} else {
		print $object->getTagUrl(1);
		print '<input type="hidden" name="typeid" value="' . $object->typeid . '">';
	}
	print "</td></tr>";

	// Company
	print '<tr><td>' . $langs->trans("Company") . '</td><td><input type="text" name="societe" size="40" value="' . (isset($_POST["societe"]) ? $_POST["societe"] : $object->societe) . '"></td></tr>';

	// Civilite
	print '<tr><td width="20%">' . $langs->trans("UserTitle") . '</td><td width="35%">';
	print $formcompany->select_civility(isset($_POST["civilite_id"]) ? $_POST["civilite_id"] : $object->civilite_id) . "\n";
	print '</td>';
	print '</tr>';

	// Name
	print '<tr><td><span class="fieldrequired">' . $langs->trans("Lastname") . '</span></td><td><input type="text" name="nom" size="40" value="' . (isset($_POST["nom"]) ? $_POST["nom"] : $object->Lastname) . '"></td>';
	print '</tr>';

	// Firstname
	print '<tr><td><span class="fieldrequired">' . $langs->trans("Firstname") . '</td><td><input type="text" name="prenom" size="40" value="' . (isset($_POST["prenom"]) ? $_POST["prenom"] : $object->Firstname) . '"></td>';
	print '</tr>';

	// Password
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) {
		print '<tr><td><span class="fieldrequired">' . $langs->trans("Password") . '</span></td><td><input type="password" name="pass" size="30" value="' . (isset($_POST["pass"]) ? $_POST["pass"] : $object->pass) . '"></td></tr>';
	}

	// Address
	print '<tr><td>' . $langs->trans("Address") . '</td><td>';
	print '<textarea name="address" wrap="soft" cols="40" rows="2">' . (isset($_POST["address"]) ? $_POST["address"] : $object->address) . '</textarea>';
	print '</td></tr>';

	// Zip / Town
	print '<tr><td>' . $langs->trans("Zip") . ' / ' . $langs->trans("Town") . '</td><td>';
	print $formcompany->select_ziptown((isset($_POST["zipcode"]) ? $_POST["zipcode"] : $object->zip), 'zipcode', array('town', 'selectcountry_id', 'departement_id'), 6);
	print ' ';
	print $formcompany->select_ziptown((isset($_POST["town"]) ? $_POST["town"] : $object->town), 'town', array('zipcode', 'selectcountry_id', 'departement_id'));
	print '</td></tr>';

	// Country
	//$object->country_id=$object->country_id?$object->country_id:$mysoc->country_id;    // In edit mode we don't force to company country if not defined
	print '<tr><td width="25%">' . $langs->trans('Country') . '</td><td>';
	print $form->select_country(isset($_POST["country_id"]) ? $_POST["country_id"] : $object->country_id, 'country_id');
	if ($user->admin)
		print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
	print '</td></tr>';

	// State
	if (empty($conf->global->MEMBER_DISABLE_STATE)) {
		print '<tr><td>' . $langs->trans('State') . '</td><td>';
		print $formcompany->select_state($object->fk_departement, isset($_POST["country_id"]) ? $_POST["country_id"] : $object->country_id);
		print '</td></tr>';
	}

	// Tel
	print '<tr><td>' . $langs->trans("PhonePro") . '</td><td><input type="text" name="phone" size="20" value="' . (isset($_POST["phone"]) ? $_POST["phone"] : $object->phone) . '"></td></tr>';

	// Tel perso
	print '<tr><td>' . $langs->trans("PhonePerso") . '</td><td><input type="text" name="phone_perso" size="20" value="' . (isset($_POST["phone_perso"]) ? $_POST["phone_perso"] : $object->phone_perso) . '"></td></tr>';

	// Tel mobile
	print '<tr><td>' . $langs->trans("PhoneMobile") . '</td><td><input type="text" name="phone_mobile" size="20" value="' . (isset($_POST["phone_mobile"]) ? $_POST["phone_mobile"] : $object->phone_mobile) . '"></td></tr>';

	// EMail
	print '<tr><td>' . ($conf->global->ADHERENT_MAIL_REQUIRED ? '<span class="fieldrequired">' : '') . $langs->trans("EMail") . ($conf->global->ADHERENT_MAIL_REQUIRED ? '</span>' : '') . '</td><td><input type="text" name="email" size="40" value="' . (isset($_POST["email"]) ? $_POST["email"] : $object->email) . '"></td></tr>';

	// Date naissance
	print "<tr><td>" . $langs->trans("Birthday") . "</td><td>\n";
	$form->select_date(($object->naiss ? $object->naiss : -1), 'naiss', '', '', 1, 'formsoc');
	print "</td></tr>\n";

	// Profil public
	print "<tr><td>" . $langs->trans("Public") . "</td><td>\n";
	print $form->selectyesno("public", (isset($_POST["public"]) ? $_POST["public"] : $object->public), 1);
	print "</td></tr>\n";

	// Other attributes
	$parameters = array();
	$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action); // Note that $action and $object may have been modified by hook
	if (empty($reshook) && !empty($extrafields->attribute_label)) {
		foreach ($extrafields->attribute_label as $key => $label) {
			$option = "options_" . $key;
			$value = (isset($_POST["options_" . $key]) ? $_POST["options_" . $key] : $object->array_options->$option);
			print '<tr><td>' . $label . '</td><td>';
			print $extrafields->showInputField($key, $value);
			print '</td></tr>' . "\n";
		}
	}

	// Third party Dolibarr
	if ($conf->societe->enabled) {
		print '<tr><td>' . $langs->trans("LinkedToDolibarrThirdParty") . '</td><td colspan="2" class="valeur">';
		if ($object->fk_soc) {
			$company = new Societe($db);
			$result = $company->fetch($object->fk_soc);
			print $company->getNomUrl(1);
		} else {
			print $langs->trans("NoThirdPartyAssociatedToMember");
		}
		print '</td></tr>';
	}

	// Login Dolibarr
	print '<tr><td>' . $langs->trans("LinkedToDolibarrUser") . '</td><td colspan="2" class="valeur">';
	if ($object->user_id) {
		print $form->form_users($_SERVER['PHP_SELF'] . '?id=' . $object->id, $object->user_id, 'none');
	}
	else
		print $langs->trans("NoDolibarrAccess");
	print '</td></tr>';

	print '</table>';

	print '<br><center>';
	print '<input type="submit" class="button" name="save" value="' . $langs->trans("Save") . '">';
	print ' &nbsp; &nbsp; &nbsp; ';
	print '<input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">';
	print '</center';

	print '</form>';

	print '</div></div>';
}

/*
 * Add new subscription form
 */
if ($rowid && ($action == 'addsubscription' || $action == 'create_thirdparty') && $user->rights->adherent->cotisation->creer) {

	print_fiche_titre($langs->trans("NewCotisation"));

	$bankdirect = 0;  // Option to write to bank is on by default
	$bankviainvoice = 0; // Option to write via invoice is on by default
	$invoiceonly = 0;
	if ($conf->banque->enabled && $conf->global->ADHERENT_BANK_USE && (empty($_POST['paymentsave']) || $_POST["paymentsave"] == 'bankdirect'))
		$bankdirect = 1;
	if ($conf->banque->enabled && $conf->societe->enabled && $conf->facture->enabled && $object->fk_soc)
		$bankviainvoice = 1;

	print "\n\n<!-- Form add subscription -->\n";

	if ($conf->use_javascript_ajax) {
		print "\n" . '<script type="text/javascript" language="javascript">';
		print 'jQuery(document).ready(function () {
                        jQuery(".bankswitchclass").' . ($bankdirect || $bankviainvoice || in_array(GETPOST('paymentsave'), array('bankdirect', 'bankviainvoice')) ? 'show()' : 'hide()') . ';
                        jQuery(".bankswitchclass2").' . ($bankdirect || $bankviainvoice || in_array(GETPOST('paymentsave'), array('bankdirect', 'bankviainvoice')) ? 'show()' : 'hide()') . ';
                        jQuery("#none").click(function() {
                            jQuery(".bankswitchclass").hide();
                            jQuery(".bankswitchclass2").hide();
                        });
                        jQuery("#bankdirect").click(function() {
                            jQuery(".bankswitchclass").show();
                            jQuery(".bankswitchclass2").show();
                        });
                        jQuery("#bankviainvoice").click(function() {
                            jQuery(".bankswitchclass").show();
                            jQuery(".bankswitchclass2").show();
                        });
    	                jQuery("#invoiceonly").click(function() {
                            jQuery(".bankswitchclass").hide();
                            jQuery(".bankswitchclass2").hide();
                        });
                        jQuery("#selectoperation").change(function() {
                            code=jQuery("#selectoperation option:selected").val();
                            if (code == \'CHQ\')
                            {
                                jQuery(\'.fieldrequireddyn\').addClass(\'fieldrequired\');
                            	if (jQuery(\'#fieldchqemetteur\').val() == \'\')
                            	{
                                	jQuery(\'#fieldchqemetteur\').val(jQuery(\'#memberlabel\').val());
                            	}
                            }
                            else
                            {
                                jQuery(\'.fieldrequireddyn\').removeClass(\'fieldrequired\');
                            }
                        });
                        ';
		if (GETPOST('paymentsave'))
			print 'jQuery("#' . GETPOST('paymentsave') . '").attr(\'checked\',true);';
		print '});';
		print '</script>' . "\n";
	}


	// Confirm create third party
	if ($_GET["action"] == 'create_thirdparty') {
		$name = $object->getFullName($langs);
		if (!empty($name)) {
			if ($object->societe)
				$name.=' (' . $object->societe . ')';
		}
		else {
			$name = $object->societe;
		}

		// Create a form array
		$formquestion = array(array('label' => $langs->trans("NameToCreate"), 'type' => 'text', 'name' => 'companyname', 'value' => $name));

		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?rowid=" . $object->id, $langs->trans("CreateDolibarrThirdParty"), $langs->trans("ConfirmCreateThirdParty"), "confirm_create_thirdparty", $formquestion, 1);
		if ($ret == 'html')
			print '<br>';
	}

	print '<form name="cotisation" method="post" action="' . $_SERVER["PHP_SELF"] . '">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<input type="hidden" name="action" value="cotisation">';
	print '<input type="hidden" name="id" value="' . $rowid . '">';
	print '<input type="hidden" name="memberlabel" id="memberlabel" value="' . dol_escape_htmltag($object->getFullName($langs)) . '">';
	print '<input type="hidden" name="thirdpartylabel" id="thirdpartylabel" value="' . dol_escape_htmltag($company->name) . '">';
	print "<table class=\"border\" width=\"100%\">\n";

	$today = dol_now();
	$datefrom = 0;
	$dateto = 0;
	$paymentdate = -1;

	// Date payment
	if ($_POST["paymentyear"] && $_POST["paymentmonth"] && $_POST["paymentday"]) {
		$paymentdate = dol_mktime(0, 0, 0, $_POST["paymentmonth"], $_POST["paymentday"], $_POST["paymentyear"]);
	}

	// Date start subscription
	print '<tr><td width="30%" class="fieldrequired">' . $langs->trans("DateSubscription") . '</td><td>';
	if ($_POST["reday"]) {
		$datefrom = dol_mktime(0, 0, 0, $_POST["remonth"], $_POST["reday"], $_POST["reyear"]);
	}
	if (!$datefrom) {
		if ($object->datefin > 0) {
			$datefrom = dol_time_plus_duree($object->datefin, 1, 'd');
		} else {
			$datefrom = dol_now();
		}
	}
	$form->select_date($datefrom, '', '', '', '', "cotisation");
	print "</td></tr>";

	// Date end subscription
	if ($_POST["endday"]) {
		$dateto = dol_mktime(0, 0, 0, $_POST["endmonth"], $_POST["endday"], $_POST["endyear"]);
	}
	if (!$dateto) {
		$dateto = -1;  // By default, no date is suggested
	}
	print '<tr><td>' . $langs->trans("DateEndSubscription") . '</td><td>';
	$form->select_date($dateto, 'end', '', '', '', "cotisation");
	print "</td></tr>";

	if ($adht->cotisation) {
		// Amount
		print '<tr><td class="fieldrequired">' . $langs->trans("Amount") . '</td><td><input type="text" name="cotisation" size="6" value="' . $_POST["cotisation"] . '"> ' . $langs->trans("Currency" . $conf->currency) . '</td></tr>';

		// Label
		print '<tr><td class="fieldrequired">' . $langs->trans("Label") . '</td>';
		print '<td><input name="label" type="text" size="32" value="' . $langs->trans("Subscription") . ' ';
		print dol_print_date(($datefrom ? $datefrom : time()), "%Y") . '" ></td></tr>';

		// Complementary action
		if ($conf->banque->enabled || $conf->facture->enabled) {
			$company = new Societe($db);
			if ($object->fk_soc) {
				$result = $company->fetch($object->fk_soc);
			}

			// Title payments
			//print '<tr><td colspan="2"><b>'.$langs->trans("Payment").'</b></td></tr>';
			// Define a way to write payment
			print '<tr><td valign="top" class="fieldrequired">' . $langs->trans('MoreActions');
			print '</td>';
			print '<td>';
			print '<input type="radio" class="moreaction" id="none" name="paymentsave" value="none"' . (!$bankdirect && !$bankviainvoice ? ' checked="checked"' : '') . '> ' . $langs->trans("None") . '<br>';
			if ($conf->banque->enabled) {
				print '<input type="radio" class="moreaction" id="bankdirect" name="paymentsave" value="bankdirect"' . ($bankdirect ? ' checked="checked"' : '');
				print '> ' . $langs->trans("MoreActionBankDirect") . '<br>';
			}
			if ($conf->societe->enabled && $conf->facture->enabled) {
				print '<input type="radio" class="moreaction" id="invoiceonly" name="paymentsave" value="invoiceonly"' . ($invoiceonly ? ' checked="checked"' : '');
				if (empty($object->fk_soc) || empty($bankviainvoice))
					print ' disabled="disabled"';
				print '> ' . $langs->trans("MoreActionInvoiceOnly");
				if ($object->fk_soc)
					print ' (' . $langs->trans("ThirdParty") . ': ' . $company->getNomUrl(1) . ')';
				else {
					print ' (' . $langs->trans("NoThirdPartyAssociatedToMember");
					print ' - <a href="' . $_SERVER["PHP_SELF"] . '?rowid=' . $object->id . '&amp;action=create_thirdparty">';
					print $langs->trans("CreateDolibarrThirdParty");
					print '</a>)';
				}
				print '<br>';
			}
			if ($conf->banque->enabled && $conf->societe->enabled && $conf->facture->enabled) {
				print '<input type="radio" class="moreaction" id="bankviainvoice" name="paymentsave" value="bankviainvoice"' . ($bankviainvoice ? ' checked="checked"' : '');
				if (empty($object->fk_soc) || empty($bankviainvoice))
					print ' disabled="disabled"';
				print '> ' . $langs->trans("MoreActionBankViaInvoice");
				if ($object->fk_soc)
					print ' (' . $langs->trans("ThirdParty") . ': ' . $company->getNomUrl(1) . ')';
				else {
					print ' (' . $langs->trans("NoThirdPartyAssociatedToMember");
					print ' - <a href="' . $_SERVER["PHP_SELF"] . '?rowid=' . $object->id . '&amp;action=create_thirdparty">';
					print $langs->trans("CreateDolibarrThirdParty");
					print '</a>)';
				}
				print '<br>';
			}
			print '</td></tr>';

			// Bank account
			print '<tr class="bankswitchclass"><td class="fieldrequired">' . $langs->trans("FinancialAccount") . '</td><td>';
			$form->select_comptes($_POST["accountid"], 'accountid', 0, '', 1);
			print "</td></tr>\n";

			// Payment mode
			print '<tr class="bankswitchclass"><td class="fieldrequired">' . $langs->trans("PaymentMode") . '</td><td>';
			$form->select_types_paiements($_POST["operation"], 'operation', '', 2);
			print "</td></tr>\n";

			// Date of payment
			print '<tr class="bankswitchclass"><td class="fieldrequired">' . $langs->trans("DatePayment") . '</td><td>';
			$form->select_date($paymentdate ? $paymentdate : -1, 'payment', 0, 0, 1, 'cotisation', 1, 1);
			print "</td></tr>\n";

			print '<tr class="bankswitchclass2"><td>' . $langs->trans('Numero');
			print ' <em>(' . $langs->trans("ChequeOrTransferNumber") . ')</em>';
			print '</td>';
			print '<td><input id="fieldnum_chq" name="num_chq" type="text" size="8" value="' . (empty($_POST['num_chq']) ? '' : $_POST['num_chq']) . '"></td></tr>';

			print '<tr class="bankswitchclass2 fieldrequireddyn"><td>' . $langs->trans('CheckTransmitter');
			print ' <em>(' . $langs->trans("ChequeMaker") . ')</em>';
			print '</td>';
			print '<td><input id="fieldchqemetteur" name="chqemetteur" size="32" type="text" value="' . (empty($_POST['chqemetteur']) ? $facture->client->name : $_POST['chqemetteur']) . '"></td></tr>';

			print '<tr class="bankswitchclass2"><td>' . $langs->trans('Bank');
			print ' <em>(' . $langs->trans("ChequeBank") . ')</em>';
			print '</td>';
			print '<td><input id="chqbank" name="chqbank" size="32" type="text" value="' . (empty($_POST['chqbank']) ? '' : $_POST['chqbank']) . '"></td></tr>';
		}
	}

	print '<tr><td colspan="2">&nbsp;</td>';

	print '<tr><td width="30%">' . $langs->trans("SendAcknowledgementByMail") . '</td>';
	print '<td>';
	if (!$object->email) {
		print $langs->trans("NoEMail");
	} else {
		$subjecttosend = $object->makeSubstitution($conf->global->ADHERENT_MAIL_COTIS_SUBJECT);
		//$texttosend = $object->makeSubstitution($adht->getMailOnSubscription());

		$tmp = '<input name="sendmail" type="checkbox"' . ((isset($_POST["sendmail"]) ? $_POST["sendmail"] : $conf->global->ADHERENT_DEFAULT_SENDINFOBYMAIL) ? ' checked="checked"' : '') . '>';
		$helpcontent = '';
		$helpcontent.='<b>' . $langs->trans("MailFrom") . '</b>: ' . $conf->global->ADHERENT_MAIL_FROM . '<br>' . "\n";
		$helpcontent.='<b>' . $langs->trans("MailRecipient") . '</b>: ' . $object->email . '<br>' . "\n";
		$helpcontent.='<b>' . $langs->trans("Subject") . '</b>:<br>' . "\n";
		$helpcontent.=$subjecttosend . "\n";
		$helpcontent.="<br>";
		$helpcontent.='<b>' . $langs->trans("Content") . '</b>:<br>';
		$helpcontent.=dol_htmlentitiesbr($texttosend) . "\n";

		print $form->textwithpicto($tmp, $helpcontent, 1, 'help');
	}
	print '</td></tr>';
	print '</table>';
	print '<br>';

	print '<center>';
	print '<input type="submit" class="button" name="add" value="' . $langs->trans("AddSubscription") . '">';
	print ' &nbsp; &nbsp; ';
	print '<input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">';
	print '</center>';

	print '</form>';

	print "\n<!-- End form subscription -->\n\n";
} elseif ($rowid && $action != 'edit') {
	dol_htmloutput_mesg($mesg);

	/*	 * ************************************************************************* */
	/*                                                                            */
	/* Mode affichage                                                             */
	/*                                                                            */
	/*	 * ************************************************************************* */

	$res = $object->fetch($rowid);
	if ($res < 0) {
		dol_print_error($db, $object->error);
		exit;
	}
	//$res=$object->fetch_optionals($object->id,$extralabels);
	//if ($res < 0) { dol_print_error($db); exit; }

	/*$adht = new AdherentType($db);
	$result = $adht->getView('list', array("key" => $object->typeid, 'limit' => 1));
	if (count($result->rows))
		$adht->id = $result->rows[0]->value->_id;

	$adht->libelle = $object->typeid;*/


	/* try {
	  $res = $adht->fetch($object->typeid);
	  } catch (Exception $e) {
	  $adht->libelle = $object->typeid;
	  } */

	$titre = $langs->trans("Member");
	print_fiche_titre($titre);
	print '<div class="container">';
	print '<div class="row">';
	
	print start_box($titre, "twelve", "16-User.png");

	/*
	 * Affichage onglets
	 */
	$head = member_prepare_head($object);

	dol_fiche_head($head, 'general', $langs->trans("Member"), 0, 'user');

	dol_htmloutput_errors($errmsg, $errmsgs);

	// Confirm create user
	if ($_GET["action"] == 'create_user') {
		$login = $object->login;
		if (empty($login)) {
			// Full firstname and name separated with a dot : firstname.name
			include_once(DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php');
			$login = dol_buildlogin($object->Lastname, $object->Firstname);
		}
		if (empty($login))
			$login = strtolower(substr($object->Firstname, 0, 4)) . strtolower(substr($object->Lastname, 0, 4));

		// Create a form array
		$formquestion = array(
			array('label' => $langs->trans("LoginToCreate"), 'type' => 'text', 'name' => 'login', 'value' => $login)
		);
		$text = $langs->trans("ConfirmCreateLogin") . '<br>';
		if ($conf->societe->enabled) {
			if ($object->fk_soc > 0)
				$text.=$langs->trans("UserWillBeExternalUser");
			else
				$text.=$langs->trans("UserWillBeInternalUser");
		}
		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=" . $object->id, $langs->trans("CreateDolibarrLogin"), $text, "confirm_create_user", $formquestion, 'yes');
		if ($ret == 'html')
			print '<br>';
	}

	// Confirm create third party
	if ($_GET["action"] == 'create_thirdparty') {
		$name = $object->getFullName($langs);
		if (!empty($name)) {
			if ($object->societe)
				$name.=' (' . $object->societe . ')';
		}
		else {
			$name = $object->societe;
		}

		// Create a form array
		$formquestion = array(array('label' => $langs->trans("NameToCreate"), 'type' => 'text', 'name' => 'companyname', 'value' => $name));

		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=" . $object->id, $langs->trans("CreateDolibarrThirdParty"), $langs->trans("ConfirmCreateThirdParty"), "confirm_create_thirdparty", $formquestion, 1);
		if ($ret == 'html')
			print '<br>';
	}

	// Confirm validate member
	if ($action == 'valid') {
		$langs->load("mails");

		$adht = new AdherentType($db);
		$result = $adht->getView('list', array("key" => $object->typeid, 'limit' => 1));
		if (count($result->rows))
			$adht->fetch($result->rows[0]->value->_id);

		$subjecttosend = $object->makeSubstitution($conf->global->ADHERENT_MAIL_VALID_SUBJECT);
		$texttosend = $object->makeSubstitution($adht->getMailOnValid());

		$tmp = $langs->trans("SendAnEMailToMember");
		$tmp.=' (' . $langs->trans("MailFrom") . ': <b>' . $conf->global->ADHERENT_MAIL_FROM . '</b>, ';
		$tmp.=$langs->trans("MailRecipient") . ': <b>' . $object->email . '</b>)';
		$helpcontent = '';
		$helpcontent.='<b>' . $langs->trans("MailFrom") . '</b>: ' . $conf->global->ADHERENT_MAIL_FROM . '<br>' . "\n";
		$helpcontent.='<b>' . $langs->trans("MailRecipient") . '</b>: ' . $object->email . '<br>' . "\n";
		$helpcontent.='<b>' . $langs->trans("Subject") . '</b>:<br>' . "\n";
		$helpcontent.=$subjecttosend . "\n";
		$helpcontent.="<br>";
		$helpcontent.='<b>' . $langs->trans("Content") . '</b>:<br>';
		$helpcontent.=dol_htmlentitiesbr($texttosend) . "\n";
		$label = $form->textwithpicto($tmp, $helpcontent, 1, 'help');

		// Cree un tableau formulaire
		$formquestion = array();
		if ($object->email)
			$formquestion[] = array('type' => 'checkbox', 'name' => 'send_mail', 'label' => $label, 'value' => ($conf->global->ADHERENT_DEFAULT_SENDINFOBYMAIL ? true : false));
		if ($conf->global->ADHERENT_USE_MAILMAN) {
			$langs->load("mailmanspip");
			$formquestion[] = array('type' => 'other', 'label' => $langs->transnoentitiesnoconv("SynchroMailManEnabled"), 'value' => '');
		}
		if ($conf->global->ADHERENT_USE_SPIP) {
			$langs->load("mailmanspip");
			$formquestion[] = array('type' => 'other', 'label' => $langs->transnoentitiesnoconv("SynchroSpipEnabled"), 'value' => '');
		}
		print $form->formconfirm($_SERVER["PHP_SELF"] . "?id=" . $rowid, $langs->trans("ValidateMember"), $langs->trans("ConfirmValidateMember"), "confirm_valid", $formquestion, 1);
	}

	// Confirm send card by mail
	if ($action == 'sendinfo') {
		print $form->formconfirm($_SERVER["PHP_SELF"] . "?id=" . $rowid, $langs->trans("SendCardByMail"), $langs->trans("ConfirmSendCardByMail", $object->email), "confirm_sendinfo", '', 0, 1);
	}

	// Confirm resiliate
	if ($action == 'resign') {
		$langs->load("mails");

		$adht = new AdherentType($db);
		$adht->fetch($object->typeid);

		$subjecttosend = $object->makeSubstitution($conf->global->ADHERENT_MAIL_RESIL_SUBJECT);
		$texttosend = $object->makeSubstitution($adht->getMailOnResiliate());

		$tmp = $langs->trans("SendAnEMailToMember");
		$tmp.=' (' . $langs->trans("MailFrom") . ': <b>' . $conf->global->ADHERENT_MAIL_FROM . '</b>, ';
		$tmp.=$langs->trans("MailRecipient") . ': <b>' . $object->email . '</b>)';
		$helpcontent = '';
		$helpcontent.='<b>' . $langs->trans("MailFrom") . '</b>: ' . $conf->global->ADHERENT_MAIL_FROM . '<br>' . "\n";
		$helpcontent.='<b>' . $langs->trans("MailRecipient") . '</b>: ' . $object->email . '<br>' . "\n";
		$helpcontent.='<b>' . $langs->trans("Subject") . '</b>:<br>' . "\n";
		$helpcontent.=$subjecttosend . "\n";
		$helpcontent.="<br>";
		$helpcontent.='<b>' . $langs->trans("Content") . '</b>:<br>';
		$helpcontent.=dol_htmlentitiesbr($texttosend) . "\n";
		$label = $form->textwithpicto($tmp, $helpcontent, 1, 'help');

		// Cree un tableau formulaire
		$formquestion = array();
		if ($object->email)
			$formquestion[] = array('type' => 'checkbox', 'name' => 'send_mail', 'label' => $label, 'value' => ($conf->global->ADHERENT_DEFAULT_SENDINFOBYMAIL ? 'true' : 'false'));
		if ($backtopage)
			$formquestion[] = array('type' => 'hidden', 'name' => 'backtopage', 'value' => ($backtopage != '1' ? $backtopage : $_SERVER["HTTP_REFERER"]));
		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=" . $rowid, $langs->trans("ResiliateMember"), $langs->trans("ConfirmResiliateMember"), "confirm_resign", $formquestion);
		if ($ret == 'html')
			print '<br>';
	}

	// Confirm remove member
	if ($action == 'delete') {
		$formquestion = array();
		if ($backtopage)
			$formquestion[] = array('type' => 'hidden', 'name' => 'backtopage', 'value' => ($backtopage != '1' ? $backtopage : $_SERVER["HTTP_REFERER"]));
		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=" . $rowid, $langs->trans("DeleteMember"), $langs->trans("ConfirmDeleteMember"), "confirm_delete", $formquestion, 0, 1);
		if ($ret == 'html')
			print '<br>';
	}

	/*
	 * Confirm add in spip
	 */
	if ($action == 'add_spip') {
		$langs->load("mailmanspip");
		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=" . $rowid, "Add to spip", "Etes-vous sur de vouloir ajouter cet adherent dans spip ? (serveur : " . ADHERENT_SPIP_SERVEUR . ")", "confirm_add_spip");
		if ($ret == 'html')
			print '<br>';
	}

	/*
	 * Confirm removed from spip
	 */
	if ($action == 'del_spip') {
		$langs->load("mailmanspip");
		$ret = $form->form_confirm($_SERVER["PHP_SELF"] . "?id=$rowid", "Supprimer dans spip", "Etes-vous sur de vouloir effacer cet adherent dans spip ? (serveur : " . ADHERENT_SPIP_SERVEUR . ")", "confirm_del_spip");
		if ($ret == 'html')
			print '<br>';
	}

	$rowspan = 17;
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED))
		$rowspan++;
	if ($conf->societe->enabled)
		$rowspan++;



	print '<table class="border" width="100%">';

	// Ref
	print '<tr><td width="20%">' . $langs->trans("Ref") . '</td>';
	print '<td class="valeur" colspan="2">';
	print $form->showrefnav($object, 'id');
	print '</td></tr>';

	$showphoto = '<td rowspan="' . $rowspan . '" align="center" valign="middle" width="25%">';
	$showphoto.=$form->showphoto('memberphoto', $object);
	$showphoto.='</td>';

	// Login
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) {
		print '<tr><td>' . $langs->trans("Login") . ' / ' . $langs->trans("Id") . '</td><td class="valeur">' . $object->login . '&nbsp;</td>';
		print $showphoto;
		$showphoto = '';
		print '</tr>';
	}

	// Morphy
	print '<tr><td>' . $langs->trans("Nature") . '</td><td class="valeur" >' . $object->getmorphylib() . '</td>';
	print $showphoto;
	$showphoto = '';
	print '</tr>';

	// Type
	print '<tr><td>' . $langs->trans("MemberType") . '</td><td class="valeur">' . $object->getTagUrl(1) . "</td></tr>\n";

	// Company
	print '<tr><td>' . $langs->trans("Company") . '</td><td class="valeur">' . $object->societe . '</td></tr>';

	// Civility
	print '<tr><td>' . $langs->trans("UserTitle") . '</td><td class="valeur">' . $object->getCivilityLabel() . '&nbsp;</td>';
	print '</tr>';

	// Name
	print '<tr><td>' . $langs->trans("Lastname") . '</td><td class="valeur">' . $object->Lastname . '&nbsp;</td>';
	print '</tr>';

	// Firstname
	print '<tr><td>' . $langs->trans("Firstname") . '</td><td class="valeur">' . $object->Firstname . '&nbsp;</td></tr>';

	// Password
	if (empty($conf->global->ADHERENT_LOGIN_NOT_REQUIRED)) {
		print '<tr><td>' . $langs->trans("Password") . '</td><td>' . preg_replace('/./i', '*', $object->pass) . '</td></tr>';
	}

	// Address
	print '<tr><td>' . $langs->trans("Address") . '</td><td class="valeur">';
	dol_print_address($object->address, 'gmap', 'member', $object->id);
	print '</td></tr>';

	// Zip / Town
	print '<tr><td nowrap="nowrap">' . $langs->trans("Zip") . ' / ' . $langs->trans("Town") . '</td><td class="valeur">' . $object->zip . (($object->zip && $object->town) ? ' / ' : '') . $object->town . '</td></tr>';

	// Country
	print '<tr><td>' . $langs->trans("Country") . '</td><td class="valeur">';
	$img = picto_from_langcode($object->country_code);
	if ($img)
		print $img . ' ';
	print getCountry($object->country_code);
	print '</td></tr>';

	// State
	print '<tr><td>' . $langs->trans('State') . '</td><td class="valeur">' . $object->departement . '</td>';

	// Tel pro.
	print '<tr><td>' . $langs->trans("PhonePro") . '</td><td class="valeur">' . dol_print_phone($object->phone, $object->country_code, 0, $object->fk_soc, 1) . '</td></tr>';

	// Tel perso
	print '<tr><td>' . $langs->trans("PhonePerso") . '</td><td class="valeur">' . dol_print_phone($object->phone_perso, $object->country_code, 0, $object->fk_soc, 1) . '</td></tr>';

	// Tel mobile
	print '<tr><td>' . $langs->trans("PhoneMobile") . '</td><td class="valeur">' . dol_print_phone($object->phone_mobile, $object->country_code, 0, $object->fk_soc, 1) . '</td></tr>';

	// EMail
	print '<tr><td>' . $langs->trans("EMail") . '</td><td class="valeur">' . dol_print_email($object->email, 0, $object->fk_soc, 1) . '</td></tr>';

	// Date naissance
	print '<tr><td>' . $langs->trans("Birthday") . '</td><td class="valeur">' . dol_print_date($object->naiss, 'day') . '</td></tr>';

	// Public
	print '<tr><td>' . $langs->trans("Public") . '</td><td class="valeur">' . yn($object->public) . '</td></tr>';

	// Status
	print '<tr><td>' . $langs->trans("Status") . '</td><td class="valeur">' . $object->getLibStatus() . '</td></tr>';

	// Other attributes
	$parameters = array();
	$reshook = $hookmanager->executeHooks('formObjectOptions', $parameters, $object, $action); // Note that $action and $object may have been modified by hook
	if (empty($reshook) && !empty($extrafields->attribute_label)) {
		foreach ($extrafields->attribute_label as $key => $label) {
			$option = "options_$key";
			$value = $object->array_options->$option;
			print "<tr><td>" . $label . "</td><td>";
			print $extrafields->showOutputField($key, $value);
			print "</td></tr>\n";
		}
	}

	print '</tr></table>';
	print '</td><td colspan="2" class="valeur">';
	if ($_GET['action'] == 'editlogin') {
		print $form->form_users($_SERVER['PHP_SELF'] . '?id=' . $object->id, $object->user_id, 'userid', '');
	} else {
		if ($object->user_id) {
			print $form->form_users($_SERVER['PHP_SELF'] . '?id=' . $object->id, $object->user_id, 'none');
		}
		else
			print $langs->trans("NoDolibarrAccess");
	}
	print '</td></tr>';

	print "</table>\n";

	print "</div>\n";


	/*
	 * Barre d'actions
	 *
	 */
	print '<div class="tabsAction">';

	if ($action != 'valid' && $action != 'editlogin' && $action != 'editthirdparty') {
		// Modify
		if ($user->rights->adherent->creer) {
			print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $rowid . '&action=edit">' . $langs->trans("Modify") . '</a>';
		} else {
			print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NotEnoughPermissions")) . "\">" . $langs->trans("Modify") . "</font>";
		}

		// Valider
		if ($object->Status == 0) {
			if ($user->rights->adherent->creer) {
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $rowid . '&action=valid">' . $langs->trans("Validate") . "</a>\n";
			} else {
				print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NotEnoughPermissions")) . "\">" . $langs->trans("Validate") . "</font>";
			}
		}

		// Reactiver
		if ($object->Status == -1) {
			if ($user->rights->adherent->creer) {
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $rowid . '&action=valid">' . $langs->trans("Reenable") . "</a>\n";
			} else {
				print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NotEnoughPermissions")) . "\">" . $langs->trans("Reenable") . "</font>";
			}
		}

		// Resilier
		if ($object->Status >= 1) {
			if ($user->rights->adherent->supprimer) {
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $rowid . '&action=resign">' . $langs->trans("Resiliate") . "</a>\n";
			} else {
				print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NotEnoughPermissions")) . "\">" . $langs->trans("Resiliate") . "</font>";
			}
		}

		// Delete
		if ($user->rights->adherent->supprimer) {
			print '<a class="butActionDelete" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&action=delete">' . $langs->trans("Delete") . "</a>\n";
		} else {
			print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NotEnoughPermissions")) . "\">" . $langs->trans("Delete") . "</font>";
		}

		// Action SPIP
		if ($conf->mailmanspip->enabled && $conf->global->ADHERENT_USE_SPIP) {
			include_once(DOL_DOCUMENT_ROOT . '/mailmanspip/class/mailmanspip.class.php');
			$mailmanspip = new MailmanSpip($db);

			$isinspip = $mailmanspip->is_in_spip($object);
			if ($isinspip == 1) {
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=$object->id&action=del_spip">' . $langs->trans("DeleteIntoSpip") . "</a>\n";
			}
			if ($isinspip == 0) {
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=$object->id&action=add_spip\">' . $langs->trans("AddIntoSpip") . "</a>\n";
			}
			if ($isinspip == -1) {
				print '<br><br><font class="error">Failed to connect to SPIP: ' . $object->error . '</font>';
			}
		}
	}

	print '</div>';

	print end_box();

	print "</div>";

	print '<div class="row">';

	$titre = $langs->trans("Subscriptions");
	print start_box($titre, "six", "16-Money.png");

	$i = 0;
	$obj = new stdClass();
	print '<table class="display dt_act" id="subscription_datatable" >';
	// Ligne des titres 
	print'<thead>';
	print'<th class="essential">';
	print $langs->trans("Ref");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "_id";
	$obj->aoColumns[$i]->bUseRendered = false;
	$obj->aoColumns[$i]->bSearchable = false;
	$obj->aoColumns[$i]->bVisible = false;
	$i++;
	print'<th class="essential">';
	print $langs->trans('DateSubscription');
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "dateh";
	$obj->aoColumns[$i]->sType = "date";
	$obj->aoColumns[$i]->sClass = "center";
	//$obj->aoColumns[$i]->sWidth = "200px";
	$obj->aoColumns[$i]->sDefaultContent = "";
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("dateh", "date");
	$obj->aoColumns[$i]->sDefaultContent = "";
//$obj->aoColumns[$i]->sClass = "edit";
	$i++;
	print'<th class="essential">';
	print $langs->trans('DateEnd');
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "datef";
	$obj->aoColumns[$i]->sType = "date";
	$obj->aoColumns[$i]->sClass = "center";
	//$obj->aoColumns[$i]->sWidth = "200px";
	$obj->aoColumns[$i]->sDefaultContent = "";
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("datef", "date");
	$obj->aoColumns[$i]->sDefaultContent = "";
	//$obj->aoColumns[$i]->sClass = "edit";
	$i++;
	print'<th class="essential">';
	print $langs->trans("Amount");
	print'</th>';
	$obj->aoColumns[$i]->mDataProp = "amount";
	$obj->aoColumns[$i]->sClass = "fright";
	$obj->aoColumns[$i]->sDefaultContent = "0";
	$obj->aoColumns[$i]->fnRender = $object->datatablesFnRender("amount", "price");
	print'</tr>';
	print'</thead>';
	print'<tfoot>';
	print'</tfoot>';
	print'<tbody>';
	$result = $object->getView('cotisation', array("key" => $object->id));
	if (count($result->rows) > 0)
		foreach ($result->rows as $key => $aRow) {
			print '<tr>';
			print '<td>' . $key . '</td>';
			print '<td>' . $aRow->value->dateh . '</td>';
			print '<td>' . $aRow->value->datef . '</td>';
			print '<td>' . $aRow->value->amount . '</td>';
			print '</tr>';
		}
	print'</tbody>';

	print "</table>";

	//$obj->sDom = 'l<fr>t<\"clear\"rtip>';
	$obj->bServerSide = false;
	$obj->iDisplayLength = 10;
	$object->datatablesCreate($obj, "subscription_datatable");


	if ($user->rights->adherent->cotisation->creer) {
		if ($action != 'addsubscription' && $action != 'create_thirdparty') {
			print '<div class="tabsAction">';

			if ($object->Status > 0)
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $rowid . '&action=addsubscription">' . $langs->trans("AddSubscription") . "</a>";
			else
				print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("ValidateBefore")) . '">' . $langs->trans("AddSubscription") . '</a>';

			print "<br>\n";

			print '</div>';
		}
	}

	print end_box();

	$titre = $langs->trans("Messenger");
	print start_box($titre, "six", "16-Mail.png");

	print end_box();

	$titre = $langs->trans("CardMember");
	print start_box($titre, "six", "16-Mail.png");

	$licence = new AdherentCard($db);
	try {
		$licence->load($object->login);
		print $licence->body;
	} catch (Exception $e) {
		print "No licence Card";
	}



	print '<div class="tabsAction">';

	// Send card by email
	if ($user->rights->adherent->creer) {
		if ($object->Status >= 1) {
			if ($object->email)
				print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&action=sendinfo">' . $langs->trans("SendCard") . "</a>\n";
			else
				print "<a class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NoEMail")) . "\">" . $langs->trans("SendCard") . "</a>\n";
		}
		else {
			print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("ValidateBefore")) . "\">" . $langs->trans("SendCard") . "</font>";
		}
	} else {
		print "<font class=\"butActionRefused\" href=\"#\" title=\"" . dol_escape_htmltag($langs->trans("NotEnoughPermissions")) . "\">" . $langs->trans("SendCard") . "</font>";
	}

	print '</div>';

	print end_box();
}

print '</div></div>';

llxFooter();

$db->close();
?>
