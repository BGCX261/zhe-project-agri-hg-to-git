<?php

/**
* _default method combines the options array with a set of defaults giving the values in the options array priority.
*
* @param array $defaults
* @param array $options
* @return array
*/
function _default($defaults, $options) {
    return array_merge($defaults, $options);
}

/**
* _required method returns false if the $data array does not contain all of the keys assigned by the $required array.
*
* @param array $required
* @param array $data
* @return bool
*/
function _required($required, $data) {
    foreach($required as $field) if(!isset($data[$field])) return false;
    return true;
}