<?php 
include("head.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ES">
<head>
<title>Backoffice</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<?php 
// CONFIGURAR
$table = "textosbackoffice";
$hay_fotouploader = 0; // 1 si hay fotouploader y/o fileuploader
$hay_locationmap = 0; // 1 si hay mapa de localizacion
$no_borrar = 0; // 1 si no se pueden borrar items
$es_blog = ""; //Vacio si no se suben fotos dentro del texto, 'jbimages' si se puede hacer
$ordenable = 0; //1 si se pueden ordenar los items
$sufijo_plural = "textos";
$sufijo_singular = "texto";
$campo_listar = "t.text_titulo"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "DESC";
$pinta_listar = "text_titulo";
// END CONFIGURAR

include("header.php");?>
</head>

<body>
<?php include("body.php");?>

<?php include("form_common.php");?>

<?php include("footer.php");?>
</body>
</html>
<?php include("bottom.php");?>