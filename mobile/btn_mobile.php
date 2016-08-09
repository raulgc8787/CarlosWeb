<?php
// Cierre de toda la construccion de los elementos dentro del body, ademas de la estructura del footer
?>

<!-- Productos type -->

<?php
switch ($btnType) {
    case "btn1":
?>
	<a href="tel:933426466"><div class="btnMb"></div></a>

	<script type="text/javascript">

		$("#nav li").removeClass("current");
		$("#navItem5").addClass("current");
	

	</script>

<?php
    break;
    case "btn2":
?>
	<?php include('mobile/btn_link.php'); ?>

	<script type="text/javascript">

		$("#nav li").removeClass("current");
		$("#navItem5").addClass("current");
	

	</script>

<?
        break;
}

?>
