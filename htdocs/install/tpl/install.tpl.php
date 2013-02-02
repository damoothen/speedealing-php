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

<h3 class="block-title"><?php echo $langs->trans("SpeedealingFirstInstall"); ?></h3>
<fieldset class="wizard-fieldset fields-list">

<legend class="legend"><?php echo $langs->trans("Welcome"); ?></legend>

	<div class="field-block">
		<h4>Hello!</h4>
		<p>Please fill this form to complete your registration:</p>
	</div>

	<div class="field-block button-height">
		<label for="selectlang" class="label"><b><?php echo $langs->trans("DefaultLanguage"); ?></b></label>
		<?php echo $formadmin->select_language('auto', 'selectlang', 1, 0, 0, 1); ?>
	</div>

	<div class="field-block button-height">
		<label for="last_name" class="label"><b>Last name</b></label>
		<input type="text" name="last_name" id="last_name" value="" class="input validate[required]">
	</div>

	<div class="field-block button-height">
		<span class="label"><b>Gender</b></span>
		<input type="radio" name="gender" id="gender_male" value="male" class="radio"> <label for="gender_male">Male</label><br>
		<input type="radio" name="gender" id="gender_female" value="female" class="radio"> <label for="gender_female">Female</label>
	</div>

</fieldset>

<fieldset class="wizard-fieldset fields-list">

	<legend class="legend">Profile</legend>

	<div class="field-block button-height">
		<small class="input-info">This is the name that will be displayed on profile page</small>
		<label for="login" class="label"><b>User login</b></label>
		<input type="text" name="login" id="login" value="" class="input validate[required,custom[onlyLetterNumber]]">
	</div>

	<div class="field-block button-height">
		<label for="file" class="label"><b>Avatar</b> (*.jpg)</label>
		<span class="input file"><span class="file-text"></span><span class="button compact">Select file</span><input type="file" name="file-input" id="file-input" value="" class="file withClearFunctions"></span>
		<small class="input-info">Max file size: 2MB</small>
	</div>

	<div class="field-drop button-height black-inputs">
		<label for="resize_height" class="label"><b>Resize height</b> (in px)</label>
		<span class="number input margin-right">
			<button type="button" class="button number-down">-</button>
			<input type="text" name="resize_height" id="resize_height" value="320" class="input-unstyled" data-number-options='{"min":100,"max":400}'>
			<button type="button" class="button number-up">+</button>
		</span>

		<input type="checkbox" name="crop" id="crop" class="switch medium" checked="checked"> &nbsp; <label for="crop">Enable crop</label>
	</div>

</fieldset>

</form>
<!-- END PHP TEMPLATE FOR INSTALL WIZARD -->