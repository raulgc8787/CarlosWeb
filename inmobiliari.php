<?php 
include("head.php");

//Configurar
$table = "noticias";
$table_content = $table."_content";
$hay_locationmap = 1; // 1 si hay mapa de localizacion
$sufijo_plural = "Noticias";
$sufijo_singular = "Noticia";
$campo_listar = "t.text_fecha"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "DESC";
$pinta_listar = "text_titulo";
$pint2_listar = "textarea_intro";
$cols = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table;
$cols_content = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content;
$limite = "LIMIT 0,2";
//End configurar

?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_INMOBILIARI_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_INMOBILIARI_DESC;?>" />

<?php include("header.php");?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

</head>

<body class="srvPage inmobiliari <?php echo IDIOMA;?> flex">

<?php include("body.php");?>

<div class="popContact"><?php echo COMMON_CALL; ?> <br/><span class="num"><?php echo PHONE; ?></span></div>

<?php
$prefijo = "";
if($detect->isMobile()) $prefijo = "mob_";
?>


<div class="outtercbp">	
	<ul id="cbp-bislideshow" class="cbp-bislideshow">
		<li>
			<img src="images/branding/<?php echo $prefijo;?>36.jpg">
				<div class="container">
					<em><h1><?php echo INMOBILIARI_H1; ?></h1></em>
				</div>
		</li>
	</ul>
</div>

 
<div class="block1">
	<div class="intro">   
			<div class="columnsCSS">
				<img src="images/icons/serveis-04.svg" alt="<?php echo METAS_INMOBILIARI_ALT;?>">
				<h2><?php echo INMOBILIARI_H2; ?></h2>
				<div class="text"><? echo INMOBILIARI_INTRO ; ?></div>

			</div><div class="clearfix"></div>
	</div>
</div>

<div class="row">
	<div class="container">
		<div class="serveis">
			<?php
			$idCategoria = 5;
			$s = mysqli_query($link,"SELECT * FROM servicios t, servicios_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.select_categoriass=".$idCategoria);
			while($servicios = mysqli_fetch_array($s)){
				$url = urls_amigables($servicios['text_url']);
				$url = $url."-s-".$servicios['id']."-".IDIOMA;	
				echo ' <a href="'.$url.'" title="'.$servicios['text_title'].'"><div class="item"><div class="element">
		   <h2>'.$servicios['text_titulo'].'</h2></div></div></a>';
			}
			?> 
		</div>
	</div>
</div>

<div class="row FAQ">
	<div class="container">
	<h3><?php echo INMOBILIARI_H3; ?></h3>
		<ul>
			<?php
					$idCategoria = 5;
					$f = mysqli_query($link,"SELECT * FROM faq t, faq_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.select_categoriass=".$idCategoria);
					$faq = mysqli_fetch_array($f);
					for($i=1; $i<= 50; $i++) {
						if ($faq['text_titulo'.$i] != "" && $faq['textarea_texto'.$i] != "") {
					    	echo '<li>'.$faq['text_titulo'.$i].'</li>';
					    	echo '<li>'.$faq['textarea_texto'.$i].'</li>';
					    } else {
					    	continue;
					    }
					}
					?> 
		</ul>
	</div>
</div>
<div class="row newsList">
	<div class="container">
		<div class="row"> <div class="lastNews"><img src="images/icons/ico-news-01.svg" alt="<?php echo METAS_NOTICIAS_ALT; ?>"><h2 class="lastNews"><?php echo SERVICIOS_EXITO; ?></h2></div></div>
		<div class="columnsCSS">
			<ul>
			<?php
				$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.checkbox_visible='1' AND t.select_categorias=".$idCategoria." ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;

				$l = db_query($link,$listar);
				while($list = mysqli_fetch_array($l)){
					echo '<li> <div class="news">';
							
							$title = $list[$pinta_listar];
							$url = urls_amigables($title);
				   			$url = $url."-n-".$list['id']."-".IDIOMA;	
						echo '<div class="date"> '.fechaEspExt($list['text_fecha']).'</div>';
						echo '<div class="title"><div class="element"><h2><a href="'.$url.'" title="'.$title.'">'.$title.'</a></h2></div></div>';
						echo '<div class="mas"><a href="'.$url.'" title="'.$title.'">'.COMMON_VERMAS.'</a></div></div>';

							
					
					echo '<div class="imagen imagen2"><a href="'.$url.'" title="'.$title.'">';
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
			<div class="row"><span class="button smallest link"><a href="<?php echo $links['listado_noticias.php'];?>?id=<?php echo $idCategoria;?>" title="<?php echo METAS_MENU_NOTICIAS_TIT;?>"><?php echo COMMON_BT_CASOS; ?></a></span></div>
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
    margin: 15,
    loop: true,
    nav:true,
    navText: [
      "",
      ""
      ],
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      1000: {
        items: 3
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