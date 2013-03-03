<?php

namespace datatables\filters;

use datatables\FilterInterface;

class Filesize implements FilterInterface {
    
    protected $precision;
    
    /* ______________________________________________________________________ */
    
    public function __construct($precision = 2) {
        $this->precision = $precision;
    }
    
    /* ______________________________________________________________________ */
    
    public function apply($input) {
        $sizes = array(' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB');
        if($input) {
            return (round($input/pow(1024, ($n = floor(log($input, 1024)))), $this->precision) . $sizes[$n]);
        }
        return '&mdash;';
    }
}