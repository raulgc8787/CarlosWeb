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
//End configurar


// ***
// Paginacion
// ***
$este_fichero = $_SERVER['PHP_SELF'];
$porpagina = 8;
if ((isset($_GET["pag"])) && (!isset($_POST["pagina"]))) $pagina= $_GET["pag"];
if (!isset($pagina)) $pagina=1;
$limite = " LIMIT ".(($pagina-1)*$porpagina).",".$porpagina;
// Consulta per saber el total de projectes que compleixen
$sql = "SELECT COUNT(*) FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE tc.".$pinta_listar." != '' AND t.checkbox_visible='1'";
if(isset($_GET['id']) && $_GET['id'] != ""){
	//$sql .= " AND t.select_ciclos=".$_GET['id'];
	$sql .= " AND t.select_categorias = ".$_GET['id'];
}
$resultpagina = db_query($link,$sql);
$linepagina = mysqli_fetch_array($resultpagina);
$total_buscats_paginas = $linepagina["COUNT(*)"];
// ***
// END Paginacion
// ***

include("auto_functions.php");

?>

<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_NEWS_TIT; ?></title>
<meta name="keywords" content="<?php echo METAS_KW; ?>" />
<meta name="description" content="<?php echo METAS_NEWS_DESC; ?>" />
<?php include("header.php");?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

</head>

<body class="listaNews <?php echo IDIOMA;?> noticias flex">
<?php include("body.php");?>

<div class="row">    

	<div class="container">
		
		<h1><?php echo NOTICIAS_H1; ?></h1>
		
		<div class="overflow">
		<div class="categorias">
		
			
		
			<ul>
				<li <?php if(!isset($_GET['id'])) echo 'class="current"';?>><a href="<?php echo $links['listado_noticias.php'];?>">Todo</a></li>
				<?php
				$c = mysqli_query($link,"SELECT * FROM categorias t, categorias_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' ORDER BY t.id ASC");
				while($categorias = mysqli_fetch_array($c)){
					echo '<li ';
					if($_GET['id'] == $categorias['id']) echo 'class="current"';
					echo '><a href="'.$links['listado_noticias.php'].'?id='.$categorias['id'].'">'.$categorias['text_titulo'].'</a></li>';
				}
				?>	
			</ul>
	

		</div></div>

		
		<div class="newslist">
			<ul>
			<?php
			if(!isset($_GET['id'])){
				$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.checkbox_visible='1' ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;
			} else{
				$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.checkbox_visible='1' AND t.select_categorias=".$_GET['id']." ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;
			}
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
		
		<?php
		// ***
		// Paginacion
		// ***
		$total_paginas = ceil($total_buscats_paginas/$porpagina);

		if($total_paginas > 1){

			$url = $links['este_fichero']."?";

			if(isset($_GET['id'])){
				$url = $links['este_fichero']."?id=".$_GET['id']."&";
			}
			
			paginacion($total_paginas,$pagina,$url);

		}
		// ***
		// END Paginacion
		// ***
		?>

		</div>
	</div>

</div>
<?php include("footer.php");?>
</body>
</html>
<?php include("bottom.php");?>