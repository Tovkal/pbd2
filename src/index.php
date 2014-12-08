<?php
// Start the session
if(!isset($_SESSION)){
    session_start();
}?>

<html>
<head>
    <?php include("includes/head.html"); ?>
    <title>Inici - Ultima Hora de Mallorca - Clasificats</title>
</head>
<body>

<div class="wrapper container-fluid">
    <div class="row column border">
        <div class="col-md-9" >
            <a href="index.php">
                <img src="img/logo.png" class="logo" />
            </a>
        </div>
    </div>
    <div class="row column">
        <div id="content" class="col-md-9 border">
            <?php include 'llista_anuncis.php';?>
        </div>
        <div id="menuColumn" class="col-md-3 border column">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>
</body>
</html>