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

require('../../main.inc.php');

$json = GETPOST('json', 'alpha');
$class = GETPOST('class', 'alpha');
$bServerSide = GETPOST('bServerSide', 'int');

/*
 * View
 */

top_httphead();

//print '<!-- Ajax page called with url '.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' -->'."\n";

if (!empty($json) && !empty($class)) {

    $result = dol_include_once("/" . $class . "/class/" . strtolower($class) . ".class.php");
    if (empty($result)) {
        dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php"); // Old version
    }

    $object = new $class($db);

    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array()
    );

    if ($bServerSide && $_GET['sSearch']) {
        if (isset($_GET['key']))
            $params['key'] = $_GET['key'];
        $params['limit'] = intval(empty($_GET['iDisplayLength']) ? $conf->view_limit : $_GET['iDisplayLength']);
        $params['q'] = $_GET['sSearch'] . "*";
        $params['skip'] = intval($_GET['iDisplayStart']);
        //'sort' => $_GET['mDataProp_'.$_GET['iSortCol_0']],
        //'stale'=> "ok"

        $result = $object->getIndexedView($json, $params);
    } else {
        if (isset($_GET['key']))
            $params['key'] = $_GET['key'];
        $params['limit'] = intval(empty($_GET['iDisplayLength']) ? $conf->view_limit : $_GET['iDisplayLength']);
        $params['skip'] = intval($_GET['iDisplayStart']);
        //'stale'=> "update_after"

        $result = $object->getView($json, $params);
        dol_setcache("total_rows", $result->total_rows);
    }

    if (empty($result->total_rows))
        $bServerSide = 0;

    //print_r($result);
    //error_log(json_encode($result));
    //exit;
    $output["iTotalRecords"] = dol_getcache("total_rows");
    $output["iTotalDisplayRecords"] = $result->total_rows;

    if (isset($result->rows))
        foreach ($result->rows AS $aRow) {
            unset($aRow->value->class);
            unset($aRow->value->_rev);
            $output["aaData"][] = clone $aRow->value;
            unset($aRow);
        }
    //error_log(json_encode($output));
    //sorting
    if ($bServerSide)
        $object->sortDatatable($output["aaData"], $_GET['mDataProp_' . $_GET['iSortCol_0']], $_GET['sSortDir_0']);

    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}
?>
