/**
 * Description: A file containing functions to validate user inputs.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

/**
 * Returns true iff Email and Password inputs from login.php are valid. Alerts user in the case of invalid inputs.
 * @returns {boolean}
 */

const MISSING_FIELD_MSG = "All fields are required";
const BAD_EMAIL_MSG = "Invalid email address";

// IDs of the input form fields in login.php.
const EMAIL_ID = "email";
const PSWD_ID = "pswd";

function validateLoginInfoFormat() {
    let email = document.getElementById(EMAIL_ID).value;
    let pswd = document.getElementById(PSWD_ID).value;

    if (email.length === 0 || pswd.length === 0) {
        alert(MISSING_FIELD_MSG);
        return false;
    } else if (email.indexOf("@") === -1) {
        alert(BAD_EMAIL_MSG);
        return false;
    }
    return true;
}
