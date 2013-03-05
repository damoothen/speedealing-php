<?php

namespace datatables\elements;

use datatables\ElementInterface;

class FilterSelect implements ElementInterface {

    protected $options = array();
    protected $addEmpty = true;

    /* ______________________________________________________________________ */

    public function __construct(array $options = array(), $addEmpty = true) {
        $this->options = $options;
        $this->addEmpty = $addEmpty;
    }

    /* ______________________________________________________________________ */

    public function __toString() {
        return (string) $this->render();
    }

    /* ______________________________________________________________________ */

    public function render() {
    	global $langs;

        $output = "<select>";
        if($this->addEmpty) {
            $this->options = array('-1' => '') + $this->options;
        }

        foreach($this->options as $key => $val) {
        	if ($val['enable'] || $key == -1) {
        		$label = (!empty($val['label']) ? $val['label'] : $langs->trans($key));
        		$label = ($key == -1) ? '' : $label;
        		$output .= "<option value='{$key}'>{$label}</option>";
        	}
        }
        $output .= "</select>";
        return $output;
    }
}