<?php
/**
 * @Description of classloader
 * Uses the Standard PHP Library (SPL) function 
 * spl_autoload_register
 */
spl_autoload_register(function ($class) {
    include COREPATH . '/classes/' . strtolower($class). '.php';
});