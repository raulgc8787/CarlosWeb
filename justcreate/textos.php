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

<script src="js/encoder.js" type="text/javascript"></script>
<script type="text/javascript">

function HTMLEncode(){var i=G('txtInput').value,db=G('chkDouble').checked;G('txtEncoded').value=Encoder.htmlEncode(i,db);}
function HTMLDecode(){var i=G('txtEncoded').value;G('txtDecoded').value=Encoder.htmlDecode(i);}

function textiny(id){

	tinymce.EditorManager.init({
		extended_valid_elements : "span"
	});
	tiene = $(".clase"+id).hasClass("editme");
	
	if(tiene == true){
		<?php
		$i = db_query($link,"SELECT * FROM config_idiomes");
		while($idiomas = mysqli_fetch_array($i)){
			$idioma = $idiomas['nom_idioma'];
			?>
			tinymce.EditorManager.execCommand('mceRemoveEditor',false, id+"_<?php echo $idioma;?>");
			<?php
		}
		?>
		$(".clase"+id).removeClass("editme");
	} else{
		<?php
		$i = db_query($link,"SELECT * FROM config_idiomes");
		while($idiomas = mysqli_fetch_array($i)){
			$idioma = $idiomas['nom_idioma'];
			?>
			//tinymce.EditorManager.execCommand('mceAddEditor',false, id+"_<?php //echo $idioma;?>");	
			var init_obj = {
				menubar:false,
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste <?php echo $es_blog;?>"
				],
				toolbar: "styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link | code",
				force_p_newlines : false,
				forced_root_block : '',
				extended_valid_elements : "span"
				
			};			
			new tinymce.Editor(id+"_<?php echo $idioma;?>", init_obj, tinymce.EditorManager).render();		
			<?php
		}
		?>
		$(".clase"+id).addClass("editme");
	}
}

function checkKey(){
	valor = $("#newkey").val();	
	$.ajax({
		type: "POST", 
		url: "do_checkkey.php",
		data: "valor="+valor,
		success: function(error) {		
			donde = "keyerrors";
			//window.alert(error);
			//no esta bien construido			
			if(error == "1"){
				pintaMensaje(donde,"El formato de la clave no es correcto, debe ser: PREFIJO_TXT")
			} 
			//ya existe
			else if(error == "2"){
				pintaMensaje(donde,"Ya existe una clave con este nombre!")
			}
			//todo ok
			else if(error == 0){
				addKey();
				//pintaMensaje(donde,"clave insertada")
			}
			
		}
	});
}

function addKey(){
	
	tinyMCE.triggerSave();
	
	valor = $("#newkey").val();
	anular = 0;
	
	//Si es una URL volem mirar que el text no tingui caracters estranys que trenquin el htaccess
	if(valor.indexOf(".") !=-1){
		
		strExpReg = /^[a-zA-Z0-9-]+$/;
		<?php
		$i = db_query($link,"SELECT * FROM config_idiomes");
		while($idiomas = mysqli_fetch_array($i)){
			$idioma = $idiomas['nom_idioma'];
			?>		
			if (!strExpReg.test($("#0_<?php echo $idioma;?>").val())){
				if(($("#0_<?php echo $idioma;?>").val() != "") && ($("#0_<?php echo $idioma;?>").val() != "index.html")){
					donde = "keyerrors";
					pintaMensaje(donde,"La url no es válida, contiene carácteres no válidos en el idioma: <?php echo $idioma;?>");
					//return false;
					//alert("no la pasa el idioma: <?php echo $idioma;?>");
					anular = 1;
				} 
				
			} else{
				//alert("La url del idioma <?php echo $idioma;?> es buena");
			}
			<?php
		}
		?>
	}
	
	if(anular == 1){
		return false;
	}
	
	$.ajax({
		type: "POST", 
		url: "do_addkey.php",
		data: {
			valor : valor,
			<?php
			$i = db_query($link,"SELECT * FROM config_idiomes");
			while($idiomas = mysqli_fetch_array($i)){
				$idioma = $idiomas['nom_idioma'];
				echo $idioma;?>: Encoder.htmlDecode($("#0_<?php echo $idioma;?>").val()),
				<?php
			}
			?>
			"0" : 0
		},
		success: function(url) {	
			window.location.href = url;	
		}
	});
}
function save(id){	

	tinyMCE.triggerSave();
	
	valor = $("a[name='row"+id+"']").text();
	anular = 0;
	
	//Si es una URL volem mirar que el text no tingui caracters estranys que trenquin el htaccess
	if(valor.indexOf(".") !=-1){
		
		strExpReg = /^[a-zA-Z0-9-]+$/;
		<?php
		$i = db_query($link,"SELECT * FROM config_idiomes");
		while($idiomas = mysqli_fetch_array($i)){
			$idioma = $idiomas['nom_idioma'];
			?>		
			if (!strExpReg.test($("#"+id+"_<?php echo $idioma;?>").val())){
				if(($("#"+id+"_<?php echo $idioma;?>").val() != "") && ($("#"+id+"_<?php echo $idioma;?>").val() != "index.html")){
					donde = "keyerrors";
					pintaMensaje(donde,"La url no es válida, contiene carácteres no válidos en el idioma: <?php echo $idioma;?>");
					//return false;
					//alert("no la pasa el idioma: <?php echo $idioma;?>");
					anular = 1;
				} 
				
			} else{
				//alert("La url del idioma <?php echo $idioma;?> es buena");
			}
			<?php
		}
		?>
	}
	
	if(anular == 1){
		return false;
	}
		
	//window.alert(datastring);
	$.ajax({
		type: "POST", 
		url: "do_save.php",
		data: {
			id : id,
			<?php
			$i = db_query($link,"SELECT * FROM config_idiomes");
			while($idiomas = mysqli_fetch_array($i)){
				$idioma = $idiomas['nom_idioma'];
				?>
				<?php echo $idioma;?>: Encoder.htmlDecode($("#"+id+"_<?php echo $idioma;?>").val()),
				<?php
			}
			?>
			"0" : 0
		},
		success: function(a) {	
			pintaMensajeMini('msg_'+id,guardado);  
		}
	});
}

function carga(url){
	$("#fake").load(url);

}

function reservanuevoRegistro(){
	reservanuevoRegistro = $(".nuevoRegistro").height()+50;
	$('#footer').css("height",reservanuevoRegistro);
}
$(document).ready(function(){
	reservanuevoRegistro();
});
</script>
</head>

<body>
<?php include("body.php");?>

<div class="section-header">
    <h1><?php echo  $backoffice['tit_gestion_de'][$_SESSION['bo_idioma']]." ".$sufijo_plural;?></h1>

	<!--GESTION DE TEXTOS: EXPORTAR, IMPORTAR, CONTROL-->
	<ul class="importexport-menu">
		<li><a href="textos_export.php" target="_blank"><?php echo $backoffice['exportar'][$_SESSION['bo_idioma']];?></a>
	    <?php
		if($_SESSION['rol'] == 0){
		?>
	     / <a href="textos_import.php" target="_self"><?php echo $backoffice['importar'][$_SESSION['bo_idioma']];?></a> /  <a onClick="carga('textos_control.php')" target="_blank"><?php echo $backoffice['control'][$_SESSION['bo_idioma']];?></a> / <a onClick="carga('textos_restore.php')" target="_blank"><?php echo $backoffice['restaurar'][$_SESSION['bo_idioma']];?></a>
	    <?php
		}
		?>
	</ul>
	<div id="fake"></div>
	<!--END GESTION DE TEXTOS-->


	<!--BUSCADOR-->
	<form method="post" action=""><input type="text" name="search" id="search" value="<?php echo $_POST['search'];?>" /><input type="submit" name="buscar" value="<?php echo $backoffice['btn_buscar'][$_SESSION['bo_idioma']];?>" class="busca"></form>
	<!--END BUSCADOR-->


	<!--LISTADO DE CLAVES-->
	<ul id="keys_menu">
		<li><?php echo $backoffice['filtrar_clave'][$_SESSION['bo_idioma']];?> </li>
		<?php
	    //Agrupo los prefijos de textos
	    $prefijos = array();
	    $it = 0;
	    $t = db_query($link,"SELECT * FROM textos ORDER BY text_nodisabled ASC");
	    while($txt = mysqli_fetch_array($t)){
	        $key = $txt['text_nodisabled'];
	        $prefijo = substr($key,0,strpos($key,"_"));
			$punto = @strpos($key,".",1);
			if($punto < 1){
				if(in_array($prefijo,$prefijos, true) == false){
					$prefijos[$it] = $prefijo;
					$it++;
				}
			}
	    }

	    foreach($prefijos as $key=>$value){
	    	if($value!=''){	
	        	echo '<li><a href="?key='.$value.'">'.$value.'</a></li>';
	    	}
	    }
	    ?>
	    
	    <?php
		//Solo nosotros podemos modificar urls, asi como crear claves
		if($_SESSION['rol'] == 0){
		?>
	        <li><a href="?key=url">urls</a></li>
	        <li><a href="textos.php"><?php echo $backoffice['crear_clave'][$_SESSION['bo_idioma']];?></a></li>
	    <?php
		}
		?>
	</ul>
	<!--END LISTADO DE CLAVES-->

</div>




<table>
	<?php
	//Consulta standard
    $sub = " WHERE 1=0";
	
	//Buscamos por clave
    if(isset($_GET['key'])){
        $sub = " WHERE text_nodisabled LIKE '".$_GET['key']."\_%' AND text_nodisabled NOT LIKE '%.%' ";
		if($_GET['key'] == "url"){
			$sub = " WHERE text_nodisabled LIKE '%.%' ";
		}
		$sql = "SELECT * FROM textos ".$sub." ORDER BY text_nodisabled ASC";
    }
	
	//Buscamos por el buscador
	if(isset($_POST['search'])){
		$sub = "WHERE (t.text_nodisabled LIKE '%".$_POST['search']."%' OR tc.textarea_titulo LIKE '%".$_POST['search']."%')";
		$sql = "SELECT * FROM textos t LEFT JOIN textos_content tc ON (t.id = tc.id) ".$sub." GROUP BY t.id ORDER BY t.text_nodisabled ASC";
	} 
	
	//Creando clave
	else{
		$sql = "SELECT * FROM textos ".$sub." ORDER BY text_nodisabled ASC";
	}
	
	//Ejecutamos la consulta    
    $t = db_query($link,$sql);
    
	while($textos = mysqli_fetch_array($t)){	
		 
		$i = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
		$class_textarea = "mceNoEditor";
		$checked = "";
		
		while($idiomas = mysqli_fetch_array($i)){
		
			$v = db_query($link,"SELECT * FROM textos_content WHERE id=".$textos['id']." AND idioma='".$idiomas['nom_idioma']."'");
			$valor = mysqli_fetch_array($v);
		
			if(strlen($valor['textarea_titulo']) != strlen(strip_tags($valor['textarea_titulo']))){
				
				$class_textarea = "editme";
				$checked = "checked";
				
			}	
		
		}
		
		
		echo '<tr style="padding-top: 20px; border-top: 1px dotted #ccc; margin-top: 50px;"><td class="txt_key" ><a name="row'.$textos['id'].'">'.$textos['text_nodisabled'].'</a> <a href="?key='.$_GET['key'].'&delete='.$textos['id'].'" onclick="return confirm(\''.$backoffice['alerta_borrar'][$_SESSION['bo_idioma']].'\')" class="delete">'.$backoffice['borrar'][$_SESSION['bo_idioma']].'</a>';
		if($_GET['key'] != 'url'){
			echo '<br /><span><input type="checkbox" name="check'.$textos['id'].'" id="check'.$textos['id'].'" value="1" '.$checked.' onclick="textiny('.$textos['id'].')" > '.$backoffice['texto_enriquecido'][$_SESSION['bo_idioma']].'</span>';
		}
		echo '</td><td width="58%">';

		$i = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
		
		while($idiomas = mysqli_fetch_array($i)){		
		
			$v = db_query($link,"SELECT * FROM textos_content WHERE id=".$textos['id']." AND idioma='".$idiomas['nom_idioma']."'");
			$valor = mysqli_fetch_array($v);
			
			echo '<strong>'.$idiomas['nom_idioma'].'</strong><textarea class="clase'.$textos['id'].' '.$class_textarea.'" ="'.$textos['id'].'_'.$idiomas['nom_idioma'].'" id="'.$textos['id'].'_'.$idiomas['nom_idioma'].'">'.$valor['textarea_titulo'].'</textarea><br />';
			 
		}
		
        echo '</td><td width="170" valign="bottom">
				<span id="msg_'.$textos['id'].'" class="mensajes"></span><input type="button" onclick="save('.$textos['id'].')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'">
			  </td></tr>';
    }
	
	//Crear nueva clave
	$value = "";
	if($_GET['key'] != "" && $_GET['key'] != "url") $value = $_GET['key']."_";
    ?>
    
	<?php
	//Solo nosotros podemos crear claves
	if($_SESSION['rol'] == 0){
	?>
    <tr class="tbJustcreate">
    <td valign="top" style="border-top: 0; padding-top: 30px;">
    	<input type="text" name="newkey" id="newkey" value="<?php echo $value;?>" style="width:250px;" />
        <br /><br  /><!-- <span><input class="none" type="checkbox" name="check0" id="check0" value="1" onClick="textiny(0)" style="float:left;"><?php echo $backoffice['texto_enriquecido'][$_SESSION['bo_idioma']];?></span> -->
        <br /><div id="keyerrors"></div>
    </td>
    <td class="none">
    <?php
	$i = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
	while($idiomes = mysqli_fetch_array($i)){
		echo '<b>'.$idiomes['nom_idioma'].'</b><textarea class="clase0 mceNoEditor textarea_small" ="0_'.$idiomes['nom_idioma'].'" id="0_'.$idiomes['nom_idioma'].'"></textarea><br />';
	}
	?>
    </td>
    <td valign="top" style="border-top: 0;"><input type="button" class="addkey" value="<?php echo $backoffice['crear_registro'][$_SESSION['bo_idioma']];?>" onClick="checkKey();" /></td>
    </tr>
    <?php
	}
	?>
</table>



<?php include("footer.php");?>



</body>
</html>
<?php include("bottom.php");?>