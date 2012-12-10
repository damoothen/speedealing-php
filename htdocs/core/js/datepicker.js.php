<?php
/* Copyright (C) 2011 Regis Houssin         <regis@dolibarr.fr>
 * Copyright (C) 2011 Laurent Destailleur   <eldy@users.sourceforge.net>
 * Copyright (C) 2012 Herve Prot            <herve.prot@symeos.com>
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

//if (! defined('NOREQUIREUSER')) define('NOREQUIREUSER','1');	// Not disabled cause need to load personalized language
//if (! defined('NOREQUIREDB'))   define('NOREQUIREDB','1');
if (! defined('NOREQUIRESOC'))    define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN')) define('NOREQUIRETRAN','1');	// Not disabled cause need to do translations
if (! defined('NOCSRFCHECK'))     define('NOCSRFCHECK',1);
if (! defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL',1);
if (! defined('NOLOGIN'))         define('NOLOGIN',1);
if (! defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);
if (! defined('NOREQUIREHTML'))   define('NOREQUIREHTML',1);
if (! defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX','1');

session_cache_limiter(FALSE);

require_once '../../main.inc.php';

// Define javascript type
header('Content-type: text/javascript; charset=UTF-8');
// Important: Following code is to avoid page request by browser and PHP CPU at each Dolibarr page access.
if (empty($dolibarr_nocache)) header('Cache-Control: max-age=3600, public, must-revalidate');
else header('Cache-Control: no-cache');


// Define tradMonths javascript array (we define this in datepicker AND in parent page to avoid errors with IE8)
$tradMonths=array(
dol_escape_js($langs->transnoentitiesnoconv("January")),
dol_escape_js($langs->transnoentitiesnoconv("February")),
dol_escape_js($langs->transnoentitiesnoconv("March")),
dol_escape_js($langs->transnoentitiesnoconv("April")),
dol_escape_js($langs->transnoentitiesnoconv("May")),
dol_escape_js($langs->transnoentitiesnoconv("June")),
dol_escape_js($langs->transnoentitiesnoconv("July")),
dol_escape_js($langs->transnoentitiesnoconv("August")),
dol_escape_js($langs->transnoentitiesnoconv("September")),
dol_escape_js($langs->transnoentitiesnoconv("October")),
dol_escape_js($langs->transnoentitiesnoconv("November")),
dol_escape_js($langs->transnoentitiesnoconv("December"))
);

$tradMonthsShort=array(
$langs->trans("JanuaryMin"),
$langs->trans("FebruaryMin"),
$langs->trans("MarchMin"),
$langs->trans("AprilMin"),
$langs->trans("MayMin"),
$langs->trans("JuneMin"),
$langs->trans("JulyMin"),
$langs->trans("AugustMin"),
$langs->trans("SeptemberMin"),
$langs->trans("OctoberMin"),
$langs->trans("NovemberMin"),
$langs->trans("DecemberMin")
);

$tradDays=array(
$langs->trans("Sunday"),
$langs->trans("Monday"),
$langs->trans("Tuesday"),
$langs->trans("Wednesday"),
$langs->trans("Thursday"),
$langs->trans("Friday"),
$langs->trans("Saturday")
);

$tradDaysShort=array(
$langs->trans("ShortSunday"),
$langs->trans("ShortMonday"),
$langs->trans("ShortTuesday"),
$langs->trans("ShortWednesday"),
$langs->trans("ShortThursday"),
$langs->trans("ShortFriday"),
$langs->trans("ShortSaturday")
);

$tradDaysMin=array(
$langs->trans("SundayMin"),
$langs->trans("MondayMin"),
$langs->trans("TuesdayMin"),
$langs->trans("WednesdayMin"),
$langs->trans("ThursdayMin"),
$langs->trans("FridayMin"),
$langs->trans("SaturdayMin")
);
?>


// For eldy and jQuery date picker
var tradMonths = <?php echo json_encode($tradMonths) ?>;
var tradMonthsShort = <?php echo json_encode($tradMonthsShort) ?>;
var tradDays = <?php echo json_encode($tradDays) ?>;
var tradDaysShort = <?php echo json_encode($tradDaysShort) ?>;
var tradDaysMin = <?php echo json_encode($tradDaysMin) ?>;


// For JQuery date picker
$(document).ready(function() {
	$.datepicker.setDefaults({
		autoSize: true,
		changeMonth: true,
		changeYear: true,
		altField: '#timestamp',
		altFormat: '@'			// Gives a timestamp dateformat
	});
});

jQuery(function($){
	$.datepicker.regional['<?php echo $langs->defaultlang ?>'] = {
		closeText: '<?php echo $langs->trans("Close2") ?>',
		prevText: '<?php echo $langs->trans("Previous") ?>',
		nextText: '<?php echo $langs->trans("Next") ?>',
		currentText: '<?php echo $langs->trans("Now") ?>',
		monthNames: tradMonths,
		monthNamesShort: tradMonthsShort,
		dayNames: tradDays,
		dayNamesShort: tradDaysShort,
		dayNamesMin: tradDaysMin,
		weekHeader: '<?php echo $langs->trans("Week"); ?>',
		dateFormat: '<?php echo $langs->trans("FormatDateShortJQuery"); ?>',
		firstDay: <?php echo (isset($conf->global->MAIN_START_WEEK)?$conf->global->MAIN_START_WEEK:'1'); ?>,
		isRTL: <?php echo ($langs->trans("DIRECTION")=='rtl'?'true':'false'); ?>,
		showMonthAfterYear: false,  // TODO add specific to country
		yearSuffix: ''				// TODO add specific to country
	};
	$.datepicker.setDefaults($.datepicker.regional['<?php echo $langs->defaultlang ?>']);
});


<?php
if (is_object($db)) $db->close();
?>
