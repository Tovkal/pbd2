<?php
// Start the session
session_start();
?>
<html>
<head>
    <?php include("includes/head.html"); ?>
</head>
<body>

<div class="wrapper container-fluid">
    <div class="row">
        <div class="col-md-12 header" >
            <a href="index.php" >Inici</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            (Anuncis aquí)
        </div>
        <div class="col-md-3">
            <?php if(!isset($_SESSION['userID']) || empty($_SESSION['userID'])) { ?>
            <form id="loginForm" role="form" action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Nom d'usuari</label>
                    <input type="text" name="userID" class="form-control" />
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Accedir</button>
                    <button id="signupButton" type="button" class="btn btn-primary" formaction="alta.php" formmethod="post">Donar'se d'alta</button>
                </div>
            </form>
            <?php } else if (isset($_SESSION['userID'])) {
                echo "Logged in as: " . $_SESSION['userID'];
            ?>
            <form id="logoutForm" role="form" action="logout.php" method="post">
                <button type="submit" class="btn btn-primary">Logout</button>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>