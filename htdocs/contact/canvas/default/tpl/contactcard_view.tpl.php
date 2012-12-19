<?php
/* Copyright (C) 2010-2012 Regis Houssin <regis@dolibarr.fr>
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

<!-- BEGIN PHP TEMPLATE CONTACTCARD_VIEW.TPL.PHP DEFAULT -->
<?php echo $this->control->tpl['showhead']; ?>

<?php
dol_htmloutput_errors($this->control->tpl['error'],$this->control->tpl['errors']);
?>

<?php if (! empty($this->control->tpl['action_create_user'])) echo $this->control->tpl['action_create_user']; ?>
<?php if (! empty($this->control->tpl['action_delete'])) echo $this->control->tpl['action_delete']; ?>

<table class="border allwidth">

<tr>
	<td width="20%"><?php echo $langs->trans("Ref"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['showrefnav']; ?></td>
</tr>

<tr>
	<td width="20%"><?php echo $langs->trans("Lastname"); ?></td>
	<td width="30%"><?php echo $this->control->tpl['name']; ?></td>
	<td width="25%"><?php echo $langs->trans("Firstname"); ?></td>
	<td width="25%"><?php echo $this->control->tpl['firstname']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Company"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['company']; ?></td>
</tr>

<tr>
	<td width="15%"><?php echo $langs->trans("UserTitle"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['civility']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("PostOrFunction"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['poste']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Address"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['address']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Zip").' / '.$langs->trans("Town"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['zip'].$this->control->tpl['ville']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("Country"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['country']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans('State'); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['departement']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("PhonePro"); ?></td>
	<td><?php echo $this->control->tpl['phone_pro']; ?></td>
	<td><?php echo $langs->trans("PhonePerso"); ?></td>
	<td><?php echo $this->control->tpl['phone_perso']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("PhoneMobile"); ?></td>
	<td><?php echo $this->control->tpl['phone_mobile']; ?></td>
	<td><?php echo $langs->trans("Fax"); ?></td>
	<td><?php echo $this->control->tpl['fax']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("EMail"); ?></td>
	<td><?php echo $this->control->tpl['email']; ?></td>
	<?php if ($this->control->tpl['nb_emailing']) { ?>
	<td nowrap><?php echo $langs->trans("NbOfEMailingsReceived"); ?></td>
	<td><?php echo $this->control->tpl['nb_emailing']; ?></td>
	<?php } else { ?>
	<td colspan="2">&nbsp;</td>
	<?php } ?>
</tr>

<tr>
	<td><?php echo $langs->trans("IM"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['jabberid']; ?></td>
</tr>

<tr>
	<td><?php echo $langs->trans("ContactVisibility"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['visibility']; ?></td>
</tr>

<tr>
	<td valign="top"><?php echo $langs->trans("Note"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['note']; ?></td>
</tr>

<?php foreach ($this->control->tpl['contact_element'] as $element) { ?>
<tr>
	<td><?php echo $element['linked_element_label']; ?></td>
	<td colspan="3"><?php echo $element['linked_element_value']; ?></td>
</tr>
<?php } ?>

<tr>
	<td><?php echo $langs->trans("DolibarrLogin"); ?></td>
	<td colspan="3"><?php echo $this->control->tpl['dolibarr_user']; ?></td>
</tr>

</table>

<?php echo $this->control->tpl['showend']; ?>

<?php if (! $user->societe_id) { ?>
<div class="tabsAction">

<?php if ($user->rights->societe->contact->creer) { ?>
<a class="butAction" href="<?php echo $_SERVER["PHP_SELF"].'?id='.$this->control->tpl['id'].'&amp;action=edit&amp;canvas='.$canvas; ?>"><?php echo $langs->trans('Modify'); ?></a>
<?php } ?>

<?php if (! $this->control->tpl['user_id'] && $user->rights->user->user->creer) { ?>
<a class="butAction" href="<?php echo $_SERVER["PHP_SELF"].'?id='.$this->control->tpl['id'].'&amp;action=create_user&amp;canvas='.$canvas; ?>"><?php echo $langs->trans("CreateDolibarrLogin"); ?></a>
<?php } ?>

<?php if ($user->rights->societe->contact->supprimer) { ?>
<a class="butActionDelete" href="<?php echo $_SERVER["PHP_SELF"].'?id='.$this->control->tpl['id'].'&amp;action=delete&amp;canvas='.$canvas; ?>"><?php echo $langs->trans('Delete'); ?></a>
<?php } ?>

</div><br>
<?php }

echo $this->control->tpl['actionstodo'];

echo $this->control->tpl['actionsdone'];
?>

<!-- END PHP TEMPLATE -->