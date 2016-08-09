<?php
// *********
// FUNCIONES
// ---------
// pinta($field,$idioma)
// ---------
// *********


// ******************
// LISTA DE tipocampo
// ------------------
// text (*|fecha|location)
// textarea
// notiny
// file (fotoXXX|ficheroXXX)
// checkbox
// select -> DE MOMENTO NO LO ESTOY PINTANDO
// selectm 
// checkboxm
// fotouploader -> REQUIERE IDIOMA
// fileuploader -> REQUIERE IDIOMA
// location ->  AQUI NO SE USA
// ------------------
// ******************


//Parámetros que recibe:
//field: nombre del campo (TIPO_NOMBRE p.ej "text_titulo")
//comment: al hacer el comentario decimos como se pinta el nombre del campo en los formularios de insert y update
//value: valor del campo (solo se usa en el formulario de update)
//idioma: recibimos el idioma para acabar de generar el nombre del campo si hace falta (en tablas _content)
function pinta($field,$idioma=''){
	
	global $link;
	global $table;
	global $backoffice;
	global $element;
	global $titulo;
	
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
				if($quecampo == "fecha"){
					$value = fechaEsp($element[$field]);
				} else{
					$value = $element[$field];
				}

				//En el formulario de edicion, si tenemos coordenadas queremos mostrar el mapa
				if($quecampo == "latlng"){
					echo "&nbsp;";
					$latlng = explode(",",$value);
					echo '<div id="map"><div id="map_canvas"></div>';
					echo $element['text_address'].", ".$element['text_city'];					
					echo '</div>';	
					?>
					<script>
					initialize("<?php echo $latlng[0];?>","<?php echo $latlng[1];?>","<?php echo $element['text_address'];?>","<?php echo $element['text_city'];?>");
					</script>
					<?php						
				}
				//Comun a todos excepto al mapa
				else{
					echo $value;
				}
				break;
			
			case "textarea":
				$value = $element[$field];
				echo $value;
				break;
			
			case "notiny":
				$value = $element[$field];
				echo '<div class="iframeParent">'.$value.'</div>';
				break;
			
			case "file":
				$value = $element[$field];
				if($value != ""){					
					//LAS FOTOS DEBEN EMPEZAR POR 'foto', p.ej: 'foto', 'foto1', 'fotoPrueba'		
					if(substr($quecampo,0,4) == "foto"){ 
						echo '<a href="images/'.$table.'/'.$value.'" title="'.$titulo.'" rel="photo"><img src="images/'.$table.'/med_'.$value.'" alt="'.$titulo.'" /></a>';
					} 
					//LOS FICHEROS DEBEN EMPEZAR POR 'fichero', p.ej: 'fichero', 'fichero1', 'ficheroAdjunto'
					else if(substr($quecampo,0,7) == "fichero"){ 
						$ext = pathinfo('downloads/'.$value, PATHINFO_EXTENSION);
						if($ext == "pdf"){
							echo '<a href="downloads/'.$value.'" target="_blank"><img src="downloads/'.$value.'.jpg" class="pdf2jpg"/></a>';
						}
						echo '<a href="downloads/'.$value.'" target="_blank">'.$value.'</a>';
					}
				}
				break;
			
			case "checkbox": //Este checkbox para mi sera unico (no hay varias cajas), para marcar un estado binario de un elemento (p.ej. destacado/no destacado)
				$value = $element[$field];
				$c = '';
				if($value == 1) $c = $comment;
				echo $c;
				break;

			case "select": //Busco la tabla select_XXX con la que llenare la info
				echo '<div id="'.$field.'" class="select">';
				/*
				$s = db_query($link,"SELECT * FROM `".$nombre_campo."` ORDER BY titulo ASC");
				while($select = mysqli_fetch_array($s)){
					if($value == $select['valor']) echo $select['valor'];
				}
				*/
				echo $value;
				echo '</div>';
				break;
			
			case "selectm":
				  $s = db_query($link,"SELECT * FROM ".$quecampo." WHERE id IN(SELECT id_auxiliar FROM ".$table."_".$quecampo." WHERE id_principal='".$_SESSION['id']."') ORDER BY text_nombre ASC");
				  $cuantas = mysqli_num_rows($s);
				  if($cuantas == 0){
				  ?>
					
				  <?php
				  } else{
					echo '<ul>';
					while($seleccionados = mysqli_fetch_array($s)){
						echo '<li>'.$seleccionados['nombre'].'</li>';
					}
					echo '</ul>';
				  }
				break;
			
			case "checkboxm": //Busco la tabla checkboxm_XXX con la que llenare la info				
				$value = $element[$field];				
				$marcados = explode("/",$value);	
				//echo "SELECT * FROM `".$quecampo."` ORDER BY id ASC";
				$c = db_query($link,"SELECT * FROM `".$quecampo."` ORDER BY id ASC");
				echo '<ul>';
				while($cm = mysqli_fetch_array($c)){
					if(in_array($cm['id'],$marcados)) echo '<li>'.$cm['text_nombre'].'</li>';
				}
				echo '</ul>';
				break;
								
			case "fotouploader": //Busco la tabla XXX		
				echo '<div id="'.$field.'" class="fotouploader">';
				?>
				<div id="galleria_<?php echo $field;?>">
				<?php
				$it = 1;
				//Leo las fotos en el orden en que estan guardades en la DB
				$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
				$cuantasf = mysqli_num_rows($fotos);
				if($cuantasf > 0){
					while($ar_fotos = mysqli_fetch_array($fotos)){
						echo '<img src="pics_'.$quecampo.'/'.$_SESSION['id'].'/med_'.$ar_fotos['foto'].'"  />';
						$it++;
					}
				} 
				?>	
				</div>	
                <div id="source">
                	<?php
					$it = 1;
					//Leo las fotos en el orden en que estan guardades en la DB
					if($idioma!=''){
								
						$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
					
					}else{
					
						$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." ORDER BY orden ASC");
					
					}
					$cuantasf = mysqli_num_rows($fotos);
					if($cuantasf > 0){
						while($ar_fotos = mysqli_fetch_array($fotos)){
							echo '<a href="pics_'.$quecampo.'/'.$_SESSION['id'].'/'.$ar_fotos['foto'].'" rel="photo"></a>';
							$it++;
						}
					} 
					?>	            
                </div>	
                <script type="text/javascript">
				// Initialize Galleria
				$("#galleria_<?php echo $field;?>").galleria({
					autoplay: 6000,
					transitionSpeed: 1000,
					//transitionInitial: 'fade',
					imageCrop: 'width',
					minScaleRatio: 1,
					extend: function() {
						this.bind(Galleria.IMAGE, function(e) {
							$(e.imageTarget).css('cursor','pointer');
							$(e.imageTarget).click(this.proxy(function() {
								targ = $(e.imageTarget).attr('src');
								f = targ.indexOf("med_",0);
								foto = targ.substring( (f+4), (targ.length) );
								//window.alert(foto);		
								$('a[href="<?php echo 'pics_'.$quecampo.'/'.$_SESSION['id'].'/';?>'+foto+'"]').click();	
							}));
						});
					}
				
				});
				</script>						
				<?php
				echo '</div>';
				break;
						
			case "fileuploader":	
				echo '<div id="'.$field.'" class="fileuploader">';
				?>				
				<ul>
				<?php
				$it = 1;
				//Leo las fotos en el orden en que estan guardades en la DB
				$fotos = db_query($link,"SELECT * FROM ".$quecampo." WHERE id_elem=".$_SESSION['id']." AND idioma='".$idioma."' ORDER BY orden ASC");
				$cuantasf = mysqli_num_rows($fotos);
				if($cuantasf > 0){
					while($ar_fotos = mysqli_fetch_array($fotos)){
						 if($ar_fotos['nombre'] != ""){
						 	$nombre = $ar_fotos['nombre'];
						 } else{
							$nombre = $ar_fotos['foto'];
						 }
						 echo '<li id="'.$ar_fotos['id'].'"> 
								<a href="downloads/'.$_SESSION['id'].'/'.$ar_fotos['foto'].'" target="_blank">'.$nombre.'</a>
							  </li>';
						$it++;
					}
				} 
				?>
				</ul>
				<?php
				echo '</div>';
				break;
			
			/*
			case "location":
				echo '<div id="'.$field.'" class="location">';
				?>                
                <div id="map">
                	<div id="map_canvas"></div>
              	</div>
                <?php				
				echo '</div>';
				break;
			*/

				
		} //end switch
		
	}//end if donde
}
//end function print_field
?>