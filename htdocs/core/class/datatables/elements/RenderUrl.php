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
					var img, ico = "'.(!empty($ico) ? $ico : false).'";

					if(row._id === undefined)
						return ar.join("");
					else if(data === undefined)
						data = row._id;

					if (ico) {
						var title = "' . $langs->trans("Show") . ' ' . $this->classname . '";
						img = "<img src=\"theme/' . $conf->theme . '/img/ico/icSw2/" + ico + "\" border=\"0\" alt=\"" + title + " : " + data.toString() + "\" title=\"" + title + " : " + data.toString() + "\"> ";
					}

					if (typeof data == "object") {
						if (data.length > 1) {
							$.each(data, function(key, value) {
								obj = value.id.split(":");
								var url = obj[0] + "/'. $this->cardname .'.php?id=" + value.id;
								ar[ar.length] = img + "<span class=\"' . $this->field->render->cssclass . '\"><a href=\"" + url + "\">" + value.name.toString() + "</a></span> ";
							});
						} else if (data.id) {
							obj = data.id.split(":");
							var url = obj[0] + "/'. $this->cardname .'.php?id=" + data.id;
							ar[ar.length] = img + "<span class=\"' . $this->field->render->cssclass . '\"><a href=\"" + url + "\">" + data.name.toString() + "</a></span> ";
						}
					} else {
						ar[ar.length] = img + "<a href=\"' . $url . '" + row._id + "\">" + data.toString() + "</a>";
					}
					return ar.join("");
				}';
	}
}