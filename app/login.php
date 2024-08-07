<?php

/**
 * Description: A page to log in with an email address and password.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "db_connection.php";
require_once "db_queries.php";
require_once "session_helpers.php";

// Error messages
const MISSING_FIELD_MSG = "All fields are required.";
const BAD_EMAIL_MSG = "Email address must contain '@'.";
const INCORRECT_PSWD_MSG = "The email address or password is incorrect.";

// $_POST and $_SESSION keys
const EMAIL_KEY = "email";
const PSWD_KEY = "pass";
const LOGIN_KEY = "login";

session_start();

// Check if the user hit the cancel button
checkUserHitCancel();

// User pressed login and data successfully validated on the browser side
if (isset($_POST[LOGIN_KEY])) {
    validateLoginInfoFormat(); // Server side validation. If valid, below code runs.
    $_SESSION[EMAIL_KEY] = $_POST[EMAIL_KEY];
    $_SESSION[PSWD_KEY] = $_POST[PSWD_KEY];
    header("Location: " . basename(__FILE__));
    exit();
}

// User pressed login and data successfully validated on the server side
if (isset($_SESSION[EMAIL_KEY]) && isset($_SESSION[PSWD_KEY])) {
    $user_info = getUsers($_SESSION[EMAIL_KEY], $_SESSION[PSWD_KEY], $db);

    if ($user_info === false) {
        // User does not exist
        unset($_SESSION[EMAIL_KEY]);
        unset($_SESSION[PSWD_KEY]);

        $_SESSION[ERROR_MSG_KEY] = INCORRECT_PSWD_MSG;
        header("Location: " . basename(__FILE__));
        exit();
    }

    unset($_SESSION[EMAIL_KEY]);
    unset($_SESSION[PSWD_KEY]);

    $_SESSION[USER_ID_KEY] = $user_info[USER_ID_COLNAME];
    $_SESSION[USER_NAME_KEY] = $user_info[USER_NAME_COLNAME];

    header("Location: index.php");
    exit();
}

/**
 * Validates the login information format.
 * Redirects back to the login page with an error message if validation fails.
 */
function validateLoginInfoFormat()
{
    $email = $_POST[EMAIL_KEY];
    $pswd = $_POST[PSWD_KEY];

    if (strlen($email) === 0 || strlen($pswd) === 0) {
        $_SESSION[ERROR_MSG_KEY] = MISSING_FIELD_MSG;
        header("Location: " . basename(__FILE__));
        exit();
    } elseif (!str_contains($email, "@")) {
        $_SESSION[ERROR_MSG_KEY] = BAD_EMAIL_MSG;
        header("Location: " . basename(__FILE__));
        exit();
    }
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

    <!-- JS Import for client-side validation -->
    <script src="./static/validation.js"></script>
</head>

<body>
    <div class="spacer"></div>
    <h1>Login</h1>
    <div class="small-spacer"></div>

    <?php if (isset($_SESSION[ERROR_MSG_KEY])) {
        // User bypassed browser side data validation but failed on the server side
        echo "<div class='alert alert-danger' role='alert'>" .
            $_SESSION[ERROR_MSG_KEY] .
            "</div>";
        echo '<div class="small-spacer"></div>';
        unset($_SESSION[ERROR_MSG_KEY]);
    } elseif (isset($_SESSION[SUCCESS_MSG_KEY])) {
        // User successfully signed up
        echo "<div class='alert alert-success' role='alert'>" .
            $_SESSION[SUCCESS_MSG_KEY] .
            "</div>";
        echo '<div class="small-spacer"></div>';
        unset($_SESSION[SUCCESS_MSG_KEY]);
    } ?>

    <form method="post" class="form-group">
        <div>
            <label for="<?= EMAIL_KEY ?>">Email</label>
            <input type="text" class="form-control" name="<?= EMAIL_KEY ?>" id="email">
        </div>
        <div class="small-spacer"></div>
        <div>
            <label for="<?= PSWD_KEY ?>">Password</label>
            <input type="password" class="form-control" name="<?= PSWD_KEY ?>" id="pswd">
        </div>
        <div class="spacer"></div>
        <div class="button-container">
            <input type="submit" class="btn btn-outline-success" value="Log In" name="<?= LOGIN_KEY ?>" onclick="return validateLoginInfoFormat();">
            <input type="submit" class="btn btn-outline-danger" value="Cancel" name="<?= CANCEL_KEY ?>">
        </div>
    </form>
    <div class="spacer"></div>
</body>

</html>