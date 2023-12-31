/**
 * Description: A file containing functions to validate user inputs. IDs of form fields may be hard coded.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

/**
 * Returns true iff Email and Password inputs from login.php are valid. Alerts user in the case of invalid inputs.
 * @returns {boolean}
 */

const MISSING_FIELD_MSG = "All fields are required";
const BAD_EMAIL_MSG = "Invalid email address";

function validateLoginInfoFormat() {
    // WARNING: String arguments must match that of the ids of the input form fields in login.php.
    let email = document.getElementById("email").value;
    let pswd = document.getElementById("pswd").value;

    if (email.length === 0 || pswd.length === 0) {
        alert(MISSING_FIELD_MSG);
        return false;
    } else if (email.indexOf("@") === -1) {
        alert(BAD_EMAIL_MSG);
        return false;
    }
    return true;
}
