<?php

use App\Classes\Config;
use App\Classes\Input;
use App\Classes\Redirect;
use App\Classes\Session;
use App\Classes\Token;
use App\Classes\User;
use App\Classes\Validate;

require_once 'core/app.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Session::flash('status', 'Unauthorized.');
    Redirect::to('login.php');
}

if (Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check(Input::all(), [
        'username' => 'required|min:3|max:20',
        'name'     => 'required|min:3|max:100',
        'email'    => 'required|email:unique:users',
    ]);

    if ($validation->passed() && Token::check(Input::get('token'))) {
        $updated = $user->update($user->data()->id, [
            'username' => Input::get('username'),
            'name'     => Input::get('name'),
            'email'    => Input::get('email')
        ]);

        if ($updated) {
            Session::flash('status', 'User information has been updated.');
            Redirect::back();
        }
    }
}

get_header();
?>

<header class="app-header">
    <nav>
        <div class="container flex">
            <a href="dashboard.php"><?php echo Config::get('APP_NAME'); ?></a>
            <ul>
                <li>
                    <a href="#">My Profile</a>
                </li>
                <li>
                    <a href="logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="container">
    <?php get_template('update-form'); ?>
</div>

<?php
get_footer();