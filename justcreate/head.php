<?php 

// Llamadas a otros php de configuracion, inicio de sesiones, etc
include("../files/config.php");
include("../files/funciones.php");
include("../files/Mobile_Detect.php");
//
include "./secure/secure.php";
include "SimpleImage.php";
include "SimpleImage.class.php";

//Establecer tamaño maximo de subida de ficheros
$maxfile = file_upload_max_size();
$maxfile = ($maxfile / 1024) / 1024;
define("MAXFILE",$maxfile);

$link = conectar();

if((isset($_POST['bo_idioma'])) && ($_POST['bo_idioma']!='')){

	$_SESSION['bo_idioma'] = $_POST['bo_idioma'];

}

if($_SESSION['bo_idioma']==''){

	$_SESSION['bo_idioma'] = 'es';
	
}

define("IDIOMA",$_SESSION['bo_idioma']);

//Busco los textos del backoffice
$t = db_query($link,"SELECT * FROM textosbackoffice t LEFT JOIN textosbackoffice_content tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."')");

while($textos = mysqli_fetch_array($t)){
	
	$backoffice[$textos['text_titulo']][IDIOMA] = $textos['textarea_texto'];

}

//Busco los config_general
$t = db_query($link,"SELECT * FROM config_general");
while($config_keys = mysqli_fetch_array($t)){
	define($config_keys['name'], $config_keys['value']);
}

?>