<?php

use App\Classes\Redirect;
use App\Classes\Session;
use App\Classes\User;

require_once 'core/app.php';

if ((new User())->logout()) {
    Session::flash('status', 'You have logged out successfully.');
    Redirect::to('login.php');
} else {
    Session::flash('status', 'You are not logged in.');
    Redirect::to('login.php');
}