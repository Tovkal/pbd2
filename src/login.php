<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 21/11/14
 * Time: 22:11
 */

include "common.php";

// Start the session
session_start();

// Fetch user's data if it's already registered
$db = Common::initPDOConnection("BDII_08");
$select = $db->prepare("SELECT * FROM Usuari WHERE UserID = :userID");
$select->execute(array('userID' => $_POST['userID']));
if ($select->rowCount() == 1) {
    $result = $select->fetch(PDO::FETCH_ASSOC);
    $_SESSION['userID'] = $result['userID'];
    $_SESSION['nom'] = $result['nom'];
    $_SESSION['nivellPrivilegi'] = $result['nivellPrivilegi'];
} else {
    $_SESSION['loginError'] = true;
}

// Print results
//if(!empty($_SESSION['userID']) && !empty($_SESSION['nom']) && !empty($_SESSION['nivellPrivilegi'])) {
//    echo 'userID' . $_SESSION['userID'];
//    echo 'nom' . $_SESSION['nom'];
//    echo 'nivellPrivilegi' . $_SESSION['nivellPrivilegi'];
//}

// Return to the home page
header('Location: index.php');
