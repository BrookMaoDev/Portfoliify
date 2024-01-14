<?php

/**
 * Description: A file with common functions which process incoming $_GET, $_POST, and $_SESSION data.
 * Author: Brook Mao
 * Created: January 14, 2024
 */

require_once "db_queries.php";

const NOT_LOGGED_IN_MSG = "You are not logged in";
const BAD_PROFILE_MSG = "Failed to retrieve profile";
const NOT_OWNER_OF_PROFILE_MSG = "You are not the owner of this profile";

/**
 * Checks if the user is logged in. If not, page dies.
 */
function checkLoggedIn()
{
    if (!isset($_SESSION[USER_ID_KEY]) || !isset($_SESSION[USER_NAME_KEY])) {
        die(NOT_LOGGED_IN_MSG);
    }
}

/**
 * Checks if the user hit the cancel button. If so, redirect to index.php.
 */
function checkUserHitCancel()
{
    if (isset($_POST[CANCEL_KEY])) {
        header("Location: index.php");
        exit;
    }
}

/**
 * Checks if the PROFILE_ID_KEY from $_GET is valid. If not, page dies.
 */
function checkProfileGet()
{
    if (!isset($_GET[PROFILE_ID_KEY]) || !is_numeric($_GET[PROFILE_ID_KEY])) {
        die(BAD_PROFILE_MSG);
    }
}

/**
 * Returns a profile with $profile_id from $db if such profile exists, dies and returns false otherwise.
 */
function requireProfile(PDO $db, int $profile_id): array|bool
{
    $profile = getProfile($db, $profile_id);
    if ($profile === false) {
        die(BAD_PROFILE_MSG);
        return false;
    }
    return $profile;
}

/**
 * Dies if the user_id of $profile is not the same as the USER_ID_KEY in $_SESSION.
 */
function checkIfUserOwnsProfile($profile)
{
    if ($profile[PROFILE_USER_ID_COLNAME] !== $_SESSION[USER_ID_KEY]) {
        die(NOT_OWNER_OF_PROFILE_MSG);
    }
}
