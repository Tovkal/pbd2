<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 7/12/14
 * Time: 17:57
 */
// Start the session
if(!isset($_SESSION)){
    session_start();
}

// Only logged admin users can view the page
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    header('Location: index.php');
}

?>

<html>
<head>
    <?php include "includes/head.html"; ?>
    <title>Els meus anuncis</title>
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
            <h3>Els meus anuncis</h3>
            <hr>
            <div id="mainAlert" class="hidden" role="alert"></div>

            <div id="viewAnuncis" class="row">
                <div class="col-md-12">
                    <table id="anuncisTable" class="table table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-2">Foto</th>
                            <th class="col-md-2">Titol curt</th>
                            <th class="col-md-2">Data publicació</th>
                            <th class="col-md-2">Data despublicació</th>
                            <th class="col-md-2">Seccio</th>
                            <th class="col-md-2"></th>
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

<script type="application/javascript">
var $anuncisTable = $("#anuncisTable");
var $mainAlert = $("#mainAlert");

$(document).ready(function() {
    setupTable();
});

function addRowToTable(id, anunci) {
    if (anunci['foto']) {
        var $foto = $("<td></td>").html("<img src='img/anuncis/" + anunci['foto'] + "' style='width:100px;' />").addClass("col-md-2");
    } else {
        // TODO - tovkal - 07/12/2014 - Foto generica de secció
        var $foto = $("<td></td>").html("//TODO").addClass("col-md-2");
    }
    var $titolCurt = $("<td></td>").text(anunci['titolCurt']).addClass("col-md-2");
    var $dataPub = $("<td></td>").text(anunci['dataWeb']).addClass("col-md-2");
    var $dataNoPub = $("<td></td>").text(anunci['dataNoWeb']).addClass("col-md-2");
    var $seccio = $("<td></td>").text(anunci['titol_seccio']).addClass("col-md-2");
    var $consultBtn = $("<button></button>").attr("type", "button").attr("value", id).addClass("btn").addClass("btn-default").html('<span class="glyphicon glyphicon-eye-open"></span>').attr("onclick", "consultAnunci(this.value)");
    var $editBtn = $("<button></button>").attr("type", "button").attr("value", id).addClass("btn").addClass("btn-primary").html('<span class="glyphicon glyphicon-pencil"></span>').attr("onclick", "editAnunci(this.value)");
    var $deleteBtn = $("<button></button>").attr("type", "button").attr("value", id).addClass("btn").addClass("btn-danger").html('<span class="glyphicon glyphicon-trash"></span>').attr("onclick", "deleteAnunci(this.value)");
    var $buttons = $("<td></td>").append($consultBtn).append($editBtn).append($deleteBtn).addClass("col-md-2");
    var $row = $("<tr></tr>").attr("anunci", id).append($foto).append($titolCurt).append($dataPub).append($dataNoPub).append($seccio).append($buttons);
    $anuncisTable.append($row);
}

function setupTable() {
    $.ajax({
        type: "POST",
        datatype: "json",
        url: "dao/anunci.php",
        data: "action=getList",
        success: function(returned_data) {
            var result;
            try {
                result = JSON.parse(returned_data);
            } catch (err) {
                $anuncisTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels anuncis. Intenta actualtizar la pàgina.</td></tr>");
                console.log(err);
                console.log(returned_data);
            }

            if (result) {
                if (result['error'] == true) {
                    $anuncisTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels anuncis. Intenta actualtizar la pàgina.</td></tr>");
                    console.log(result['db_msg_error']);
                } else {
                    $.each(result['anuncis'], function (key, value) {
                        addRowToTable(key, value);
                    });
                }
            }
        },
        error: function(err) {
            $anuncisTable.append("<tr class='danger'><td colspan='4' style='text-align:center;'>No s'ha pogut carregar la informació dels anuncis. Intenta actualtizar la pàgina.</td></tr>");
            console.log(err);
        }
    });
}

function consultAnunci(idAnunci) {
    window.location.href = "anunci.php?a=consultar&id=" + idAnunci;
}

function editAnunci(idAnunci) {
    window.location.href = "anunci.php?a=modificar&id=" + idAnunci;
}

function deleteAnunci(idAnunci) {
    $.ajax({
        type: "POST",
        datatype: "json",
        url: "dao/anunci.php",
        data: "action=eliminar&idAnunci=" + idAnunci,
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
                    showSuccess($mainAlert, "S'ha eliminat correctament l'anunci", 2000);
                    $anuncisTable.find("tbody").find("tr[anunci=" + idAnunci + "]").remove();
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