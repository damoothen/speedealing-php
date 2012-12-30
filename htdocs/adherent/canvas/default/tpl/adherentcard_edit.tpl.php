<?php
/* Copyright (C) 2010 Regis Houssin  <regis.houssin@capnetworks.com>
 * Copyright (C) 2012 Philippe Grand <philippe.grand@atoo-net.com>
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
 */

$contact = $GLOBALS['objcanvas']->control->object;

?>

<!-- BEGIN PHP TEMPLATE ADHERENTCARD_EDIT.TPL.PHP DEFAULT -->

<?php
print_fiche_titre($this->control->tpl['title']);

dol_htmloutput_errors($this->control->tpl['error'],$this->control->tpl['errors']);

echo $this->control->tpl['ajax_selectcountry'];
?>

<br>

<form method="post" name="formmember" action="<?php echo $_SERVER["PHP_SELF"].'?id='.GETPOST('id','int'); ?>">
<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
<input type="hidden" name="canvas" value="<?php echo $canvas ?>">
<input type="hidden" name="id" value="<?php echo GETPOST('id','int'); ?>">
<input type="hidden" name="action" value="update">
<input type="hidden" name="adherentid" value="<?php echo $this->control->tpl['id']; ?>">
<input type="hidden" name="old_name" value="<?php echo $this->control->tpl['name']; ?>">
<input type="hidden" name="old_firstname" value="<?php echo $this->control->tpl['firstname']; ?>">
<?php if (! empty($this->control->tpl['company_id'])) { ?>
<input type="hidden" name="socid" value="<?php echo $this->control->tpl['company_id']; ?>">
<?php } ?>

<table class="border allwidth">

<tr>
	<td><?php echo $langs->trans("Ref"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['ref']; ?></td>
</tr>

<tr>
	<td width="15%" class="fieldrequired"><?php echo $langs->trans("Lastname").' / '.$langs->trans("Label"); ?></td>
	<td><input name="lastname" type="text" size="30" maxlength="80" value="<?php echo $this->control->tpl['name']; ?>"></td>
	<td width="20%"><?php echo $langs->trans("Firstname"); ?></td>
	<td width="25%"><input name="firstname" type="text" size="30" maxlength="80" value="<?php echo $this->control->tpl['firstname']; ?>"></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Company"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['company']; ?></td>
</tr>

<tr>
	<td width="15%"><?php echo $langs->trans("UserTitle"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['select_civility']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Morphy"); ?></td>
	<td colspan="3"><input name="poste" type="text" size="50" maxlength="80" value="<?php echo $this->control->tpl['select_morphy']; ?>"></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Address"); ?></td>
	<td colspan="3"><textarea class="flat" name="address" cols="70"><?php echo $this->control->tpl['address']; ?></textarea></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Zip").' / '.$langs->trans("Town"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['select_zip'].'&nbsp;'.$this->control->tpl['select_town']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Country"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['select_country'].$this->control->tpl['info_admin']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans('State'); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['select_state']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("PhonePro"); ?></td>
	<td><input name="phone_pro" type="text" size="18" maxlength="80" value="<?php echo $this->control->tpl['phone_pro']; ?>"></td>
	<td><?php echo $langs->trans("PhonePerso"); ?></td>
	<td><input name="phone_perso" type="text" size="18" maxlength="80" value="<?php echo $this->control->tpl['phone_perso']; ?>"></td>
</tr>

<tr>
	<td><?php echo $langs->trans("PhoneMobile"); ?></td>
	<td><input name="phone_mobile" type="text" size="18" maxlength="80" value="<?php echo $this->control->tpl['phone_mobile']; ?>"></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Email"); ?></td>
	<td><input name="email" type="text" size="50" maxlength="80" value="<?php echo $this->control->tpl['email']; ?>"></td>
</tr>

<tr>
	<td><?php echo $langs->trans("ContactVisibility"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['select_visibility']; ?></td>
</tr>

<tr>
	<td valign="top"><?php echo $langs->trans("Note"); ?></td>
	<td colspan="3" valign="top"><textarea name="note" cols="70" rows="<?php echo ROWS_3; ?>"><?php echo $this->control->tpl['note']; ?></textarea></td>
</tr>

<tr>
	<td><?php echo $langs->trans("DolibarrLogin"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['dolibarr_user']; ?></td>
</tr>

<tr>
	<td colspan="4" align="center">
	<input type="submit" class="button" name="save" value="<?php echo $langs->trans("Save"); ?>">&nbsp;
	<input type="submit" class="button" name="cancel" value="<?php echo $langs->trans("Cancel"); ?>">
	</td>
</tr>

</table><br>

</form>

<!-- END PHP TEMPLATE -->