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

class RowAction implements PluginInterface {

	/* ______________________________________________________________________ */

	public function apply(Datatables $table) {
		$table->callback('
			// check/uncheck all
			$("#checkall").click(function() {
				if ($(this).is(":checked")) {
					$(".dataTables_wrapper table tbody tr").each(function() {
						chk = $(this).children("td").find(":checkbox").attr("checked", true);
					})
				} else {
					$(".dataTables_wrapper table tbody tr").each(function() {
						$(this).children("td").find(":checkbox").attr("checked", false);
					})
				}
			});
			// uncheck menu checkbox
			$(".checkbox").click(function() {
				if (!$(this).is(":checked")) {
					$("#checkall").attr("checked", false);
				}
			});
		');
	}
}