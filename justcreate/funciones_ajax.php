<?php 
session_start();
// Incluyo las funciones basicas
include "../files/config.php";
include "../files/funciones.php";
// Arranque BBDD
$link = conectar();

global $backoffice;

$funcion = $_POST['funcion'];

switch($funcion){
	
	// LISTA DE FUNCIONES
	// cambiaIdioma(idioma)
	// eliminarFotoUploader(tabla,id_elem,id,idioma)
	// eliminarFicheroUploader(tabla,id_elem,id,idioma)
	// eliminarFichero(tabla,id,idioma,nombre_fichero,donde)
	// eliminarFoto(tabla,id,idioma,nombre_fichero,donde)
	// guardaNombre(tabla,id,registro,idioma,valor)
	// cargaUploader(tabla,id,idioma)
	// creaRegCheckM(tanla,valor,marcados)
	// cambiaStatus(tabla,campo,valor,id)
	
	case "cambiaIdioma":
		
		$idioma = $_POST['idioma'];
		$_SESSION['idioma'] = $idioma;
	
		break;
	
	
	case "eliminarFotoUploader":
	
		$tabla = $_POST['tabla'];
		$id_elem = $_POST['id_elem'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		
		//buscamos el nombre de la foto
		if($idioma == ""){
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		} else{
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." AND idioma='".$idioma."' LIMIT 1");
		}
		$ar_nf = mysqli_fetch_array($nf);		
		$foto = $ar_nf['foto'];
		$orden = $ar_nf['orden'];
		
		//Buscamos todas las versiones que tendra la foto y las eliminamos
		$c = db_query($link,"SELECT * FROM config_thumbs ct LEFT JOIN config_tipothumb ctt ON (ctt.id = ct.select_tipothumb) ORDER BY ct.id ASC");
		while($con = mysqli_fetch_array($c)){
			$prefix_thumbnail = $con['text_prefix'];
			@unlink('../pics_'.$tabla.'/'.$id_elem.'/'.$prefix_thumbnail.''.$foto);
		}
		@unlink('../pics_'.$tabla.'/'.$id_elem.'/'.$foto);
	
		//Borramos el registro de la foto y reasignamos el orden
		$borra_reg = db_query($link,"DELETE FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		$update_order = db_query($link,"UPDATE ".$tabla." SET orden=orden-1 WHERE orden > ".$orden." AND id_elem=".$id_elem."");	
		
		//Pinto la lista
		$it = 1;
		//Leo las fotos en el orden en que estan guardades en la DB
		$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND idioma='".$idioma."' ORDER BY orden ASC");
		while($ar_fotos = mysqli_fetch_array($fotos)){
			 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
					<img src="../pics_'.$tabla.'/'.$id_elem.'/th_'.$ar_fotos['foto'].'" class="fotos_subidas" />
					<a class="eliminar" onclick="eliminarFotoUploader(\''.$tabla.'\','.$id_elem.','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
				  </li>';
			$it++;
		}
		
		break;


	case "eliminarBannUploader":
	
		$tabla = $_POST['tabla'];
		$id_elem = $_POST['id_elem'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		
		//buscamos el nombre de la foto
		if($idioma == ""){
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		} else{
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." AND idioma='".$idioma."' LIMIT 1");
		}
		$ar_nf = mysqli_fetch_array($nf);		
		$foto = $ar_nf['foto'];
		$orden = $ar_nf['orden'];
		
		//Buscamos todas las versiones que tendra la foto y las eliminamos
		$c = db_query($link,"SELECT * FROM config_thumbs ORDER BY id ASC");
		while($con = mysqli_fetch_array($c)){
			$prefix_thumbnail = $con['prefix_thumbnail'];
			@unlink('../pics_'.$tabla.'/'.$id_elem.'/'.$prefix_thumbnail.''.$foto);
		}
		@unlink('../pics_'.$tabla.'/'.$id_elem.'/'.$foto);
	
		//Borramos el registro de la foto y reasignamos el orden
		$borra_reg = db_query($link,"DELETE FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		$update_order = db_query($link,"UPDATE ".$tabla." SET orden=orden-1 WHERE orden > ".$orden." AND id_elem=".$id_elem."");	
		
		//Pinto la lista
		$it = 1;
		//Leo las fotos en el orden en que estan guardades en la DB
		$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND idioma='".$idioma."' ORDER BY orden ASC");
		while($ar_fotos = mysqli_fetch_array($fotos)){
			/*echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
					<img src="../pics_'.$tabla.'/'.$id_elem.'/th_'.$ar_fotos['foto'].'" class="fotos_subidas" />
					<a class="eliminar" onclick="eliminarFotoUploader(\''.$tabla.'\','.$id_elem.','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
				  </li>';*/
			echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
					<img src="../pics_'.$tabla.'/'.$_SESSION['id'].'/th_'.$ar_fotos['foto'].'" class="fotos_subidas" />
					<span>Texto:</span> <input type="text" name="'.$tabla.$ar_fotos['id'].$idioma.'" id="'.$tabla.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$tabla.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
					<input type="button" class="guardar" onclick="guardaNombre(\''.$tabla.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" value="Guardar" />
					<span id="msg_'.$tabla.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
					<a class="eliminar" onclick="eliminarBannUploader(\''.$tabla.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
					<span>Link:</span> <input type="text" name="'.$tabla.$ar_fotos['id'].$idioma.'l" id="'.$tabla.$ar_fotos['id'].$idioma.'l" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$tabla.$ar_fotos['id'].$idioma.'l\').focus()" value="'.$ar_fotos['link'].'" />
					<input type="button" class="guardar" onclick="guardaLink(\''.$tabla.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'l\')" value="Guardar" />
					<span id="msg_'.$tabla.$ar_fotos['id'].$idioma.'l" class="mensajes"></span>
				  </li>';
			$it++;
			$it++;
		}
		
		break;
		
		
	case "eliminarFicheroUploader":
	
		$tabla = $_POST['tabla'];
		$id_elem = $_POST['id_elem'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		
		//buscamos el nombre de la foto
		if($idioma == ""){
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		} else{
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." AND idioma='".$idioma."' LIMIT 1");
		}
		$ar_nf = mysqli_fetch_array($nf);		
		$foto = $ar_nf['foto'];
		$orden = $ar_nf['orden'];
		
		//Buscamos todas las versiones que tendra la foto y las eliminamos
		@unlink('../downloads/'.$id_elem.'/'.$foto);
	
		//Borramos el registro de la foto y reasignamos el orden
		$borra_reg = db_query($link,"DELETE FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		$update_order = db_query($link,"UPDATE ".$tabla." SET orden=orden-1 WHERE orden > ".$orden." AND id_elem=".$id_elem."");	
		
		//Pinto la lista
		$it = 1;
		//Leo las fotos en el orden en que estan guardades en la DB
		$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND idioma='".$idioma."' ORDER BY orden ASC");
		$cuantasf = mysqli_num_rows($fotos);
		if($cuantasf > 0){
			while($ar_fotos = mysqli_fetch_array($fotos)){
				 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
						<a href="../downloads/'.$id.'/'.$ar_fotos['foto'].'" target="_blank">'.$ar_fotos['foto'].'</a>
						<input type="text" name="'.$tabla.$ar_fotos['id'].$idioma.'" id="'.$tabla.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$tabla.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
						<input type="button" class="guardar" onclick="guardaNombre(\''.$tabla.'\','.$id_elem.','.$ar_fotos['id'].',\''.$idioma.'\')" value="Guardar" />
						<span id="msg_'.$tabla.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
						<a class="eliminar" onclick="eliminarFicheroUploader(\''.$tabla.'\','.$id_elem.','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
					  </li>';
				$it++;
			}
		} else{
			echo '<li>'.$backoffice['noficheros_actualmente']['es'].'</li>';
		}
		
		break;
	
	
	case "eliminarFichero":
	
		$tabla = $_POST['tabla'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		$nombre_fichero = $_POST['nombre_fichero'];
		
		//Busco info del fichero
		$c = "SELECT * FROM ".$tabla." WHERE id=".$id;
		if($idioma != ""){
			$c .= " AND idioma='".$idioma."' ";
		}
		//echo $c;
		$i = db_query($link,$c);
		$info = mysqli_fetch_array($i);
		
		$fichero_en_si = $info['file_'.$nombre_fichero];
		
		//Elimino el registro
		$u = "UPDATE ".$tabla." SET file_".$nombre_fichero."='' WHERE id=".$id."";
		if($idioma != ""){
			$u .= " AND idioma='".$idioma."'";
		}
		$update = db_query($link,$u);
		
		//Borro el fichero
		@unlink('../downloads/'.$fichero_en_si);
		
		echo '';
		
		break;
	
		
	case "eliminarFoto":
	
		$tabla = $_POST['tabla'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		$nombre_fichero = $_POST['nombre_fichero'];
		
		$guionbajo = strpos($tabla,"_");
		if($guionbajo > 0){
			$sufix = substr($tabla,0,$guionbajo);
		} else{
			$sufix = $tabla;
		}
		
		//Busco info del fichero
		$c = "SELECT * FROM ".$tabla." WHERE id=".$id;
		if($idioma != ""){
			$c .= " AND idioma='".$idioma."' ";
		}
		$i = db_query($link,$c);
		$info = mysqli_fetch_array($i);
		
		$foto_en_si = $info['file_'.$nombre_fichero];
		
		//Buscamos todas las versiones que tendra la foto y las eliminamos
		$c = db_query($link,"SELECT * FROM config_thumbs ct LEFT JOIN config_tipothumb ctt ON (ctt.id = ct.select_tipothumb) ORDER BY ct.id ASC");
		while($con = mysqli_fetch_array($c)){
			$prefix_thumbnail = $con['text_prefix'];
			@unlink('../images/'.$sufix.'/'.$prefix_thumbnail.''.$foto);
		}
		@unlink('../images/'.$sufix.'/'.$foto);
		
		//Elimino el registro
		$u = "UPDATE ".$tabla." SET file_".$nombre_fichero."='' WHERE id=".$id."";
		if($idioma != ""){
			$u .= " AND idioma='".$idioma."'";
		}
		$update = db_query($link,$u);
		
		echo '';
		
		break;
	
	
	case "guardaNombre":
	
		$tabla = $_POST['tabla'];
		$id = $_POST['id'];
		$registro = $_POST['registro'];
		$idioma = $_POST['idioma'];		
		$valor = $_POST['valor'];
		
		//Guardo el nombre
		$u = db_query($link,"UPDATE ".$tabla." SET nombre='".$valor."' WHERE id_elem=".$id." AND id=".$registro."");	
		
		break;


	case "guardaLink":
	
		$tabla = $_POST['tabla'];
		$id = $_POST['id'];
		$registro = $_POST['registro'];
		$idioma = $_POST['idioma'];		
		$valor = $_POST['valor'];
		
		//Guardo el nombre
		$u = db_query($link,"UPDATE ".$tabla." SET link='".$valor."' WHERE id_elem=".$id." AND id=".$registro."");	
		
		break;
		
		
	case "cargaUploader":
	
		$tabla = $_POST['tabla'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		
		$columnas = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$tabla;
		
		$esFileUploader = 0;
		$c = db_query($link,$columnas);
		while($rc = mysqli_fetch_array($c)){
			$field = $rc[0];
			if($field == "nombre"){
				$esFileUploader = 1;
			}
		}
		
		if($esFileUploader == 0){
			$it = 1;
			//Leo las fotos en el orden en que estan guardades en la DB
			$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id." AND idioma='".$idioma."' ORDER BY orden ASC");
			$cuantasf = mysqli_num_rows($fotos);
			if($cuantasf > 0){
				while($ar_fotos = mysqli_fetch_array($fotos)){
					 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
							<img src="../pics_'.$tabla.'/'.$id.'/th_'.$ar_fotos['foto'].'" class="fotos_subidas" />
							<a class="eliminar" onclick="eliminarFotoUploader(\''.$tabla.'\','.$id.','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
						  </li>';
					$it++;
				}
			} else{
				echo '<li>'.$backoffice['nofotos_actualmente']['es'].'</li>';
			}
		} else if($esFileUploader == 1){
			$it = 1;
			//Leo las fotos en el orden en que estan guardades en la DB
			$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id." AND idioma='".$idioma."' ORDER BY orden ASC");
			$cuantasf = mysqli_num_rows($fotos);
			if($cuantasf > 0){
				while($ar_fotos = mysqli_fetch_array($fotos)){
					 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
							<a href="../downloads/'.$id.'/'.$ar_fotos['foto'].'" target="_blank">'.$ar_fotos['foto'].'</a>
							<input type="text" name="'.$tabla.$ar_fotos['id'].$idioma.'" id="'.$tabla.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$tabla.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
							<input type="button" class="guardar" onclick="guardaNombre(\''.$tabla.'\','.$id.','.$ar_fotos['id'].',\''.$idioma.'\')" value="Guardar" />
							<span id="msg_'.$tabla.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
							<a class="eliminar" onclick="eliminarFotoUploader(\''.$tabla.'\','.$id.','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
						  </li>';
					$it++;
				}
			} else{
				echo '<li>No hay ficheros subidos</li>';
			}
		}
		
		break;
	
	
	case "creaRegCheckM":
	
		$tabla = $_POST['tabla'];
		$valor = $_POST['valor'];
		$marcados = $_POST['marcados'];
		
		$i = db_query($link,"INSERT INTO ".$tabla."(nombre) VALUES('".$valor."')");
		
		$nomenclaturas = explode("_",$tabla);
		$quecampo = $nomenclaturas[1];
		
		$marcados = explode("/",$marcados); //Ahora todavia no tengo este value
		$c = db_query($link,"SELECT * FROM `".$tabla."` ORDER BY id ASC");
		while($cm = mysqli_fetch_array($c)){
			echo '<li><input type="checkbox" name="'.$quecampo.'[]" value="'.$cm['id'].'" ';
			//if($value == $cm['id']) echo 'selected="selected" ';
			if(in_array($cm['id'],$marcados)) echo 'checked="checked"';
			echo '/> '.$cm['nombre'].' </li>';
		}
		
		break;
		
	
	case "cambiaStatus":
	
		$tabla = $_POST['tabla'];
		$campo = $_POST['campo'];
		$valor = $_POST['valor'];
		$id = $_POST['id'];
		
		$c = db_query($link,"UPDATE ".$tabla." SET ".$campo."=".$valor." WHERE id=".$id);
		
		$v = db_query($link,"SELECT * FROM ".$tabla." WHERE id=".$id);
		$list = mysqli_fetch_array($v);
		
		if($list['checkbox_visible'] == "1"){
			echo '<img src="images/icons/visible.png" title="Visible" onclick="cambiaStatus(\''.$tabla.'\',\'checkbox_visible\',0,'.$list['id'].')" />&nbsp;';
		} else if(isset($list['checkbox_visible'])){
			echo '<img src="images/icons/visibleno.png" title="No visible" onclick="cambiaStatus(\''.$tabla.'\',\'checkbox_visible\',1,'.$list['id'].')" />&nbsp;';
		}
		if($list['checkbox_portada'] == "1"){
			echo '<img src="images/icons/portada.png" title="Destacado en portada" onclick="cambiaStatus(\''.$tabla.'\',\'checkbox_portada\',0,'.$list['id'].')" />&nbsp;';
		} else if(isset($list['checkbox_portada'])){
			echo '<img src="images/icons/portadano.png" title="No destacado en portada" onclick="cambiaStatus(\''.$tabla.'\',\'checkbox_portada\',1,'.$list['id'].')" />&nbsp;';
		}
		if($list['checkbox_destacado'] == "1"){
			echo '<img src="images/icons/destacado.png" title="Destacado" onclick="cambiaStatus(\''.$tabla.'\',\'checkbox_destacado\',0,'.$list['id'].')" />&nbsp;';
		} else if(isset($list['checkbox_destacado'])){
			echo '<img src="images/icons/destacadono.png" title="No destacado " onclick="cambiaStatus(\''.$tabla.'\',\'checkbox_destacado\',1,'.$list['id'].')" />&nbsp;';
		}				
		
		break;
	
	
	case "addItem":
	
		$tabla = $_POST['tabla'];
		$idioma = $_POST['idioma'];
		$valor = $_POST['valor'];
		$id = $_POST['id'];
		
		$i = db_query($link,"INSERT INTO ".$tabla."(id_elem,idioma,nombre) VALUES('".$id."','".$idioma."','".$valor."')");
		
		$it = 1;
		//Leo las fotos en el orden en que estan guardades en la DB
		$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id." AND idioma='".$idioma."' ORDER BY orden ASC");
		$cuantasf = mysqli_num_rows($fotos);
		if($cuantasf > 0){
			while($ar_fotos = mysqli_fetch_array($fotos)){
				 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx">                                 
						<input type="text" name="'.$tabla.$ar_fotos['id'].$idioma.'" id="'.$tabla.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$tabla.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
						<input type="button" class="guardar" onclick="guardaNombre(\''.$tabla.'\','.$id.','.$ar_fotos['id'].',\''.$idioma.'\')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'" />
						<span id="msg_'.$tabla.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
						<a class="eliminar" onclick="eliminarFicheroList(\''.$tabla.'\','.$id.','.$ar_fotos['id'].',\''.$idioma.'\')" >'.$backoffice['eliminar'][$_SESSION['bo_idioma']].'</a>
					  </li>';
				$it++;
			}
		}
        echo '<li class="sortitemx"><input type="text" name="'.$tabla.$idioma.'" id="'.$tabla.$idioma.'" /><input type="button" onclick="addItem(\''.$tabla.'\',\''.$id.'\',\''.$idioma.'\')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'" /></li>';
	
		break;
		
	
	case "eliminarFicheroList":
	
		$tabla = $_POST['tabla'];
		$id_elem = $_POST['id_elem'];
		$id = $_POST['id'];
		$idioma = $_POST['idioma'];
		
		//buscamos el nombre de la foto
		if($idioma == ""){
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		} else{
			$nf = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." AND idioma='".$idioma."' LIMIT 1");
		}
		$ar_nf = mysqli_fetch_array($nf);		
		$foto = $ar_nf['foto'];
		$orden = $ar_nf['orden'];
		
		//Buscamos todas las versiones que tendra la foto y las eliminamos
		@unlink('../downloads/'.$id_elem.'/'.$foto);
	
		//Borramos el registro de la foto y reasignamos el orden
		$borra_reg = db_query($link,"DELETE FROM ".$tabla." WHERE id_elem=".$id_elem." AND id=".$id." LIMIT 1");
		$update_order = db_query($link,"UPDATE ".$tabla." SET orden=orden-1 WHERE orden > ".$orden." AND id_elem=".$id_elem."");	
		
		//Pinto la lista
		$it = 1;
		//Leo las fotos en el orden en que estan guardades en la DB
		$fotos = db_query($link,"SELECT * FROM ".$tabla." WHERE id_elem=".$id_elem." AND idioma='".$idioma."' ORDER BY orden ASC");
		$cuantasf = mysqli_num_rows($fotos);
		if($cuantasf > 0){
			while($ar_fotos = mysqli_fetch_array($fotos)){
				 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$tabla.$idioma.' sortitemx"> 
						<a href="../downloads/'.$id.'/'.$ar_fotos['foto'].'" target="_blank">'.$ar_fotos['foto'].'</a>
						<input type="text" name="'.$tabla.$ar_fotos['id'].$idioma.'" id="'.$tabla.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$tabla.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
						<input type="button" class="guardar" onclick="guardaNombre(\''.$tabla.'\','.$id_elem.','.$ar_fotos['id'].',\''.$idioma.'\')" value="'.$backoffice['btn_guardar']['es'].'" />
						<span id="msg_'.$tabla.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
						<a class="eliminar" onclick="eliminarFicheroList(\''.$tabla.'\','.$id_elem.','.$ar_fotos['id'].',\''.$idioma.'\')" >'.$backoffice['eliminar']['es'].'</a>
					  </li>';
				$it++;
			}
		} else{
			echo '<li>'.$backoffice['noitems_actualmente']['es'].'</li>';
		}
		echo '<li class="sortitemx"><input type="text" name="'.$tabla.$idioma.'" id="'.$tabla.$idioma.'" /><input type="button" onclick="addItem(\''.$tabla.'\',\''.$id.'\',\''.$idioma.'\')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'" /></li>';
		
		break;

}

mysqli_close($link);
?>