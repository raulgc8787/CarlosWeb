<?php
include("head.php");
require_once("htaccess.php");

$valor = trim($_POST['valor']);

if(!(strpos($valor,".") > 0)){

	$valor = strtoupper($valor);

}


//Comprobamos que no existe la clave
$e = db_query($link,"SELECT * FROM textos WHERE text_nodisabled='".$valor."'");
$hay = mysqli_num_rows($e);
if($hay == 0){
	//Insercion
	$i = db_query($link,"INSERT INTO textos(text_nodisabled) VALUES('".$valor."')");
	$last_id = mysqli_insert_id($link);
	$idi = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
	while($idiomas = mysqli_fetch_array($idi)){
		$nom_idioma = $idiomas['nom_idioma'];
		$i = db_query($link,"INSERT INTO textos_content(id,idioma,textarea_titulo) VALUES('".$last_id."','".$nom_idioma."','".$_POST[$nom_idioma]."')");
	}
	if(strpos($valor,".") > 1){		
		htaccess();
	}
	//Comprobamos donde enviamos
	$prefijo = substr($valor,0,strpos($valor,"_"));
	if($prefijo == "") $prefijo = "url";
	$url = "textos.php?key=".$prefijo;
} else{
	//error, ya existe
}


echo $url;
?>
