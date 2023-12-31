<?php

/**
 * Description: A page with an email address and password to get the user to log in.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";
require_once "pdo.php";

const MISSING_FIELD_MSG = "All fields are required";
const BAD_EMAIL_MSG = "Invalid email address";
const INCORRECT_PSWD_MSG = "The email address or password is incorrect";

// $_POST keys
const LOGIN_KEY = "login";
const CANCEL_KEY = "cancel";

session_start();

// User wants to leave page
if (isset($_POST[CANCEL_KEY])) {
    header("Location: index.php");
    exit;
}

// User pressed login and data successfully validated on browser side
if (isset($_POST[LOGIN_KEY])) {
    validateLoginInfoFormat(); // Server side validation. If valid, below code runs.
    $_SESSION[EMAIL_KEY] = $_POST[EMAIL_KEY];
    $_SESSION[PSWD_KEY] = $_POST[PSWD_KEY];
    header("Location: " . basename(__FILE__));
    exit;
}

// User pressed login and data successfully validated on server side
if (isset($_SESSION[EMAIL_KEY]) || isset($_SESSION[PSWD_KEY])) {
    $user_info = getUsers($_SESSION[EMAIL_KEY], $_SESSION[PSWD_KEY], $db);
    if ($user_info === false) { // User does not exist
        unset($_SESSION[EMAIL_KEY]);
        unset($_SESSION[PSWD_KEY]);
        $_SESSION[ERROR_MSG_KEY] = INCORRECT_PSWD_MSG;
        header("Location: " . basename(__FILE__));
        exit;
    }
    unset($_SESSION[EMAIL_KEY]);
    unset($_SESSION[PSWD_KEY]);
    $_SESSION[USER_ID_KEY] = $user_info[USER_ID_COLNAME];
    $_SESSION[USER_NAME_KEY] = $user_info[USER_NAME_COLNAME];
    header("Location: index.php");
    exit;
}

function validateLoginInfoFormat()
{
    $email = $_POST[EMAIL_KEY];
    $pswd = $_POST[PSWD_KEY];

    if (strlen($email) === 0 || strlen($pswd) === 0) {
        $_SESSION[ERROR_MSG_KEY] = MISSING_FIELD_MSG;
        header("Location: " . basename(__FILE__));
        exit;
    } elseif (!str_contains($email, "@")) {
        $_SESSION[ERROR_MSG_KEY] = BAD_EMAIL_MSG;
        header("Location: " . basename(__FILE__));
        exit;
    }
}

/**
 * Returns a user in $db with the given $email and $pswd if they exist.
 */
function getUsers(string $email, string $pswd, PDO $db): array|bool
{
    $hashed_pswd = hash(HASH_METHOD, SALT . $pswd);

    $stmt = $db->prepare("SELECT " . USER_ID_COLNAME . ", " . USER_NAME_COLNAME . " FROM " . USERS_TABLE . " WHERE " . USER_EMAIL_COLNAME . " = :email AND " . USER_PSWD_COLNAME . " = :password");
    $stmt->execute(array(
        ":email" => $email,
        ":password" => $hashed_pswd
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row;
}

?>

<html>

<head>
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="styles.css">
    <script src="form_validations.js"></script>
</head>

<body>
    <h1>Please Log In</h1>
    <?php
    if (isset($_SESSION[ERROR_MSG_KEY])) { // User bypassed browser side data validation but failed on the server side
        echo '<p style="color: red;">' . $_SESSION[ERROR_MSG_KEY] . '</p>';
    }
    unset($_SESSION[ERROR_MSG_KEY]);
    ?>
    <form method="post">
        <div class="text-field">
            Email<br>
            <input type="text" name="<?= EMAIL_KEY ?>" id="email">
        </div>
        <div class="text-field">
            Password<br>
            <input type="password" name="<?= PSWD_KEY ?>" id="pswd">
        </div>
        <input type="submit" value="Log In" name="<?= LOGIN_KEY ?>" onclick="return validateLoginInfoFormat();">
        <input type="submit" value="Cancel" name="<?= CANCEL_KEY ?>">
    </form>
</body>

</html>