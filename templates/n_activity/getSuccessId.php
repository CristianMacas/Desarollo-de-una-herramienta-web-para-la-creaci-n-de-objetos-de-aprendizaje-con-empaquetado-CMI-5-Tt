<?php
	require "conexion.php";
	if (isset($_POST['idActividad']))
	{
		$idActividad = $_POST['idActividad'];
		$sql= "SELECT id FROM model_diagram_success WHERE nactivity_id = '$idActividad'";
		$result=mysqli_query($conn,$sql);
		$count=mysqli_num_rows($result);
		if (mysqli_num_rows($result)>0)
		{

			$mostrar=mysqli_fetch_array($result);
			//{
				echo $mostrar['id'];
			//}
		}
	}
?>