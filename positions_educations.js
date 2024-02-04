/**
 * Description: A file containing functions to validate user inputs,
 * and jQuery code for position fields in add.php and edit.php.
 * Author: Brook Mao
 * Created: January 16, 2024
 */

// IDs from add.php and edit.php with # in front to indicate we are grabbing an element with given ID.
const POSITIONS_ADD_BUTTON_ID = "#addPos";
const POSITIONS_DIV_CONTAINER_ID = "#positions";

const EDUCATIONS_ADD_BUTTON_ID = "#addEdu";
const EDUCATIONS_DIV_CONTAINER_ID = "#educations";

let numPositions;
let numEducations;

$(document).ready(function () {
    numPositions = getNumPositions();
    numEducations = getNumEducations();
    $(POSITIONS_ADD_BUTTON_ID).click(addPosition);
    $(EDUCATIONS_ADD_BUTTON_ID).click(addEducation);
});

addPosition = function (event) {
    event.preventDefault();
    numPositions++;
    $(POSITIONS_DIV_CONTAINER_ID).append(
        `<div id="position${numPositions}" class="position">
        <p>
            Year: <input type="text" name="year${numPositions}">
            <input type="button" value="Remove Position" onclick="removePosition('position${numPositions}')">
        </p>
        <textarea name="desc${numPositions}" cols="60" rows="10"></textarea>
        </div>`
    );
};

addEducation = function (event) {
    event.preventDefault();
    numEducations++;
    $(EDUCATIONS_DIV_CONTAINER_ID).append(
        `<div id="education${numEducations}" class="education">
        <p>
            Year: <input type="text" name="eduyear${numEducations}">
            <input type="button" value="Remove Education" onclick="removeEducation('education${numEducations}')">
        </p>
        School: <input type="text" name="school${numEducations}">
        </div>`
    );
};

/**
 *
 * @returns {number} The number of positions already on the document
 * (could be non-zero in the case of edit.php using this file).
 */
function getNumPositions() {
    let matchingPositions = document.querySelectorAll(".position");
    return matchingPositions.length;
}

function getNumEducations() {
    let matchingElements = document.querySelectorAll(".education");
    return matchingElements.length;
}

/**
 * Remove the div representing a position input with html id position_id.
 * @param {string} position_id
 */
function removePosition(position_id) {
    $(`#${position_id}`).remove();
    numPositions--;
    shiftPositions(position_id);
}

/**
 * All positions created after the position with position_id
 * will have their number at the end decremented by 1.
 * @param {string} position_id
 */
function shiftPositions(position_id) {
    idNum = getTrailingNum(position_id) + 1; // The smallest position_id we may need to decrement.
    while ($(`#position${idNum}`).length) {
        // Element with id idNum exists.
        $(`#position${idNum}`).attr("id", `position${idNum - 1}`);
        $(`[name=year${idNum}]:first`).attr("name", `year${idNum - 1}`);
        $(`[name=desc${idNum}]:first`).attr("name", `desc${idNum - 1}`);
        $(`[onclick="removePosition('position${idNum}')"]:first`).attr(
            "onclick",
            `removePosition('position${idNum - 1}')`
        );
        idNum++;
    }
}

function removeEducation(education_id) {
    $(`#${education_id}`).remove();
    numEducations--;
    shiftEducations(education_id);
}

function shiftEducations(education_id) {
    idNum = getTrailingNum(education_id) + 1;
    while ($(`#education${idNum}`).length) {
        // Element with id idNum exists.
        $(`#education${idNum}`).attr("id", `education${idNum - 1}`);
        $(`[name=eduyear${idNum}]:first`).attr("name", `eduyear${idNum - 1}`);
        $(`[name=school${idNum}]:first`).attr("name", `school${idNum - 1}`);
        $(`[onclick="removeEducation('education${idNum}')"]:first`).attr(
            "onclick",
            `removeEducation('education${idNum - 1}')`
        );
        idNum++;
    }
}

/**
 * @param {string} str
 * @returns {number}
 */
function getTrailingNum(str) {
    return Number(str.trim().match(/\d+$/)[0]);
}
