<?php 
include("head.php");

//Configurar
$table = "noticias";
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

$ht_titulo = $element[$pinta_listar];
$ht_description = html_entity_decode(strip_tags($element[$pint2_listar]), ENT_COMPAT, 'UTF-8');

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
$limite2 = " LIMIT 1";
$listar = "SELECT * FROM " . $table . " t LEFT JOIN " . $table_content . " tc ON (t.id=tc.id AND tc.idioma='" . IDIOMA . "') WHERE t.id = '" . $id . "' AND t.checkbox_visible='1' AND t.text_fecha <= '" . date('Y-m-d') . "' ORDER BY " . $campo_listar . " " . $orden_listar . " " . $limite2;

$l = db_query($link, $listar);

while ($list = mysqli_fetch_array($l)) {

	echo '<li>';

	$url = urls_amigables($list[$pinta_listar]);
	$url = $url . "-n-" . $list['id'] . "-" . IDIOMA;
	$title = $list[$pinta_listar];

	//Foto
	$r = db_query($link, "SELECT * FROM " . $table . " WHERE id=" . $list['id']);
	$registro = mysqli_fetch_array($r);
	if ($registro['file_foto'] != "") {
		echo '<img src="images/' . $table . '/' . $registro['file_foto'] . '" alt="' . $title . '" />';
	} else {
		//O foto de uploader, averiguo si hay campo de fileuploader
		$list_cols = db_query($link, $cols);
		while ($lista = mysqli_fetch_array($list_cols)) {
			$field = $lista[0];
			if (substr($field, 0, 12) == "fotouploader") {
				$tablafotos = substr($field, 13, strlen($field) - 13);
				$ff = db_query($link, "SELECT * FROM " . $tablafotos . " WHERE id_elem=" . $list['id'] . " AND orden=0 LIMIT 1");
				$ffoto = mysqli_fetch_array($ff);
				if ($ffoto['foto'] != "") {
					echo '<img src="pics_fotosnoticias/' . $list['id'] . '/' . $ffoto['foto'] . '" />';
				}
			}
		}
	}
	echo '<div class="container"><em><h1>'.$title.'</h1></em></div></li>';

}
?>
	</ul>
</div>

<div class="block1">
	<div class="intro">   
			<div class="columnsCSS">
				<?php echo '<div class="date"> '.fechaEspExt($element['text_fecha']).'</div>';
       			pinta('textarea_texto');?>
			</div><div class="clearfix"></div>
	</div>
</div>



<div class="row newsList">
	<div class="container">
		<div class="row"> <div class="lastNews"><img src="images/icons/ico-news-01.svg" alt="<?php echo METAS_NOTICIAS_ALT; ?>"><h2 class="lastNews">Últimes notícies</h2></div></div>
		<div class="columnsCSS">
			<ul>
			<?php
				$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.checkbox_visible='1' ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;

				$l = db_query($link,$listar);
				while($list = mysqli_fetch_array($l)){
					echo '<li> <div class="news">';
							
							$title = $list[$pinta_listar];
							$url = urls_amigables($title);
				   			$url = $url."-n-".$list['id']."-".IDIOMA;	
						echo '<div class="date"> '.fechaEspExt($list['text_fecha']).'</div>';
						echo '<div class="title"><div class="element"><h2><a href="'.$url.'" title="'.$title.'">'.$title.'</a></h2></div></div>';
						echo '<div class="mas"><a href="'.$url.'" title="'.$title.'">'.COMMON_VERMAS.'</a></div></div>';

							
					
					echo '<div class="imagen"><a href="'.$url.'" title="'.$title.'">';
					//Foto
					$r = db_query($link,"SELECT * FROM ".$table." WHERE id=".$list['id']);
					$registro = mysqli_fetch_array($r);
					if($registro['file_foto'] != ""){
						echo '<img src="images/'.$table.'/med_'.$registro['file_foto'].'" alt="'.$title.'" />';
					} else{
						//O foto de uploader, averiguo si hay campo de fileuploader
						$list_cols = db_query($link,$cols);
						while($lista = mysqli_fetch_array($list_cols)){
							$field = $lista[0];
							if(substr($field,0,12) == "fotouploader"){						
								$tablafotos = substr($field,13,strlen($field)-13);						
								$ff = db_query($link,"SELECT * FROM ".$tablafotos." WHERE id_elem=".$list['id']." AND orden=0 LIMIT 1");
								$ffoto = mysqli_fetch_array($ff);
								if($ffoto['foto'] != ""){
									echo '<img src="pics_fotosnoticias/'.$list['id'].'/med_'.$ffoto['foto'].'" alt="'.$title.'" />';
								}
							}
						}
					}
					echo '</a></div></li>';
				}
				?>
			</ul>
		</div>
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