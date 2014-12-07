<?php 

    					require_once('conexion.php');
$id=$_POST["id"];
$nombre=$_POST["nombre"];
$apellido_paterno=$_POST["apellido_paterno"];
$apellido_materno=$_POST["apellido_materno"];

$sql="INSERT INTO primera(id,nombre,apellido_paterno,apellido_materno)VALUES($id,'$nombre','$apellido_paterno','$apellido_materno')";
$result = mysql_query ($sql);
if(!$result){
 die('no se pudo insertar');

									echo mysql_error();

									}

									?>