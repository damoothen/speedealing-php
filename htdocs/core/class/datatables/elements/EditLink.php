<?php

namespace datatables\elements;

use datatables\ElementInterface;

class EditLink implements ElementInterface {

    protected $url;
    protected $class;
    protected $label;

    /* ______________________________________________________________________ */

    public function __construct($url = '', $class = 'icon edit', $label = 'Edit') {
        $this->url = $url;
        $this->class = $class;
        $this->label = $label;
    }

    /* ______________________________________________________________________ */

    public function __toString() {
        return (string) $this->render();
    }

    /* ______________________________________________________________________ */

    public function render() {
    	global $conf;
        return "<a href='{$this->url}' class='{$this->class}' title='{$this->label}'><img src=\"theme/{$conf->theme}/img/edit.png\" alt=\"\" /></a>";
    }
}