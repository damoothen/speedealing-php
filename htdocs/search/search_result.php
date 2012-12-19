<?php
/* Copyright (C) 2012			Herve Prot	<herve.prot@symeos.com>
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

$sParam = $_POST[search_item];

try {
    $result = $object->getIndexedView("list", array('limit' => 6,
        'q' => $sParam . "*"
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
        <ol><?php
    if (isset($result->rows))
        foreach ($result->rows AS $aRow) :
                ?><li>
                        <a href="societe/fiche.php?id=<?php echo $aRow->value->_id; ?>"><?php echo $aRow->value->name . " (" . $langs->trans($aRow->value->class) . ")"; ?></a>
                        <p><?php echo $aRow->value->address; ?> <?php echo $aRow->value->Zip; ?> <?php echo $aRow->value->Town; ?></br>
                            <?php echo "RCS : " . $aRow->value->idprof1; ?> <?php echo $aRow->value->CustomerCode; ?></br>
                            <?php echo $aRow->value->phone; ?> <?php echo $aRow->value->Mail; ?></p>
                    </li>
                <?php endforeach; ?>
        </ol>
    <?php } else { ?>
        Sorry no matches for <strong><?php echo $sParam; ?></strong>, please try some different term.
    <?php }; ?>
</div>
