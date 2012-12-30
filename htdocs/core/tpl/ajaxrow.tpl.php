<?php
/* Copyright (C) 2010-2012 Regis Houssin       <regis.houssin@capnetworks.com>
 * Copyright (C) 2010-2012 Laurent Destailleur <eldy@users.sourceforge.net>
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
 * Javascript code to activate drag and drop on lines
 */
?>

<!-- BEGIN PHP TEMPLATE FOR JQUERY -->
<?php
$id=$object->id;
$fk_element=$object->fk_element;
$table_element_line=$object->table_element_line;
$nboflines=(isset($object->lines)?count($object->lines):(isset($tasksarray)?count($tasksarray):0));
$forcereloadpage=$conf->global->MAIN_FORCE_RELOAD_PAGE;

if (GETPOST('action') != 'editline' && $nboflines > 1) { ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".imgup").hide();
	$(".imgdown").hide();
    $(".lineupdown").removeAttr('href');
    $(".tdlineupdown").css("background-image",'url(<?php echo DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/grip.png'; ?>)');
    $(".tdlineupdown").css("background-repeat","no-repeat");
    $(".tdlineupdown").css("background-position","center center");

    $("#tablelines").tableDnD({
		onDrop: function(table, row) {
			var reloadpage = "<?php echo $forcereloadpage; ?>";
			var roworder = cleanSerialize($("#tablelines").tableDnDSerialize());
			var table_element_line = "<?php echo $table_element_line; ?>";
			var fk_element = "<?php echo $fk_element; ?>";
			var element_id = "<?php echo $id; ?>";
			$.post("<?php echo DOL_URL_ROOT; ?>/core/ajax/row.php",
					{
						roworder: roworder,
						table_element_line: table_element_line,
						fk_element: fk_element,
						element_id: element_id
					},
					function() {
						if (reloadpage == 1) {
							location.href = '<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>';
						} else {
							$("#tablelines .drag").each(
									function( intIndex ) {
										$(this).removeClass("pair impair");
										if (intIndex % 2 == 0) $(this).addClass('impair');
										if (intIndex % 2 == 1) $(this).addClass('pair');
									});
						}
					});
		},
		onDragClass: "dragClass",
		dragHandle: "tdlineupdown"
	});
    $(".tdlineupdown").hover( function() { $(this).addClass('showDragHandle'); },
    	function() { $(this).removeClass('showDragHandle'); }
    );
});
</script>
<?php } else { ?>
<script>
$(document).ready(function(){
	$(".imgup").hide();
	$(".imgdown").hide();
    $(".lineupdown").removeAttr('href');
});
</script>
<?php } ?>
<!-- END PHP TEMPLATE -->