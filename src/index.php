<?php
// Start the session
if(!isset($_SESSION)) {
    session_start();
}
?>

<html>
<head>
    <?php include("includes/head.html"); ?>
    <title>Inici - Ultima Hora de Mallorca - Clasificats</title>
    <?php include("includes/datepicker.html") ?>
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
    <div class="row column">
        <div id="content" class="col-md-9 border">
            <div id="mainAlert" class="hidden" role="alert"></div>
            <div id="filters">
                <form id="filterForm" role="form">
                    <h5>Filtra la cerca d'anuncis:</h5>
                    <div class="row">
                        <div id="filtreSeccio" class="col-md-5">
                            <div class="form-group">
                                <label for="seccio">Secció a mostrar</label>
                                <select id="seccio" name="seccio" class="form-control">
                                    <option value="-1">Totes</option>
                                </select>
                            </div>
                        </div>
                        <div id="filtreDates" class="col-md-7">
                            <span id="helpBlock" class="help-block">Deixa els camps en blanc per no filtrar per data.</span>
                            <div class="form-group">
                                <label for="dataWeb">Desde (inclòs)</label>
                                <div class='input-group date' id='dataWeb'>
                                    <input type='text' id="dataWebInput" name="dataWeb" class="form-control obligatori" data-date-format="DD/MM/YYYY" placeholder="dd/mm/aaaa" />
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dataNoWeb">Fins (inclòs)</label>
                                <div class='input-group date' id='dataNoWeb'>
                                    <input type='text' id="dataNoWebInput" name="dataNoWeb" class="form-control obligatori" data-date-format="DD/MM/YYYY" placeholder="dd/mm/aaaa" />
                                <span class="input-group-addon">
						            <span class="glyphicon glyphicon-calendar"></span>
					            </span>
                                </div>
                            </div>
                            <script type="application/javascript">
                                var $dataWeb = $("#dataWebInput");
                                var $dataNoWeb = $("#dataNoWebInput");
                                $(function () {
                                    var settings = {pickTime: false, date: "glyphicon glyphicon-calendar", minDate: getTodaysDate()};
                                    $dataWeb.datetimepicker(settings);
                                    $dataNoWeb.datetimepicker(settings);
                                    $dataWeb.on("dp.change",function (e) {
                                        $dataNoWeb.data("DateTimePicker").setMinDate(e.date);
                                    });
                                    $dataNoWeb.on("dp.change",function (e) {
                                        $dataWeb.data("DateTimePicker").setMaxDate(e.date);
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 pull-right">
                            <button type="button" class="btn btn-primary pull-right" onclick="filterSearch();" >Aplicar</button>
                            <button type="button" class="btn btn-primary pull-right" onclick="getAnuncis();" style="margin-right: 5px;" >Borrar tots els filtres</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="anuncisContainer" style="padding: 5px;"></div>
        </div>
        <div id="menuColumn" class="col-md-3 border column">
            <?php include 'menu.php';?>
        </div>
    </div>
</div>

<script type="application/javascript">

    var $anuncisContainer = $("#anuncisContainer");
    var $mainAlert = $("#mainAlert");

    $(document).ready(function() {
        getAnuncis();
        getSeccions();
    });

    function getAnuncis() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/anunci.php",
            data: "action=anuncisAmbSeccions",
            success: function (returned_data) {
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($mainAlert, "La resposta obtinguda del servidor no es correcta. Torni a intentar-ho ens uns instants.");
                    console.log(returned_data);
                    console.log(err);
                    $anuncisContainer.html(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        showError($mainAlert, result['error_msg']);
                        console.log(result['db_msg_error']);
                    } else {
                        $anuncisContainer.empty();

                        if (result['llistaAnuncis'].length == 0) {
                            showError($mainAlert, 'No hi ha anuncis a la base de dades per aquesta cerca');
                        } else {
                            $.each(result['llistaAnuncis'], function (key, val) {
                                $anuncisContainer.append(crearSeccioAnuncis(key, val));
                            });
                        }
                    }
                }
            },
            error: function (err) {
                showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }

    function crearSeccioAnuncis(key, result) {
        return $("<div></div>").append(crearCapçaleraSeccio(key, result['codi_seccio'])).append(crearLlistaAnuncis(result['anuncis']));
    }

    function crearCapçaleraSeccio(titol_seccio, codi_seccio) {
        var $titolCapçalera = $("<h3></h3>").text(titol_seccio).addClass('titol-capçalera-seccio');
        return $("<div></div>").attr("id", "seccio" + codi_seccio).addClass("row").addClass("border").append($titolCapçalera);
    }

    function crearLlistaAnuncis(anuncis) {
        var index = crearIndex(anuncis);
        var $resultat = $("<div></div>").addClass("row");
        var length = getJSONLength(anuncis);
        for (var i = 0; i < length; i = i + 4) {
            var $row = $("<div></div>").append(crearAnunci(anuncis[index[i]]));
            if (i + 1 < length) {
              $row.append(crearAnunci(anuncis[index[i + 1]]));
            }
            if (i + 2 < length) {
                $row.append(crearAnunci(anuncis[index[i + 2]]));
            }
            if (i + 3 < length) {
                $row.append(crearAnunci(anuncis[index[i + 3]]));
            }
            $resultat.append($row);
        }
        return $resultat;
    }

    function crearAnunci(anunci) {
        return $("<div></div>").addClass('col-md-3').addClass('text-center').addClass('column-without-side-padding').addClass('recuadre-anunci').append(createFoto(anunci['foto'])).append(createTitol(anunci['titolCurt'])).append(createInfoButton(anunci['id']));
    }

    function createFoto(foto) {
        return $("<img />").attr("src", foto).addClass('thumbnail-anunci');
    }

    function createTitol(titol) {
        return $('<p></p>').text(titol);
    }

    function createInfoButton(id) {
        var $span = $("<span></span>").addClass('glyphicon').addClass('glyphicon-info-sign');
        return $('<button></button>').attr('id', 'createSeccioBtn').attr('type', 'button')
            .addClass('btn').addClass('btn-primary').addClass('pull-right').addClass('anunci-info-button').attr('onclick', 'gotoConsultaAnunci(' + id + ')')
            .append($span);
    }

    function gotoConsultaAnunci(id) {
        window.location.href = "anunci.php?a=consultar&id=" + id;
    }

    function getJSONLength(json) {
        var key, count = 0;
        for(key in json) {
            if(json.hasOwnProperty(key)) {
                count++;
            }
        }
        return count;
    }

    function crearIndex(json) {
        var index = [];

        // build the index
        for (var x in json) {
            index.push(x);
        }

        // sort the index
        index.sort(function (a, b) {
            return a == b ? 0 : (a > b ? 1 : -1);
        });

        return index;
    }

    function getSeccions() {
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
                    scrollToTop();
                    showError($mainAlert, "La resposta del selector de seccions no es correcta");
                    console.log(err);
                    console.log(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        scrollToTop();
                        showError($mainAlert, result['error_msg']);
                        console.log(result['db_msg_error']);
                        $actionBtn.disable();
                    } else {
                        $.each(result['opcions'], function (key, value) {
                            $("#seccio")
                                .append($("<option></option>")
                                    .attr("value", value)
                                    .text(key));
                        });
                    }
                }
            },
            error: function(err) {
                scrollToTop();
                showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }

    function filterSearch() {
        $.ajax({
            type: "POST",
            datatype: "json",
            url: "dao/anunci.php",
            data: "action=anuncisFiltrats&" + $("#filterForm").serialize(),
            success: function (returned_data) {console.log(returned_data);
                var result;
                try {
                    result = JSON.parse(returned_data);
                } catch (err) {
                    showError($mainAlert, "La resposta obtinguda del servidor no es correcta. Torni a intentar-ho ens uns instants.");
                    console.log(returned_data);
                    console.log(err);
                    $anuncisContainer.html(returned_data);
                }

                if (result) {
                    if (result['error'] == true) {
                        showError($mainAlert, result['error_msg']);
                        console.log(result['db_msg_error']);
                    } else {
                        $anuncisContainer.empty();

                        if (result['llistaAnuncis'].length == 0) {
                            showError($mainAlert, 'No hi ha anuncis a la base de dades per aquesta cerca');
                        } else {
                            $.each(result['llistaAnuncis'], function (key, val) {
                                $anuncisContainer.append(crearSeccioAnuncis(key, val));
                                console.log("e");
                            });
                        }

                    }
                }
            },
            error: function (err) {
                showError($mainAlert, "No s'ha pogut contactar amb el servidor. Torna a intentar-ho en uns segons.");
                console.log(err);
            }
        });
    }
</script>

</body>
</html>