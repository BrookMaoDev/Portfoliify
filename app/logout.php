<?php

/**
 * Description: Logs the user out by clearing session data and redirecting to index.php.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

session_start(); // Start the session

// Destroy the session and clear all session data
session_destroy();

// Redirect to the home page
header("Location: index.php");
exit;
