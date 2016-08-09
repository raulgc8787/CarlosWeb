<?php 
include("head.php");

if(isset($_GET['delete'])){
	$dc = db_query($link,"DELETE FROM config_thumbs WHERE id=".$_GET['delete']);
	header("Location: thumbs.php");
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
$table = "config_thumbs";
$campo_listar = "t.id"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "ASC";
$pinta_listar = "prefix_thumbnail";
$sufijo_plural = "thumbs";
$sufijo_singular = "thumb";
// END CONFIGURAR

include("header.php");
?>

<script>
function addKey(){
	
	valor = $("#newkey").val();
	$.ajax({
		type: "POST", 
		url: "do_addkeyth.php",
		data: {
			prefix: $("#prefix").val(),
			mida: $("#mida").val(),
			tipo: $('input[name=tipo]:checked', '#miForm').val()
		},
		success: function(url) {	
			window.location.href = url;	
		}
	});
}

function save(id){	

	$.ajax({
		type: "POST", 
		url: "do_saveth.php",
		data: {
			id : id,
			mida: $("#mida"+id).val(),
			tipo: $('input[name=tipo' + id + ']:checked', '#miForm').val()
		},
		success: function(a) {	
			pintaMensajeMini('msg_'+id,guardado);  
		}
	});
}
</script>

</head>

<body>
<?php include("body.php");?>

<div class="section-header">
    <h1><?php echo  $backoffice['tit_gestion_de'][$_SESSION['bo_idioma']]." ".$sufijo_plural;?></h1>
    
</div>



<form id="miForm">
<table>
	<?php
    $sql = "SELECT * FROM ".$table." t ORDER BY ".$campo_listar." ".$orden_listar;

    $t = db_query($link,$sql);
    while($thumbs = mysqli_fetch_array($t)){	
        
		echo '<tr><td class="txt_key"><a name="row'.$thumbs['id'].'">'.$thumbs['text_prefix'].'</a>';
		
		echo '</td><td valign="top"><input type="text" id="mida'.$thumbs['id'].'" name="mida'.$thumbs['id'].'" value="'.$thumbs['text_mida'].'"></td><td width="58%">';

		echo '<table><tr>';
		
		$tt = db_query($link,"SELECT * FROM config_tipothumb ORDER BY id ASC");
		while($tipo = mysqli_fetch_array($tt)){
			echo '<td><input type="radio" name="tipo'.$thumbs['id'].'" value="'.$tipo['id'].'" ';
			if($thumbs['select_tipothumb'] == $tipo['id']) echo ' checked="checked" ';
			echo '>'.$tipo['text_titulo'].'</td>';
		}

		echo '</tr></table>';

        echo '</td><td width="170" valign="bottom">
				<span id="msg_'.$thumbs['id'].'" class="mensajes"></span><input type="button" onclick="save('.$thumbs['id'].')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'">
			  </td></tr>';
    
	}
	?>
    <tr class="nuevoRegistro">
    <td valign="top">
    	<span><label>prefix:</label>
    	<input type="text" name="prefix" id="prefix" value="<?php echo $value;?>" style="width:100px;" />       
        </span>
        <span>
        <label>mida: </label>
        <input type="text" name="mida" id="mida" value="<?php echo $value;?>" style="width:100px;" />        
        </span>
        <br /><div id="keyerrors"></div>
    </td>
    <td>
    	<table><tr>
        <?php
		$tt = db_query($link,"SELECT * FROM config_tipothumb ORDER BY id ASC");
		while($tipo = mysqli_fetch_array($tt)){
			echo '<td><input type="radio" name="tipo" value="'.$tipo['id'].'" >'.$tipo['text_titulo'].'</td>';
		}
		?>
    	</tr></table>
    </td>
    <td valign="top"><input type="button" class="addkey" value="<?php echo $backoffice['crear_registro'][$_SESSION['bo_idioma']];?>" onclick="addKey()" /></td>
    </tr>
</table>
</form>


<?php include("footer.php");?>



</body>
</html>
<?php include("bottom.php");?>