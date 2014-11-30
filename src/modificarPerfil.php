<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 25/11/14
 * Time: 14:49
 */

// Start the session
if(!isset($_SESSION)){
    session_start();
}

include "common.php";

if(Common::is_ajax()) {
    if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['nom']) && !empty($_POST['nom'])) {
        echo json_encode(updateUser());
    } else {
        $result = array("error" => true, "error_msg" => "No s'ha indicat una id d'usuari?" . $_POST['userID'] . " " . $_POST['nom']);
        echo json_encode($result);
    }

    exit();
}

function updateUser() {
    // Fetch user's data if it's already registered
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("UPDATE Usuari SET userID = :userID, nom = :nom WHERE id = :id");

    $wasSuccessful = $select->execute(array('userID' => $_POST['userID'], 'nom' => $_POST['nom'], 'id' => $_SESSION['id']));
    if ($wasSuccessful) {
        $response = array("error" => false,
            "user" => array(
                "userID" => $_POST['userID'],
                "nom" => $_POST['nom']
            )
        );
        $_SESSION['userID'] = $_POST['userID'];
        $_SESSION['nom'] = $_POST['nom'];
        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut actualitzar l'usuari", "db_error_msg" => ($select->errorInfo()));
    }
}