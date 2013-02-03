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

<h3 class="block-title"><?php echo $langs->trans("SpeedealingWelcome"); ?></h3>
<fieldset id="welcome" class="wizard-fieldset fields-list">

<legend class="legend"><?php echo $langs->trans("Welcome"); ?></legend>

	<div class="field-block">
		<h4><?php echo $langs->trans("WelcomeTitle"); ?></h4>
		<p><?php echo $langs->trans("WelcomeDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="selectlang" class="label"><b><?php echo $langs->trans("DefaultLanguage"); ?></b></label>
		<?php echo $formadmin->select_language($setuplang, 'selectlang', 1, 0, 0, 1); ?>
	</div>

	<div class="field-block">
		<h4><?php echo $langs->trans("InstallTypeTitle"); ?></h4>
		<p><?php echo $langs->trans("InstallTypeDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<span class="label"><b><?php echo $langs->trans("InstallType"); ?></b></span>
		<input type="radio" name="install_type" id="install_type_fullweb" value="fullweb" class="radio" checked="checked"> <label for="install_type_fullweb"><?php echo $langs->trans("InstallTypeFullWeb"); ?></label>
		<span class="info-spot on-top">
			<span class="icon-info-round"></span>
			<span class="info-bubble"><?php echo $langs->trans("InstallTypeDesc"); ?></span>
		</span>
		<br>
		<input type="radio" name="install_type" id="install_type_primary_server" value="primary_server" class="radio"> <label for="install_type_primary_server"><?php echo $langs->trans("InstallTypePrimaryServer"); ?></label>
		<span class="info-spot on-top">
			<span class="icon-info-round"></span>
			<span class="info-bubble"><?php echo $langs->trans("InstallTypePrimaryServerDesc"); ?></span>
		</span>
		<br>
		<input type="radio" name="install_type" id="install_type_secondary_server" value="secondary_server" class="radio disabled"> <label for="install_type_secondary_server"><?php echo $langs->trans("InstallTypeSecondaryServer"); ?></label>
		<span class="info-spot on-top">
			<span class="icon-info-round"></span>
			<span class="info-bubble"><?php echo $langs->trans("InstallTypeSecondaryServerDesc"); ?></span>
		</span>
		<br>
		<input type="radio" name="install_type" id="install_type_client" value="client" class="radio disabled"> <label for="install_type_client"><?php echo $langs->trans("InstallTypeClient"); ?></label>
		<span class="info-spot on-top">
			<span class="icon-info-round"></span>
			<span class="info-bubble"><?php echo $langs->trans("InstallTypeClientDesc"); ?></span>
		</span>
	</div>

</fieldset>

<fieldset id="prerequisite" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Prerequisite"); ?></legend>

	<div class="field-block">
		<h4><?php echo $langs->trans("PrerequisiteTitle"); ?></h4>
		<p><?php echo $langs->trans("PrerequisiteDesc"); ?></p>
	</div>


</fieldset>

<fieldset id="configuration" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Configuration"); ?></legend>

	<div class="field-block">
		<h4><?php echo $langs->trans("ConfigurationTitle"); ?></h4>
		<p><?php echo $langs->trans("ConfigurationDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="main_dir" class="label"><b><?php echo $langs->trans("WebPagesDirectory"); ?></b></label>
		<input type="text" class="input full-width" name="main_dir" id="main_dir" value="<?php echo $dolibarr_main_document_root; ?>" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="main_data_dir" class="label"><b><?php echo $langs->trans("DocumentsDirectory"); ?></b></label>
		<input type="text" class="input full-width" name="main_data_dir" id="main_data_dir" value="<?php echo $dolibarr_main_data_root; ?>" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="main_url" class="label"><b><?php echo $langs->trans("URLRoot"); ?></b></label>
		<input type="text" class="input full-width" name="main_url" id="main_url" value="<?php echo $dolibarr_main_url_root; ?>" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<?php if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') { ?>
	<div class="field-drop button-height black-inputs">
		<input type="checkbox" name="main_force_https" id="main_force_https" class="switch medium"> &nbsp; <label for="main_force_https"><?php echo $langs->trans("ForceHttps"); ?></label>
	</div>
	<?php } ?>

	<div class="field-block">
		<h4><?php echo $langs->trans("MemcachedTitle"); ?></h4>
		<p><?php echo $langs->trans("MemcachedDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="memcached_host" class="label"><b><?php echo $langs->trans("Server"); ?></b></label>
		<input type="text" class="input full-width" name="memcached_host" id="memcached_host" value="localhost" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="memcached_port" class="label"><b><?php echo $langs->trans("Port"); ?></b></label>
		<input type="text" class="input" name="memcached_port" id="memcached_port" value="11211" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

</fieldset>

<fieldset id="database" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Database"); ?></legend>

	<div class="field-block">
		<h4><?php echo $langs->trans("SuperadminTitle"); ?></h4>
		<p><?php echo $langs->trans("SuperadminDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="couchdb_user_root" class="label"><b><?php echo $langs->trans("Login"); ?></b></label>
		<input type="text" class="input full-width" name="couchdb_user_root" id="couchdb_user_root" value="" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_pass_root" class="label"><b><?php echo $langs->trans("Password"); ?></b></label>
		<input type="text" class="input full-width" name="couchdb_pass_root" id="couchdb_pass_root" value="" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-drop button-height black-inputs">
		<input type="checkbox" name="couchdb_create_admin" id="couchdb_create_admin" class="switch medium" checked="checked"> &nbsp; <label for="couchdb_create_admin"><?php echo $langs->trans("CreateAdminUser"); ?></label>
	</div>

	<div class="field-block">
		<h4><?php echo $langs->trans("DatabaseTitle"); ?></h4>
		<p><?php echo $langs->trans("DatabaseDesc"); ?></p>
	</div>

	<div class="field-block button-height">
		<label for="couchdb_host" class="label"><b><?php echo $langs->trans("Server"); ?></b></label>
		<input type="text" class="input full-width" name="couchdb_host" id="couchdb_host" value="http://localhost" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_name" class="label"><b><?php echo $langs->trans("DatabaseName"); ?></b></label>
		<input type="text" class="input full-width" name="couchdb_name" id="couchdb_name" value="" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="couchdb_port" class="label"><b><?php echo $langs->trans("Port"); ?></b></label>
		<input type="text" class="input" name="couchdb_port" id="couchdb_port" value="5984" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-drop button-height black-inputs">
		<input type="checkbox" name="couchdb_create_database" id="couchdb_create_database" class="switch medium" checked="checked"> &nbsp; <label for="couchdb_create_database"><?php echo $langs->trans("CheckToCreateCouchdbDatabase"); ?></label>
	</div>

</fieldset>

<fieldset id="install" class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Install"); ?></legend>

	<div class="field-block">
		<h4><?php echo $langs->trans("InstallTitle"); ?></h4>
		<p><?php echo $langs->trans("InstallDesc"); ?></p>
	</div>

	<div class="field-block">
		<label for="add_superadmin" class="label"><b><?php echo $langs->trans("AddSuperAdmin"); ?></b></label>
		<span id="add_superadmin"></span>
	</div>

	<div class="field-block hidden">
		<label for="add_syncuser" class="label"><b><?php echo $langs->trans("AddSyncUser"); ?></b></label>
		<span id="add_syncuser"></span>
	</div>

	<div class="field-block">
		<label for="add_database" class="label"><b><?php echo $langs->trans("AddDatabase"); ?></b></label>
		<span id="add_database"></span>
	</div>

	<div class="field-block hidden">
		<label for="sync_database" class="label"><b><?php echo $langs->trans("SynchronizingDatabase"); ?></b></label>
		<span id="sync_database"></span>
	</div>

</fieldset>

</form>
<!-- END PHP TEMPLATE FOR INSTALL WIZARD -->