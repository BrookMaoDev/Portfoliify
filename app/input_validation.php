<?php

/**
 * Description: A file with common functions to process user inputs.
 * Author: Brook Mao
 * Created: January 18, 2024
 */

const NOT_NUMERIC_MSG = "Year must be numeric.";

/**
 * Validates all position inputs.
 * Returns a 2D array of all positions if valid, or an error message otherwise.
 */
function validatePositions(): string|array
{
    $allPositions = [];
    $posNum = 1; // Lowest position number possible

    // Loop through all position inputs
    while (isset($_POST["year" . $posNum]) && isset($_POST["desc" . $posNum])) {
        $pos = validatePosition($_POST["year" . $posNum], $_POST["desc" . $posNum]);

        // Return error message if validation fails
        if (is_string($pos)) {
            return $pos;
        }

        $allPositions[] = $pos;
        $posNum++;
    }

    return $allPositions;
}

/**
 * Validates a single position input.
 * Returns an array of the position if valid, or an error message otherwise.
 */
function validatePosition(string $year, string $desc): string|array
{
    if (strlen($year) === 0 || strlen($desc) === 0) {
        return MISSING_FIELD_MSG; // Defined in the file using this file.
    }

    if (!is_numeric($year)) {
        return NOT_NUMERIC_MSG;
    }

    return [$year, $desc];
}

/**
 * Validates all education inputs.
 * Returns a 2D array of all educations if valid, or an error message otherwise.
 */
function validateEducations(): string|array
{
    $allEducations = [];
    $eduNum = 1; // Lowest education number possible

    // Loop through all education inputs
    while (isset($_POST["eduyear" . $eduNum]) && isset($_POST["school" . $eduNum])) {
        $edu = validateEducation($_POST["eduyear" . $eduNum], $_POST["school" . $eduNum]);

        // Return error message if validation fails
        if (is_string($edu)) {
            return $edu;
        }

        $allEducations[] = $edu;
        $eduNum++;
    }

    return $allEducations;
}

/**
 * Validates a single education input.
 * Returns an array of the education if valid, or an error message otherwise.
 */
function validateEducation(string $year, string $school): string|array
{
    if (strlen($year) === 0 || strlen($school) === 0) {
        return MISSING_FIELD_MSG; // Defined in the file using this file.
    }

    if (!is_numeric($year)) {
        return NOT_NUMERIC_MSG;
    }

    return [$year, $school];
}
