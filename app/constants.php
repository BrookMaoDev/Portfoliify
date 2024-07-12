<?php

/**
 * Description: A file containing necessary PHP constants.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

// Security
const SALT = "XyZzy12*_";
const HASH_METHOD = "md5";

// Tables
const USERS_TABLE = "users";
const PROFILES_TABLE = "profile";
const POSITIONS_TABLE = "position";
const INSTITUTION_TABLE = "institution";
const EDUCATION_TABLE = "education";

// Columns for USERS_TABLE
const USER_ID_COLNAME = "user_id";
const USER_NAME_COLNAME = "name";
const USER_EMAIL_COLNAME = "email";
const USER_PSWD_COLNAME = "password";

// Columns for PROFILES_TABLE
const PROFILE_ID_COLNAME = "profile_id";
const PROFILE_USER_ID_COLNAME = "user_id";
const PROFILE_FNAME_COLNAME = "first_name";
const PROFILE_LNAME_COLNAME = "last_name";
const PROFILE_EMAIL_COLNAME = "email";
const PROFILE_HEADLINE_COLNAME = "headline";
const PROFILE_SUMM_COLNAME = "summary";

// Columns for POSITIONS_TABLE
const POSITION_ID_COLNAME = "position_id";
const POSITION_PROFILE_ID_COLNAME = "profile_id";
const POSITION_RANK_COLNAME = "rank";
const POSITION_YEAR_COLNAME = "year";
const POSITION_DESCRIPTION_COLNAME = "description";

// Columns for INSTITUTION_TABLE
const INSTITUTION_ID_COLNAME = "institution_id";
const INSTITUTION_NAME_COLNAME = "name";

// Columns for EDUCATION_TABLE
const EDUCATION_PROFILE_ID_COLNAME = "profile_id";
const EDUCATION_INSTITUTION_ID_COLNAME = "institution_id";
const EDUCATION_RANK_COLNAME = "rank";
const EDUCATION_YEAR_COLNAME = "year";

// $_GET keys
const PROFILE_ID_KEY = "profile_id";
const LOOKAHEAD_TERM_KEY = "term";

// $_POST keys
const CANCEL_KEY = "cancel";

// $_SESSION keys
const USER_ID_KEY = "user_id";
const USER_NAME_KEY = "name";
const ERROR_MSG_KEY = "error_msg";
const SUCCESS_MSG_KEY = "success_msg";
