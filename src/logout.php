<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 23/11/14
 * Time: 17:47
 */

// Start the session
if(!isset($_SESSION)){
    session_start();
}

session_destroy();
header("location:index.php"); //to redirect back to "index.php" after logging out
exit();