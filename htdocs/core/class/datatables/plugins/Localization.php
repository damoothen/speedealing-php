<?php

namespace datatables\plugins;

use datatables\Datatables,
    datatables\PluginInterface;

class Localization implements PluginInterface {

    /* ______________________________________________________________________ */

	public function apply(Datatables $table) {
		global $langs;

		$menu = '<select>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="-1">'.$langs->transnoentities('All').'</option>
				</select>';

		$table->setTranslationArray(array(
				"sProcessing"	=> $langs->trans('Processing'),
				"sLengthMenu"	=> $langs->trans('Show').' '.$menu.' '.$langs->trans('Entries'),
				"sSearch"		=> $langs->trans('Search').':',
				"sZeroRecords"	=> $langs->trans('NoRecordsToDisplay'),
				"sInfoEmpty"	=> $langs->trans('NoEntriesToShow'),
				"sInfoFiltered"	=> '('.$langs->trans('FilteredFrom').' _MAX_ '.$langs->trans('TotalEntries').')',
				"sInfo"			=> $langs->trans('Showing').' _START_ '.$langs->trans('To').' _END_ '.$langs->trans('Of').' _TOTAL_ '.$langs->trans('Entries'),
				"sInfoPostFix"	=> '',
				"sUrl"			=> '',
				"oPaginate"		=> array(
						"sFirst"	=> $langs->transnoentities('First'),
						"sLast"		=> $langs->transnoentities('Last'),
						"sPrevious"	=> $langs->transnoentities('Previous'),
						"sNext"		=> $langs->transnoentities('Next')
				)
		));
	}
}