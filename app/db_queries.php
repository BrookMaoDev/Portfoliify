<?php

/**
 * Description: A file with functions to submit common queries to the database.
 * Author: Brook Mao
 * Created: January 1, 2024
 */

require_once "constants.php";

// Constants for array indices
const POSITION_ARRAY_YEAR_INDEX = 0;
const POSITION_ARRAY_DESC_INDEX = 1;
const EDUCATION_ARRAY_YEAR_INDEX = 0;
const EDUCATION_ARRAY_SCHOOL_INDEX = 1;

/**
 * Retrieves a profile with the given profile ID from the database.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The ID of the profile to retrieve.
 * @return array|bool The profile data as an associative array, or false if not found.
 */
function getProfile(PDO $db, int $profile_id): array|bool
{
    $stmt = $db->prepare("SELECT * FROM " . PROFILES_TABLE . " WHERE " . PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Retrieves all profiles from the database.
 * 
 * @param PDO $db The PDO database connection.
 * @return array An array of all profiles.
 */
function getProfiles(PDO $db): array
{
    $stmt = $db->prepare("SELECT * FROM " . PROFILES_TABLE);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

/**
 * Retrieves all positions associated with a given profile ID, ordered by rank.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The profile ID to retrieve positions for.
 * @return array An array of positions.
 */
function getPositions(PDO $db, int $profile_id): array
{
    $stmt = $db->prepare("SELECT * FROM " . POSITIONS_TABLE . " WHERE " . POSITION_PROFILE_ID_COLNAME . " = :profile_id ORDER BY " . POSITION_RANK_COLNAME . " ASC");
    $stmt->execute(array(":profile_id" => $profile_id));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retrieves all educations associated with a given profile ID, ordered by rank.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The profile ID to retrieve educations for.
 * @return array An array of educations.
 */
function getEducations(PDO $db, int $profile_id): array
{
    $stmt = $db->prepare("SELECT * FROM " . EDUCATION_TABLE . " WHERE " . EDUCATION_PROFILE_ID_COLNAME . " = :profile_id ORDER BY " . EDUCATION_RANK_COLNAME . " ASC");
    $stmt->execute(array(":profile_id" => $profile_id));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert institution_id to the actual name
    foreach ($rows as &$row) {
        $edu_id = $row[EDUCATION_INSTITUTION_ID_COLNAME];
        $row[EDUCATION_INSTITUTION_ID_COLNAME] = getInstitutionNameById($db, $edu_id);
    }

    return $rows;
}

/**
 * Adds positions to the database associated with a given profile ID.
 * 
 * @param PDO $db The PDO database connection.
 * @param array $positions An array of positions to add.
 * @param int $profile_id The profile ID to associate the positions with.
 */
function insertPositions(PDO $db, array $positions, int $profile_id)
{
    foreach ($positions as $i => $position) {
        $stmt = $db->prepare("INSERT INTO " . POSITIONS_TABLE . " ("
            . POSITION_PROFILE_ID_COLNAME . ", "
            . POSITION_RANK_COLNAME . ", "
            . POSITION_YEAR_COLNAME . ", "
            . POSITION_DESCRIPTION_COLNAME . ") VALUES (:profile_id, :rank, :year, :description)");

        $stmt->execute(array(
            ":profile_id" => $profile_id,
            ":rank" => $i,
            ":year" => $position[POSITION_ARRAY_YEAR_INDEX],
            ":description" => $position[POSITION_ARRAY_DESC_INDEX],
        ));
    }
}

/**
 * Adds educations to the database associated with a given profile ID.
 * 
 * @param PDO $db The PDO database connection.
 * @param array $educations An array of educations to add.
 * @param int $profile_id The profile ID to associate the educations with.
 */
function insertEducations(PDO $db, array $educations, int $profile_id)
{
    foreach ($educations as $i => $education) {
        $stmt = $db->prepare("INSERT INTO " . EDUCATION_TABLE . " ("
            . EDUCATION_PROFILE_ID_COLNAME . ", "
            . EDUCATION_INSTITUTION_ID_COLNAME . ", "
            . EDUCATION_RANK_COLNAME . ", "
            . EDUCATION_YEAR_COLNAME . ") VALUES (:profile_id, :institution_id, :rank, :year)");

        $stmt->execute(array(
            ":profile_id" => $profile_id,
            ":institution_id" => getInstitutionId($db, $education[EDUCATION_ARRAY_SCHOOL_INDEX]),
            ":rank" => $i,
            ":year" => $education[EDUCATION_ARRAY_YEAR_INDEX],
        ));
    }
}

/**
 * Adds a new profile to the database.
 * 
 * @param PDO $db The PDO database connection.
 * @param string $fname The first name of the profile owner.
 * @param string $lname The last name of the profile owner.
 * @param string $email The email of the profile owner.
 * @param string $headline The headline of the profile.
 * @param string $summ The summary of the profile.
 * @param array $positions An array of positions to associate with the profile.
 * @param array $educations An array of educations to associate with the profile.
 */
function insertResume(PDO $db, string $fname, string $lname, string $email, string $headline, string $summ, array $positions, array $educations)
{
    $stmt = $db->prepare("INSERT INTO " . PROFILES_TABLE . " ("
        . PROFILE_USER_ID_COLNAME . ", "
        . PROFILE_FNAME_COLNAME . ", "
        . PROFILE_LNAME_COLNAME . ", "
        . PROFILE_EMAIL_COLNAME . ", "
        . PROFILE_HEADLINE_COLNAME . ", "
        . PROFILE_SUMM_COLNAME . ") VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)");

    $stmt->execute(array(
        ":user_id" => $_SESSION[USER_ID_KEY],
        ":first_name" => $fname,
        ":last_name" => $lname,
        ":email" => $email,
        ":headline" => $headline,
        ":summary" => $summ,
    ));

    $profile_id = (int) $db->lastInsertId();

    insertPositions($db, $positions, $profile_id);
    insertEducations($db, $educations, $profile_id);
}

/**
 * Retrieves the institution ID for a given school name. If the institution does not exist, it inserts it.
 * 
 * @param PDO $db The PDO database connection.
 * @param string $school_name The name of the school.
 * @return int The ID of the institution.
 */
function getInstitutionId(PDO $db, string $school_name): int
{
    $stmt = $db->prepare("SELECT " . INSTITUTION_ID_COLNAME . " FROM " . INSTITUTION_TABLE . " WHERE " . INSTITUTION_NAME_COLNAME . " = :school_name");
    $stmt->execute(array(":school_name" => $school_name));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // Insert new institution if not found
        $stmt = $db->prepare("INSERT INTO " . INSTITUTION_TABLE . " (" . INSTITUTION_NAME_COLNAME . ") VALUES (:school_name)");
        $stmt->execute(array(":school_name" => $school_name));
        return (int) $db->lastInsertId();
    }

    return (int) $row[INSTITUTION_ID_COLNAME];
}

/**
 * Retrieves the name of an institution given its ID.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $institution_id The ID of the institution.
 * @return string|bool The name of the institution, or false if not found.
 */
function getInstitutionNameById(PDO $db, int $institution_id): string|bool
{
    $stmt = $db->prepare("SELECT " . INSTITUTION_NAME_COLNAME . " FROM " . INSTITUTION_TABLE . " WHERE " . INSTITUTION_ID_COLNAME . " = :institution_id");
    $stmt->execute(array(":institution_id" => $institution_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row[INSTITUTION_NAME_COLNAME] : false;
}

/**
 * Deletes all positions associated with a given profile ID.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The profile ID.
 */
function removePositions(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM " . POSITIONS_TABLE . " WHERE " . POSITION_PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id));
}

/**
 * Deletes all educations associated with a given profile ID.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The profile ID.
 */
function removeEducations(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM " . EDUCATION_TABLE . " WHERE " . EDUCATION_PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id));
}

/**
 * Updates a profile with the given profile ID in the database with new values.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The ID of the profile to update.
 * @param string $fname The new first name of the profile owner.
 * @param string $lname The new last name of the profile owner.
 * @param string $email The new email of the profile owner.
 * @param string $headline The new headline of the profile.
 * @param string $summ The new summary of the profile.
 * @param array $positions An array of new positions to associate with the profile.
 * @param array $educations An array of new educations to associate with the profile.
 */
function editResume(PDO $db, int $profile_id, string $fname, string $lname, string $email, string $headline, string $summ, array $positions, array $educations)
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

    // Remove old positions and insert new ones
    removePositions($db, $profile_id);
    insertPositions($db, $positions, $profile_id);

    // Remove old educations and insert new ones
    removeEducations($db, $profile_id);
    insertEducations($db, $educations, $profile_id);
}

/**
 * Deletes a profile with the given profile ID from the database.
 * 
 * @param PDO $db The PDO database connection.
 * @param int $profile_id The ID of the profile to remove.
 */
function removeResume(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM " . PROFILES_TABLE . " WHERE " . PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id));
}

/**
 * Retrieves a user from the database with the given email and password if they exist.
 * 
 * @param string $email The email of the user.
 * @param string $pswd The password of the user.
 * @param PDO $db The PDO database connection.
 * @return array|bool The user data as an associative array, or false if not found.
 */
function getUsers(string $email, string $pswd, PDO $db): array|bool
{
    $hashed_pswd = hash(HASH_METHOD, SALT . $pswd);

    $stmt = $db->prepare("SELECT " . USER_ID_COLNAME . ", " . USER_NAME_COLNAME . " FROM " . USERS_TABLE . " WHERE " . USER_EMAIL_COLNAME . " = :email AND " . USER_PSWD_COLNAME . " = :password");
    $stmt->execute(array(
        ":email" => $email,
        ":password" => $hashed_pswd
    ));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Retrieves a user from the database with the given email if they exist.
 * 
 * @param string $email The email of the user.
 * @param PDO $db The PDO database connection.
 * @return array|bool The user data as an associative array, or false if not found.
 */
function getUserByEmail(string $email, PDO $db): array|bool
{
    $stmt = $db->prepare("SELECT " . USER_ID_COLNAME . ", " . USER_NAME_COLNAME . " FROM " . USERS_TABLE . " WHERE " . USER_EMAIL_COLNAME . " = :email");
    $stmt->execute(array(":email" => $email));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Adds a new user to the database.
 * 
 * @param string $email The email of the user.
 * @param string $pswd The password of the user.
 * @param string $name The name of the user.
 * @param PDO $db The PDO database connection.
 */
function insertUser(string $email, string $pswd, string $name, PDO $db)
{
    $hashed_pswd = hash(HASH_METHOD, SALT . $pswd);

    $stmt = $db->prepare("INSERT INTO " . USERS_TABLE . " ("
        . USER_EMAIL_COLNAME . ", "
        . USER_PSWD_COLNAME . ", "
        . USER_NAME_COLNAME . ") VALUES (:email, :password, :name)");

    $stmt->execute(array(
        ":email" => $email,
        ":password" => $hashed_pswd,
        ":name" => $name,
    ));
}
