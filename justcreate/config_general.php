<?php 
include("head.php");

if(isset($_GET['delete'])){
	$dc = db_query($link,"DELETE FROM config_general WHERE id=".$_GET['delete']);
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
$table = "config_general";
$campo_listar = "t.name"; //t.campo si es de la tabla 'normal' y tc.campo si es de la tabla 'content'
$orden_listar = "ASC";
$pinta_listar = "prefix_thumbnail";
$sufijo_plural = "thumbs";
$sufijo_singular = "thumb";
// END CONFIGURAR

include("header.php");
?>

<script type="text/javascript">
function addKey(){
	
	valor = $("#newkey").val();
	$.ajax({
		type: "POST", 
		url: "do_addkeygeneral.php",
		data: {
			name: $("#name").val(),
			value: $("#value").val()
		},
		success: function(url) {	
			window.location.href = url;	
		}
	});
}

function save(id){	
	
	$.ajax({
		type: "POST", 
		url: "do_savegeneral.php",
		data: {
			name: $("#name"+id).val(),
			value: $("#value"+id).val()
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
    while($config_keys = mysqli_fetch_array($t)){	
        
		echo '<tr><td class="txt_key"><a name="row'.$config_keys['id'].'">'.$config_keys['name'].'</a>';
		
		echo '</td><td valign="top">';
		
		echo '<input type="hidden" id="name'.$config_keys['id'].'" name="name'.$config_keys['id'].'" value="'.$config_keys['name'].'">';
		
		echo '<input type="text" id="value'.$config_keys['id'].'" name="value'.$config_keys['id'].'" value="'.$config_keys['value'].'">';
		
		echo '</td><td width="58%">';

        echo '</td><td width="170" valign="bottom">
				<span id="msg_'.$config_keys['id'].'" class="mensajes"></span><input type="button" onclick="save('.$config_keys['id'].')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'">
			  </td></tr>';
    
	}
	?>
    <tr class="nuevoRegistro">
    <td valign="top">
    	<span><label>key:</label>
    	<input type="text" name="name" id="name" style="width:100px;" />       
        </span>
        <span>
        <label>value: </label>
        <input type="text" name="value" id="value" style="width:100px;" />        
        </span>
        <br /><div id="keyerrors"></div>
    </td>
    <td valign="top"><input type="button" class="addkey" value="<?php echo $backoffice['crear_registro'][$_SESSION['bo_idioma']];?>" onclick="addKey()" /></td>
    </tr>
</table>
</form>


<?php include("footer.php");?>



</body>
</html>
<?php include("bottom.php");?>