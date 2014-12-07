<?php 
$conexion= mysql_connect("localhost","pruebas","pruebas");
if(!$conexion){
die('No se ha podido conectar a la BD: ' . mysql_error());

					}

					mysql_query('SET NAMES \'utf8\'');

					mysql_select_db('baseprueba',$conexion);

					?>