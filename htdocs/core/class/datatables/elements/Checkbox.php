<?php

namespace datatables\elements;

use datatables\ElementInterface;

class Checkbox implements ElementInterface {

    protected $name;
    protected $value;
    protected $id;

    /* ______________________________________________________________________ */

    public function __construct($name, $value = '', $id = null) {
        $this->name = $name;
        $this->value = $value;
        $this->id = $id;
    }

    /* ______________________________________________________________________ */

    public function __toString() {
        return (string) $this->render();
    }

    /* ______________________________________________________________________ */

    public function render() {
        $output = "<input type='checkbox' ";
        if($this->id) {
            $output .= "id='{$this->id}' ";
        }
        $output .= "name='{$this->name}' value='{$this->value}' />";
        return $output;
    }
}