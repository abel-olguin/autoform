<?php
require_once("funciones.php");
$user=$_POST['user'];
$pass=$_POST['pass'];
$db=$_POST['db'];

$conexion = new mysqli("localhost" ,$user , $pass);
if ($conexion->connect_errno)
  {
    die("No se ha podido conectar a la BD: " . $conexion->connect_errno);
  }
$conexion->query('SET NAMES \'utf8\'');

/*conexion a base de datos*/

$sql = "SHOW TABLES FROM $db";
$resultado = $conexion->query($sql);
$conexion->select_db($db);
if (!$resultado) {
    echo "Error de BD, no se pudieron listar las tablas\n";
    echo 'Error MySQL: ' . $resultado->connect_errno;
    exit;
}

while ($fila = $resultado->fetch_row()) {
/*crear carpetas y archivos*/
		
		$rutaVista=$fila[0]."/";
    	$rutaForms=$fila[0]."/templates/";
    	$rutaFunciones=$fila[0]."/functions/";

    	$rutaForm=$rutaForms."form.php";
    	$rutaFuncion=$rutaFunciones."result.php";

    	if(crearDirectorios($fila[0])){
    		crearArchivos($rutaVista);
    		crearConexion($user,$pass,$db,$rutaFunciones);
    	}
    	
    	$form=fopen($rutaForm, "a");
    	$funcion=fopen($rutaFuncion, "a");

    	$textForm="<form action='../$rutaFuncion' method=post>\n";
    	$textFuncion="<?php \n
    					require_once('conexion.php');\n";
    	$insercion='$sql="INSERT INTO '.$fila[0].'(';
    		$values="";
    		$valuesName="";
/*crear carpetas y directorios fin*/
/*obtener tablas*/
			    $columnas = $conexion->query("SHOW COLUMNS FROM $fila[0]");
					if (!$columnas) {
			   		echo 'No se pudo ejecutar la consulta: ' . $conexion->connect_errno;
			    	exit;
					}
						$numero_columnas=$columnas->num_rows;
				if ($columnas->num_rows > 0) {
		    		while ($filas = $columnas->fetch_assoc()) {
		    			$numero_columnas--;
		    			extract($filas);
		  				if($Field!="id"){

		  					$label=ucwords ( str_replace(array('-','_'), array(' ',' '), $Field));
		    			$textForm .="<label for='$Field'>$label</label><input type='text' name='$Field' id='$Field' class='class_$Field'></br>\n";
		    			$textFuncion.='$'.$Field.'=$_POST["'.$Field.'"];';
		    			$textFuncion.="\n";
		    			
		    			if (strpos($Type, 'int')===false||strpos($Type, 'float')===false||strpos($Type, 'double')===false
		    				||strpos($Type, 'boolean')===false) {
    						$valuesName.="'$".$Field."'";
							} else {
   							$valuesName.='$'.$Field;
							}

		    			if($numero_columnas == 0){
		    				$values.=$Field;
		    				
		    			}else{
		    				$values.=$Field.',';
		    				$valuesName.=',';
		    				}
		    			}

		   			 }
					}
					$textFuncion.="\n";
					$insercion.=$values.')VALUES('.$valuesName.')";';
					$insercion.="\n";
					$textFuncion.=$insercion.'$result = $conexion->query ($sql);';
					$textFuncion.="\n";
					$textFuncion.='if($conexion->connect_errno){';
					
					$textFuncion.="\n die('no se pudo insertar');\n";
					$textFuncion.='echo $conexion->connect_errno;';
					$textFuncion.= "\n
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

$resultado->free_result();

?>

