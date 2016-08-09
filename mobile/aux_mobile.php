<?php
// Cierre de toda la construccion de los elementos dentro del body, ademas de la estructura del footer
?>

<!-- Productos type -->

<?php
switch ($auxType) {
    case "aux1":
?>
	<?php include('mobile/aux_mobile_search.php'); ?>

<?php
    break;
    case "aux2":
?>
	<?php include('mobile/aux_mobile_icon_top.php'); ?>

<?php
    break;
    case "aux3":
?>
	<?php include('mobile/aux_mobile_icon_bottom.php'); ?>

<?
        break;
}

?>
