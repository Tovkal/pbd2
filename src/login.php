<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 21/11/14
 * Time: 22:11
 */

include "common.php";

if(is_ajax()) {
    if(isset($_POST['userID']) && !empty($_POST['userID'])) {
        echo json_encode(lookupUser());
    } else {
        $result = array("error" => true, "error_msg" => "No s'ha indicat una id d'usuari");
        echo json_encode($result);
    }

    exit();
}

//Function to check if the request is an AJAX request
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


function lookupUser() {
    // Start the session
    session_start();

    // Fetch user's data if it's already registered
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("SELECT * FROM Usuari WHERE UserID = :userID");

    $select->execute(array('userID' => $_POST['userID']));
    if ($select->rowCount() == 1) {
        $result = $select->fetch(PDO::FETCH_ASSOC);

        $user = array("error" => false,
                    "user" => array(
                        "userID" => $result['userID'],
                        "nom" => $result['nom'],
                        "nivellPrivilegi" => $result['nivellPrivilegi']
                    )
        );
        return $user;
    } else {
        return array("error" => true, "error_msg" => "No s'ha trobat l'usuari indicat");
    }

}