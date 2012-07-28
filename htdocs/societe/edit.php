<?php
/* Copyright (C) 2001-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Brian Fraval         <brian@fraval.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2008	   Patrick Raguin       <patrick.raguin@auguria.net>
 * Copyright (C) 2010-2011 Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2010-2012 Herve Prot           <herve.prot@symeos.com>
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
if (!defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL', '1'); // Disables token renewal
if (!defined('NOREQUIREMENU'))
	define('NOREQUIREMENU', '1');
if (!defined('NOHEADER'))
	define('NOHEADER', '1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/company.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/images.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/lib/files.lib.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formadmin.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formcompany.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/html.formfile.class.php");
require_once(DOL_DOCUMENT_ROOT . "/core/class/extrafields.class.php");
require_once(DOL_DOCUMENT_ROOT . "/contact/class/contact.class.php");
if ($conf->adherent->enabled)
	require_once(DOL_DOCUMENT_ROOT . "/adherents/class/adherent.class.php");
if ($conf->highcharts->enabled)
	dol_include_once("/highCharts/class/highCharts.class.php");

$langs->load("companies");
$langs->load("commercial");
$langs->load("bills");
$langs->load("banks");
$langs->load("users");
if ($conf->notification->enabled)
	$langs->load("mails");

$mesg = '';
$error = 0;
$errors = array();

$action = (GETPOST('action', 'alpha') ? GETPOST('action', 'alpha') : 'view');
$confirm = GETPOST('confirm', 'alpha');
$cancel = GETPOST('cancel', 'alpha');
$socid = GETPOST('id', 'alpha');
if ($user->societe_id)
	$socid = $user->societe_id;

// Security check
$result = restrictedArea($user, 'societe', $socid, '&societe', '', 'fk_soc', 'rowid', $objcanvas);
$object = new Societe($db);

/*
 * Actions
 */

if (empty($reshook)) {
	if ($_POST["getcustomercode"]) {
		// We defined value code_client
		$_POST["code_client"] = "Acompleter";
	}

	if ($_POST["getsuppliercode"]) {
		// We defined value code_fournisseur
		$_POST["code_fournisseur"] = "Acompleter";
	}

	// Add new third party
	if ((!$_POST["getcustomercode"] && !$_POST["getsuppliercode"])
			&& ($action == 'add' || $action == 'update') && empty($cancel) && $user->rights->societe->creer) {
		require_once(DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php");

		if ($action == 'update')
			$object->load($socid);
		else
			$object->values->canvas = $canvas;

		if (GETPOST("private") == 1) {
			$object->values->particulier = GETPOST("private");

			$object->values->name = empty($conf->global->MAIN_FIRSTNAME_NAME_POSITION) ? trim($_POST["prenom"] . ' ' . $_POST["nom"]) : trim($_POST["nom"] . ' ' . $_POST["prenom"]);
			$object->values->civilite_id = (int) $_POST["civilite_id"];
			// Add non official properties
			$object->values->name_bis = $_POST["nom"];
			$object->values->firstname = $_POST["prenom"];
		} else {
			$object->values->name = ucwords($_POST["nom"]);
		}
		$object->values->Address = $_POST["Address"];
		$object->values->Zip = $_POST["Zip"];
		$object->values->Town = $_POST["Town"];
		$object->values->Country = $_POST["Country"];
		$object->values->State = $_POST["State"];

		$tel = preg_replace("/\s/", "", $_POST["Phone"]);
		$tel = preg_replace("/\./", "", $tel);
		$fax = preg_replace("/\s/", "", $_POST["Fax"]);
		$fax = preg_replace("/\./", "", $fax);
		if ($tel) {
			$object->values->Phone = $tel;
		}
		if ($fax) {
			$object->values->Fax = $fax;
		}
		$email = $_POST["email"];
		if ($email) {
			$object->values->EMail = $email;
		}

		$object->values->Web = clean_url(trim($_POST["Web"]), 0);

		if (!empty($_POST['Deal'])) {
			foreach ($_POST['Deal'] as $key => $aRow) {
				$object->values->$key = $aRow;
				/* $object->idprof["idprof2"]     = $_POST["idprof2"];
				  $object->idprof["idprof3"]     = $_POST["idprof3"];
				  $object->idprof["idprof4"]     = $_POST["idprof4"]; */
			}
		}

		$object->values->prefix_comm = $_POST["prefix_comm"];

		$object->values->CustomerCode = $_POST["code_client"];
		$object->values->SupplierCode = $_POST["code_fournisseur"];
		$object->values->VATIsUsed = (bool) $_POST["assujtva_value"];
		$object->values->VATIntra = dol_sanitizeFileName($_POST["tva_intra"], '');
		$object->values->localtax1_assuj = (int) $_POST["localtax1assuj_value"];
		$object->values->localtax2_assuj = (int) $_POST["localtax2assuj_value"];

		$object->values->JuridicalStatus = $_POST["forme_juridique_code"];
		$object->values->Capital = price2num(trim($_POST["capital"]), 'MT') . " " . $langs->trans("Currency" . $conf->currency);
		$object->values->Staff = $_POST["effectif_id"];

		$object->values->barcode = $_POST["barcode"];

		$object->values->Status = $_POST["Status"];

		$object->values->tms = dol_now();

		if (GETPOST("private") == 1) {
			$object->values->typent_id = 8; // TODO predict another method if the field "special" change of rowid
		} else {
			$object->values->typent_id = $_POST["typent_id"];
		}

		$object->values->client = (int) $_POST["client"];
		$object->values->fournisseur = (int) $_POST["fournisseur"];
		$object->values->fournisseur_categorie = $_POST["fournisseur_categorie"];

		$object->values->commercial_id = $_POST["commercial_id"];
		$object->values->DefaultLang = $_POST["default_lang"];

		if ($conf->map->enabled) {
			//Retire le CEDEX de la ville :
			$town = $_POST["town"];
			$town = strtolower($town);
			$find = "cedex";
			$pos = strpos($town, $find);
			if ($pos != false) {
				$town = substr($town, 0, $pos);
			}
			$apiUrl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=" . urlencode($_POST["adresse"] . "," . $_POST["zipcode"] . "," . $town);
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $apiUrl);
			curl_setopt($c, CURLOPT_HEADER, false);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			// make the call
			$json = curl_exec($c);
			$response = json_decode($json);
			curl_close($c);
			if ($response->status == "OK") {
				$object->values->gps = array($response->results[0]->geometry->location->lat, $response->results[0]->geometry->location->lng);
			} else {
				$object->values->gps = array(0, 0);
			}
		}

		if (GETPOST('deletephoto'))
			$object->values->logo = '';
		else if (!empty($_FILES['photo']['name']))
			$object->values->logo = dol_sanitizeFileName($_FILES['photo']['name']);

		// Check parameters
		if (empty($_POST["cancel"])) {
			if (!empty($object->values->Web) && !isValidUrl($object->values->Web)) {
				$langs->load("errors");
				$error++;
				$errors[] = $langs->trans("ErrorBadUrl", $object->values->Web);
				$action = ($action == 'add' ? 'create' : 'edit');
			}
			if ($object->fournisseur && !$conf->fournisseur->enabled) {
				$langs->load("errors");
				$error++;
				$errors[] = $langs->trans("ErrorSupplierModuleNotEnabled");
				$action = ($action == 'add' ? 'create' : 'edit');
			}

			/* for ($i = 1; $i < 3; $i++)
			  {
			  $slabel="idprof".$i;
			  if (($_POST[$slabel] && $object->id_prof_verifiable($i)))
			  {
			  if($object->id_prof_exists($i,$_POST["$slabel"],$object->id))
			  {
			  $langs->load("errors");
			  $error++; $errors[] = $langs->transcountry('ProfId'.$i, $object->country_id)." ".$langs->trans("ErrorProdIdAlreadyExist", $_POST[$slabel]);
			  $action = ($action=='add'?'create':'edit');
			  }
			  }
			  } */
		}
		if (!$error) {
			if ($action == 'add') {
				if (!empty($conf->global->MAIN_FIRST_TO_UPPER))
					$object->values->name = ucwords($object->values->name);

				dol_syslog(get_class($object) . "::create " . $object->values->name);

				// Check parameters
				if (!empty($conf->global->SOCIETE_MAIL_REQUIRED) && !isValidEMail($object->values->EMail)) {
					$langs->load("errors");
					$message = $langs->trans("ErrorBadEMail", $object->values->EMail);
					return -1;
				}

				$object->values->tms = dol_now();

				// For automatic creation during create action (not used by Dolibarr GUI, can be used by scripts)
				if ($object->values->CustomCode == -1)
					$compta->setCode("CustomerCode", $object->get_codeclient($object->values->prefix_comm, 0));
				if ($object->values->SupplierCode == -1)
					$compta->setCode("SupplierCode", $object->get_codefournisseur($object->values->prefix_comm, 1));

				if (!$message) {
					try {
						$object->record();
						Header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id);
						exit;
					} catch (Exception $e) {
						$message = "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
						dol_syslog("Ego::Create " . $message, LOG_ERR);
					}
				}

				$message = '<div class="error">' . $message . '</div>';
				$action = "create";

				$result = 0; // TODO go next
				if ($result >= 0) {
					if ($object->values->particulier) {
						dol_syslog("This thirdparty is a personal people", LOG_DEBUG);
						$contact = new Contact($db);

						$contact->civilite_id = $object->civilite_id;
						$contact->name = $object->name_bis;
						$contact->firstname = $object->firstname;
						$contact->address = $object->address;
						$contact->zip = $object->zip;
						$contact->town = $object->town;
						$contact->state_id = $object->state_id;
						$contact->country_id = $object->country_id;
						$contact->socid = $object->id; // fk_soc
						$contact->status = 1;
						$contact->email = $object->email;
						$contact->phone_pro = $object->tel;
						$contact->fax = $object->fax;
						$contact->priv = 0;

						$result = $contact->create($user);
						if (!$result >= 0) {
							$error = $contact->error;
							$errors = $contact->errors;
						}
					}

					// Gestion du logo de la société
					$dir = $conf->societe->multidir_output[$conf->entity] . "/" . $object->id . "/logos/";
					$file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
					if ($file_OK) {
						if (image_format_supported($_FILES['photo']['name'])) {
							dol_mkdir($dir);

							if (@is_dir($dir)) {
								$newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
								$result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

								if (!$result > 0) {
									$errors[] = "ErrorFailedToSaveFile";
								} else {
									// Create small thumbs for company (Ratio is near 16/9)
									// Used on logon for example
									$imgThumbSmall = vignette($newfile, $maxwidthsmall, $maxheightsmall, '_small', $quality);

									// Create mini thumbs for company (Ratio is near 16/9)
									// Used on menu or for setup page for example
									$imgThumbMini = vignette($newfile, $maxwidthmini, $maxheightmini, '_mini', $quality);
								}
							}
						}
					}
					// Gestion du logo de la société
				}

				if ($result >= 0) {
					$db->commit();

					$url = $_SERVER["PHP_SELF"] . "?id=" . $object->id;
					if (($object->client == 1 || $object->client == 3) && empty($conf->global->SOCIETE_DISABLE_CUSTOMERS))
						$url = DOL_URL_ROOT . "/comm/fiche.php?socid=" . $object->id;
					else if ($object->values->fournisseur == 1)
						$url = DOL_URL_ROOT . "/fourn/fiche.php?socid=" . $object->id;
					Header("Location: " . $url);
					exit;
				}
				else {
					$db->rollback();
					$action = 'create';
				}
			}

			if ($action == 'update') {
				if ($_POST["cancel"]) {
					Header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $socid);
					exit;
				}

				$backtopage = '';
				if (!empty($_POST["backtopage"]))
					$backtopage = $_POST["backtopage"];

				$oldcopy = dol_clone($object);

				### Calcul des coordonnées GPS
				if ($conf->map->enabled) {
					//Retire le CEDEX de la ville :
					$town = $_POST["town"];
					$town = strtolower($town);
					$find = "cedex";
					$pos = strpos($town, $find);
					if ($pos != false) {
						$town = substr($town, 0, $pos);
						//print $town;exit;
					}
					$apiUrl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=" . urlencode($_POST["adresse"] . "," . $_POST["zipcode"] . "," . $_POST["town"]);
					$c = curl_init();
					curl_setopt($c, CURLOPT_URL, $apiUrl);
					curl_setopt($c, CURLOPT_HEADER, false);
					curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
					// make the call
					$json = curl_exec($c);
					$response = json_decode($json);
					curl_close($c);
					if ($response->status == "OK") {
						$object->values->gps = array($response->results[0]->geometry->location->lat, $response->results[0]->geometry->location->lng);
					} else {
						$object->values->gps = array(0, 0);
					}
				}

				// To not set code if third party is not concerned. But if it had values, we keep them.
				if (empty($object->values->client) && empty($oldcopy->code_client))
					$object->code_client = '';
				if (empty($object->valeus->fournisseur) && empty($oldcopy->code_fournisseur))
					$object->code_fournisseur = '';
				//var_dump($object);exit;
				$soc = new Societe($db);
				// For automatic creation during create action (not used by Dolibarr GUI, can be used by scripts)
				if ($object->values->CustomCode == -1)
					$object->values->CustomCode = $soc->get_codeclient($object->values->prefix_comm, 0);
				if ($object->values->SupplierCode == -1)
					$object->values->SupplierCode = $soc->get_codefournisseur($object->values->prefix_comm, 1);

				//$object->update($socid,$user,1,$oldcopy->codeclient_modifiable(),$oldcopy->codefournisseur_modifiable());

				try {
					$object->record();
					$result = 1;
				} catch (Exception $e) {
					$error = "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
					print $error;
					exit;
				}

				// Gestion du logo de la société
				$dir = $conf->societe->multidir_output[$object->entity] . "/" . $object->_id . "/logos";
				$file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
				if ($file_OK) {
					if (GETPOST('deletephoto')) {
						$fileimg = $dir . '/' . $object->values->logo;
						$dirthumbs = $dir . '/thumbs';
						dol_delete_file($fileimg);
						dol_delete_dir_recursive($dirthumbs);
					}

					if (image_format_supported($_FILES['photo']['name']) > 0) {
						dol_mkdir($dir);

						if (@is_dir($dir)) {
							$newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
							$result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

							if (!$result > 0) {
								$errors[] = "ErrorFailedToSaveFile";
							} else {
								// Create small thumbs for company (Ratio is near 16/9)
								// Used on logon for example
								$imgThumbSmall = vignette($newfile, $maxwidthsmall, $maxheightsmall, '_small', $quality);

								// Create mini thumbs for company (Ratio is near 16/9)
								// Used on menu or for setup page for example
								$imgThumbMini = vignette($newfile, $maxwidthmini, $maxheightmini, '_mini', $quality);
							}
						}
					} else {
						$errors[] = "ErrorBadImageFormat";
					}
				}
				// Gestion du logo de la société

				if (!$error && !count($errors)) {
					?>
					<script language="javascript">    
						//code to close fancy box
						parent.$.fancybox.close();
					</script>
					<?php
					exit;
				} else {
					$action = "edit";
				}
			}
		}
	}
}



/*
 *  View
 */

$help_url = 'EN:Module_Third_Parties|FR:Module_Tiers|ES:Empresas';
llxHeader('', $langs->trans("ThirdParty"), $help_url);

$form = new Form($db);
$formfile = new FormFile($db);
$formadmin = new FormAdmin($db);
$formcompany = new FormCompany($db);

$countrynotdefined = $langs->trans("ErrorSetACountryFirst") . ' (' . $langs->trans("SeeAbove") . ')';


if (is_object($objcanvas) && $objcanvas->displayCanvasExists($action)) {
	// -----------------------------------------
	// When used with CANVAS
	// -----------------------------------------
	if (!$objcanvas->hasActions() && $socid) {
		$object->load($socid); // For use with "pure canvas" (canvas that contains templates only)
	}
	$objcanvas->assign_values($action, $socid); // Set value for templates
	$objcanvas->display_canvas($action);  // Show template
} else {
	// -----------------------------------------
	// When used in standard mode
	// -----------------------------------------
	if ($action == 'create') {
		/*
		 *  Creation
		 */

		// Load object modCodeTiers
		$module = $conf->global->SOCIETE_CODECLIENT_ADDON;
		if (!$module)
			dolibarr_error('', $langs->trans("ErrorModuleThirdPartyCodeInCompanyModuleNotDefined"));
		if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php') {
			$module = substr($module, 0, dol_strlen($module) - 4);
		}
		$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
		foreach ($dirsociete as $dirroot) {
			$res = dol_include_once($dirroot . $module . ".php");
			if ($res)
				break;
		}
		$modCodeClient = new $module;
		$module = $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;
		if (!$module)
			$module = $conf->global->SOCIETE_CODECLIENT_ADDON;
		if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php') {
			$module = substr($module, 0, dol_strlen($module) - 4);
		}
		$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
		foreach ($dirsociete as $dirroot) {
			$res = dol_include_once($dirroot . $module . ".php");
			if ($res)
				break;
		}
		$modCodeFournisseur = new $module;

		//if ($_GET["type"]=='cp') { $object->client=3; }
		if (GETPOST("type") != 'f') {
			$object->client = 3;
		}
		if (GETPOST("type") == 'c') {
			$object->client = 1;
		}
		if (GETPOST("type") == 'p') {
			$object->client = 2;
		}
		if ($conf->fournisseur->enabled && (GETPOST("type") == 'f' || GETPOST("type") == '')) {
			$object->fournisseur = 1;
		}
		if (GETPOST("private") == 1) {
			$object->particulier = 1;
		}

		$object->name = $_POST["nom"];
		$object->firstname = $_POST["prenom"];
		$object->particulier = GETPOST('private', 'int');
		$object->prefix_comm = $_POST["prefix_comm"];
		$object->client = $_POST["client"] ? $_POST["client"] : $object->client;
		$object->code_client = $_POST["code_client"];
		$object->fournisseur = $_POST["fournisseur"] ? $_POST["fournisseur"] : $object->fournisseur;
		$object->code_fournisseur = $_POST["code_fournisseur"];
		$object->address = $_POST["adresse"];
		$object->zip = $_POST["zipcode"];
		$object->town = $_POST["town"];
		$object->state_id = $_POST["departement_id"];
		$object->tel = $_POST["tel"];
		$object->fax = $_POST["fax"];
		$object->email = $_POST["email"];
		$object->url = $_POST["url"];
		$object->capital = $_POST["capital"];
		$object->barcode = $_POST["barcode"];
		$object->idprof1 = $_POST["idprof1"];
		$object->idprof2 = $_POST["idprof2"];
		$object->idprof3 = $_POST["idprof3"];
		$object->idprof4 = $_POST["idprof4"];
		$object->typent_id = $_POST["typent_id"];
		$object->effectif_id = $_POST["effectif_id"];
		$object->civility_id = $_POST["civilite_id"];

		$object->tva_assuj = $_POST["assujtva_value"];
		$object->status = $_POST["status"];

		//Local Taxes
		$object->localtax1_assuj = $_POST["localtax1assuj_value"];
		$object->localtax2_assuj = $_POST["localtax2assuj_value"];

		$object->tva_intra = $_POST["tva_intra"];

		$object->commercial_id = $_POST["commercial_id"];
		$object->default_lang = $_POST["default_lang"];

		$object->logo = dol_sanitizeFileName($_FILES['photo']['name']);

		// Gestion du logo de la société
		$dir = $conf->societe->multidir_output[$object->entity] . "/" . $object->id . "/logos";
		$file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
		if ($file_OK) {
			if (image_format_supported($_FILES['photo']['name'])) {
				dol_mkdir($dir);

				if (@is_dir($dir)) {
					$newfile = $dir . '/' . dol_sanitizeFileName($_FILES['photo']['name']);
					$result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

					if (!$result > 0) {
						$errors[] = "ErrorFailedToSaveFile";
					} else {
						// Create small thumbs for company (Ratio is near 16/9)
						// Used on logon for example
						$imgThumbSmall = vignette($newfile, $maxwidthsmall, $maxheightsmall, '_small', $quality);

						// Create mini thumbs for company (Ratio is near 16/9)
						// Used on menu or for setup page for example
						$imgThumbMini = vignette($newfile, $maxwidthmini, $maxheightmini, '_mini', $quality);
					}
				}
			}
		}

		// We set country_id, country_id and country for the selected country
		$object->country_id = $_POST["country_id"] ? $_POST["country_id"] : $mysoc->country_id;
		if ($object->country_id) {
			$tmparray = getCountry($object->country_id, 'all');
			$object->country_id = $tmparray['code'];
			$object->country = $tmparray['label'];
		}
		$object->forme_juridique_code = $_POST['forme_juridique_code'];
		/* Show create form */

		print_fiche_titre($langs->trans("NewCompany"));

		if ($conf->use_javascript_ajax) {
			print "\n" . '<script type="text/javascript">';
			print '$(document).ready(function () {
						id_te_private=8;
                        id_ef15=1;
                        is_private=' . (GETPOST("private") ? GETPOST("private") : 0) . ';
						if (is_private) {
							$(".individualline").show();
						} else {
							$(".individualline").hide();
						}
                        $("#radiocompany").click(function() {
                        	$(".individualline").hide();
                        	$("#typent_id").val(0);
                        	$("#effectif_id").val(0);
                        	$("#TypeName").html(document.formsoc.ThirdPartyName.value);
                        	document.formsoc.private.value=0;
                        });
                        $("#radioprivate").click(function() {
                        	$(".individualline").show();
                        	$("#typent_id").val(id_te_private);
                        	$("#effectif_id").val(id_ef15);
                        	$("#TypeName").html(document.formsoc.LastName.value);
                        	document.formsoc.private.value=1;
                        });
                        $("#selectcountry_id").change(function() {
                        	document.formsoc.action.value="create";
                        	document.formsoc.submit();
                        });
                     });';
			print '</script>' . "\n";

			print "<br>\n";
			print $langs->trans("ThirdPartyType") . ': &nbsp; ';
			print '<input type="radio" id="radiocompany" class="flat" name="private" value="0"' . (!GETPOST("private") ? ' checked="checked"' : '');
			print '> ' . $langs->trans("Company/Fundation");
			print ' &nbsp; &nbsp; ';
			print '<input type="radio" id="radioprivate" class="flat" name="private" value="1"' . (!GETPOST("private") ? '' : ' checked="checked"');
			print '> ' . $langs->trans("Individual");
			print ' (' . $langs->trans("ToCreateContactWithSameName") . ')';
			print "<br>\n";
			print "<br>\n";
		}


		dol_htmloutput_errors($error, $errors);

		print '<form enctype="multipart/form-data" action="' . $_SERVER["PHP_SELF"] . '" method="post" name="formsoc">';

		print '<input type="hidden" name="action" value="add">';
		print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
		print '<input type="hidden" name="private" value=' . $object->particulier . '>';
		print '<input type="hidden" name="type" value=' . GETPOST("type") . '>';
		print '<input type="hidden" name="LastName" value="' . $langs->trans('LastName') . '">';
		print '<input type="hidden" name="ThirdPartyName" value="' . $langs->trans('ThirdPartyName') . '">';
		if ($modCodeClient->code_auto || $modCodeFournisseur->code_auto)
			print '<input type="hidden" name="code_auto" value="1">';

		print '<table class="border" width="100%">';

		// Name, firstname
		if ($object->particulier || GETPOST("private")) {
			print '<tr><td><span id="TypeName" class="fieldrequired">' . $langs->trans('LastName') . '</span></td><td' . (empty($conf->global->SOCIETE_USEPREFIX) ? ' colspan="3"' : '') . '><input type="text" size="30" maxlength="60" name="nom" value="' . $object->name . '"></td>';
			if (!empty($conf->global->SOCIETE_USEPREFIX)) {  // Old not used prefix field
				print '<td>' . $langs->trans('Prefix') . '</td><td><input type="text" size="5" maxlength="5" name="prefix_comm" value="' . $object->prefix_comm . '"></td>';
			}
			print '</tr>';
		} else {
			print '<tr><td><span span id="TypeName" class="fieldrequired">' . $langs->trans('ThirdPartyName') . '</span></td><td' . (empty($conf->global->SOCIETE_USEPREFIX) ? ' colspan="3"' : '') . '><input type="text" size="30" maxlength="60" name="nom" value="' . $object->name . '"></td>';
			if (!empty($conf->global->SOCIETE_USEPREFIX)) {  // Old not used prefix field
				print '<td>' . $langs->trans('Prefix') . '</td><td><input type="text" size="5" maxlength="5" name="prefix_comm" value="' . $object->prefix_comm . '"></td>';
			}
			print '</tr>';
		}
		// If javascript on, we show option individual
		if ($conf->use_javascript_ajax) {
			print '<tr class="individualline"><td>' . $langs->trans('FirstName') . '</td><td><input type="text" size="30" name="prenom" value="' . $object->firstname . '"></td>';
			print '<td colspan=2>&nbsp;</td></tr>';
			print '<tr class="individualline"><td>' . $langs->trans("UserTitle") . '</td><td>';
			print $formcompany->select_civility($object->civility_id) . '</td>';
			print '<td colspan=2>&nbsp;</td></tr>';
		}

		// Prospect/Customer
		print '<tr><td width="25%"><span class="fieldrequired">' . $langs->trans('ProspectCustomer') . '</span></td><td width="25%"><select class="flat" name="client">';
		$selected = isset($_POST['client']) ? GETPOST('client') : $object->client;
		if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS))
			print '<option value="2"' . ($selected == 2 ? ' selected="selected"' : '') . '>' . $langs->trans('Prospect') . '</option>';
		if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS))
			print '<option value="3"' . ($selected == 3 ? ' selected="selected"' : '') . '>' . $langs->trans('ProspectCustomer') . '</option>';
		print '<option value="1"' . ($selected == 1 ? ' selected="selected"' : '') . '>' . $langs->trans('Customer') . '</option>';
		print '<option value="0"' . ($selected == 0 ? ' selected="selected"' : '') . '>' . $langs->trans('NorProspectNorCustomer') . '</option>';
		print '</select></td>';

		print '<td width="25%">' . $langs->trans('CustomerCode') . '</td><td width="25%">';
		print '<table class="nobordernopadding"><tr><td>';
		$tmpcode = $object->code_client;
		if ($modCodeClient->code_auto)
			$tmpcode = $modCodeClient->getNextValue($object, 0);
		print '<input type="text" name="code_client" size="16" value="' . $tmpcode . '" maxlength="15">';
		print '</td><td>';
		$s = $modCodeClient->getToolTip($langs, $object, 0);
		print $form->textwithpicto('', $s, 1);
		print '</td></tr></table>';

		print '</td></tr>';

		if ($conf->fournisseur->enabled && !empty($user->rights->fournisseur->lire)) {
			// Supplier
			print '<tr>';
			print '<td><span class="fieldrequired">' . $langs->trans('Supplier') . '</span></td><td>';
			print $form->selectyesno("fournisseur", (isset($_POST['fournisseur']) ? GETPOST('fournisseur') : $object->fournisseur), 1);
			print '</td>';
			print '<td>' . $langs->trans('SupplierCode') . '</td><td>';
			print '<table class="nobordernopadding"><tr><td>';
			$tmpcode = $object->code_fournisseur;
			if ($modCodeFournisseur->code_auto)
				$tmpcode = $modCodeFournisseur->getNextValue($object, 1);
			print '<input type="text" name="code_fournisseur" size="16" value="' . $tmpcode . '" maxlength="15">';
			print '</td><td>';
			$s = $modCodeFournisseur->getToolTip($langs, $object, 1);
			print $form->textwithpicto('', $s, 1);
			print '</td></tr></table>';
			print '</td></tr>';

			// Category
			/* if ($object->fournisseur)
			  {
			  $load = $object->LoadSupplierCateg();
			  if ( $load == 0)
			  {
			  if (count($object->SupplierCategories) > 0)
			  {
			  print '<tr>';
			  print '<td>'.$langs->trans('SupplierCategory').'</td><td colspan="3">';
			  print $form->selectarray("fournisseur_categorie",$object->SupplierCategories,$_POST["fournisseur_categorie"],1);
			  print '</td></tr>';
			  }
			  }
			  } */
		}

		// Status
		print '<tr><td>' . $langs->trans('Status') . '</td><td colspan="3">';
		print $form->selectarray('status', array('0' => $langs->trans('ActivityCeased'), '1' => $langs->trans('InActivity')), 1);
		print '</td></tr>';

		// Barcode
		if ($conf->global->MAIN_MODULE_BARCODE) {
			print '<tr><td>' . $langs->trans('Gencod') . '</td><td colspan="3"><input type="text" name="barcode" value="' . $object->barcode . '">';
			print '</td></tr>';
		}

		// Address
		print '<tr><td valign="top">' . $langs->trans('Address') . '</td><td colspan="3"><textarea name="adresse" cols="40" rows="3" wrap="soft">';
		print $object->address;
		print '</textarea></td></tr>';

		// Zip / Town
		print '<tr><td>' . $langs->trans('Zip') . '</td><td>';
		print $formcompany->select_ziptown($object->zip, 'zipcode', array('town', 'selectcountry_id', 'departement_id'), 6);
		print '</td><td>' . $langs->trans('Town') . '</td><td>';
		print $formcompany->select_ziptown($object->town, 'town', array('zipcode', 'selectcountry_id', 'departement_id'));
		print '</td></tr>';

		// Country
		print '<tr><td width="25%">' . $langs->trans('Country') . '</td><td colspan="3">';
		print $form->select_country($object->country_id, 'country_id');
		if ($user->admin)
			print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
		print '</td></tr>';

		// State
		if (empty($conf->global->SOCIETE_DISABLE_STATE)) {
			print '<tr><td>' . $langs->trans('State') . '</td><td colspan="3">';
			if ($object->country_id)
				print $formcompany->select_state($object->state_id, $object->country_id, 'departement_id');
			else
				print $countrynotdefined;
			print '</td></tr>';
		}

		// Phone / Fax
		print '<tr><td>' . $langs->trans('Phone') . '</td><td><input type="text" name="tel" value="' . $object->tel . '"></td>';
		print '<td>' . $langs->trans('Fax') . '</td><td><input type="text" name="fax" value="' . $object->fax . '"></td></tr>';

		print '<tr><td>' . $langs->trans('EMail') . ($conf->global->SOCIETE_MAIL_REQUIRED ? '*' : '') . '</td><td><input type="text" name="email" size="32" value="' . $object->email . '"></td>';
		print '<td>' . $langs->trans('Web') . '</td><td><input type="text" name="url" size="32" value="' . $object->url . '"></td></tr>';

		// Prof ids
		$i = 1;
		$j = 0;
		while ($i <= 6) {
			$idprof = $langs->transcountry('ProfId' . $i, $object->country_id);
			if ($idprof != '-') {
				if (($j % 2) == 0)
					print '<tr>';
				print '<td>' . $idprof . '</td><td>';
				$key = 'idprof' . $i;
				print $formcompany->get_input_id_prof($i, 'idprof' . $i, $object->$key, $object->country_id);
				print '</td>';
				if (($j % 2) == 1)
					print '</tr>';
				$j++;
			}
			$i++;
		}
		if ($j % 2 == 1)
			print '<td colspan="2"></td></tr>';

		// Assujeti TVA
		$form = new Form($db);
		print '<tr><td>' . $langs->trans('VATIsUsed') . '</td>';
		print '<td>';
		print $form->selectyesno('assujtva_value', 1, 1);  // Assujeti par defaut en creation
		print '</td>';
		print '<td nowrap="nowrap">' . $langs->trans('VATIntra') . '</td>';
		print '<td nowrap="nowrap">';
		$s = '<input type="text" class="flat" name="tva_intra" size="12" maxlength="20" value="' . $object->tva_intra . '">';

		if (empty($conf->global->MAIN_DISABLEVATCHECK)) {
			$s.=' ';

			if ($conf->use_javascript_ajax) {
				print "\n";
				print '<script language="JavaScript" type="text/javascript">';
				print "function CheckVAT(a) {\n";
				print "newpopup('" . DOL_URL_ROOT . "/societe/checkvat/checkVatPopup.php?vatNumber='+a,'" . dol_escape_js($langs->trans("VATIntraCheckableOnEUSite")) . "',500,230);\n";
				print "}\n";
				print '</script>';
				print "\n";
				$s.='<a href="#" onclick="javascript: CheckVAT(document.formsoc.tva_intra.value);">' . $langs->trans("VATIntraCheck") . '</a>';
				$s = $form->textwithpicto($s, $langs->trans("VATIntraCheckDesc", $langs->trans("VATIntraCheck")), 1);
			} else {
				$s.='<a href="' . $langs->transcountry("VATIntraCheckURL", $object->id_pays) . '" target="_blank">' . img_picto($langs->trans("VATIntraCheckableOnEUSite"), 'help') . '</a>';
			}
		}
		print $s;
		print '</td>';
		print '</tr>';

		// Type - Size
		print '<tr><td>' . $langs->trans("ThirdPartyType") . '</td><td>' . "\n";
		print $form->selectarray("typent_id", $formcompany->typent_array(0), $object->typent_id);
		if ($user->admin)
			print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
		print '</td>';
		print '<td>' . $langs->trans("Staff") . '</td><td>';
		print $form->selectarray("effectif_id", $formcompany->effectif_array(0), $object->effectif_id);
		if ($user->admin)
			print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
		print '</td></tr>';

		// Legal Form
		print '<tr><td>' . $langs->trans('JuridicalStatus') . '</td>';
		print '<td colspan="3">';
		if ($object->country_id) {
			$formcompany->select_forme_juridique($object->forme_juridique_code, $object->country_id);
		} else {
			print $countrynotdefined;
		}
		print '</td></tr>';

		// Capital
		print '<tr><td>' . $langs->trans('Capital') . '</td><td colspan="3"><input type="text" name="capital" size="10" value="' . $object->capital . '"> ' . $langs->trans("Currency" . $conf->currency) . '</td></tr>';

		// Local Taxes
		// TODO add specific function by country
		if ($mysoc->country_id == 'ES') {
			if ($mysoc->localtax1_assuj == "1" && $mysoc->localtax2_assuj == "1") {
				print '<tr><td>' . $langs->trans("LocalTax1IsUsedES") . '</td><td>';
				print $form->selectyesno('localtax1assuj_value', 0, 1);
				print '</td><td>' . $langs->trans("LocalTax2IsUsedES") . '</td><td>';
				print $form->selectyesno('localtax2assuj_value', 0, 1);
				print '</td></tr>';
			} elseif ($mysoc->localtax1_assuj == "1") {
				print '<tr><td>' . $langs->trans("LocalTax1IsUsedES") . '</td><td colspan="3">';
				print $form->selectyesno('localtax1assuj_value', 0, 1);
				print '</td><tr>';
			} elseif ($mysoc->localtax2_assuj == "1") {
				print '<tr><td>' . $langs->trans("LocalTax2IsUsedES") . '</td><td colspan="3">';
				print $form->selectyesno('localtax2assuj_value', 0, 1);
				print '</td><tr>';
			}
		}

		if ($conf->global->MAIN_MULTILANGS) {
			print '<tr><td>' . $langs->trans("DefaultLang") . '</td><td colspan="3">' . "\n";
			print $formadmin->select_language(($object->default_lang ? $object->default_lang : $conf->global->MAIN_LANG_DEFAULT), 'default_lang', 0, 0, 1);
			print '</td>';
			print '</tr>';
		}

		if ($user->rights->societe->client->voir) {
			// Assign a Name
			print '<tr>';
			print '<td>' . $langs->trans("AllocateCommercial") . '</td>';
			print '<td colspan="3">';
			$form->select_users($object->commercial_id, 'commercial_id', 1);
			print '</td></tr>';
		}

		// Ajout du logo
		print '<tr>';
		print '<td>' . $langs->trans("Logo") . '</td>';
		print '<td colspan="3">';
		print '<input class="flat" type="file" name="photo" id="photoinput" />';
		print '</td>';
		print '</tr>';

		print '</table>' . "\n";

		print '<br><center>';
		print '<input type="submit" class="button" value="' . $langs->trans('AddThirdParty') . '">';
		print '</center>' . "\n";

		print '</form>' . "\n";
	} else {
		/*
		 * Edition
		 */

		try {
			$object->load($socid);
		} catch (Exception $e) {
			$error = "Something weird happened: " . $e->getMessage() . " (errcode=" . $e->getCode() . ")\n";
			print $error;
			exit;
		}


		// Load object modCodeTiers
		$module = $conf->global->SOCIETE_CODECLIENT_ADDON;
		if (!$module)
			dolibarr_error('', $langs->trans("ErrorModuleThirdPartyCodeInCompanyModuleNotDefined"));
		if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php') {
			$module = substr($module, 0, dol_strlen($module) - 4);
		}
		$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
		foreach ($dirsociete as $dirroot) {
			$res = dol_include_once($dirroot . $module . ".php");
			if ($res)
				break;
		}
		$modCodeClient = new $module;
		$module = $conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;
		if (!$module)
			$module = $conf->global->SOCIETE_CODECLIENT_ADDON;
		if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php') {
			$module = substr($module, 0, dol_strlen($module) - 4);
		}
		$dirsociete = array_merge(array('/core/modules/societe/'), $conf->societe_modules);
		foreach ($dirsociete as $dirroot) {
			$res = dol_include_once($dirroot . $module . ".php");
			if ($res)
				break;
		}
		$modCodeFournisseur = new $module;

		if (!empty($_POST["ThirdPartyName"])) {
			// We overwrite with values if posted
			$object->ThirdPartyName = $_POST["ThirdPartyName"];
			$object->prefix_comm = $_POST["prefix_comm"];
			$object->client = $_POST["client"];
			$object->code_client = $_POST["code_client"];
			$object->fournisseur = $_POST["fournisseur"];
			$object->code_fournisseur = $_POST["code_fournisseur"];
			$object->address = $_POST["adresse"];
			$object->zip = $_POST["zipcode"];
			$object->town = $_POST["town"];
			$object->country_id = $_POST["country_id"] ? $_POST["country_id"] : $mysoc->country_id;
			$object->Country = $_POST["Country"] ? $_POST["Country"] : $mysoc->country_code;
			$object->state_id = $_POST["departement_id"];
			$object->tel = $_POST["tel"];
			$object->fax = $_POST["fax"];
			$object->email = $_POST["email"];
			$object->url = $_POST["url"];
			$object->capital = $_POST["capital"];
			$object->idprof1 = $_POST["idprof1"];
			$object->idprof2 = $_POST["idprof2"];
			$object->idprof3 = $_POST["idprof3"];
			$object->idprof4 = $_POST["idprof4"];
			$object->typent_id = $_POST["typent_id"];
			$object->effectif_id = $_POST["effectif_id"];
			$object->barcode = $_POST["barcode"];
			$object->forme_juridique_code = $_POST["forme_juridique_code"];
			$object->default_lang = $_POST["default_lang"];

			$object->tva_assuj = $_POST["assujtva_value"];
			$object->tva_intra = $_POST["tva_intra"];
			$object->status = $_POST["status"];

			//Local Taxes
			$object->localtax1_assuj = $_POST["localtax1assuj_value"];
			$object->localtax2_assuj = $_POST["localtax2assuj_value"];

			// We set country_id, and pays_code label of the chosen country
			// TODO move to DAO class
			if ($object->values->Country) {
				$sql = "SELECT code, libelle from " . MAIN_DB_PREFIX . "c_pays where code = '" . $object->values->Country . "'";
				$resql = $db->query($sql);
				if ($resql) {
					$obj = $db->fetch_object($resql);
				} else {
					dol_print_error($db);
				}
				$object->countryname = $langs->trans("Country" . $object->values->Country);
			}
		}

		dol_htmloutput_errors($error, $errors);
		?>
		<!-- BEGIN PHP TEMPLATE thirdparty update card -->
		<script type="text/javascript">
			$(document).ready(function () {
				$("#selectCountry").change(function() {
					document.formsoc.action.value="edit";
					document.formsoc.submit();
				});
			})
		</script>

		<form id="validate_wizard" class="stepy-wizzard nice" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $object->id; ?>" method="POST" name="formsoc">
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
			<input type="hidden" name="id" value="<?php echo $object->id; ?>">
			<?php if ($auto_customer_code->auto || $auto_supplier_code->auto): ?>
				<input type="hidden" name="code_auto" value="1">
			<?php endif; ?>

			<?php for ($i = 0; $i < count($object->fk_extrafields->place); $i++): ?>
				<fieldset title="<?php echo $langs->trans($object->fk_extrafields->block[$i]); ?>">
					<legend><?php echo $langs->trans($object->fk_extrafields->block[$i] . "Resume"); ?></legend>

					<div class="row">
						<div class="two columns">
							<div class="form_legend">
								<h4><?php echo $langs->trans($object->fk_extrafields->block[$i]); ?></h4>
								<p><?php echo $langs->trans($object->fk_extrafields->block[$i] . "Legend"); ?></p>
							</div>
						</div>
						<div class="ten columns">
							<div class="form_content"><!-- forms columns -->
								<div class="eight columns"><!-- center form -->
									<?php
									foreach ($object->fk_extrafields->place[$i][0] as $key) {
										$aRow = $object->fk_extrafields->fields->$key;
										print $object->form($aRow, $key, "large");
									}
									?>			</div><!-- end center form -->
								<div class="four columns"><!-- right form -->
									<?php
									foreach ($object->fk_extrafields->place[$i][1] as $key) {
										$aRow = $object->fk_extrafields->fields->$key;
										print $object->form($aRow, $key, "small");
									}
									?>
								</div><!-- end right form -->
							</div><!-- end forms columns -->
						</div><!-- end ten columns -->
					</div><!-- end row -->
				</fieldset>
			<?php endfor; ?>
			<button type="submit" class="finish gh_button icon approve primary"><?php echo $langs->trans("Save"); ?></button>

			<script>
				$(document).ready(function() {
					$('#validate_wizard').stepy({
						backLabel	: 'Previous',
						block		: true,
						errorImage	: true,
						nextLabel	: 'Next',
						titleClick	: true,
						validate	: true
					});
					$('#validate_wizard').validate({
						errorPlacement: function(error, element) {
							error.appendTo( element.closest("div.elVal") );
						}, highlight: function(element) {
							$(element).closest('div.elVal').addClass("form-field error");
						}, unhighlight: function(element) {
							$(element).closest('div.elVal').removeClass("form-field error");
						}, rules: {
		<?php foreach ($object->fk_extrafields->fields as $key => $aRow) : ?>
			<?php if (isset($aRow->validate)) : ?>
				<?php echo "'" . $key . "'"; ?> : {
				<?php foreach ($aRow->validate as $idx => $row) : ?>
					<?php echo $idx . " : " . $row; ?>,
				<?php endforeach; ?>
									},
			<?php endif; ?>
		<?php endforeach; ?>
				},
				ignore				: ':hidden'
			});
		<?php foreach ($object->fk_extrafields->fields as $key => $aRow) : ?>
			<?php if (isset($aRow->mask)) : ?>
						$("#<?php echo $key; ?>").inputmask("<?php echo $aRow->mask; ?>");
			<?php endif; ?>
			<?php if (isset($aRow->spinner)) : ?>
						$("#<?php echo $key; ?>").spinner(<?php echo $aRow->spinner; ?>);
			<?php endif; ?>
		<?php endforeach; ?>
			$('.stepy-titles').each(function(){
				$(this).children('li').each(function(index){
					var myIndex = index + 1
					$(this).append('<span class="stepNb">'+myIndex+'</span>');
				})
			})
			$(".chzn-select").chosen();
		});
			</script>

			<?php
			print '<table class="border" width="100%">';

			// VAT payers
			print '<tr><td>' . $langs->trans('VATIsUsed') . '</td><td>';
			print $form->selectyesno('assujtva_value', $object->tva_assuj, 1);
			print '</td>';

			// VAT Code
			print '<td nowrap="nowrap">' . $langs->trans('VATIntra') . '</td>';
			print '<td nowrap="nowrap">';
			$s = '<input type="text" class="flat" name="tva_intra" size="12" maxlength="20" value="' . $object->tva_intra . '">';

			if (empty($conf->global->MAIN_DISABLEVATCHECK)) {
				$s.=' &nbsp; ';

					print "\n";
					print '<script language="JavaScript" type="text/javascript">';
					print "function CheckVAT(a) {\n";
					print "newpopup('" . DOL_URL_ROOT . "/societe/checkvat/checkVatPopup.php?vatNumber='+a,'" . dol_escape_js($langs->trans("VATIntraCheckableOnEUSite")) . "',500,285);\n";
					print "}\n";
					print '</script>';
					print "\n";
					$s.='<a href="#" onclick="javascript: CheckVAT(document.formsoc.tva_intra.value);">' . $langs->trans("VATIntraCheck") . '</a>';
					$s = $form->textwithpicto($s, $langs->trans("VATIntraCheckDesc", $langs->trans("VATIntraCheck")), 1);
//				} else {
//					$s.='<a href="' . $langs->transcountry("VATIntraCheckURL", $object->id_pays) . '" target="_blank">' . img_picto($langs->trans("VATIntraCheckableOnEUSite"), 'help') . '</a>';
//				}
			}
			print $s;
			print '</td>';
			print '</tr>';

			// Local Taxes
			// TODO add specific function by country
			if ($mysoc->country_id == 'ES') {
				if ($mysoc->localtax1_assuj == "1" && $mysoc->localtax2_assuj == "1") {
					print '<tr><td>' . $langs->trans("LocalTax1IsUsedES") . '</td><td>';
					print $form->selectyesno('localtax1assuj_value', $object->localtax1_assuj, 1);
					print '</td><td>' . $langs->trans("LocalTax2IsUsedES") . '</td><td>';
					print $form->selectyesno('localtax2assuj_value', $object->localtax2_assuj, 1);
					print '</td></tr>';
				} elseif ($mysoc->localtax1_assuj == "1") {
					print '<tr><td>' . $langs->trans("LocalTax1IsUsedES") . '</td><td colspan="3">';
					print $form->selectyesno('localtax1assuj_value', $object->localtax1_assuj, 1);
					print '</td></tr>';
				} elseif ($mysoc->localtax2_assuj == "1") {
					print '<tr><td>' . $langs->trans("LocalTax2IsUsedES") . '</td><td colspan="3">';
					print $form->selectyesno('localtax2assuj_value', $object->localtax2_assuj, 1);
					print '</td></tr>';
				}
			}

			// Type - Size
			print '<tr><td>' . $langs->trans("ThirdPartyType") . '</td><td>';
			print $form->selectarray("typent_id", $formcompany->typent_array(1), $object->typent_id);
			if ($user->admin)
				print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
			print '</td>';
			print '<td>' . $langs->trans("Staff") . '</td><td>';
			print $form->selectarray("effectif_id", $formcompany->effectif_array(1), $object->effectif_id);
			if ($user->admin)
				print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
			print '</td></tr>';

			print '<tr><td>' . $langs->trans('JuridicalStatus') . '</td><td colspan="3">';
			//print $form->selectarray("forme_juridique_code",$formcompany->forme_juridique_array(1), $object->forme_juridique_code);
			$formcompany->select_forme_juridique($object->Deal->JuridicalStatus, $object->country_id);
			if ($user->admin)
				print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"), 1);
			print '</td></tr>';

			// Capital
			print '<tr><td>' . $langs->trans("Capital") . '</td><td colspan="3"><input type="text" name="capital" size="10" value="' . $object->capital . '"> ' . $langs->trans("Currency" . $conf->currency) . '</td></tr>';

			// Default language
			if ($conf->global->MAIN_MULTILANGS) {
				print '<tr><td>' . $langs->trans("DefaultLang") . '</td><td colspan="3">' . "\n";
				print $formadmin->select_language($object->default_lang, 'default_lang', 0, 0, 1);
				print '</td>';
				print '</tr>';
			}

			// Logo
			print '<tr>';
			print '<td>' . $langs->trans("Logo") . '</td>';
			print '<td colspan="3">';
			if ($object->logo)
				print $form->showphoto('societe', $object, 50);
			$caneditfield = 1;
			if ($caneditfield) {
				if ($object->logo)
					print "<br>\n";
				print '<table class="nobordernopadding">';
				if ($object->logo)
					print '<tr><td><input type="checkbox" class="flat" name="deletephoto" id="photodelete"> ' . $langs->trans("Delete") . '<br><br></td></tr>';
				//print '<tr><td>'.$langs->trans("PhotoFile").'</td></tr>';
				print '<tr><td><input type="file" class="flat" name="photo" id="photoinput"></td></tr>';
				print '</table>';
			}
			print '</td>';
			print '</tr>';

			print '</table>';
			print '<br>';

			print '<center>';
			print '<input type="submit" class="button" name="save" value="' . $langs->trans("Save") . '">';
			print ' &nbsp; &nbsp; ';
			print '<input type="submit" class="button" name="cancel" value="' . $langs->trans("Cancel") . '">';
			print '</center>';

			print '</form>';
		}
	}

	print '</div>'; // end row
// End of page
	llxFooter();
	$db->close();
	?>
