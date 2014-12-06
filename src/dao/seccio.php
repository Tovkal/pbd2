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
        if ($_POST['action'] == 'crearAnunci') {
            echo json_encode(getSeccioList());
        } else if ($_POST['action'] == 'fullFetch') {
            echo json_encode(getFullSeccioList());
        } else if ($_POST['action'] == 'edit') {
            validateEdit();
        } else if ($_POST['action'] == 'delete') {

        }
    }
    exit();
}

function validateEdit() {
    if (isset($_POST['titolCurt']) && !empty($_POST['titolCurt'])) {
        if (isset($_POST['preu']) && !empty($_POST['preu'])) {
            if (isset($_POST['photoName']) && !empty($_POST['photoName'])) {
                echo json_encode(editSeccio());
            } else {
                $result = array("error" => true, "error_msg" => "No s'ha pujat una foto");
                echo json_encode($result);
            }
        } else {
            $result = array("error" => true, "error_msg" => "No s'ha indicat un preu");
            echo json_encode($result);
        }
    } else {
        $result = array("error" => true, "error_msg" => "No s'ha indicat un titol curt");
        echo json_encode($result);
    }

}

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

function getFullSeccioList() {
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("SELECT * FROM Seccio");

    $wasSuccessful = $select->execute();
    if ($wasSuccessful) {

        $seccions = array();
        foreach($select as $seccio) {
            $dadesSeccio = array(
                'titolCurt' => $seccio['titol_curt'],
                'descripcio' => $seccio['descripcio'],
                'preu' => $seccio['preu'],
                'foto' => $seccio['foto_generica_seccio']
            );
            $seccions[$seccio['codi_seccio']] = $dadesSeccio;
        }

        // Close DB connection
        $db = null;

        return array("error" => false, "seccions" => $seccions);
    } else {
        return array("error" => true, "error_msg" => "No s'han pogut carregar les seccions. Intenti recarregar la pàgina.");
    }
}

function editSeccio() {
    $db = Common::initPDOConnection("BDII_08");
    $sql = "UPDATE Seccio SET titol_curt = :titolCurt, preu = :preu, foto_generica_seccio = :foto";
    $parameters = array(
        'titolCurt' => $_POST['titolCurt'],
        'preu' => $_POST['preu'],
        'foto' => $_POST['photoName'],
        'codi_seccio' => $_POST['codi_seccio']
    );
    if (isset($_POST['descripcio']) && !empty($_POST['descripcio'])) {
        $sql = $sql . ", descripcio = :descripcio";
        $parameters['descripcio'] = $_POST['descripcio'];
    }
    $sql = $sql . " WHERE codi_seccio = :codi_seccio";

    $select = $db->prepare($sql);
    $wasSuccessful = $select->execute($parameters);
    if ($wasSuccessful) {

        // Close DB connection
        $db = null;

        return array("error" => false);
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut actualitzar la secció. Intenta-ho més tard.");
    }
}