<?php

require('save_cmi5_as_doctrine_format.php');

try{
    //Obtiene los datos json crudos del request.
    $json = file_get_contents('php://input');

    //Convierte los datos json a un objeto PHP.
    $data = json_decode($json, false, 512, JSON_UNESCAPED_UNICODE);

    $conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');

    $result = mysqli_query($conexion, "select * from user where email='".$data->user_email."'");
    $user = mysqli_fetch_array($result);
    $user_name = (string)$user[4];    

    $result = mysqli_query($conexion, "select * from course where id='".$data->course_id."'");
    $course = mysqli_fetch_array($result);
    $course_name = (string)$course[1];   

    $result = mysqli_query($conexion, "select * from nactivity where id='".$data->nactivity_id."'");
    $nactivity = mysqli_fetch_array($result);
    $nactivity_title = (string)$nactivity[2];   

    $conexion->close();

    //Guarda el resultado en la tabla statement.
    saveStatement(
        $user_name, 
        $data->user_email, 
        $data->verb, 
        '',     // objectType, 
        $data->nactivity_id, 
        $nactivity_title,     // definition, 
        ($data->verb == 'terminated' ? true : false),
        ($data->verb == 'terminated' ? true : false), 
        '',     // profesor que pone la actividad
        '', 
        $course_name, 
        'XPerienceUML', 
        ''  //$extensions
    );
    
    echo json_encode(array("message"=>"successful", "status"=>200, "data"=>$data));
    http_response_code(200);
}catch (Exception $e){
    http_response_code(500);
}


?>