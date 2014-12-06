<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 1/12/14
 * Time: 21:19
 */
 // Start the session
if(!isset($_SESSION)){
    session_start();
}?>

<div class="row row-top-margin">
    <div class="col-md-12">
        <div id="sideAlert" class="hidden" role="alert" style="margin-bottom: 0;">Alert</div>
    </div>
</div>

<div id="userSection" class="row">
    <div class="col-md-12">
        <form id="userForm" name="userForm">
            <input id="action" name="action" type="hidden" value="" />
            <div class="form-group row-centered">
                <div class="row row-top-margin hideOnLogin">
                    <div class="col-md-12">
                        <label for="userID">ID d'usuari</label>
                        <input type="text" id="userID" name="userID" class="form-control" />
                    </div>
                </div>
                <div class="row row-top-margin hideOnLogin">
                    <div class="col-md-12">
                        <label for="password">Contrasenya</label>
                        <input type="text" id="password" name="password" class="form-control" />
                    </div>
                </div>
                <div class="row row-top-margin hideLoggedOut hidden">
                    <div class="col-md-12">
                        <p id="userInfo"></p>
                    </div>
                </div>
                <div id="expandedSignup" class="row hidden">
                    <div class="col-md-12">
                        <label for="nom">Nom i cognoms</label>
                        <input type="text" id="nom" name="nom" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="row row-centered">
                <button id="loginButton" type="button" class="btn btn-primary hideOnLogin" onclick="doLogin();">Accedir</button>
                <button id="signupButton" type="button" class="btn btn-primary hideOnLogin" onclick="doSignup();">Donar'se d'alta</button>
                <button id="modifyProfileButton" type="button" class="btn btn-primary hideLoggedOut hidden" onclick="modifyProfile();">Modificar perfil</button>
                <button id="logoutButton" type="button" class="btn btn-primary hideLoggedOut hidden" onclick="doLogout();">Logout</button>
            </div>
        </form>
    </div>
</div>
<div id="sellerSection" class="row row-centered hideLoggedOut hidden">
    <hr>
    <div class="col-md-12">
        <p class="lead">Anuncis</p>
        <button id="nouAnunci" type="button" class="btn btn-primary" onclick="crearAnunci()">Publicar anunci</button>
        <button id="veureAnuncis" type="button" class="btn btn-primary">Veure anuncis</button>
    </div>
</div>
<div id="adminSection" class="row row-centered row-top-margin hidden">
    <hr>
    <div class="col-md-12">
        <p class="lead">Administració</p>
        <button type="button" class="btn btn-primary" onclick="adminSeccions();">Seccions</button>
        <button type="button" class="btn btn-primary">Usuaris</button>
    </div>
</div>

<script type="application/javascript">

    var $userForm = $("#userForm");
    var $loginButton = $userForm.find("#loginButton");
    var $signupButton = $userForm.find("#signupButton");
    var $modifyProfileButton = $userForm.find("#modifyProfileButton");
    var $logoutButton = $userForm.find("#logoutButton");
    var $userID = $userForm.find("#userID");
    var $password = $userForm.find("#password");
    var $userInfo = $userForm.find("#userInfo");
    var $expandedSignup = $userForm.find("#expandedSignup");
    var $sideAlert = $("#sideAlert");
    var $action = $userForm.find("#action");

    $(document).ready(function() {
       showLoggedIn();

        // After writing password and pressing enter, submit form to login.
        $password.keydown(function() {
            if (event.which == 13) {
                event.preventDefault();
                doLogin();
            }
        });
    });

    function showLoggedIn() {
        <?php if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) { ?>
            didLogin();
        $userInfo.text("Hola, <?php echo $_SESSION['nom'] ?> (<?php echo $_SESSION['userID'] ?>)");
        <?php if (isset($_SESSION['id_privilegi']) && !empty($_SESSION['id_privilegi'])) { ?>
            adminLogin();
        <?php } } ?>
    }

    function didLogin() {
        $(".hideOnLogin").hideBootstrap();
        $(".hideLoggedOut").showBootstrap();
    }

    function didLogout() {
        $(".hideOnLogin").showBootstrap();
        $(".hideLoggedOut").hideBootstrap();
    }

    function adminLogin() {
        $("#adminSection").showBootstrap();
    }

    function adminLogout() {
        $("#adminSection").hideBootstrap();
    }

    function doLogin() {
        if ($expandedSignup.isVisible()) {
            hideSignupForm();
        }

        $action.val("login");

        var data = $userForm.serialize();
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/usuari.php",
            data: data,
            success: function(returned_data) {

                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    $("#content").html(returned_data);
                }

                if (result['error'] == true) {
                    showError($sideAlert, result['error_msg']);
                } else {

                    var user = result['user'];

                    showSuccess($sideAlert, "Estas conectat, benvingut", 3000);
                    didLogin();
                    if (user['id_privilegi'] == 1) {
                        adminLogin();
                    }
                    $userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function(err) {
                showError($sideAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }

    function doSignup() {
        $loginButton.addClass("disabled");
        showSignupForm();
        showInfo($sideAlert, "Insereix el teu nom i cognoms");
        $signupButton.attr("onClick", "doSignupAjaxCall();");
        if ($userID.isEmpty()) {
            showError($sideAlert, "Escriu un nom d'usuari");
            $userID.on("keypress", function() {
                fadeAlertWithoutDelay($sideAlert);
            });
        } else if ($password.isEmpty()) {
            showError($sideAlert, "Escriu una contrasenya");
            $password.on("keypress", function() {
                fadeAlertWithoutDelay($sideAlert);
            });
        }
    }

    function showSignupForm() {
        $expandedSignup.showBootstrap();
    }

    function hideSignupForm() {
        $expandedSignup.hideBootstrap();
    }

    function doSignupAjaxCall() {
        $action.val("signup");

        var data = $userForm.serialize();
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/usuari.php",
            data: data,
            success: function(returned_data) {
                var result = JSON.parse(returned_data);

                if (result['error'] == true) {
                    showError($sideAlert, result['error_msg']);
                    console.log(result['db_error_msg']);
                } else {
                    var user = result['user'];

                    didLogout();
                    $userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function(err) {
                showError($sideAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }


    // Redirections
    function modifyProfile() {
        window.location.href = "perfil.php";
    }

    function crearAnunci() {
        window.location.href = "anunci.php?a=crear";
    }

    function adminSeccions() {
        window.location.href = "admin_seccions.php";
    }

    function doLogout() {
        $loginButton.showBootstrap();
        $signupButton.showBootstrap();
        $modifyProfileButton.hideBootstrap();
        $logoutButton.hideBootstrap();
        $userID.showBootstrap();
        $password.showBootstrap();
        $userInfo.hideBootstrap();

        adminLogout();


        if ($loginButton.hasClass('disabled')) {
            $loginButton.removeClass('disabled');
        }

        /*
         * Per qualque motiu, quan aqui posava un session_destroy aquest s'executava quan refrescaves sa pàgina un parell
         * de vegades sense cridar a aquesta funcio, per això he afegit un php especific per logout
         */
        window.location = "dao/logout.php";
    }
</script>