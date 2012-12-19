<?php
/* Copyright (C) 2011	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2011	Dimitri Mouillard	<dmouillard@teclib.com>
 * Copyright (C) 2012	Regis Houssin		<regis@dolibarr.fr>
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

/**
 *   	\file       htdocs/holiday/common.inc.php
 *		\ingroup    holiday
 *		\brief      Common load of data
 */

require_once realpath(dirname(__FILE__)) . '/../main.inc.php';
if (! class_exists('Holiday')) {
	require DOL_DOCUMENT_ROOT. '/holiday/class/holiday.class.php';
}

$langs->load("user");
$langs->load("other");
$langs->load("holiday");

if (empty($conf->holiday->enabled))
{
    llxHeader('',$langs->trans('CPTitreMenu'));
    print '<div class="tabBar">';
    print '<span style="color: #FF0000;">'.$langs->trans('NotActiveModCP').'</span>';
    print '</div>';
    llxFooter();
    exit();
}


$verifConf.= "SELECT value";
$verifConf.= " FROM ".MAIN_DB_PREFIX."holiday_config";
$verifConf.= " WHERE name = 'userGroup'";

$result = $db->query($verifConf);
$obj = $db->fetch_object($result);

if($obj->value == NULL)
{
    llxHeader('',$langs->trans('CPTitreMenu'));
    print '<div class="tabBar">';
    print '<span style="color: #FF0000;">'.$langs->trans('NotConfigModCP').'</span>';
    print '</div>';
    llxFooter();
    exit();
}

?>