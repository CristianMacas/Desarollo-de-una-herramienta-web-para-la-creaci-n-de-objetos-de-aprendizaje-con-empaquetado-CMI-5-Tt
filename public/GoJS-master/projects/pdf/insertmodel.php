<?php
$conexion = mysqli_connect('localhost', 'xperienc', 'Zb(nY;w1A62r1R', 'xperienc_uml');
$sql0 = "select * from nactivity ";
$sql = "select * from nactivity na inner join nta nt WHERE (nt.id=na.nta_id) and nt.denomination='Interactiva'";
$sql2 = "select * from course ";
$sql3 = "select * from user ";
$result = mysqli_query($conexion, $sql);
$resultcurse = mysqli_query($conexion, $sql2);
$resuluser = mysqli_query($conexion, $sql3);
$resultact = mysqli_query($conexion, $sql0);
$mostraractividad = mysqli_fetch_array($result);
$mostraruser = mysqli_fetch_array($resuluser);

$mostrarcurso = mysqli_fetch_array($resultcurse);
$curso = $mostrarcurso['id'];

$actividad = isset($_POST['actividad']) ? $_POST['actividad'] : '';
$action = isset($_POST['user']) ? $_POST['user'] : '';
$datajson = isset($_POST['datajson']) ? $_POST['datajson'] : '';

$insertquery = "insert into model_diagram_test(course_id,nactivity_id,action,data) values('$curso','$actividad','$action','$datajson')";
mysqli_query($conexion, $insertquery);

echo "Insertado Satisfactoriamente !!!";
$conn->close();
?>