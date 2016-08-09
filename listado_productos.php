<?php 
include("head.php");

//Configurar
$table = "productos";
$table_content = $table."_content";
$hay_locationmap = 1; // 1 si hay mapa de localizacion
$sufijo_plural = "Productos";
$sufijo_singular = "Producto";
$campo_listar = "t.orden"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "DESC";
$pinta_listar = "text_titulo";
$pint2_listar = "text_intro";
$cols = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table;
$cols_content = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content;
//End configurar

// ***
// Paginacion
// ***
$este_fichero = $_SERVER['PHP_SELF'];
$porpagina = PORPAGINA;
if ((isset($_GET["pag"])) && (!isset($_POST["pagina"]))) $pagina= $_GET["pag"];
if (!isset($pagina)) $pagina=1;
$limite = " LIMIT ".(($pagina-1)*$porpagina).",".$porpagina;
// Consulta per saber el total de projectes que compleixen
$sql = "SELECT COUNT(*) FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE tc.".$pinta_listar." != '' AND t.checkbox_visible='1'";
$resultpagina = db_query($link,$sql);
$linepagina = mysqli_fetch_array($resultpagina);
$total_buscats_paginas = $linepagina["COUNT(*)"];
// ***
// END Paginacion
// ***

//Busco nom categoria
if(isset($_GET['id'])){
	$current_category = (int)$_GET['id'];
	$cate = db_query($link,"SELECT * FROM categorias_productos_content WHERE idioma='".IDIOMA."' AND id=".$current_category);
	$category = mysqli_fetch_array($cate);
	$catNombre = ": ".$category['text_nombre'];
} else{
	$catNombre = "";
}

include("auto_functions.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<title><?php echo METAS_PRODUCTS_TIT; ?></title>
<meta name="keywords" content="<?php echo METAS_KW; ?>" />
<meta name="description" content="<?php echo METAS_PRODUCTS_DESC;?>" />
<?php include("header.php");?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

</head>

<body class="empresa srvPage  <?php echo IDIOMA;?> productos flex">
<?php include("body.php");?>
  
<div class="popContact"><?php echo COMMON_CALL; ?> <br/><span class="num"><?php echo PHONE; ?></span></div>

<?php
$prefijo = "";
if($detect->isMobile()) $prefijo = "mob_";
?>
<div class="outtercbp">	
	<ul id="cbp-bislideshow" class="cbp-bislideshow">
		<li>
			<img src="images/branding/<?php echo $prefijo;?>45.jpg" alt="">
				<div class="container">
					<em><h1><?php echo EMPRESA_H1; ?></h1></em>
				</div>
		</li>
	</ul>
</div>

<div class="block1">
	<div class="intro">   
			<div class="columnsCSS">
				<img src="images/icons/qsom-icon-01.svg" alt="">
				<h2><?php echo EMPRESA_H2; ?></h2>
				<div class="text"><? echo EMPRESA_TEXTO  ; ?>
				<div class="align-C"><img class="signature" src="images/branding/firma.jpg" alt="<? echo BRAND; ?>"><br><? echo EMPRESA_TEXTO_SIGNATURE  ; ?></div>
				
			</div>



			</div><div class="clearfix"></div>
	</div>
</div>   

	<div class="block2">
		<div class="container">
			<h1><?php echo EQUIPO_H1; ?><?php echo $catNombre;?></h1>
				<div class="equipo">
						<ul>
					<?php
					if(isset($_GET['id'])){
						$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE tc.".$pinta_listar." != '' AND t.checkbox_visible='1' AND t.select_categorias_productos = '".(int)$_GET['id']."' ORDER BY ".$campo_listar." ".$orden_listar." ";
					} else{
						$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE tc.".$pinta_listar." != '' AND t.checkbox_visible='1' ORDER BY ".$campo_listar." ".$orden_listar." ";
					}
					
					$l = db_query($link,$listar);
					while($list = mysqli_fetch_array($l)){
						echo '<li>';

								$title = $list[$pinta_listar];
								$url = urls_amigables(strip_tags($title));
							    $url = $url."-p-".$list['id']."-".IDIOMA;			
								
								echo '<div class="portrait"><a href="'.$url.'" title="'.$title.'">';
								//Foto
								$r = db_query($link,"SELECT * FROM ".$table." WHERE id=".$list['id']);
								$registro = mysqli_fetch_array($r);
								if($registro['file_foto'] != ""){
									echo '<img src="images/'.$table.'/portrait'.$registro['file_foto'].'" alt="'.$title.'"  />';
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
												echo '<img src="pics_fotos'.$table.'/'.$list['id'].'/portrait'.$ffoto['foto'].'" alt="'.$title.'" />';
											}
										}
									}
								}
								echo '</a></div>';
								

								echo '<div class="desc"><h2><a href="'.$url.'" title="'.$title.'">'.$title.'</a></h2>';
								echo '<span class="textintro">'.$list[$pint2_listar].'</span>';
								if($list['text_link'] != ""){
									echo '<span class="linkedin"><a href="'.$list['text_link'].'" target="_blank"><img src="/images/icons/icon-linkedin-01.svg"></a></span>';
								}
								echo '</div></li>';
							}
							?>
							</ul>
				</div>
		</div>
	</div>
<div class="row">
	<div class="container principios">   
			<div class="columnsCSS">
			<? echo EMPRESA_PRINCIPIOS; ?>
			<div class="clearfix"></div>
	</div>
</div> 	

<div class="row">
	<div class="section"><h2><?php echo EMPRESA_SERVICIOS; ?></h2></div>
		<div class="serveis">   
			<div class="columnsCSS">
				<ul class="serveisList">
					<li>
						<img src="images/icons/serveis-02.svg" alt="<?php echo METAS_MATRIMONIOS_ALT; ?>"><h2><?php echo INDEX_BLOCK1; ?></h2>
						<div class="text"><?php echo INDEX_BLOCK1_TX; ?></div>
						<div class="mas"><a href="<?php echo $links['matrimonis.php'];?>" title="<?php echo METAS_MENU_MATRIMONIOS;?>">
						<?php echo COMMON_LEERMAS; ?></a></div>
					</li>
					<li>
						<img src="images/icons/serveis-01.svg" alt="<?php echo METAS_HERENCIAS_ALT; ?>"><h2><?php echo INDEX_BLOCK2; ?></h2>
						<div class="text"><?php echo INDEX_BLOCK2_TX; ?> </div>
						<div class="mas"><a href="<?php echo $links['herencia.php'];?>" title="<?php echo METAS_MENU_HERENCIAS;?>">
						<?php echo COMMON_LEERMAS; ?></a></div>
					</li>
					<li>
						<img src="images/icons/serveis-03.svg" alt="<?php echo METAS_BANCARI_ALT; ?>"><h2><?php echo INDEX_BLOCK3; ?></h2>
						<div class="text"><?php echo INDEX_BLOCK3_TX; ?> </div>
						<div class="mas"><a href="<?php echo $links['bancario.php'];?>" title="<?php echo METAS_MENU_BANCARI;?>">
						<?php echo COMMON_LEERMAS; ?></a></div>
					</li>
					<li>
						<img src="images/icons/serveis-04.svg" alt="<?php echo METAS_INMOBILIARI_ALT; ?>"><h2><?php echo INDEX_BLOCK4; ?></h2>
						<div class="text"><?php echo INDEX_BLOCK4_TX; ?> </div>
						<div class="mas"><a href="<?php echo $links['inmobiliari.php'];?>" title="<?php echo METAS_MENU_INMOBILIARI;?>">
						<?php echo COMMON_LEERMAS; ?></a></div>
					</li>
				</ul>

			</div>

			<div class="clearfix"></div>
		</div>
</div>

<div class="row">
	<div class="container">
		<div class="qSom">
			<h2><? echo EMPRESA_H2END; ?></h2>
			<div class="text"><? echo EMPRESA_ENDING; ?></div>
		</div>
		<div class="imagen"><img src="images/branding/BPM_Advocats036.jpg"></div>
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
