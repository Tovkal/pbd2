<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 4/12/14
 * Time: 15:04
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}

// Only logged users can view the page
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    header('Location: index.php');
}
?>

<html>
<head>
    <title>Crear anunci</title>
    <?php include "includes/head.html"; ?>
    <?php include "includes/datepicker.html"; ?>
</head>
<body>
<script type="application/javascript">
    function reuploadPhoto() {
        $("#reuploadPhotoBtn").hideBootstrap();
        $("#photoUpload").showBootstrap();
        $("#photoProgressBar").width('0%');
    }
</script>
<div class="wrapper container-fluid">
    <div class="row">
        <div class="col-md-12 border" >
            <a href="index.php" >Inici</a>
        </div>
    </div>
    <div class="row">
        <div id="content" class="col-md-9 border">
            <form id="anunciForm" class="form" role="form" style="margin-bottom: 0;">
                <input id="action" name="action" type="hidden" />
                <input id="idAunci" name="idAnunci" type="hidden" />
                <input id="photoName" name="photoName" type="hidden" />
                <div class="row">
                    <div class="col-md-12">
                        <h2>Crear anunci</h2>
                        <hr>
                        <div id="mainAlert" class="hidden" role="alert"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <span class="help-block">Camps obligatoris.</span>
                        <div class="form-group">
                            <label for="titolCurt" class="control-label">Titol curt*</label>
                            <input type="text" class="form-control obligatori" id="titolCurt" name="titolCurt" maxlength="30" />
                        </div>
                        <div class="form-group">
                            <label for="telefon">Telèfon*</label>
                            <input type="text" class="form-control bfh-phone obligatori" id="telefon" name="telefon" maxlength="9" placeholder="123456789" />

                        </div>
                    </div>
                    <div class="col-md-8">
                        <span class="help-block">Camps opcionals.</span>
                        <div class="form-group">
                            <label for="textAnunci">Descripció</label>
                            <textarea id="textAnunci" name="textAnunci" class="form-control" data-bv-excluded maxlength="150" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dataWeb">Data de publicació*</label>
                            <div class='input-group date' id='dataWeb'>
                                <input type='text' id="dataWebInput" name="dataWeb" class="form-control obligatori" data-date-format="DD/MM/YYYY" placeholder="dd/mm/aaaa" />
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dataNoWeb">Data de despublicació*</label>
                            <div class='input-group date' id='dataNoWeb'>
                                <input type='text' id="dataNoWebInput" name="dataNoWeb" class="form-control obligatori" data-date-format="DD/MM/YYYY" placeholder="dd/mm/aaaa" />
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                            </div>
                        </div>
                        <script type="application/javascript">
                            var $dataWeb = $("#dataWeb");
                            var $dataNoWeb = $("#dataNoWeb");
                            $(function () {
                                var settings = {pickTime: false};
                                $dataWeb.datetimepicker(settings);
                                $dataNoWeb.datetimepicker(settings);
                                $dataWeb.on("dp.change",function (e) {
                                    $('#dataNoWeb').data("DateTimePicker").setMinDate(e.date);
                                });
                                $dataNoWeb.on("dp.change",function (e) {
                                    $dataWeb.data("DateTimePicker").setMaxDate(e.date);
                                });
                            });
                        </script>
                        <div class="form-group">
                            <label for="seccio">Secció*</label>
                            <select id="seccio" name="seccio" class="form-control">
                                <option value="-1">Selecciona una secció</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div id="photoAlert" class="hidden" role="alert">
                        </div>
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
                                    <label for="foto">Foto</label>
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
            <div class="row" style="padding-bottom: 20px;">
                <hr>
                <div class="col-md-12">
                    <button id="actionBtn" class="btn pull-right" onclick="">Crear</button>
                </div>
            </div>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>
<script type="application/javascript">
    var $anunciForm = $('#anunciForm');
    var $mainAlert = $("#mainAlert");
    var $actionBtn = $("#actionBtn");

    // Form inputs
    var $action = $("#action");
    var $idAnunci = $("#idAnunci");
    var $photoName = $("#photoName");
    var $titolCurt = $("#titolCurt");
    var $telefon = $("#telefon");
    var $textAnunci = $("#textAnunci");
    var $dataWeb = $("#dataWebInput");
    var $dataNoWeb = $("#dataNoWebInput");
    var $seccio = $("#seccio");

    $(document).ready(function() {
        var action = getUrlParameter("a");
        $("#action").val(action);

        setupSeccio(action);
    });

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }

    function loadSeccions() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/seccio.php",
            data: "action=fetch",
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($mainAlert, "La resposta del selector de seccions no es correcta");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        showError($mainAlert, result['error_msg']);
                        $actionBtn.disable();
                    } else {
                        $.each(result['opcions'], function (key, value) {
                            $seccio
                                .append($("<option></option>")
                                    .attr("value", key)
                                    .text(value));
                        });
                    }
                }
            },
            error: function(err) {
                showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }

    function setupSeccio(action) {
        loadSeccions();
        if (action == 'crear') {
            $anunciForm.find("h2").text("Crear anunci");
            $actionBtn.attr("onclick", "crearAnunci();").addClass("btn-success").text("Crear");
            $action.val("crear");

        } else if (action == "modificar") {
            $anunciForm.find("h2").text("Modificar anunci");
            showInfo($mainAlert, "Carregant dades. Per favor esperi.");
            $actionBtn.attr("onclick", "modificarAnunci();").addClass("btn-primari").text("Modificar");
            $action.val("modificar");

            $.ajax({
                type: "POST",
                datatype: "json",
                url: "dao/anunci.php",
                data: "action=" + action + "&id=" + getUrlParameter("id"),
                success: function(returned_data) {
                    var result;
                    try {
                        result = JSON.parse(returned_data);
                    } catch (err) {
                        showError($mainAlert, "La resposta del selector de seccions no es correcta");
                        console.log(returned_data);
                        console.log(err);
                    }

                    if (result) {
                        if (result['error'] == true) {
                            showError($mainAlert, result['error_msg']);
                            $actionBtn.disable();
                        } else {
                            showSuccess($mainAlert, 'Dades carregades amb èxit.', 2000);
                            var anunci = result['anunci'];
                            $idAnunci.val(result['id']);
                            $titolCurt.val(anunci['titolCurt']);
                            $telefon.val(anunci['telefon']);
                            $textAnunci.val(getOptionalInput(anunci['textAnunci']));
                            $dataWeb.val(anunci['dataWeb']);
                            $dataNoWeb.val(anunci['dataNoWeb']);
                            $seccio.find("option[value=" + anunci['codi_seccio'] + "]").attr('selected', 'selected');

                            if(anunci['foto']) {
                                $photoName.val(anunci['foto']);
                                $("#photoUpload").hideBootstrap();
                                $("#photo").html("<img src='upload/" + anunci['foto'] + "' style='display:block;margin:auto;height:100%; width:100%;'>");
                                $("#reuploadPhotoBtn").showBootstrap();
                                $("#photoPreview").showBootstrap();
                            }
                        }
                    }
                },
                error: function(err) {
                    showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                    console.log(err);
                }
            })
        }
    }

    function getOptionalInput(string) {
        if (string == "NULL") {
            return "";
        }
        return string;
    }

    function crearAnunci() {
        var data = $("#anunciForm").serialize();
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/anunci.php",
            data: data,
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($mainAlert, "La resposta a la creació de l'anunci no es correcta.");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result['error'] == true) {
                    showError($mainAlert, result['error_msg']);
                } else {
                    showSuccess($mainAlert, "S'ha creat l'anunci correctament", 2500);
                }
            },
            error: function(err) {
                showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }

</script>
</body>
</html>