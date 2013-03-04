<?php
/* Copyright (C) 2013	Regis Houssin	<regis.houssin@capnetworks.com>
 * Copyright (C) 2013	Herve Prot		<herve.prot@symeos.com>
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

namespace datatables\elements;

use datatables\ElementInterface;

class RenderAction implements ElementInterface {

	protected $field;
	protected $name;
	protected $classname;
	protected $cardname;

	/* ______________________________________________________________________ */

	public function __construct($field = '', $name = '', $classname = '', $cardname = 'fiche') {
		$this->field = $field;
		$this->name = $name;
		$this->classname = $classname;
		$this->cardname = $cardname;
	}

	/* ______________________________________________________________________ */

	public function __toString() {
		return (string) $this->render();
	}

	/* ______________________________________________________________________ */

	public function render() {
		global $conf, $langs;

		$url = strtolower($this->classname) . '/'.$this->cardname.'.php?id=';

		$output = 'function(data, type, row) {
						var ar = [];';

		foreach($this->field->action as $action => $param) {
			if ($action == 'edit')
				$output.= 'ar[ar.length] = \'<a href="' . $url . '\' + row._id + \'&action=' . $action . '&backtopage=' . $_SERVER['PHP_SELF'] . '" class="' . $param->cssclass . '" title="' . $langs->trans($param->label) . '"><img src="theme/' . $conf->theme . '/img/edit.png" alt="" /></a>\';';
			else if ($action == 'delete')
				$output.= 'ar[ar.length] = \'<a class="' . $param->cssclass . '" title="' . $langs->trans($param->label) . '"><img src="theme/' . $conf->theme . '/img/delete.png" alt="" /></a>\';';
		}

		$output.= 'return ar.join("");
					}';

		return $output;
	}
}