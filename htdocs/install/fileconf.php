<?php
/* Copyright (C) 2004		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2004		Eric Seigne				<eric.seigne@ryxeo.com>
 * Copyright (C) 2004-2011	Laurent Destailleur		<eldy@users.sourceforge.net>
 * Copyright (C) 2004		Benoit Mortier			<benoit.mortier@opensides.be>
 * Copyright (C) 2004		Sebastien DiCintio		<sdicintio@ressource-toi.org>
 * Copyright (C) 2005-2011	Regis Houssin			<regis@dolibarr.fr>
 * Copyright (C) 2012		Herve Prot				<herve.prot@symeos.com>
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

include_once("./inc.php");


$err = 0;

$setuplang = isset($_POST["selectlang"]) ? $_POST["selectlang"] : (isset($_GET["selectlang"]) ? $_GET["selectlang"] : (isset($_GET["lang"]) ? $_GET["lang"] : 'auto'));
$langs->setDefaultLang($setuplang);

$langs->load("install");
$langs->load("errors");

// You can force preselected values of the config step of Dolibarr by adding a file
// install.forced.php into directory htdocs/install (This is the case with some wizard
// installer like DoliWamp, DoliMamp or DoliBuntu).
// We first init "forced values" to nothing.
if (!isset($force_install_noedit))
	$force_install_noedit = '';
if (!isset($force_install_type))
	$force_install_type = '';
if (!isset($force_install_dbserver))
	$force_install_dbserver = '';
if (!isset($force_install_port))
	$force_install_port = '';
if (!isset($force_install_database))
	$force_install_database = '';
if (!isset($force_install_prefix))
	$force_install_prefix = '';
if (!isset($force_install_createdatabase))
	$force_install_createdatabase = '';
if (!isset($force_install_databaselogin))
	$force_install_databaselogin = '';
if (!isset($force_install_databasepass))
	$force_install_databasepass = '';
if (!isset($force_install_databaserootlogin))
	$force_install_databaserootlogin = '';
if (!isset($force_install_databaserootpass))
	$force_install_databaserootpass = '';
// Now we load forced value from install.forced.php file.
$useforcedwizard = false;
$forcedfile = "./install.forced.php";
if ($conffile == "/etc/dolibarr/conf.php")
	$forcedfile = "/etc/dolibarr/install.forced.php";
if (@file_exists($forcedfile)) {
	$useforcedwizard = true;
	include_once($forcedfile);
}

dolibarr_install_syslog("Fileconf: Entering fileconf.php page");



/*
 * 	View
 */

pHeader($langs->trans("ConfigurationFile"), "etape1");

// Test if we can run a first install process
if (!is_writable($conffile)) {
	print $langs->trans("ConfFileIsNotWritable", $conffiletoshow);
	pFooter(1, $setuplang, 'jscheckparam');
	exit;
}

if (!empty($force_install_message)) {
	print '<b>' . $langs->trans($force_install_message) . '</b><br>';
}
?>
<fieldset title="<?php echo $langs->trans("WebServer"); ?>">
	<legend>Lorem ipsum dolor&hellip;</legend>
	<div class="row sepH_b">
		<div class="row sepH_b">
			<div class="twelve columns">
				<h3 class="inner_heading"><?php echo $langs->trans("WebServer"); ?></h3>
			</div>
		</div>
		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("WebPagesDirectory"); ?></label>

						<!-- Documents root $dolibarr_main_document_root -->
						<?php
						if (!isset($dolibarr_main_url_root) || dol_strlen($dolibarr_main_url_root) == 0) {
							//print "x".$_SERVER["SCRIPT_FILENAME"]." y".$_SERVER["DOCUMENT_ROOT"];
							// Si le php fonctionne en CGI, alors SCRIPT_FILENAME vaut le path du php et
							// ce n'est pas ce qu'on veut. Dans ce cas, on propose $_SERVER["DOCUMENT_ROOT"]
							if (preg_match('/^php$/i', $_SERVER["SCRIPT_FILENAME"]) || preg_match('/[\\/]php$/i', $_SERVER["SCRIPT_FILENAME"]) || preg_match('/php\.exe$/i', $_SERVER["SCRIPT_FILENAME"])) {
								$dolibarr_main_document_root = $_SERVER["DOCUMENT_ROOT"];

								if (!preg_match('/[\\/]dolibarr[\\/]htdocs$/i', $dolibarr_main_document_root)) {
									$dolibarr_main_document_root.="/dolibarr/htdocs";
								}
							} else {
								$dolibarr_main_document_root = substr($_SERVER["SCRIPT_FILENAME"], 0, dol_strlen($_SERVER["SCRIPT_FILENAME"]) - 21);
								// Nettoyage du path propose
								// Gere les chemins windows avec double "\"
								$dolibarr_main_document_root = str_replace('\\\\', '/', $dolibarr_main_document_root);

								// Supprime les slash ou antislash de fins
								$dolibarr_main_document_root = preg_replace('/[\\/]+$/', '', $dolibarr_main_document_root);
							}
						}

						if ($force_install_noedit)
							print '<input type="hidden" value="' . $dolibarr_main_document_root . '" name="main_dir">';
						print '<input type="text" class="input-text large" size="60" value="' . $dolibarr_main_document_root . '"' . (empty($force_install_noedit) ? '' : ' disabled="disabled"') . ' name="main_dir' . (empty($force_install_noedit) ? '' : '_bis') . '">';
						?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">

					<h5><?php echo $langs->trans("WithNoSlashAtTheEnd"); ?></h5>
					<p><?php echo $langs->trans("Examples"); ?></p>
					<pre>/var/www/speedealing/htdocs
C:/wwwroot/speedealing/htdocs</pre>
				</div>
			</div>
		</div>
		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php print $langs->trans("DocumentsDirectory"); ?></label>

						<!-- Documents URL $dolibarr_main_data_root -->
						<?php
						if (empty($dolibarr_main_data_root)) {
							// Si le repertoire documents non defini, on en propose un par defaut
							if (empty($force_install_main_data_root)) {
								$dolibarr_main_data_root = preg_replace("/\/htdocs$/", "", $dolibarr_main_document_root);
								$dolibarr_main_data_root.="/documents";
							} else {
								$dolibarr_main_data_root = $force_install_main_data_root;
							}
						}
						if ($force_install_noedit)
							print '<input type="hidden" value="' . $dolibarr_main_data_root . '" name="main_data_dir">';
						print '<input type="text" class="input-text large" size="60" value="' . $dolibarr_main_data_root . '"' . (empty($force_install_noedit) ? '' : ' disabled="disabled"') . ' name="main_data_dir' . (empty($force_install_noedit) ? '' : '_bis') . '">';
						?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">

					<h5><?php echo $langs->trans("WithNoSlashAtTheEnd"); ?></h5>
					<h5><?php echo $langs->trans("DirectoryRecommendation"); ?></h5>
					<p><?php echo $langs->trans("Examples"); ?></p>
					<pre>/var/lib/speedealing/documents
C:/My Documents/speedealing/</pre>
				</div>
			</div>
		</div>

		<!-- Root URL $dolibarr_main_url_root -->
		<?php
		if (!empty($main_url))
			$dolibarr_main_url_root = $main_url;
		if (empty($dolibarr_main_url_root)) {
			// If defined (Ie: Apache with Linux)
			if (isset($_SERVER["SCRIPT_URI"])) {
				$dolibarr_main_url_root = $_SERVER["SCRIPT_URI"];
			}
			// If defined (Ie: Apache with Caudium)
			elseif (isset($_SERVER["SERVER_URL"]) && isset($_SERVER["DOCUMENT_URI"])) {
				$dolibarr_main_url_root = $_SERVER["SERVER_URL"] . $_SERVER["DOCUMENT_URI"];
			}
			// If SCRIPT_URI, SERVER_URL, DOCUMENT_URI not defined (Ie: Apache 2.0.44 for Windows)
			else {
				$proto = 'http';
				if (!empty($_SERVER["HTTP_HOST"]))
					$serverport = $_SERVER["HTTP_HOST"];
				else
					$serverport = $_SERVER["SERVER_NAME"];
				$dolibarr_main_url_root = $proto . "://" . $serverport . $_SERVER["SCRIPT_NAME"];
			}
			// Clean proposed URL
			$dolibarr_main_url_root = preg_replace('/\/fileconf\.php$/', '', $dolibarr_main_url_root); // Remove the /fileconf.php
			$dolibarr_main_url_root = preg_replace('/\/$/', '', $dolibarr_main_url_root);  // Remove the /
			$dolibarr_main_url_root = preg_replace('/\/index\.php$/', '', $dolibarr_main_url_root);  // Remove the /index.php
			$dolibarr_main_url_root = preg_replace('/\/install$/', '', $dolibarr_main_url_root);   // Remove the /install
		}
		?>
		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("URLRoot"); ?></label>
						<?php
						if ($force_install_noedit)
							print '<input type="hidden" value="' . $dolibarr_main_url_root . '" name="main_url">';
						print '<input type="text" class="input-text large" size="60" value="' . $dolibarr_main_url_root . '"' . (empty($force_install_noedit) ? '' : ' disabled="disabled"') . ' name="main_url' . (empty($force_install_noedit) ? '' : '_bis') . '">';
						?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<p><?php echo $langs->trans("Examples"); ?></p>
					<pre>http://localhost/
http://www.myserver.com:8180/speedealing</pre>
				</div>
			</div>
		</div>
		<?php
		if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') {   // Enabled if the installation process is "https://"
			?>
			<div class="row box_s_reg sepH_a">
				<div class="seven columns">
					<div class="form_content">
						<div class="formRow elVal">
							<label><?php echo $langs->trans("ForceHttps"); ?></label>
							<input type="checkbox" class="input-text large"
								   name="main_force_https"
								   <?php if (!empty($force_install_mainforcehttps)) print ' checked="on"'; ?>>
						</div>
					</div>
				</div>
				<div class="five columns">
					<div class="form_legend">
						<h5><?php echo $langs->trans("CheckToForceHttps"); ?></h5>
					</div>
				</div>
			</div>	
			<?php
		}
		?>
	</div>
</fieldset>

<!-- NoSQL Database -->
<fieldset title="<?php echo $langs->trans("DatabaseNoSQL"); ?>">
	<legend>Lorem ipsum dolor&hellip;</legend>
	<div class="row sepH_b">
		<div class="row sepH_b">
			<div class="twelve columns">
				<h3 class="inner_heading"><?php echo $langs->trans("DatabaseNoSQL"); ?></h3>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("DatabaseName"); ?></label>
						<input type="text" class="input-text small" 
							   id="couchdb_name"
							   name="couchdb_name"
							   value="<?php echo (!empty($dolibarr_main_couchdb_host)) ? $dolibarr_main_couchdb_host : ($force_install_database ? $force_install_database : 'speedealing'); ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("CreateDatabase"); ?></label>
						<input type="checkbox" id="couchdb_create_database" name="couchdb_create_database"
							   <?php if ($force_install_createdatabase) print ' checked="on"'; ?>>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("DatabaseName"); ?></h5>
					<h5><?php echo $langs->trans("CheckToCreateCouchdbDatabase"); ?></h5>
				</div>
			</div>
		</div>

		<?php
		if (!isset($dolibarr_main_couchdb_host)) {
			$dolibarr_main_couchdb_host = "http://localhost.localdomain";
		}
		?>
		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Server"); ?></label>
						<input type="text" class="input-text medium"
							   name="couchdb_host<?php print ($force_install_noedit == 2 && $force_install_dbserver) ? '_bis' : ''; ?>"
							   <?php if ($force_install_noedit == 2 && $force_install_dbserver) print ' disabled="disabled"'; ?>
							   value="<?php print (!empty($dolibarr_main_couchdb_host)) ? $dolibarr_main_couchdb_host : (empty($force_install_dbserver) ? 'http://localhost.localdomain' : $force_install_dbserver); ?>">
							   <?php if ($force_install_noedit == 2 && $force_install_dbserver) print '<input type="hidden" name="couchdb_host" value="' . ((!empty($dolibarr_main_couchdb_host)) ? $dolibarr_main_couchdb_host : $force_install_dbserver) . '">'; ?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("ServerAddressCouchdbDescription"); ?></h5>
					<p><?php echo $langs->trans("Examples"); ?></p>
					<pre>http://localhost.localdomain
https://couchdb.speedealing.com</pre>
				</div>
			</div>
		</div>


		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Port"); ?></label>
						<input type="text" class="input-text small"
							   name="couchdb_port<?php print ($force_install_noedit == 2 && $force_install_port) ? '_bis' : ''; ?>"
							   <?php if ($force_install_noedit == 2 && $force_install_port) print ' disabled="disabled"'; ?>
							   value="<?php print (!empty($dolibarr_main_couchdb_port)) ? $dolibarr_main_couchdb_port : (empty($force_install_port) ? '5984' : $force_install_port); ?>">
							   <?php if ($force_install_noedit == 2 && $force_install_port) print '<input type="hidden" name="couchdb_port" value="' . ((!empty($dolibarr_main_couchdb_port)) ? $dolibarr_main_couchdb_port : $force_install_port) . '">'; ?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("ServerPortCouchdbDescription"); ?></h5>
				</div>
			</div>
		</div>

		<div class="row sepH_b">
			<div class="twelve columns">
				<h3 class="inner_heading"><?php echo $langs->trans("DatabaseSuperUserAccess"); ?></h3>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Login"); ?></label>
						<input type="text" id="couchdb_user_root" class="input-text medium"
							   name="couchdb_user_root"
							   value="<?php print (!empty($db_user_root)) ? $db_user_root : $force_install_databaserootlogin; ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Password"); ?></label>
						<input type="password" class="input-text medium"
							   id="couchdb_pass_root" name="couchdb_pass_root"
							   value="<?php print (!empty($db_pass_root)) ? $db_pass_root : $force_install_databaserootpass; ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("CreateAdminUser"); ?></label>
						<input type="checkbox" id="couchdb_create_admin" name="couchdb_create_admin" <?php if (!empty($force_install_createdatabase)) print ' checked="on"'; ?>>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("DatabaseCouchdbUserDescription"); ?></h5>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<!-- SQL database -->

<fieldset title="<?php echo $langs->trans("DatabaseSQL"); ?>">
	<legend>Lorem ipsum dolor&hellip;</legend>
	<div class="row sepH_b">
		<div class="row sepH_b">
			<div class="twelve columns">
				<h3 class="inner_heading"><?php echo $langs->trans("DatabaseSQL"); ?></h3>
			</div>
		</div>
		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("DatabaseName"); ?></label>
						<input type="text" class="input-text small" id="db_name"
							   name="db_name"
							   value="<?php echo (!empty($dolibarr_main_db_name)) ? $dolibarr_main_db_name : ($force_install_database ? $force_install_database : 'speedealing'); ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("CreateDatabase"); ?></label>
						<input type="checkbox" id="db_create_database" name="db_create_database"
							   <?php if ($force_install_createdatabase) print ' checked="on"'; ?>>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("DatabaseName"); ?></h5>
					<h5><?php echo $langs->trans("CheckToCreateDatabase"); ?></h5>
				</div>
			</div>
		</div>

		<?php
		if (!isset($dolibarr_main_db_host)) {
			$dolibarr_main_db_host = "localhost";
		}
		?>
		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("DriverType"); ?></label>
						<?php
						$defaultype = !empty($dolibarr_main_db_type) ? $dolibarr_main_db_type : ($force_install_type ? $force_install_type : 'mysqli');

						$modules = array();
						$nbok = $nbko = 0;
						$option = '';

// Scan les drivers
						$dir = DOL_DOCUMENT_ROOT . '/core/db';
						$handle = opendir($dir);
						if (is_resource($handle)) {
							while (($file = readdir($handle)) !== false) {
								if (is_readable($dir . "/" . $file) && preg_match('/^(.*)\.class\.php$/i', $file, $reg)) {
									$type = $reg[1];
									$class = 'DoliDB' . ucfirst($type);
									include_once($dir . "/" . $file);

									if ($type == 'sqlite')
										continue; // We hide sqlite because support can't be complete unti sqlit does not manage foreign key creation after table creation
// Version min of database
									$versionbasemin = getStaticMember($class, 'versionmin');
									$note = '(' . getStaticMember($class, 'label') . ' >= ' . versiontostring($versionbasemin) . ')';

									// Switch to mysql if mysqli is not present
									if ($defaultype == 'mysqli' && !function_exists('mysqli_connect'))
										$defaultype = 'mysql';

									// Show line into list
									if ($type == 'mysql') {
										$testfunction = 'mysql_connect';
										$testclass = '';
									}
									if ($type == 'mysqli') {
										$testfunction = 'mysqli_connect';
										$testclass = '';
									}
									if ($type == 'pgsql') {
										$testfunction = 'pg_connect';
										$testclass = '';
									}
									if ($type == 'mssql') {
										$testfunction = 'mssql_connect';
										$testclass = '';
									}
									if ($type == 'sqlite') {
										$testfunction = '';
										$testclass = 'PDO';
									}
									$option.='<option value="' . $type . '"' . ($defaultype == $type ? ' selected="selected"' : '');
									if ($testfunction && !function_exists($testfunction))
										$option.=' disabled="disabled"';
									if ($testclass && !class_exists($testclass))
										$option.=' disabled="disabled"';
									$option.='>';
									$option.=$type . '&nbsp; &nbsp;';
									if ($note)
										$option.=' ' . $note;
									// Experimental
									if ($type == 'mssql')
										$option.=' ' . $langs->trans("Experimental");
									elseif ($type == 'sqlite')
										$option.=' ' . $langs->trans("Experimental");
									// No available
									elseif (!function_exists($testfunction))
										$option.=' - ' . $langs->trans("FunctionNotAvailableInThisPHP");
									$option.='</option>';
								}
							}
						}

						if ($force_install_noedit && $force_install_type)
							print '<input id="db_type" type="hidden" value="' . $force_install_type . '" name="db_type">';
						print '<select id="db_type" name="db_type' . (empty($force_install_noedit) || empty($force_install_type) ? '' : '_bis') . '"' . ($force_install_noedit && $force_install_type ? ' disabled="disabled"' : '') . '>';
						print $option;
						print '</select>';
						?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("DatabaseType"); ?></h5>
				</div>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Server"); ?></label>
						<input type="text" class="input-text medium"
							   name="db_host<?php print ($force_install_noedit == 2 && $force_install_dbserver) ? '_bis' : ''; ?>"
							   <?php if ($force_install_noedit == 2 && $force_install_dbserver) print ' disabled="disabled"'; ?>
							   value="<?php print (!empty($dolibarr_main_db_host)) ? $dolibarr_main_db_host : (empty($force_install_dbserver) ? 'localhost' : $force_install_dbserver); ?>">
							   <?php if ($force_install_noedit == 2 && $force_install_dbserver) print '<input type="hidden" name="db_host" value="' . ((!empty($dolibarr_main_db_host)) ? $dolibarr_main_db_host : $force_install_dbserver) . '">'; ?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("ServerAddressDescription"); ?></h5>
				</div>
			</div>
		</div>


		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Port"); ?></label>
						<input type="text" class="input-text small"
							   name="db_port<?php print ($force_install_noedit == 2 && $force_install_port) ? '_bis' : ''; ?>"
							   <?php if ($force_install_noedit == 2 && $force_install_port) print ' disabled="disabled"'; ?>
							   value="<?php print (!empty($dolibarr_main_db_port)) ? $dolibarr_main_db_port : $force_install_port; ?>">
							   <?php if ($force_install_noedit == 2 && $force_install_port) print '<input type="hidden" name="db_port" value="' . ((!empty($dolibarr_main_db_port)) ? $dolibarr_main_db_port : $force_install_port) . '">'; ?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("ServerPortDescription"); ?></h5>
				</div>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("DatabasePrefix"); ?></label>
						<input type="text" id="db_prefix" class="input-text small"
							   name="db_prefix"
							   value="<?php echo (!empty($dolibarr_main_db_prefix)) ? $dolibarr_main_db_prefix : ($force_install_prefix ? $force_install_prefix : 'llx_'); ?>">
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("DatabasePrefix"); ?></h5>
				</div>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Login"); ?></label>
						<input type="text" id="db_user" class="input-text medium"
							   name="db_user"
							   value="<?php print (!empty($dolibarr_main_db_user)) ? $dolibarr_main_db_user : $force_install_databaselogin; ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Password"); ?></label>
						<input type="password" id="db_pass" class="input-text medium"
							   name="db_pass"
							   value="<?php print (!empty($dolibarr_main_db_pass)) ? $dolibarr_main_db_pass : $force_install_databasepass; ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("CreateUser"); ?></label>
						<input type="checkbox"
							   id="db_create_user" name="db_create_user"
							   <?php if (!empty($force_install_createuser)) print ' checked="on"'; ?>>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("AdminLogin"); ?></h5>
					<h5><?php echo $langs->trans("AdminPassword"); ?></h5>
					</br>
					<h5><?php echo $langs->trans("CheckToCreateUser"); ?></h5>
				</div>
			</div>
		</div>

		<div class="row sepH_b">
			<div class="twelve columns">
				<h3 class="inner_heading"><?php echo $langs->trans("DatabaseSuperUserAccess"); ?></h3>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Login"); ?></label>
						<input type="text" id="db_user_root" class="input-text medium"
							   name="db_user_root"
							   value="<?php print (!empty($db_user_root)) ? $db_user_root : $force_install_databaserootlogin; ?>">
					</div>
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Password"); ?></label>
						<input type="password" class="input-text medium"
							   id="db_pass_root" name="db_pass_root"
							   value="<?php print (!empty($db_pass_root)) ? $db_pass_root : $force_install_databaserootpass; ?>">
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("DatabaseRootLoginDescription"); ?></h5>
					<p><?php echo $langs->trans("Examples"); ?></p>
					<pre>root (Mysql)
postgres (PostgreSql)</pre>
					<h5><?php echo $langs->trans("KeepEmptyIfNoPassword"); ?></h5>
				</div>
			</div>
		</div>

	</div>
</fieldset>

<!-- Memcached -->
<!-- Max 3 steps !!!! bug in 4 steps -->
<!--<fieldset title="<?php echo $langs->trans("Memcached"); ?>">
	<legend>Lorem ipsum dolor&hellip;</legend>
	<div class="row sepH_b">
		<div class="row sepH_b">
			<div class="twelve columns">
				<h3 class="inner_heading"><?php echo $langs->trans("Memcached"); ?></h3>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="formRow elVal">
					<label><?php echo $langs->trans("EnabledMemcached"); ?></label>
					<input type="checkbox"
						   id="db_enable_memcached" name="db_enable_memcached">
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("MemcachedDescription"); ?></h5>
				</div>
			</div>
		</div>

		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Server"); ?></label>
						<input type="text" class="input-text medium"
							   name="memcached_host<?php print ($force_install_noedit == 2 && $force_install_dbserver) ? '_bis' : ''; ?>"
							   <?php if ($force_install_noedit == 2 && $force_install_dbserver) print ' disabled="disabled"'; ?>
							   value="<?php print (!empty($dolibarr_main_memcached_host)) ? $dolibarr_main_memcached_host : (empty($force_install_dbserver) ? 'localhost' : $force_install_dbserver); ?>">
							   <?php if ($force_install_noedit == 2 && $force_install_dbserver) print '<input type="hidden" name="memcached_host" value="' . ((!empty($dolibarr_main_memcached_host)) ? $dolibarr_main_memcached_host : $force_install_dbserver) . '">'; ?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("ServerAddressMemcachedDesc"); ?></h5>
				</div>
			</div>
		</div>


		<div class="row box_s_reg sepH_a">
			<div class="seven columns">
				<div class="form_content">
					<div class="formRow elVal">
						<label><?php echo $langs->trans("Port"); ?></label>
						<input type="text" class="input-text small"
							   name="memcached_port<?php print ($force_install_noedit == 2 && $force_install_port) ? '_bis' : ''; ?>"
							   <?php if ($force_install_noedit == 2 && $force_install_port) print ' disabled="disabled"'; ?>
							   value="<?php print (!empty($dolibarr_main_memcached_port)) ? $dolibarr_main_memcached_port : (empty($force_install_port) ? '11211' : $force_install_port); ?>">
							   <?php if ($force_install_noedit == 2 && $force_install_port) print '<input type="hidden" name="memcached_port" value="' . ((!empty($dolibarr_main_memcached_port)) ? $dolibarr_main_memcached_port : $force_install_port) . '">'; ?>
					</div>
				</div>
			</div>
			<div class="five columns">
				<div class="form_legend">
					<h5><?php echo $langs->trans("ServerPortMemcachedDesc"); ?></h5>
				</div>
			</div>
		</div>
	</div>
</fieldset>-->

<button type="submit" class="finish gh_button icon approve primary"><?php echo $langs->trans("Save"); ?></button>

<script type="text/javascript">
	$(document).ready(function() {
		//* step by step wizard
		//prth_wizard.simple();
		prth_wizard.validation();
		prth_wizard.steps_nb();
		// extended select elements
		//prth_chosen_select.init();
	});
	//* wizard
	prth_wizard = {
		simple: function(){
			$('#simple_wizard').stepy({
				titleClick	: true
			});
		},
		validation: function(){
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
					'db_user'		: {
						required	: true,
						minlength	: 3
					},
					'main_dir'		: 'required',
					'main_data_dir'	: 'required',
					'main_url'		: 'required',
					'db_host'		: 'required',
					'db_name'		: 'required',
					'db_pass'		: 'required',
					'couchdb_user_root' : 'required',
					'couchdb_pass_root' : 'required'
				}, messages: {
					'db_user'		: { required:  '<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("Login"))); ?>' },
					'couchdb_user_root'		: { required:  '<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("Login"))); ?>' },
					'main_dir'		: { required:  '<?php echo $langs->trans('ErrorFieldRequired'); ?>' },
					'db_pass'		: { required:  '<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("Password"))); ?>' },
					'couchdb_pass_root'		: { required:  '<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("Password"))); ?>' },
					'main_data_dir'	: { required:  '<?php echo $langs->trans('ErrorFieldRequired'); ?>' },
					'db_host'		: { required:  '<?php echo $langs->trans('ErrorFieldRequired'); ?>' }
				},
				ignore				: ':hidden'
			});
		},
		//* add numbers to step titles
		steps_nb: function(){
			$('.stepy-titles').each(function(){
				$(this).children('li').each(function(index){
					var myIndex = index + 1
					$(this).append('<span class="stepNb">'+myIndex+'</span>');
				})
			})
		}
	};
	
</script>

<!--
<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery("#db_type").change(function() {
			if (jQuery("#db_type").val()=='sqlite') { jQuery(".hidesqlite").hide(); }
			else  { jQuery(".hidesqlite").show(); }
		});

		function init_needroot()
		{
			/*alert(jQuery("#db_create_database").attr("checked")); */
			if (jQuery("#db_create_database").attr("checked") || jQuery("#db_create_user").attr("checked") || jQuery("#couchdb_create_admin").attr("checked"))
			{
				jQuery(".needroot").removeAttr('disabled');
			}
			else
			{
				jQuery(".needroot").attr('disabled','disabled');
			}
		}

		init_needroot();
		jQuery("#db_create_database").click(function() {
			init_needroot();
		});
		jQuery("#db_create_user").click(function() {
			init_needroot();
		});
		jQuery("#couchdb_create_admin").click(function() {
			init_needroot();
		});
<?php if ($force_install_noedit) { ?>
					jQuery("#db_pass").focus();
<?php } ?>
	});

	function checkDatabaseName(databasename) {
		if (databasename.match(/[;\.]/)) { return false; }
		return true;
	}

	function jscheckparam()
	{
		ok=true;

		if (document.forminstall.main_dir.value == '')
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("WebPagesDirectory"))); ?>');
		}
		else if (document.forminstall.main_data_dir.value == '')
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("DocumentsDirectory"))); ?>');
		}
		else if (document.forminstall.main_url.value == '')
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("URLRoot"))); ?>');
		}
		else if (document.forminstall.db_host.value == '')
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("Server"))); ?>');
		}
		else if (document.forminstall.db_name.value == '')
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("ErrorFieldRequired", $langs->transnoentitiesnoconv("DatabaseName"))); ?>');
		}
		else if (! checkDatabaseName(document.forminstall.db_name.value))
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("ErrorSpecialCharNotAllowedForField", $langs->transnoentitiesnoconv("DatabaseName"))); ?>');
		}
		// If create database asked
		else if (document.forminstall.db_create_database.checked == true && (document.forminstall.db_user_root.value == ''))
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("YouAskToCreateDatabaseSoRootRequired")); ?>');
		}
		else if (document.forminstall.db_create_database.checked == true && (document.forminstall.db_user_root.value == ''))
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("YouAskToCreateDatabaseSoRootRequired")); ?>');
		}
		// If create user asked
		else if (document.forminstall.db_create_user.checked == true && (document.forminstall.db_user_root.value == ''))
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("YouAskToCreateDatabaseUserSoRootRequired")); ?>');
		}
		else if (document.forminstall.db_create_user.checked == true && (document.forminstall.db_user_root.value == ''))
		{
			ok=false;
			alert('<?php echo dol_escape_js($langs->transnoentities("YouAskToCreateDatabaseUserSoRootRequired")); ?>');
		}

		return ok;
	}
</script>
-->
<?php
// $db->close();	Not database connexion yet

pFooter(1, $setuplang, 'jscheckparam');
?>
