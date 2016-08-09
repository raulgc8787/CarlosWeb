<?php 
include("head.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_CONTACTO_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_CONTACTO_DESC;?>" />

<?php include("header.php");?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>
<?php //include("js/mapamultiple-js.php"); ?>
</head>

<body class="ok <?php echo IDIOMA;?> flex">
<?php include("body.php");?>
<div class="container">    
	<div class="row">

		<h1><?php echo CONTACTO_H1;?></h1>

	    <?php 
		
		if((int)$_GET['error']=='1'){ 
		
			echo COMMON_ENVIOERROR; 
		
		}else{
		
			echo COMMON_ENVIOOK;
		
		}
		
		?>

    </div>

</div>
<?php include("footer.php");?>
</body>
</html>
<?php include("bottom.php");?>