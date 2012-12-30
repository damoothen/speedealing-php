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

$statutarray=array('1' => $langs->trans("OnSell"), '0' => $langs->trans("NotOnSell"));
?>

<!-- BEGIN PHP TEMPLATE -->

<?php print_fiche_titre($langs->trans("Product")); ?>

<?php dol_htmloutput_errors((is_numeric($object->error)?'':$object->error),$object->errors); ?>

<?php dol_htmloutput_errors($GLOBALS['mesg'],$GLOBALS['mesgs']); ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
<input type="hidden" name="action" value="add">
<input type="hidden" name="type" value="0">
<input type="hidden" name="canvas" value="<?php echo $canvas; ?>">

<table class="border allwidth">

<tr>
<td class="fieldrequired" width="20%"><?php echo $langs->trans("Ref"); ?></td>
<td><input name="ref" size="40" maxlength="32" value="<?php echo $object->ref; ?>">
<?php if ($_error == 1) echo $langs->trans("RefAlreadyExists"); ?>
</td></tr>

<tr>
<td class="fieldrequired"><?php echo $langs->trans("Label"); ?></td>
<td><input name="libelle" size="40" value="<?php echo $object->label; ?>"></td>
</tr>

<tr>
<td class="fieldrequired"><?php echo $langs->trans("Status").' ('.$langs->trans("Sell").')'; ?></td>
<td><?php echo $form->selectarray('statut',$statutarray,$object->status); ?></td>
</tr>

<tr>
<td class="fieldrequired"><?php echo $langs->trans("Status").' ('.$langs->trans("Buy").')'; ?></td>
<td><?php echo $form->selectarray('statut_buy',$statutarray,$object->status_tobuy); ?></td>
</tr>

<?php if (! empty($conf->stock->enabled)) { ?>
<tr><td><?php echo $langs->trans("StockLimit"); ?></td><td>
<input name="seuil_stock_alerte" size="4" value="<?php echo $object->seuil_stock_alerte; ?>">
</td></tr>
<?php } else { ?>
<input name="seuil_stock_alerte" type="hidden" value="0">
<?php } ?>

<tr><td><?php echo $langs->trans("Nature"); ?></td><td>
<?php echo $object->finished; ?>
</td></tr>

<tr><td><?php echo $langs->trans("Weight"); ?></td><td>
<input name="weight" size="4" value="<?php echo $object->weight; ?>">
<?php echo $object->weight_units; ?>
</td></tr>

<tr><td><?php echo $langs->trans("Length"); ?></td><td>
<input name="size" size="4" value="<?php echo $object->length; ?>">
<?php echo $object->length_units; ?>
</td></tr>

<tr><td><?php echo $langs->trans("Surface"); ?></td><td>
<input name="surface" size="4" value="<?php echo $object->surface; ?>">
<?php echo $object->surface_units; ?>
</td></tr>

<tr><td><?php echo $langs->trans("Volume"); ?></td><td>
<input name="volume" size="4" value="<?php echo $object->volume; ?>">
<?php echo $object->volume_units; ?>
</td></tr>

<tr><td><?php echo $langs->trans("Hidden"); ?></td>
<td><?php echo $object->hidden; ?></td></tr>

<tr><td valign="top"><?php echo $langs->trans("NoteNotVisibleOnBill"); ?></td><td>
<?php echo $object->textarea_note; ?>
</td></tr>
</table>

<br>

<?php if (! $conf->global->PRODUIT_MULTIPRICES) { ?>

<table class="border allwidth">

<tr><td><?php echo $langs->trans("SellingPrice"); ?></td>
<td><input name="price" size="10" value="<?php echo $object->price; ?>">
<?php echo $object->price_base_type; ?>
</td></tr>

<tr><td><?php echo $langs->trans("MinPrice"); ?></td>
<td><input name="price_min" size="10" value="<?php echo $object->price_min; ?>">
</td></tr>

<tr><td width="20%"><?php echo $langs->trans("VATRate"); ?></td><td>
<?php echo $object->tva_tx; ?>
</td></tr>

</table>

<br>
<?php } ?>

<div align="center"><input type="submit" class="button" value="<?php echo $langs->trans("Create"); ?>"></div>

</form>

<!-- END PHP TEMPLATE -->