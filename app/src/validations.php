<?php

/**
 * Description: A file with common functions to process user inputs.
 * Author: Brook Mao
 * Created: January 18, 2024
 */

const NOT_NUMERIC_MSG = "Year must be numeric";

/**
 * Returns a 2D array of all positions if all inputs are valid, an error message otherwise.
 */
function validatePositions(): string|array
{
    $allPositions = [];
    $posNum = 1; // Lowest position number possible
    while (isset($_POST["year" . $posNum]) && isset($_POST["desc" . $posNum])) {
        $pos = validatePosition($_POST["year" . $posNum], $_POST["desc" . $posNum]);
        if (gettype($pos) === "string") {
            return $pos; // Since it is a string, it is an error message.
        }
        array_push($allPositions, $pos);
        $posNum++;
    }
    return $allPositions;
}

/**
 * Returns an array of the position if $year and $desc are valid, an error message otherwise.
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

function validateEducations(): string|array
{
    $allEducations = [];
    $eduNum = 1;

    while (isset($_POST["eduyear" . $eduNum]) && isset($_POST["school" . $eduNum])) {
        $edu = validateEducation($_POST["eduyear" . $eduNum], $_POST["school" . $eduNum]);

        if (gettype($edu) === "string") {
            return $edu;
        }

        array_push($allEducations, $edu);
        $eduNum++;
    }

    return $allEducations;
}

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
