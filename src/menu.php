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
        <div id="sideAlert" class="hidden" role="alert">Alert</div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form id="userForm" name="userForm">
            <input id="action" name="action" type="hidden" value="" />
            <div class="form-group row-centered">
                <label for="userID">ID d'usuari</label>
                <input type="text" id="userID" name="userID" class="form-control" />
                <label for="password">Contrasenya</label>
                <input type="text" id="password" name="password" class="form-control" />
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
        <button type="button" class="btn btn-primary">Veure usuaris</button>
    </div>
</div>

<script type="text/javascript">

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
        <?php if(isset($_SESSION['userID']) && !empty($_SESSION['userID'])) { ?>
        $loginButton.hideBootstrap();
        $signupButton.hideBootstrap();
        $modifyProfileButton.showBootstrap();
        $logoutButton.showBootstrap();
        $userID.hideBootstrap();
        $password.hideBootstrap();
        $userInfo.showBootstrap();
        $userInfo.text("Hola, <?php echo $_SESSION['nom'] ?> (<?php echo $_SESSION['userID'] ?>)");
        <?php } ?>
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
                var result = JSON.parse(returned_data);
                if (result['error'] == true) {
                    showError($sideAlert, result['error_msg']);
                } else {
                    var user = result['user'];

                    showSuccess($sideAlert, "Estas conectat, benvingut");
                    $loginButton.hideBootstrap();
                    $signupButton.hideBootstrap();
                    $modifyProfileButton.showBootstrap();
                    $logoutButton.showBootstrap();
                    $userID.hideBootstrap();
                    $password.hideBootstrap();
                    $userInfo.showBootstrap();
                    $userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function() {
                //show alert error
                //TODO show error
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

                console.log("taL?=" + result['error']);
                if (result['error'] == true) {
                    showError($sideAlert, result['error_msg']);
                    console.log(result['db_error_msg']);
                } else {
                    var user = result['user'];

                    showSuccess($sideAlert, "Estas conectat, benvingut");
                    $loginButton.hideBootstrap();
                    $signupButton.hideBootstrap();
                    $logoutButton.showBootstrap();
                    $userID.hideBootstrap();
                    $password.hideBootstrap();
                    $userInfo.showBootstrap();
                    $userInfo.text("Hola, " + user['nom'] + " (" + user['userID'] + ")");
                }
            },
            error: function() {
                //show alert error
                //TODO show error
            }
        });
    }

    function modifyProfile() {
        window.location.href="perfil.php";
    }

    function doLogout() {
        $loginButton.showBootstrap();
        $signupButton.showBootstrap();
        $modifyProfileButton.hideBootstrap();
        $logoutButton.hideBootstrap();
        $userID.showBootstrap();
        $password.showBootstrap();
        $userInfo.hideBootstrap();


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