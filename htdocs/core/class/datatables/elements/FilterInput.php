<?php

namespace datatables\elements;

use datatables\ElementInterface;

class FilterInput implements ElementInterface {

    protected $label;

    /* ______________________________________________________________________ */

    public function __construct($label = 'Filter {:label}') {
        $this->label = $label;
    }

    /* ______________________________________________________________________ */

    public function __toString() {
        return (string) $this->render();
    }

    /* ______________________________________________________________________ */

    public function render() {
        return "<input type='text' placeholder='{$this->label}' />";
    }
}