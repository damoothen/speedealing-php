<?php

/* Copyright (C) 2005      Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2007 Regis Houssin        <regis.houssin@capnetworks.com>
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

require_once DOL_DOCUMENT_ROOT . '/core/class/commondocgenerator.class.php';

/**
 * 	Parent class for export modules
 */
class ModeleExports extends CommonDocGenerator {    // This class can't be abstract as there is instance propreties loaded by liste_modeles

    var $error = '';
    var $driverlabel = array();
    var $driverversion = array();
    var $liblabel = array();
    var $libversion = array();

    /**
     *  Load into memory list of available export format
     *
     *  @param	DoliDB	$db     			Database handler
     *  @param  string	$maxfilenamelength  Max length of value to show
     *  @return	array						List of templates
     */
    function liste_modeles($db, $maxfilenamelength = 0) {
        dol_syslog(get_class($this) . "::liste_modeles");

        $dir = DOL_DOCUMENT_ROOT . "/export/core/modules/export/";
        $handle = opendir($dir);

        // Recherche des fichiers drivers exports disponibles
        $var = True;
        $i = 0;
        if (is_resource($handle)) {
            while (($file = readdir($handle)) !== false) {
                if (preg_match("/^export_(.*)\.modules\.php$/i", $file, $reg)) {
                    $moduleid = $reg[1];

                    // Chargement de la classe
                    $file = $dir . "/export_" . $moduleid . ".modules.php";
                    $classname = "Export" . ucfirst($moduleid);

                    require_once $file;
                    $module = new $classname($db);

                    // Picto
                    $this->picto[$module->id] = $module->picto;
                    // Driver properties
                    $this->driverlabel[$module->id] = $module->getDriverLabel();
                    $this->driverdesc[$module->id] = $module->getDriverDesc();
                    $this->driverversion[$module->id] = $module->getDriverVersion();
                    // If use an external lib
                    $this->liblabel[$module->id] = $module->getLibLabel();
                    $this->libversion[$module->id] = $module->getLibVersion();

                    $i++;
                }
            }
            closedir($handle);
        }

        asort($this->driverlabel);

        return $this->driverlabel;
    }

    /**
     *  Return picto of export driver
     *
     *  @param	string	$key	Key of driver
     *  @return	string			Picto string
     */
    function getPicto($key) {
        return $this->picto[$key];
    }

    /**
     *  Renvoi libelle d'un driver export
     *
     *  @param	string	$key	Key of driver
     *  @return	string			Label
     */
    function getDriverLabel($key) {
        return $this->driverlabel[$key];
    }

    /**
     *  Renvoi le descriptif d'un driver export
     *
     *  @param	string	$key	Key of driver
     *  @return	string			Description
     */
    function getDriverDesc($key) {
        return $this->driverdesc[$key];
    }

    /**
     *  Renvoi version d'un driver export
     *
     *  @param	string	$key	Key of driver
     *  @return	string			Driver version
     */
    function getDriverVersion($key) {
        return $this->driverversion[$key];
    }

    /**
     *  Renvoi libelle de librairie externe du driver
     *
     *  @param	string	$key	Key of driver
     *  @return	string			Label of library
     */
    function getLibLabel($key) {
        return $this->liblabel[$key];
    }

    /**
     *  Renvoi version de librairie externe du driver
     *
     *  @param	string	$key	Key of driver
     *  @return	string			Version of library
     */
    function getLibVersion($key) {
        return $this->libversion[$key];
    }

}

?>
