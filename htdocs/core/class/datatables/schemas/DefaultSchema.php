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

			$this->push($aRow, array(
					'label'			=> (!empty($field->label)?$langs->trans($field->label):''),
					'searchable'	=> (is_bool($field->list->searchable) === true ? $field->list->searchable : true),
					'sortable'		=> (is_bool($field->list->sortable) === true ? $field->list->sortable : true),
					'visible'		=> (is_bool($field->list->visible) === true ? $field->list->visible : true),
					'class'			=> (!empty($field->list->cssclass)?$field->list->cssclass:''),
					'footer'		=> $this->element('FilterInput', array($langs->trans('Search') . ' {:label}')),
					'outputFilter'	=> function($input, $row) {
						return $input ? $input : '&mdash;';
					}
			));
		}
		//var_dump($this);
/*

        // role
        $roleOptions = array(
            'admin' => 'Admin',
            'user'  => 'User'
        );
        $this->push('role', array(
            'label'        => 'Role',
            'footer'       => $this->element('FilterSelect', array($roleOptions)),
            'outputFilter' => function($input, $row) use($roleOptions) {
                return $roleOptions[$input];
            }
        ));
*/

        // actions
        $this->push('action', array(
        		'type'         => 'static',
        		'width'        => 35,
        		'sortable'     => false,
        		'searchable'   => false,
        		'outputFilter' => function($input, $row) use($schema) {
        			return $schema->element('EditLink', array("societe/fiche.php?id={$row['_id']}")) .
        			$schema->element('DeleteLink', array("societe/fiche.php?id={$row['_id']}"));
        		}
        ));

        // checkbox
        $this->push('check', array(
        		'type'         => 'static',
        		'label'        => (string) $this->element('Checkbox', array('ca', 1, 'ca')),
        		'width'        => 8,
        		'sortable'     => false,
        		'searchable'   => false,
        		'outputFilter' => function($input, $row) use($schema) {
        			return (string) $schema->element('Checkbox', array('id[]', $row['_id']));
        		}
        ));

        // register another fields
    }
}
?>