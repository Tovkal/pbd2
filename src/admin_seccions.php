<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 6/12/14
 * Time: 16:42
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}

// Only logged admin users can view the page
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])
    || !isset($_SESSION['id_privilegi']) || empty($_SESSION['id_privilegi']) || $_SESSION['id_privilegi'] != 1) {
    header('Location: index.php');
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
            <h3>Administració de les seccions</h3>
            <hr>
            <div id="mainAlert" class="hidden" role="alert"></div>

            <div id="viewSeccions" class="row">
                <div class="col-md-12">
                    <table id="seccionsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-3">Foto genèrica de secció</th>
                                <th class="col-md-2">Titol curt</th>
                                <th class="col-md-4">Descripció</th>
                                <th class="col-md-2">Preu de secció</th>
                                <th class="col-md-1"><button id="createSeccioBtn" type="button" class="btn btn-success" onclick="createSeccio();"><span class="glyphicon glyphicon-plus"></span></button></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="editSeccio" class="row hidden">
                <div class="col-md-12">
                    <form id="seccioForm" class="form" role="form" style="margin-bottom: 0;">
                        <input id="photoName" name="photoName" type="hidden" />
                        <input id="action" name="action" type="hidden" />
                        <input id="codi_seccio" name="codi_seccio" type="hidden" />
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Crear secció</h4>
                                <hr>
                                <div id="editSeccioAlert" class="hidden" role="alert"></div>
                            </div>
                        </div>
                        <div class="row">
                            <span class="help-block" style="padding-left: 15px; padding-bottom: 5px;">El text curt, el preu i la foto són camps obligatoris.</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="titolCurt" class="control-label">Titol curt*</label>
                                    <input type="text" class="form-control obligatori" id="titolCurt" name="titolCurt" maxlength="30" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="descripcio">Descripció</label>
                                    <input type="text" id="descripcio" name="descripcio" class="form-control" maxlength="30" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="preu" class="control-label">Preu*</label>
                                    <input type="text" pattern="\d+(\.\d{2})?" class="form-control obligatori" id="preu" name="preu" />
                                    <span class="help-block" >Cèntims separats per un punt.</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="photoAlert" class="hidden" role="alert"> </div>
                                <div id="photoUpload">
                                    <!-- Standar Form -->
                                    <label>
                                        Selecciona una foto del teu ordenador
                                    </label>
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <input type="file" name="photoFile" id="js-upload-files" />
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary" id="js-upload-submit">Pujar foto</button>
                                    </div>

                                    <!-- Drop Zone -->
                                    <label>
                                        O arrosega una foto al recuardre
                                    </label>
                                    <div class="upload-drop-zone" id="drop-zone">
                                        Arrosega i amolla la foto aquí
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="progress">
                                        <div id="photoProgressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;">
                                        </div>
                                    </div>
                                </div>
                                <div id="photoPreview" class="form-group hidden">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="foto">Foto*</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div id="photo" style="height: 250px; border: 1px dashed #000000;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button id="reuploadPhotoBtn" type="button" class="btn btn-primary hidden" onclick="reuploadPhoto();">Canviar foto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script type="application/javascript" src="js/uploadPhoto.js"></script>
                        </div>
                    </form>
                    <div class="row pull-right" style="padding-bottom: 20px;">
                        <div class="col-md-12">
                            <button id="cancelCreationBtn" class="btn btn-primary" onclick="hideEditSeccio()">Cancelar</button>
                            <button id="crearBtn" class="btn btn-success" onclick="submitAnunci();">Crear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

<script type="application/javascript">
    var seccions;
    var $seccionsTable = $("#seccionsTable");
    var $mainAlert = $("#mainAlert");
    var $editSeccio = $("#editSeccio");
    var $titolCurt = $("#titolCurt");
    var $descripcio = $("#descripcio");
    var $preu = $("#preu");
    var $crearBtn = $("#crearBtn");
    var $action = $("#action");
    var $editSeccioAlert = $("#editSeccioAlert");
    var $codi_seccio = $("#codi_seccio");

    $(document).ready(function() {
        setupTable();
    });

    function addRowToTable(id, seccio) {
        var $titolCurt = $("<td></td>").text(seccio['titolCurt']).addClass("col-md-2");
        var $descripcio = $("<td></td>").text(seccio['descripcio'] ? seccio['descripcio'] : "").addClass("col-md-4");
        var $preu = $("<td></td>").text(seccio['preu']).addClass("col-md-2");
        var $foto = $("<td></td>").html("<img src='img/seccio/" + seccio['foto'] + "' style='width:100px;' />").addClass("col-md-3");
        var $editBtn = $("<button></button>").attr("type", "button").attr("value", id).addClass("btn").addClass("btn-primary").html('<span class="glyphicon glyphicon-pencil"></span>').attr("onclick", "editSeccio(this.value)");
        var $deleteBtn = $("<button></button>").attr("type", "button").attr("value", id).addClass("btn").addClass("btn-danger").html('<span class="glyphicon glyphicon-trash"></span>').attr("onclick", "deleteSeccio(this.value)");
        var $buttons = $("<td></td>").append($editBtn).append($deleteBtn).addClass("col-md-1");
        var $row = $("<tr></tr>").attr("seccio", id).append($foto).append($titolCurt).append($descripcio).append($preu).append($buttons);
        $seccionsTable.append($row);
    }

    function setupTable() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/seccio.php",
            data: "action=fullFetch",
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    $seccionsTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació de les seccions. Intenta actualtizar la pàgina.</td></tr>");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        $seccionsTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació de les seccions. Intenta actualtizar la pàgina.</td></tr>");
                        console.log(result['db_msg_error']);
                    } else {
                        seccions = result['seccions'];
                        $.each(seccions, function (key, value) {
                            addRowToTable(key, value);
                        });
                    }
                }
            },
            error: function(err) {
                $seccionsTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació de les seccions. Intenta actualtizar la pàgina.</td></tr>");
                console.log(err);
            }
        });
    }

    function showEditSeccio() {
        $editSeccioAlert.hideBootstrap();
        $editSeccio.showBootstrap();
        $('html, body').animate({
            scrollTop: $editSeccio.offset().top
        }, 750);
    }

    function hideEditSeccio() {
        $editSeccio.hideBootstrap();
    }

    function createSeccio() {
        $editSeccio.find("h4").text("Crear secció");
        $crearBtn.text("Crear");
        $crearBtn.attr("onclick", "submitCrearSeccio()");
        $("#seccioForm").find("input[type=text], textarea, input[name=photoName]").val("");
        $action.val("create");
        showEditSeccio();
    }

    function submitCrearSeccio() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/seccio.php",
            data: $("#seccioForm").serialize(),
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($editSeccioAlert, "No s'ha creat correctament la secció. Refresca la pàgina i torna a intentar-ho.");
                    if (result) {
                        console.log(result['db_error_msg']);
                    }
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        showError($editSeccioAlert, result['error_msg']);
                        console.log(result['db_msg_error']);
                    } else {
                        showSuccess($mainAlert, "S'ha creat correctament la secció", 2000);
                        addRowToTable(result['seccio']['codi_seccio'], result['seccio']);
                        hideEditSeccio();
                    }
                }
            },
            error: function(err) {
                showError($editSeccioAlert, "No s'ha pogut contactar amb el servidor. Torna a provar-ho d'aquí uns segons.");
                console.log(err);
            }
        });
    }

    function editSeccio(codi_seccio) {
        $editSeccio.find("h4").text("Modificar secció");
        $crearBtn.text("Modificar");
        $crearBtn.attr("onclick", "submitEditSeccio()");
        $action.val("edit");
        if (seccions) {
            $titolCurt.val(seccions[codi_seccio]['titolCurt']);
            $preu.val(seccions[codi_seccio]['preu']);
            $descripcio.val(seccions[codi_seccio]['descripcio']);
            $codi_seccio.val(codi_seccio);
            $("#photoUpload").hideBootstrap();
            $("#photoName").val(seccions[codi_seccio]['foto']);
            $("#photo").html("<img src='img/seccio/" + seccions[codi_seccio]['foto'] + "' style='display:block;margin:auto;height:100%; width:100%;'>");
            $("#reuploadPhotoBtn").showBootstrap();
            $("#photoPreview").showBootstrap();
        } else {
            showError($("#editSeccioAlert"), "No s'ha pogut obtenir les dades de la fila. Refresca la pàgina i torna a intentar-ho");
        }
        showEditSeccio();
    }

    function submitEditSeccio() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/seccio.php",
            data: $("#seccioForm").serialize(),
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($editSeccioAlert, "No s'ha modificar correctament la secció. Refresca la pàgina i torna a intentar-ho.");
                    if (result) {
                        console.log(result['db_error_msg']);
                    }
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        showError($editSeccioAlert, result['error_msg']);
                        console.log(result['db_msg_error']);
                    } else {
                        showSuccess($mainAlert, "S'ha modificar correctament la secció", 2000);
                        $seccionsTable.find("tbody").find("tr").each(function() {
                            this.remove();
                        });
                        hideEditSeccio();
                        setupTable();
                    }
                }
            },
            error: function(err) {
                showError($editSeccioAlert, "No s'ha pogut contactar amb el servidor. Torna a provar-ho d'aquí uns segons.");
                console.log(err);
            }
        });
    }

    function deleteSeccio(codi_seccio) {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/seccio.php",
            data: "action=delete&codi_seccio=" + codi_seccio,
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($mainAlert, "No s'ha eliminat correctament la secció. Refresca la pàgina i torna a intentar-ho.");
                    if (result) {
                        console.log(result['db_error_msg']);
                    }
                    console.log("Returned data = " + returned_data);
                    console.log("Parse error = " + err);
                }

                if (result) {
                    if (result['error'] == true) {
                        showError($mainAlert, result['error_msg']);
                        console.log(result['db_msg_error']);
                    } else {
                        showSuccess($mainAlert, "S'ha eliminat correctament la secció", 2000);
                        $seccionsTable.find("tbody").find("tr[seccio=" + codi_seccio + "]").remove();
                        hideEditSeccio();
                    }
                }
            },
            error: function(err) {
                showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a provar-ho d'aquí uns segons.");
                console.log(err);
            }
        });
    }
</script>

</body>
</html>