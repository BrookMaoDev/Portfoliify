<?php

/**
 * Description: A file with functions to submit common queries to the db.
 * Author: Brook Mao
 * Created: January 1, 2024
 */

/**
 * Returns a profile with $profile_id from $db if such profile exists, false otherwise. 
 */
function getProfile(PDO $db, int $profile_id): array|bool
{
    $stmt = $db->prepare("SELECT * FROM " . PROFILES_TABLE
        . " WHERE " . PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

/**
 * Adds a new profile to $db.
 */
function insertResume(PDO $db, string $fname, string $lname, string $email, string $headline, string $summ)
{
    $stmt = $db->prepare("INSERT INTO " . PROFILES_TABLE . " ("
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

/**
 * Updates profile(s) with $profile_id from $db with new values.
 */
function editResume(PDO $db, int $profile_id, string $fname, string $lname, string $email, string $headline, string $summ)
{
    $stmt = $db->prepare("UPDATE " . PROFILES_TABLE . " SET "
        . PROFILE_FNAME_COLNAME . " = :first_name, "
        . PROFILE_LNAME_COLNAME . " = :last_name, "
        . PROFILE_EMAIL_COLNAME . " = :email, "
        . PROFILE_HEADLINE_COLNAME . " = :headline, "
        . PROFILE_SUMM_COLNAME . " = :summary WHERE " . PROFILE_ID_COLNAME . " = :profile_id");

    $stmt->execute(array(
        ":profile_id" => $profile_id,
        ":first_name" => $fname,
        ":last_name" => $lname,
        ":email" => $email,
        ":headline" => $headline,
        ":summary" => $summ,
    ));
}

/**
 * Removes profile(s) with $profile_id from $db.
 */
function removeResume(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM " . PROFILES_TABLE . " WHERE " . PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id,));
}
