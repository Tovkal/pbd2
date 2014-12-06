<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 6/12/14
 * Time: 14:19
 */
// Start session if needed
if(!isset($_SESSION)){
    session_start();
}

include "../common.php";

if (Common::is_ajax()) {
    if (isset($_POST['action']) && !empty($_POST['action'])) {
        if ($_POST['action'] == 'crear') {
            echo json_encode(getSeccioList());
        }
    }
    exit();
}

exit();

function getSeccioList() {
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("SELECT codi_seccio, titol_curt FROM Seccio");

    $wasSuccessful = $select->execute();
    if ($wasSuccessful) {

        $seccions = array();
        foreach($select as $seccio) {
            $seccions[$seccio['codi_seccio']] = $seccio['titol_curt'];
        }

        // Close DB connection
        $db = null;

        return array("error" => false, "opcions" => $seccions);
    } else {
        return array("error" => true, "error_msg" => "No s'han pogut carregar les seccions. Intenti recarregar la pàgina.");
    }
}