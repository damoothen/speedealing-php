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

class DeleteNotification implements PluginInterface {

	/* ______________________________________________________________________ */

	public function apply(Datatables $table) {
		global $langs, $object;

		$var_name = $table->getConfig('var_name');

		$table->method("
			$('tbody tr td .delEnqBtn').live('click', function(){
				var aPos = {$var_name}.fnGetPosition(this.parentNode);
				var aData = oTable.fnGetData(aPos[0]);
				if(aData['name'] === undefined)
					var text = aData['label'];
				else
					var text = aData['name'];
				var answer = confirm('" . $langs->trans("Delete") . " ' + text + ' ?');
				if(answer) {
					$.ajax({
						type: 'POST',
						url: '/core/ajax/deleteinplace.php',
						data: 'json=trash&class=" . get_class($object) . "&id=' + aData['_id'],
						success: function(msg){
							oTable.fnDeleteRow(aPos[0]);
						}
					});
				}
				return false;
			});
		");
	}
}