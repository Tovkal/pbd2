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
            validateInputCrear();
        } else if ($_POST['action'] == "modificar" && isset($_POST['id']) && !empty($_POST['id'])) {
            echo json_encode(fetchAnunci());
        } else if ($_POST['action'] == "modificar") {
            echo json_encode(modificarAnunci());
        } else if ($_POST['action'] == "getList") {
            echo json_encode(getList());
        } else if ($_POST['action'] == "eliminar") {
            echo json_encode(deleteAnunci());
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

/**
 * Valida que tots els camps obligatoris s'hagin enviat, i crida al mètode per crear l'anunci
 */
function validateInputCrear() {
    if (isset($_POST['titolCurt']) && !empty($_POST['titolCurt'])) {
        if (isset($_POST['telefon']) && !empty($_POST['telefon'])) {
            if (isset($_POST['dataWeb']) && !empty($_POST['dataWeb'])) {
                if (isset($_POST['dataNoWeb']) && !empty($_POST['dataNoWeb'])) {
                    if (isset($_POST['seccio']) && !empty($_POST['seccio']) && $_POST['seccio'] != -1) {
                        echo json_encode(crearAnunci());
                    } else {
                        $result = array("error" => true, "error_msg" => "Ha de seleccionar una secció per l'anunci");
                        echo json_encode($result);
                    }
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
}

/**
 * Crear l'anunci a la base de dades i retorna si sh'a completat l'operació amb èxit o no
 *
 * @return array amb la resposta
 */
function crearAnunci() {
    $db = Common::initPDOConnection("BDII_08");
    $columns = "id_usuari, titol_curt, telefon, data_web, data_no_web, codi_seccio";
    $values = ":id_usuari, :titol_curt, :telefon, :data_web, :data_no_web, :codi_seccio";
    $parameters = array(
        'titol_curt' => $_POST['titolCurt'],
        'telefon' => $_POST['telefon'],
        'data_web' => parseDateToDBFormat($_POST['dataWeb']),
        'data_no_web' => parseDateToDBFormat($_POST['dataNoWeb']),
        'id_usuari' => $_SESSION['id_usuari'],
        'codi_seccio' => $_POST['seccio']
    );

    if (isset($_POST['textAnunci']) && !empty($_POST['textAnunci'])) {
        $columns = $columns . ", text_anunci";
        $values = $values . ", :textAnunci";
        $parameters['textAnunci'] = $_POST['textAnunci'];
    }

    if (isset($_POST['photoName']) && !empty($_POST['photoName'])) {
        $columns = $columns . ", foto";
        $values = $values . ", :photoName";
        $parameters['photoName'] = $_POST['photoName'];
    }

    $sql = "INSERT INTO Anunci ({$columns}) VALUES ({$values})";

    $select = $db->prepare($sql);

    $wasSuccessful = $select->execute($parameters);
    if ($wasSuccessful) {
        $response = array("error" => false);

        // Close DB connection
        $db = null;

        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut crear l'anunci", "db_error_msg" => ($select->errorInfo()));
    }
}

// TODO - tovkal - 07/12/2014 - Crear trigger que posi dateNoWeb a hora 23:59:59 enlloc de 0

/**
 * Parse date to datetime format (dd/mm/yyyy -> yyyy-mm-dd)
 *
 * @param $strDate
 * @return string
 */
function parseDateToDBFormat($strDate) {
    $dateTime = DateTime::createFromFormat('d/m/Y', $strDate);
    return $dateTime->format('Y-m-d');

}

/**
 * Parse date from datetime format (yyyy-mm-dd -> dd/mm/yyyy)
 *
 * @param $strDate
 * @return string
 */
function parseDateFromDBFormat($strDate) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $strDate);
    return $dateTime->format('d/m/Y');

}

function fetchAnunci() {
    $db = Common::initPDOConnection("BDII_08");
    $sql = "SELECT id, titol_curt, telefon, date(data_web) as data_web, date(data_no_web) as data_no_web, codi_seccio, foto, text_anunci FROM Anunci WHERE id = :id";
    $select = $db->prepare($sql);
    $wasSuccessful = $select->execute(array('id' => $_POST['id']));
    if ($wasSuccessful) {

        $result = $select->fetch(PDO::FETCH_ASSOC);
        $anunci = array(
            'id' => $result['id'],
            'titolCurt' => $result['titol_curt'],
            'telefon' => $result['telefon'],
            'dataWeb' => parseDateFromDBFormat($result['data_web']),
            'dataNoWeb' => parseDateFromDBFormat($result['data_no_web']),
            'codi_seccio' => $result['codi_seccio'],
            'foto' => $result['foto'],
            'textAnunci' => $result['text_anunci']
        );

        $response = array("error" => false, 'anunci' => $anunci);

        // Close DB connection
        $db = null;

        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut crear l'anunci", "db_error_msg" => ($select->errorInfo()));
    }
}

function modificarAnunci() {
    $db = Common::initPDOConnection("BDII_08");

    $values = "titol_curt = :titolCurt, telefon = :telefon, data_web = :dataWeb, data_no_web = :dataNoWeb, codi_seccio = :codiSeccio";
    $parameters = array(
        'id' => $_POST['idAnunci'],
        'titolCurt' => $_POST['titolCurt'],
        'telefon' => $_POST['telefon'],
        'dataWeb' => parseDateToDBFormat($_POST['dataWeb']),
        'dataNoWeb' => parseDateToDBFormat($_POST['dataNoWeb']),
        'codiSeccio' => $_POST['seccio']
    );

    if (isset($_POST['textAnunci'])) {
        $values = $values . ", text_anunci = :textAnunci";
        if (empty($_POST['textAnunci'])) {
            $parameters['textAnunci'] = null;
        } else {
            $parameters['textAnunci'] = $_POST['textAnunci'];
        }

    }

    if (isset($_POST['photoName'])) {
        $values = $values . ", foto = :photoName";
        if(empty($_POST['photoName'])) {
            $parameters['photoName'] = null;
        } else {
            $parameters['photoName'] = $_POST['photoName'];
        }
    }

    $sql = "UPDATE Anunci SET {$values} WHERE id = :id";
    $update = $db->prepare($sql);
    $wasSuccessful = $update->execute($parameters);
    if ($wasSuccessful) {

        // Close DB connection
        $db = null;

        return array("error" => false);
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut modificar l'anunci", "db_error_msg" => ($update->errorInfo()));
    }
}

function getList() {
    $db = Common::initPDOConnection("BDII_08");
    $sql = "SELECT id, Anunci.titol_curt as 'titol_curt', telefon, date(data_web) as 'data_web', date(data_no_web) as 'data_no_web', foto, Seccio.titol_curt as 'titol_seccio'  FROM Anunci INNER JOIN Seccio ON Anunci.codi_seccio = Seccio.codi_seccio WHERE id_usuari = :idUsuari";
    $select = $db->prepare($sql);
    $wasSuccessful = $select->execute(array('idUsuari' => $_SESSION['id_usuari']));
    if ($wasSuccessful) {

        $anuncis = array();
        foreach($select as $anunci) {
            $dadesAnunci = array(
                'titolCurt' => $anunci['titol_curt'],
                'dataWeb' => parseDateFromDBFormat($anunci['data_web']),
                'dataNoWeb' => parseDateFromDBFormat($anunci['data_no_web']),
                'foto' => $anunci['foto'],
                'titol_seccio' => $anunci['titol_seccio']
            );
            $anuncis[$anunci['id']] = $dadesAnunci;
        };

        $response = array("error" => false, 'anuncis' => $anuncis);

        // Close DB connection
        $db = null;

        return $response;
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut recuperar la llista d'anuncis", "db_error_msg" => ($select->errorInfo()));
    }
}

function deleteAnunci() {
    $db = Common::initPDOConnection("BDII_08");
    $sql = "UPDATE Anunci SET actiu = 0 WHERE id = :id";
    $parameters = array(
        'id' => $_POST['idAnunci']
    );

    $update = $db->prepare($sql);
    $wasSuccessful = $update->execute($parameters);
    if ($wasSuccessful) {

        // Close DB connection
        $db = null;

        return array("error" => false);
    } else {
        return array("error" => true, "error_msg" => "No s'ha pogut eliminar l'anunci. Intenta-ho més tard.", "db_error_msg" => ($update->errorInfo()));
    }
}

?>