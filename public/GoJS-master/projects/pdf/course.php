<?php
	require "conexion.php";
	$idCourse= $_POST['idCourse'];
	if (isset($idCourse))
	{
		$sql= "SELECT nactivity.id AS id, nactivity.title AS title, nactivity.description as description, nactivity.place, nta.denomination, course.name, nactivity.tecsol AS tecsol
                FROM nactivity 
	                LEFT JOIN nta ON nactivity.nta_id = nta.id 
	                LEFT JOIN model_diagram_success ON model_diagram_success.nactivity_id = nactivity.id 
	                LEFT JOIN course ON model_diagram_success.course_id = course.id
                WHERE course.id = model_diagram_success.course_id AND model_diagram_success.nactivity_id = nactivity.id AND nta.id = nactivity.nta_id AND course.id = '$idCourse' AND model_diagram_success.data!='[]'";
		$result=mysqli_query($conn,$sql);
		$count=mysqli_num_rows($result);
		if (mysqli_num_rows($result)>0)
		{
			echo "<option selected disabled> Seleccione Actividad </option>";
			while($mostrar=mysqli_fetch_array($result))
			{
				echo '<option value='.$mostrar['id'].'>'.$mostrar['title'].'</option>';
			}
		}
		else
		{
		    http_response_code(500); 
		    //echo "<option selected disabled> No hay actividades para este curso </option>";
		    //exit; 
		}
	}
?>