<?php

/**
 * Description: Will log the user out by clearing data in the session and redirecting back to index.php.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

session_start();
session_destroy();
header("Location: index.php");
exit;
