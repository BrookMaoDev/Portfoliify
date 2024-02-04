<?php

/**
 * Description: A page to edit an existing entry in the database.
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
const SUCCESS_MSG = "Profile updated";

// $_POST keys
const EDIT_KEY = "edit";
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
checkProfileGet();

$profile = requireProfile($db, $_GET[PROFILE_ID_KEY]);
checkIfUserOwnsProfile($profile);

// User is not the owner of this profile
if ($profile[PROFILE_USER_ID_COLNAME] !== $_SESSION[USER_ID_KEY]) {
    die(NOT_OWNER_OF_PROFILE_MSG);
}

// User wants to edit the entry
if (isset($_POST[EDIT_KEY])) {
    validateProfileFields(); // Server side validation. If valid, below code runs.
    $positionsArray = validatePositions();
    $educationsArray = validateEducations();
    if (gettype($positionsArray) === "string") { // Then it's an error message.
        $_SESSION[ERROR_MSG_KEY] = $positionsArray;
        header("Location: " . basename(__FILE__) . "?" . PROFILE_ID_KEY . "=" . $_GET[PROFILE_ID_KEY]);
        exit;
    } else if (gettype($educationsArray) === "string") {
        $_SESSION[ERROR_MSG_KEY] = $educationsArray;
        header("Location: " . basename(__FILE__) . "?" . PROFILE_ID_KEY . "=" . $_GET[PROFILE_ID_KEY]);
        exit;
    }
    $_SESSION[FNAME_KEY] = $_POST[FNAME_KEY];
    $_SESSION[LNAME_KEY] = $_POST[LNAME_KEY];
    $_SESSION[EMAIL_KEY] = $_POST[EMAIL_KEY];
    $_SESSION[HEADLINE_KEY] = $_POST[HEADLINE_KEY];
    $_SESSION[SUMM_KEY] = $_POST[SUMM_KEY];
    $_SESSION[POSITIONS_ARRAY_KEY] = $positionsArray;
    $_SESSION[EDUCATIONS_ARRAY_KEY] = $educationsArray;
    header("Location: " . basename(__FILE__) . "?" . PROFILE_ID_KEY . "=" . $_GET[PROFILE_ID_KEY]);
    exit;
}

// User's new entry inputs are valid and are now in the session
if (isset($_SESSION[FNAME_KEY])) {
    editResume(
        $db,
        $_GET[PROFILE_ID_KEY],
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
    unset($_SESSION[EDUCATIONS_ARRAY_KEY]);

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
        header("Location: " . basename(__FILE__) . "?" . PROFILE_ID_KEY . "=" . $_GET[PROFILE_ID_KEY]);
        exit;
    } elseif (!str_contains($email, "@")) {
        $_SESSION[ERROR_MSG_KEY] = BAD_EMAIL_MSG;
        header("Location: " . basename(__FILE__) . "?" . PROFILE_ID_KEY . "=" . $_GET[PROFILE_ID_KEY]);
        exit;
    }
}

function loadSavedPositions($positions)
{
    for ($i = 0; $i < sizeof($positions); $i++) {
        $elementIdNum = $i + 1; // This is because in positions.js,
        // we made the numbers at the end of each element ID start at 1.
        $savedYear = htmlentities($positions[$i][POSITION_YEAR_COLNAME]);
        $savedDesc = htmlentities($positions[$i][POSITION_DESCRIPTION_COLNAME]);
        echo (
            "<div id='position$elementIdNum' class='position'>
            <p>
                Year: <input type='text' name='year$elementIdNum' value='$savedYear'>
                <input type='button' value='Remove Position' onclick=\"removePosition('position$elementIdNum')\">
            </p>
            <textarea name='desc$elementIdNum' cols='60' rows='10'>$savedDesc</textarea>
            </div>"
        );
    }
}

function loadSavedEducations($educations)
{
    for ($i = 0; $i < sizeof($educations); $i++) {
        $elementIdNum = $i + 1;
        $savedYear = htmlentities($educations[$i][EDUCATION_YEAR_COLNAME]);
        $savedSchool = htmlentities($educations[$i][EDUCATION_INSTITUTION_ID_COLNAME]);
        echo (
            "<div id='education$elementIdNum' class='education'>
            <p>
                Year: <input type='text' name='eduyear$elementIdNum' value='$savedYear'>
                <input type='button' value='Remove Education' onclick=\"removeEducation('education$elementIdNum')\">
            </p>
            School: <input type='text' name='school$elementIdNum' value='$savedSchool'>
            </div>"
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="positions_educations.js"></script>
</head>

<body>
    <h1>Editing Profile for <?= htmlentities($_SESSION[USER_NAME_KEY]) ?></h1>
    <?php
    if (isset($_SESSION[ERROR_MSG_KEY])) {
        echo "<p style='color: red;'>" . $_SESSION[ERROR_MSG_KEY] . "</p>";
        unset($_SESSION[ERROR_MSG_KEY]);
    }
    ?>
    <form method="post">
        <div class="text-field">
            First Name<br>
            <input type="text" name="<?= FNAME_KEY ?>" value="<?= htmlentities($profile[PROFILE_FNAME_COLNAME]) ?>">
        </div>
        <div class="text-field">
            Last Name<br>
            <input type="text" name="<?= LNAME_KEY ?>" value="<?= htmlentities($profile[PROFILE_LNAME_COLNAME]) ?>">
        </div>
        <div class="text-field">
            Email<br>
            <input type="text" name="<?= EMAIL_KEY ?>" value="<?= htmlentities($profile[PROFILE_EMAIL_COLNAME]) ?>">
        </div>
        <div class="text-field">
            Headline<br>
            <input type="text" name="<?= HEADLINE_KEY ?>" value="<?= htmlentities($profile[PROFILE_HEADLINE_COLNAME]) ?>">
        </div>
        <div class="text-field">
            Summary<br>
            <textarea name="<?= SUMM_KEY ?>" rows="10" cols="60"><?= htmlentities($profile[PROFILE_SUMM_COLNAME]) ?></textarea>
        </div>
        <input type="button" id="addEdu" value="New Education">
        <div id="educations">
            <?php loadSavedEducations(getEducations($db, $_GET[PROFILE_ID_KEY])) ?>
        </div>
        <input type="button" id="addPos" value="New Position">
        <div id="positions">
            <?php loadSavedPositions(getPositions($db, $_GET[PROFILE_ID_KEY])) ?>
        </div>
        <input type="submit" name="<?= EDIT_KEY ?>" value="Save">
        <input type="submit" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
</body>

</html>