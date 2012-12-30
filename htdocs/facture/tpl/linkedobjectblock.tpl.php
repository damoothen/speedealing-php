<?php
/* Copyright (C) 2010-2011 Regis Houssin <regis.houssin@capnetworks.com>
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

<?php

$langs = $GLOBALS['langs'];
$linkedObjectBlock = $GLOBALS['linkedObjectBlock'];

$langs->load("bills");
echo '<br>';
if ($num > 1) print_titre($langs->trans("RelatedBills"));
else print_titre($langs->trans("RelatedBill"));
?>
<table class="noborder allwidth">
<tr class="liste_titre">
	<td><?php echo $langs->trans("Ref"); ?></td>
	<td align="center"><?php echo $langs->trans("Date"); ?></td>
	<td align="right"><?php echo $langs->trans("AmountHTShort"); ?></td>
	<td align="right"><?php echo $langs->trans("Status"); ?></td>
</tr>
<?php
$var=true;
foreach($linkedObjectBlock as $object)
{
	$var=!$var;
?>
<tr <?php echo $GLOBALS['bc'][$var]; ?> ><td>
	<a href="<?php echo DOL_URL_ROOT.'/compta/facture.php?facid='.$object->id ?>"><?php echo img_object($langs->trans("ShowBill"),"bill").' '.$object->ref; ?></a></td>
	<td align="center"><?php echo dol_print_date($object->date,'day'); ?></td>
	<td align="right"><?php echo price($object->total_ht); ?></td>
	<td align="right"><?php echo $object->getLibStatut(3); ?></td>
</tr>
<?php
$total = $total + $object->total_ht;
}
?>
<tr class="liste_total">
	<td align="left" colspan="2"><?php echo $langs->trans("TotalHT"); ?></td>
	<td align="right"><?php echo price($total); ?></td>
	<td>&nbsp;</td>
</tr>
</table>

<!-- END PHP TEMPLATE -->