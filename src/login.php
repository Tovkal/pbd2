<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 21/11/14
 * Time: 22:11
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}

include "common.php";

if(Common::is_ajax()) {
    if(isset($_POST['userID']) && !empty($_POST['userID'])) {
        echo json_encode(lookupUser());
    } else {
        $result = array("error" => true, "error_msg" => "No s'ha indicat una id d'usuari");
        echo json_encode($result);
    }

    exit();
}

function lookupUser() {
    // Fetch user's data if it's already registered
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("SELECT * FROM Usuari WHERE UserID = :userID");

    $wasSuccessful = $select->execute(array('userID' => $_POST['userID']));
    if ($wasSuccessful && $select->rowCount() == 1) {
        $result = $select->fetch(PDO::FETCH_ASSOC);

        $response = array("error" => false,
                    "user" => array(
                        "userID" => $result['userID'],
                        "nom" => $result['nom'],
                        "id_privilegi" => $result['id_privilegi']
                    )
        );
        $_SESSION['userID'] = $result['userID'];
        $_SESSION['nom'] = $result['nom'];
        $_SESSION['id_privilegi'] = $result['id_privilegi'];
        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha trobat l'usuari indicat");
    }
}