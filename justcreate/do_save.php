<?php
require_once("head.php");
require_once("htaccess.php");

$id = (int)$_POST['id'];

$i = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
while($idiomas = mysqli_fetch_array($i)){
	
	$valor = $_POST[$idiomas['nom_idioma']];
	echo $valor."<br />";
	
	//Miramos si este registro existe en la base de datos
	$e = db_query($link,"SELECT * FROM textos_content WHERE id=".$id." AND idioma='".$idiomas['nom_idioma']."'");
	$hay = mysqli_num_rows($e);
	//Update
	if($hay == 1){
		$sql = "UPDATE textos_content SET textarea_titulo='".$valor."' WHERE id='".$id."' AND idioma='".$idiomas['nom_idioma']."'";
		$u = db_query($link,$sql);
	} 
	//Insert
	else if($hay == 0){
		$sql = "INSERT INTO textos_content(id,idioma,textarea_titulo) VALUES('".$id."','".$idiomas['nom_idioma']."','".$valor."')";
		$i = db_query($link,$sql);
	}
	//echo $sql;
	
	//Preventivo:
	//Miramos todas las urls
	$u = db_query($link,"SELECT * FROM textos WHERE text_nodisabled LIKE '%.php'");
	while($urls = mysqli_fetch_array($u)){
		$t = db_query($link,"SELECT * FROM textos_content WHERE id=".$urls['id']);
		while($trans = mysqli_fetch_array($t)){
			$txt = $trans['textarea_titulo'];
			$x = db_query($link,"UPDATE textos_content SET textarea_titulo='".urls_amigables($txt)."' WHERE id=".$trans['id']." AND idioma='".$trans['idioma']."' LIMIT 1");
		}
	}
	
	//Si corresponde, actualizo el htaccess
	//Para ello busco la key
	$k = db_query($link,"SELECT * FROM textos WHERE id=".$id);
	$key = mysqli_fetch_array($k);
	$valor = $key['text_nodisabled'];
	if(strpos($valor,".") > 1){		
		htaccess();
	}
}
?>
