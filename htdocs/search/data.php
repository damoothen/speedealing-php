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

$sParam = $_GET['q'];
if (!$sParam)
    exit;

$result = $object->getIndexedView("list", array('limit' => 6,
    'q' => $sParam . "*"
        ));

//print_r($result);

if ($result->total_rows <= 0)
    exit;

if (isset($result->rows))
    foreach ($result->rows AS $aRow) {
        foreach ($aRow->value as $key => $aCol) {
            if ($key != "_id" && $key != "_rev" && is_string($aCol) && strpos(strtolower($aCol), strtolower($sParam)) !== false)
                echo $aCol . "\n";
        }
    }
?>