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
$limite = "LIMIT 0,4";
//End configurar

?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_HOME_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_HOME_DESC;?>" />

<?php include("header.php");?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

</head>

<body class="home <?php echo IDIOMA;?> flex">

<?php include("body.php");?>

<div class="popContact"><?php echo COMMON_CALL; ?><br/><span class="num"><?php echo PHONE; ?></span></div>

<div class="outtercbp">	
<ul id="cbp-bislideshow" class="cbp-bislideshow">


<?php
	            if(!$detect->isMobile() || ($detect->isMobile() && $detect->isTablet() && MENU_TABLET_DESKTOP == 1)){
	            
	$s = mysqli_query($link,"SELECT * FROM fotosslide WHERE id_elem=1 AND idioma='".IDIOMA."' ORDER BY orden ASC");
	while($slide = mysqli_fetch_array($s)){
		echo '<li><a href="'.$slide['link'].'"><img src="pics_fotosslide/1/'.$slide['foto'].'" alt="'.$slide['nombre'].'"><div class="container"><em>'.$slide['nombre'].'</em></div></a></li>';
	}
	?>
	     
	         <?php
	            }
	            else {
	            ?>
					<li><img src="images/branding/mob_esbpmadvocats010.jpg" alt=""><div class="container"><em><?php echo MOBILE_CLAIM; ?></em></div></li>
					
	         <?php
	            } 
	     
    		?>




</ul>
<div id="cbp-bicontrols" class="cbp-bicontrols">
	<span class="cbp-biprev cbp-controls"></span>
	<span class="cbp-binext cbp-controls"></span>
</div>
</div>
 
<div class="block1">
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

			<h1><?php echo INDEX_H1; ?></h1>
	</div>
</div>
<div class="row">
	<div class="container">
		<div class="imagen"><img src="images/branding/equipo.jpg" alt="<?php echo METAS_HOMEEMPRESA_ALT; ?>"></div>
		<div class="qSom">
			<img src="images/icons/serveis-05.svg" alt="<?php echo METAS_HOMEEMPRESA_ALT2; ?>">
			<h2><?php echo INDEX_H2; ?></h2>
			<div class="text"><?php echo INDEX_H2_TX; ?> </div>
			<div class="mas"><a href="<?php echo $links['listado_productos.php'];?>" title="<?php echo METAS_MENU_EMPRESA_TIT;?>"><?php echo COMMON_LEERMAS; ?></a></div>
		</div>
		
	</div>
</div>

<div class="row newsList">
	<div class="container">
		<div class="row"> <div class="lastNews"><img src="images/icons/ico-news-01.svg" alt="<?php echo METAS_NOTICIAS_ALT; ?>"><h2 class="lastNews"><?php echo INDEX_H2_NW; ?></h2></div></div>
		<div class="columnsCSS">
			<ul>
			<?php
				$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.checkbox_visible='1' AND  t.checkbox_portada='1' ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;

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
		<div class="row"><span class="button smallest link"><a href="<?php echo $links['listado_noticias.php'];?>" title="<?php echo METAS_MENU_NOTICIAS_TIT;?>"><?php echo COMMON_BT_NWS; ?></a></span></div>
	</div>
</div>


<?php include("footer.php");?>
<!-- ///// cbp-bislideshow ///// -->
<script src="js/modernizr.custom.js"></script>
<script src="js/jquery.imagesloaded.min.js"></script>
<script src="js/cbpBGSlideshow.js"></script>

<script>
$(document).ready(function() {
	
    heightAll('#cbp-bislideshow');

})


</script>

</body>
</html>
<?php include("bottom.php");?>