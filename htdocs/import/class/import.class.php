<?php

/* Copyright (C) 2011       Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2012       Herve Prot              <herve.prot@symeos.com>
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

class Import extends nosqlDocument {

	var $array_import_module;
	var $array_import_perms;
	var $array_import_icon;
	var $array_import_code;
	var $array_import_label;
	var $array_import_tables;
	var $array_import_fields;
	var $array_import_entities;
	var $array_import_regex;
	var $array_import_examplevalues;
	var $array_import_convertvalue;

	/**
	 *    Constructor
	 *
	 *    @param  	DoliDB		$db		Database handler
	 */
	function __construct($db) {
		$this->db = $db;
	}

	/**
	 *  Load description int this->array_import_module, this->array_import_fields, ... of an importable dataset
	 *
	 *  @param		User	$user      	Object user making import
	 *  @param  	string	$filter		Load a particular dataset only. Index will start to 0.
	 *  @return		int					<0 if KO, >0 if OK
	 */
	function load_arrays($user, $filter = '') {
		global $langs, $conf;

		$var = true;
		$i = 0;

		$modules = new DolibarrModules($this->db);
		$result = $modules->getView("list_import");

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

			// Permission
			$this->array_import_perms[$i] = $user->rights->import->run;
			// Icon
			$this->array_import_icon[$i] = $aRow->value->icon;
			// Code du dataset export
			$this->array_import_code[$i] = $aRow->key[1];
			// Libelle du dataset export
			$langstring = "ImportDataset_" . $aRow->key[1];
			//print "x".$langstring;
			if ($langs->trans($langstring) == $langstring) {
				// Traduction non trouvee
				$this->array_import_label[$i] = $langs->trans($aRow->value->label);
			} else {
				// Traduction trouvee
				$this->array_import_label[$i] = $langs->trans($langstring);
			}

			// Class
			if (isset($aRow->value->class))
				$this->array_import_class[$i] = $aRow->value->class;
			else
				$this->array_import_class[$i] = $aRow->key[0];


			// Module
			$class = $this->array_import_class[$i];
			$this->array_import_module[$i] = new DolibarrModules($this->db);
			$this->array_import_module[$i]->load("module:" . $aRow->key[0]);

			dol_include_once("/" . strtolower($class) . "/class/" . strtolower($class) . ".class.php");
			$object = new $class($this->db);

			$this->array_import_fields[$i]["_id"] = "_id";
			$this->array_import_fields[$i]["_rev"] = "_rev";

			if (isset($object->fk_extrafields))
				foreach ($object->fk_extrafields->fields as $idx => $row) {
					if ($row->enable && $row->type != "uploadfile")
						if ($row->class) {
							$this->array_import_fields[$i][$idx . "->name"] = $idx . "->name";
							$this->array_import_fields[$i][$idx . "->id"] = $idx . "->id";
						}
						else
							$this->array_import_fields[$i][$idx] = $idx;
				}

			$i++;
		}

		return 1;
	}

	/**
	 *  Build an import example file.
	 *  Arrays this->array_export_xxx are already loaded for required datatoexport
	 *
	 *  @param      string	$model              Name of import engine ('csv', ...)
	 *  @param      string	$headerlinefields   Array of values for first line of example file
	 *  @param      string	$contentlinevalues	Array of values for content line of example file
	 *  @param		string	$datatoimport		Dataset to import
	 *  @return		string						<0 if KO, >0 if OK
	 */
	function build_example_file($model, $headerlinefields, $contentlinevalues, $datatoimport) {
		global $conf, $langs;

		$indice = 0;

		dol_syslog(get_class($this) . "::build_example_file " . $model);

		// Creation de la classe d'import du model Import_XXX
		$dir = DOL_DOCUMENT_ROOT . "/core/modules/import/";
		$file = "import_" . $model . ".modules.php";
		$classname = "Import" . $model;
		require_once $dir . $file;
		$objmodel = new $classname($this->db, $datatoimport);

		$outputlangs = $langs; // Lang for output
		$s = '';

		// Genere en-tete
		$s.=$objmodel->write_header_example($outputlangs);

		// Genere ligne de titre
		$s.=$objmodel->write_title_example($outputlangs, $headerlinefields);

		// Genere ligne de titre
		$s.=$objmodel->write_record_example($outputlangs, $contentlinevalues);

		// Genere pied de page
		$s.=$objmodel->write_footer_example($outputlangs);

		return $s;
	}

	/**
	 *  Save an export model in database
	 *
	 *  @param		User	$user 	Object user that save
	 *  @return		int				<0 if KO, >0 if OK
	 */
	function create($user) {
		global $conf;

		dol_syslog("Import.class.php::create");

		// Check parameters
		if (empty($this->model_name)) {
			$this->error = 'ErrorWrongParameters';
			return -1;
		}
		if (empty($this->datatoimport)) {
			$this->error = 'ErrorWrongParameters';
			return -1;
		}
		if (empty($this->hexa)) {
			$this->error = 'ErrorWrongParameters';
			return -1;
		}

		$this->db->begin();

		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'import_model (';
		$sql.= 'fk_user, label, type, field';
		$sql.= ')';
		$sql.= " VALUES (" . ($user->id > 0 ? $user->id : 0) . ", '" . $this->db->escape($this->model_name) . "', '" . $this->datatoimport . "', '" . $this->hexa . "')";

		dol_syslog(get_class($this) . "::create sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			$this->db->commit();
			return 1;
		} else {
			$this->error = $this->db->lasterror();
			$this->errno = $this->db->lasterrno();
			dol_syslog(get_class($this) . "::create error " . $this->error, LOG_ERR);
			$this->db->rollback();
			return -1;
		}
	}

	/**
	 *  Load an import profil from database
	 *
	 *  @param		int		$id		Id of profil to load
	 *  @return		int				<0 if KO, >0 if OK
	 */
	function fetch($id) {
		$sql = 'SELECT em.rowid, em.field, em.label, em.type';
		$sql.= ' FROM ' . MAIN_DB_PREFIX . 'import_model as em';
		$sql.= ' WHERE em.rowid = ' . $id;

		dol_syslog(get_class($this) . "::fetch sql=" . $sql, LOG_DEBUG);
		$result = $this->db->query($sql);
		if ($result) {
			$obj = $this->db->fetch_object($result);
			if ($obj) {
				$this->id = $obj->rowid;
				$this->hexa = $obj->field;
				$this->model_name = $obj->label;
				$this->datatoimport = $obj->type;
				$this->fk_user = $obj->fk_user;
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
	 * 	@param      User	$user        	User that delete
	 *  @param      int		$notrigger	    0=launch triggers after, 1=disable triggers
	 * 	@return		int						<0 if KO, >0 if OK
	 */
	function delete($user, $notrigger = 0) {
		global $conf, $langs;
		$error = 0;

		$sql = "DELETE FROM " . MAIN_DB_PREFIX . "import_model";
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
				// Call triggers
				include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
				$interface = new Interfaces($this->db);
				$result = $interface->run_triggers('IMPORT_DELETE', $this, $user, $langs, $conf);
				if ($result < 0) {
					$error++;
					$this->errors = $interface->errors;
				}
				// End call triggers
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
