<?php

/**
 * Description: A page with an email address and password to get the user to log in.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";
?>

<html>

<head>
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Please Log In</h1>
    <form method="post">
        <div class="text-field">
            Email<br>
            <input type="text" name="<?= EMAIL_KEY ?>">
        </div>
        <div class="text-field">
            Password<br>
            <input type="password" name="<?= PSWD_KEY ?>">
        </div>
    </form>
</body>

</html>