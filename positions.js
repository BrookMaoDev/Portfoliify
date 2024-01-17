/**
 * Description: A file containing functions to validate user inputs,
 * and jQuery code for position fields in add.php and edit.php.
 * Author: Brook Mao
 * Created: January 16, 2024
 */

// IDs from add.php and edit.php with # in front to indicate we are grabbing an element with given ID.
const POSITIONS_ADD_BUTTON_ID = "#addPos";
const POSITIONS_DIV_CONTAINER_ID = "#positions";

let numPositions = 0;

$(document).ready(function () {
    $(POSITIONS_ADD_BUTTON_ID).click(addPosition);
});

addPosition = function (event) {
    event.preventDefault();
    numPositions++;
    $(POSITIONS_DIV_CONTAINER_ID).append(
        `<div id="position${numPositions}" class="position">
        Year: <input type="text" name="year${numPositions}">
        <input type="button" value="Remove Position" onclick="removePosition('position${numPositions}')"><br>
        <textarea name="desc${numPositions}" cols="60" rows="10"></textarea>
    </div>`
    );
};

/**
 * Remove the div representing a position input with html id position_id.
 * @param {string} position_id
 */
function removePosition(position_id) {
    $(`#${position_id}`).remove();
}
