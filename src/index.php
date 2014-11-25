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
        <div id="content" class="col-md-9 border">&nbsp;</div>
        <div class="col-md-3 border">
            <div class="row row-top-margin">
                <div class="col-md-12">
                    <div id="alert" class="hidden" role="alert">Iep</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form id="userForm">
                        <div class="form-group row-centered">
                            <label for="userID">Nom d'usuari</label>
                            <input type="text" id="userID" name="userID" class="form-control" />
                            <p id="userInfo" class="hidden"></p>
                            <div id="expandedSignup" class="row-top-margin hidden">
                                <label for="nom">Nom i cognoms</label>
                                <input type="text" id="nom" name="nom" class="form-control" />
                            </div>
                        </div>

                        <div class="row row-centered">
                            <button id="loginButton" type="button" class="btn btn-primary" onclick="doLogin();">Accedir</button>
                            <button id="signupButton" type="button" class="btn btn-primary" onclick="doSignup();">Donar'se d'alta</button>
                            <button id="modifyProfileButton" type="button" class="btn btn-primary hidden" onclick="modifyProfile();">Modificar perfil</button>
                            <button id="logoutButton" type="button" class="btn btn-primary hidden" onclick="doLogout();">Logout</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row row-centered">
                <div class="col-md-12">
                    <button id="nouAnunci" type="button" class="btn btn-primary">Publicar anunci</button>
                    <button id="veureAnuncis" type="button" class="btn btn-primary">Veure anuncis</button>
                </div>
            </div>

            <div id="adminPanel" class="row row-centered row-top-margin">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary">Administrar seccions</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var content = $("#content");
    var userForm = $("#userForm");
    var loginButton = $("#loginButton");
    var signupButton = $("#signupButton");
    var modifyProfileButton = $("#modifyProfileButton");
    var logoutButton = $("#logoutButton");
    var userID = $("#userID");
    var userInfo = $("#userInfo");
    var expandedSignup = $("#expandedSignup");

    $(document).ready(function() {
        showLoggedIn();
        showAnuncis();
        //getPHPFile('profile', content);
    });

    function showAnuncis() {
        getPHPFile('anuncis', content);
    }

    function doLogin() {
        if (expandedSignup.isVisible()) {
            hideSignupForm();
        }

        var data = userForm.serialize();
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
                    modifyProfileButton.showBootstrap();
                    logoutButton.showBootstrap();
                    userID.hideBootstrap();
                    userInfo.showBootstrap();
                    userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function() {
                //show alert error
                //TODO show error
            }
        });
    }

    function showLoggedIn() {
        <?php if(isset($_SESSION['userID']) && !empty($_SESSION['userID'])) { ?>
        loginButton.hideBootstrap();
        signupButton.hideBootstrap();
        modifyProfileButton.showBootstrap();
        logoutButton.showBootstrap();
        userID.hideBootstrap();
        userInfo.showBootstrap();
        userInfo.text("Hola, <?php echo $_SESSION['nom'] ?> (<?php echo $_SESSION['userID'] ?>)");
        <?php } ?>
    }

    function doSignup() {
        loginButton.addClass("disabled");
        showSignupForm();
        showInfo("Insereix el teu nom i cognoms");
        signupButton.attr("onClick", "doSignupAjaxCall();");
        if(userID.isEmpty()) {
            showError("Escriu un nom d'usuari");
            userID.on("keypress", function() {
                fadeAlert();
            });
        }
    }

    function showSignupForm() {
        expandedSignup.showBootstrap();
    }

    function hideSignupForm() {
        expandedSignup.hideBootstrap();
    }

    function doSignupAjaxCall() {
        var data = userForm.serialize();
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "alta.php",
            data: data,
            success: function(returned_data) {
                var result = JSON.parse(returned_data);

                if (result['error'] == true) {
                    showError(result['error_msg']);
                    console.log(result['db_error_msg']);
                } else {
                    var user = result['user'];

                    showSuccess("Estas conectat, benvingut");
                    loginButton.hideBootstrap();
                    signupButton.hideBootstrap();
                    logoutButton.showBootstrap();
                    userID.hideBootstrap();
                    userInfo.showBootstrap();
                    userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function() {
                //show alert error
                //TODO show error
            }
        });
    }

    function modifyProfile() {
        getPHPFile('perfil', content);
    }

    function doLogout() {
        loginButton.showBootstrap();
        signupButton.showBootstrap();
        modifyProfileButton.hideBootstrap();
        logoutButton.hideBootstrap();
        userID.showBootstrap();
        userInfo.hideBootstrap();


        if (loginButton.hasClass('disabled')) {
            loginButton.removeClass('disabled');
        }

        /*
         * Per qualque motiu, quan aqui posava un session_destroy aquest s'executava quan refrescaves sa pàgina un parell
         * de vegades sense cridar a aquesta funcio, per això he afegit un php especific per logout
         */
        window.location = "logout.php";
    }

    var $alert = $("#alert");

    function showError(msg) {
        setupAlert("alert-danger fade-in", msg);
    }

    function showSuccess(msg) {
        setupAlert("alert-success fade-in", msg);
        setTimeout(fadeAlert, 3000);
    }

    function showInfo(msg) {
        setupAlert("alert-info fade-in", msg);
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

    (function($) {
        $.fn.hideBootstrap = function() {
            if (!this.hasClass("hidden")) {
                this.addClass("hidden");
            }
        };
        $.fn.showBootstrap = function() {
            if (this.hasClass("hidden")) {
                this.removeClass("hidden");
            }
        };
        $.fn.isEmpty = function() {
            return this.val().length == 0;
        };
        $.fn.isVisible = function() {
            return !this.hasClass('hidden');
        };
    })(jQuery);

</script>
</body>
</html>