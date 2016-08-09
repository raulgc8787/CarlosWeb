<?php
require_once("head.php");

$name = trim($_POST['name']);
$value = trim($_POST['value']);

//Miramos si este registro existe en la base de datos
$e = db_query($link,"SELECT * FROM config_general WHERE name='".$name."'");
$hay = mysqli_num_rows($e);
//Update
if($hay == 1){
	$sql = "UPDATE config_general SET value='".$value."' WHERE name='".$name."'";
	$u = db_query($link,$sql);
} 

?>