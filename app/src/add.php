<?php

/**
 * Description: A page to add a new resume.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";
require_once "pdo.php";
require_once "db_queries.php";
require_once "process_superglobals.php";
require_once "validations.php";

const MISSING_FIELD_MSG = "All fields are required";
const BAD_EMAIL_MSG = "Email address must contain @";
const SUCCESS_MSG = "Profile added";

// $_POST keys
const ADD_KEY = "add";
const CANCEL_KEY = "cancel";

// $_POST and $_SESSION keys
const FNAME_KEY = "first_name";
const LNAME_KEY = "last_name";
const EMAIL_KEY = "email";
const HEADLINE_KEY = "headline";
const SUMM_KEY = "summary";
const POSITIONS_ARRAY_KEY = "positions";
const EDUCATIONS_ARRAY_KEY = "educations";

session_start();

checkLoggedIn();
checkUserHitCancel();

// User wants to add a new entry
if (isset($_POST[ADD_KEY])) {
    validateProfileFields(); // Server side validation. If valid, below code runs.
    $positionsArray = validatePositions();
    $educationsArray = validateEducations();
    if (gettype($positionsArray) === "string") { // Then it's an error message.
        $_SESSION[ERROR_MSG_KEY] = $positionsArray;
        header("Location: " . basename(__FILE__));
        exit;
    } else if (gettype($educationsArray) === "string") {
        $_SESSION[ERROR_MSG_KEY] = $educationsArray;
        header("Location: " . basename(__FILE__));
        exit;
    }
    $_SESSION[FNAME_KEY] = $_POST[FNAME_KEY];
    $_SESSION[LNAME_KEY] = $_POST[LNAME_KEY];
    $_SESSION[EMAIL_KEY] = $_POST[EMAIL_KEY];
    $_SESSION[HEADLINE_KEY] = $_POST[HEADLINE_KEY];
    $_SESSION[SUMM_KEY] = $_POST[SUMM_KEY];
    $_SESSION[POSITIONS_ARRAY_KEY] = $positionsArray;
    $_SESSION[EDUCATIONS_ARRAY_KEY] = $educationsArray;
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
        $_SESSION[SUMM_KEY],
        $_SESSION[POSITIONS_ARRAY_KEY],
        $_SESSION[EDUCATIONS_ARRAY_KEY]
    );

    unset($_SESSION[FNAME_KEY]);
    unset($_SESSION[LNAME_KEY]);
    unset($_SESSION[EMAIL_KEY]);
    unset($_SESSION[HEADLINE_KEY]);
    unset($_SESSION[SUMM_KEY]);
    unset($_SESSION[POSITIONS_ARRAY_KEY]);
    unset($$_SESSION[EDUCATIONS_ARRAY_KEY]);

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="./static/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./static/positions_educations.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
</head>

<body>
    <h1>Adding Profile for <?= htmlentities($_SESSION[USER_NAME_KEY]) ?></h1>
    <?php
    if (isset($_SESSION[ERROR_MSG_KEY])) {
        echo "<p style='color: red;'>" . $_SESSION[ERROR_MSG_KEY] . "</p>\n";
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
        <input type="button" id="addEdu" value="New Education">
        <div id="educations"></div>
        <input type="button" id="addPos" value="New Position">
        <div id="positions"></div>
        <input type="submit" name="<?= ADD_KEY ?>" value="Add">
        <input type="submit" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
</body>

</html>