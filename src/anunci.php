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
}?>

<html>
<head>
    <?php include "includes/head.html"; ?>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <script type="text/javascript" src="js/bootstrap-formhelpers-phone.js"></script>
</head>
<body>
<script type="text/javascript">
    function reuploadPhoto() {
        $("#reuploadPhotoBtn").hideBootstrap();
        $("#photoUpload").showBootstrap();
        $("#photoProgressBar").style.width = '0%';
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
            <form id="anunciForm" class="form" role="form">
                <input id="photoName" name="photoName" type="hidden" />
                <div class="row">
                    <div class="col-md-12">
                        <h2>Crear anunci</h2>
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="titolCurt">Titol curt</label>
                            <input type="text" class="form-control" id="titolCurt" name="titolCurt" maxlength="30">
                        </div>
                        <div class="form-group">
                            <label for="telefon">Telèfon</label>
                            <input type="text" class="form-control bfh-phone" id="telefon" name="telefon"  data-format="ddd ddd ddd">

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="descripcio">Descripció</label>
                            <textarea id="descripcio" name="titolCurt" class="form-control" maxlength="150" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dataWeb">Data de publicació</label>
                            <div class='input-group date' id='dataWeb'>
                                <input type='text' id="dataWeb" name="dataWeb" class="form-control" data-date-format="DD/MM/YYYY"/>
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dataNoWeb">Data de despublicació</label>
                            <div class='input-group date' id='dataNoWeb'>
                                <input type='text' id="dataNoWeb" name="dataNoWeb" class="form-control" data-date-format="DD/MM/YYYY"/>
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
                    </div>
            </form>

                    <div class="col-md-8">
                        <div id="photoAlert" class="hidden" role="alert"></div>
                        <div id="photoUpload">
                            <!-- Standar Form -->
                            <form action="" method="post" enctype="multipart/form-data" id="js-upload-form">
                                <label>Selecciona una foto del teu ordenador</label>
                                <div class="form-inline">
                                    <div class="form-group">
                                        <input type="file" name="files[]" id="js-upload-files" multiple>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">Pujar foto</button>
                                </div>
                            </form>

                            <!-- Drop Zone -->
                            <label>O arrosega una foto al recuardre</label>
                            <div class="upload-drop-zone" id="drop-zone">
                                Arrosega i amolla la foto aquí
                            </div>

                            <!-- Progress Bar -->
                            <div class="progress">
                                <div id="photoProgressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12"><label for="foto">Foto</label></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="photo" style="height: 250px; border: 1px dashed #000000;"></div>
                                </div>
                                <div class="col-md-4">
                                    <button id="reuploadPhotoBtn" type="button" class="btn btn-primary hidden" onclick="reuploadPhoto();">Canviar foto</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript" src="js/uploadPhoto.js"></script>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-success pull-right">Crear</button>
                    </div>
                </div>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

</body>
</html>