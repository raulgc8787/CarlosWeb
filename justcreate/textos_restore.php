<?php 

include("../files/config.php");
include("../files/funciones.php");

$link = conectar();

//Inserto registro de restauracion con errores
$fecha = date("Y-m-d H:i:s");
$i1 = db_query($link,"INSERT INTO txtcontrol(fecha,operacion) VALUES('".$fecha."','Restauracion con errores')"); 
$id1 = mysqli_insert_id($link);	

//Borro las tablas normales
$r1 = db_query($link,"DELETE FROM textos"); 
$r2 = db_query($link,"DELETE FROM textos_content"); 

//Renombro las backup a normal
$r3 = db_query($link,"INSERT textos SELECT * FROM textos_bk"); 
$r4 = db_query($link,"INSERT textos_content SELECT * FROM textos_content_bk"); 

//Actualizamos la restauracion ya que no ha dado error, ha sido correcta
$u1 = db_query($link,"UPDATE txtcontrol SET operacion='Restauracion correcta' WHERE id='".$id1."'");

//Cierro conexión BBDD ->
mysqli_close($link);

?>
<div style="float:left; width:100%; margin-top:25px; padding:25px; text-align:center;"><strong>Restauraci&oacute;n completada</strong></div>