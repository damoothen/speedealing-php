<?php

/* Copyright (C) 2002-2003	Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2002-2003	Jean-Louis Bergamo	<jlb@j1b.org>
 * Copyright (C) 2004		Sebastien Di Cintio	<sdicintio@ressource-toi.org>
 * Copyright (C) 2004		Benoit Mortier		<benoit.mortier@opensides.be>
 * Copyright (C) 2009-2011	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2009-2011	Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2012		Herve Prot		<herve.prot@symeos.com>
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
require_once(DOL_DOCUMENT_ROOT . "/core/class/nosqlDocument.class.php");

class ExtraFields extends nosqlDocument {

	var $db;
	// Tableau contenant le nom des champs en clef et la definition de ces champs
	var $attribute_type;
	// Tableau contenant le nom des champs en clef et le label de ces champs en value
	var $attribute_label;
	// Tableau contenant le nom des champs en clef et la taille de ces champs en value
	var $attribute_size;
	// Tableau contenant le statut unique ou non
	var $attribute_unique;
	var $error;
	var $errno;
	var $type2label;

	/**
	 * 	Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	function __construct($db) {

		parent::__construct($db);

		$this->type2label = array(
			'text' => 'TextLong',
			'int' => 'Int',
			'double' => 'Float',
			'select' => "Select",
			'date' => 'Date',
			'datetime' => 'DateAndTime'
		);

		return 1;
	}

	/**
	 *  Add a new extra field parameter
	 *
	 *  @param	string	$attrname           Code of attribute
	 *  @param  string	$label              label of attribute
	 *  @param  int		$type               Type of attribute ('int', 'text', 'varchar', 'date', 'datehour')
	 *  @param  int		$size               Size/length of attribute
	 *  @param  string	$elementtype        Element type ('member', 'product', 'company', ...)
	 *  @param	int		$unique				Is field unique or not
	 *  @return int      					<=0 if KO, >0 if OK
	 */
	function addExtraField($attrname, $label, $type, $size) {
		if (empty($attrname))
			return -1;
		if (empty($label))
			return -1;

		// Create field into database
		if ($attrname != '' && preg_match("/^\w[a-zA-Z0-9-_]*$/", $attrname)) {
			$maxpos = 0;
			foreach ($this->fields as $row) {
				if ($row->optional) {
					if ($row->pos > $maxpos)
						$maxpos = $row->pos;
				}
			}

			$this->fields->$attrname->enable = true;
			$this->fields->$attrname->pos = $maxpos;
			$this->fields->$attrname->edit = true;
			$this->fields->$attrname->optional = true; // Is an extrafields create by user

			return $this->update($attrname, $label, $type, $size);
		} else {
			return -1;
		}
	}

	/**
	 * 	Delete an optionnal attribute
	 *
	 * 	@param	string	$attrname		Code of attribute to delete
	 *  @return int              		< 0 if KO, 0 if nothing is done, 1 if OK
	 */
	function delete($attrname) {
		$table = '';

		if (!empty($attrname) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $attrname) && $this->fields->$attrname->optional) {
			unset($this->fields->$attrname);
			unset($this->type2label);
			$this->record(true);

			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * 	Enable or Disable an optional attribut
	 *
	 * 	@param	string	$attrname		Code of attribute to delete
	 * 	@param	boolean	$enable                 Enable = 1 or Disable = 0
	 *  @return int              		< 0 if KO, 0 if nothing is done, 1 if OK
	 */
	function setStatus($attrname, $enable = false) {

		if (!empty($attrname) && preg_match("/^\w[a-zA-Z0-9-_]*$/", $attrname)) {
			$this->fields->$attrname->enable = $enable;
			unset($this->type2label);
			$this->record(true);

			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * 	Modify type of a personalized attribute
	 *
	 *  @param	string	$attrname			Name of attribute
	 *  @param	string	$label				Label of attribute
	 *  @param	string	$type				Type of attribute
	 *  @param	int	$length				Length of attribute
	 * 	@return	int							>0 if OK, <=0 if KO
	 */
	function update($attrname, $label, $type, $length) {
		$table = '';

		if (isset($attrname) && $attrname != '' && preg_match("/^\w[a-zA-Z0-9-_]*$/", $attrname)) {

			$this->fields->$attrname->type = $type;
			$this->fields->$attrname->label = $label;
			$this->fields->$attrname->size = $length;

			unset($this->type2label);
			$this->record(true);
			return 1;
		} else {
			return -1;
		}
	}

	/**
	 *  Load array of labels
	 *
	 *  @return	void
	 */
	function fetch($class) {
		global $langs;
		require_once DOL_DOCUMENT_ROOT . '/admin/class/dict.class.php';

		try {
			$this->load("extrafields:" . $class, true); // load and cache
			//print_r($this->fields->Status);
		} catch (Exception $e) {
			
		}

		if (isset($this->fields) && count($this->fields))
			foreach ($this->fields as $aRow) {
				if (isset($aRow->dict)) {
					$dict = new Dict($this->db);
					$values = $dict->load($aRow->dict, true);
					$aRow->values = clone $values->values;
				}
			}


		if (isset($this->langs) && count($this->langs) && !empty($langs->defaultlang))
			foreach ($this->langs as $aRow) {
				$langs->load($aRow);
			}

		return 1;
	}

	/**
	 *  Return HTML string to put an input field into a page
	 *
	 *  @param	string	$key             Key of attribute
	 *  @param  string	$value           Value to show
	 *  @param  string	$moreparam       To add more parametes on html input tag
	 *  @return	void
	 */
	function showInputField($key, $value, $moreparam = '') {
		global $conf;

		$label = $this->fields->$key->label;
		$type = $this->fields->$key->type;
		$size = $this->fields->$key->size;
		if ($type == 'date') {
			$showsize = 10;
		} elseif ($type == 'datetime') {
			$showsize = 19;
		} elseif (in_array($type, array('int', 'double'))) {
			$showsize = 10;
		} else {
			$showsize = round($size);
			if ($showsize > 48)
				$showsize = 48;
		}

		if (in_array($type, array('date', 'datetime'))) {
			$tmp = explode(',', $size);
			$newsize = $tmp[0];
			$out = '<input type="text" name="options_' . $key . '" size="' . $showsize . '" maxlength="' . $newsize . '" value="' . $value . '"' . ($moreparam ? $moreparam : '') . '>';
		} else if (in_array($type, array('int', 'double'))) {
			$tmp = explode(',', $size);
			$newsize = $tmp[0];
			$out = '<input type="text" name="options_' . $key . '" size="' . $showsize . '" maxlength="' . $newsize . '" value="' . $value . '"' . ($moreparam ? $moreparam : '') . '>';
		} else if ($type == 'text') {
			$out = '<input type="text" name="options_' . $key . '" size="' . $showsize . '" maxlength="' . $size . '" value="' . $value . '"' . ($moreparam ? $moreparam : '') . '>';
		} else if ($type == 'textarea') {
			require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
			$doleditor = new DolEditor('options_' . $key, $value, '', 200, 'dolibarr_notes', 'In', false, false, !empty($conf->fckeditor->enabled) && $conf->global->FCKEDITOR_ENABLE_SOCIETE, 5, 100);
			$out = $doleditor->Create(1);
		}
		// Add comments
		if ($type == 'date')
			$out.=' (YYYY-MM-DD)';
		elseif ($type == 'datetime')
			$out.=' (YYYY-MM-DD HH:MM:SS)';
		return $out;
	}

	/**
	 * Return HTML string to put an output field into a page
	 *
	 * @param   string	$key            Key of attribute
	 * @param   string	$value          Value to show
	 * @param	string	$moreparam		More param
	 * @return	string					Formated value
	 */
	function showOutputField($key, $value, $moreparam = '') {
		$label = $this->fields->$key->label;
		$type = $this->fields->$key->type;
		$size = $this->fields->$key->size;

		if ($type == 'date') {
			$showsize = 10;
		} elseif ($type == 'datetime') {
			$showsize = 19;
		} elseif ($type == 'int') {
			$showsize = 10;
		} else {
			$showsize = round($size);
			if ($showsize > 48)
				$showsize = 48;
		}
		//print $type.'-'.$size;
		$out = $value;
		return $out;
	}

	/**
	 *  Compare this->position for usort
	 *
	 *  @param  int		$a			first element
	 *  @param  int		$b			second element
	 *  @return	-1,0,1
	 */
	public function compare($a, $b) {
		$a1 = $a->pos;
		$b1 = $b->pos;

		if ($a1 == $b1) {
			return 0;
		}
		return ($a1 > $b1) ? +1 : -1;
	}

}

?>
