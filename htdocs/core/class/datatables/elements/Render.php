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

class Render implements ElementInterface {

	protected $data;
	protected $name;
	protected $classname;
	protected $cardname;

	/* ______________________________________________________________________ */

	public function __construct($data = '', $name = '', $classname = '', $cardname = 'fiche') {
		$this->data = $data;
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

		$output = '';
		switch ($this->data->type) {
			case "url" :
				if (empty($this->data->url)) // default url
					$url = strtolower($this->classname) . '/'.$this->cardname.'.php?id=';
				else
					$url = $this->data->url;

				$output .= 'function(data, type, row) {
								var ar = [];
								if(row._id === undefined)
									return ar.join("");
								else if(data === undefined)
									data = row._id;'."\n";

				if (!empty($this->data->ico)) {
					$title = $langs->trans("Show") . ' ' . $this->classname;
					$output .= 'ar[ar.length] = "<img src=\"theme/' . $conf->theme . '/img/ico/icSw2/' . $this->data->ico . '\" border=\"0\" alt=\"' . $title . ' : ";
								ar[ar.length] = data.toString() + "\" title=\"' . $title . ' : " + data.toString() + "\"> ";'."\n";
				}
				$output .= 'ar[ar.length] = "<a href=\"' . $url . '" + row._id + "\">" + data.toString() + "</a>";
							var str = ar.join("");
							return str;
						}';
				break;
			case "email" :
				$output .= 'function(data, type, row) {
								var ar = [];
								if(data === undefined)
									return ar.join("");

								ar[ar.length] = "<a href=\"mailto:" + data.toString() + "\">" + data.toString() + "</a>";
								var str = ar.join("");
								return str;
							}';
				break;
			default :
				$output .= 'function(data, type, row) {
								return data;
							}';
				break;
		}

		return $output;
	}
}