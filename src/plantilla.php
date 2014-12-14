<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 2/12/14
 * Time: 14:52
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}?>

<html>
<head>
    <?php include "includes/head.html"; ?>
</head>
<body>

<div class="container-fluid">
    <div class="row column">
        <div class="col-md-9" >
            <a href="index.php">
                <img src="img/logo.png" class="logo" />
            </a>
        </div>
    </div>
    <div class="row">
        <div id="content" class="col-md-9">&nbsp;</div>
        <div id="menuColumn" class="col-md-3">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

</body>
</html>