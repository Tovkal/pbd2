<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 2/12/14
 * Time: 18:31
 */
// Start session if needed
if(!isset($_SESSION)){
    session_start();
}

include "../common.php";

if (Common::is_ajax()) {
    if (isset($_POST['userID']) && !empty($_POST['userID'])) {
        if (isset($_POST['action']) && !empty($_POST['action'])) {
            if ($_POST['action'] == "login") {
                if (isset($_POST['password']) && !empty($_POST['password'])) {
                    echo json_encode(lookupUser());
                } else {
                    $result = array("error" => true, "error_msg" => "No s'ha inserit una contrasenya");
                    echo json_encode($result);
                }


            } else if (isset($_POST['nom']) && !empty($_POST['nom'])) {
                if ($_POST['action'] == "signup") {
                    echo json_encode(createUser());
                } else if ($_POST['action'] == "update") {
                    echo json_encode(updateUser());
                } else {
                    $result = array("error" => true, "error_msg" => "No s'ha indicat una acció correcta");
                    echo json_encode($result);
                }
            }
        }

    } else {
        $result = array("error" => true, "error_msg" => "No s'ha indicat una id d'usuari");
        echo json_encode($result);
    }

    exit();
}

function lookupUser() {
    // Fetch user's data if it's already registered
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("SELECT * FROM Usuari WHERE userID = :userID AND password = :password");

    $wasSuccessful = $select->execute(array('userID' => $_POST['userID'], 'password' => $_POST['password']));
    if ($wasSuccessful && $select->rowCount() == 1) {
        $result = $select->fetch(PDO::FETCH_ASSOC);

        $response = array("error" => false,
            "user" => array(
                "userID" => $result['userID'],
                "nom" => $result['nom'],
                "id_privilegi" => $result['id_privilegi']
            )
        );
        $_SESSION['id'] = $result['id'];
        $_SESSION['userID'] = $result['userID'];
        $_SESSION['nom'] = $result['nom'];
        $_SESSION['id_privilegi'] = $result['id_privilegi'];

        // Close DB connection
        $db = null;

        return $response;
    } else {
        return array("error" => true, "error_msg" => "L'usuari i/o la contrasenya no es correcte");
    }
}

function createUser() {
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
        $_SESSION['id'] = $db->lastInsertId("Usuari");
        $_SESSION['userID'] = $_POST['userID'];
        $_SESSION['nom'] = $_POST['nom'];
        $_SESSION['id_privilegi'] = 2;

        // Close DB connection
        $db = null;

        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut inserir l'usuari", "db_error_msg" => ($select->errorInfo()));
    }
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

        // Close DB connection
        $db = null;

        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut actualitzar l'usuari", "db_error_msg" => ($select->errorInfo()));
    }
}