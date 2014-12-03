<?php
// Start the session
if(!isset($_SESSION)){
    session_start();
}?>

<html>
<head>
    <?php include("includes/head.html"); ?>
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
            <?php include 'llista_anuncis.php';?>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>
</body>
</html>