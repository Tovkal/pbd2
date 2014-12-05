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

// Only logged users can view the page
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    header('Location: index.php');
    echo "potato";
}

include "common.php";

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

<html>
<head>
    <?php include "includes/head.html"; ?>
</head>
<body>

<div class="wrapper container-fluid">
    <div class="row">
        <div class="col-md-12 border" >
            <a href="index.php" >Inici</a>
        </div>
    </div>
    <div class="row">
        <div id="content" class="col-md-9 border">
            <div class="row row-top-margin" style="padding-right: 10px; padding-left: 10px;">
                <div class="col-md-12">
                    <div id="mainAlert" class="hidden" role="alert">Alert</div>
                </div>
            </div>
            <div class="row" style="padding-right: 10px; padding-left: 10px;">
                <div class="col-md-12">
                    <p style="padding-bottom: 10px;">A continuació es mostren els valors actuals del teu compte. Si vols modificar-ne un, insereix el text a la casella adequada i pulsa modificar.</p>
                    <form id="profileForm" name="profileForm" role="form">
                        <input id="action" name="action" type="hidden" value="update" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userID">ID d'usuari</label>
                                    <input type="text" id="userID" name="userID" class="form-control" value="<?php echo $_SESSION['userID'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label for="nom">Nom i Cognoms </label>
                                    <input type="text" id="nom" name="nom" class="form-control" value="<?php echo $_SESSION['nom'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row row-top-margin">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Contrasenya</label>
                                    <input type="text" id="password" name="password" class="form-control" value="<?php echo $_SESSION['password'] ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipusUsuari">Tipus d'usuari</label>
                                    <p class="form-control-static" id="tipusUsuari"><?php echo $_SESSION['descripcio_privilegi'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right row-top-margin" style="padding-bottom: 10px;">
                                    <button type="button" class="btn btn-success" onclick="processUpdate()">Modificar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

<script type="application/javascript">
    var $mainAlert = $("#mainAlert");

    $(document).ready(function() {
       <?php if(isset($_SESSION['updateOK']) && !empty($_SESSION['updateOK'])) { ?>
            showSuccess($mainAlert, "S'han actualitzat correctament les dades.", 3000);
       <?php unset($_SESSION['updateOK']); } ?>
    });

    function processUpdate() {
        var data = $("#profileForm").serialize();
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/usuari.php",
            data: data,
            success: function(returned_data) {
                var result = JSON.parse(returned_data);

                if (result['error'] == true) {
                    showError($mainAlert, result['error_msg']);
                } else {
                    location.reload();
                }
            },
            error: function() {
                //show alert error
                //TODO show error
            }
        });
    }
</script>

</body>
</html>