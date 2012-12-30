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
 *
 */
?>

<!-- BEGIN PHP TEMPLATE -->

<table class="notopnoleftnoright allwidth" style="margin-bottom: 2px;">
<tr>
	<td class="nobordernopadding" width="40" align="left" valign="middle">
		<?php echo $title_picto; ?>
	</td>
	<td class="nobordernopadding" valign="middle">
    	<div class="titre"><?php echo $title_text; ?></div>
	</td>
</tr>
</table>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" name="formulaire">
<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
<input type="hidden" name="action" value="list">
<input type="hidden" name="sortfield" value="<?php echo $sortfield; ?>">
<input type="hidden" name="sortorder" value="<?php echo $sortorder; ?>">
<input type="hidden" name="canvas" value="service">
<input type="hidden" name="type" value="1">

<table class="liste allwidth">

<!-- FIELDS TITLE -->

<tr class="liste_titre">
	<?php
 	foreach($fieldlist as $field) {
 		if ($field['enabled']) {
 			if ($field['sort'])	{ ?>
 				<td class="liste_titre" align="<?php echo $field['align']; ?>"><?php echo $field['title']; ?>
 					<a href="<?php echo $_SERVER["PHP_SELF"];?>?sortfield=<?php echo $field['name']; ?>&amp;sortorder=asc&amp;begin=&amp;tosell=&amp;canvas=default&amp;fourn_id=&amp;snom=&amp;sref=">
 						<img src="<?php echo DOL_URL_ROOT; ?>/theme/<?php echo $conf->theme; ?>/img/1downarrow.png" border="0" alt="A-Z" title="A-Z">
 					</a>
  					<a href="<?php echo $_SERVER["PHP_SELF"];?>?sortfield=<?php echo $field['name']; ?>&amp;sortorder=desc&amp;begin=&amp;tosell=&amp;canvas=default&amp;fourn_id=&amp;snom=&amp;sref=">
  						<img src="<?php echo DOL_URL_ROOT; ?>/theme/<?php echo $conf->theme; ?>/img/1uparrow.png" border="0" alt="Z-A" title="Z-A">
  					</a>
  				</td>
  		<?php } else { ?>
  				<td class="liste_titre" align="<?php echo $field['align']; ?>"><?php echo $field['title']; ?></td>
	<?php } } } ?>
</tr>

 <!-- FIELDS SEARCH -->

<tr class="liste_titre">
	<?php
 	$num = count($fieldlist);
 	foreach($fieldlist as $key => $searchfield)	{
 		if ($searchfield['enabled']) {
 			if ($searchfield['search'])	{ ?>
  				<td class="liste_titre" align="<?php echo $searchfield['align']; ?>"><input class="flat" type="text" name="s<?php echo $searchfield['alias']; ?>" value=""></td>
	<?php } else if ($key == $num) { ?>
  			<td class="liste_titre" align="right">
  				<input type="image" class="liste_titre" name="button_search" src="<?php echo DOL_URL_ROOT; ?>/theme/<?php echo $conf->theme; ?>/img/search.png" alt="<?php echo $langs->trans('Search'); ?>">
  				<input type="image" class="liste_titre" name="button_removefilter" src="<?php echo DOL_URL_ROOT; ?>/theme/<?php echo $conf->theme; ?>/img/searchclear.png" alt="<?php echo $langs->trans('RemoveFilter'); ?>">
  			</td>
  	<?php } else { ?>
  			<td class="liste_titre">&nbsp;</td>
 	<?php } } } ?>
</tr>

<!-- FIELDS DATA -->

<?php
$var=True;
foreach($datas as $line) {
	$var=!$var;	?>
	<tr <?php echo $bc[$var]; ?>>
   		<?php
   		foreach($line as $key => $value) {
   			foreach($fieldlist as $field) {
   				if ($field['alias'] == $key) { ?>
   					<td align="<?php echo $field['align']; ?>"><?php echo $value; ?></td>
   		<?php } } } ?>
   	</tr>
<?php } ?>

</table>
</form>

<!-- END PHP TEMPLATE -->