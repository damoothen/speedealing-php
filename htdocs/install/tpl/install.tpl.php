<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
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
 *
 */
?>

<!-- BEGIN PHP TEMPLATE FOR INSTALL WIZARD -->
<form class="block wizard">

<div class="block-title"><img src="<?php echo DOL_URL_ROOT; ?>/logo.png" alt="<?php echo $langs->trans("Speedealing"); ?>"></div>
<span class="ribbon">
	<span class="ribbon-inner green-gradient glossy"><?php echo constant('DOL_VERSION'); ?></span>
</span>
<fieldset id="welcome" class="wizard-fieldset fields-list">

<!-- Welcome step -->

<legend class="legend"><?php echo $langs->trans("Welcome"); ?></legend>

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("WelcomeTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/welcome.png" alt="Welcome"></div>
		<p><?php echo $langs->trans("WelcomeDescription"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="selectlang" class="label"><b><?php echo $langs->trans("DefaultLanguage"); ?></b></label>
		<?php echo $formadmin->select_language($setuplang, 'selectlang', 1, 0, 0, 1); ?>
	</div>

	<div class="field-drop button-height black-inputs">
		<p><?php echo $langs->trans("LanguageDescription"); ?></p>
	</div>

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("InstallTypeTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/installtype.png" alt="Install"></div>
		<p><?php echo $langs->trans("InstallTypeDescription"); ?></p>
	</div>

	<div class="field-block button-height">
		<span class="label"><b><?php echo $langs->trans("InstallType"); ?></b></span>
		<input type="radio" name="install_type" id="install_type_server" value="server" class="radio" checked> <label for="install_type_server"><?php echo $langs->trans("InstallTypeServer"); ?></label>
		<span class="info-spot on-top">
			<span class="icon-info-round"></span>
			<span class="info-bubble blue-bg"><?php echo $langs->trans("InstallTypeServerDescription"); ?></span>
		</span>
		<br>
		<input type="radio" name="install_type" id="install_type_client" value="client" class="radio" disabled> <label for="install_type_client"><?php echo $langs->trans("InstallTypeClient"); ?></label>
		<span class="info-spot on-top">
			<span class="icon-info-round"></span>
			<span class="info-bubble blue-bg"><?php echo $langs->trans("InstallTypeClientDescription"); ?></span>
		</span>
	</div>

</fieldset>

<!-- Prerequisite step -->

<fieldset id="prerequisite" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Prerequisites"); ?></legend>

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("PrerequisitesTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/prerequisite.png" alt="Prerequisites"></div>
		<p><?php echo $langs->trans("PrerequisitesDescription"); ?></p>
	</div>

	<div class="field-block">
		<label for="php_version" class="label"><b><?php echo $langs->trans("PHPVersion"); ?></b></label>
		<div id="php_version"></div>
	</div>

	<div class="field-block">
		<label for="php_memory" class="label"><b><?php echo $langs->trans("PHPMemoryLimit"); ?></b></label>
		<div id="php_memory"></div>
	</div>

	<div class="field-block">
		<label for="php_gd" class="label"><b><?php echo $langs->trans("PHPGD"); ?></b></label>
		<div id="php_gd"></div>
	</div>

	<div class="field-block">
		<label for="php_curl" class="label"><b><?php echo $langs->trans("PHPCurl"); ?></b></label>
		<div id="php_curl"></div>
	</div>

	<div class="field-block">
		<label for="php_memcached" class="label"><b><?php echo $langs->trans("PHPMemcached"); ?></b></label>
		<div id="php_memcached"></div>
	</div>

	<div class="field-block">
		<label for="couchdb_rewrite" class="label"><b><?php echo $langs->trans("CouchDB"); ?></b></label>
		<div id="couchdb_rewrite"></div>
	</div>

	<div class="field-block">
		<label for="conf_file" class="label"><b><?php echo $langs->trans("ConfFileStatus"); ?></b></label>
		<div id="conf_file"></div>
	</div>

	<div id="reload_required" class="field-block">
		<p class="red"><?php echo $langs->trans("ReloadIsRequired"); ?></p>
	</div>

	<div class="field-block button-height wizard-controls align-right">
		<button id="reload_button" type="button" class="button glossy mid-margin-right">
			<span class="button-icon red-gradient">
				<span class="icon-redo"></span>
			</span>
			<?php echo $langs->trans("Reload"); ?>
		</button>
	</div>

</fieldset>

<!-- Users step -->

<fieldset id="users" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Users"); ?></legend>

	<!-- Local Superadmin -->

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("SuperadminTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/superadmin.png" alt="Superadmin"></div>
		<p><?php echo $langs->trans("SuperadminDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_root" class="label"><b><?php echo $langs->trans("EmailAddress"); ?></b></label>
		<input type="text" name="couchdb_user_root" id="couchdb_user_root" value="admin@speedealing.com" class="input full-width validate[required,custom[email]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_pass_root" class="label"><b><?php echo $langs->trans("Password"); ?></b></label>
		<input type="password" name="couchdb_pass_root" id="couchdb_pass_root" value="" class="input full-width validate[required]">
	</div>

	<!-- User Account -->

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("UserAccount"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/useraccount.png" alt="UserAccount"></div>
		<p><?php echo $langs->trans("UserAccountDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_firstname" class="label"><b><?php echo $langs->trans("FirstName"); ?></b></label>
		<input type="text" name="couchdb_user_firstname" id="couchdb_user_firstname" value="Demo" class="input full-width validate[required]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_lastname" class="label"><b><?php echo $langs->trans("LastName"); ?></b></label>
		<input type="text" name="couchdb_user_lastname" id="couchdb_user_lastname" value="Demo" class="input full-width validate[required]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_pseudo" class="label"><b><?php echo $langs->trans("Pseudo"); ?></b></label>
		<input type="text" name="couchdb_user_pseudo" id="couchdb_user_pseudo" value="demo" class="input full-width validate[required]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_email" class="label"><b><?php echo $langs->trans("EmailAddress"); ?></b></label>
		<input type="text" name="couchdb_user_email" id="couchdb_user_email" value="demo@demo.org" class="input full-width validate[required,custom[email]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_pass" class="label"><b><?php echo $langs->trans("Password"); ?></b></label>
		<input type="password" name="couchdb_user_pass" id="couchdb_user_pass" value="demo" class="input full-width validate[required]">
	</div>

</fieldset>

<!-- Database step -->

<fieldset id="database" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Database"); ?></legend>

	<!-- Local database -->

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("DatabaseTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/couchdb.png" alt="CouchDB"></div>
		<p><?php echo $langs->trans("DatabaseDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="couchdb_name" class="label"><b><?php echo $langs->trans("Database"); ?></b></label>
		<input type="text" name="couchdb_name" id="couchdb_name" value="speedealing" class="input full-width validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_host" class="label"><b><?php echo $langs->trans("Server"); ?></b></label>
		<input type="text" name="couchdb_host" id="couchdb_host" value="http://localhost" class="input full-width validate[required,custom[urlMini],custom[noTrailingSlash]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_port" class="label"><b><?php echo $langs->trans("Port"); ?></b></label>
		<input type="text" name="couchdb_port" id="couchdb_port" value="5984" class="input validate[required,custom[onlyNumberSp]]">
	</div>

	<!-- Local Sync User -->

	<div class="field-block syncuser">
		<h4 class="blue"><?php echo $langs->trans("SyncUserTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/usersync.png" alt="Syncuser"></div>
		<p><?php echo $langs->trans("SyncUserDesc"); ?></p>
	</div>

	<div class="field-block button-height syncuser">
		<label for="couchdb_user_sync" class="label"><b><?php echo $langs->trans("Identifier"); ?></b></label>
		<span id="couchdb_user_sync"></span>
		<button type="button" id="reload_identifier" class="icon-redo button compact orange-gradient glossy float-right">
	</div>

	<div class="field-block button-height syncuser">
		<label for="couchdb_pass_sync" class="label"><b><?php echo $langs->trans("SecurityKey"); ?></b></label>
		<span id="couchdb_pass_sync"></span>
		<button type="button" id="reload_secretkey" class="icon-redo button compact orange-gradient glossy float-right">
	</div>

	<!-- Remote database -->

	<div class="field-block remotebase">
		<h4 class="blue"><?php echo $langs->trans("RemoteDatabaseTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/couchdb-replication.png" alt="CouchDB"></div>
		<p><?php echo $langs->trans("RemoteDatabaseDesc"); ?></p>
	</div>

	<div class="field-block button-height remotebase">
		<label for="couchdb_user_sync_remote" class="label"><b><?php echo $langs->trans("Identifier"); ?></b></label>
		<input type="text" name="couchdb_user_sync_remote" id="couchdb_user_sync_remote" value="" class="input full-width validate[required]">
	</div>

	<div class="field-block button-height remotebase">
		<label for="couchdb_pass_sync_remote" class="label"><b><?php echo $langs->trans("SecurityKey"); ?></b></label>
		<input type="password" name="couchdb_pass_sync_remote" id="couchdb_pass_sync_remote" value="" class="input full-width validate[required]">
	</div>

	<div class="field-block button-height remotebase">
		<label for="couchdb_host_remote" class="label"><b><?php echo $langs->trans("DatabaseUrl"); ?></b></label>
		<input type="text" name="couchdb_host_remote" id="couchdb_host_remote" value="http://localhost" class="input full-width validate[required,custom[urlMini],custom[noTrailingSlash]]">
	</div>

	<div class="field-drop button-height black-inputs">
		<input type="checkbox" name="couchdb_create_usersync" id="couchdb_create_usersync" class="switch mini"> &nbsp; <label for="couchdb_create_usersync"><?php echo $langs->trans("CheckToCreateCouchdbUserSync"); ?></label><br>
		<input type="checkbox" name="couchdb_replication" id="couchdb_replication" class="switch mini"> &nbsp; <label for="couchdb_replication"><?php echo $langs->trans("CheckToReplicateRemoteDatabase"); ?></label>
	</div>

	<!-- Memcached -->

	<div class="field-block memcached">
		<h4 class="blue"><?php echo $langs->trans("MemcachedTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/memcached.png" alt="Memcached"></div>
		<p><?php echo $langs->trans("MemcachedDesc"); ?></p>
	</div>

	<div class="field-block button-height memcached">
		<label for="memcached_host" class="label"><b><?php echo $langs->trans("Server"); ?></b></label>
		<input type="text" name="memcached_host" id="memcached_host" value="localhost" class="input full-width validate[required]">
	</div>

	<div class="field-block button-height memcached">
		<label for="memcached_port" class="label"><b><?php echo $langs->trans("Port"); ?></b></label>
		<input type="text" name="memcached_port" id="memcached_port" value="11211" class="input validate[required,custom[onlyNumberSp]]">
	</div>

	<div class="field-block button-height wizard-controls align-right">
		<button type="button" id="install_button" class="button glossy mid-margin-right">
			<?php echo $langs->trans("Install"); ?>
			<span class="button-icon green-gradient right-side">
				<span class="icon-tick"></span>
			</span>
		</button>
	</div>

</fieldset>

<!-- Install step -->

<fieldset id="install" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Install"); ?></legend>

	<div class="field-block">
		<h4 class="blue"><?php echo $langs->trans("InstallTitle"); ?></h4>
		<div class="image hidden-on-mobile"><img src="<?php echo DOL_URL_ROOT; ?>/install/img/installation.png" alt="Installation"></div>
		<p><?php echo $langs->trans("InstallDescription"); ?></p>
	</div>

	<div class="field-block">
		<label for="set_conf" class="label"><b><?php echo $langs->trans("SystemSetup"); ?></b></label>
		<span id="set_conf"></span>
	</div>

	<div class="field-block">
		<label for="set_database" class="label"><b><?php echo $langs->trans("Database"); ?></b></label>
		<span id="set_database"></span>
	</div>

	<div class="field-block">
		<label for="set_security" class="label"><b><?php echo $langs->trans("Security"); ?></b></label>
		<span id="set_security"></span>
	</div>

	<div class="field-drop button-height black-inputs">

	</div>

	<div class="field-block button-height wizard-controls align-right">
		<button id="start_button" type="button" class="button glossy mid-margin-right">
			<?php echo $langs->trans("Start"); ?>
			<span class="button-icon green-gradient right-side">
				<span class="icon-play"></span>
			</span>
		</button>
	</div>

</fieldset>

</form>
<!-- END PHP TEMPLATE FOR INSTALL WIZARD -->
