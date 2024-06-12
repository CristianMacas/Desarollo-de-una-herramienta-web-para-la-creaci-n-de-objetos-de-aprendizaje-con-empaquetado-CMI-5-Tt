<?php 
    require "conexion.php";
    $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml'); 
    $ruta = 'http://'.$_SERVER['SERVER_NAME'].'/public/index.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/public/dist/img/XperienceUML.png" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    

</head>
<body>
    <div>
        <section >
            <div class="row"  style="background-color:#f4f6f9; margin-left: 5px;">
                <div class="col-md-2" style="color:white;background-color:#4b545c;width:100%; text-align: center;display: grid;place-items: center;">
                    <a href="<?php echo $ruta;?>" class="brand-link">
						<img src="/public/dist/img/XperienceUML.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
						<span class="brand-text font-weight-light" style="color:white">
							<i>XPerienceUML</i>
						</span>
					</a>
                </div>
                <div class="col-md-10" style="width:100%">
                    <?php
                        //Aqui se selecciona si se va a agregar o a evauar el diagrama.
                        $msg = "<div class='container'>
                                  <div class='row justify-content-between'>
                                    <div class='col'>
                                      <div>1. Seleccione el Curso.</div>
                                   <div>2. Seleccione la Actividad.</div>
                                   <div>3. Clic en <button> <strong>Iniciar</strong> </button> para crear la soluci&oacute;n</div>
                                    </div>
                                    <div class='col'>
                                      <div>Nota: Usar clic derecho para m&aacute;s opciones.</div>
                                   <div>Se recomienda fijarse bien en la conexi&oacute;n de las relaciones entre clases</div>
                                    </div>
                                    
                                  </div>
                                </div>";
                        $buttonText = 'Evaluar Soluci&oacute;n';
                        $modalType = "buttonCheck";
                        if (isset($_GET["mode"]) && ($_GET["mode"] != 'undefined')){
                            if ($_GET["mode"] == 'add')
                            {
                                $buttonText = 'Agregar Soluci&oacute;n';
                                $modalType = "buttonInsert";
                                echo '<pre hidden="true" id="modeEdit" value="false"></pre>';
                            }
                            elseif (($_GET["mode"] == 'view') || ($_GET["mode"] == 'edit'))
                            {
                                $msg = "<h3>Vista de la Soluci&oacute;n</h3>";
                                $buttonText = ($_GET["mode"] == 'edit') ? 'Guardar cambios' : '';
                                if ($_GET["mode"] == 'edit') 
                                {
                                    $modalType = "buttonInsert";
                                    $msg = "<h3>Crear Soluci&oacute;n</h3>";
                                    echo '<pre hidden="true" id="modeEdit" value="true"></pre>
                                    ';
                                }
                                else
                                {
                                    
                                    echo '<pre hidden="true" id="modeEdit" value="false"></pre>';
                                }
                                if (isset($_GET["table"]) && ($_GET["table"] != 'undefined'))
                                {
                                    $table = $_GET["table"];
                                    if (isset($_GET["id"]) && ($_GET["id"] != 'undefined'))
                                    {
                                        $id = $_GET["id"];
                                        $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
                                        $result = mysqli_query($conexion, "select data from ".$table." where id='".$id."'");
                                        $diagram = mysqli_fetch_array($result);
                                        if (isset($diagram))
                                        {
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
                        
                    ?>
                      <div class="col align-self-end" style="margin-top: 20px;margin-bottom: 20px;">
                                      <button class="btn btn-info btn-sm" id="Instruction" data-toggle="modal" data-target="#modalInstruction" >Instrucciones para el uso de la herramienta</button>
                                      <button class="btn btn-info btn-sm" id="Ayuda" data-toggle="modal" data-target="#modalAyuda" style=" float: right; margin-left: 5px;" disabled>Ayuda t&eacute;cnica</button>       
                                      <button class="btn btn-info btn-sm" id="verDescription" data-toggle="modal" data-target="#modalInit" display=none style=" float: right; margin-left: 5px;" disabled>Ver descipci&oacute;n completa...</button>
                      </div>        
                </div>
            </div>
            <div class="container-fluid" style="background-color:#f4f6f9">
                <?php
                    $response_activity = json_decode(file_get_contents($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/public/index.php/xapi/modelActivity'), true);
                ?>
                <div class="p-1 w-full">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row" style="margin-bottom:7px;margin-right:3px">
                                <?php 
                                    $user_by_email='None';
                                    if (!isset($_GET["id"]))  {
                                        $idUser = $_GET['idUser'];
                                        $sql = "SELECT * FROM user WHERE id = ".$idUser;
                                        $result = mysqli_query($conn,$sql);
                                        $user_by_email=mysqli_fetch_array($result)['email'];
                                    }
                                    
                                    echo '<input type="hidden" value="' . $user_by_email . '" id="user">';
                                ?>
                                    
                                <select class="form-control" name="course" id="course" required >
                                    <?php 
                                        echo '<option disabled selected> Seleccione el curso </option>';
                                        if (!isset($_GET["id"]))
                                        {   
                                            $sql = 'SELECT course.* FROM course LEFT JOIN course_user ON course_user.course_id = course.id
                                                    WHERE course.id = course_user.course_id AND course_user.user_id = '.$idUser;
                                            $result = mysqli_query($conn,$sql);
                                            while ($mostrar=mysqli_fetch_array($result)){
                                                echo '<option value='.$mostrar['id'].'>'.$mostrar['name'].'</option>';
                                            }
                                        }
                                        else
                                        {
                                            $id = $_GET["id"];
                                            $tabla = $_GET["table"];
                                            $sql = "SELECT course.id, course.name, $tabla.data, nactivity.title AS title, nactivity.id AS idactivity, nactivity.description AS description, nactivity.tecsol AS tecsol
                                                        FROM course 
	                                                        LEFT JOIN $tabla ON $tabla.course_id = course.id 
	                                                        LEFT JOIN nactivity ON $tabla.nactivity_id = nactivity.id
                                                        WHERE $tabla.course_id = course.id  AND nactivity.id = $tabla.nactivity_id AND $tabla.id = ".$id;
                                            $result = mysqli_query($conn,$sql);
                                            $mostrar=mysqli_fetch_array($result);
                                            echo '<option value="'.$mostrar['id'].'" selected>'.$mostrar['name'].'</option>';
                                            $description = $mostrar['description'];
                                            $tecsol = $mostrar['tecsol'];
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="row" style="margin-bottom:7px;margin-right:3px">
                                <?php 
                                    if (isset($_GET["id"]))
                                    {
                                        echo '<select class="form-control" name="activity" id="activity" disabled>';
                                        echo '<option value="'.$mostrar['idactivity'].'" selected>'.$mostrar['title'].'</option>';
                                        echo '</select>';
                                    }
                                    else {
                                        echo '<select class="form-control" name="activity" id="activity" onchange="selectDescription()" onclick="getCourse()" >';
                                        echo '<option disabled selected> No hay actividad seleccionada</option>';
                                        echo '</select>';
                                    }
                                ?>  
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row" >  
                                <?php  
                                    if (isset($_GET["id"]))
                                    {
                                        echo '<textarea class="form-control" id="description" rows="2" readonly  style="background-color:#ffffba;font-weight:bold;resize:none;overflow: hidden;height:76px;margin-right:15px;">'.$mostrar['description'].'</textarea>';
                                    }
                                    else 
                                    {
                                        echo '<textarea class="form-control" id="description" rows="2" readonly  style="background-color:#ffffba;font-weight:bold;border: none;height: 53px;height:76px;margin-right:15px;">Descripci&oacute;n de la actividad...</textarea>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <div style="float: right;" class="w-full">
                <?php 
                    if ($user_by_email!="None")
                    {
                        echo 'Usuario: '.$user_by_email;
                    }
                ?>
            </div>
        </section>
    </div>

    <div id="allSampleContent" class="p-1 w-full"  style="background-color:#f4f6f9">
        <div id="sample">
            <div id="botonera" >
                <?php $idUser = $_GET['idUser'];?>
                 <input type="hidden" name="idUser" id="idUser" value="<?php print $idUser;?>">
                <!-- Button trigger modal -->
                <?php 
                    if (!isset($_GET["table"])) {
                        echo '<button name="botonIniciar" id="botonIniciar" class="btn btn-primary btn-sm" disabled onclick="lanzar(this.name,\'AgregarRel,AgregarClases,Deshacer,Rehacer,Borrar,Abandonar,verDescription,' . $modalType . ',Ayuda\')">';
                        echo '<strong>Iniciar</strong>';
                        echo '</button>';
                    }
                    if ($buttonText!='')
                    {
                        $a='';
                        if (!isset($_GET["id"])) $a="disabled";
                        echo '<button type="button" class="btn btn-success btn-sm" name="'.$modalType.'" id="'.$modalType.'" style="margin-left:4px" '.$a.'>';
                        echo '<i class="bi bi-arrow-bar-up"></i>';
                        echo '<strong>'.$buttonText.'</strong>';
                        echo '</button>';

                        echo '<button name = "AgregarClases" id = "AgregarClases" class="btn btn-info btn-sm" onclick="addNewClass(0, 0)" '.$a.' style="margin-left:4px">';
                        echo '<i class="bi bi-window"></i>';
                        echo '<strong>Agregar clase</strong>';
                        echo '</button>';

                        echo '<button name = "AgregarRel" id = "AgregarRel" class="btn btn-info btn-sm" onclick="addNewLink(0, 0)" '.$a. ' style="margin-left:4px">';
                        echo '<i  class="bi bi-arrow-down-right"></i>';
                        echo '<strong>Agregar relaci&oacute;n</strong>';
                        echo '</button>';

                        echo '<button name = "Deshacer" id = "Deshacer" class="btn btn-info btn-sm" onclick="undoDiagramChanges()" '.$a.' style="margin-left:4px">';
                        echo '<i class="bi bi-arrow-left-circle"></i>';
                        echo '<strong>Deshacer</strong>';
                        echo '</button>';

                        echo '<button name = "Rehacer" id = "Rehacer" class="btn btn-info btn-sm" onclick="redoDiagramChanges()" '.$a.' style="margin-left:4px">';
                        echo '<i class="bi bi-arrow-right-circle"></i>';
                        echo '<strong>Rehacer</strong';
                        echo '</button>';

                        echo '<button name = "Borrar" id = "Borrar" class="btn btn-warning btn-sm" onclick="clearDiagram()" '.$a.' style="margin-left:4px">';
                        echo '<i class="bi bi-trash"></i>';
                        echo '<strong>Borrar todo</strong>';
                        echo '</button>';
                        
                    } 
                    if ( ! isset($_GET["table"])){
                        if (!isset($_GET["id"])) {$a= "disabled";}  else {$a='';};
                        echo '<button name="Abandonar" id="Abandonar" class="btn btn-danger btn-sm" onclick="abandonar()" '.$a.' style="margin-left:4px">';
                        echo '<strong>Abandonar ejercicio</strong>';
                        echo '</button>';
                    }
                ?>
                <?php 
                    if (isset($_GET["idUser"]))
                        { $salida= $ruta.'/model/diagram/test/'.$idUser.'/user'; }
                    else
                        {   
                            if (isset($_GET["course"])) 
                            { 
                                $idcurso = $_GET["course"];
                                $salida= $ruta.'/n/activity/'.$idcurso.'/course'; 
                                
                            }
                            else
                            {
                                $salida= $ruta.'/model/diagram/success';
                                  
                            }
                        }
                    
                    ?>
                <input type="hidden" name="salida" value="<?php echo $salida;?>">    
                
                <button  class="btn btn-danger btn-sm" onclick="mostraConfSalir()"> Salir </button>
                
            </div>

            <div style="width:100%; white-space:nowrap;">
                <div id="myDiagramDiv" style="border: solid 1px black; background-color: rgb(135, 135, 135); height: 600px">
                </div>
            </div>
        </div>
    </div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>The <strong>show.bs.modal</strong> event occurs when the modal is about to be shown.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div> 

  <!-- Modal Init-->
<div class="modal fade" id="modalInit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" id="modalInitHeader">
        <h5 class="modal-title" id="modalInitTitle">Descripci&oacute;n completa de la actividad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalInitBody">
         <div id="modalInitResult" ><?php 
             if (isset($description))  echo $description; ?></div>
      </div>
      <div class="modal-footer" id="modalInsertFooter">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Ayuda-->
<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" id="modalAyudaHeader">
        <h5 class="modal-title" id="modalAyudaTitle">Ayuda mediante la Soluci&oacute;n T&eacute;cnica</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalAyudaBody">
         <div id="modalAyudaResult" ><?php if (isset($tecsol)) {$contenido = nl2br($tecsol); echo $contenido;} else { echo 'test';} ?></div>
      </div>
      <div class="modal-footer" id="modelAyudaFooter">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Instrucciones-->
<div class="modal fade" id="modalInstruction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" id="modalInstructionHeader">
        <h5 class="modal-title" id="modalInstructionTitle">Instrucciones para la soluci&oacute;n de una actividad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalInstructionBody">
         <div id="modalInstructionResult" >
            <p>Instrucciones :</p>
            <ol>
            <li>Seleccione el Curso.</li>
            <li>Seleccione la Actividad.</li>
            <li>Haga clic en <button> <strong>Iniciar</strong> </button> para crear la soluci&oacute;n.</li>
            <li>Seleccione los botones <button> <strong> Agregar clase</strong> </button> y/o <button> <strong> Agregar relaci&oacute;n</strong> </button> , o hacer click derecho para Agregar clase y/o Agregar relaci&oacute;n, e ir construyendo la soluci&oacute;n.</li>
            <li>Haga clic derecho sobre el t&iacute;tulo de la clase para opciones adicionales, como agregar o eliminar atributos y m&eacute;todos.</li>
            <li>Use clic derecho en 'Tipo' para modificar el tipo de atributo o m&eacute;todo.</li>
            <li>Con clic derecho en el nombre del atributo o m&eacute;todo, puede seleccionar si es p&uacute;blico o privado.</li>
            <li>Para establecer relaciones, conecte los puntos correspondientes entre clases. Haga clic derecho sobre el nombre de la relaci&oacute;n para seleccionar el tipo deseado de entre las opciones disponibles.</li>
            <li>Una vez conformada la solucion de clic en <?php echo $buttonText;?>.</li>
            <li>Una vez terminado el ejercicio de clic en Salir.</li>
            </ol>
            <p>Nota: Se recomienda fijarse bien en la conexi&oacute;n de las relaciones entre clases</div>
            </p>
         </div>
      </div>
      <div class="modal-footer" id="modalInstructionFooter">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal check-->
<div class="modal fade" id="modalCheck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                <h5 class="modal-title" id="modalInsertTitle">Insertando soluci&oacute;n...</h5>
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

<!-- Modal salir-->
<div class="modal" id="confirmModalSalir" name="confirmModalSalir" style="display:none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar acción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mensaje">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  onclick="ocultarNoSalir()">No</button>
                <button type="button" class="btn btn-danger"  onclick="ocultarConfSalir()">Si</button>
            </div>
        </div>
    </div>
</div>
 
<script src="./locals/go.js"></script>
<script src="./locals/require.min.js"></script>
<!--script src="./uml_diagram.js"></script-->
<script src="./locals/jquery.slim.min.js"></script>
<script src="./locals/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="uml_diagram.js"></script>

<script type="text/javascript">

    $("#buttonInsert").click(function(){
        $("#modalInsert").modal("show");
        
    });
        
    $("#modalInsert").on('show.bs.modal', function(){
        diagram_insert(
            document.getElementById('course').value, 
            document.getElementById('activity').value,
            document.getElementById('modeEdit').value
            );
    });
    

    $("#buttonCheck").click(function(){
        $("#modalCheck").modal("show");
    });
    
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
        $('#modalTitle').text('Evaluando soluci&oacute;n...');
        $('#modalResult').text("");
        $('#modalHeader').css("background-color", "white");
        $('#modalBody').css("background-color", "white");
        $('#modalFooter').css("background-color", "white");
    });
    

    $(function(){
        $('#course').on('change',function(){
            document.getElementById('activity').disabled=false;
            var idCourse=document.getElementById('course').value;
            $.ajax({
                type:'POST',
                url:"course.php",
                data:'idCourse='+idCourse,
                success:function(data){
                    $("#activity option").remove();
                    $("#activity").append(data);
                },
                error:function(){
                    $("#activity option").remove();
                    document.getElementById('activity').disabled=true;
                    document.getElementById('botonIniciar').disabled=true;
                    document.getElementById('Ayuda').disabled=true;
                    document.getElementById('verDescription').disabled=true;
                },
            });
        
            return false;
        });  
    });

    function selectDescription() {
        var descrip = document.getElementById('activity').value;
        $.ajax({
            type:'POST',
            url:"actividad.php",
            data:'idActividad='+descrip,
            success:function(data){
                var arr = data.split("|");
                document.getElementById('description').value=arr[0];
                $('#modalInitResult').text(arr[0]);
                $('#modalAyudaResult').text(arr[1]);
            }
        });
    }

    $('#modalInit').on('shown.bs.modal', function () {
        var descrip = document.getElementById('activity').value;
        var result = <?= json_encode($response_activity); ?>;
        result.forEach(element => {
            if (element['id'] == descrip) {
                document.getElementById('description').value = element['description'];
                $('#modalInitResult').text(element['description']); 
            }
        });
    });

    // Reinicia los textos que se muestran en el modal de evaluar ejercicios.
    $('#modalAyuda').on('hide.bs.modal', function () {
        $('#modalAyudaTitle').text('Ayuda mediante la Soluci&oacute;n... T&eacute;cnica...');
        $('#modalAyudaHeader').css("background-color", "white");
        $('#modalAyudaBody').css("background-color", "white");
        $('#modalAyudaFooter').css("background-color", "white");
    });
    

    // Reinicia los textos que se muestran en el modal de insertar.
    $('#modalInsert').on('hide.bs.modal', function () {
        $('#modalInsertTitle').text('Insertando soluci&oacute;n...');
        $('#modalInsertResult').text("");
        $('#modalInsertHeader').css("background-color", "white");
        $('#modalInsertBody').css("background-color", "white");
        $('#modalInsertFooter').css("background-color", "white");
    });

    $('#modalInit').on('shown.bs.modal', function () {
        var descrip = document.getElementById('activity').value;
        var result = <?php echo json_encode($response_activity); ?>;
        result.forEach(element => {
            if (element['id'] == descrip) {
                document.getElementById('description').value = element['description'];
                $('#modalInitResult').text(element['description']); 
            }
        });
    });

    // Reinicia los textos que se muestran en el modal de insertar.
    $('#modalInit').on('hide.bs.modal', function () {
        $('#modalInitHeader').css("background-color", "white");
        $('#modalInitBody').css("background-color", "white");
        $('#modalInitFooter').css("background-color", "white");
    });

    function activarBotones(name,nombreBotones){
        var partesBotones = nombreBotones.split(",");
        document.getElementById(name).disabled = true;
	    for(i=0;i<partesBotones.length;i++){
		    var boton = document.getElementById(partesBotones[i]);
		    if(boton.name == name) boton.disabled = true;
		    else boton.disabled = false;
	    }
    }

    function activarBotones2(nombreBotones){
        var partesBotones = nombreBotones.split(",");
	    for(i=0;i<partesBotones.length;i++){
		    var boton = document.getElementById(partesBotones[i]);
		    boton.disabled = false;
	    };
    }

    function lanzar(name,nombreBotones){
        activarBotones(name,nombreBotones);
        var combo = document.getElementById("activity");
        var selected = combo.options[combo.selectedIndex].text;
        var txt = "Usted va a responder la actividad: "+selected;
        alert(txt);
        var c = document.getElementById('course').value;
        var a = document.getElementById('activity').value;
        var u = document.getElementById('user').value;
        report_verb(
            c, 
            a,
            u,
            'initialized'
        );
        report_verb(
            c, 
            a,
            u,
            'launched'
        );
        document.getElementById('course').disabled=true;
        document.getElementById('activity').disabled=true;
        
    }
    
    function abandon()
    {
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

    function abandonar(){
        abandon();
        var home =  window.location.hostname;
        var port =  window.location.port;
        window.location.href = 'http://'+home+'/public/GoJS-master/projects/pdf/diagrama.php?idUser='+document.getElementById('idUser').value;
    }
    

    function cambiarOpciones(){
       document.getElementById('activity').disabled=false;
    }

    function getCourse()
    { 
        document.getElementById('botonIniciar').disabled=false;
        document.getElementById('Ayuda').disabled=false;
        document.getElementById('verDescription').disabled=false;
    }


    function mostraConfSalir() {
        
        var div = document.getElementById("confirmModalSalir");
        const mensajeElement = document.getElementById('mensaje');
        const mensaje = "¿Esta seguro de que desea salir?";

        mensajeElement.textContent = mensaje;
        div.style.display = "block";
      }
      
    function ocultarConfSalir() 
    {
        var div = document.getElementById("confirmModalSalir");
        //alert("ver boton");
        var boton = document.querySelector("#Abandonar");
        //alert(boton);
        if (boton!==null) 
        {
            //alert("boton presente");
            var aba = document.getElementById("Abandonar");
            if (aba.disabled == false)
            {
                //alert("boton activo");
                abandon();
            }
        } 
        div.style.display = "none";
        window.history.back();
    }
    function ocultarSalir() {
        var div = document.getElementById("confirmModalSalir");
        div.style.display = "none";
        window.history.back();
      }
      
    function ocultarNoSalir() {
        var div = document.getElementById("confirmModalSalir");
        div.style.display = "none";
        //window.history.back();
      }

</script>

</body>
</html>
