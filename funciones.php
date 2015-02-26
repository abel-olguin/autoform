<?php
/**
 * @param $ruta_vista
 *
 * Crea todos los archivos necesarios
 *
 * Funcion encargada de crear todo el arbol de
 * archivos que son necesarios para el formulario
 */
function crearArchivos($ruta_vista){

    $title       = explode('/',$ruta_vista);
	$header      = $ruta_vista."/header.php";
    $index       = $ruta_vista."/index.php";
	$footer      = $ruta_vista."/footer.php";

    $header_root = 'sources/header.php';
    $footer_root = 'sources/footer.php';
    $index_root  = 'sources/index.php';




				if (!copy($header_root,$header))
                {
        			die("No se puede escribir en el archivo header");
   				}
                else
                {
                    search_and_replace($header,array('--title_str--'),$title);
                }

                if (!copy($footer_root,$footer))
                {
                    die("No se puede escribir en el archivo header");
                }

                if (!copy($index_root ,$index))
                {
                    die("No se puede escribir en el archivo header");
                }
   				 echo "exito";
}

/**
 * @param $base
 * @return int
 *
 * crea las carpetas necesarias
 *
 * Funcion encargada de crear las carpetas necesarias
 * para el funcionamiento del formulario
 */
function crearDirectorios($base){

    $result = 0;
	$root   = explode('/',$base)[0];


            rrmdir($base."/templates");
            rrmdir($base."/functions");
            rrmdir($base);

        if(!is_dir($root))
        {
            if(!mkdir($root,0777))
            {
                die('Fallo al crear la carpeta base');
            }
            else
            {
                $result++;
            }

        }

        if(mkdir($base))
        {
            $result ++;
        }
        else
        {
            die('Fallo al crear la carpeta identificadora');
        }
        if(mkdir($base."/templates",0777))
        {
            $result++;
        }
        else
        {
            die('Fallo al crear las template');
        }
        if(mkdir($base."/functions",0777))
        {
            $result++;
        }
        else
        {
            die('Fallo al crear la carpeta functions');
        }

        if($result == 3 || $result == 4)
        {
            return 1;
        }
        else
        {
            return 0;
        }

}

/**
 * @param $user
 * @param $pass
 * @param $db
 * @param $ruta_funciones
 *
 * Crea el archivo de conexion
 *
 * Funcion encargada de crear el archivo de conexion
 * para realizar inserciones en el formulario posteriormente
 */
function crearConexion($user,$pass,$db,$ruta_funciones){

	$conexion       = $ruta_funciones."conexion.php";
	$conexion_root  = 'sources/conexion.php';

	if (!copy($conexion_root,$conexion))
    {
        die("No se puede escribir en el archivo conexion");
    }
    else
    {
        $search  = array('--name_str--','--pass_str--','--db_str--');
        $replace = array($user,$pass,$db);
        search_and_replace($conexion,$search,$replace);

    }


}

/**
 * @param $dir
 * Elimina un directorio y su arbol de archivos
 *
 * Funcion encargada de eliminar un directorio incluyendo su arbol de archivos
 * (todo lo que contenga el directorio)
 */
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

/**
 * @param $path
 * @param $search
 * @param $replace
 *
 * Busca y reemplaza en un archivo
 *
 * Funcion encargada de buscar y reemplazar en un archivo
 *
 */
function search_and_replace($path,$search,$replace)
{
    $path_to_file  = $path;
    $file_contents = file_get_contents($path_to_file);
    $file_contents = str_replace($search,$replace,$file_contents);

    file_put_contents($path_to_file,$file_contents);
}
?>