<?php
namespace datatables\schemas;

use datatables\Schema;

class DefaultSchema extends Schema {

    /* ______________________________________________________________________ */

	public function __construct() {
		global $langs, $object;

		// variable to be used inside closure object
		$schema = $this;

		foreach ($object->fk_extrafields->longList as $key => $aRow) {

			$field = $object->fk_extrafields->fields->$aRow;

			if (empty($field->enable)) continue;

			if (!empty($field->render) || !empty($field->action))
					$rendertype = (!empty($field->render->type) ? $field->render->type : $field->type);

			$this->push($aRow, array(
					'label'			=> (!empty($field->label) ? $langs->trans($field->label) : $langs->trans($aRow)),
					'default'		=> (!empty($field->default) ? $field->default : ''),
					'type'			=> (!empty($field->list->static) ? 'static' : 'dynamic'),							// static (no movable and never hidden)
					'searchable'	=> (is_bool($field->list->searchable) === true ? $field->list->searchable : true),	// True by default
					'sortable'		=> (is_bool($field->list->sortable) === true ? $field->list->sortable : true),		// True by default
					'visible'		=> (is_bool($field->list->visible) === true ? $field->list->visible : true),		// True by default
					'class'			=> (!empty($field->list->cssclass) ? $field->list->cssclass : ''),
					'width'			=> (!empty($field->list->width) ? $field->list->width : false),
					'footer'		=> ($field->list->searchable !== false ? $this->element('FilterInput', array($langs->trans('Search') . ' {:label}')) : ''),
					'editable'		=> (!empty($field->list->editable) ? $this->element('Editable', array($field->type, $aRow, get_class($object), $field->list->validate)) : false),
					'render'		=> (isset($rendertype) ? $this->element('Render' . ucfirst($rendertype), array($field, $aRow, get_class($object))) : false)
			));
		}
    }
}
?>