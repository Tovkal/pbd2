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

<div class="wrapper container-fluid">
    <div class="row">
        <div class="col-md-12 border" >
            <a href="index.php" >Inici</a>
        </div>
    </div>
    <div class="row">
        <div id="content" class="col-md-9 border">
            <form class="form" role="form">
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
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12"><label for="foto">Foto</label></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div style="height: 250px; border: 1px dashed #000000;"></div>
                                </div>
                                <div class="col-md-4">
                                    <span class="btn btn-primary btn-file">Pujar<input type="file"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-success pull-right">Crear</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

</body>
</html>