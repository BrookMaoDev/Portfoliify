<?php

/**
 * Description: This file is responsible for handling the signup process.
 * Author: Brook Mao
 * Created: July 9, 2024
 */

require_once "db_connection.php";
require_once "db_queries.php";
require_once "session_helpers.php";

// Error messages
const MISSING_FIELD_MSG = "All fields are required.";
const BAD_EMAIL_MSG = "Email address must contain '@'.";
const EMAIL_EXISTS_MSG = "The email address is already in use.";
const SUCCESS_MSG = "You have successfully signed up!";

// $_POST and $_SESSION keys
const NAME_KEY = "name";
const EMAIL_KEY = "email";
const PSWD_KEY = "pass";
const SIGNUP_KEY = "signup";

session_start();

checkUserHitCancel();

/**
 * Validates the format of the email address and password.
 */
function validateSignupInfoFormat()
{
    if (
        empty($_POST[NAME_KEY]) ||
        empty($_POST[EMAIL_KEY]) ||
        empty($_POST[PSWD_KEY])
    ) {
        $_SESSION[ERROR_MSG_KEY] = MISSING_FIELD_MSG;
        header("Location: " . basename(__FILE__));
        exit();
    }

    if (strpos($_POST[EMAIL_KEY], "@") === false) {
        $_SESSION[ERROR_MSG_KEY] = BAD_EMAIL_MSG;
        header("Location: " . basename(__FILE__));
        exit();
    }

    if (getUserByEmail($_POST[EMAIL_KEY], $GLOBALS["db"]) !== false) {
        $_SESSION[ERROR_MSG_KEY] = EMAIL_EXISTS_MSG;
        header("Location: " . basename(__FILE__));
        exit();
    }
}

if (isset($_POST[SIGNUP_KEY])) {
    validateSignupInfoFormat();
    insertUser($_POST[EMAIL_KEY], $_POST[PSWD_KEY], $_POST[NAME_KEY], $db);
    $_SESSION[SUCCESS_MSG_KEY] = SUCCESS_MSG;
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfoliify</title>

    <!-- CSS Imports -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/styles.css">
</head>

<body>
    <div class="spacer"></div>
    <h1>Sign Up</h1>
    <div class="small-spacer"></div>

    <?php if (isset($_SESSION[ERROR_MSG_KEY])) {
        echo "<div class='alert alert-danger' role='alert'>" .
            $_SESSION[ERROR_MSG_KEY] .
            "</div>";
        echo "<div class='small-spacer'></div>";
        unset($_SESSION[ERROR_MSG_KEY]);
    } ?>

    <form method="post" class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control">
        <div class="small-spacer"></div>

        <label for="email">Email</label>
        <input name="email" class="form-control">
        <div class="small-spacer"></div>

        <label for="pass">Password</label>
        <input type="password" name="pass" class="form-control">
        <div class="spacer"></div>

        <div class="button-container">
            <input type="submit" class="btn btn-outline-success" value="Sign Up" name="<?= SIGNUP_KEY ?>">
            <input type="submit" class="btn btn-outline-danger" value="Cancel" name="<?= CANCEL_KEY ?>">
        </div>
    </form>
    <div class="spacer"></div>
</body>

</html>