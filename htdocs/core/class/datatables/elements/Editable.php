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

class Editable implements ElementInterface {

	protected $type;
	protected $name;
	protected $classname;
	protected $validate;
	protected $width;
	protected $height;

	/* ______________________________________________________________________ */

	public function __construct($type = 'text', $name = '', $classname = '', $validate = false, $width = '150px', $height = '14px') {
		$this->type = $type;
		$this->name = $name;
		$this->classname = $classname;
		$this->validate = $validate;
		$this->width = $width;
		$this->height = $height;
	}

	/* ______________________________________________________________________ */

	public function __toString() {
		return (string) $this->render();
	}

	/* ______________________________________________________________________ */

	public function render() {

		// Type of data
		$type = '';
		switch ($this->type) {
			case "select" :
				$type .= "type: 'select',\n";
				$type .= "loadurl: urlLoadInPlace,\n";
				$type .= "loaddata: function ( value, settings ) {
								return {
									'id': oTable.fnGetData( this.parentNode, 0),
									'element_class': '{$this->classname}',
									'type': 'select',
									'key': 'editval_{$this->name}',
								};
						},";
				break;
			case "date" :
				 $type .= "type: 'datepicker',";
				 $type .= "cancel: cancelInPlace,";
				 break;
			default :
				$type .= "type: 'text',";
				break;
		}

		// Type of validation
		$validate = '';
		if (!empty($this->validate)) {
			$validate .= 'oValidationOptions : { rules:{ value: {';

			foreach ($this->validate as $key => $value)
				if ($key != "cssclass")
					$validate .= $key . ":" . $value . ",";

			$validate .= '} } },';
			if (isset($this->validate->cssclass))
				$validate .= 'cssclass: "' . $this->validate->cssclass . '",';
		}

		// Output
		$output = "
			{
				indicator: indicatorInPlace,
				tooltip: tooltipInPlace,
				placeholder: '',
				submit: submitInPlace,
				onblur: 'cancel',
				width: '{$this->width}',
				height: '{$this->height}',
				{$type}
				submitdata: function ( value, settings ) {
					return {
						'id': oTable.fnGetData( this.parentNode, 0),
						'element_class' : '{$this->classname}',
						'type': '{$this->type}',
						'key': 'editval_{$this->name}'
					};
				},
				callback: function(sValue, y) {
					//var aPos = oTable.fnGetPosition( this );
					//oTable.fnAddData( sValue, aPos[0], aPos[1] ); // doesn't work with server-side
					//oTable.fnDraw();
					$(this).html(sValue);
				},
				{$validate}
			},";

		return $output;
	}
}