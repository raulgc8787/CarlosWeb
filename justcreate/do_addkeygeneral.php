<?php
include("head.php");

$name = strtoupper(trim($_POST['name']));
$value = trim($_POST['value']);

//Miramos si este registro existe en la base de datos
$e = db_query($link,"SELECT * FROM config_general WHERE name='".$name."' ");
$hay = mysqli_num_rows($e);
//Update
if($hay == 0){
	$sql = "INSERT INTO config_general(name, value) VALUES('".$name."','".$value."')";
	$u = db_query($link,$sql);
} 

echo $url;
?>