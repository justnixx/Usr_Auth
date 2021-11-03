<?php

use App\Classes\DB;

require_once 'core/app.php';

$db = DB::getInstance();

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS users(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(20),
    `password` VARCHAR(256),
    `email` VARCHAR(256),
    `name` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (id)
);
CREATE TABLE IF NOT EXISTS users_session(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `token` VARCHAR(64),
    PRIMARY KEY (id)

);
SQL;

get_header();
?>

<div class="container flex h100v">
    <?php if ($db->query($sql)) : ?>
    <p>Success.</p>
    <?php else : ?>
    <p>Failure.</p>
    <?php endif; ?>
</div>

<?php
get_footer();