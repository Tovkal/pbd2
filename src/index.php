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
            <div id="alert" class="hidden" role="alert">Iep</div>
            <form id="userForm" role="form">
                <div class="form-group">
                    <label for="userID">Nom d'usuari</label>
                    <input type="text" id="nameID" name="userID" class="form-control" />
                    <p id="userInfo" class="hidden"></p>
                </div>
                <div>
                    <button id="loginButton" type="button" class="btn btn-primary" onclick="doLogin();">Accedir</button>
                    <button id="signupButton" type="button" class="btn btn-primary" onclick="doSignup();">Donar'se d'alta</button>
                    <button id="logoutButton" type="submit" class="btn btn-primary hidden" onclick="doLogout();">Logout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    var loginButton = $("#loginButton");
    var signupButton = $("#signupButton");
    var logoutButton = $("#logoutButton");
    var nameID = $("#nameID");
    var userInfo = $("#userInfo");

    function doLogin() {
        var data = $("#userForm").serialize();
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "login.php",
            data: data,
            success: function(returned_data) {
                var result = JSON.parse(returned_data);

                if (result['error'] == true) {
                    showError(result['error_msg']);
                } else {
                    var user = result['user'];

                    showSuccess("Estas conectat, benvingut");
                    loginButton.hideBootstrap();
                    signupButton.hideBootstrap();
                    logoutButton.showBootstrap();
                    nameID.hideBootstrap();
                    userInfo.showBootstrap();
                    userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function() {
                //show alert error
            }
        });
    }

    var $alert = $("#alert");

    function showError(msg) {
        setupAlert("alert-danger fade-in", msg);
    }

    function showSuccess(msg) {
        setupAlert("alert-success fade-in", msg);
        setTimeout(fadeAlert, 3000);
    }

    function setupAlert(alertClass, msg) {
        $alert.attr("class", "alert " + alertClass);
        $alert.text(msg);
    }

    function fadeAlert() {
        $alert.fadeOut("slow", function() {
            $alert.attr("class", "hidden");
            $alert.removeAttr("style");
        });
    }

    (function($){
        $.fn.hideBootstrap = function(){
            if (!this.hasClass("hidden")) {
                this.addClass("hidden");
            }
        };
        $.fn.showBootstrap = function(){
            if (this.hasClass("hidden")) {
                this.removeClass("hidden");
            }
        };

    })(jQuery);

</script>
</body>
</html>