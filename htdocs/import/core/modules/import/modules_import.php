<?php

/* Copyright (C) 2005-2012	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin		<regis.houssin@capnetworks.com>
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
 * or see http://www.gnu.org/
 */

require_once DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php';

/**
 * 	Parent class for import file readers
 */
class ModeleImports {

    var $db;
    var $datatoimport;
    var $error = '';
    var $id;           // Id of driver
    var $label;        // Label of driver
    var $extension;    // Extension of files imported by driver
    var $version;      // Version of driver
    var $label_lib;    // Label of external lib used by driver
    var $version_lib;  // Version of external lib used by driver
    // Array of all drivers
    var $_driverlabel = array();
    var $_driverdesc = array();
    var $_driverversion = array();
    var $_liblabel = array();
    var $_libversion = array();

    /**
     *  Constructor
     */
    function __construct() {
        
    }

    /**
     *  Charge en memoire et renvoie la liste des modeles actifs
     *
     *  @param	DoliDB	$db     			Database handler
     *  @param  string	$maxfilenamelength  Max length of value to show
     *  @return	array						List of templates
     */
    function liste_modeles($db, $maxfilenamelength = 0) {
        dol_syslog(get_class($this) . "::liste_modeles");

        $dir = DOL_DOCUMENT_ROOT . "/import/core/modules/import/";
        $handle = opendir($dir);

        // Recherche des fichiers drivers imports disponibles
        $var = True;
        $i = 0;
        if (is_resource($handle)) {
            while (($file = readdir($handle)) !== false) {
                if (preg_match("/^import_(.*)\.modules\.php/i", $file, $reg)) {
                    $moduleid = $reg[1];

                    // Chargement de la classe
                    $file = $dir . "/import_" . $moduleid . ".modules.php";
                    $classname = "Import" . ucfirst($moduleid);

                    require_once $file;
                    $module = new $classname($db, '');

                    // Picto
                    $this->picto[$module->id] = $module->picto;
                    // Driver properties
                    $this->_driverlabel[$module->id] = $module->getDriverLabel('');
                    $this->_driverdesc[$module->id] = $module->getDriverDesc('');
                    $this->_driverversion[$module->id] = $module->getDriverVersion('');
                    // If use an external lib
                    $this->_liblabel[$module->id] = $module->getLibLabel('');
                    $this->_libversion[$module->id] = $module->getLibVersion('');

                    $i++;
                }
            }
        }

        return array_keys($this->_driverlabel);
    }

    /**
     *  Return picto of import driver
     *
     * 	@param	string	$key	Key
     * 	@return	string
     */
    function getPicto($key) {
        return $this->picto[$key];
    }

    /**
     *  Renvoi libelle d'un driver import
     *
     * 	@param	string	$key	Key
     * 	@return	string
     */
    function getDriverLabel($key) {
        return $this->_driverlabel[$key];
    }

    /**
     *  Renvoi la description d'un driver import
     *
     * 	@param	string	$key	Key
     * 	@return	string
     */
    function getDriverDesc($key) {
        return $this->_driverdesc[$key];
    }

    /**
     *  Renvoi version d'un driver import
     *
     * 	@param	string	$key	Key
     * 	@return	string
     */
    function getDriverVersion($key) {
        return $this->_driverversion[$key];
    }

    /**
     *  Renvoi libelle de librairie externe du driver
     *
     * 	@param	string	$key	Key
     * 	@return	string
     */
    function getLibLabel($key) {
        return $this->_liblabel[$key];
    }

    /**
     *  Renvoi version de librairie externe du driver
     *
     * 	@param	string	$key	Key
     * 	@return	string
     */
    function getLibVersion($key) {
        return $this->_libversion[$key];
    }

}

?>
