<?php
include("head.php");
require_once("htaccess.php");

$prefix = $_POST['prefix'];
$mida = $_POST['mida'];
$tipo = $_POST['tipo'];

//Miramos si este registro existe en la base de datos
$e = db_query($link,"SELECT * FROM config_thumbs WHERE text_prefix='".$prefix."' ");
$hay = mysqli_num_rows($e);
//Update
if($hay == 0){
	$sql = "INSERT INTO config_thumbs(text_prefix, select_tipothumb, text_mida) VALUES('".$prefix."','".$tipo."','".$mida."')";
	//echo $sql; 
	$u = db_query($link,$sql) or die(mysqli_error($link));
} 


echo $url;
?>
