<?php
/* Copyright (C) 2011-2012 Regis Houssin <regis@dolibarr.fr>
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

<!-- START TEMPLATE FILE UPLOAD MAIN -->
<script type="text/javascript">
window.locale = {
    "fileupload": {
        "errors": {
            "maxFileSize": "<?php echo $langs->trans('FileIsTooBig'); ?>",
            "minFileSize": "<?php echo $langs->trans('FileIsTooSmall'); ?>",
            "acceptFileTypes": "<?php echo $langs->trans('FileTypeNotAllowed'); ?>",
            "maxNumberOfFiles": "<?php echo $langs->trans('MaxNumberOfFilesExceeded'); ?>",
            "uploadedBytes": "<?php echo $langs->trans('UploadedBytesExceedFileSize'); ?>",
            "emptyResult": "<?php echo $langs->trans('EmptyFileUploadResult'); ?>"
        },
        "error": "<?php echo $langs->trans('Error'); ?>",
        "start": "<?php echo $langs->trans('Start'); ?>",
        "cancel": "<?php echo $langs->trans('Cancel'); ?>",
        "destroy": "<?php echo $langs->trans('Delete'); ?>"
    }
};

$(function () {
	'use strict';

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload();

	// Events
	$('#fileupload').fileupload({
		completed: function (e, data) {
			location.href='<?php echo $_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"]; ?>';
		},
		destroy: function (e, data) {
			var that = $(this).data('fileupload');
			$( "#confirm-delete" ).dialog({
				resizable: false,
				width: 400,
				modal: true,
				buttons: {
					"<?php echo $langs->trans('Ok'); ?>": function() {
						$( "#confirm-delete" ).dialog( "close" );
						if (data.url) {
							$.ajax(data)
								.success(function (data) {
									if (data) {
										that._adjustMaxNumberOfFiles(1);
										$(this).fadeOut(function () {
											$(this).remove();
											$.jnotify("<?php echo $langs->trans('FileIsDelete'); ?>");
										});
									} else {
										$.jnotify("<?php echo $langs->trans('ErrorFileNotDeleted'); ?>", "error", true);
									}
								});
						} else {
							data.context.fadeOut(function () {
								$(this).remove();
							});
						}
					},
					"<?php echo $langs->trans('Cancel'); ?>": function() {
						$( "#confirm-delete" ).dialog( "close" );
					}
				}
			});
		}
	});
});
</script>
<!-- END TEMPLATE FILE UPLOAD MAIN -->