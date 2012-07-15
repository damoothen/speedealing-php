<?php
/* Copyright (C) 2012			Herve Prot	<herve.prot@symeos.com>
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
 */

if (!defined('NOTOKENRENEWAL'))
	define('NOTOKENRENEWAL', '1'); // Disables token renewal
if (!defined('NOREQUIREMENU'))
	define('NOREQUIREMENU', '1');
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');
if (!defined('NOREQUIREAJAX'))
	define('NOREQUIREAJAX', '1');
if (!defined('NOREQUIRESOC'))
	define('NOREQUIRESOC', '1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');

require('../main.inc.php');
require_once(DOL_DOCUMENT_ROOT . "/search/class/search.class.php");

$object = new Search($db);
$object->useDatabase('societe');

$sParam = $_POST[search_item];

try {
	$result = $object->getIndexedView("list", array('limit' => 6,
		'q' => $sParam ."*"
			));

	$found = false;

	if (isset($result->rows))
		foreach ($result->rows AS $rowid => $aRow) {
			foreach ($aRow->value as $key => $aCol) {
				if ($key != "_id" && $key != "_rev" && is_string($aCol) && strpos(strtolower($aCol), strtolower($sParam)) !== false)
					$found = true;
			}
			if (!$found) {
				unset($result->rows[$rowid]);
				$result->total_rows--;
			}
			$found = false;
		}

	if ($result->total_rows)
		$valuefounded = true;
} catch (Exception $e) {
	
}
?>

<div class="search_results search_pop">
	<?php if ($valuefounded == true) { ?>
		<h5 class="sepH_b">Showing <?php echo $result->total_rows; ?> results for <mark><?php echo $sParam; ?></mark></h5>
		<ol>
			<?php
			if (isset($result->rows))
				foreach ($result->rows AS $aRow) :
					?>
					<li>
						<a href="societe/fiche.php?id=<?php echo $aRow->value->_id;?>"><?php echo $aRow->value->ThirdPartyName . " (" . $langs->trans($aRow->value->class) . ")"; ?></a>
						<p><?php echo $aRow->value->Address; ?> <?php echo $aRow->value->Zip; ?> <?php echo $aRow->value->Town; ?></br>
							<?php echo "RCS : ".$aRow->value->SIREN; ?> <?php echo $aRow->value->CustomerCode; ?></br>
							<?php echo $aRow->value->Phone; ?> <?php echo $aRow->value->Mail; ?></p>
					</li>
				<?php endforeach; ?>
		</ol>
	<?php } else { ?>
		Sorry no matches for <strong><?php echo $sParam; ?></strong>, please try some different term.
	<?php }; ?>
</div>
