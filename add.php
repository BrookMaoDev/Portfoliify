<?php

/**
 * Description: A page to add a new resume.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";
require_once "pdo.php";

// $_POST keys
const ADD_KEY = "add";
const CANCEL_KEY = "cancel";
const FNAME_KEY = "fname";
const LNAME_KEY = "lname";
const EMAIL_KEY = "email";
const HEADLINE_KEY = "headline";
const SUMM_KEY = "summary";

session_start();

$user_id = $_SESSION[USER_ID_KEY];
$name = $_SESSION[USER_NAME_KEY];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Adding Profile for <?= $name ?></h1>
    <form method="post">
        <div class="text-field">
            First Name<br>
            <input type="text" name="<?= FNAME_KEY ?>">
        </div>
        <div class="text-field">
            Last Name<br>
            <input type="text" name="<?= LNAME_KEY ?>">
        </div>
        <div class="text-field">
            Email<br>
            <input type="text" name="<?= EMAIL_KEY ?>">
        </div>
        <div class="text-field">
            Headline<br>
            <input type="text" name="<?= HEADLINE_KEY ?>">
        </div>
        <div class="text-field">
            Summary<br>
            <input type="text" name="<?= SUMM_KEY ?>">
        </div>
        <input type="submit" name="<?= ADD_KEY ?>" value="Add">
        <input type="submit" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
</body>

</html>