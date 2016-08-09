<?php 
include("head.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_LEGAL_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_LEGAL_DESC;?>" />

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

<?php include("header.php");?>
</head>

<body class="cookies <?php echo IDIOMA;?> flex">
<?php include("body.php");?>
<div class="subcontainer">    

	<div class="row">
	    <?php 
	      $paginaLegal = COMMON_LEGAL_TEXT;
	      $paginaLegal = str_replace("{CLIENTE}",CLIENTE,$paginaLegal);
	      $paginaLegal = str_replace("{NIF}",NIF,$paginaLegal);
	      $paginaLegal = str_replace("{COMMON_DIRECCION}",COMMON_DIRECCION,$paginaLegal);
	      $paginaLegal = str_replace("{PHONE}",PHONE,$paginaLegal);
	      echo $paginaLegal;
	    ?>
	</div>

</div>
<?php include("footer.php");?>
</body>
</html>
<?php include("bottom.php");?>