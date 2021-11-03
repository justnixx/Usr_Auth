<?php

require_once 'core/app.php';

use App\Classes\Input;
use App\Classes\Redirect;
use App\Classes\Session;
use App\Classes\Token;
use App\Classes\User;
use App\Classes\Validate;

if (Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check(Input::all(), [
        'username' => 'required',
        'password' => 'required'
    ]);

    if ($validation->passed() && Token::check(Input::get('token'))) {
        $user = new User();
        $remember_me = (Input::get('login_remember_me') === 'on') ? true : false;
        $login =  $user->login(Input::get('username'), Input::get('password'), $remember_me);

        if ($login) {
            Session::flash('status', 'Login successful.');
            Redirect::to('dashboard.php');
        } else {
            Session::flash('status', 'Invalid login.');
        }
    }
}

get_header();
?>

<div class="container flex flex-column h100v">
    <?php if (Session::has('status')) : ?>
    <p class="status"><?php echo Session::flash('status'); ?></p>
    <?php endif; ?>
    <form class="card" action="" method="POST" autocomplete="off">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <fieldset class="flex">
            <input type="text" name="username" value="<?php echo Input::get('username'); ?>" placeholder="Username">
        </fieldset>
        <div class="error">
            <?php if (error('username', 'validation')) : ?>
            <small><?php echo error('username', 'validation'); ?></small>
            <?php endif; ?>
        </div>
        <fieldset class="flex">
            <input type="password" name="password" placeholder="Password">
        </fieldset>
        <div class="error">
            <?php if (error('password', 'validation')) : ?>
            <small><?php echo error('password', 'validation'); ?></small>
            <?php endif; ?>
        </div>
        <fieldset class="flex">
            <label for="login_remember_me"><input type="checkbox" id="login_remember_me" name="login_remember_me">Keep
                me logged in</label>
        </fieldset>
        <button>Login</button>
        <small>Don't have an account? click <a href="register.php"><b>here</b></a> to register.</small>
    </form>
</div>

<?php
get_footer();