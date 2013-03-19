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

class RenderTag implements ElementInterface {

	protected $field;
	protected $name;
	protected $classname;

	/* ______________________________________________________________________ */

	public function __construct($field = '', $name = '', $classname = '') {
		$this->field = $field;
		$this->name = $name;
		$this->classname = $classname;
	}

	/* ______________________________________________________________________ */

	public function __toString() {
		return (string) $this->render();
	}

	/* ______________________________________________________________________ */

	public function render() {
		return 'function(data, type, row) {
					var ar = [];
					for (var i in data) {
						ar[ar.length] = "<small class=\"' . $this->field->render->cssclass . '\">" + data[i].toString() + "</small> ";
					}
					return ar.join("");
				}';
	}
}