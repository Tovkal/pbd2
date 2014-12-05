<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 5/12/14
 * Time: 21:14
 */

// Start session if needed
if(!isset($_SESSION)){
    session_start();
}

include "../common.php";

if (Common::is_ajax()) {
    if (isset($_POST['action']) && !empty($_POST['action'])) {
        if ($_POST['action'] == "crear") {
            if (isset($_POST['titolCurt']) && !empty($_POST['titolCurt'])) {
                if (isset($_POST['telefon']) && !empty($_POST['telefon'])) {
                    if (isset($_POST['dataWeb']) && !empty($_POST['dataWeb'])) {
                        if (isset($_POST['dataNoWeb']) && !empty($_POST['dataNoWeb'])) {
                            //crearAnunci();
                        } else {
                            $result = array("error" => true, "error_msg" => "La data de despublicació no pot estar buida");
                            echo json_encode($result);
                        }
                    } else {
                        $result = array("error" => true, "error_msg" => "La data de publicació no pot estar buida");
                        echo json_encode($result);
                    }
                } else {
                    $result = array("error" => true, "error_msg" => "El telèfon es obligatori perque els compradors et pugin contactar");
                    echo json_encode($result);
                }
            } else {
                $result = array("error" => true, "error_msg" => "El titol curt és un camp obligatori");
                echo json_encode($result);
            }
        } else {
            $result = array("error" => true, "error_msg" => "Acció desconeguda");
            echo json_encode($result);
        }
    } else {
        $result = array("error" => true, "error_msg" => "Acció buida");
        echo json_encode($result);
    }


    exit();
}