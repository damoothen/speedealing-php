<?php
/* Copyright (C) 2012 Regis Houssin <regis@dolibarr.fr>
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

// Hide by default
$hide = (empty($object->extraparams[$blocname]['showhide']) ? true : false);

?>

<!-- BEGIN PHP TEMPLATE BLOC SHOW/HIDE -->

<script type="text/javascript">
$(document).ready(function() {
	$("#hide-<?php echo $blocname ?>").click(function(){
		setShowHide(0);
		$("#<?php echo $blocname ?>_bloc").hide("blind", {direction: "vertical"}, 800).removeClass("nohideobject");
		$(this).hide();
		$("#show-<?php echo $blocname ?>").show();
	});
	$("#show-<?php echo $blocname ?>").click(function(){
		setShowHide(1);
		$("#<?php echo $blocname ?>_bloc").show("blind", {direction: "vertical"}, 800).addClass("nohideobject");
		$(this).hide();
		$("#hide-<?php echo $blocname ?>").show();
	});
	function setShowHide(status) {
		var id			= <?php echo $object->id; ?>;
		var element		= '<?php echo $object->element; ?>';
		var htmlelement	= '<?php echo $blocname ?>';
		var type		= 'showhide';
		
		$.get("<?php echo dol_buildpath('/core/ajax/extraparams.php', 1); ?>?id="+id+"&element="+element+"&htmlelement="+htmlelement+"&type="+type+"&value="+status);
	}
});
</script>

<div style="float:right; position: relative; top: 3px; right:5px;" id="hide-<?php echo $blocname ?>" class="linkobject<?php echo ($hide ? ' hideobject' : ''); ?>"><?php echo img_picto('', '1uparrow.png'); ?></div>
<div style="float:right; position: relative; top: 3px; right:5px;" id="show-<?php echo $blocname ?>" class="linkobject<?php echo ($hide ? '' : ' hideobject'); ?>"><?php echo img_picto('', '1downarrow.png'); ?></div>
<div id="<?php echo $blocname ?>_title" class="liste_titre"><?php echo $title; ?></div>

<div id="<?php echo $blocname ?>_bloc" class="<?php echo ($hide ? 'hideobject' : 'nohideobject'); ?>">

<?php include DOL_DOCUMENT_ROOT.'/core/tpl/'.$blocname.'.tpl.php'; ?>

</div>
<br>

<!-- END PHP TEMPLATE BLOC SHOW/HIDE -->