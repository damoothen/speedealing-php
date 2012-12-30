<?php
/* Copyright (C) 2010-2012 Regis Houssin <regis.houssin@capnetworks.com>
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
?>

<!-- BEGIN PHP TEMPLATE -->

<table class="noborder">
<tr class="liste_titre">
<td colspan="2"><?php echo $langs->trans("File"); ?></td>
<td align="center"><?php echo $langs->trans("Version"); ?></td>
<td align="center"><?php echo $langs->trans("Active"); ?></td>
<td align="center">&nbsp;</td>
</tr>

<?php
$var=True;
foreach ($triggers as $trigger)
{
$var=!$var;
?>

<tr <?php echo $bc[$var]; ?>>

<td valign="top" width="14" align="center"><?php echo $trigger['picto']; ?></td>
<td valign="top"><?php echo $trigger['file']; ?></td>
<td valign="top" align="center"><?php echo $trigger['version']; ?></td>
<td valign="top" align="center"><?php echo $trigger['status']; ?></td>
<td valign="top"><?php echo $form->textwithpicto('', $trigger['info']); ?></td>

</tr>

<?php
}
?>

</table>

<!-- END PHP TEMPLATE -->