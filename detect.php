<?php 
// Llamadas a otros php de configuracion, inicio de sesiones, etc
include("files/config.php");
include("files/funciones.php");
include("files/Mobile_Detect.php");

$link = conectar();

$detect = new Mobile_Detect();
/*
if ($detect->isMobile()) {
	echo "mobile";
} else if($detect->isTablet()){
	echo "tablet";
} else{
	echo "desktop";
}
*/
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
echo $deviceType;
?>