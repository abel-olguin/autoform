<?php 

    					require_once('conexion.php');
$id=$_POST["id"];
$nombre_2=$_POST["nombre_2"];
$apellido_paterno_2=$_POST["apellido_paterno_2"];
$apellido_materno_2=$_POST["apellido_materno_2"];

$sql="INSERT INTO segunda(id,nombre_2,apellido_paterno_2,apellido_materno_2)VALUES($id,$nombre_2,$apellido_paterno_2,$apellido_materno_2)";
$result = mysql_query ($query);
if(!$result){
 die('no se pudo insertar');

									echo mysql_error();

									}

									?>