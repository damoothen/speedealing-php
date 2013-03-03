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
 */

if (! defined('NOREQUIRESOC'))    define('NOREQUIRESOC','1');
if (! defined('NOCSRFCHECK'))     define('NOCSRFCHECK',1);
if (! defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL',1);
if (! defined('NOREQUIREMENU'))   define('NOREQUIREMENU',1);
if (! defined('NOREQUIREHTML'))   define('NOREQUIREHTML',1);
if (! defined('NOREQUIREAJAX'))   define('NOREQUIREAJAX','1');

session_cache_limiter(FALSE);

require '../../main.inc.php';

// Define javascript type
header('Content-type: text/javascript; charset=UTF-8');
// Important: Following code is to avoid page request by browser and PHP CPU at each Dolibarr page access.
if (empty($dolibarr_nocache)) header('Cache-Control: max-age=3600, public, must-revalidate');
else header('Cache-Control: no-cache');

?>

{
	"sProcessing":		"<?php echo $langs->trans('Processing'); ?>",
	"sLengthMenu":		"<?php echo $langs->trans('Show'); ?> _MENU_ <?php echo $langs->trans('Entries'); ?>",
	"sSearch":			"<?php echo $langs->trans('Search'); ?>:",
	"sZeroRecords":		"<?php echo $langs->trans('NoRecordsToDisplay'); ?>",
	"sInfoEmpty":		"<?php echo $langs->trans('NoEntriesToShow'); ?>",
	"sInfoFiltered":	"(<?php echo $langs->trans('FilteredFrom'); ?> _MAX_ <?php echo $langs->trans('TotalEntries'); ?>)",
	"sInfo":			"<?php echo $langs->trans('Showing'); ?> _START_ <?php echo $langs->trans('To'); ?> _END_ <?php echo $langs->trans('Of'); ?> _TOTAL_ <?php echo $langs->trans('Entries'); ?>",
	"sInfoPostFix":		"",
	"sUrl":				"",
	"oPaginate": {
		"sFirst":		"<?php echo $langs->transnoentities('First'); ?>",
		"sLast":		"<?php echo $langs->transnoentities('Last'); ?>",
		"sPrevious":	"<?php echo $langs->transnoentities('Previous'); ?>",
		"sNext":		"<?php echo $langs->transnoentities('Next'); ?>"
	}
}