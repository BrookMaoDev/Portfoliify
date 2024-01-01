<?php

/**
 * Description: A page to add a new resume.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";
require_once "pdo.php";

const MISSING_FIELD_MSG = "All fields are required";
const BAD_EMAIL_MSG = "Email address must contain @";
const SUCCESS_MSG = "Profile added";
const NOT_LOGGED_IN_MSG = "You are not logged in";

// $_POST keys
const ADD_KEY = "add";
const CANCEL_KEY = "cancel";

// $_POST and $_SESSION keys
const FNAME_KEY = "fname";
const LNAME_KEY = "lname";
const EMAIL_KEY = "email";
const HEADLINE_KEY = "headline";
const SUMM_KEY = "summary";

session_start();

if (!isset($_SESSION[USER_ID_KEY]) || !isset($_SESSION[USER_NAME_KEY])) {
    die(NOT_LOGGED_IN_MSG);
}

// User wants to leave page
if (isset($_POST[CANCEL_KEY])) {
    header("Location: index.php");
    exit;
}

// User wants to add a new entry
if (isset($_POST[ADD_KEY])) {
    validateProfileFields(); // Server side validation. If valid, below code runs.
    $_SESSION[FNAME_KEY] = $_POST[FNAME_KEY];
    $_SESSION[LNAME_KEY] = $_POST[LNAME_KEY];
    $_SESSION[EMAIL_KEY] = $_POST[EMAIL_KEY];
    $_SESSION[HEADLINE_KEY] = $_POST[HEADLINE_KEY];
    $_SESSION[SUMM_KEY] = $_POST[SUMM_KEY];
    header("Location: " . basename(__FILE__));
    exit;
}

// User's new entry inputs are valid and are now in the session
if (isset($_SESSION[FNAME_KEY])) {
    insertResume(
        $db,
        $_SESSION[FNAME_KEY],
        $_SESSION[LNAME_KEY],
        $_SESSION[EMAIL_KEY],
        $_SESSION[HEADLINE_KEY],
        $_SESSION[SUMM_KEY]
    );

    unset($_SESSION[FNAME_KEY]);
    unset($_SESSION[LNAME_KEY]);
    unset($_SESSION[EMAIL_KEY]);
    unset($_SESSION[HEADLINE_KEY]);
    unset($_SESSION[SUMM_KEY]);

    $_SESSION[SUCCESS_MSG_KEY] = SUCCESS_MSG;
    header("Location: index.php");
    exit;
}

function validateProfileFields()
{
    // $_POST data
    $fname = $_POST[FNAME_KEY];
    $lname = $_POST[LNAME_KEY];
    $email = $_POST[EMAIL_KEY];
    $headline = $_POST[HEADLINE_KEY];
    $summ = $_POST[SUMM_KEY];

    if (
        strlen($fname) === 0
        || strlen($lname) === 0
        || strlen($email) === 0
        || strlen($headline) === 0
        || strlen($summ) === 0
    ) {
        $_SESSION[ERROR_MSG_KEY] = MISSING_FIELD_MSG;
        header("Location: " . basename(__FILE__));
        exit;
    } elseif (!str_contains($email, "@")) {
        $_SESSION[ERROR_MSG_KEY] = BAD_EMAIL_MSG;
        header("Location: " . basename(__FILE__));
        exit;
    }
}

function insertResume(PDO $db, string $fname, string $lname, string $email, string $headline, string $summ)
{
    $stmt = $db->prepare("INSERT INTO profile ("
        . PROFILE_USER_ID_COLNAME . ", "
        . PROFILE_FNAME_COLNAME . ", "
        . PROFILE_LNAME_COLNAME . ", "
        . PROFILE_EMAIL_COLNAME . ", "
        . PROFILE_HEADLINE_COLNAME . ", "
        . PROFILE_SUMM_COLNAME . ")
    VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)");

    $stmt->execute(array(
        ":user_id" => $_SESSION[USER_ID_KEY],
        ":first_name" => $fname,
        ":last_name" => $lname,
        ":email" => $email,
        ":headline" => $headline,
        ":summary" => $summ,
    ));
}
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
    <h1>Adding Profile for <?= $_SESSION[USER_NAME_KEY] ?></h1>
    <?php
    if (isset($_SESSION[ERROR_MSG_KEY])) {
        echo "<p style='color: red;'>" . $_SESSION[ERROR_MSG_KEY] . "</p>";
        unset($_SESSION[ERROR_MSG_KEY]);
    }
    ?>
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
            <textarea name="<?= SUMM_KEY ?>" rows="10" cols="60"></textarea>
        </div>
        <input type="submit" name="<?= ADD_KEY ?>" value="Add">
        <input type="submit" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
</body>

</html>