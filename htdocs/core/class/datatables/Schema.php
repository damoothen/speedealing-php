<?php

namespace datatables;

class Schema {

    protected $schema = array();

    /* ______________________________________________________________________ */

    public function __construct(array $schema = array()) {
        foreach($schema as $field => $config) {
            $this->push($field, $config);
        }
        $this->init();
    }

    /* ______________________________________________________________________ */

    public function init() {}

    /* ______________________________________________________________________ */

	public function getConfig($field, $key) {
		return isset($this->schema[$field][$key]) ? $this->schema[$field][$key] : null;
	}

    /* ______________________________________________________________________ */

	public function setConfig($field, $key, $value = null) {
        if(isset($this->schema[$field])) {
            $this->schema[$field][$key] = $value;
            return true;
        }
		return false;
	}

    /* ______________________________________________________________________ */

	public function push($field, array $config) {
		$this->schema[$field] = $this->_formatConfig($config);
		return $this;
	}

    /* ______________________________________________________________________ */

	public function unshift($field, array $config) {
		$data = array($field => $this->_formatConfig($config));
        array_unshift($this->schema, $data);
		return $this;
	}

    /* ______________________________________________________________________ */

	public function data() {
        return $this->schema;
	}

    /* ______________________________________________________________________ */

	public function isSortable($field) {
		if( ! isset($this->schema[$field])) {
            return false;
        }
		return (
            $this->schema[$field]['sortable'] == true &&
            $this->schema[$field]['type'] == 'dynamic'
        ) ? true : false;
	}

    /* ______________________________________________________________________ */

	public function isSearchable($field) {
		if( ! isset($this->schema[$field])) {
            return false;
        }
		return ($this->schema[$field]['searchable'] == true) ? true : false;
	}

    /* ______________________________________________________________________ */

	public function getDefaultValues() {
        $data = array();
        foreach($this->schema as $field => $config) {
            $data[$field] = $config['default'];
        }
        return $data;
	}

	/* ______________________________________________________________________ */

	public function getType() {
		$data = array();
		foreach($this->schema as $field => $config) {
			$data[$field] = $config['type'];
		}
		return $data;
	}

    /* ______________________________________________________________________ */

	public function getLabels() {
        $data = array();
        foreach($this->schema as $field => $config) {
            $data[$field] = $config['label'];
        }
        return $data;
	}

    /* ______________________________________________________________________ */
    /** @todo use array_filter instead of foreach iteration */
	public function getVisibleColumns() {
        $data = array();
        foreach($this->schema as $field => $config) {
            if($config['visible'] === true) {
                $data[] = $field;
            }
        }
        return $data;
	}

    /* ______________________________________________________________________ */

    public function adapt($data, \Closure $rowFilter = null) {
        if(is_array($data) OR (is_object($data) && $data instanceof \Iterator)) {
            $defaults = $this->getDefaultValues();

            $newData = array();
            foreach($data as $key => $value) {
                if($rowFilter) {
                    $value = $rowFilter($value);
                }

                $temp = array_merge($defaults, array_intersect_key($value, $this->schema));

                foreach($temp as $key => $cell) {
                    $callback = $this->schema[$key]['outputFilter'];
                    if(is_callable($callback)) {
                        $temp[$key] = $callback($cell, $value);
                    }
                }

                $newData[] = array_values($temp);
            }

            return $newData;
        }

        throw new \UnexpectedValueException(
            "Could not adapt data into schema. Data must be an array or an Iterator."
        );
    }

    /* ______________________________________________________________________ */

    protected function filter($name, array $config = array()) {
        return $this->createInstanceOf(
            $name, __NAMESPACE__ . '\\filters\\', __NAMESPACE__ . '\\FilterInterface', $config);
    }

    /* ______________________________________________________________________ */

    public function element($name, array $config = array()) {
        return $this->createInstanceOf(
            $name, __NAMESPACE__ . '\\elements\\', __NAMESPACE__ . '\\ElementInterface', $config);
    }

    /* ______________________________________________________________________ */

    private function createInstanceOf($name, $prefix, $interface, array $config = array()) {
        if (is_string($name) && ($name = ucwords(str_replace(array("_", '-'), " ", $name)))) {
            $class = $prefix . $name;

            if ( ! class_exists($class)) {
                $class = $name;
                if ( ! class_exists($class)) {
                    throw new RuntimeException('Class not found: ' . $class);
                }
            }

            $rc = new \ReflectionClass($class);
            if ( ! $rc->implementsInterface($interface)) {
                throw new DomainException(sprintf('`%s` must implements `%s`.', $class, $interface));
            }

            $obj = $rc->newInstanceArgs($config);
            return $obj;
        } elseif ($name instanceof $name) {
            return $name;
        }

        $type = is_object($name) ? get_class($name) : gettype($name);
        throw new InvalidArgumentException(
            sprintf('%s expects args #1 to be string or `%s`, %s given.', __METHOD__, $interface, $type)
        );
    }

    /* ______________________________________________________________________ */

    protected function _formatConfig(array $config) {
        $defaults = array(
        		'outputFilter'	=> null,        # Usually a closure object to format output
        		'inputFilter'	=> null,        # Usually a closure object to format output
        		'type'			=> 'dynamic',   # Define column type. Either `dynamic` or `static`
        		'visible'		=> true,
        		'sortable'		=> true,
        		'searchable'	=> true,
        		'editable'		=> false,
        		'width'			=> false,
        		'class'			=> '',
        		'label'			=> '',
        		'default'		=> '',
        		'render'		=> '',
        		'footer'		=> '',
        		'key_prefix'    => '',
        		'aoColumns'     => array()
		);

        return $config + $defaults;
    }
}