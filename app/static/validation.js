/**
 * Description: A file containing functions to validate user inputs.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

const MISSING_FIELD_MSG = "All fields are required.";
const BAD_EMAIL_MSG = "Invalid email address.";

// IDs of the input form fields in login.php
const EMAIL_ID = "email";
const PSWD_ID = "pswd";

// IDs of the input form fields in add.php
const FNAME_ID = "first_name";
const LNAME_ID = "last_name";
const HEADLINE_ID = "headline";
const SUMM_ID = "summary";

/**
 * Validates the Email and Password inputs from login.php.
 * Alerts the user in case of invalid inputs.
 * @returns {boolean} True if inputs are valid, false otherwise.
 */
function validateLoginInfoFormat() {
  const email = document.getElementById(EMAIL_ID).value;
  const pswd = document.getElementById(PSWD_ID).value;

  if (!email || !pswd) {
    alert(MISSING_FIELD_MSG);
    return false;
  } else if (!email.includes("@")) {
    alert(BAD_EMAIL_MSG);
    return false;
  }

  return true;
}

/**
 * Validates the inputs from add.php.
 * Alerts the user in case of invalid inputs.
 * @returns {boolean} True if inputs are valid, false otherwise.
 */
function validateProfileFields() {
  const fname = document.getElementById(FNAME_ID).value;
  const lname = document.getElementById(LNAME_ID).value;
  const email = document.getElementById(EMAIL_ID).value;
  const headline = document.getElementById(HEADLINE_ID).value;
  const summ = document.getElementById(SUMM_ID).value;

  if (!fname || !lname || !email || !headline || !summ) {
    alert(MISSING_FIELD_MSG);
    return false;
  } else if (!email.includes("@")) {
    alert(BAD_EMAIL_MSG);
    return false;
  }

  return true;
}
