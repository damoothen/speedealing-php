<?php

/* Copyright (C) 2013      Herve Prot             <herve.prot@symeos.com>
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

/**
 *
 * @return number
 * @todo move this function outside install directory
 */
function upgrade() {
	global $user, $conf, $langs;

	if (!$user->admin)
		accessforbidden();

	$log = dol_getcache("mesgs");

	//Update modules configuration
	$object = new DolibarrModules($db);

	//Upgrade core files
	$object->upgradeCore();

	// Load Modules files
	$filename = array();
	$modules = array();
	$orders = array();
	$categ = array();
	$dirmod = array();
	$modNameLoaded = array();
	$mesg = $object->load_modules_files($filename, $modules, $orders, $categ, $dirmod, $modNameLoaded);

	//Update extrafields && Update views
	$result = $object->getView("list");
	foreach ($result->rows as $aRow) {
		if ($aRow->id == "module:User")
			$aRow->value->numero = 0;

		$object = new DolibarrModules($db);
		if (!empty($modules[$aRow->value->numero]) && $aRow->value->enabled) { // Test if module is present and enabled
			$objMod = $modules[$aRow->value->numero];

			foreach ($objMod as $key => $row)
				$object->$key = $row;

			$object->_id = "module:" . $objMod->name;
			$object->_rev = $aRow->value->_rev;
			$object->enabled = true;
			$object->record();
			$object->_load_documents();
		}
	}

	//Update dict
	$dict = new Dict($db);
	$result = $dict->getView("list");

	$dir = DOL_DOCUMENT_ROOT . "/install/couchdb/json/";
	foreach ($result->rows as $aRow) {
		try {
			$dict->load($aRow->key);
		} catch (Exception $e) {
			// Dict not in db
		}
		$filename = str_replace(':', '.', $aRow->key);
		$fp = fopen($dir . $filename . ".json", "r");
		if ($fp) {
			$json = fread($fp, filesize($dir . $filename . ".json"));
			$obj = json_decode($json);
			unset($obj->_rev);
		}

		if (is_object($obj->values)) {
			$found = false;
			$dict->_id = $obj->_id;
			foreach ($obj->values as $key => $row) {
				if (isset($dict->values->$key)) {
					$found = true;
					$enable = $dict->values->$key->enable;
				}
				$dict->values->$key = $row;
				if ($found)
					$dict->values->$key->enable = $enable;
				$found = false;
			}
			$dict->record();
		} else {

		}
	}

	// Put the new version in $conf
	$conf->global->MAIN_VERSION = DOL_VERSION;
	$conf->record();
	//Flush caches
	dol_flushcache();


	// All is ok;
	$error = new stdClass();
	$error->title = $langs->trans("UpgradeOk");

	$error->message = $langs->trans("NewInstalledVersion", $conf->global->MAIN_VERSION);
	$log[] = clone $error;
	dol_setcache("mesgs", $log);

	return 1;
}

?>