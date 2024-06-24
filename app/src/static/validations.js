/**
 * Description: A file containing functions to validate user inputs.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

const MISSING_FIELD_MSG = "All fields are required";
const BAD_EMAIL_MSG = "Invalid email address";

// IDs of the input form fields in login.php.
const EMAIL_ID = "email";
const PSWD_ID = "pswd";

// IDs of the input form fields in add.php.
const FNAME_ID = "first_name";
const LNAME_ID = "last_name";
// const EMAIL_ID = "email"; // uses same as login.php currently
const HEADLINE_ID = "headline";
const SUMM_ID = "summary";

/**
 * Returns true iff Email and Password inputs from login.php are valid. Alerts user in the case of invalid inputs.
 * @returns {boolean}
 */
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

/**
 * Returns true iff inputs from add.php are valid. Alerts user in the case of invalid inputs.
 * @returns {boolean}
 */
function validateProfileFields() {
    const fname = document.getElementById(FNAME_ID).value;
    const lname = document.getElementById(LNAME_ID).value;
    const email = document.getElementById(EMAIL_ID).value;
    const headline = document.getElementById(HEADLINE_ID).value;
    const summ = document.getElementById(SUMM_ID).value;

    if (
        fname.length === 0 ||
        lname.length === 0 ||
        email.length === 0 ||
        headline.length === 0 ||
        summ.length === 0
    ) {
        alert(MISSING_FIELD_MSG);
        return false;
    } else if (email.indexOf("@") === -1) {
        alert(BAD_EMAIL_MSG);
        return false;
    }

    return true;
}
