<?php
require_once("head.php");
require_once("htaccess.php");

$id = (int)$_POST['id'];
$mida = $_POST['mida'];
$tipo = $_POST['tipo'];

//Miramos si este registro existe en la base de datos
$e = db_query($link,"SELECT * FROM config_thumbs WHERE id=".$id." ");
$hay = mysqli_num_rows($e);
//Update
if($hay == 1){
	$sql = "UPDATE config_thumbs SET select_tipothumb='".$tipo."', text_mida='".$mida."' WHERE id='".$id."' ";
	echo $sql; 
	$u = db_query($link,$sql) or die(mysqli_error($link));
} 

?>
