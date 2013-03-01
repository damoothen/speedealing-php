<?php

namespace datatables\filters;

use datatables\FilterInterface;

class Date implements FilterInterface {
    
    protected $format;
    
    /* ______________________________________________________________________ */
    
    public function __construct($format = 'Y - m - d') {
        $this->format = $format;
    }
    
    /* ______________________________________________________________________ */
    
    public function apply($input) {
        if( ! $input) {
            return '&mdash;';
        } elseif($input instanceof \DateTime) {
            return (string) $input->format($this->format);
        } else {
            $utime = (is_numeric($input)) ? $input : strtotime($input);
            return date($this->format, $utime);
        }
    }
}