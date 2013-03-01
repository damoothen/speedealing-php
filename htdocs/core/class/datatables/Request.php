<?php

namespace datatables;

class Request {
    
    protected $schema;
    protected $requests = array(
        'page'    => 1,
        'sort'    => null,
        'order'   => null,
        'sorts'   => array(),
        'filters' => array(),
        'search'  => '',
        'limit'   => 0,
        'offset'  => 0
    );
    
    /* ______________________________________________________________________ */
    
    public function __construct(Schema $schema, array $requests = array()) {
        $this->schema = $schema;
        if(empty($requests)) {
            $requests = $_POST + $_GET;
        }
        $this->_parse($schema->data(), $requests);
    }
    
    /* ______________________________________________________________________ */
    
    public function __toString() {
        return print_r($this->requests, true);
    }
    
    /* ______________________________________________________________________ */
    
    public function __get($name) {
        return $this->get($name);
    }
    
    /* ______________________________________________________________________ */
    
    public function __set($name, $value) {
        return $this->set($name, $value);
    }
    
    /* ______________________________________________________________________ */
    
    public function addFilter($name, $value) {
        return $this->requests['filters'][$name] = $value;
    }
    
    /* ______________________________________________________________________ */
    
    public function getRequests() {
        return $this->requests;
    }
    
    /* ______________________________________________________________________ */
    
    public function get($name, $default = null) {
        return isset($this->requests[$name]) ? $this->requests[$name] : $default;
    }
    
    /* ______________________________________________________________________ */
    
    public function set($name, $value) {
        return $this->requests[$name] = $value;
    }
    
    /* ______________________________________________________________________ */
    
    protected function _parse(array $schema, array $requests) {
        // search query
        if(isset($requests['sSearch'])) {
            $this->requests['search'] = $requests['sSearch'];
        }
        
        // record offset
        if(isset($requests['iDisplayStart'])) {
            $this->requests['offset'] = intval($requests['iDisplayStart']);
        }
        
        // record limit
        if(isset($requests['iDisplayLength'])) {
            $this->requests['limit'] = intval($requests['iDisplayLength']);
        }
        
        // get current page
        // @ -> prevent warning when the divisor is zero
        $this->requests['page'] = (int) @ floor($this->requests['offset'] / $this->requests['limit']) + 1;
        
        // get sorting parameters
        if(isset($requests['iSortingCols']) && ($n = $requests['iSortingCols'])) {
            $fields = array_keys($schema);
            for($i = 0; $i < $n; $i++) {
                
                $key = "iSortCol_{$i}";
                
                if(isset($requests[$key]) && 
                    isset($fields[$requests[$key]]) && 
                    $this->schema->isSortable($fields[$requests[$key]])) 
                {
                    $field = $fields[$requests[$key]];
                    
                    $direction = 'ASC';
                    if(isset($requests["sSortDir_{$i}"])) {
                        $direction = strtoupper($requests["sSortDir_{$i}"]);
                    }
                    
                    $this->requests['sorts'][$field] = $direction;
                }
            }
        }
        
        // get filter params
        $cols = $this->schema->getVisibleColumns();
        $numVisible = count($cols);
        $reqKeyPrefix = 'sSearch_';
        for($i = 0; $i < $numVisible; $i++) {
            if(isset($requests[$reqKeyPrefix . $i]) 
                && trim($requests[$reqKeyPrefix . $i]) != ''
                && $this->schema->isSearchable($cols[$i])
            ) {
                $this->requests['filters'][$cols[$i]] = $requests[$reqKeyPrefix . $i];
            }
        }
        
        // get first sort & direction
        if(count($this->requests['sorts'])) {
            list($sort, $order) = each($this->requests['sorts']);
            $this->requests['sort'] = $sort;
            $this->requests['order'] = $order;
        }
    }
}