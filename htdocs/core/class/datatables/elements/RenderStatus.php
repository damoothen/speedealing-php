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

class RenderStatus implements ElementInterface {

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
		global $langs;

		$output = 'function(data, type, row) {
						var now = Math.round(+new Date());
						var status = new Array();
						var expire = new Array();
						var statusDateEnd = "";';

		if (!empty($this->field->values)) {
			foreach ($this->field->values as $key => $aRow) {
				if (isset($aRow->label))
					$output.= 'status["' . $key . '"]= new Array("' . $langs->trans($aRow->label) . '","' . $aRow->cssClass . '");';
				else
					$output.= 'status["' . $key . '"]= new Array("' . $langs->trans($key) . '","' . $aRow->cssClass . '");';
				if (isset($aRow->dateEnd))
					$output.= 'expire["' . $key . '"]="' . $aRow->dateEnd . '";';
			}
		}

		// TODO show the data structure
		/*if (isset($params["dateEnd"])) {
		 $rtr.= 'if(obj.aData.' . $params["dateEnd"] . ' === undefined)
			obj.aData.' . $params["dateEnd"] . ' = "";';
		$rtr.= 'if(obj.aData.' . $params["dateEnd"] . ' != ""){';
		$rtr.= 'var dateEnd = new Date(obj.aData.' . $params["dateEnd"] . ').getTime();';
		$rtr.= 'if(dateEnd < now)';
		$rtr.= 'if(expire[stat] !== undefined)
			stat = expire[stat];';
		$rtr.= '}';
		}*/

		$output.= 'var ar = [];
					ar[ar.length] = "<small class=\"tag " + status[data][1] + " glossy\">" + status[data][0] + "</small>";
					return ar.join("");
				}';

		return $output;
	}
}