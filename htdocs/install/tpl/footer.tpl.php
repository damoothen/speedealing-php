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

<!-- BEGIN PHP TEMPLATE FOR WIZARD FOOTER -->

<!-- Scripts -->
<script src="<?php echo DOL_URL_ROOT; ?>/includes/jquery/js/jquery-latest.min.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/setup.js"></script>

<!-- Template functions -->
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.input.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.message.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.notify.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.progress-slider.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.scroll.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.tooltip.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/developr.wizard.js"></script>

<!-- jQuery Form Validation -->
<script src="<?php echo DOL_URL_ROOT; ?>/theme/symeos/js/libs/formValidator/jquery.validationEngine.js"></script>
<script src="<?php echo DOL_URL_ROOT; ?>/install/js/install.validation.js.php"></script>

<!-- Wizard process -->
<script src="<?php echo DOL_URL_ROOT; ?>/install/js/install.wizard.js"></script>

<script>
$(document).ready(function() {
	$('#selectlang').change(function() {
		var lang = $(this).val();
		window.location.replace('<?php echo $_SERVER['PHP_SELF']; ?>?selectlang=' + lang);
	});
	$('.wizard').wizard({
		controlPrev: '<button type="button" class="button glossy mid-margin-right wizard-prev float-left"><span class="button-icon anthracite-gradient"><span class="icon-backward"></span></span><?php echo $langs->trans("Previous"); ?></button>',
		controlNext: '<button type="button" class="button glossy mid-margin-right wizard-next float-right"><?php echo $langs->trans("Next"); ?><span class="button-icon right-side"><span class="icon-forward"></span></span></button>'
	});
});
</script>

</body>
</html>
<!-- END PHP TEMPLATE FOR WIZARD FOOTER -->