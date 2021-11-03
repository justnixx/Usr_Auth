<?php

use App\Classes\AppError;
use App\Classes\Validate;

/**-----------------------------------------------------
 * Utility functions
 -----------------------------------------------------*/

function asset($file_path = null)
{
    $file_path = '/css/app.css';
    $scheme = (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS']) ? 'https' : 'http';
    $url = "{$scheme}://" . dirname($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . "/assets/" . ltrim($file_path);
    return $url;
}

function get_header(string $name = null)
{
    if ($name) {
        include_once "header-{$name}.php";
    } else {
        include_once 'header.php';
    }
}

function get_footer(string $name = null)
{
    if ($name) {
        include_once "footer-{$name}.php";
    } else {
        include_once 'footer.php';
    }
}

function parse_snake_case(string $str)
{
    return str_replace('_', ' ', $str);
}

function title_case(string $str)
{
    return ucfirst($str);
}

function get_template(string $name)
{
    include_once "inc/templates/{$name}.php";
}

function error(string $code, string $errors)
{
    if (isset($GLOBALS[$errors]) && $errors = $GLOBALS[$errors]) {
        return ($errors->errors()->has($code)) ? $errors->errors()->getMessage($code) : null;
    }
}