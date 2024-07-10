<?php

/**
 * Description: A page that shows the details for a particular entry.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "db_connection.php";
require_once "db_queries.php";
require_once "session_helpers.php";

// Ensure profile ID is present in the GET request
checkProfileGet();

// Fetch profile, positions, and educations from the database
$profile = requireProfile($db, (int) $_GET[PROFILE_ID_KEY]);
$positions = getPositions($db, (int) $_GET[PROFILE_ID_KEY]);
$educations = getEducations($db, (int) $_GET[PROFILE_ID_KEY]);

/**
 * Creates a table row for a position.
 *
 * @param array $position Associative array representing a position.
 */
function createPositionRow($position)
{
    $desc = htmlentities($position[POSITION_DESCRIPTION_COLNAME]);
    $year = htmlentities($position[POSITION_YEAR_COLNAME]);

    echo "<tr><td>$desc</td><td>$year</td></tr>";
}

/**
 * Creates a table for positions.
 *
 * @param array $positions Array of associative arrays representing positions.
 */
function createPositionsTable($positions)
{
    echo "<table class='table-hover'>
            <tr>
                <th>Description</th>
                <th>Year</th>
            </tr>";

    foreach ($positions as $position) {
        createPositionRow($position);
    }

    echo "</table>";
}

/**
 * Creates a table row for an education.
 *
 * @param array $education Associative array representing an education.
 */
function createEducationRow($education)
{
    $school_name = htmlentities($education[EDUCATION_INSTITUTION_ID_COLNAME]);
    $year = htmlentities($education[EDUCATION_YEAR_COLNAME]);

    echo "<tr><td>$school_name</td><td>$year</td></tr>";
}

/**
 * Creates a table for educations.
 *
 * @param array $educations Array of associative arrays representing educations.
 */
function createEducationsTable($educations)
{
    echo "<table class='table-hover'>
            <tr>
                <th>School</th>
                <th>Year</th>
            </tr>";

    foreach ($educations as $education) {
        createEducationRow($education);
    }

    echo "</table>";
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
</head>

<body>
    <div class="spacer"></div>
    <h1><?= htmlentities($profile[PROFILE_FNAME_COLNAME]) .
        " " .
        htmlentities($profile[PROFILE_LNAME_COLNAME]) ?></h1>
    <p class="text-center"><?= htmlentities(
        $profile[PROFILE_EMAIL_COLNAME]
    ) ?></p>
    <div class="small-spacer"></div>
    <h2><?= htmlentities($profile[PROFILE_HEADLINE_COLNAME]) ?></h2>
    <div class="small-spacer"></div>
    <p><?= htmlentities($profile[PROFILE_SUMM_COLNAME]) ?></p>
    <div class="small-spacer"></div>

    <!-- Positions Section -->
    <h2 class="mb-2">Positions</h2>
    <?php createPositionsTable($positions); ?>
    <div class="small-spacer"></div>

    <!-- Education Section -->
    <h2 class="mb-2">Education</h2>
    <?php createEducationsTable($educations); ?>
    <div class="spacer"></div>

    <!-- Back Button -->
    <a href="index.php" class="btn btn-outline-primary">Back</a>
    <div class="spacer"></div>
</body>

</html>