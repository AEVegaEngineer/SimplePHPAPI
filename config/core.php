<?php
$time = time();
// mostrar reporte de errores
error_reporting(E_ALL);
 
// time-zone
date_default_timezone_set('America/Argentina/San_Juan');
 
// variables usadas para el jwt
//lave del token
$key = "_ColegioDeMedicos_19422581";
$iat = $time;
?>