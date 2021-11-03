<?php

require_once 'core/app.php';

use App\Classes\Hash;
use App\Classes\Input;
use App\Classes\Redirect;
use App\Classes\Session;
use App\Classes\Token;
use App\Classes\User;
use App\Classes\Validate;

if (Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check(Input::all(), [
        'username'              => 'required|min:3|max:20',
        'name'                  => 'required|min:3|max:100',
        'email'                 => 'required|email:unique:users',
        'password'              => 'required|min:6',
        'password_confirmation' => 'required|matches:password'
    ]);

    if ($validation->passed() && Token::check(Input::get('token'))) {
        $user = new User();
        $created = $user->create([
            'name'     => Input::get('name'),
            'username' => Input::get('username'),
            'email'    => Input::get('email'),
            'password' => Hash::make(Input::get('password'))
        ]);

        if ($created) {
            Session::flash('status', 'Your registration was successful. Log in with your details below.');
            Redirect::to('login.php');
        }
    }
}

get_header();
?>

<div class="container flex flex-column h100v">
    <?php get_template('reg-form'); ?>
</div>

<?php
get_footer();