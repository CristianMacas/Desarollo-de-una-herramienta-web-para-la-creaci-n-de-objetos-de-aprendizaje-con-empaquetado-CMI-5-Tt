<?php

require('uml_json_comparer.php');
require('save_cmi5_as_doctrine_format.php');

try{
    //Obtiene los datos json crudos del request.
    $json = file_get_contents('php://input');

    //Convierte los datos json a un objeto PHP.
    $data = json_decode($json);
    $diagramModel =  json_encode($data->diagramModel, JSON_UNESCAPED_UNICODE);
    
    $course_id=$data->course_id; 
    $nactivity_id=$data->nactivity_id;
    $user_email=$data->user_email;

    $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
    
    $result = mysqli_query($conexion, "select * from model_diagram_success where course_id=".$course_id." and nactivity_id=".$nactivity_id);
    $diagram = mysqli_fetch_array($result);
    //$template = json_decode($diagram['data'], false, 512, JSON_UNESCAPED_UNICODE);    // En la posicion 3 esta el JSON. Lo convierte a objeto.
    $template = json_decode($diagram['data'], JSON_UNESCAPED_UNICODE);    // En la posicion 3 esta el JSON. Lo convierte a objeto.
    
    // Compara los dos json y devuelve la lista con las diferencias.
    $comparer_result = check_uml_diagram($template, $diagramModel);
    
    $result = mysqli_query($conexion, "select * from user where email='".$user_email."'");
    $user = mysqli_fetch_array($result);
    $user_name = (string)$user[4];    

    $result = mysqli_query($conexion, "select * from course where id='".$course_id."'");
    $course = mysqli_fetch_array($result);
    $course_name = (string)$course[1];   

    $result = mysqli_query($conexion, "select * from nactivity where id='".$nactivity_id."'");
    $nactivity = mysqli_fetch_array($result);
    $nactivity_title = (string)$nactivity[2];   

    $conexion->close();
    
    $conection = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
    mysqli_set_charset($conection,"utf8");
    date_default_timezone_set('America/Guayaquil');
    $fecha = new \DateTime(date("Y-m-d H:i:s"));
    $result = mysqli_query($conection, "insert into model_diagram_test values (NULL, '".$course_id."', '".$nactivity_id."', '".$user_name."', '".$diagramModel."', '".$fecha."')");
    $conection->close();
    
    
    /*//if ($data->edit){
        $result = mysqli_query($conexion, "update model_diagram_test set data='".$diagramModel."' where course_id='".$data->course_id."' and nactivity_id='".$data->nactivity_id."'");
    //} else {
    //    $result = mysqli_query($conexion, "insert into model_diagram_success values (NULL, '".$data->course_id."', '".$data->nactivity_id."', '".$diagramModel."', '')");
    //}    
    $conexion->close();*/
    
    //Guarda el resultado en la tabla statement.
    saveStatement(
        $user_name, 
        $user_email, 
        'terminated', 
        '',     // objectType, 
        $nactivity_id, 
        $nactivity_title,     // definition, 
        (sizeof($comparer_result) == 0 ? true : false),
        (sizeof($comparer_result) == 0 ? true : false), 
        '',     // profesor que pone la actividad
        '', 
        $course_name, 
        'XPerienceUML', 
        ''  //$extensions
    );

    echo json_encode(array("message"=>"successful", "status"=>200, "data"=>"OK"));
    http_response_code(200);
}catch (Exception $e){
    http_response_code(500);
}


?>