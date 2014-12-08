<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 8/12/14
 * Time: 10:45
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
    <title>Administració d'usuaris</title>
    <script type="application/javascript" src="js/bootstrap/bootstrap.min.js"></script>
    <?php include "includes/datepicker.html"; ?>
</head>
<body>

<div class="wrapper container-fluid">
    <div class="row column border">
        <div class="col-md-9" >
            <a href="index.php">
                <img src="img/logo.png" class="logo" />
            </a>
        </div>
    </div>
    <div class="row">
        <div id="content" class="col-md-9 border">
            <h3>Administració d'usuaris</h3>
            <hr>
            <div id="mainAlert" class="hidden" role="alert"></div>

            <div id="viewSeccions" class="row">
                <div class="col-md-12">
                    <table id="usuarisTable" class="table table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-3">userID</th>
                            <th class="col-md-4">Nom</th>
                            <th class="col-md-3">Nombre d'anuncis</th>
                            <th class="col-md-2">Emetre factura</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div id="menuColumn" class="col-md-3 border">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFactura" tabindex="-1" role="dialog" aria-labelledby="titolModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="titolModal">Factura del usuari</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="filterForm" class="form" role="form">
                        <input type="hidden" name="id" id="id" value="" />
                        <h5>Filtre</h5>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="radio">
                                    <label class="radio-inline">
                                        <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked> Sense filtre
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">Període determinat de temps
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="dateFilter" class="form-group hidden">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dataWeb">Des de (inclòs)</label>
                                        <div class='input-group date' id='dataWeb'>
                                            <input type='text' id="dataWebInput" name="dataInici" class="form-control obligatori" data-date-format="DD/MM/YYYY" placeholder="dd/mm/aaaa" />
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dataNoWeb">Fins (exclòs)</label>
                                        <div class='input-group date' id='dataNoWeb'>
                                            <input type='text' id="dataNoWebInput" name="dataFi" class="form-control obligatori" data-date-format="DD/MM/YYYY" placeholder="dd/mm/aaaa" />
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                                        </div>
                                    </div>
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
                            <button type="button" class="btn btn-primary pull-right" onclick="veureFacturaAmbFiltre();">Aplicar</button><br>
                        </div>
                    </form>
                    <hr>
                    <div>
                        <table id="facturaTable" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="col-md-3">Titol</th>
                                <th class="col-md-2">Data publicació</th>
                                <th class="col-md-2">Preu secció</th>
                                <th class="col-md-2">Nombre canvis</th>
                                <th class="col-md-1">Total</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script type="application/javascript">
    var $usuarisTable = $("#usuarisTable");
    var $mainAlert = $("#mainAlert");
    var $dateFilter = $("#dateFilter");
    var $facturaTable = $("#facturaTable");
    var $userID = $("#id");

    $(document).ready(function() {
        setupTable();

        $("#inlineRadio1").click(function() {
            $dateFilter.hideBootstrap();
            veureFactura($userID.val());
        });
        $("#inlineRadio2").click(function() {
            $dateFilter.showBootstrap();
            $facturaTable.find("input[type=text]").val("");
        });

    });

    function addRowToUserTable(id, usuari) {
        var $userID = $("<td></td>").text(usuari['userID']).addClass("col-md-3");
        var $nom = $("<td></td>").text(usuari['nom']).addClass("col-md-4");
        var $nombreAnuncis = $("<td></td>").text(usuari['nombreAnuncis']).addClass("col-md-3");
        var $veureFactura = $("<button></button>").attr("type", "button").attr("value", id).addClass("btn").addClass("btn-primary").html('<span class="glyphicon glyphicon-euro"></span>').attr("onclick", "veureFactura(this.value)");
        var $buttons = $("<td></td>").append($veureFactura).addClass("col-md-2");
        var $row = $("<tr></tr>").attr("usuari", id).append($userID).append($nom).append($nombreAnuncis).append($buttons);
        $usuarisTable.append($row);
    }

    function setupTable() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/anunci.php",
            data: "action=fetchAnuncisUsuaris",
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    $usuarisTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        $usuarisTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                        console.log(result['db_msg_error']);
                    } else {
                        $.each(result['usuaris'], function (key, value) {
                            addRowToUserTable(key, value);
                        });
                    }
                }
            },
            error: function(err) {
                $usuarisTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                console.log(err);
            }
        });
    }

    function addRowToFacturaTable(id, anunci) {
        var $titol = $("<td></td>").text(anunci['titolCurt']).addClass("col-md-3");
        var $dataPublicacio = $("<td></td>").text(anunci['data']).addClass("col-md-2");
        var $preuSeccio = $("<td></td>").text(anunci['preuSeccio']).addClass("col-md-2");
        var $nombreCanvis = $("<td></td>").text(anunci['nombreCanvis']).addClass("col-md-2");
        var $total = $("<td></td>").text(anunci['total'] + '€').addClass("col-md-1");
        var $row = $("<tr></tr>").attr("anunci", id).append($titol).append($dataPublicacio).append($preuSeccio).append($nombreCanvis).append($total);
        $facturaTable.append($row);
    }

    function veureFactura(id_usuari) {
        $("#modalFactura").modal("show");
        $userID.val(id_usuari);
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/anunci.php",
            data: "action=facturaUsuari&id=" + id_usuari,
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    $facturaTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        $facturaTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                        console.log(result['db_msg_error']);
                    } else {
                        $facturaTable.find("tbody").remove();
                        $.each(result['anuncis'], function (key, value) {
                            addRowToFacturaTable(key, value);
                        });
                        var $textTotal = $("<td></td>").html("<strong>Total:</strong>").addClass("col-md-2");
                        var $valorTotal = $("<td></td>").text(result['preuTotal'] + '€').addClass("col-md-1");
                        var $row = $("<tr></tr>").append($("<td></td>")).append($("<td></td>")).append($("<td></td>")).append($textTotal).append($valorTotal);
                        $facturaTable.append($row);
                    }
                }
            },
            error: function(err) {
                $facturaTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                console.log(err);
            }
        });
    }

    function veureFacturaAmbFiltre() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/anunci.php",
            data: "action=facturaUsuariFiltrada&" + $("#filterForm").serialize(),
            success: function(returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    $facturaTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        $facturaTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                        console.log(result['db_msg_error']);
                    } else {
                        $facturaTable.find("tbody").remove();
                        $.each(result['anuncis'], function (key, value) {
                            addRowToFacturaTable(key, value);
                        });

                        var $textTotal = $("<td></td>").html("<strong>Total:</strong>").addClass("col-md-2");
                        var $valorTotal = $("<td></td>").text(result['preuTotal'] + '€').addClass("col-md-1");
                        var $row = $("<tr></tr>").append($("<td></td>")).append($("<td></td>")).append($("<td></td>")).append($textTotal).append($valorTotal);
                        $facturaTable.append($row);
                    }
                }
            },
            error: function(err) {
                $facturaTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels usuaris. Intenta actualtizar la pàgina.</td></tr>");
                console.log(err);
            }
        });
    }
</script>

</body>
</html>