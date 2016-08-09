<?php 
include("head.php");

//Configurar
$table = "productos";
$table_content = $table."_content";
$sufijo_plural = "Productos";
$sufijo_singular = "Producto";
$campo_listar = "t.orden"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "DESC";
$pinta_listar = "text_titulo";
$pint2_listar = "textarea_intro";
$cols = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table;
$cols_content = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content;
//End configurar

$id = $_GET['id'];
$_SESSION['id'] = $id;
	
$e = db_query($link,"SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".IDIOMA."') WHERE t.id='".$id."'");
$element = mysqli_fetch_array($e);

$ht_titulo = $element['text_title'];
$ht_description = html_entity_decode(strip_tags($element['text_description']), ENT_COMPAT, 'UTF-8');

$ht_imagen = 'http://'.$_SERVER['HTTP_HOST'].''.dirname($_SERVER['SCRIPT_NAME']).'/images/logos/logo.png';

//Busco nom categoria
if((int)$element['select_categorias_productos']>0){
	$current_category = (int)$element['select_categorias_productos'];
	$cate = db_query($link,"SELECT * FROM categorias_productos_content WHERE idioma='".IDIOMA."' AND id=".(int)$element['select_categorias_productos']);
	$category = mysqli_fetch_array($cate);
	$catNombre = ": ".$category['text_nombre'];
} else{
	$catNombre = "";
}

include("auto_functions.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo strip_tags($ht_titulo);?></title>
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

<body class="cv empresa  <?php echo IDIOMA;?> productos flex">
<?php include("body.php");?>
            
<div class="block2">
        <div class="container">
                <div class="equipo">
                        <ul>
                            <li><div class="portrait">
                            <img src="images/productos/portrait<?php echo $element['file_foto'];?>" alt="<?php pinta('text_titulo');?>"  />
                            </div>
                            <div class="desc">
                            <h2><?php pinta('text_titulo');?></h2>
                            <span class="textintro"><?php pinta('text_intro');?></span>
                            <span class="linkedin"><a href="<?php pinta ('text_link'); ?>" target="_blank"><img src="/images/icons/icon-linkedin-01.svg"></a>
                            </span>
                            </div>
                            </li>
                       </ul>
                       <div class="subcontainer"><?php pinta('textarea_texto');?></div>
                </div>
        </div>
        <div class="row center"><span class="button smallest link"><a href="<?php echo $links['listado_productos.php'];?>" title="<?php echo METAS_MENU_EMPRESA_TIT;?>" title=""><?php echo COMMON_VOLVER; ?></a></span></div>
    </div>


<!--             <p><?php pinta('textarea_texto');?></p>



            
            <h1><?php pinta('text_titulo');?></h1>

            <h2><?php pinta('text_intro');?></h2>

            <?php pinta('file_foto');?> -->
    

</div>

<?php include("footer.php");?>

</body>
</html>
<?php include("bottom.php");?>