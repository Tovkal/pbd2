<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 23/11/14
 * Time: 16:24
 */

// Start the session
if(!isset($_SESSION)){
    session_start();
}

include "common.php";

/*$_SESSION['userID'] = "TEST";
$_SESSION['nom'] = "TEST TEST TEST";
$_SESSION['id_privilegi'] = "1";
$_SESSION['descripcio_privilegi'] = "Anunciant";*/

// Fetch user's privilege description if not known
if (!isset($_SESSION['descripcio_privilegi']) || empty($_SESSION['descripcio_privilegi'])) {
    $db = Common::initPDOConnection("BDII_08");
    $select = $db->prepare("SELECT descripcio FROM Privilegi p INNER JOIN Usuari u ON p.id = u.id_privilegi WHERE u.userID = :userID");
    $wasSuccessful = $select->execute(array('userID' => $_SESSION['userID']));

    if ($wasSuccessful && $select->rowCount() == 1) {
        $result = $select->fetch(PDO::FETCH_ASSOC);

        $_SESSION['descripcio_privilegi'] = $result['descripcio'];
    } else {
        //TODO Millorar presentació error
        echo "Error obtening la descripció del nivell de privilegis de l'usuari amb userID: " . $_SESSION['userID'];
        print_r($select->errorInfo());
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form name="profile" class="form-inline" role="form">
                <div class="form-group form-group-right-padding">
                    <label for="userID">Nom d'usuari</label>
                        <input type="text" id="userID" name="userID" class="form-control" value="<?php echo $_SESSION['userID'] ?>">
                </div>
                <div class="form-group form-group-right-padding">

                    <label for="nom">Nom i Cognoms </label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?php echo $_SESSION['nom'] ?>">
                </div>
                <div class="form-group">
                    <label for="tipusUsuari">Tipus d'usuari</label>
                        <p class="form-control-static" id="tipusUsuari"><?php echo $_SESSION['descripcio_privilegi'] ?></p>
                </div>
            </form>
        </div>
    </div>
</div>