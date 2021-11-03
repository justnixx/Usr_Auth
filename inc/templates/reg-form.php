<form class="card" action="register.php" method="POST" autocomplete="off">
    <input type="hidden" name="token" value="<?php echo App\Classes\Token::generate(); ?>">
    <fieldset class="flex">
        <input type="text" name="name" value="<?php echo App\Classes\Input::get('name'); ?>" placeholder="Name">
    </fieldset>
    <div class="error">
        <?php if (error('name', 'validation')) : ?>
        <small><?php echo error('name', 'validation'); ?></small>
        <?php endif; ?>
    </div>
    <fieldset class="flex">
        <input type="text" name="username" value="<?php echo App\Classes\Input::get('username'); ?>"
            placeholder="Username">
        <input type="email" name="email" value="<?php echo App\Classes\Input::get('email'); ?>" placeholder="Email">
    </fieldset>
    <div class="error">
        <?php if (error('username', 'validation')) : ?>
        <small><?php echo error('username', 'validation'); ?></small>
        <?php endif; ?>
        <?php if (error('email', 'validation')) : ?>
        <small><?php echo error('email', 'validation'); ?></small>
        <?php endif; ?>
    </div>
    <fieldset class="flex">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="password_confirmation" placeholder="Confirm password">
    </fieldset>
    <div class="error">
        <?php if (error('password', 'validation')) : ?>
        <small><?php echo error('password', 'validation'); ?></small>
        <?php endif; ?>
        <?php if (error('password_confirmation', 'validation')) : ?>
        <small><?php echo error('password_confirmation', 'validation'); ?></small>
        <?php endif; ?>
    </div>
    <button>Register</button>
    <small>Already have an account? click <a href="login.php"><b>here</b></a> to log in.</small>
</form>