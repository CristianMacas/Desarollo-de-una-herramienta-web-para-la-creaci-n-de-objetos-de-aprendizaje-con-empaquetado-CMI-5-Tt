<?php

require('uml_json_comparer.php');

try{
    //Obtiene los datos json crudos del request.
    $json = file_get_contents('php://input');

    //Convierte los datos json a un objeto PHP.
    $data = json_decode($json);
    $diagramModel =  json_encode($data->diagramModel, JSON_UNESCAPED_UNICODE);

    $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
    //if ($data->edit){
        $result = mysqli_query($conexion, "update model_diagram_success set data='".$diagramModel."' where course_id='".$data->course_id."' and nactivity_id='".$data->nactivity_id."'");
    //} else {
    //    $result = mysqli_query($conexion, "insert into model_diagram_success values (NULL, '".$data->course_id."', '".$data->nactivity_id."', '".$diagramModel."', '')");
    //}    
    $conexion->close();

    echo json_encode(array("message"=>"successful", "status"=>200, "data"=>"OK"));
    http_response_code(200);
}catch (Exception $e){
    http_response_code(500);
}


?>