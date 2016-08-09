<?php 
include("head.php");

//Configurar
$table = "servicios";
$table_content = $table."_content";
$sufijo_plural = "Noticias";
$sufijo_singular = "Noticia";
$campo_listar = "t.text_fecha"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "DESC";
$pinta_listar = "text_titulo";
$pint2_listar = "textarea_intro";
$cols = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table;
$cols_content = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content;
$limite = "LIMIT 0,4";
//End configurar

$id = $_GET['id'];
$_SESSION['id'] = $id;
	
$e = db_query($link,"SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.id='".$id."'");
$element = mysqli_fetch_array($e);

$ht_titulo = $element['text_title'];
$ht_description = html_entity_decode(strip_tags($element['text_description']), ENT_COMPAT, 'UTF-8');

$ht_imagen = 'http://'.$_SERVER['HTTP_HOST'].''.dirname($_SERVER['SCRIPT_NAME']).'/images/logos/logo.png';

include("auto_functions.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo $ht_titulo;?></title>
<meta name="keywords" content="<?php echo METAS_KW; ?>" />
<meta name="description" content="<?php echo $ht_description;?>" />
<link rel="image_src" href="<?php echo $ht_imagen; ?>" />
<meta property="og:image" content="<?php echo $ht_imagen; ?>"/>
<?php include("header.php");?>
<link href="colorbox/colorbox.css" rel="stylesheet" type="text/css" media="screen" />
<script src="colorbox/jquery.colorbox.js" type="text/javascript"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$("a[rel='photo']").colorbox({maxWidth:"75%",maxHeight:"75%",loop:false , transition:"elastic", speed:"100", reposition:false});
});
</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

</head>

<body class="noticia srvPage <?php echo IDIOMA;?> noticias flex">
<?php include("body.php");?>

<div class="outtercbp">	
	<ul id="cbp-bislideshow" class="cbp-bislideshow">

		<?php
		$prefijo = "";
		if($detect->isMobile()) $prefijo = "mob_";
		?>
		

		<?php
		if($element['select_categoriass'] == 2){//Matrimonio
			$valor = rand(0,9); // Uso de rand(): rand(Valor minimo, Valor maximo);
			$imagenes = array("14.jpg" , "13.jpg" , "52.jpg" , "35.jpg" , "10.jpg", "28.jpg" , "54.jpg" , "53.jpg" , "09.jpg" , "12,jpg");
		} else if($element['select_categoriass'] == 3){//Herencia
			$valor = rand(0,1); // Uso de rand(): rand(Valor minimo, Valor maximo);
			$imagenes = array("32.jpg" , "53.jpg");
		} else if($element['select_categoriass'] == 4){//Bancario
			$valor = rand(0,6); // Uso de rand(): rand(Valor minimo, Valor maximo);
			$imagenes = array("09.jpg" , "49.jpg" , "50.jpg" , "12.jpg" , "11.jpg", "55.jpg" , "38.jpg");
		} else if($element['select_categoriass'] == 5){//Inmobiliario
			$valor = rand(0,2); // Uso de rand(): rand(Valor minimo, Valor maximo);
			$imagenes = array("46.jpg" , "43.jpg" , "50.jpg");
		} 



		echo '<li><img src="images/branding/'.$prefijo.''.$imagenes[$valor].'" alt="">'
	?>
			<div class="container"><em><h1><?php pinta('text_titulo');?></h1></em></div></li>
		
	</ul>
</div>

<div class="block1">
	<div class="intro">   
			<div class="columnsCSS">
				<?php pinta('textarea_texto');?>
			</div><div class="clearfix"></div>
			<div class="row"><span class="button smallest link"><a onclick="javascript:history.back()" title="<?php echo METAS_MENU_NOTICIAS_TIT;?>">< <?php echo COMMON_VOLVER; ?></a></span></div>
	</div>
</div>



</div>
<?php include("footer.php");?>
<!-- ///// cbp-bislideshow ///// -->
<script src="js/modernizr.custom.js"></script>
<script src="js/jquery.imagesloaded.min.js"></script>
<script src="js/cbpBGSlideshow.js"></script>

<!-- ///// owl-carousel ///// -->
<!-- <script src="js/jquery-1.9.1.min.js"></script> -->
<script src="js/owl.carousel.min.js"></script>
<script>
$(document).ready(function() {
	var owl = $('.owl-carousel');
	owl.owlCarousel({
	    margin: 10,
	    nav: true,
	    loop: true,
	    responsive: {
	      0: {
	        items: 1
	      },
	      600: {
	        items: 3
	      },
	      1000: {
	        items: 5
	      }
	    }
	})

    $('#btnTarget').fullHeightImage({
		btn: "#btnDown",
		margintop: "130",
		speed:1000,
		HeightWindow: false
    });

    // heightAll('#cbp-bislideshow');
	
    heightAll('#cbp-bislideshow');

})


</script>

</body>
</html>
<?php include("bottom.php");?>