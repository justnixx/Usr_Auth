<?php

use App\Classes\Config;
use App\Classes\Redirect;
use App\Classes\Session;
use App\Classes\User;

require_once 'core/app.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Session::flash('status', 'Unauthorized.');
    Redirect::to('login.php');
}

get_header();
?>

<header class="app-header">
    <nav>
        <div class="container flex">
            <a href="#"><?php echo Config::get('APP_NAME'); ?></a>
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
    <div class="card">
        <?php if (Session::has('status')) : ?>
        <p class="status"><?php echo Session::flash('status'); ?></p>
        <?php endif; ?>

        <p class="greet"></p>
    </div>
</div>

<script>
const date = new Date();
const hour = date.getHours();
let timeOfDay = '';
if (hour < 12) {
    timeOfDay = 'morning';
} else if (hour >= 12 && hour < 16) {
    timeOfDay = 'afternoon';
} else {
    timeOfDay = 'evening';
}
let greet = 'Good ' + timeOfDay + ', ';
let user = '<?php echo $user->data()->username; ?>';
user = user[0].toUpperCase() + user.slice(1) + '!';
greet += user;
document.querySelector('.greet').textContent = greet;
</script>

<?php
get_footer();