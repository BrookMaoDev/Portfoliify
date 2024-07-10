/**
 * Description: A file containing functions to validate user inputs,
 * and jQuery code for handling position and education fields in add.php and edit.php.
 * Author: Brook Mao
 * Created: January 16, 2024
 */

// Constants for element IDs from add.php and edit.php
const POSITIONS_ADD_BUTTON_ID = "#addPos";
const POSITIONS_DIV_CONTAINER_ID = "#positions";
const EDUCATIONS_ADD_BUTTON_ID = "#addEdu";
const EDUCATIONS_DIV_CONTAINER_ID = "#educations";
const SCHOOL_CLASS = "school";

let numPositions;
let numEducations;

$(document).ready(function () {
  numPositions = getNumPositions();
  numEducations = getNumEducations();

  $(`.${SCHOOL_CLASS}`).autocomplete({
    source: "school_autocomplete.php",
  });

  $(POSITIONS_ADD_BUTTON_ID).click(addPosition);
  $(EDUCATIONS_ADD_BUTTON_ID).click(addEducation);
});

$(window).resize(function () {
  $(".ui-autocomplete").css("display", "none");
});

/**
 * Adds a new position input field to the form.
 * @param {Event} event
 */
function addPosition(event) {
  event.preventDefault();
  numPositions++;
  $(POSITIONS_DIV_CONTAINER_ID).append(
    `<div id="position${numPositions}" class="position">
            <div>
                <label for="year${numPositions}">Year</label>
                <input type="text" class="form-control" name="year${numPositions}">
            </div>
            <div>
                <label for="desc${numPositions}">Description</label>
                <input type="text" class="form-control" name="desc${numPositions}">
            </div>
            <div>
                <input type="button" value="Remove" onclick="removePosition('position${numPositions}')" class='btn btn-outline-warning'>
            </div>
            <div class="small-spacer"></div>
        </div>`,
  );
}

/**
 * Adds a new education input field to the form.
 * @param {Event} event
 */
function addEducation(event) {
  event.preventDefault();
  numEducations++;
  $(EDUCATIONS_DIV_CONTAINER_ID).append(
    `<div id="education${numEducations}" class="education">
            <div>
                <label for="eduyear${numEducations}">Year</label>
                <input type="text" class="form-control" name="eduyear${numEducations}">
            </div>
            <div>
                <label for="school${numEducations}">School</label>
                <input type="text" class="form-control ${SCHOOL_CLASS}" name="school${numEducations}">
            </div>
            <div>
                <input type="button" value="Remove" onclick="removeEducation('education${numEducations}')" class='btn btn-outline-warning'>
            </div>
            <div class="small-spacer"></div>
        </div>`,
  );
  $(`.${SCHOOL_CLASS}`).autocomplete({
    source: "school_autocomplete.php",
  });
}

/**
 * Retrieves the number of position inputs currently in the form.
 * @returns {number}
 */
function getNumPositions() {
  return document.querySelectorAll(".position").length;
}

/**
 * Retrieves the number of education inputs currently in the form.
 * @returns {number}
 */
function getNumEducations() {
  return document.querySelectorAll(".education").length;
}

/**
 * Removes a specific position input field by ID.
 * @param {string} position_id
 */
function removePosition(position_id) {
  $(`#${position_id}`).remove();
  numPositions--;
  shiftPositions(position_id);
}

/**
 * Adjusts the IDs and names of all position inputs following the removed one.
 * @param {string} position_id
 */
function shiftPositions(position_id) {
  let idNum = getTrailingNum(position_id) + 1;
  while ($(`#position${idNum}`).length) {
    $(`#position${idNum}`).attr("id", `position${idNum - 1}`);
    $(`[name=year${idNum}]:first`).attr("name", `year${idNum - 1}`);
    $(`[name=desc${idNum}]:first`).attr("name", `desc${idNum - 1}`);
    $(`[onclick="removePosition('position${idNum}')"]:first`).attr(
      "onclick",
      `removePosition('position${idNum - 1}')`,
    );
    idNum++;
  }
}

/**
 * Removes a specific education input field by ID.
 * @param {string} education_id
 */
function removeEducation(education_id) {
  $(`#${education_id}`).remove();
  numEducations--;
  shiftEducations(education_id);
}

/**
 * Adjusts the IDs and names of all education inputs following the removed one.
 * @param {string} education_id
 */
function shiftEducations(education_id) {
  let idNum = getTrailingNum(education_id) + 1;
  while ($(`#education${idNum}`).length) {
    $(`#education${idNum}`).attr("id", `education${idNum - 1}`);
    $(`[name=eduyear${idNum}]:first`).attr("name", `eduyear${idNum - 1}`);
    $(`[name=school${idNum}]:first`).attr("name", `school${idNum - 1}`);
    $(`[onclick="removeEducation('education${idNum}')"]:first`).attr(
      "onclick",
      `removeEducation('education${idNum - 1}')`,
    );
    idNum++;
  }
}

/**
 * Extracts the trailing number from a string.
 * @param {string} str
 * @returns {number}
 */
function getTrailingNum(str) {
  return Number(str.trim().match(/\d+$/)[0]);
}
