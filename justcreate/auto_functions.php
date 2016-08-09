<?php
// *********
// FUNCIONES
// ---------
// pintaHijos($quecampo,$parent,$prefijoVisual)
// print_field($field,$comment,$value,$idioma)
// do_insert()
// do_update()
// ponfoto($foto,$sufix,$last_id,$table,$idioma)
// ponfichero($fichero,$id,$table,$idioma)
// ---------
// *********


// ******************
// LISTA DE tipocampo
// ------------------
// text (*|fecha)
// textarea
// notiny
// file (fotoXXX|ficheroXXX)
// checkbox -> libre, pero hay 3 casos especiales: visible | portada | destacado
// select -> requiere tabla relacionada
// selectm -> requiere tabla relacionada
// checkboxm -> requiere tabla relacionada
// fotouploader -> requiere tabla relacionada
// fileuploader -> requiere tabla relacionada
// location -> se apoya en dos campos: 'text_city', y 'text_address' - requiere un campo adicional 'text_location'
// ------------------
// ******************


// *****
// TO-DO
// -----
//ojo que al eliminar una foto o fichero no estoy recalculando el limite maximo -> Lo podria hacer si guardo el valor en sesion y siempre leo el que este en sesion, asi lo puedo modificar
//El dia de mañana: hacerlo configurable, crear las tablas de cada seccion del backoffice con un editor dragable y ordenable
// -----
// *****

function pintaHijos($quecampo,$parent,$prefijoVisual){
	
	global $link;
	global $value;
	
	if($parent > 0){
	
		$prefijoVisual = $prefijoVisual."&nbsp;&nbsp;&nbsp;";
	
	}
	
	$cols = db_query($link,"SHOW FULL COLUMNS FROM ".$DATABASE.".".$quecampo);
	$is_recursive = false;
	
	while($list_cols = mysqli_fetch_array($cols)){
		
		if($list_cols[0]=='select_'.$quecampo){
			
			$is_recursive = true;
			break;
		
		}
		
	}
	
	if($is_recursive){
		
		$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` WHERE `select_".$quecampo."` = '".$parent."' ORDER BY orden ASC");
		if(!$s){
			$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` WHERE `select_".$quecampo."'` = '".$parent."' GROUP BY id ORDER BY text_titulo ASC");
		}
		while($select = mysqli_fetch_array($s)){
			if($select['idparent'] == 0 && $select['idparent'] != "" && $select['idparent'] != NULL){ 
				//echo '<option disabled="disabled" style="font-weight:bold;">'.$select['text_titulo'].'</option>'; //Si no fuesen seleccionables las categorias padre
				echo '<option value="'.$select['id'].'" style="font-weight:bold;" ';
				if($value == $select['id']) echo 'selected="selected" ';
				echo '>'.$prefijoVisual.$select['text_titulo'].'</option>'; //Pero en este caso si que lo son
			} else{
				echo '<option value="'.$select['id'].'" ';
				if($value == $select['id']) echo 'selected="selected" ';
				echo '>'.$prefijoVisual.$select['text_titulo'].'</option>';
			}
			pintaHijos($quecampo,$select['id'],$prefijoVisual);
		}
	
	}else{
	
		$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` ORDER BY orden ASC");
		if(!$s){
			$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` GROUP BY id ORDER BY text_titulo ASC");
		} if(!$s){
			$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` t, `".$quecampo."_content` tc WHERE t.id=tc.id AND tc.idioma='es' GROUP BY t.id ORDER BY tc.text_titulo ASC");
		}
		while($select = mysqli_fetch_array($s)){

			echo '<option value="'.$select['id'].'" style="font-weight:bold;" ';
			
			if($value == $select['id']){
			
				echo 'selected="selected" ';
			
			}
			
			echo '>'.$prefijoVisual.$select['text_titulo'].'</option>'; //Pero en este caso si que lo son

		}		
	
	}
	
}
function pintaHijosMultiple($quecampo,$parent,$prefijoVisual){
	
	global $link;
	global $value;
	global $table;
	
	if($parent > 0){
	
		$prefijoVisual = $prefijoVisual."&nbsp;&nbsp;&nbsp;";
	
	}
	
	$cols = db_query($link,"SHOW FULL COLUMNS FROM ".$DATABASE.".".$quecampo);
	$is_recursive = false;
	
	while($list_cols = mysqli_fetch_array($cols)){
		
		if($list_cols[0]=='select_'.$quecampo){
			
			$is_recursive = true;
			break;
		
		}
		
	}
	
	if($is_recursive){
		
		$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` WHERE `select_".$quecampo."` = '".$parent."' ORDER BY orden ASC");
		if(!$s){
			$s = mysqli_query($link,"SELECT * FROM `".$quecampo."` WHERE `select_".$quecampo."'` = '".$parent."' GROUP BY id ORDER BY text_titulo ASC");
		}
		while($select = mysqli_fetch_array($s)){
			if($select['idparent'] == 0 && $select['idparent'] != "" && $select['idparent'] != NULL){ 
				//echo '<option disabled="disabled" style="font-weight:bold;">'.$select['text_titulo'].'</option>'; //Si no fuesen seleccionables las categorias padre
				echo '<option value="'.$select['id'].'" style="font-weight:bold;" ';
				if($value == $select['id']) echo 'selected="selected" ';
				echo '>'.$prefijoVisual.$select['text_titulo'].'</option>'; //Pero en este caso si que lo son
			} else{
				echo '<option value="'.$select['id'].'" ';
				if($value == $select['id']) echo 'selected="selected" ';
				echo '>'.$prefijoVisual.$select['text_titulo'].'</option>';
			}
			pintaHijosMultiple($quecampo,$select['id'],$prefijoVisual);
		}
	
	}else{
	
		$s = mysqli_query($link,"SELECT * FROM ".$quecampo." WHERE id NOT IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY text_titulo ASC");
		if(!$s){
			$s = mysqli_query($link,"SELECT * FROM ".$quecampo." WHERE id NOT IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY text_titulo ASC");
			if(!$s){
				$s = mysqli_query($link,"SELECT * FROM ".$quecampo." t, ".$quecampo."_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.id NOT IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY tc.text_titulo ASC");			
			}
		} 
		while($select = mysqli_fetch_array($s)){

			echo '<option value="'.$select['id'].'" style="font-weight:bold;" ';
			
			if($value == $select['id']){
			
				echo 'selected="selected" ';
			
			}
			
			echo '>'.$prefijoVisual.$select['text_titulo'].'</option>'; //Pero en este caso si que lo son

		}		
	
	}
	
}

//Parámetros que recibe:
//field: nombre del campo (TIPO_NOMBRE p.ej "text_titulo")
//comment: al hacer el comentario decimos como se pinta el nombre del campo en los formularios de insert y update
//value: valor del campo (solo se usa en el formulario de update)
//idioma: recibimos el idioma para acabar de generar el nombre del campo si hace falta (en tablas _content)
function print_field($field,$comment,$value,$idioma){
	
	global $link;
	global $table;
	global $backoffice;
	
	$separador = "_";
	$donde = strpos($field,$separador);
	
	if($donde > 1){
		
		//Dividimos el 'field' en 2 partes:
		//tipocampo: nos dice si es text, textarea, etc
		//quecampo: nombre del campo, para en casos concretos hacer una casuistica especifica
		$tipocampo = substr($field,0,$donde);
		$quecampo = substr($field,($donde+1),strlen($field)-$donde);
		if($idioma != ""){
			$nombre_campo = $field.'_'.$idioma;
		} else{
			$nombre_campo = $field;
		}
		
		//Aqui esta la chicha real de esta funcion
		switch ($tipocampo){
			
			case "text":
				//Caso concreto: el text_fecha lo querremos mostrar en un formato de fecha amigable para el usuario
				if($value == "" && substr($quecampo,0,5) == "fecha"){
					$value = date("d-m-Y");
				} else if($value != "" && substr($quecampo,0,5) == "fecha"){
					$value = fechaEsp($value);
				} 
				if($quecampo == "disabled"){ //Añadido para Aerotaxis
					echo '<li class="text"><span>'.$comment.'</span> <input type="text" name="'.$nombre_campo.'" id="'.$nombre_campo.'" value="'.$value.'" readonly /></li>'.chr(10);
				} else{
					//Comun a todos
					echo '<li class="text"><span>'.$comment.'</span> <input type="text" name="'.$nombre_campo.'" id="'.$nombre_campo.'" value="'.$value.'" /></li>'.chr(10);
				}
				//En el formulario de edicion, si tenemos coordenadas queremos mostrar el mapa
				if($quecampo == "latlng"){
					if($_SESSION['action'] == "update"){
						$latlng = explode(",",$value);						
						?>
						<script>
						codeAddressLoad("<?php echo $latlng[0];?>","<?php echo $latlng[1];?>");
						</script>
						<?php						
					}
				}
				break;
			
			case "textarea":
				echo '<li class="textarea"><span>'.$comment.'</span><br /><textarea name="'.$nombre_campo.'" id="'.$nombre_campo.'" class="editme">'.$value.'</textarea></li>'.chr(10);
				break;
			
			case "notiny":
				echo '<li class="notiny"><span>'.$comment.'</span> <textarea name="'.$nombre_campo.'" id="'.$nombre_campo.'" class="mceNoEditor">'.$value.'</textarea></li>'.chr(10);
				break;
			
			case "file":
				//Cuando estemos en el form de modificar haremos unas casuisticas particulares si es un fichero o una foto
				if($value != ""){
					
					if($idioma != ""){
						$latabla = $table."_content";
					} else{
						$latabla = $table;
					}
					//LAS FOTOS DEBEN EMPEZAR POR 'foto', p.ej: 'foto', 'foto1', 'fotoPrueba'		
					if(substr($quecampo,0,4) == "foto"){ 
						echo '<li class="file picture"><span>'.$comment.'</span> <input type="file" name="'.$nombre_campo.'" id="'.$nombre_campo.'" value="'.$value.'" />';
						?>
						  <ul>
                          <li id="li<?php echo $nombre_campo;?>">
						  <img src="../images/<?php echo $table; ?>/th_<?php echo $value; ?>" style="border:1px solid #ccc;" /> <br />
						  
						  <input type="button" onclick="eliminarFoto('<?php echo $latabla;?>','<?php echo $_SESSION['id'];?>','<?php echo $idioma;?>','<?php echo $quecampo;?>','<?php echo $nombre_campo;?>')" class="form_submit" value="<?php echo $backoffice['eliminar'][$_SESSION['bo_idioma']];?>" />
						  </li>
                          </ul>
						<?php
						echo '</li>'.chr(10);
					} 
					//LOS FICHEROS DEBEN EMPEZAR POR 'fichero', p.ej: 'fichero', 'fichero1', 'ficheroAdjunto'
					else if(substr($quecampo,0,7) == "fichero"){ 
						echo '<li class="file attached"><span>'.$comment.'</span> <input type="file" name="'.$nombre_campo.'" id="'.$nombre_campo.'" value="'.$value.'" />';
						?>
                          <ul>
						  <li id="li<?php echo $nombre_campo;?>">
   						  <span>
						  <?php echo $backoffice['actual'][$_SESSION['bo_idioma']];?>: 
						  
                          <?php echo '<a href="../downloads/'.$value.'" target="_blank">'.$value.'</a>'; ?>
                          </span>
                          <?php
						  $ext = pathinfo('../downloads/'.$value, PATHINFO_EXTENSION);
						  if($ext == "pdf"){
							  echo '<a href="../downloads/'.$value.'" target="_blank"><img src="../downloads/'.$value.'.jpg" class="pdf2jpg"/></a>';
						  }
						  
						  ?>
						  
						  <input type="button" onclick="eliminarFichero('<?php echo $latabla;?>','<?php echo $_SESSION['id'];?>','<?php echo $idioma;?>','<?php echo $quecampo;?>','<?php echo $nombre_campo;?>')" class="form_submit" value="<?php echo $backoffice['eliminar'][$_SESSION['bo_idioma']];?>" />
						  </li>
                          </ul>
                        <?php 
						echo '</li>'.chr(10);
					}
				} else{
					echo '<li class="file"><span>'.$comment.'</span> <input type="file" name="'.$nombre_campo.'" id="'.$nombre_campo.'" value="'.$value.'" /></li>'.chr(10);
				}
				break;
			
			case "checkbox": //Este checkbox para mi sera unico (no hay varias cajas), para marcar un estado binario de un elemento (p.ej. destacado/no destacado)
				$c = '';
				if($value == 1) $c = 'checked="checked"';
				echo '<li class="checkbox"><span>'.$comment.'</span> <input type="checkbox" name="'.$nombre_campo.'" id="'.$nombre_campo.'" value="1" '.$c.' /></li>'.chr(10);
				break;						

			case "select": //Busco la tabla XXX con la que llenare la info
				$prefijoVisual = "";
				echo '<li class="select"><span>'.$comment.'</span> ';
				echo '<select name="'.$nombre_campo.'" id="'.$nombre_campo.'">';
				echo '<option value="0">'.$backoffice['selecciona'][$_SESSION['bo_idioma']].'</option>';
				pintaHijos($quecampo,0,"");
				echo '</select>';
				echo '</li>';
				break;
			
			case "selectm":
				
				echo '<li class="selectm"><span>'.$comment.'</span> ';
				
				?>
                  <table border="0"><tr><td valign="top">
                  <select id="elem_no_destacados<?php echo $quecampo;?>" name="elem_no_destacados<?php echo $quecampo;?>" class="form_input" multiple="multiple" size="20">
				  <?php 
				  //Esta es una alternativa
				  //pintaHijosMultiple($quecampo,0,"");

				  //Esta es otra alternativa	
			  	  $s = mysqli_query($link,"SELECT * FROM ".$quecampo." t, ".$quecampo."_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.id NOT IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY t.id ASC");
				  if(!$s){
					  $s = mysqli_query($link,"SELECT * FROM ".$quecampo." WHERE id NOT IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY id ASC");
				  }
				  $cuantas = mysqli_num_rows($s);
				  
				  if($cuantas == 0){
				  ?>
				  	<option value='-'><?php echo $backoffice['selectm_noelementosseleccionados'][$_SESSION['bo_idioma']];?></option>
				  <?php
				  } else{
				  	while($noseleccionados = mysqli_fetch_array($s)){
				  		$prefijoVisual = "";
				  		if($noseleccionados['select_ciclos'] != 0) $prefijoVisual = "&nbsp;&nbsp;&nbsp;";
						echo '<option value="'.$noseleccionados['id'].'">'.$prefijoVisual.$noseleccionados['text_titulo'].'</option>';
					}
				  }
				  ?>
                  <option value=""></option>
                  </select>
                  </td>
                  <td class="middletd">
                  <input type="button" value="&rarr;" class="form_submit" onClick="volcarSelects('elem_no_destacados<?php echo $quecampo;?>', 'elem_destacados<?php echo $quecampo;?>');" /> 
                  <input type="button" value="&larr;" class="form_submit" onClick="volcarSelects('elem_destacados<?php echo $quecampo;?>', 'elem_no_destacados<?php echo $quecampo;?>');" />
                  </td>
                  <td valign="top">                  
                  <select id="elem_destacados<?php echo $quecampo;?>" name="elem_destacados<?php echo $quecampo;?>[]" class="form_input"  multiple="multiple" size="20">
                  
                  	  <?php
					  $s = mysqli_query($link,"SELECT * FROM ".$quecampo." WHERE id IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY text_titulo ASC");
					  if(!$s){
						  $s = mysqli_query($link,"SELECT * FROM ".$quecampo." t, ".$quecampo."_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.id IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY tc.text_titulo ASC");		
					  }
					  $cuantas = mysqli_num_rows($s);
					  
					  if($cuantas == 0){
					  ?>
					  	<option value='-'><?php echo $backoffice['selectm_noelementosseleccionados'][$_SESSION['bo_idioma']];?></option>
					  <?php
					  } else{
					  	while($seleccionados = mysqli_fetch_array($s)){
							echo '<option value="'.$seleccionados['id'].'" selected="selected">'.$seleccionados['text_titulo'].'</option>';
						}
					  }
					  ?>
                  
                  	
                  </select>
                  </td></tr></table>
                  <div id="mensaje_selectm"></div>
                <?php
				echo '</li>';
				break;
			
			case "checkboxm": //Busco la tabla checkboxm_XXX con la que llenare la info
				
				$marcados = explode("/",$value);
				
				echo '<li class="checkbox checkboxm"><span>'.$comment.'</span><ul id="'.$nombre_campo.'">';
				
				$result_cols_content = db_query($link,"SHOW FULL COLUMNS FROM ".$DATABASE.".".$quecampo."_content  LIKE 'text_titulo'");
				
				if(mysqli_num_rows($result_cols_content)>0){
				
					$c = db_query($link,"SELECT * FROM ".$quecampo." t LEFT JOIN ".$quecampo."_content tc ON ((t.id = tc.id) AND (tc.idioma='".IDIOMADEFAULT."')) ORDER BY tc.text_titulo ASC");
				
				}else{
					
					$c = db_query($link,"SELECT * FROM ".$quecampo." ORDER BY text_titulo ASC");
				
				}
				
				while($cm = mysqli_fetch_array($c)){
					
					echo '<li><input type="checkbox" name="'.$quecampo.'[]" value="'.$cm['id'].'" ';

					if(in_array($cm['id'],$marcados)) echo 'checked="checked"';
					echo '/> '.$cm['text_titulo'].' </li>';
				}
				
				echo '</ul>';
				echo '</li>';
				break;
			
			case "fotouploader": //Busco la tabla XXX
				//Solo en formulario de edicion!
				if($_SESSION['action'] == "update"){
					$_SESSION['tabla'] = $quecampo;			
					echo '<li><span>'.$comment.'</span> ';
					?>
					
					<div id="uploader<?php echo $quecampo.$idioma;?>">
						<p><?php echo $backoffice['navegador_no_compatible'][$_SESSION['bo_idioma']];?></p>
					</div>
	
					<span><?php echo $backoffice['ordenar_fotos'][$_SESSION['bo_idioma']];?></span>
					
					<ul id="sortme<?php echo $quecampo.$idioma;?>">
					<?php
					$it = 1;
					//Leo las fotos en el orden en que estan guardades en la DB
					$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
					$cuantasf = mysqli_num_rows($fotos);
					if($cuantasf > 0){
						while($ar_fotos = mysqli_fetch_array($fotos)){
							 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$quecampo.$idioma.' sortitemx"> 
									<img src="../pics_'.$quecampo.'/'.$_SESSION['id'].'/th_'.$ar_fotos['foto'].'" class="fotos_subidas" />
									<a class="eliminar" onclick="eliminarFotoUploader(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
								  </li>';
							$it++;
						}
					} else{
						echo '<li>'.$backoffice['nofotos_actualmente'][$_SESSION['bo_idioma']].'</li>';
					}
					?>
					</ul>
					
					<div id="showmsg<?php echo $quecampo.$idioma;?>" class="mensajes"></div>
					
					<?php
				}
				break;

			case "bannuploader": //Busco la tabla XXX
				//Solo en formulario de edicion!
				if($_SESSION['action'] == "update"){
					$_SESSION['tabla'] = $quecampo;			
					echo '<li><span>'.$comment.'</span> ';
					//Pongo un campo hidden
					//echo '<input type="text" name="fotouploader" value="'.$quecampo.'" />';
					?>
					
					<div id="uploader<?php echo $quecampo.$idioma;?>">
						<p><?php echo $backoffice['navegador_no_compatible'][$_SESSION['bo_idioma']];?></p>
					</div>
	
					<span><?php echo $backoffice['ordenar_fotos'][$_SESSION['bo_idioma']];?></span>
					
					<ul id="sortme<?php echo $quecampo.$idioma;?>">
					<?php
					$it = 1;
					//Leo las fotos en el orden en que estan guardades en la DB
					$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
					$cuantasf = mysqli_num_rows($fotos);
					if($cuantasf > 0){
						while($ar_fotos = mysqli_fetch_array($fotos)){
							 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$quecampo.$idioma.' sortitemx"> 
									<img src="../pics_'.$quecampo.'/'.$_SESSION['id'].'/th_'.$ar_fotos['foto'].'" class="fotos_subidas" />
									<span>Texto:</span> <input type="text" name="'.$quecampo.$ar_fotos['id'].$idioma.'" id="'.$quecampo.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$quecampo.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
									<input type="button" class="guardar" onclick="guardaNombre(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'" />
									<span id="msg_'.$quecampo.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
									<a class="eliminar" onclick="eliminarBannUploader(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
									<span>Link:</span> <input type="text" name="'.$quecampo.$ar_fotos['id'].$idioma.'l" id="'.$quecampo.$ar_fotos['id'].$idioma.'l" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$quecampo.$ar_fotos['id'].$idioma.'l\').focus()" value="'.$ar_fotos['link'].'" />
									<input type="button" class="guardar" onclick="guardaLink(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'l\')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'" />
									<span id="msg_'.$quecampo.$ar_fotos['id'].$idioma.'l" class="mensajes"></span>
								  </li>';
							$it++;
						}
					} else{
						echo '<li>'.$backoffice['nofotos_actualmente'][$_SESSION['bo_idioma']].'</li>';
					}
					?>
					</ul>
					
					<div id="showmsg<?php echo $quecampo.$idioma;?>" class="mensajes"></div>
					
					<?php
				}
				break;
			
			case "fileuploader":
				//Solo en formulario de edicion!
				if($_SESSION['action'] == "update"){
					$_SESSION['tabla'] = $quecampo;			
					echo '<li><span>'.$comment.'</span> ';
					?>
                    
                    <div id="uploader<?php echo $quecampo.$idioma;?>">
						<p><?php echo $backoffice['navegador_no_compatible'][$_SESSION['bo_idioma']];?></p>
					</div>
                    
                    <span><?php echo $backoffice['ordenar_ficheros'][$_SESSION['bo_idioma']];?></span>
                    
                    <ul id="sortme<?php echo $quecampo.$idioma;?>">
					<?php
					$it = 1;
					//Leo las fotos en el orden en que estan guardades en la DB
					$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
					$cuantasf = mysqli_num_rows($fotos);
					if($cuantasf > 0){
						while($ar_fotos = mysqli_fetch_array($fotos)){
							 echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$quecampo.$idioma.' sortitemx"> 
									<a href="../downloads/'.$_SESSION['id'].'/'.$ar_fotos['foto'].'" target="_blank">'.$ar_fotos['foto'].'</a>
									<input type="text" name="'.$quecampo.$ar_fotos['id'].$idioma.'" id="'.$quecampo.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$quecampo.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
									<input type="button" class="guardar" onclick="guardaNombre(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" value="Guardar" />
									<span id="msg_'.$quecampo.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
									<a class="eliminar" onclick="eliminarFicheroUploader(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" >Eliminar</a>
								  </li>';
							$it++;
						}
					} else{
						echo '<li>'.$backoffice['noficheros_actualmente'][$_SESSION['bo_idioma']].'</li>';
					}
					?>
					</ul>
					
					<div id="showmsg<?php echo $quecampo.$idioma;?>" class="mensajes"></div>
                    
					<?php
					echo '</li>';
				}
				break;
							
			case "list":
				echo '<li><span>'.$comment.'</span> ';
				?>
                
                <ul id="sortme<?php echo $quecampo.$idioma;?>">
				<?php
                $it = 1;
                //Leo las fotos en el orden en que estan guardades en la DB
                $fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
                $cuantasf = mysqli_num_rows($fotos);
                if($cuantasf > 0){
                    while($ar_fotos = mysqli_fetch_array($fotos)){
                         echo '<li id="'.$ar_fotos['id'].'" class="sortitem'.$quecampo.$idioma.' sortitemx">                                 
                                <input type="text" name="'.$quecampo.$ar_fotos['id'].$idioma.'" id="'.$quecampo.$ar_fotos['id'].$idioma.'" onfocus="this.value=this.value;" onclick="document.getElementById(\''.$quecampo.$ar_fotos['id'].$idioma.'\').focus()" value="'.$ar_fotos['nombre'].'" />
                                <input type="button" class="guardar" onclick="guardaNombre(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" value="'.$backoffice['btn_guardar'][$_SESSION['bo_idioma']].'" />
                                <span id="msg_'.$quecampo.$ar_fotos['id'].$idioma.'" class="mensajes"></span>
                                <a class="eliminar" onclick="eliminarFicheroList(\''.$quecampo.'\','.$_SESSION['id'].','.$ar_fotos['id'].',\''.$idioma.'\')" >'.$backoffice['eliminar'][$_SESSION['bo_idioma']].'</a>
                              </li>';
                        $it++;
                    }
                } else{
                    echo '<li>'.$backoffice['noitems_actualmente'][$_SESSION['bo_idioma']].'</li>';
                }
                ?>
                <li class="sortitemx"><input type="text" name="<?php echo $quecampo.$idioma;?>" id="<?php echo $quecampo.$idioma;?>" /><input type="button" onclick="addItem('<?php echo $quecampo;?>','<?php echo $_SESSION['id'];?>','<?php echo $idioma;?>')" value="<?php echo $backoffice['btn_guardar'][$_SESSION['bo_idioma']];?>" /></li>
                </ul>
                
                <div id="showmsg<?php echo $quecampo.$idioma;?>" class="mensajes"></div>
                    
                <?php
				echo '</li>';
				
				break;				
			
			case "location":
				echo '<li class="location"><span>'.$comment.'</span> ';
				?>
                <input type="button" name="coords" id="coords" onclick="codeAddress()" value="<?php echo $backoffice['ubicar_enelmapa'][$_SESSION['bo_idioma']];?>" class="form_input" /> 
                
                <div id="map">
                	<div id="map_canvas"></div>
              	</div>
                <?php
				break;

				
		} //end switch
		
	}//end if donde
}
//end function print_field



function do_insert(){
	
	global $link;
	global $table;
	global $table_content;
	global $cols;
	global $cols_content;
	
	//Comenzamos a construir la consulta de insercion para la tabla COMUN A TODOS LOS IDIOMAS
	$sql = "INSERT INTO ".$table."(";		
	$sql_fields = "";
	$sql_values = "";
	
	//Listamos los campos
	$list_cols = db_query($link,$cols);
	while($list = mysqli_fetch_array($list_cols)){
		$field = $list[0];
		$sql_fields .= $field.","; 
	}
	$sql_fields = substr($sql_fields,0,strlen($sql_fields)-1);
	$sql .= $sql_fields;
	$sql .= ") VALUES(";
	
	//Listamos los valores
	$list_cols = db_query($link,$cols);
	while($list = mysqli_fetch_array($list_cols)){
		$field = $list[0];
		//Particularidad para conversion de la fecha
		if(substr($field,0,10) == "text_fecha"){
			$_POST[$field] = fechaDb($_POST[$field]);
		}
		//Particular usuarios ->
		if(($table=='usuaris')&&($field=='idioma')){
		
			if($_POST[$field]==''){
				
				$_POST[$field]='es';
				
			}
		
		}		
		//Si hay checkbox multiple		
		if(substr($field,0,9) == "checkboxm"){
			$nombrecheck = substr($field,10,strlen($field)-10);
			$valuecheck = "";
			if($_POST[$nombrecheck] != ""){
				foreach($_POST[$nombrecheck] as $key=>$value){
					$valuecheck .= $value."/";
	
				}
				$valuecheck = substr($valuecheck,0,strlen($valuecheck)-1);
			}
			$_POST[$field] = $valuecheck;
		}
		$sql_values .= "'".$_POST[$field]."',"; 
	}
	$sql_values = substr($sql_values,0,strlen($sql_values)-1);
	$sql .= $sql_values;
	$sql .= ")";
	
	$ins = db_query($link,$sql) or die(mysqli_error($link));
	$last_id = mysqli_insert_id($link);	
	
	//CASOS PARTICULARES: SELECT MULTIPLE, FOTOS Y FICHEROS
	$list_cols = db_query($link,$cols);
	$it = 0;
	while($list = mysqli_fetch_array($list_cols)){
		$field = $list[0];

		//caso tipico: selectm_XXX
		if(substr($field,0,7) == "selectm"){
			$cual_es = substr($field,8,strlen($field)-8);
			if($_POST['elem_destacados'.$cual_es] != ""){
				foreach($_POST['elem_destacados'.$cual_es] as $key=>$value){
					?><script>window.alert("<?php echo "INSERT INTO ".$table."_".$cual_es."(id_principal,id_auxiliar) values(".$last_id.",".$value.")";?>");</script><?php
					$i = db_query($link,"INSERT INTO ".$table."_".$cual_es."(id_principal,id_auxiliar) values(".$last_id.",".$value.")");
				}
			}
		}
		
		//caso tipico: file_fotoXXX
		$patron_foto = '/file_foto.*/';
		if( preg_match($patron_foto, $field) ){
			if ($_FILES[$field]['name']!= ""){
				$sufix = $table;
				if ( (strpos($_FILES[$field]['type'], "jpeg")) || (strpos($_FILES[$field]['type'], "png")) || (strpos($_FILES[$field]['type'], "gif")) ){ 
					ponfoto($field,$sufix,$last_id,$table,$idioma="");
				}
			} //end file_foto
		}

		//caso tipico: file_ficheroXXX 
		$patron_fichero = '/file_fichero.*/';
		if( preg_match($patron_fichero, $field) ){
			if ($_FILES[$field]['name']) {
				ponfichero($field,$last_id,$table,$idioma="");
			}
		}
	
	} //end while
	
	//AQUI ACABA LA PARTE COMUN PARA TODOS LOS IDIOMAS
	
	$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
	$tableExists = mysqli_num_rows($result) > 0;
	if($tableExists){
	
		//Comenzamos a construir la consulta de insercion para la tabla DEL CONTENIDO SEGUN IDIOMA
		$idi = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
		while($ar_idi = mysqli_fetch_array($idi)){
			$ni = $ar_idi['nom_idioma'];				
					
			$sql = "INSERT INTO ".$table_content."(";		
			$sql_fields = "id,";
			$sql_values = $last_id.",";
			
			//Listamos los campos
			$list_cols_content = db_query($link,$cols_content);
			while($list_content = mysqli_fetch_array($list_cols_content)){
				if($list_content[0] != "id"){
					$field = $list_content[0];
					$sql_fields .= $field.","; 
				}
			}
			$sql_fields = substr($sql_fields,0,strlen($sql_fields)-1);
			$sql .= $sql_fields;
			$sql .= ") VALUES(";
			
			//Listamos los valores
			$list_cols_content = db_query($link,$cols_content);
			while($list_content = mysqli_fetch_array($list_cols_content)){
				if($list_content[0] != "id"){
					$field = $list_content[0];
					$sql_values .= "'".$_POST[$field."_".$ni]."',"; 
				}
			}
			$sql_values = substr($sql_values,0,strlen($sql_values)-1);
			$sql .= $sql_values;
			$sql .= ")";
			
			//echo $sql;
			
			$ins = db_query($link,$sql) or die(mysqli_error($link));
			
			//CASO PARTICULAR: FOTOS Y FICHEROS
			$list_cols_content = db_query($link,$cols_content);
			$it = 0;
			while($list_content = mysqli_fetch_array($list_cols_content)){
				$field = $list_content[0];
		
				//Sobre la insercion tipica tenemos casos particulares, como el caso que haya fotos o ficheros adjuntos
				//caso tipico: file_fotoXXX
				$patron_foto = '/file_foto.*/';
				if( preg_match($patron_foto, $field) ){			
					if ($_FILES[$field.'_'.$ni]['name']!= ""){
						$sufix = $table;
						//if ((strpos($_FILES[$field.'_'.$ni]['type'], "jpeg"))){ 
						if ( (strpos($_FILES[$field.'_'.$ni]['type'], "jpeg")) || (strpos($_FILES[$field.'_'.$ni]['type'], "png")) || (strpos($_FILES[$field.'_'.$ni]['type'], "gif")) ){ 
							ponfoto($field,$sufix,$last_id,$table_content,$ni);
						}
					} //end file_foto
				}
		
				//caso tipico: file_ficheroXXX 
				$patron_fichero = '/file_fichero.*/';
				if( preg_match($patron_fichero, $field) ){
					if ($_FILES[$field.'_'.$ni]['name']) {
						ponfichero($field,$last_id,$table_content,$ni);
					}
				}
			
			} //end while
		
		}//end while
	
	}
	
	return $last_id;
} 
//end function do_insert



function do_update($id){
	
	global $link;
	global $table;
	global $table_content;
	global $cols;
	global $cols_content;
	
	//Comenzamos a construir la consulta de insercion para la tabla COMUN A TODOS LOS IDIOMAS
	$sql = "UPDATE ".$table." SET ";		
	$sql_fields = array();
	$sql_values = array();
	$sql_middle = "";
	
	//Listamos los campos
	$list_cols = db_query($link,$cols);
	$it = 0;
	while($list = mysqli_fetch_array($list_cols)){
		$field = $list[0];
		$excepciones = "file_";
		if(substr($field,0,5) != $excepciones && $field != "id" && $field != "orden"){
			$sql_fields[$it] = $field;
			$it++;
		}
	}
	
	//Listamos los valores
	$list_cols = db_query($link,$cols);
	$it = 0;
	while($list = mysqli_fetch_array($list_cols)){
		$field = $list[0];
		//Particularidad para conversion de la fecha
		if(substr($field,0,10) == "text_fecha"){
			$_POST[$field] = fechaDb($_POST[$field]);
		}
		//Particular usuarios ->
		if(($table=='usuaris')&&($field=='idioma')){
		
			if($_POST[$field]==''){
				
				$_POST[$field]='es';
				
			}
		
		}
		//Si hay checkbox multiple		
		if(substr($field,0,9) == "checkboxm"){
			$nombrecheck = substr($field,10,strlen($field)-10);
			$valuecheck = "";
			if($_POST[$nombrecheck] != ""){
				foreach($_POST[$nombrecheck] as $key=>$value){
					$valuecheck .= $value."/";
	
				}
				$valuecheck = substr($valuecheck,0,strlen($valuecheck)-1);
			}
			$_POST[$field] = $valuecheck;
		}
		$excepciones = "file_";
		if(substr($field,0,5) != $excepciones && $field != "id" && $field != "orden"){ //No quiero actualizar el contenido de los campos foto o fichero si vienen vacios
			$sql_values[$it] = $_POST[$field]; 
			$it++;
		}
	}

	//Acabamos de construir la sentencia
	for($i=0;$i<$it;$i++){
		$sql_middle .= $sql_fields[$i]."='".$sql_values[$i]."',";
	}
	$sql_middle = substr($sql_middle,0,strlen($sql_middle)-1);
	$sql .= $sql_middle;
	
	$final = substr($sql,strlen($sql)-4,3);
	
	if($final == "SET"){
		$sql .= "id=".$id;
	}
	$sql .= " WHERE id=".$id;
	
	$upd = db_query($link,$sql) or die(mysqli_error($link));
	
	
	//CASOS PARTICULARES: SELECT MULTIPLE, FOTOS Y FICHEROS
	$list_cols = db_query($link,$cols);
	$it = 0;
	while($list = mysqli_fetch_array($list_cols)){
		$field = $list[0];
		
		//caso tipico: selectm_XXX
		if(substr($field,0,7) == "selectm"){
			$cual_es = substr($field,8,strlen($field)-8);
			$d = db_query($link,"DELETE FROM ".$table."_".$cual_es." WHERE id_principal=".$id);
			if($_POST['elem_destacados'.$cual_es] != ""){
				foreach($_POST['elem_destacados'.$cual_es] as $key=>$value){				
					$i = db_query($link,"INSERT INTO ".$table."_".$cual_es."(id_principal,id_auxiliar) values(".$id.",".$value.")");
				}
			}
		}

		//Sobre la insercion tipica tenemos casos particulares, como el caso que haya fotos o ficheros adjuntos
		//caso tipico: file_fotoXXX
		$patron_foto = '/file_foto.*/';
		if( preg_match($patron_foto, $field) ){
			if ($_FILES[$field]['name']!= ""){
				$sufix = $table;
				if ( (strpos($_FILES[$field]['type'], "jpeg")) || (strpos($_FILES[$field]['type'], "png")) || (strpos($_FILES[$field]['type'], "gif")) ){ 
					ponfoto($field,$sufix,$id,$table,$idioma="");
				}
			} //end file_foto
		}

		//caso tipico: file_ficheroXXX 
		$patron_fichero = '/file_fichero.*/';
		if( preg_match($patron_fichero, $field) ){
			if ($_FILES[$field]['name']) {
				ponfichero($field,$id,$table,$idioma="");
			}
		}
	
	} //end while
	
	//AQUI ACABA LA PARTE COMUN
	
	$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
	$tableExists = mysqli_num_rows($result) > 0;
	if($tableExists){
	
	//Comenzamos a construir la consulta de insercion para la tabla DEL CONTENIDO SEGUN IDIOMA
	$idi = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
	while($ar_idi = mysqli_fetch_array($idi)){
		$ni = $ar_idi['nom_idioma'];	
		
		//Busco si hay un registro en este idioma
		$hi = db_query($link,"SELECT * FROM ".$table_content." WHERE id=".$id." AND idioma='".$ni."'");
		$num_hi = mysqli_num_rows($hi);
		
		//Si lo hay, es actualizacion
		if($num_hi == 1){
			
			$sql = "UPDATE ".$table_content." SET ";		
			$sql_fields = array();
			$sql_values = array();
			$sql_middle = "";
			
			//Listamos los campos
			$list_cols_content = db_query($link,$cols_content);
			$it = 0;
			while($list_content = mysqli_fetch_array($list_cols_content)){
				$field = $list_content[0];
				
				$excepciones = "file_";
				if(substr($field,0,5) != $excepciones){				
					$sql_fields[$it] = $field;
					$it++;
				}
			}
			
			//Listamos los valores
			$list_cols_content = db_query($link,$cols_content);
			$it = 0;
			while($list_content = mysqli_fetch_array($list_cols_content)){
				$field = $list_content[0];
				
				$excepciones = "file_";
				if(substr($field,0,5) != $excepciones){				
					$sql_values[$it] = $_POST[$field."_".$ni]; 
					$it++;
				}
			}
			
			//Acabamos de construir la sentencia
			for($i=0;$i<$it;$i++){
				$sql_middle .= $sql_fields[$i]."='".$sql_values[$i]."',";
			}
			$sql_middle = substr($sql_middle,0,strlen($sql_middle)-1);
			$sql .= $sql_middle;
			$sql .= " WHERE id=".$id." AND idioma='".$ni."'";
	
			$upd = db_query($link,$sql) or die("error: ".$sql);
		
		}
		//Si no lo hay es insercion! PUEDE QUE LA WEB EMPEZASE CON X IDIOMAS Y LUEGO SE AÑADAN IDIOMAS ADICIONALES	
		else{
			
			$sql = "INSERT INTO ".$table_content."(";		
			$sql_fields = "";
			$sql_values = "";
			
			//Listamos los campos
			$list_cols_content = db_query($link,$cols_content);
			while($list_content = mysqli_fetch_array($list_cols_content)){
				$field = $list_content[0];
				$sql_fields .= $field.","; 
			}
			$sql_fields = substr($sql_fields,0,strlen($sql_fields)-1);
			$sql .= $sql_fields;
			$sql .= ") VALUES(";
			
			//Listamos los valores
			$list_cols_content = db_query($link,$cols_content);
			while($list_content = mysqli_fetch_array($list_cols_content)){
				$field = $list_content[0];
				$sql_values .= "'".$_POST[$field."_".$ni]."',"; 
			}
			$sql_values = substr($sql_values,0,strlen($sql_values)-1);
			$sql .= $sql_values;
			$sql .= ")";
			
			$ins = db_query($link,$sql) or die(mysqli_error($link));
		
		}
				
		
		
		//CASO PARTICULAR: FOTOS Y FICHEROS
		$list_cols_content = db_query($link,$cols_content);
		$it = 0;
		while($list_content = mysqli_fetch_array($list_cols_content)){
			$field = $list_content[0];

			//Sobre la insercion tipica tenemos casos particulares, como el caso que haya fotos o ficheros adjuntos
			//caso tipico: file_fotoXXX
			$patron_foto = '/file_foto.*/';
			if( preg_match($patron_foto, $field) ){
				if ($_FILES[$field.'_'.$ni]['name']!= ""){
					$sufix = $table;
					//if ((strpos($_FILES[$field.'_'.$ni]['type'], "jpeg"))){ 
					if ( (strpos($_FILES[$field.'_'.$ni]['type'], "jpeg")) || (strpos($_FILES[$field.'_'.$ni]['type'], "png")) || (strpos($_FILES[$field.'_'.$ni]['type'], "gif")) ){ 
						ponfoto($field,$sufix,$id,$table_content,$ni);
					}
				}
			}//end file_foto
			
			//caso tipico: file_ficheroXXX
			$patron_foto = '/file_fichero.*/';
			if( preg_match($patron_foto, $field) ){
				if ($_FILES[$field.'_'.$ni]['name']) {
					ponfichero($field,$id,$table_content,$ni);
				}
			}//end file_fichero
		}
		
	} //end while
	
	}//if table_content

}
//end function do_update



function ponfoto($foto,$sufix,$last_id,$table,$idioma){
	
	global $link;
	global $table;
	global $table_content;

	$ts = time();
	
	if($idioma != ""){
		$fotof = $foto."_".$idioma;
	} else{
		$fotof = $foto;
	}
	
	if($_FILES[$fotof]['type'] == "image/jpeg" || $_FILES[$fotof]['type'] == "image/pjpeg"){
											
		$formato = ".jpg";
		
		$_FILES[$fotof]['name'] = str_replace(".jpeg","",mb_strtolower($_FILES[$fotof]['name'], 'UTF-8'));
		$_FILES[$fotof]['name'] = str_replace(".jpg","",mb_strtolower($_FILES[$fotof]['name'], 'UTF-8'));
	
	}else if($_FILES[$fotof]['type']== "image/png"){	
	
		$formato=".png";
									
		$_FILES[$fotof]['name'] = str_replace(".png","",mb_strtolower($_FILES[$fotof]['name'], 'UTF-8'));
									
	}else{
									
		$formato=".gif";
			
		$_FILES[$fotof]['name'] = str_replace(".gif","",mb_strtolower($_FILES[$fotof]['name'], 'UTF-8'));	
									
	}
	
	$fichero_foto = clean_name(($_FILES[$fotof]['name']));
	$fichero_foto = $ts.$fichero_foto.$formato;
	
	if (copy($_FILES[$fotof][ 'tmp_name' ], "../images/".$sufix."/".$fichero_foto)){

		//Genero tots els tipus de thumbs
		$th = db_query($link,"SELECT * FROM config_thumbs ct LEFT JOIN config_tipothumb ctt ON (ctt.id = ct.select_tipothumb) ORDER BY ct.id ASC");
		
		while($thumb = mysqli_fetch_array($th)){
		
			$prefix = $thumb['text_prefix'];
			$tipus = $thumb['text_titulo'];
			$mida = $thumb['text_mida'];
			
			$image = new SimpleImage();
			
			
			if($tipus == "width"){
				$image->load("../images/".$sufix."/".$fichero_foto);
				$image->resizeToWidth($mida);
				
			} else if($tipus == "height"){
				$image->load("../images/".$sufix."/".$fichero_foto);
				$image->resizeToHeight($mida);
				
			} else if($tipus == "square"){
				$image->load("../images/".$sufix."/".$fichero_foto);
				$image->square($mida);
				
			} else if($tipus == "resize"){
				$image->load("../images/".$sufix."/".$fichero_foto);
				$mida_thumbnail = preg_split("/,/",$mida);
				$width_final = $mida_thumbnail[0];
				$height_final = $mida_thumbnail[1];							
				$image->resize($width_final,$height_final);
				
				//QUIERO HACER UN CROP, NO UN RESIZE!
				$image2 = new SimpleImage2("../images/".$sufix."/".$fichero_foto);
				$info = $image2->get_original_info();
				$width_original = $info['width'];
				$height_original = $info['height'];
				$apaisamiento_original = $width_original / $height_original;
				$apaisamiento_final = $width_final / $height_final;
				if($apaisamiento_final > $apaisamiento_original){
					$image2->fit_to_width($width_final);
					$x_inicial = 0;
					$punto_medio = $image2->get_height();
					$y_inicial = ceil($punto_medio/2) - ceil($height_final/2);
					$x_final = $width_final;
					$y_final = $y_inicial + $height_final;
				} else{
					$image2->fit_to_height($height_final);
					$punto_medio = $image2->get_width();
					$x_inicial = ceil($punto_medio/2) - ceil($width_final/2);
					$y_inicial = 0;				
					$x_final = $x_inicial + $width_final;
					$y_final = $height_final;		
				}
				$image2->crop($x_inicial,$y_inicial,$x_final,$y_final)->save("../images/".$sufix."/".$prefix.$fichero_foto);
				
			} else if($tipus == "fit"){				
						
				//params
				$mida_thumbnail = preg_split("/,/",$mida);
				$width_final = $mida_thumbnail[0];
				$height_final = $mida_thumbnail[1];					
				$rutaFisicaFinal = "../images/".$sufix."/".$prefix.$fichero_foto;
											
				// Source image
				$extension = substr($fichero_foto,strlen($fichero_foto)-3,3);
				if($extension == "gif"){
					$src = imagecreatefromgif("../images/".$sufix."/".$fichero_foto);
					// Destination image with white background
					$dst = imagecreatetruecolor($width_final, $height_final);
					imagesavealpha($dst, true);
					imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 255, 255, 255,127));	
					scale_image($src, $dst, 'fit');			
					imagegif($dst,$rutaFisicaFinal);
				} else if($extension == "png"){
					$src = imagecreatefrompng("../images/".$sufix."/".$fichero_foto);
					// Destination image with white background
					$dst = imagecreatetruecolor($width_final, $height_final);
					imagesavealpha($dst, true);
					imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 255, 255, 255,127));
					scale_image($src, $dst, 'fit');			
					imagepng($dst,$rutaFisicaFinal);	
				} else{
					$src = imagecreatefromjpeg("../images/".$sufix."/".$fichero_foto);	
					// Destination image with white background
					$dst = imagecreatetruecolor($width_final, $height_final);
					imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));	
					scale_image($src, $dst, 'fit');			
					imagejpeg($dst,$rutaFisicaFinal);
				}
			}
			
			if($tipus != "resize" && $tipus != "fit"){
				$image->save("../images/".$sufix."/".$prefix.$fichero_foto);  
			}
		}
		if($idioma == ""){
			$sql = "UPDATE ".$table." SET ".$foto." ='".$fichero_foto."' WHERE id = '".$last_id."' LIMIT 1";
		} else{
			$sql = "UPDATE ".$table_content." SET ".$foto." ='".$fichero_foto."' WHERE id = '".$last_id."' AND idioma='".$idioma."' LIMIT 1";
		}
		$result = mysqli_query ($link,$sql) or die("error: ".$sql);
	} 
	
}
//end ponfoto

function scale_image($src_image, $dst_image, $op = 'fit') {
	$src_width = imagesx($src_image);
	$src_height = imagesy($src_image);
 
	$dst_width = imagesx($dst_image);
	$dst_height = imagesy($dst_image);
 
	// Try to match destination image by width
	$new_width = $dst_width;
	$new_height = round($new_width*($src_height/$src_width));
	$new_x = 0;
	$new_y = round(($dst_height-$new_height)/2);
 
	// FILL and FIT mode are mutually exclusive
	if ($op =='fill')
		$next = $new_height < $dst_height;
	 else
		$next = $new_height > $dst_height;
 
	// If match by width failed and destination image does not fit, try by height 
	if ($next) {
		$new_height = $dst_height;
		$new_width = round($new_height*($src_width/$src_height));
		$new_x = round(($dst_width - $new_width)/2);
		$new_y = 0;
	}
 
	// Copy image on right place
	imagecopyresampled($dst_image, $src_image , $new_x, $new_y, 0, 0, $new_width, $new_height, $src_width, $src_height);				
	
}

function ponfichero($fichero,$id,$table,$idioma){
	
	global $link;
	global $table;
	global $table_content;
		
	$fitxer = $fichero;
	
	if($idioma != ""){
		$fitxerf = $fichero."_".$idioma;
	} else{
		$fitxerf = $fichero;
	}
	
	$ts = time();
	$nombre = mb_strtolower($_FILES[$fitxerf]['name'],'UTF-8');	
	$nombre = clean_name_for_files(($nombre));
	
	if(copy($_FILES[$fitxerf]['tmp_name'],'../downloads/'.$nombre)){
		$ext = pathinfo('../downloads/'.$nombre, PATHINFO_EXTENSION);
		if($ext == "pdf"){
			$im = new imagick();
			$im->setResolution(72,72);
			$im->readimage('../downloads/'.$nombre.'[0]'); 
			$im->setImageFormat('jpeg');    
			$im->writeImage('../downloads/'.$nombre.'.jpg'); 
			$im->clear(); 
			$im->destroy();
		}
		if($idioma == ""){
			$sql = "UPDATE ".$table." SET ".$fichero."='".$nombre."' WHERE id=".$id." ";
		} else{
			$sql = "UPDATE ".$table_content." SET ".$fichero."='".$nombre."' WHERE id=".$id." AND idioma='".$idioma."' LIMIT 1";
		}
		$result = db_query($link,$sql) or die("error: ".$sql);
	} else{
		echo "error: no se ha podido copiar el fichero";
	}	
	
} 
//end ponfichero
?>