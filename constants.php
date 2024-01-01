<?php

/**
 * Description: A file containing necessary PHP constants.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

// Data validation
const SALT = "XyZzy12*_";
const HASH_METHOD = "md5";

// Database
const DB_NAME = "res_profile";

// Tables
const USERS_TABLE = "users";
const PROFILES_TABLE = "profile";

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

// $_SESSION keys
const USER_ID_KEY = "user_id";
const USER_NAME_KEY = "name";
const ERROR_MSG_KEY = "error_msg";
const SUCCESS_MSG_KEY = "success_msg";

// $_GET keys
const PROFILE_ID_KEY = "profile_id";
