<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 21/11/14
 * Time: 20:09
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}

include "common.php";

if(Common::is_ajax()) {
    if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['nom']) && !empty($_POST['nom'])) {
        echo json_encode(createUser());
    } else {
        $result = array("error" => true, "error_msg" => "No s'ha indicat una id d'usuari");
        echo json_encode($result);
    }

    exit();
}

function createUser() {
    session_start();

    // Fetch user's data if it's already registered
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("INSERT INTO Usuari (userID, nom, id_privilegi) VALUES (:userID, :nom, 2)"); //Anuncians = nivell 2

    $wasSuccessful = $select->execute(array('userID' => $_POST['userID'], 'nom' => $_POST['nom']));
    if ($wasSuccessful) {
        $response = array("error" => false,
            "user" => array(
                "userID" => $_POST['userID'],
                "nom" => $_POST['nom'],
                "id_privilegi" => 2
            )
        );
        $_SESSION['userID'] = $_POST['userID'];
        $_SESSION['nom'] = $_POST['nom'];
        $_SESSION['id_privilegi'] = 2;
        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut inserir l'usuari", "db_error_msg" => ($select->errorInfo()));
    }
}