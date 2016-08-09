<?php

// Incluyo las funciones basicas
include "../files/config.php";
include "../files/funciones.php";

$sortme = $_POST;
foreach($sortme as $key=>$value){
	//echo "k:".$key." - v:".$value[0]."--";
}
$cuantos = count($sortme[$key]);
$nombretabla = substr($key,6,strlen($key)-6);

// Connect to the database as necessary
$link = conectar();

for ($i = 0; $i < $cuantos; $i++) {
	$v = $sortme[$key][$i];
    //echo "UPDATE ".$nombretabla." SET orden=$i WHERE id=$v AND id_elem=".$_SESSION['id'];	
    db_query($link,"UPDATE ".$nombretabla." SET orden=$i WHERE id=$v");
	echo "UPDATE ".$nombretabla." SET orden=$i WHERE id=$v";
}
?>