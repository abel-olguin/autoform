<?php
require_once("funciones.php");


$user = $_POST['user'];
$pass = $_POST['pass'];
$db   = $_POST['db'];

/*conexion a base de datos*/
$conexion = new mysqli("localhost" ,$user , $pass);

if ($conexion->connect_errno)
  {
    die("No se ha podido conectar a la BD: " . $conexion->connect_errno);
  }
/**
 * Queries
 */
$conexion->query('SET NAMES \'utf8\'');

$sql       = "SHOW TABLES FROM $db";
$resultado = $conexion->query($sql);

$conexion->select_db($db);

if (!$resultado) {

    echo "Error de BD, no se pudieron listar las tablas\n";

    die('Error MySQL: ' . $resultado->connect_errno);
}
/**
 * queries end
 */
while ($fila = $resultado->fetch_row()) {
/*crear carpetas y archivos*/

		$ruta_vista      = $db."/".$fila[0];
    	$ruta_forms      = $db."/".$fila[0]."/templates/";
    	$ruta_funciones  = $db."/".$fila[0]."/functions/";
        $ruta_form       = $ruta_forms."form.php";
    	$ruta_funcion    = $ruta_funciones."result.php";

    	if(crearDirectorios($ruta_vista)){

    		crearArchivos($ruta_vista);

    		crearConexion($user,$pass,$db,$ruta_funciones);
    	}
    	
    	$form         = fopen($ruta_form, "a+");
    	$funcion      = fopen($ruta_funcion, "a+");

    	$text_form    = "<form action='functions/result.php' method=post>\n";
    	$text_funcion  = "<?php \n
    					require_once('conexion.php');\n";

        $insercion    = '$sql="INSERT INTO '.$fila[0].'(';
        $values       = "";
        $valuesName   = "";
/*crear carpetas y directorios */


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

		  				if($Field != "id"){

                            $label        = ucwords ( str_replace(array('-','_'), array(' ',' '), $Field));
		    			    $text_form    .= "<label for='$Field'>$label: </label><br><input type='text' name='$Field' id='$Field' class='class_$Field' required></br>\n";
		    			    $text_funcion .= '$'.$Field.' = $_POST["'.$Field.'"];';
		    			    $text_funcion .= "\n";


		    			if (strpos($Type, 'int') === false && strpos($Type, 'float') === false && strpos($Type, 'double') === false
                            && strpos($Type, 'boolean') === false)
                        {

    						$valuesName.="'$".$Field."'";
						}
                        else
                        {

   							$valuesName.='$'.$Field;
						}

		    			if($numero_columnas == 0){
		    				$values .= $Field;
		    				
		    			}else{
		    				$values .= $Field.',';
		    				$valuesName.=',';
		    				}
		    			}

		   			 }

					}
					$text_funcion .= "\n";
					$insercion    .= $values.')VALUES('.$valuesName.')";';
					$insercion    .= "\n";
					$text_funcion .= $insercion.'$result = $conexion->query ($sql);';
					$text_funcion .= "\n";
					$text_funcion .= 'if($conexion->connect_errno){';
					
					$text_funcion .= "\n die('no se pudo insertar:'.";
					$text_funcion .= '$conexion->connect_errno);';
					$text_funcion .=  "\n
									}\n
									else
									{
									echo 'exito';
									}
									?>";


					$text_form.="<input type='submit' value='enviar'>\n</form>";

					if (fwrite($funcion,$text_funcion) === FALSE) {
        			echo "No se puede escribir en el archivo funcion";
       			 exit;
   				 }
   				 if (fwrite($form,$text_form) === FALSE) {
        			echo "No se puede escribir en el archivo form";
       			 exit;
   				 }
   				 fclose($funcion);
   				 fclose($form);

}

$resultado->free_result();

?>

