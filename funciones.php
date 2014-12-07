<?php
$user="pruebas";
$pass="pruebas";
$db="baseprueba";

$conexion = mysql_connect("localhost" , "pruebas" , "pruebas");
if (!$conexion)
  {
    die("No se ha podido conectar a la BD: " . mysql_error());
  }


mysql_query('SET NAMES \'utf8\'');

?>

<?php
$sql = "SHOW TABLES FROM baseprueba";
$resultado = mysql_query($sql);
mysql_select_db("baseprueba",$conexion);
if (!$resultado) {
    echo "Error de BD, no se pudieron listar las tablas\n";
    echo 'Error MySQL: ' . mysql_error();
    exit;
}

while ($fila = mysql_fetch_row($resultado)) {
/*crear carpetas y archivos*/
		

    	$rutaForms=$fila[0]."/templates/";
    	$rutaFunciones=$fila[0]."/functions/";

    	$rutaForm=$rutaForms."form.php";
    	$rutaFuncion=$rutaFunciones."result.php";

    	if(crearDirectorios($fila[0])){
    		crearArchivos($rutaForms);
    		crearConexion($user,$pass,$db,$rutaFunciones);
    	}
    	
    	$form=fopen($rutaForm, "a");
    	$funcion=fopen($rutaFuncion, "a");

    	$textForm="<form action='../../$rutaFuncion' method=post>\n";
    	$textFuncion="<?php \n
    					require_once('conexion.php');\n";
    	$insercion='$sql="INSERT INTO '.$fila[0].'(';
    		$values="";
    		$valuesName="";
/*crear carpetas y directorios fin*/
/*obtener tablas*/
			    $columnas = mysql_query("SHOW COLUMNS FROM $fila[0]");
					if (!$columnas) {
			   		echo 'No se pudo ejecutar la consulta: ' . mysql_error();
			    	exit;
					}
						$numero_columnas=mysql_num_rows($columnas);
				if (mysql_num_rows($columnas) > 0) {
		    		while ($filas = mysql_fetch_assoc($columnas)) {
		    			$numero_columnas--;
		    			extract($filas);
		  				
		    			$textForm .="<input type='text' name='$Field' id='$Field' class='class_$Field'>\n";
		    			$textFuncion.='$'.$Field.'=$_POST["'.$Field.'"];';
		    			$textFuncion.="\n";
		    			echo $numero_columnas;
		    			if($numero_columnas == 0){
		    				$values.=$Field;
		    				$valuesName.='$'.$Field;
		    			}else{
		    				$values.=$Field.',';
		    				$valuesName.='$'.$Field.',';
		    			}
		    			

		   			 }
					}
					$textFuncion.="\n";
					$insercion.=$values.')VALUES('.$valuesName.')";';
					$insercion.="\n";
					$textFuncion.=$insercion.'$result = mysql_query ($sql);';
					$textFuncion.="\n";
					$textFuncion.='if(!$result){';
					
					$textFuncion.="\n die('no se pudo insertar');\n
									echo mysql_error();\n
									}\n
									?>";


					$textForm.="<input type='submit' value='enviar'>\n</form>";

					if (fwrite($funcion,$textFuncion) === FALSE) {
        			echo "No se puede escribir en el archivo funcion";
       			 exit;
   				 }
   				 if (fwrite($form,$textForm) === FALSE) {
        			echo "No se puede escribir en el archivo form";
       			 exit;
   				 }
   				 fclose($funcion);
   				 fclose($form);

}

mysql_free_result($resultado);

?>

<?php
function crearArchivos($rutaForm){
	$header=$rutaForm."header.php";
	$footer=$rutaForm."footer.php";
	

		$fHeader = fopen($header,"a");
    	$fFooter = fopen($footer,"a");
    	/*template*/
    	
    	$headerText="
    				<!-- Form desing by vendetta -->\n
    				<html lang='es'> \n
					<head>\n
					<meta charset='utf-8'>\n
					<meta http-equiv='X-UA-Compatible' content=IE='edge'>\n
					<meta name='viewport' content='width=device-width, initial-scale=1'>\n
					<title>Form</title>\n
					</head>\n
					<body>\n
					<div class='container'>\n";
		$footerText="
					</div>\n
					<footer>\n
					</footer>\n
					</body>\n
					</html>	\n	
					";

				if (fwrite($fHeader,$headerText) === FALSE) {
        			echo "No se puede escribir en el archivo header";
       			 exit;
   				 }

   				if (fwrite($fFooter,$footerText) === FALSE) {
        			echo "No se puede escribir en el archivo footer";
       			 exit;
   				 }

   				 fclose($fHeader);
   				 fclose($fFooter);
   				 echo "exito";
}

function crearDirectorios($base){
	$result=0;
	if(!mkdir($base,0777)){
    		die('Fallo al crear las carpetas...');
    	}else{
    		$result=1;
    	}
    	if(!mkdir($base."/templates",0777)){
    		die('Fallo al crear las carpetas...');
    	}else{
    		$result+=1;
    	}
    	if(!mkdir($base."/functions",0777)){
    		die('Fallo al crear las carpetas...');
    	}else{
    		$result+=1;
    	}
    	if($result/3==1){
    		return 1;
    	}else{
    		return 0;
    	}
}

function crearConexion($user,$pass,$db,$rutaFunciones){
	$conexion=$rutaFunciones."conexion.php";
	$fConexion=fopen($conexion, "a");

	$conexionText="<?php \n";
	$conexionText.='$conexion= mysql_connect("localhost","'.$user.'","'.$pass.'");';
	$conexionText.="\n";
	$conexionText.='if(!$conexion){';
	$conexionText.="\n";
	$conexionText.="die('No se ha podido conectar a la BD: ' . mysql_error());\n
					}\n
					mysql_query('SET NAMES \'utf8\'');\n
					mysql_select_db('$db',";
	$conexionText.='$conexion);';
	$conexionText.="\n
					?>";

	if (fwrite($fConexion,$conexionText) === FALSE) {
        			echo "No se puede escribir en el archivo footer";
       			 exit;
   				 }
   				 fclose($fConexion);

}
?>