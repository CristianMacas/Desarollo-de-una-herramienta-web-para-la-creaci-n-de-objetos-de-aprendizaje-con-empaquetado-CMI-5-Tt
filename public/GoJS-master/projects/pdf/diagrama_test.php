<?php 
    $conexion = mysqli_connect('localhost', 'root', '', 'uml'); 
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="/dist/img/XperienceUML.png" />
    <link rel="stylesheet" href="./locals/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="/dist/css/adminlte.css" />
    <!--script src="./locals/bootstrap.bundle.min.js"></script-->
    <title>Editor UML</title>
</head>
    
<body>
    <div>
        <section >
            <div class="row"  style="background-color:#f4f6f9">
                <div class="col-md-2" style="color:white;background-color:#4b545c;width:100%">
                    <a href="../../../../#" class="brand-link">
                        <img 
                            src="/dist/img/XperienceUML.png" 
                            alt="Logo" 
                            class="brand-image img-circle elevation-3" 
                            style="opacity: .8;"
                        >
                        <span class="brand-text font-weight-light" style="color:white">
                            <i>XPerienceUML</i>
                        </span>
                    </a>
                </div>
                <div class="col-md-10" style="width:100%">
                    <?php
                        //Aqui se selecciona si se va a agregar o a evaluar el diagrama.
                        $msg = "<h3>Responder Actividad de Modelado de Diagrama</h3>";
                        $buttonText = 'Evaluar Diagrama';
                        $modalType = "#modalCheck";
                        if (isset($_GET["mode"]) && ($_GET["mode"] != 'undefined')){
                            if ($_GET["mode"] == 'add'){
                                $msg = "<h3>Agregar Diagrama para Actividad de Modelado</h3>";
                                $buttonText = 'Agregar Diagrama';
                                $modalType = "#modalInsert";
                                echo '<pre hidden="true" id="modeEdit" value="false"></pre>';
                            }elseif (($_GET["mode"] == 'view') || ($_GET["mode"] == 'edit')){
                                $msg = "<h3>Vista del Diagrama</h3>";
                                $buttonText = ($_GET["mode"] == 'edit') ? 'Guardar cambios' : '';
                                if ($_GET["mode"] == 'edit') {
                                    $modalType = "#modalInsert";
                                    $msg = "<h3>Editar el Diagrama</h3>";
                                    echo '<pre hidden="true" id="modeEdit" value="true"></pre>';
                                }else{
                                    echo '<pre hidden="true" id="modeEdit" value="false"></pre>';
                                }
                                if (isset($_GET["table"]) && ($_GET["table"] != 'undefined')){
                                    $table = $_GET["table"];
                                    if (isset($_GET["id"]) && ($_GET["id"] != 'undefined')){
                                        $id = $_GET["id"];
                                        $conexion = mysqli_connect('localhost', 'root', '', 'uml');
                                        $result = mysqli_query($conexion, "select data from ".$table." where id='".$id."'");
                                        $diagram = mysqli_fetch_array($result);
                                        if (isset($diagram)){
                                            if (sizeof($diagram) > 0){
                                                $data = (string)$diagram[0];   
                                                $conexion->close();
                                                echo '<pre hidden="true" id="dataJsonDiagram">'.$data.'</pre>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo $msg;
                    ?>
                </div>
            </div>
            <div class="container-fluid" style="background-color:#f4f6f9">
                    <?php
                        session_start();
                        $user_by_email = $_SESSION["_sf2_attributes"];
                        if (array_key_exists('_security.last_username', $user_by_email)) {
                            $user_by_email = $_SESSION["_sf2_attributes"]['_security.last_username'];
                            //echo $user_by_email.'<br>';
                        } else {
                            $user_by_email = 'None';
                        }
                        $response_course = json_decode(file_get_contents('http://localhost:80/CMI5/public/index.php/xapi/course'), true);
                        $response_activity = json_decode(file_get_contents('http://localhost:80/CMI5/public/index.php/xapi/modelActivity'), true);
                    ?>
                    <?php if ($user_by_email == 'None') :
                        echo '<h3> Debe iniciar sesión primero</h3>'; ?>
                    <?php else : ?>
                    <?php
                    if ($buttonText == ''){
                        echo '<div class="p-1 w-full" hidden="true">';
                    }else{
                        echo '<div class="p-1 w-full">';
                    }
                    ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row" style="margin-bottom:7px;margin-right:3px">
                                    <?= '<input type="hidden" value=' . $user_by_email . ' id="user">' ?>
                                    <select class="form-control" name="course" id="course" required>
                                        <option disabled selected> Seleccione el curso </option>
                                        <?php foreach ($response_course as $resp) {
                                            echo '<option value=' . $resp['id'] . '>' . $resp['name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="row" style="margin-bottom:7px;margin-right:3px">
                                    <select class="form-control" name="activity" id="activity" onchange="selectDescription()">
                                        <option disabled selected> Seleccione actividad </option>
                                        <?php foreach ($response_activity as $resp) {
                                            echo '<option value=' . $resp['id'] . '>' . substr($resp['title'], 0, 50) . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row" >
                                    <textarea class="form-control" id="description" rows="3" readonly  style="background-color:#ffffba;font-weight:bold">Descripción de la actividad...</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    </div>


    <div id="allSampleContent" class="p-1 w-full"  style="background-color:#f4f6f9">
        <div id="sample">
            <?php 
            // Con esto se ocultan los botones si solo se esta viendo el diagrama.
            if ($buttonText == ''){
                echo '<div id="botonera" hidden="true">';
            }else{
                echo '<div id="botonera" >';
            }
            ?>
                <!-- Button trigger modal -->
                <?php 
                    if ( ! isset($_GET["table"])){
                        echo '<button id="botonIniciar" class="btn btn-primary btn-sm" onclick="lanzar()">';
                        echo '<strong>Iniciar</strong>';
                        echo '</button>';
                    }
                ?>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="<?php echo $modalType ?>">
                    <i class="bi bi-arrow-bar-up"></i>
                    <strong><?php echo $buttonText ?></strong>
                </button>
                <button class="btn btn-info btn-sm" onclick="addNewClass(0, 0)" >
                    <i class="bi bi-window"></i>
                    <strong>Agregar clase</strong>
                </button>
                <button class="btn btn-info btn-sm" onclick="addNewLink(0, 0)">
                    <i class="bi bi-arrow-down-right"></i>
                    <strong>Agregar relación</strong>
                </button>
                <button class="btn btn-info btn-sm" onclick="undoDiagramChanges()">
                    <i class="bi bi-arrow-left-circle"></i>
                    <strong>Deshacer</strong>
                </button>
                <button class="btn btn-info btn-sm" onclick="redoDiagramChanges()">
                    <i class="bi bi-arrow-right-circle"></i>
                    <strong>Rehacer</strong>
                </button>

                <button class="btn btn-warning btn-sm" onclick="clearDiagram()">
                    <i class="bi bi-trash"></i>
                    <strong>Borrar todo</strong>
                </button>
                <?php 
                    if ( ! isset($_GET["table"])){
                        echo '<button class="btn btn-danger btn-sm" onclick="abandonar()">';
                        echo '<strong>Abandonar ejercicio</strong>';
                        echo '</button>';
                    }
                ?>
                </div>
            <div style="width:100%; white-space:nowrap;">
                <div id="myDiagramDiv" style="border: solid 1px black; background-color: rgb(135, 135, 135); height: 600px"></div>
            </div>
        </div>
    </div>



<!-- Modal check-->
<div class="modal fade" id="modalCheck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="modalTitle">Evaluando diagrama...</h5>
                </button>
            </div>
            <div class="modal-body"  id="modalBody">
                <div id="modalResult" ></div>
            </div>
            <div class="modal-footer" style="padding:3px;" id="modalFooter">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="magin:0px;">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal insert-->
<div class="modal fade" id="modalInsert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalInsertHeader">
                <h5 class="modal-title" id="modalInsertTitle">Insertando diagrama...</h5>
                </button>
            </div>
            <div class="modal-body"  id="modalInsertBody">
                <div id="modalInsertResult" ></div>
            </div>
            <div class="modal-footer" style="padding:3px;" id="modalInsertFooter">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="magin:0px;">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="./locals/go.js"></script>
<!--script src="./locals/require.min.js"></script-->
<script src="./uml_diagram.js"></script>
<!--script src="./locals/jquery.slim.min.js"></script-->
<script src="./locals/jquery-3.5.1.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" ></script>

<script type="application/javascript">

    function selectDescription() {
        var descrip = document.getElementById('activity').value;
        var result = <?= json_encode($response_activity); ?>;
        result.forEach(element => {
            if (element['id'] == descrip) {
                document.getElementById('description').value = element['description'];
            }
        });
    }

    // Procesa los datos del diagrama cuando se muestra el modal de evaluar ejercicios.
    $('#modalCheck').on('shown.bs.modal', function () {
        diagram_check(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('user').value
        );
    });

    // Reinicia los textos que se muestran en el modal de evaluar ejercicios.
    $('#modalCheck').on('hide.bs.modal', function () {
        $('#modalTitle').text('Evaluando diagrama...');
        $('#modalResult').text("");
        $('#modalHeader').css("background-color", "white");
        $('#modalBody').css("background-color", "white");
        $('#modalFooter').css("background-color", "white");
    });

    // Procesa los datos del diagrama cuando se muestra el modal de insertar.
    $('#modalInsert').on('shown.bs.modal', function () {
        diagram_insert(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('modeEdit').value
        );
    });

    // Reinicia los textos que se muestran en el modal de insertar.
    $('#modalInsert').on('hide.bs.modal', function () {
        $('#modalInsertTitle').text('Evaluando diagrama...');
        $('#modalInsertResult').text("");
        $('#modalInsertHeader').css("background-color", "white");
        $('#modalInsertBody').css("background-color", "white");
        $('#modalInsertFooter').css("background-color", "white");
    });


    function lanzar(){
        report_verb(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('user').value,
            'initialized'
        );
        report_verb(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('user').value,
            'launched'
        );
    }

    function abandonar(){
        report_verb(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('user').value,
            'abandoned'
        );
        report_verb(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('user').value,
            'waived'
        );
    }

</script>

<?php endif ?>
</body>

</html>
