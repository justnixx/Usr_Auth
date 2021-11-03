<?php

use App\Classes\Config;
use App\Classes\Cookie;
use App\Classes\Redirect;
use App\Classes\Session;
use App\Classes\User;

session_start();

$GLOBALS['config'] = parse_ini_file('./.env');

// Autoload classes
spl_autoload_register(function ($path_to_class) {
    $namespace = 'App'; // root namespace
    $path_to_class = explode('\\', $path_to_class);

    if ($namespace !== $path_to_class[0]) {
        return; // bail out - not our namespace
    }

    require_once strtolower($path_to_class[1]) . "/{$path_to_class[2]}.php";
});

// Include functions 
include_once './functions/functions.php';

// Attempt to log user with remember token
if (!Session::has(Config::get('SESSION_NAME')) && Cookie::has(Config::get('COOKIE_NAME'))) {
    $user = new User();

    if ($user->login()) {
        Redirect::to('dashboard.php');
    }
}