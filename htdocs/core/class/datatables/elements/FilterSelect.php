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
        $output = "<select>";
        if($this->addEmpty) {
            $this->options = array('' => '') + $this->options;
        }
        
        foreach($this->options as $key => $val) {
            $output .= "<option value='{$key}'>{$val}</option>";
        }
        $output .= "</select>";
        return $output;
    }
}