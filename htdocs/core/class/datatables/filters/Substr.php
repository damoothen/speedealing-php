<?php

namespace datatables\filters;

use datatables\FilterInterface;

class Substr implements FilterInterface {
    
	protected $length;
	protected $suffix;
	
    /* ______________________________________________________________________ */
    
    public function __construct($length = 100, $suffix = '[...]') {
        $this->length = $length;
		$this->suffix = $suffix;
    }
	
    /* ______________________________________________________________________ */
    
    public function apply($input) {
        $words = explode(' ', substr($input, 0, $this->length));
		array_pop($words);
        return implode(' ', $words) . $this->suffix;
    }
}