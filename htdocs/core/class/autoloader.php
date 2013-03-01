<?php

/**
 * Autoloader
 *
 * @param $className string
 * @return void
 */
spl_autoload_register(function($classname) {
    include str_replace(
        array('_', '\\'), '/', $classname
    ) . '.php';
});