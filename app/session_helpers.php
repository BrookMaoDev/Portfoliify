<?php

/**
 * Description: A file with common functions to process incoming $_GET, $_POST, and $_SESSION data.
 * Author: Brook Mao
 * Created: January 14, 2024
 */

require_once "constants.php";

// Constants for error messages
const NOT_LOGGED_IN_MSG = "Not logged in.";
const BAD_PROFILE_MSG = "Failed to retrieve profile.";
const NOT_OWNER_OF_PROFILE_MSG = "You are not the owner of this profile.";

/**
 * Checks if the user is logged in by verifying session variables.
 * Terminates the script with an error message if the user is not logged in.
 */
function checkLoggedIn()
{
    if (!isset($_SESSION[USER_ID_KEY]) || !isset($_SESSION[USER_NAME_KEY])) {
        die(NOT_LOGGED_IN_MSG);
    }
}

/**
 * Checks if the user hit the cancel button in a form submission.
 * Redirects to the index page if the cancel button was pressed.
 */
function checkUserHitCancel()
{
    if (isset($_POST[CANCEL_KEY])) {
        header("Location: index.php");
        exit();
    }
}

/**
 * Checks if the PROFILE_ID_KEY from $_GET is valid.
 * Terminates the script with an error message if the profile ID is not set or not numeric.
 */
function checkProfileGet()
{
    if (!isset($_GET[PROFILE_ID_KEY]) || !is_numeric($_GET[PROFILE_ID_KEY])) {
        die(BAD_PROFILE_MSG);
    }
}

/**
 * Retrieves a profile with the given profile ID from the database.
 * Terminates the script with an error message if the profile does not exist.
 *
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The ID of the profile to retrieve.
 * @return array|bool The profile data as an associative array, or false if not found.
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
 * Checks if the user owns the profile by comparing the profile's user ID with the user ID in the session.
 * Terminates the script with an error message if the user does not own the profile.
 *
 * @param array $profile The profile data.
 */
function checkIfUserOwnsProfile(array $profile)
{
    if ($profile[PROFILE_USER_ID_COLNAME] !== $_SESSION[USER_ID_KEY]) {
        die(NOT_OWNER_OF_PROFILE_MSG);
    }
}
