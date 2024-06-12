<?php
	require "conexion.php";
	$nactivity_id = $_POST['nactivity_id'];

	$sql= "SELECT place AS mensaje
            FROM nactivity 
            WHERE id = '$nactivity_id'";
	$result=mysqli_query($conn,$sql);
	$count=mysqli_num_rows($result);
	if (mysqli_num_rows($result)>0)
	{
        $mostrar=mysqli_fetch_array($result);
		echo $mostrar['mensaje'];
	}
?>