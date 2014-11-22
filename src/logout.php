<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 22/11/14
 * Time: 20:17
 */

// Start the session
session_start();

unset($_SESSION['userID']);

header('Location: index.php');