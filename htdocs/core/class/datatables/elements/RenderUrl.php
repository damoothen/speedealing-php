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

class RenderUrl implements ElementInterface {

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

		$ico = (!empty($this->field->render->ico) ? $this->field->render->ico : (!empty($this->field->ico) ? $this->field->ico : ''));
		$url = strtolower($this->classname) . '/'.$this->cardname.'.php?id=';

		if (!empty($this->field->render->url))
			$url = $this->field->render->url;

		return 'function(data, type, row) {
					var ar = [];
					ico = "'.(!empty($ico) ? ' ' . $ico : false).'";

					if(row._id === undefined)
						return ar.join("");
					else if(data === undefined)
						data = row._id;

					var objClassName = "' . $this->classname . '";
					if (objClassName == "mixed" && row.element) {
						objClassName = row.element;
						ico = "icon-object-" + objClassName.toLowerCase();
					}

					var url = objClassName.toLowerCase() + "/'.$this->cardname.'.php?id=";

					var title = "' . $langs->trans("Show"). ' " + objClassName;

					if (typeof data == "object") {
						if (data.length > 1) {
							$.each(data, function(key, value) {
								if (ico)
									ar[ar.length] = "<span class=\"" + ico + "\" title=\"" + title + " : " + value.name.toString() + "\">";
								ar[ar.length] = "<a href=\"" + url + value.id + "\">" + value.name.toString() + "</a> ";
								if (ico)
									ar[ar.length] = "</span>";
							});
						} else if (data.id) {
							if (ico)
								ar[ar.length] = "<span class=\"" + ico + "\" title=\"" + title + " : " + data.name.toString() + "\">";
							ar[ar.length] = "<a href=\"" + url + data.id + "\">" + data.name.toString() + "</a> ";
							if (ico)
								ar[ar.length] = "</span>";
						}
					} else {
						if (ico)
							ar[ar.length] = "<span class=\"" + ico + "\" title=\"" + title + " : " + data.toString() + "\">";
						ar[ar.length] = "<a href=\"" + url + row._id + "\">" + data.toString() + "</a>";
						if (ico)
							ar[ar.length] = "</span>";
					}
					return ar.join("");
				}';
	}
}