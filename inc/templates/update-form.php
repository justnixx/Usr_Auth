<?php

use App\Classes\Session;

$user = $GLOBALS['user'];
?>

<form class="card" action="" method="POST" autocomplete="off">
    <?php if (Session::has('status')) : ?>
    <p class="status"><?php echo Session::flash('status'); ?></p>
    <?php endif; ?>
    <input type="hidden" name="token" value="<?php echo App\Classes\Token::generate(); ?>">
    <fieldset class="flex">
        <input type="text" name="name" value="<?php echo $user->data()->name; ?>" placeholder="Name">
    </fieldset>
    <div class="error">
        <?php if (error('name', 'validation')) : ?>
        <small><?php echo error('name', 'validation'); ?></small>
        <?php endif; ?>
    </div>
    <fieldset class="flex">
        <input type="text" name="username" value="<?php echo $user->data()->username; ?>" placeholder="Username">
        <input type="email" name="email" value="<?php echo $user->data()->email; ?>" placeholder="Email">
    </fieldset>
    <div class="error">
        <?php if (error('username', 'validation')) : ?>
        <small><?php echo error('username', 'validation'); ?></small>
        <?php endif; ?>
        <?php if (error('email', 'validation')) : ?>
        <small><?php echo error('email', 'validation'); ?></small>
        <?php endif; ?>
    </div>
    <button>Update</button>
    <small><a href="change-password.php">Change password</a></small>
</form>