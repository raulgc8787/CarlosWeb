<?php

// Incluyo las funciones basicas
include "../files/config.php";
include "../files/funciones.php";

//$sortme = $_POST['sortme'.$_SESSION['tabla']];
$sortme = $_POST;
foreach($sortme as $key=>$value){
	//echo "k:".$key." - v:".$value[0]."--";
}
$cuantos = count($sortme[$key]);
$nombretabla = substr($key,6,strlen($key)-8);

echo $nombretabla;

// Connect to the database as necessary
$link = conectar();

/*
for ($i = 0; $i < count($sortme); $i++) {
  //db_query($link,"UPDATE ".$_SESSION['tabla']." SET orden=$i WHERE id=$sortme[$i] AND idioma='".$_SESSION['idioma']."'");
  db_query($link,"UPDATE ".$nombretabla." SET orden=$i WHERE id=$sortme[$i] AND idioma='".$_SESSION['idioma']."'");
}
*/

for ($i = 0; $i < $cuantos; $i++) {
	$v = $sortme[$key][$i];
    //echo "UPDATE ".$nombretabla." SET orden=$i WHERE id=$v AND id_elem=".$_SESSION['id'];	
    db_query($link,"UPDATE ".$nombretabla." SET orden=$i WHERE id=$v AND idioma='".$_SESSION['idioma']."' AND id_elem=".$_SESSION['id']);
}
?>