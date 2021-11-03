<?php

use App\Classes\Config;
use App\Classes\Hash;
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
        'current_password'          => 'required',
        'new_password'              => 'required|min:6',
        'new_password_confirmation' => 'required|matches:new_password'
    ]);

    if ($validation->passed() && Token::check(Input::get('token'))) {
        $hash = $user->data()->password;

        if (!Hash::check(Input::get('current_password'), $hash)) {
            Session::flash('status', 'Incorrect password.');
            Redirect::back();
        }


        if (Hash::check(Input::get('new_password'), $hash)) {
            Session::flash('status', 'Please enter a new password.');
            Redirect::back();
        }

        $updated = $user->update($user->data()->id, [
            'password' => Hash::make(Input::get('new_password'))
        ]);

        if ($updated) {
            Session::flash('status', 'Your password has changed.');
            $user->logout();
            Redirect::to('login.php');
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
                    <a href="profile.php">My Profile</a>
                </li>
                <li>
                    <a href="logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="container">
    <form class="card" action="" method="POST" autocomplete="off">
        <?php if (Session::has('status')) : ?>
        <p class="status"><?php echo Session::flash('status'); ?></p>
        <?php endif; ?>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <fieldset class="flex">
            <input type="password" name="current_password" value="<?php echo Input::get('name'); ?>"
                placeholder="Current password">
        </fieldset>
        <div class="error">
            <?php if (error('current_password', 'validation')) : ?>
            <small><?php echo error('current_password', 'validation'); ?></small>
            <?php endif; ?>
        </div>
        <fieldset class="flex">
            <input type="password" name="new_password" placeholder="New password">
            <input type="password" name="new_password_confirmation" placeholder="Confirm new password">
        </fieldset>
        <div class="error">
            <?php if (error('new_password', 'validation')) : ?>
            <small><?php echo error('new_password', 'validation'); ?></small>
            <?php endif; ?>
            <?php if (error('new_password_confirmation', 'validation')) : ?>
            <small><?php echo error('new_password_confirmation', 'validation'); ?></small>
            <?php endif; ?>
        </div>
        <button>Change</button>
    </form>
</div>