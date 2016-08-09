<?php 

include("../files/config.php");
include("../files/funciones.php");

$link = conectar();

echo '<table>';
echo '<th>Fecha</th><th>Operaci&oacute;n</th><th>Fichero</th>';
$l = db_query($link,"SELECT * FROM txtcontrol ORDER BY fecha DESC");
$it = 0;
while($list = mysqli_fetch_array($l)){
	echo '<tr><td>'.fechaEsp($list['fecha'])." ".substr($list['fecha'],11,8).'</td><td>'.$list['operacion'].'</td><td>'.$list['fichero'].'</td></tr>';
	if($it == 0 && $list['operacion'] == "Insercion con errores"){
		$restaurar = 1;
	}
	$it++;
}
echo '</table>';

if($restaurar == 1){
	echo 'Restaurar';
}
?>