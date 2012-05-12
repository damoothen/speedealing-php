<?php
/* Copyright (C) 2012 Regis Houssin <regis@dolibarr.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

<!-- BEGIN PHP TEMPLATE thirdparty update card -->
<script type="text/javascript">
$(document).ready(function () {
	$("#selectcountry_id").change(function() {
		document.formsoc.action.value="edit";
		document.formsoc.submit();
	});
})
</script>

<form class="nice" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"].'?id='.$this->id; ?>" method="POST" name="formsoc">
<input type="hidden" name="action" value="update">
<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>">
<input type="hidden" name="id" value="<?php echo $this->id; ?>">
<?php if ($this->auto_customer_code || $this->auto_supplier_code): ?>
<input type="hidden" name="code_auto" value="1">
<?php endif; ?>

<!-- Block Main -->
<div class="row sepH_b">
	<div class="two columns">
		<div class="form_legend">
			<h4>Personal details</h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pharetra metus non nisi feugiat porta&hellip;</p>
		</div>
	</div>
	<div class="ten columns">
		<div class="form_content"><!-- forms columns -->
			<div class="eight columns"><!-- center form -->
				<?php if ($this->Main['ThirdPartyName']['enable']): ?>
				<div class="formRow"><!-- ThirdPartyName -->
					<label for="ThirdPartyName"><?php echo $this->Main['ThirdPartyName']['trans']; ?></label>
					<input type="text" maxlength="<?php echo $this->Main['ThirdPartyName']['length']; ?>" id="ThirdPartyName" name="ThirdPartyName" value="<?php echo $this->Main['ThirdPartyName']['value']; ?>" class="input-text large" />
				</div>
				<?php endif; ?>
				<?php if ($this->Main['Address']['enable']): ?>
				<div class="formRow"><!-- Address -->
					<label for="Address"><?php echo $this->Main['Address']['trans']; ?></label>
					<textarea cols="<?php echo $this->Main['Address']['cols']; ?>" rows="<?php echo $this->Main['Address']['rows']; ?>" id="Address" name="Address" class="auto_expand expand"><?php echo $this->Main['Address']['value']; ?></textarea>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['Zip']['enable']): ?>
				<div class="formRow"><!-- Zip -->
					<label for="Zip"><?php echo $this->Main['Zip']['trans']; ?></label>
					<?php echo $this->select_zip; ?>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['Town']['enable']): ?>
				<div class="formRow"><!-- Town -->
					<label for="Town"><?php echo $this->Main['Town']['trans']; ?></label>
					<?php echo $this->select_town; ?>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['Country']['enable']): ?>
				<div class="formRow"><!-- Country -->
					<label for="Country"><?php echo $this->Main['Country']['trans']; ?>
					<?php if ($this->info_admin): ?>
					<?php echo $this->info_admin; ?>
					<?php endif; ?>
					</label>
					<?php echo $this->select_country; ?>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['State']['enable']): ?>
				<div class="formRow"><!-- State -->
					<label for="State"><?php echo $this->$this->Main['State']['trans']; ?>
					<?php if ($this->info_admin): ?>
					<?php echo $this->info_admin; ?>
					<?php endif; ?>
					</label>
					<?php echo $this->select_state; ?>
				</div>
				<?php endif; ?>
			</div><!-- end center form -->
			<div class="four columns"><!-- right form -->
				<?php if ($this->Main['CustomerCode']['enable']): ?>
				<div class="formRow"><!-- CustomerCode -->
					<label for="CustomerCode"><span class="ttip_l info" title="<?php echo $this->customer_code_tooltip; ?>"><?php echo $this->Main['CustomerCode']['trans']; ?></span></label>
					<?php if ($this->customer_code_changed): ?>
					<input type="text" maxlength="<?php echo $this->Main['CustomerCode']['length']; ?>" id="CustomerCode" name="CustomerCode" value="<?php echo $this->Main['CustomerCode']['value']; ?>" class="input-text small" />
					<?php else: ?>
					<?php echo $this->Main['CustomerCode']['value']; ?>
					<input type="hidden" id="CustomerCode" name="CustomerCode" value="<?php echo $this->Main['CustomerCode']['value']; ?>">
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['SupplierCode']['enable']): ?>
				<div class="formRow"><!-- SupplierCode -->
					<label for="SupplierCode"><span class="ttip_l info" title="<?php echo $this->supplier_code_tooltip; ?>"><?php echo $this->Main['SupplierCode']['trans']; ?></span></label>
					<?php if ($this->supplier_code_changed): ?>
					<input type="text" maxlength="<?php echo $this->Main['SupplierCode']['length']; ?>" id="SupplierCode" name="SupplierCode" value="<?php echo $this->Main['SupplierCode']['value']; ?>" class="input-text small" />
					<?php else: ?>
					<?php echo $this->Main['SupplierCode']['value']; ?>
					<input type="hidden" id="SupplierCode" name="SupplierCode" value="<?php echo $this->Main['SupplierCode']['value']; ?>" />
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['Status']['enable']): ?>
				<div class="formRow"><!-- Status -->
					<label for="Status"><?php echo $this->$this->Main['Status']['trans']; ?></label>
					<?php echo $this->select_status; ?>
				</div>
				<?php endif; ?>
				<?php if ($this->Main['Gencod']['enable']): ?>
				<div class="formRow"><!-- Gencod -->
					<label for="Gencod"><?php echo $this->Main['Gencod']['trans']; ?></label>
					<input type="hidden" id="Gencod" name="Gencod" value="<?php echo $this->Main['Gencod']['value']; ?>" />
				</div>
				<?php endif; ?>
			</div><!-- end right form -->
		</div><!-- end forms columns -->
	</div><!-- end ten columns -->
</div><!-- end row -->

<!-- Block AddressBook -->
<div class="row sepH_b">
	<div class="two columns">
		<div class="form_legend">
			<h4>Personal details</h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pharetra metus non nisi feugiat porta&hellip;</p>
		</div>
	</div>
	<div class="ten columns">
		<div class="form_content"><!-- forms columns -->
			<div class="six columns"><!-- center form -->
			<?php foreach ($this->AddressBook as $key => $aRow): ?>
				<?php if ($aRow['enable'] && $aRow['type'] != 'AC_URL'): ?>
				<div class="formRow">
					<label for="<?php echo $key; ?>"><?php echo $aRow['trans']; ?></label>
					<input type="text" maxlength="<?php echo $aRow['length']; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $aRow['value']; ?>" class="input-text medium" />
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div><!-- end center form -->
			<div class="six columns"><!-- right form -->
			<?php foreach ($this->AddressBook as $key => $aRow): ?>
				<?php if ($aRow['enable'] && $aRow['type'] == 'AC_URL'): ?>
				<div class="formRow">
					<label for="<?php echo $key; ?>"><?php echo $aRow['trans']; ?></label>
					<input type="text" maxlength="<?php echo $aRow['length']; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $aRow['value']; ?>" class="input-text medium" />
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div><!-- end right form -->
		</div><!-- end forms columns -->
	</div><!-- end ten columns -->
</div><!-- end row -->

<!-- Block Deal -->
<div class="row sepH_b">
	<div class="two columns">
		<div class="form_legend">
			<h4>Personal details</h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pharetra metus non nisi feugiat porta&hellip;</p>
		</div>
	</div>
	<div class="ten columns">
		<div class="form_content"><!-- forms columns -->
			<div class="six columns"><!-- center form -->
			<?php foreach ($this->Deal as $key => $aRow): ?>
				<?php if ($aRow['enable'] && $aRow['type'] == 'text'): ?>
				<div class="formRow">
					<label for="<?php echo $key; ?>"><?php echo $aRow['trans']; ?></label>
					<input type="text" maxlength="<?php echo $aRow['length']; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $aRow['value']; ?>" class="input-text medium" />
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div><!-- end center form -->
			<div class="six columns"><!-- right form -->
			<?php foreach ($this->Deal as $key => $aRow): ?>
				<?php if ($aRow['enable'] && $aRow['type'] != 'text'): ?>
				<div class="formRow">
					<label for="<?php echo $key; ?>"><?php echo $aRow['trans']; ?>
					<?php if ($this->info_admin): ?>
					<?php echo $this->info_admin; ?>
					<?php endif; ?>
					</label>
					<input type="text" maxlength="<?php echo $aRow['length']; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $aRow['value']; ?>" class="input-text medium" />
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div><!-- end right form -->
		</div><!-- end forms columns -->
	</div><!-- end ten columns -->
</div><!-- end row -->

<!-- Block Accounting -->
<div class="row sepH_b">
	<div class="two columns">
		<div class="form_legend">
			<h4>Personal details</h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pharetra metus non nisi feugiat porta&hellip;</p>
		</div>
	</div>
	<div class="ten columns">
		<div class="form_content"><!-- forms columns -->
			<div class="six columns"><!-- center form -->
			<?php foreach ($this->Accounting as $key => $aRow): ?>
				<?php if ($aRow['enable'] && $aRow['type'] == 'text'): ?>
				<div class="formRow">
					<label for="<?php echo $key; ?>"><?php echo $aRow['trans']; ?></label>
					<input type="text" maxlength="<?php echo $aRow['length']; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $aRow['value']; ?>" class="input-text medium" />
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div><!-- end center form -->
			<div class="six columns"><!-- right form -->
			<?php foreach ($this->Accounting as $key => $aRow): ?>
				<?php if ($aRow['enable'] && $aRow['type'] != 'text'): ?>
				<div class="formRow">
					<label for="<?php echo $key; ?>"><?php echo $aRow['trans']; ?>
					<?php if ($this->info_admin): ?>
					<?php echo $this->info_admin; ?>
					<?php endif; ?>
					</label>
					<input type="text" maxlength="<?php echo $aRow['length']; ?>" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $aRow['value']; ?>" class="input-text medium" />
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div><!-- end right form -->
		</div><!-- end forms columns -->
	</div><!-- end ten columns -->
</div><!-- end row -->

<!-- END PHP TEMPLATE thirdparty update card -->