<?php
/**
 * Created by vendetta.
 */

$conexion = new mysqli("localhost",'--name_str--','--pass_str--');

    if($conexion->connect_errno)
    {
        die('No se ha podido conectar a la BD: '.$conexion->connect_errno);
    }

$conexion->query('SET NAMES \'utf8\'');

$conexion->select_db('--db_str--');

?>;