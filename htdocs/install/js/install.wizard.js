// Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program. If not, see <http://www.gnu.org/licenses/>.
// or see http://www.gnu.org/
//

$(document).ready(function() {
	// Elements
	var form = $('.wizard'),

	// If layout is centered
	centered;

	// Handle resizing (mostly for debugging)
	function handleWizardResize() {
		centerWizard(false);
	};

	// Register and first call
	$(window).bind('normalized-resize', handleWizardResize);

	/*
	 * Center function
	 * @param boolean animate whether or not to animate the position change
	 * @return void
	 */
	function centerWizard(animate) {
		form[animate ? 'animate' : 'css']({ marginTop: Math.max(0, Math.round(($.template.viewportHeight-30-form.outerHeight())/2))+'px' });
	};

	// Initial vertical adjust
	centerWizard(false);

	// Refresh position on change step
	form.on('wizardchange', function() { centerWizard(true); });

	// Validation
	if ($.validationEngine) {
		form.validationEngine();
	}

	$('.wizard fieldset').on('wizardleave', function() {
		// Called everytime a step (fieldset) becomes the active one
		var step = $(this).attr('id');
	});

	$('.wizard fieldset').on('wizardenter', function() {
		// Called everytime a step (fieldset) becomes the active one
		var step = $(this).attr('id');
		if (step == 'welcome') {
			$('.wizard-next').show();
		} else if (step == 'prerequisite') {
			$('.wizard-next').hide();
			$('#reload_required').hide();
			$('#reload_button').hide();
			// Check prerequisites
			ckeckPrerequisite();
		} else if (step == 'install') {
			$('#add_conf').progress({style: 'large'}).showProgressStripes();
			//$('#add_syncuser').progress({style: 'large'}).showProgressStripes();
			$('#add_database').progress({style: 'large'}).showProgressStripes();
			//$('#sync_database').progress({style: 'large'}).showProgressStripes();
		}
	});

	$('#reload_button').click(function() {
		ckeckPrerequisite();
	});

	function ckeckPrerequisite() {
		// Add loader
		$('#php_version, #php_memory, #php_utf8, #php_gd, #php_curl, #php_memcached, #conf_file').html('<span class="loader"></span>');
		// Check prerequisites
		$.getJSON('ajax/prerequisite.php', { action: 'check_prerequisite', lang: $('#selectlang').val() }, function(data) {
			if (data) {
				$.each(data, function(key, value) {
					if (key == 'continue') {
						if (value == true) {
							$('#reload_button').hide();
							$('#reload_required').hide();
							$('.wizard-next').show();
						}
						else {
							$('#reload_required').show();
							$('#reload_button').show();
						}
					} else {
						$('#' + key).html(value);
					}
				});
			}
		});
	}
});