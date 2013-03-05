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

class RowShowHide implements PluginInterface {

	/* ______________________________________________________________________ */

	public function apply(Datatables $table) {
		global $langs, $object;

		$table->setParam('sDom', 'C<"clear"><"dataTables_header"lfr>t<"dataTables_footer"p>'); // just for test

		$fields = $table->getFieldType();

		$i = 0; $exclude = array();
		if (!empty($fields)) {
			foreach($fields as $type) {
				if ($type == 'static')
					$exclude[] = $i;
				$i++;
			}
		}

		$table->colvis("
				'aiExclude': " .json_encode($exclude) . "
		");
	}
}