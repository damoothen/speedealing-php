<?php
/* Copyright (C) 2010 Regis Houssin <regis.houssin@capnetworks.com>
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
 */

$object=$GLOBALS['object'];
?>

<!-- BEGIN PHP TEMPLATE VIEW.TPL -->
<?php echo $langs->trans("Service"); ?>

<?php dol_htmloutput_errors($object->error,$object->errors); ?>

<table class="border allwidth">

<tr>
<td width="15%"><?php echo $langs->trans("Ref"); ?></td>
<td colspan="2"><?php echo $object->ref; ?></td>
</tr>

<tr>
<td><?php echo $langs->trans("Label") ?></td>
<td><?php echo $object->label; ?></td>

<?php if ($object->photos) { ?>
<td valign="middle" align="center" width="30%" rowspan="<?php echo $object->nblignes; ?>">
<?php echo $object->photos; ?>
</td>
<?php } ?>

</tr>

<tr>
<td><?php echo $langs->trans("Status").' ('.$langs->trans("Sell").')'; ?></td>
<td><?php echo $object->status; ?></td>
</tr>

<tr>
<td><?php echo $langs->trans("Status").' ('.$langs->trans("Buy").')'; ?></td>
<td><?php echo $object->status_buy; ?></td>
</tr>

<tr>
<td valign="top"><?php echo $langs->trans("Description"); ?></td>
<td colspan="2"><?php echo $object->description; ?></td>
</tr>

<tr><td><?php echo $langs->trans("Duration"); ?></td>
<td><?php echo $object->duration_value; ?>&nbsp;
<?php echo $object->duration_unit; ?>&nbsp;
</td></tr>

<tr>
<td valign="top"><?php echo $langs->trans("Note"); ?></td>
<td colspan="2"><?php echo $object->note; ?></td>
</tr>

</table>

<!-- END PHP TEMPLATE -->