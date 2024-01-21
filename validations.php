<?php

/**
 * Description: A file with common functions to process user inputs.
 * Author: Brook Mao
 * Created: January 18, 2024
 */

const NOT_NUMERIC_MSG = "Position year must be numeric";

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
