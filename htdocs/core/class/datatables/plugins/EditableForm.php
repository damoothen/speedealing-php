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

class EditableForm implements PluginInterface {

	/* ______________________________________________________________________ */

	public function apply(Datatables $table) {
		global $langs;

		$table->chain('
				,
				fnOnNewRowPosted: function(data) {
					var rtn = oTable.fnAddData(JSON.parse(data));
					return true;
				},
				fnOnAdding: function() {
					oTable.fnDraw(false);
					return true;
				},
				oAddNewRowButtonOptions: {
					icons: { primary: \'ui-icon-plus\' }
				},
				oDeleteRowButtonOptions: {
					icons: { primary: \'ui-icon-trash\' }
				},
				oAddNewRowOkButtonOptions: {
					label: "' . $langs->trans("Create") . '",
					icons: { primary: \'ui-icon-check\' },
					name: "action",
					value: "add-new"
				},
				oAddNewRowCancelButtonOptions: {
					label: "' . $langs->trans("Undo") . '",
					class: "back-class",
					name: "action",
					value: "cancel-add",
					icons: { primary: \'ui-icon-close\' }
				},
				oAddNewRowFormOptions: {
					show: "blind",
					hide: "blind"
				},
				sAddNewRowFormId: "' . $table->getConfig('container_id') . '_formAddNewRow",
				sAddNewRowButtonId: "' . $table->getConfig('container_id') . '_btnAddNewRow",
				sAddNewRowOkButtonId: "' . $table->getConfig('container_id') . '_btnAddNewRowOk",
				sAddNewRowCancelButtonId: "' . $table->getConfig('container_id') . '_btnAddNewRowCancel",
				sDeleteRowButtonId: "' . $table->getConfig('container_id') . '_btnDeleteRow"
		');
	}
}