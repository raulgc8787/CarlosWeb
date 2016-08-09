<?php 

include("head.php");

if(isset($_GET['delete'])){
	$dc = db_query($link,"DELETE FROM textos_content WHERE id=".$_GET['delete']);
	$d = db_query($link,"DELETE FROM textos WHERE id=".$_GET['delete']);
	header("Location: textos.php?key=".$_GET['key']);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ES">
<head>
<title>Backoffice</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<?php 
// CONFIGURAR
$table = "textos";
//$max_fotos = 10;
$max_fotos['fotosa'] = 10; //Máximo de fotos que puede tener la cosa del uploader
$max_fotos['fotosb'] = 5; //Máximo de fotos que puede tener la cosa del uploader
$hay_fotouploader = 0; // 1 si hay fotouploader y/o fileuploader
$hay_locationmap = 0; // 1 si hay mapa de localizacion
$no_borrar = 0; // 1 si no se pueden borrar items
$sufijo_plural = "textos";
$sufijo_singular = "texto";
$campo_listar = "t.text_nodisabled"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "ASC";
$pinta_listar = "text_nodisabled";
// END CONFIGURAR

include("header.php");?>

<script language="javascript">
//Importar excel ->
function importar_excel(){
	
	$('#result_importar').load('ajax_textos_import.php',function(){
	
		//Subida del fichero excel ->
		var inputFile = document.getElementById('fichero_excel');
		var file = inputFile.files[0];
		var data = new FormData();
		
		data.append('fichero_excel',file);
		
		//Añado el resto de los campos a subir ->
		data.append('form','form');
		
		$.ajax({
		
			url: 'ajax_textos_import.php',
			type: 'POST',
			contentType: false,
			data: data,
			processData: false,
			cache: false,
			success: function(data) {

				$('#content_copiando_fichero').html(data);
		
			}
		
		});
	
	});

}

</script>
</head>

<body>
<?php include("body.php");?>

<div class="section-header">
    <h1><?php echo  $backoffice['tit_gestion_de'][$_SESSION['bo_idioma']]." ".$sufijo_plural;?></h1>

	<!--GESTION DE TEXTOS: EXPORTAR, IMPORTAR, CONTROL-->
	<div id="fake"></div>
	<!--END GESTION DE TEXTOS-->
</div>
<table>
<tr>
<td><input name="fichero_excel" type="file" id="fichero_excel" /></td>
<td><input type="button" onclick="importar_excel();" border="0" value="Importar"></td>
</tr>
</table>
<div id="result_importar" style="display:block; width:100%; height:450px; overflow:auto; border:1px solid #dddddd; border-radius:10px; background-color:#ffffff; margin:15px 0 15px 0; font-size:1em;">&nbsp;</div>

<?php include("footer.php");?>



</body>
</html>
<?php include("bottom.php");?>