<?php
include("head.php");
require_once("htaccess.php");

$error = 0;

$valor = trim($_POST['valor']);

if(!(strpos($valor,".") > 0)){

	$valor = strtoupper($valor);

}

//Miro si es una URL
if(strpos($valor,".") > 0){
	//Comprobamos que no exista ya
	if($error == 0){
		//echo "SELECT * FROM textos WHERE text_nodisabled='".$valor."'";
		$b = db_query($link,"SELECT * FROM textos WHERE text_nodisabled='".$valor."'");
		$hay = mysqli_num_rows($b);
		if($hay > 0){
			$error = 2;
		}
	}
} 
//Si no lo es hacemos las comprobaciones pertinentes
else{
	//Miramos que tenga guion bajo y que no sea el ultimo caracter
	$bajo = substr_count($valor,"_");
	if($bajo == 0){
		$error = 1;
	} else{
		$bajopos = strpos($valor,"_");
		if($bajopos == (strlen($valor)-1) ){
			$error = 1;
		}
	}
	//Comprobamos que todo este en mayusculas
	
	if($error == 0){
		$bajopos = strpos($valor,"_");
		$siguiente = $bajopos+1;
		$prefijo = substr($valor,0,$bajopos);
		$sufijo = substr($valor,$siguiente,strlen($valor)-$siguiente);
		//echo "prefijo: ".$prefijo." sufijo: ".$sufijo."--";
		$valorfake = $prefijo.$sufijo;
		//echo $valorfake;
		//if(!ctype_upper($valorfake)){
		if(preg_match("/[a-z]/", $valorfake)){
			$error = 1;
		}
	}
	
	//Comprobamos que no exista ya
	if($error == 0){
		//echo "SELECT * FROM textos WHERE text_nodisabled='".$valor."'";
		$b = db_query($link,"SELECT * FROM textos WHERE text_nodisabled='".$valor."'");
		$hay = mysqli_num_rows($b);
		if($hay > 0){
			$error = 2;
		}
	}
}
echo $error;
mysqli_close($link);
?>