<?php

if (! function_exists('add_error')) {
    function add_error($errors, $name, $error_class = ' is-invalid '){
        return $errors && $errors->has($name) ? $error_class : '';
    }
}