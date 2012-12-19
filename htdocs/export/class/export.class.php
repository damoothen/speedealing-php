<?php

/* Copyright (C) 2005-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2011 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2011-2012 Herve Prot           <herve.prot@symeos.com>
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

class Export {

    var $db;
    var $array_export_code = array();             // Tableau de "idmodule_numlot"
    var $array_export_module = array();           // Tableau de "nom de modules"
    var $array_export_label = array();            // Tableau de "libelle de lots"
    var $array_export_sql = array();              // Tableau des "requetes sql"
    var $array_export_fields = array();           // Tableau des listes de champ+libelle a exporter
    var $array_export_entities = array();         // Tableau des listes de champ+alias a exporter
    var $array_export_dependencies = array();     // array of list of entities that must take care of the DISTINCT if a field is added into export
    var $array_export_special = array();          // Tableau des operations speciales sur champ
    // To store export modules
    var $hexa;
    var $datatoexport;
    var $model_name;
    var $sqlusedforexport;

    /**
     *    Constructor
     *
     *    @param  	DoliDB		$db		Database handler
     */
    function __construct($db) {
        $this->db = $db;
    }

    /**
     *    Load an exportable dataset
     *
     *    @param  	User		$user      	Object user making export
     *    @param  	string		$filter    	Load a particular dataset only
     *    @return	int						<0 if KO, >0 if OK
     */
    function load_arrays($user, $filter = '') {
        global $langs, $conf, $mysoc;

        dol_syslog(get_class($this) . "::load_arrays user=" . $user->id . " filter=" . $filter);

        $var = true;
        $i = 0;

        $modules = new DolibarrModules($this->db);
        $result = $modules->getView("list_export");

        foreach ($result->rows as $key => $aRow) {
            if ($filter && ($filter != $aRow->key[1]))
                continue;

            // Load lang file
            $langtoload = $aRow->value->langfiles;
            if (is_array($langtoload)) {
                foreach ($langtoload as $key) {
                    $langs->load($key);
                }
            }

            // Test if permissions are ok
            $bool = verifCond($aRow->permission);
            if (!$bool)
                break;

            // Permission
            $this->array_export_perms[$i] = $bool;
            // Icon
            $this->array_export_icon[$i] = $aRow->value->icon;
            // Code du dataset export
            $this->array_export_code[$i] = $aRow->key[1];
            // Libelle du dataset export
            $langstring = "ExportDataset_" . $aRow->key[1];
            //print "x".$langstring;
            if ($langs->trans($langstring) == $langstring) {
                // Traduction non trouvee
                $this->array_export_label[$i] = $langs->trans($aRow->value->label);
            } else {
                // Traduction trouvee
                $this->array_export_label[$i] = $langs->trans($langstring);
            }
            
            //print_r($aRow->value);exit;
            
            // Class
            if(isset($aRow->value->class))
                $this->array_export_class[$i] = $aRow->value->class;
            else
                $this->array_export_class[$i] = $aRow->key[0];

            // Module
            $class = $this->array_export_class[$i];
            $this->array_export_module[$i] = new DolibarrModules($this->db);
            $this->array_export_module[$i]->load("module:" . $aRow->key[0]);

            dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");
            $object = new $class($this->db);

            $this->array_export_fields[$i]["_id"] = "_id";
            $this->array_export_fields[$i]["_rev"] = "_rev";
            
            foreach ($object->fk_extrafields->fields as $idx => $row) {
                if ($row->enable && $row->type != "uploadfile")
                    if($row->class) {
                        $this->array_export_fields[$i][$idx."->name"] = $idx."->name";
                        $this->array_export_fields[$i][$idx."->id"] = $idx."->id";
                    }
                    else
                        $this->array_export_fields[$i][$idx] = $idx;
            }

            dol_syslog(get_class($this) . "::load_arrays loaded for module " . $modulename . " with index " . $i . ", dataset=" . $module->export_code[$r] . ", nb of fields=" . count($module->export_fields_code[$r]));
            $i++;
        }

        return 1;
    }

    /**
     *      Build the sql export request.
     *      Arrays this->array_export_xxx are already loaded for required datatoexport
     *
     *      @param      int		$indice				Indice of export
     *      @param      array	$array_selected     Filter on array of fields to export
     *      @return		string						SQL String. Example "select s.rowid as r_rowid, s.status as s_status from ..."
     */
    function build_sql($indice, $array_selected) {
        // Build the sql request
        $sql = $this->array_export_sql_start[$indice];
        $i = 0;

        //print_r($array_selected);
        foreach ($this->array_export_fields[$indice] as $key => $value) {
            if (!array_key_exists($key, $array_selected))
                continue;  // Field not selected

            if ($i > 0)
                $sql.=', ';
            else
                $i++;
            $newfield = $key . ' as ' . str_replace(array('.', '-'), '_', $key);
            ;

            $sql.=$newfield;
        }
        $sql.=$this->array_export_sql_end[$indice];

        return $sql;
    }

    /**
     *      Build export file.
     *      File is built into directory $conf->export->dir_temp.'/'.$user->id
     *      Arrays this->array_export_xxx are already loaded for required datatoexport
     *
     *      @param      User		$user               User that export
     *      @param      string		$model              Export format
     *      @param      string		$datatoexport       Name of dataset to export
     *      @param      array		$array_selected     Filter on array of fields to export
     *      @param		string		$sqlquery			If set, transmit a sql query instead of building it from arrays
     *      @return		int								<0 if KO, >0 if OK
     */
    function build_file($user, $model, $datatoexport, $array_selected, $sqlquery = '') {
        global $conf, $langs;

        $indice = 0;
        asort($array_selected);

        dol_syslog("Export::build_file $model, $datatoexport, $array_selected");

        // Check parameters or context properties
        if (!is_array($this->array_export_fields[$indice])) {
            $this->error = "ErrorBadParameter";
            return -1;
        }

        // Creation de la classe d'export du model ExportXXX
        $dir = DOL_DOCUMENT_ROOT . "/export/core/modules/export/";
        $file = "export_" . $model . ".modules.php";
        $classname = "Export" . $model;
        require_once $dir . $file;
        $objmodel = new $classname($this->db);

        $class = $this->array_export_class[$indice];
        dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");

        $object = new $class($this->db);
        $result = $object->getView("list");

        /*        if ($sqlquery)
          $sql = $sqlquery;
          else
          $sql = $this->build_sql($indice, $array_selected);

          // Run the sql
          $this->sqlusedforexport = $sql;
          dol_syslog("Export::build_file sql=" . $sql);
          $resql = $this->db->query($sql); */
        if (count($result->rows)) {
            //$this->array_export_label[$indice]
            $filename = "export_" . $datatoexport;
            $filename.='.' . $objmodel->getDriverExtension();
            $dirname = $conf->export->dir_temp . '/' . $user->id;

            $outputlangs = dol_clone($langs); // We clone to have an object we can modify (for example to change output charset by csv handler) without changing original value
            // Open file
            dol_mkdir($dirname);
            $result_file = $objmodel->open_file($dirname . "/" . $filename, $outputlangs);

            if ($result_file >= 0) {
                // Genere en-tete
                $objmodel->write_header($outputlangs);

                // Genere ligne de titre
                $objmodel->write_title($this->array_export_fields[$indice], $array_selected, $outputlangs);

                foreach ($result->rows as $aRow) {
                    $objp = $aRow->value;
                    //$var = !$var;

                    // Process special operations
                   /* if (!empty($this->array_export_special[$indice])) {
                        foreach ($this->array_export_special[$indice] as $key => $value) {
                            if (!array_key_exists($key, $array_selected))
                                continue;  // Field not selected






                                
// Operation NULLIFNEG
                            if ($this->array_export_special[$indice][$key] == 'NULLIFNEG') {
                                //$alias=$this->array_export_alias[$indice][$key];
                                $alias = str_replace(array('.', '-'), '_', $key);
                                if ($objp->$alias < 0)
                                    $objp->$alias = '';
                            }
                            // Operation ZEROIFNEG
                            if ($this->array_export_special[$indice][$key] == 'ZEROIFNEG') {
                                //$alias=$this->array_export_alias[$indice][$key];
                                $alias = str_replace(array('.', '-'), '_', $key);
                                if ($objp->$alias < 0)
                                    $objp->$alias = '0';
                            }
                        }
                    }*/
                    // end of special operation processing

                    $objmodel->write_record($array_selected, $objp, $outputlangs);
                }

                // Genere en-tete
                $objmodel->write_footer($outputlangs);

                // Close file
                $objmodel->close_file();

                return 1;
            }
            else {
                $this->error = $objmodel->error;
                dol_syslog("Export::build_file Error: " . $this->error, LOG_ERR);
                return -1;
            }
        } else {
            $this->error = $this->db->error() . " - sql=" . $sql;
            dol_syslog("Export::build_file Error: " . $this->error, LOG_ERR);
            return -1;
        }
    }

    /**
     *  Save an export model in database
     *
     *  @param		User	$user 	Object user that save
     *  @return		int				<0 if KO, >0 if OK
     */
    function create($user) {
        global $conf;

        dol_syslog("Export.class.php::create");

        $this->db->begin();

        $sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'export_model (';
        $sql.= 'label, type, field)';
        $sql.= " VALUES ('" . $this->model_name . "', '" . $this->datatoexport . "', '" . $this->hexa . "')";

        dol_syslog("Export::create sql=" . $sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if ($resql) {
            $this->db->commit();
            return 1;
        } else {
            $this->error = $this->db->lasterror();
            $this->errno = $this->db->lasterrno();
            dol_syslog("Export::create error " . $this->error, LOG_ERR);
            $this->db->rollback();
            return -1;
        }
    }

    /**
     *  Load an export profil from database
     *
     *  @param		int		$id		Id of profil to load
     *  @return		int				<0 if KO, >0 if OK
     */
    function fetch($id) {
        $sql = 'SELECT em.rowid, em.field, em.label, em.type';
        $sql.= ' FROM ' . MAIN_DB_PREFIX . 'export_model as em';
        $sql.= ' WHERE em.rowid = ' . $id;

        dol_syslog("Export::fetch sql=" . $sql, LOG_DEBUG);
        $result = $this->db->query($sql);
        if ($result) {
            $obj = $this->db->fetch_object($result);
            if ($obj) {
                $this->id = $obj->rowid;
                $this->hexa = $obj->field;
                $this->model_name = $obj->label;
                $this->datatoexport = $obj->type;

                return 1;
            } else {
                $this->error = "Model not found";
                return -2;
            }
        } else {
            dol_print_error($this->db);
            return -3;
        }
    }

    /**
     * 	Delete object in database
     *
     * 	@param      User		$user        	User that delete
     *  @param      int			$notrigger	    0=launch triggers after, 1=disable triggers
     * 	@return		int							<0 if KO, >0 if OK
     */
    function delete($user, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "export_model";
        $sql.= " WHERE rowid=" . $this->id;

        $this->db->begin();

        dol_syslog(get_class($this) . "::delete sql=" . $sql);
        $resql = $this->db->query($sql);
        if (!$resql) {
            $error++;
            $this->errors[] = "Error " . $this->db->lasterror();
        }

        if (!$error) {
            if (!$notrigger) {
                // Uncomment this and change MYOBJECT to your own tag if you
                // want this action call a trigger.
                //// Call triggers
                //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
                //$interface=new Interfaces($this->db);
                //$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
                //if ($result < 0) { $error++; $this->errors=$interface->errors; }
                //// End call triggers
            }
        }

        // Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(get_class($this) . "::delete " . $errmsg, LOG_ERR);
                $this->error.=($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return 1;
        }
    }

}

?>
