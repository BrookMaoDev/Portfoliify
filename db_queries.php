<?php

/**
 * Description: A file with functions to submit common queries to the db.
 * Author: Brook Mao
 * Created: January 1, 2024
 */

// Based on validatePosition() in validations.php. Update accordingly.
const POSITION_ARRAY_YEAR_INDEX = 0;
const POSITION_ARRAY_DESC_INDEX = 1;
const EDUCATION_ARRAY_YEAR_INDEX = 0;
const EDUCATION_ARRAY_SCHOOL_INDEX = 1;

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
 * Returns all profiles from $db.
 */
function getProfiles(PDO $db): array
{
    $stmt = $db->prepare("SELECT * FROM " . PROFILES_TABLE);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        return [];
    }
    return $rows;
}

/**
 * Returns all positions from $db with foreign key $profile_id
 * ordered by POSITION_RANK_COLNAME in ascending order.
 */
function getPositions(PDO $db, int $profile_id): array
{
    $stmt = $db->prepare("SELECT * FROM " . POSITIONS_TABLE .
        " WHERE " . POSITION_PROFILE_ID_COLNAME . " = :profile_id ORDER BY "
        . POSITION_RANK_COLNAME . " ASC");
    $stmt->execute(array(":profile_id" => $profile_id));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function getEducations(PDO $db, int $profile_id): array
{
    $stmt = $db->prepare("SELECT * FROM " . EDUCATION_TABLE .
        " WHERE " . EDUCATION_PROFILE_ID_COLNAME .
        " = :profile_id ORDER BY " . EDUCATION_RANK_COLNAME . " ASC");

    $stmt->execute(array(":profile_id" => $profile_id));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Converting institution_id into the actual name
    for ($i = 0; $i < sizeof($rows); $i++) {
        $edu_id = $rows[$i][EDUCATION_INSTITUTION_ID_COLNAME];
        $rows[$i][EDUCATION_INSTITUTION_ID_COLNAME] = getInstitutionNameById($db, $edu_id);
    }

    return $rows;
}

/**
 * Adds a new profile to $db.
 */
function insertResume(
    PDO $db,
    string $fname,
    string $lname,
    string $email,
    string $headline,
    string $summ,
    array $positions,
    array $educations
) {
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

    $profile_id = (int) $db->lastInsertId();

    insertPositions($db, $positions, $profile_id);
    insertEducations($db, $educations, $profile_id);
}

/**
 * Adds the positions from $positions into $db using $profile_id as the foreign key.
 */
function insertPositions(PDO $db, array $positions, int $profile_id)
{
    for ($i = 0; $i < sizeof($positions); $i++) {
        $stmt = $db->prepare("INSERT INTO " . POSITIONS_TABLE . "
        (" . POSITION_PROFILE_ID_COLNAME . ",
        " . POSITION_RANK_COLNAME . ",
        " . POSITION_YEAR_COLNAME . ",
        " . POSITION_DESCRIPTION_COLNAME . ")
        VALUES (:profile_id, :rank, :year, :description)");

        $stmt->execute(array(
            ":profile_id" => $profile_id,
            ":rank" => $i,
            ":year" => $positions[$i][POSITION_ARRAY_YEAR_INDEX],
            ":description" => $positions[$i][POSITION_ARRAY_DESC_INDEX]
        ));
    }
}

function insertEducations(PDO $db, array $educations, int $profile_id)
{
    for ($i = 0; $i < sizeof($educations); $i++) {
        $stmt = $db->prepare("INSERT INTO " . EDUCATION_TABLE
            . "(" . EDUCATION_PROFILE_ID_COLNAME . ", "
            . EDUCATION_INSTITUTION_ID_COLNAME . ", "
            . EDUCATION_RANK_COLNAME . ", "
            . EDUCATION_YEAR_COLNAME
            . ") VALUES(:profile_id, :institution_id, :rank, :year)");

        $stmt->execute(array(
            ":profile_id" => $profile_id,
            ":institution_id" => getInstitutionId($db, $educations[$i][EDUCATION_ARRAY_SCHOOL_INDEX]),
            ":rank" => $i,
            ":year" => $educations[$i][EDUCATION_ARRAY_YEAR_INDEX]
        ));
    }
}

/**
 * Given a $school_name, the institution_id of the institution is returned.
 * If no institution with $school_name is found in $db, then a new institution
 * with $school_name is inserted into $db, and the id is returned.
 */
function getInstitutionId(PDO $db, string $school_name): int
{
    $stmt = $db->prepare("SELECT " . INSTITUTION_ID_COLNAME .
        " FROM " . INSTITUTION_TABLE .
        " WHERE " . INSTITUTION_NAME_COLNAME . " = :school_name");

    $stmt->execute(array(":school_name" => $school_name));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        // No rows exist, meaning the school is not registered in INSTITUTION_TABLE yet.
        $stmt = $db->prepare("INSERT INTO " . INSTITUTION_TABLE .
            "(" . INSTITUTION_NAME_COLNAME . ") VALUES(:school_name)");

        $stmt->execute(array(":school_name" => $school_name));

        return (int) $db->lastInsertId();
    }

    // Otherwise we simply access the INSTITUTION_ID and return it.
    return (int) $row[INSTITUTION_ID_COLNAME];
}

/**
 * Given an $institution_id, the name of the institution is returned.
 * If institution with $institution_id does not exist in $db, then return false.
 */
function getInstitutionNameById(PDO $db, int $institution_id): string|bool
{
    $stmt = $db->prepare("SELECT " . INSTITUTION_NAME_COLNAME .
        " FROM " . INSTITUTION_TABLE .
        " WHERE " . INSTITUTION_ID_COLNAME . " = :institution_id");

    $stmt->execute(array(":institution_id" => $institution_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        // No rows exist, hence no institution with the given id exists.
        return false;
    }

    return $row[INSTITUTION_NAME_COLNAME];
}

/**
 * Deletes positions from $db where POSITION_PROFILE_ID_COLNAME
 * (foreign key) is equal to $profile_id. In other words,
 * removes all positions associated with the profile with profile_id.
 */
function removePositions(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM "
        . POSITIONS_TABLE . " WHERE "
        . POSITION_PROFILE_ID_COLNAME . " = :profile_id");

    $stmt->execute(array(":profile_id" => $profile_id));
}

function removeEducations(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM "
        . EDUCATION_TABLE . " WHERE "
        . EDUCATION_PROFILE_ID_COLNAME . " = :profile_id");

    $stmt->execute(array(":profile_id" => $profile_id));
}

/**
 * Updates profile(s) with $profile_id from $db with new values.
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

    // Updating the old positions by removing the old positions and inserting the new ones.
    removePositions($db, $profile_id);
    insertPositions($db, $positions, $profile_id);

    removeEducations($db, $profile_id);
    insertEducations($db, $educations, $profile_id);
}

/**
 * Removes profile(s) with $profile_id from $db.
 */
function removeResume(PDO $db, int $profile_id)
{
    $stmt = $db->prepare("DELETE FROM " . PROFILES_TABLE . " WHERE " . PROFILE_ID_COLNAME . " = :profile_id");
    $stmt->execute(array(":profile_id" => $profile_id,));
}

/**
 * Returns a user in $db with the given $email and $pswd if they exist.
 */
function getUsers(string $email, string $pswd, PDO $db): array|bool
{
    $hashed_pswd = hash(HASH_METHOD, SALT . $pswd);

    $stmt = $db->prepare("SELECT " . USER_ID_COLNAME . ", " . USER_NAME_COLNAME
        . " FROM " . USERS_TABLE . " WHERE "
        . USER_EMAIL_COLNAME . " = :email AND " . USER_PSWD_COLNAME . " = :password");
    $stmt->execute(array(
        ":email" => $email,
        ":password" => $hashed_pswd
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row;
}
