<?php
/* Copyright (C) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Xavier Dutoit        <doli@sydesy.com>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2004      Sebastien Di Cintio  <sdicintio@ressource-toi.org>
 * Copyright (C) 2004      Benoit Mortier       <benoit.mortier@opensides.be>
 * Copyright (C) 2005-2013 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Philippe Grand       <philippe.grand@atoo-net.com>
 * Copyright (C) 2008      Matteli
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
 * Copyright (C) 2011      Juanjo Menent        <jmenent@2byte.es>
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

// For optionnal tuning. Enabled if environment variable DOL_TUNING is defined.
// A call first. Is the equivalent function dol_microtime_float not yet loaded.
$micro_start_time = 0;
if (!empty($_SERVER['DOL_TUNING'])) {
	list($usec, $sec) = explode(" ", microtime());
	$micro_start_time = ((float) $usec + (float) $sec);
	// Add Xdebug code coverage
	//define('XDEBUGCOVERAGE',1);
	if (defined('XDEBUGCOVERAGE')) {
		xdebug_start_code_coverage();
	}
}

/**
 * Security: SQL Injection and XSS Injection (scripts) protection (Filters on GET, POST, PHP_SELF).
 *
 * @param		string		$val		Value
 * @param		string		$type		1=GET, 0=POST, 2=PHP_SELF
 * @return		boolean					true if there is an injection
 */
function test_sql_and_script_inject($val, $type) {
	$sql_inj = 0;
	// For SQL Injection (only GET and POST are used to be included into bad escaped SQL requests)
	if ($type != 2) {
		$sql_inj += preg_match('/delete[\s]+from/i', $val);
		$sql_inj += preg_match('/create[\s]+table/i', $val);
		$sql_inj += preg_match('/update.+set.+=/i', $val);
		$sql_inj += preg_match('/insert[\s]+into/i', $val);
		$sql_inj += preg_match('/select.+from/i', $val);
		$sql_inj += preg_match('/union.+select/i', $val);
		$sql_inj += preg_match('/(\.\.%2f)+/i', $val);
	}
	// For XSS Injection done by adding javascript with script
	// This is all cases a browser consider text is javascript:
	// When it found '<script', 'javascript:', '<style', 'onload\s=' on body tag, '="&' on a tag size with old browsers
	// All examples on page: http://ha.ckers.org/xss.html#XSScalc
	$sql_inj += preg_match('/<script/i', $val);
	//$sql_inj += preg_match('/<style/i', $val);
	$sql_inj += preg_match('/base[\s]+href/i', $val);
	if ($type == 1) {
		$sql_inj += preg_match('/javascript:/i', $val);
		$sql_inj += preg_match('/vbscript:/i', $val);
	}
	// For XSS Injection done by adding javascript closing html tags like with onmousemove, etc... (closing a src or href tag with not cleaned param)
	if ($type == 1)
		$sql_inj += preg_match('/"/i', $val);   // We refused " in GET parameters value
	if ($type == 2)
		$sql_inj += preg_match('/[\s;"]/', $val); // PHP_SELF is an url and must match url syntax
	return $sql_inj;
}

/**
 * Security: Return true if OK, false otherwise.
 *
 * @param		string		&$var		Variable name
 * @param		string		$type		1=GET, 0=POST, 2=PHP_SELF
 * @return		boolean					true if ther is an injection
 */
function analyse_sql_and_script(&$var, $type) {
	if (is_array($var)) {
		foreach ($var as $key => $value) {
			if (analyse_sql_and_script($value, $type)) {
				$var[$key] = $value;
			} else {
				print 'Access refused by SQL/Script injection protection in main.inc.php';
				exit;
			}
		}
		return true;
	} else {
		return (test_sql_and_script_inject($var, $type) <= 0);
	}
}

// Sanity check on URL
if (!empty($_SERVER["PHP_SELF"])) {
	$morevaltochecklikepost = array($_SERVER["PHP_SELF"]);
	analyse_sql_and_script($morevaltochecklikepost, 2);
}
// Sanity check on GET parameters
if (!empty($_SERVER["QUERY_STRING"])) {
	$morevaltochecklikeget = array($_SERVER["QUERY_STRING"]);
	analyse_sql_and_script($morevaltochecklikeget, 1);
}
// Sanity check on POST
analyse_sql_and_script($_POST, 0);

// This is to make Speedealing working with Plesk
if (!empty($_SERVER['DOCUMENT_ROOT']))
	set_include_path($_SERVER['DOCUMENT_ROOT'] . '/htdocs');

// Include the conf.php and functions.lib.php
require 'filefunc.inc.php';

// Init session. Name of session is specific to Speedealing instance.
$prefix = dol_getprefix();
$sessionname = 'DOLSESSID_' . $prefix;
$sessiontimeout = 'DOLSESSTIMEOUT_' . $prefix;
$count_icon = 0; // For counter favicon
if (!empty($_COOKIE[$sessiontimeout]))
	ini_set('session.gc_maxlifetime', $_COOKIE[$sessiontimeout]);
session_name($sessionname);
session_start();
if (ini_get('register_globals')) { // To solve bug in using $_SESSION
	foreach ($_SESSION as $key => $value) {
		if (isset($GLOBALS[$key]))
			unset($GLOBALS[$key]);
	}
}

// Init the 5 global objects
// This include will set: $conf, $langs, $user, $mysoc objects
require 'master.inc.php';

// Activate end of page function
register_shutdown_function('dol_shutdown');

// Detection browser
if (isset($_SERVER["HTTP_USER_AGENT"])) {
	$tmp = getBrowserInfo();
	$conf->browser->phone = $tmp['phone'];
	$conf->browser->name = $tmp['browsername'];
	$conf->browser->os = $tmp['browseros'];
	$conf->browser->firefox = $tmp['browserfirefox'];
	$conf->browser->version = $tmp['browserversion'];
}


// Force HTTPS if required ($conf->file->main_force_https is 0/1 or https Speedealing root url)
if (!empty($conf->file->main_force_https)) {
	$newurl = '';
	if ($conf->file->main_force_https == '1') {
		if (!empty($_SERVER["SCRIPT_URI"])) { // If SCRIPT_URI supported by server
			if (preg_match('/^http:/i', $_SERVER["SCRIPT_URI"]) && !preg_match('/^https:/i', $_SERVER["SCRIPT_URI"])) { // If link is http
				$newurl = preg_replace('/^http:/i', 'https:', $_SERVER["SCRIPT_URI"]);
			}
		} else { // Check HTTPS environment variable (Apache/mod_ssl only)
			// $_SERVER["HTTPS"] is 'on' when link is https, otherwise $_SERVER["HTTPS"] is empty or 'off'
			if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != 'on') {  // If link is http
				$newurl = preg_replace('/^http:/i', 'https:', DOL_MAIN_URL_ROOT) . $_SERVER["REQUEST_URI"];
			}
		}
	} else {
		$newurl = $conf->file->main_force_https . $_SERVER["REQUEST_URI"];
	}
	// Start redirect
	if ($newurl) {
		header("Location: " . $newurl);
		exit;
	}
}

// Chargement des includes complementaires de presentation
if (!defined('NOREQUIREMENU'))
	require DOL_DOCUMENT_ROOT . '/core/class/menu.class.php';   // Need 10ko memory (11ko in 2.2)
if (!defined('NOREQUIREHTML'))
	require DOL_DOCUMENT_ROOT . '/core/class/html.form.class.php';  // Need 660ko memory (800ko in 2.2)
if (!defined('NOREQUIREAJAX'))
	require DOL_DOCUMENT_ROOT . '/core/lib/ajax.lib.php'; // Need 22ko memory

// If install or upgrade process not done or not completely finished, we call the install page.
if (!empty($conf->global->MAIN_NOT_INSTALLED) || !empty($conf->global->MAIN_NOT_UPGRADED)) {
	Header("Location: " . DOL_URL_ROOT . "/install/index.php");
	exit;
}

// Creation of a token against CSRF vulnerabilities
if (!defined('NOTOKENRENEWAL')) {
	$token = dol_hash(uniqid(mt_rand(), TRUE)); // Genere un hash d'un nombre aleatoire
	// roulement des jetons car cree a chaque appel
	if (isset($_SESSION['newtoken']))
		$_SESSION['token'] = $_SESSION['newtoken'];
	$_SESSION['newtoken'] = $token;
}

// Check validity of token, only if option enabled (this option breaks some features sometimes)
if (isset($_POST['token']) && isset($_SESSION['token'])) {
	if (($_POST['token'] != $_SESSION['token'])) {
		unset($_POST);
	}
}

// Disable modules (this must be after session_start and after conf has been loaded)
if (GETPOST('disablemodules'))
	$_SESSION["disablemodules"] = GETPOST('disablemodules');
if (!empty($_SESSION["disablemodules"])) {
	$disabled_modules = explode(',', $_SESSION["disablemodules"]);
	foreach ($disabled_modules as $module) {
		if ($module)
			$conf->$module->enabled = false;
	}
}

/*
 * Phase authentication / login
 */
$login = '';
if (!defined('NOLOGIN')) {
	// $authmode lists the different means of identification to be tested in order of preference.
	// Example: 'http', 'dolibarr', 'ldap', 'http,forceuser'
	// Authentication mode
	if (empty($dolibarr_main_authentication))
		$dolibarr_main_authentication = 'http,dolibarr';
	// Authentication mode: forceuser
	if ($dolibarr_main_authentication == 'forceuser' && empty($dolibarr_auto_user))
		$dolibarr_auto_user = 'auto';
	// Set authmode
	$authmode = explode(',', $dolibarr_main_authentication);

	// No authentication mode
	if (!count($authmode) && empty($conf->login_modules)) {
		$langs->load('main');
		dol_print_error('', $langs->trans("ErrorConfigParameterNotDefined", 'dolibarr_main_authentication'));
		exit;
	}

	// If requested by the login has already occurred, it is retrieved from the session
	// Call module if not realized that his request.
	// At the end of this phase, the variable $login is defined.
	$resultFetchUser = '';
	$test = true;
	$user = new User($db);
	//print $user->fetch();exit;
	if (empty($_COOKIE["AuthSession"])) {
		// Check URL for urlrewrite
		if ($conf->urlrewrite && DOL_URL_ROOT != '') {
			header('Location: /index.php');
			exit;
		}

		// It is not already authenticated and it requests the login / password
		include DOL_DOCUMENT_ROOT . '/core/lib/security2.lib.php';

		// We show login page
		if (!is_object($langs)) { // This can occurs when calling page with NOREQUIRETRAN defined
			include DOL_DOCUMENT_ROOT . '/core/class/translatestandalone.class.php'; // Use this class before authentication
			$langs = new TranslateStandalone();
		}
		dol_loginfunction($langs, $conf, $mysoc);
		exit;
	} else {
		// We are already into an authenticated session
		$resultFetchUser = $user->fetch(); // FIXME this fetch increment _rev of _users

		if ($resultFetchUser <= 0) {
			// Account has been removed after login
			session_destroy();
			session_name($sessionname);
			session_start(); // Fixing the bug of register_globals here is useless since session is empty

			$langs->load('main');
			$langs->load('errors');

			$user->trigger_mesg = 'SessionExpire - login=' . $login;
			$_SESSION["dol_loginmesg"] = $langs->trans("Session expired", $login); // TODO Session Expire
			setcookie('AuthSession', '', 1, '/'); // Reset auth cookie

			// Call triggers
			if (!class_exists('Interfaces'))
				include DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
			$interface = new Interfaces($db);
			$result = $interface->run_triggers('USER_LOGIN_FAILED', $user, $user, $langs, $conf, (isset($_POST["entity"]) ? $_POST["entity"] : 0));
			if ($result < 0)
				$error++;
			// End call triggers

			header('Location: ' . DOL_URL_ROOT . '/index.php');
			exit;
		} else {
			if (!empty($conf->global->MAIN_ACTIVATE_UPDATESESSIONTRIGGER)) { // We do not execute such trigger at each page load by default
				// Call triggers
				if (!class_exists('Interfaces'))
					include DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
				$interface = new Interfaces($db);
				$result = $interface->run_triggers('USER_UPDATE_SESSION', $user, $user, $langs, $conf, $conf->entity);
				if ($result < 0)
					$error++;
				// End call triggers
			}
		}
	}

	// Is it a new session that has started ?
	// If we are here, this means authentication was successfull.
	if (!isset($_SESSION["dol_login"])) {
		$error = 0;

		// New session for this login
		$_SESSION["dol_login"] = $user->name;
		$_SESSION["dol_authmode"] = isset($dol_authmode) ? $dol_authmode : '';
		$_SESSION["dol_tz"] = isset($dol_tz) ? $dol_tz : '';
		$_SESSION["dol_tz_string"] = isset($dol_tz_string) ? $dol_tz_string : '';
		$_SESSION["dol_dst"] = isset($dol_dst) ? $dol_dst : '';
		$_SESSION["dol_dst_observed"] = isset($dol_dst_observed) ? $dol_dst_observed : '';
		$_SESSION["dol_dst_first"] = isset($dol_dst_first) ? $dol_dst_first : '';
		$_SESSION["dol_dst_second"] = isset($dol_dst_second) ? $dol_dst_second : '';
		$_SESSION["dol_screenwidth"] = isset($dol_screenwidth) ? $dol_screenwidth : '';
		$_SESSION["dol_screenheight"] = isset($dol_screenheight) ? $dol_screenheight : '';
		$_SESSION["dol_company"] = $conf->global->MAIN_INFO_SOCIETE_NOM;
		$_SESSION["dol_entity"] = $conf->entity;

		// Call triggers
		if (!class_exists('Interfaces'))
			include DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
		$interface = new Interfaces($db);
		$result = $interface->run_triggers('USER_LOGIN', $user, $user, $langs, $conf, $_POST["entity"]);
		if ($result < 0)
			$error++;
		// End call triggers

		if ($error) {
			session_destroy();
			exit;
		}

		//header('Location: ' . DOL_URL_ROOT . '/index.php?idmenu=menu:home'); // TODO Add default database
		//exit;
	}

	/*
	 * Overwrite configs global by personal configs
	 */
	// Set liste_limit
	if (isset($user->conf->MAIN_SIZE_LISTE_LIMIT))	// Can be 0
		$conf->liste_limit = $user->conf->MAIN_SIZE_LISTE_LIMIT;
	if (isset($user->conf->PRODUIT_LIMIT_SIZE))		// Can be 0
		$conf->product->limit_size = $user->conf->PRODUIT_LIMIT_SIZE;

	// Replace conf->css by personalized value
	if (isset($user->conf->MAIN_THEME) && $user->conf->MAIN_THEME) {
		$conf->theme = $user->conf->MAIN_THEME;
		$conf->css = "/theme/" . $conf->theme . "/style.css.php";
	}

	// If theme support option like flip-hide left menu and we use a smartphone, we force it
}

// Init the 4 global objects
// This include will set: $conf, $langs, $user objects
require 'after.inc.php';

if (!defined('NOREQUIRETRAN')) {
	if (!GETPOST('lang')) { // If language was not forced on URL
		// If user has chosen its own language
		if (!empty($user->conf->MAIN_LANG_DEFAULT)) {
			// If different than current language
			//print ">>>".$langs->getDefaultLang()."-".$user->conf->MAIN_LANG_DEFAULT;
			if ($langs->getDefaultLang() != $user->conf->MAIN_LANG_DEFAULT)
				$langs->setDefaultLang($user->conf->MAIN_LANG_DEFAULT);
		}
	}
	else // If language was forced on URL
		$langs->setDefaultLang(GETPOST('lang', 'alpha', 1));
}

// Case forcing style from url
if (GETPOST('theme')) {
	$conf->theme = GETPOST('theme', 'alpha', 1);
	$conf->css = "/theme/" . $conf->theme . "/style.css.php";
}

if (!defined('NOLOGIN')) {
	// If the login is not recovered, it is identified with an account that does not exist.
	// Hacking attempt?

	if (empty($user->name)) {
		accessforbidden();
		exit;
	}

	// Check if user is active
	if ($user->Status != "ENABLE") {
		// If not active, we refuse the user
		$langs->load("other");
		accessforbidden($langs->trans("ErrorLoginDisabled"));
		exit;
	}

	// Load permissions
	$user->getrights();
}

// Load main languages files
if (!defined('NOREQUIRETRAN')) {
	$langs->load("main");
	$langs->load("dict");
}

$heightforframes = 52;

// If URL Rewriting for multicompany
if ($conf->urlrewrite) {
	if (!GETPOST("db")) {
		$tmp_db = $conf->Couchdb->name; // First connecte using $user->entity for default
		$user->useDatabase($tmp_db);

		if (!empty($user->NewConnection))
			$user->set("LastConnection", $user->NewConnection);
		$user->set("NewConnection", dol_now());

		Header("Location: /" . $tmp_db . '/');
		exit;
	}
	else
		$tmp_db = GETPOST("db");

	$_SERVER['PHP_SELF'] = '/' . $conf->Couchdb->name . $_SERVER['PHP_SELF']; // Add Entity in the url
	// Switch to another entity
	/*if (dol_getcache('dol_db') != $tmp_db || strpos(DOL_URL_ROOT, $tmp_db) == 0) {
		dol_flushcache(); // reset cache
		dol_setcache("dol_db", $tmp_db);

		//$user->useDatabase($tmp_db);

		if (!empty($user->NewConnection))
			$user->set("LastConnection", $user->NewConnection);
		$user->set("NewConnection", dol_now());

		//Header("Location: /" . $tmp_db . '/');
		//unset($tmp_db);
		//exit;
	}*/
}


// Functions

if (!function_exists("llxHeader")) {

	/**
	 * Show HTML header HTML + BODY + Top menu + left menu + DIV
	 *
	 * @param 	string 	$head				Optionnal head lines
	 * @param 	string 	$title				HTML title
	 * @param	string	$target				Target to use on links
	 * @param 	int    	$disablejs			More content into html header
	 * @param 	int    	$disablehead		More content into html header
	 * @param 	array  	$arrayofjs			Array of complementary js files
	 * @param 	array  	$arrayofcss			Array of complementary css files
	 * @param	string	$morequerystring	Query string to add to the link "print" to get same parameters (use only if autodetect fails)
	 * @return	void
	 */
	function llxHeader($head = '', $title = '', $target = '', $disablejs = 0, $disablehead = 0, $arrayofjs = '', $arrayofcss = '', $morequerystring = '') {
		global $mysoc, $user, $conf, $langs;

		top_htmlhead($head, $title, $disablejs, $disablehead, $arrayofjs, $arrayofcss); // Show html headers

		if (!defined('NOHEADER'))
			print '<body class="clearfix with-menu with-shortcuts grdnt_c mhover_c">';
		else
			print '<body class="fullW" style="background: white;">';

		// If an upgrade process is required, we call the install page.
		if (empty($conf->global->MAIN_VERSION) || ($conf->global->MAIN_VERSION != DOL_VERSION)) {
			$langs->load("install");
			if (!empty($user->admin) && (empty($conf->global->MAIN_VERSION) || DOL_VERSION > $conf->global->MAIN_VERSION)) {
				include_once DOL_DOCUMENT_ROOT . '/install/upgrade.php';
				upgrade(); // Auto-upgrade
			} else { // Need manual upgrade source code Speedealing
				$log = dol_getcache("warnings");

				$error->title = $langs->trans("NeedUpgrade");

				if (DOL_VERSION < $conf->global->MAIN_VERSION)
					$error->message = $langs->trans("WarningUpgrade", DOL_VERSION, $conf->global->MAIN_VERSION);
				else
					$error->message = $langs->trans("WarningUpgrade", $conf->global->MAIN_VERSION, DOL_VERSION);
				$log[] = clone $error;
				dol_setcache("warnings", $log);
			}
		}

		// Displays title
		if (empty($mysoc->name))
			$appli = 'Speedealing';
		else
			$appli = $mysoc->name;

		print '<header role="banner" id="title-bar">';

		if ($title)
			print '<h2>' . $appli . ' - ' . $title . '</h2>';
		else
			print "<h2>" . $appli . "</h2>";
		print "\n";

		print '</header>';
		?>

		<!-- Button to open/hide menu -->
		<a href="#" id="open-menu"><span><?php echo $langs->trans("Menu"); ?></span> </a>

		<!-- Button to open/hide shortcuts -->
		<a href="#" id="open-shortcuts"><span class="icon-thumbs"></span> </a>

		<?php
		main_area($title);
	}
}

/**
 * Show HTTP header
 *
 *	@param	$type	Header type
 *	@return	void
 */
function top_httphead($type=false) {
	global $conf;

	if ($type == 'json')
		header('Content-type: application/json');
	else
		header("Content-type: text/html; charset=UTF-8");

	// On the fly GZIP compression for all pages (if browser support it). Must set the bit 3 of constant to 1.
	if (isset($conf->global->MAIN_OPTIMIZE_SPEED) && ($conf->global->MAIN_OPTIMIZE_SPEED & 0x04))
		ob_start("ob_gzhandler");
}

/**
 * Ouput html header of a page.
 * This code is also duplicated into security2.lib.php::dol_loginfunction
 *
 * @param 	string 	$head			Optionnal head lines
 * @param 	string 	$title			HTML title
 * @param 	int    	$disablejs		More content into html header
 * @param 	int    	$disablehead	More content into html header
 * @param 	array  	$arrayofjs		Array of complementary js files
 * @param 	array  	$arrayofcss		Array of complementary css files
 * @return	void
 */
function top_htmlhead($head, $title = '', $disablejs = 0, $disablehead = 0, $arrayofjs = '', $arrayofcss = '') {
	global $user, $mysoc, $conf, $langs, $db;

	top_httphead();

	if (empty($conf->css))
		$conf->css = '/theme/eldy/style.css.php'; // If not defined, eldy by default

	// DOCTYPE
	include DOL_DOCUMENT_ROOT . '/core/tpl/preheader.tpl.php';

	if (empty($disablehead)) {

		// Title
		$name = 'Speedealing';
		if (!empty($mysoc->name))
			$name = $mysoc->name;
		$title = $name . (!empty($title)?$title:'');

		// Base href
		$base_href = MAIN_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . DOL_URL_ROOT . '/';

		// Eldy Theme (obsolete)
		// Output style sheets (optioncss='print' or '')
		$themepath = dol_buildpath((empty($conf->global->MAIN_FORCETHEMEDIR) ? '' : $conf->global->MAIN_FORCETHEMEDIR) . $conf->css, 1);
		$themeparam = '?lang=' . $langs->defaultlang . '&amp;theme=' . $conf->theme . (GETPOST('optioncss') ? '&amp;optioncss=' . GETPOST('optioncss', 'alpha', 1) : '');
		if (!empty($_SESSION['dol_resetcache']))
			$themeparam.='&amp;dol_resetcache=' . $_SESSION['dol_resetcache'];
		$theme = $themepath . $themeparam;

		// Header template
		include DOL_DOCUMENT_ROOT . '/core/tpl/header.tpl.php';


		// CSS forced by modules (relative url starting with /)
		/*
		if (is_array($conf->css_modules)) {
			foreach ($conf->css_modules as $key => $cssfile) {
				// cssfile is an absolute path
				print '<link rel="stylesheet" type="text/css" title="default" href="' . dol_buildpath($cssfile, 1);
				// We add params only if page is not static, because some web server setup does not return content type text/css if url has parameters, so browser cache is not used.
				if (!preg_match('/\.css$/i', $cssfile))
					print $themeparam;
				print '"><!-- Added by module ' . $key . '-->' . "\n";
			}
		}
		// CSS forced by page in top_htmlhead call (relative url starting with /)
		if (is_array($arrayofcss)) {
			foreach ($arrayofcss as $cssfile) {
				print '<link rel="stylesheet" type="text/css" title="default" href="' . dol_buildpath($cssfile, 1);
				// We add params only if page is not static, because some web server setup does not return content type text/css if url has parameters and browser cache is not used.
				if (!preg_match('/\.css$/i', $cssfile))
					print $themeparam;
				print '"><!-- Added by page -->' . "\n";
			}
		}*/

		// For new theme TODO script init A revoir
		print '<script>
			$(document).ready(function() {
                prth_stickyFooter.resize();
				//prth_common.init();
			});
		</script>';

		// Output module javascript
		if (is_array($arrayofjs)) {
			print '<!-- Includes JS specific to page -->' . "\n";
			foreach ($arrayofjs as $jsfile) {
				if (preg_match('/^http/i', $jsfile)) {
					print '<script type="text/javascript" src="' . $jsfile . '"></script>' . "\n";
				} else {
					if (!preg_match('/^\//', $jsfile))
						$jsfile = '/' . $jsfile; // For backward compatibility
					print '<script type="text/javascript" src="' . dol_buildpath($jsfile, 1) . '"></script>' . "\n";
				}
			}
		}

		if (!empty($head))
			print $head . "\n";
		if (!empty($conf->global->MAIN_HTML_HEADER))
			print $conf->global->MAIN_HTML_HEADER . "\n";

		print '</head>';
	}

	$conf->headerdone = 1; // To tell header was output
}

/**
 * Show The top menu bar
 *
 * @return	void
 */
function top_menu() {
	global $conf, $langs;
	//global $user, $conf, $langs, $db;
	//global $dolibarr_main_authentication;
	$toprightmenu = '';

	$conf->top_menu = 'auguria_backoffice.php';

	/*
	 * Top menu
	 */
	$top_menu = $conf->top_menu;

	// Load the top menu manager
	// Load the top menu manager (only if not already done)
	if (!class_exists('MenuTop')) {
		$top_menu = 'auguria_backoffice.php';
		include DOL_DOCUMENT_ROOT . '/core/menus/standard/' . $top_menu;
	}
	?>

	<ul id="shortcuts" role="complementary" class="children-tooltip tooltip-right">
		<li class="current">
			<a href="index.php?idmenu=menu:home" title="<?php echo $langs->trans("Dashboard"); ?>">
				<img src="theme/common/modules/Maps.png" />
				<?php echo $langs->trans("Dashboard"); ?>
			</a>
		</li>
		<li>
			<span href="inbox.html" title="<?php echo $langs->trans("Messages"); ?>">
				<img src="theme/common/modules/Mail_alt.png" />
				<?php echo $langs->trans("Messages"); ?>
			</span>
		</li>
		<li>
			<a href="agenda/list.php?idmenu=menu:agendaList" title="<?php echo $langs->trans("Agenda"); ?>">
				<img src="theme/common/modules/Calendar.png" />
				<?php echo $langs->trans("Agenda"); ?>
			</a>
		</li>
		<li>
			<span href="tables.html" title="<?php echo $langs->trans("Contacts"); ?>">
				<img src="theme/common/modules/Contacts.png" />
				<?php echo $langs->trans("Contacts"); ?>
			</span>
		</li>
		<li>
			<span href="explorer.html" title="<?php echo $langs->trans("Medias"); ?>">
				<img src="theme/common/modules/Photos.png" />
				<?php echo $langs->trans("Medias"); ?>
			</span>
		</li>
		<li>
			<span href="sliders.html" title="<?php echo $langs->trans("Stats"); ?>">
				<img src="theme/common/modules/Stocks.png" />
				<?php echo $langs->trans("Stats"); ?>
			</span>
		</li>
		<li>
			<span href="form.html" title="<?php echo $langs->trans("Settings"); ?>">
				<img src="theme/common/modules/Settings.png" />
				<?php echo $langs->trans("Settings"); ?>
			<span>
		</li>
		<li>
			<span title="<?php echo $langs->trans("Notes"); ?>">
				<img src="theme/common/modules/Notes.png" />
				<?php echo $langs->trans("Notes"); ?>
			</span>
		</li>
	</ul>

	<?php
}

/**
 * Show left menu bar
 *
 * @return	void
 */
function left_menu() {
	global $user, $conf, $langs, $db;
	global $hookmanager, $count_icon;

	$searchform = '';
	$bookmarks = '';

	// Instantiate hooks of thirdparty module
	$hookmanager->initHooks(array('searchform', 'leftblock'));

	print "\n";
	?>

	<!-- Sidebar/drop-down menu -->
	<section id="menu" role="complementary">

	<!-- This wrapper is used by several responsive layouts -->
	<div id="menu-content">
		<header>
			<form action="search.php" id="search_box" method="post">
				<input name="query" id="query" type="text" size="40" placeholder="<?php echo $langs->trans("SearchOf"); ?>..." autocomplete="off" />
			</form>
		</header>
		<script>
		$(document).ready(function() {
			$('#query').sautocomplete('search/data.php', {
				delay: 10,
				minChars: 2,
				max: 6,
				matchCase: 1,
				width: 212
			}).result(function(event, query_val) {
				$.fancybox({
					href: 'search/search_result.php',
					ajax: {
						type: "POST",
						data: "search_item=" + query_val
					},
					'overlayOpacity': '0.2',
					'transitionIn': 'elastic',
					'transitionOut': 'fade',
					onComplete: function() {
						$('#query').blur();
					}
				});
			});
			$('#search_box').submit(function() {
				var query_val = $("#query").val();
				$.fancybox({
					href: 'search/search_result.php',
					ajax: {
						type: "POST",
						data: "search_item=" + query_val
					},
					'overlayOpacity': '0.2',
					'transitionIn': 'elastic',
					'transitionOut': 'fade'
				});
				return false;
			});
		});
		</script>

		<div id="profile" class="with-mid-padding">
			<div class="columns">
				<div class="five-columns">
					<div class="ego-icon big">
					<?php if (!empty($user->Photo)) : ?>
						<img alt="User name" class="ego-icon-inner" src="<?php echo $user->getFile($user->Photo); ?>">
					<?php else : ?>
						<img src="theme/symeos/img/user.png" alt="User name" class="ego-icon-inner">
					<?php endif; ?>
						<img class="ego-icon-outer" src="theme/symeos/img/timbrebase90x100.png">
					</div>
				</div>
				<div class="seven-columns">
					<?php echo $langs->trans('Hello'); ?>
					<span class="name">
						<?php echo $user->Firstname; ?> <b><?php echo $user->Lastname; ?></b>
					</span>
				</div>
			</div>
		</div>

		<!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->
		<ul id="access" class="children-tooltip">
			<li style="width: 20%;">
				<a href="index.php?idmenu=menu:home" title="<?php echo $langs->trans("Home"); ?>">
					<span class="icon-home"></span>
				</a>
			</li>
			<li style="width: 20%;">
				<span href="inbox.html" title="Messages">
					<span class="icon-inbox"></span>
				</span>
			</li>
			<li style="width: 20%;">
				<a href="agenda/list.php?idmenu=menu:myagendaListTODO" title="<?php echo $langs->trans("Agenda"); ?>">
					<span class="icon-calendar"></span>
					<?php
					require_once(DOL_DOCUMENT_ROOT . "/agenda/class/agenda.class.php");
					$agenda = new Agenda($db);
					$result = $agenda->getView("countTODO", array("group" => true, "key" => $user->id), true);
					//print_r($user->id);
					if ($result->rows[0]->value) {
						print '<span class="count">' . $result->rows[0]->value . '</span>';
						$count_icon+=$result->rows[0]->value;
					}
					?>
				</a>
			</li>
			<li style="width: 20%;">
				<a href="user/fiche.php?id=<?php echo $user->id; ?>" title="Profile">
					<span class="icon-gear"></span>
				</a>
			</li>
			<li style="width: 20%;">
				<a href="user/logout.php" title="Log out">
					<span class="icon-unlock"></span>
				</a>
			</li>
		</ul>

		<?php
		// Show menu
		$menu = new MenuAuguria($db);
		$menu->atarget = $target;
		$menu->showmenuTop();

		$agenda = new Agenda($db);
		$params = array(
						'startkey' => array($user->id, mktime(0, 0, 0, date("m"), date("d"), date("Y"))),
						'endkey' => array($user->id, mktime(23, 59, 59, date("m"), date("d"), date("Y")))
		);
		$result = $agenda->getView("listMyTasks", $params);
		if (count($result->rows)) :
		?>
		<ul class="unstyled-list">
			<li class="title-menu">Today's event</li>
			<li>
				<ul class="calendar-menu">
				<?php
				foreach ($result->rows as $aRow) {
					print '<li><a href="agenda/fiche.php?id=' . $aRow->value->_id . '" title="' . $aRow->value->societe->name . '"> <time datetime="' . dol_print_date($aRow->value->datep, "day") . '">';
					print '<b>' . date("d", $aRow->value->datep) . '</b> ' . date("M", $aRow->value->datep);
					print '</time> <small class="green">' . dol_print_date($aRow->value->datep, "hour") . '</small> ' . $aRow->value->label;
					print '</a></li>';
				}
				?>
				</ul>
			</li>
		</ul>
		<?php endif; ?>
	</div>
	<!-- End content wrapper -->

	<!-- This is optional -->
	<footer id="menu-footer">
		<div>
			<p>Copyright 2012-2013 - Symeos</p>
		</div>
	</footer>

	</section>
	<!-- End sidebar/drop-down menu -->
	<?php
        // Left column
        print '<!--Begin left area - menu ' . $left_menu . '-->' . "\n";
}

/**
 * Begin main area
 *
 * @param	string	$title		Title
 * @return	void
 */
function main_area($title = '') {
	global $conf, $langs;

	print '<!-- Main content -->';
	print '<section role="main" id="main">';
	print '<noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won\'t work as expected...</noscript>';

	if (!empty($conf->global->MAIN_ONLY_LOGIN_ALLOWED))
		print info_admin($langs->trans("WarningYouAreInMaintenanceMode", $conf->global->MAIN_ONLY_LOGIN_ALLOWED));
}

if (!function_exists("llxFooter")) {

	/**
	 * Show HTML footer
	 * Close div /DIV data-role=page + /DIV class=fiche + /DIV /DIV main layout + /BODY + /HTML.
	 *
	 * @return	void
	 */
	function llxFooter() {
		global $conf, $langs, $dolibarr_auto_user, $micro_start_time, $memcache, $count_icon;

		// Global html output events ($mesgs, $errors, $warnings)
		dol_htmloutput_events();
		?>
		</section>

		<!-- End main content -->
		<?php
		top_menu(); // print the left menu
		left_menu(); // print the right menu

		if ($conf->memcached->enabled && get_class($memcache) == 'Memcache')
			$memcache->close();

		// Core error message
		if (defined("MAIN_CORE_ERROR") && constant("MAIN_CORE_ERROR") == 1) {
			$title = img_warning() . ' ' . $langs->trans('CoreErrorTitle');
			print ajax_dialog($title, $langs->trans('CoreErrorMessage'));

			define("MAIN_CORE_ERROR", 0);
		}

		if (!defined('NOHEADER')) {

			// Footer template
			include DOL_DOCUMENT_ROOT . '/core/tpl/footer.tpl.php';

            printCommonFooter();
		}

        print "</body>\n";
        print "</html>\n";
    }
}
?>
