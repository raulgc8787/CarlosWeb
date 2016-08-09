<?php 
include("head.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_EMPRESA_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_EMPRESA_DESC;?>" />

<?php include("header.php");?>
</head>

<body class="<?php echo IDIOMA;?>">
<?php include("body.php");?>
<div class="container">  
  
	<div class="row">

		<h1><?php echo COMMON_ERROR404_H1;?></h1>
		<p><?php echo COMMON_ERROR404TEXTO;?></p>

	</div>

</div>
<?php include("footer.php");?>
</body>
</html>
<?php include("bottom.php");?>