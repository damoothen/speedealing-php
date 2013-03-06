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

namespace datatables\plugins;

use datatables\Datatables,
datatables\PluginInterface;

class FieldEditable implements PluginInterface {

	/* ______________________________________________________________________ */

	public function apply(Datatables $table) {

		$fields = $table->getFieldEditable();

		foreach($fields as $key => $values) {

			// Type of data
			$type = '';
			switch ($values['type']) {
				case "select" :
					$type .= "type: 'select',\n";
					$type .= "loadurl: urlLoadInPlace,\n";
					$type .= "loaddata: function ( value, settings ) {
								return {
									'id': oTable.fnGetData( this.parentNode, 0),
									'element_class': '{$values['classname']}',
									'type': 'select',
									'key': 'editval_{$values['name']}',
								};
							},";
					break;
				case "date" :
					$type .= "type: 'datepicker',";
					$type .= "cancel: cancelInPlace,";
					break;
				case "email" :
					$type .= "type: 'text',";
					$type .= 'data: function ( value, settings ) {
								// remove html tag
								value = $(value).html();
								return value;
							},';
					break;
				default :
					$type .= "type: 'text',";
					break;
			}

			// Type of validation
			$validate = '';
			if (!empty($values['validate'])) {
				$validate .= 'oValidationOptions : { rules:{ value: {';

				foreach ($values['validate'] as $key => $value)
					if ($key != "cssclass")
					$validate .= $key . ":" . $value . ",";

				$validate .= '} } },';
				if (isset($values['validate']->cssclass))
					$validate .= 'cssclass: "' . $values['validate']->cssclass . '",';
			}

			$table->callback("
				$('td.editfield_{$values['name']}').editable(urlSaveInPlace, {
					indicator: indicatorInPlace,
					tooltip: tooltipInPlace,
					placeholder: '',
					submit: submitInPlace,
					onblur: 'cancel',
					width: '{$values['width']}',
					height: '{$values['height']}',
					{$type}
					submitdata: function ( value, settings ) {
						return {
							'id': oTable.fnGetData( this.parentNode, 0),
							'element_class' : '{$values['classname']}',
							'type': '{$values['type']}',
							'key': 'editval_{$values['name']}'
						};
					},
					callback: function(sValue, y) {
						$(this).html(sValue);
					},
					{$validate}
				});
			");
		}
	}
}