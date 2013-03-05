<?php

namespace datatables\filters;

use datatables\FilterInterface;

class Color implements FilterInterface {
    
    /* ______________________________________________________________________ */
    
    public function apply($input) {
        if( ! $input) {
            return '&mdash;';
        }
        $style = "margin:0;padding:0;width:40px;height:14px;border:1px #333 solid;background:{$input};";
        return "<div style='{$style}' />";
    }
}