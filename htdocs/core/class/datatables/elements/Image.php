<?php

namespace datatables\elements;

use datatables\ElementInterface;

class Image implements ElementInterface {
    
    protected $url;
    protected $label;
    protected $class;
    protected $attrs;
    
    /* ______________________________________________________________________ */
    
    public function __construct($url = '', $label = '', $class = null, $attrs = null) {
        $this->url = $url;
        $this->label = $label;
        $this->class = $class;
        $this->attrs = $attrs;
    }
    
    /* ______________________________________________________________________ */
    
    public function __toString() {
        return (string) $this->render();
    }
    
    /* ______________________________________________________________________ */
    
    public function render() {
        $output = "\n<img src='{$this->url}' alt='{$this->label}' ";
        if($this->class) {
            $output .= "class='{$this->class}' ";
        }
        if($this->attrs) {
            $output .= "{$this->attrs} ";
        }
        $output .= "/>\n";
        return $output;
    }
}