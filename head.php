<?php 

// Llamadas a otros php de configuracion, inicio de sesiones, etc
include("files/config.php");
include("files/funciones.php");
include("files/Mobile_Detect.php");

$link = conectar();

//Si tengo idioma en el get
if(isset($_GET['lang'])){
	//echo "lang: ".$_GET['lang'];
	$_SESSION['idioma'] = $_GET['lang'];
	
}
//Si no hay idioma
if(!isset($_SESSION['idioma'])){
	$_SESSION['idioma'] = IDIOMADEFAULT;
}

//Compruebo que el idioma exista ->
if(!(mysqli_num_rows(db_query($link,"SELECT * FROM config_idiomes WHERE nom_idioma='".$_SESSION['idioma']."'"))>0)){
	$_SESSION['idioma'] = IDIOMADEFAULT;	
}

$idioma = $_SESSION['idioma'];

define("IDIOMA",$idioma);

//Busco los config_general
$t = db_query($link,"SELECT * FROM config_general");
while($config_keys = mysqli_fetch_array($t)){
	define($config_keys['name'], $config_keys['value']);
}

//Buscamos el setlocale
$sl = db_query($link,"SELECT * FROM config_idiomes WHERE nom_idioma='".$idioma."'");
$setlocale = mysqli_fetch_array($sl);
setlocale(LC_TIME, $setlocale['setlocale']);

//Busco los textos en el idioma que corresponda
$t = db_query($link,"SELECT * FROM textos t, textos_content tc WHERE t.id=tc.id AND tc.idioma='".$idioma."' AND t.text_nodisabled NOT LIKE '%.%'");
while($textos = mysqli_fetch_array($t)){
	define($textos['text_nodisabled'], $textos['textarea_titulo']);
}
//Y los links
$links = array();
$t = db_query($link,"SELECT * FROM textos t, textos_content tc WHERE t.id=tc.id AND tc.idioma='".$idioma."' AND t.text_nodisabled LIKE '%.%'");
while($textos = mysqli_fetch_array($t)){
	$links[$textos['text_nodisabled']] = $textos['textarea_titulo'];
}
?>