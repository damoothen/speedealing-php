<?php

namespace datatables\plugins;

use datatables\Datatables,
    datatables\PluginInterface;

class DeleteNotification implements PluginInterface {

    /* ______________________________________________________________________ */

	public function apply(Datatables $table) {
		global $langs;

        $table->callback('
        jQuery(".dataTables_wrapper a.delete").each(function(){
            jQuery(this).click(function(e) {
                return confirm("Are you sure?");
            })
        });
        ');
	}
}