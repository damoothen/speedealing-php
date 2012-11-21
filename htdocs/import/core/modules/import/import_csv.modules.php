<?php

/* Copyright (C) 2006-2012	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2009-2012	Regis Houssin		<regis@dolibarr.fr>
 * Copyright (C) 2012      Christophe Battarel  <christophe.battarel@altairis.fr>
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
 * or see http://www.gnu.org/
 */

require_once DOL_DOCUMENT_ROOT . '/import/core/modules/import/modules_import.php';

/**
 * 	Class to import CSV files
 */
class ImportCsv extends ModeleImports {

    var $db;
    var $datatoimport;
    var $error = '';
    var $errors = array();
    var $id;           // Id of driver
    var $label;        // Label of driver
    var $extension;    // Extension of files imported by driver
    var $version;      // Version of driver
    var $label_lib;    // Label of external lib used by driver
    var $version_lib;  // Version of external lib used by driver
    var $separator;
    var $handle;    // Handle fichier
    var $cacheconvert = array();      // Array to cache list of value found after a convertion
    var $cachefieldtable = array();   // Array to cache list of value found into fields@tables

    /**
     * 	Constructor
     *
     * 	@param	DoliDB		$db				Database handler
     * 	@param	string		$datatoimport	String code describing import set (ex: 'societe_1')
     */

    function __construct($db, $datatoimport) {
        global $conf, $langs;
        $this->db = $db;

        $this->separator = ','; // Change also function cleansep
        if (!empty($conf->global->IMPORT_CSV_SEPARATOR_TO_USE))
            $this->separator = $conf->global->IMPORT_CSV_SEPARATOR_TO_USE;
        $this->enclosure = '"';
        $this->escape = '"';

        $this->id = 'csv';                // Same value then xxx in file name export_xxx.modules.php
        $this->label = 'Csv';             // Label of driver
        $this->desc = $langs->trans("CSVFormatDesc", $this->separator, $this->enclosure, $this->escape);
        $this->extension = 'csv';         // Extension for generated file by this driver
        $this->picto = 'mime/other';  // Picto
        $this->version = '1.34';         // Driver version
        // If driver use an external library, put its name here
        $this->label_lib = 'Dolibarr';
        $this->version_lib = DOL_VERSION;

        $this->datatoimport = $datatoimport;
        if (preg_match('/^societe_/', $datatoimport))
            $this->thirpartyobject = new Societe($this->db);
    }

    /**
     * getDriverId
     *
     * @return int		Id
     */
    function getDriverId() {
        return $this->id;
    }

    /**
     * 	getDriverLabel
     *
     * 	@param	string	$key	Key
     * 	@return string	Label
     */
    function getDriverLabel($key = '') {
        return $this->label;
    }

    /**
     * 	getDriverDesc
     *
     * 	@param	string	$key	Key
     * 	@return string	Description
     */
    function getDriverDesc($key = '') {
        return $this->desc;
    }

    /**
     * getDriverExtension
     *
     * @return string	Driver suffix
     */
    function getDriverExtension() {
        return $this->extension;
    }

    /**
     * 	getDriverVersion
     *
     * 	@param	string	$key	Key
     * 	@return string	Driver version
     */
    function getDriverVersion($key = '') {
        return $this->version;
    }

    /**
     * 	getDriverLabel
     *
     * 	@param	string	$key	Key
     * 	@return string	Label of external lib
     */
    function getLibLabel($key = '') {
        return $this->label_lib;
    }

    /**
     * getLibVersion
     *
     * 	@param	string	$key	Key
     * 	@return string	Version of external lib
     */
    function getLibVersion($key = '') {
        return $this->version_lib;
    }

    /**
     * 	Output header of an example file for this format
     *
     * 	@param	Translate	$outputlangs		Output language
     *  @return	string
     */
    function write_header_example($outputlangs) {
        return '';
    }

    /**
     * 	Output title line of an example file for this format
     *
     * 	@param	Translate	$outputlangs		Output language
     *  @param	array		$headerlinefields	Array of fields name
     * 	@return	string
     */
    function write_title_example($outputlangs, $headerlinefields) {
        $s.=join($this->separator, array_map('cleansep', $headerlinefields));
        return $s . "\n";
    }

    /**
     * 	Output record of an example file for this format
     *
     * 	@param	Translate	$outputlangs		Output language
     * 	@param	array		$contentlinevalues	Array of lines
     * 	@return	string
     */
    function write_record_example($outputlangs, $contentlinevalues) {
        $s = join($this->separator, array_map('cleansep', $contentlinevalues));
        return $s . "\n";
    }

    /**
     * 	Output footer of an example file for this format
     *
     * 	@param	Translate	$outputlangs		Output language
     *  @return	string
     */
    function write_footer_example($outputlangs) {
        return '';
    }

    /**
     * 	Open input file
     *
     * 	@param	string	$file		Path of filename
     * 	@return	int					<0 if KO, >=0 if OK
     */
    function import_open_file($file) {
        global $langs;
        $ret = 1;

        dol_syslog(get_class($this) . "::open_file file=" . $file);

        ini_set('auto_detect_line_endings', 1); // For MAC compatibility

        $this->handle = fopen(dol_osencode($file), "r");
        if (!$this->handle) {
            $langs->load("errors");
            $this->error = $langs->trans("ErrorFailToOpenFile", $file);
            $ret = -1;
        } else {
            $this->file = $file;
        }

        return $ret;
    }

    /**
     * 	Input header line from file
     *
     * 	@return		int		<0 if KO, >=0 if OK
     */
    function import_read_header() {
        return 0;
    }

    /**
     * 	Return array of next record in input file.
     *
     * 	@return		Array		Array of field values. Data are UTF8 encoded. [fieldpos] => (['val']=>val, ['type']=>-1=null,0=blank,1=string)
     */
    function import_read_record() {
        global $conf;

        $arrayres = array();
        if (version_compare(phpversion(), '5.3') < 0) {
            $arrayres = fgetcsv($this->handle, 100000, $this->separator, $this->enclosure);
        } else {
            $arrayres = fgetcsv($this->handle, 100000, $this->separator, $this->enclosure, $this->escape);
        }

        //var_dump($this->handle);
        //var_dump($arrayres);exit;
        $newarrayres = array();
        if ($arrayres && is_array($arrayres)) {
            foreach ($arrayres as $key => $val) {
                if (!empty($conf->global->IMPORT_CSV_FORCE_CHARSET)) { // Forced charset
                    if (strtolower($conf->global->IMPORT_CSV_FORCE_CHARSET) == 'utf8') {
                        $newarrayres[$key]['val'] = $val;
                        $newarrayres[$key]['type'] = (dol_strlen($val) ? 1 : -1); // If empty we considere it's null
                    } else {
                        $newarrayres[$key]['val'] = utf8_encode($val);
                        $newarrayres[$key]['type'] = (dol_strlen($val) ? 1 : -1); // If empty we considere it's null
                    }
                } else { // Autodetect format (UTF8 or ISO)
                    if (utf8_check($val)) {
                        $newarrayres[$key]['val'] = $val;
                        $newarrayres[$key]['type'] = (dol_strlen($val) ? 1 : -1); // If empty we considere it's null
                    } else {
                        $newarrayres[$key]['val'] = utf8_encode($val);
                        $newarrayres[$key]['type'] = (dol_strlen($val) ? 1 : -1); // If empty we considere it's null
                    }
                }
            }

            $this->col = count($newarrayres);
        }

        return $newarrayres;
    }

    /**
     * 	Close file handle
     *
     *  @return	void
     */
    function import_close_file() {
        fclose($this->handle);
        return 0;
    }

}

/**
 * 	Clean a string from separator
 *
 * 	@param	string	$value	Remove separator
 * 	@return	string			String without separator
 */
function cleansep($value) {
    return str_replace(',', '/', $value);
}

;
?>
