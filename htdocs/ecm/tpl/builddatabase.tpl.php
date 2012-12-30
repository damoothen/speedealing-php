<?php
/* Copyright (C) 2012	Regis Houssin	<regis.houssin@capnetworks.com>
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

$openeddir='/';

// TODO: just use ajaxdirtree.php for load database after ajax refresh and not scan directories
// too slow every page loaded !

?>

<!-- BEGIN PHP TEMPLATE FOR JQUERY -->
<script type="text/javascript">
$(document).ready( function() {
	$('#filetree').fileTree({ root: '<?php print dol_escape_js($openeddir); ?>',
			// Called if we click on a file (not a dir)
			script: '<?php echo DOL_URL_ROOT.'/core/ajax/ajaxdirtree.php?modulepart=ecm&openeddir='.urlencode($openeddir); ?>',
			folderEvent: 'click',
			multiFolder: false  },
			// Called if we click on a file (not a dir)
		function(file) {
			$("#mesg").hide();
			loadandshowpreview(file,0);
		}
	);

	$('#refreshbutton').click( function() {
		ecmBuildDatabase();
	});
});

function loadandshowpreview(filedirname,section)
{
	//alert('filedirname='+filedirname);
	$('#ecmfileview').empty();

	var url = '<?php echo dol_buildpath('/core/ajax/ajaxdirpreview.php',1); ?>?action=preview&module=ecm&section='+section+'&file='+urlencode(filedirname);
	$.get(url, function(data) {
		//alert('Load of url '+url+' was performed : '+data);
		pos=data.indexOf("TYPE=directory",0);
		//alert(pos);
		if ((pos > 0) && (pos < 20))
		{
			filediractive=filedirname;    // Save current dirname
			filetypeactive='directory';
		}
		else
		{
			filediractive=filedirname;    // Save current dirname
			filetypeactive='file';
		}
		$('#ecmfileview').append(data);
	});
}

ecmBuildDatabase = function() {
	$.pleaseBePatient("<?php echo $langs->trans('PleaseBePatient'); ?>");
	$.getJSON( "<?php echo DOL_URL_ROOT . '/ecm/ajax/ecmdatabase.php'; ?>", {
		action: "build",
		element: "ecm"
	},
	function(response) {
		$.unblockUI();
		location.href="<?php echo $_SERVER['PHP_SELF']; ?>";
	});
};
</script>
<!-- END PHP TEMPLATE FOR JQUERY -->