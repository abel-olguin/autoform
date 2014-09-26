<?php
function crearArchivos($rutaVista){
	$header=$rutaVista."header.php";
  $index=$rutaVista."index.php";
	$footer=$rutaVista."footer.php";

	

		  $fHeader = fopen($header,"a");
    	$fFooter = fopen($footer,"a");
      $fIndex = fopen($index, "a");
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
      $indexText="<?php \n require_once('templates/form.php');\n ?>";
		  $footerText="
      					</div>\n
      					<footer>\n
      					</footer>\n
      					</body>\n
      					</html>	\n	";

				if (fwrite($fHeader,$headerText) === FALSE) {
        			echo "No se puede escribir en el archivo header";
       			 exit;
   				 }
           if (fwrite($fIndex,$indexText) === FALSE) {
              echo "No se puede escribir en el archivo index";
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
    rrmdir($base);
    rrmdir($base."/templates");
    rrmdir($base."/functions");

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
	$conexionText.='$conexion= new mysqli("localhost","'.$user.'","'.$pass.'");';
	$conexionText.="\n";
	$conexionText.='if($conexion->connect_errno){';
	$conexionText.="\n";
	$conexionText.="die('No se ha podido conectar a la BD: ' .";
  $conexionText.='$conexion->connect_errno);';
  $conexionText.="\n
        					}\n";
  $conexionText.='$conexion->query(';
  $conexionText.="'SET NAMES \'utf8\'');";
  $conexionText.="\n";
  $conexionText.='$conexion->select_db(';
  $conexionText.="'$db');";
	$conexionText.="\n
					       ?>";

	if (fwrite($fConexion,$conexionText) === FALSE) {
        			echo "No se puede escribir en el archivo footer";
       			 exit;
   				 }
   				 fclose($fConexion);

}

function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
}
?>
