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
<form class="block wizard same-height">

<h3 class="block-title"><?php echo $langs->trans("SpeedealingWelcome"); ?></h3>
<fieldset class="wizard-fieldset fields-list">

<legend class="legend"><?php echo $langs->trans("Welcome"); ?></legend>

	<div class="field-block">
		<h4>Hello!</h4>
		<p>Please fill this form to complete your registration:</p>
	</div>

</fieldset>

<fieldset class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Prerequisite"); ?></legend>


</fieldset>

<fieldset class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Configuration"); ?></legend>

	<div class="field-block button-height">
		<label for="selectlang" class="label"><b><?php echo $langs->trans("DefaultLanguage"); ?></b></label>
		<?php echo $formadmin->select_language('auto', 'selectlang', 1, 0, 0, 1); ?>
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

</fieldset>

<fieldset class="wizard-fieldset fields-list">

	<legend class="legend"><?php echo $langs->trans("Database"); ?></legend>

	<div class="field-block button-height">

	</div>

	<div class="field-block button-height">

	</div>

	<div class="field-block button-height wizard-controls align-right">

		<button type="submit" class="button glossy mid-margin-right">
			<span class="button-icon"><span class="icon-tick"></span></span>
			<?php echo $langs->trans("Save"); ?>
		</button>

	</div>

</fieldset>

</form>
<!-- END PHP TEMPLATE FOR INSTALL WIZARD -->