<?php 

header('Content-Type: text/html; charset=utf-8');

include("../files/config.php");
include("../files/funciones.php");

$link = conectar();

function db_slashes($link,$string){

	if(function_exists('mysqli_real_escape_string')){
							
		$string = mysqli_real_escape_string($link,$string);
	
	}else if(function_exists('mysql_escape_string')){
		
		$string = mysqli_escape_string($link,$string); 

	
	}else{
		
		$string = addslashes($string);
	
	}
	
	return $string;

}

if(isset($_POST['form']) && ($_POST['form']=='form')){

	if(is_uploaded_file($_FILES['fichero_excel']['tmp_name'])){
	
		$excel_types = array("application/vnd.ms-excel","application/msexcel","application/x-msexcel","application/x.msexcel","application/x-ms-excel","application/x-excel","application/x-dos_ms_excel","application/xls","application/x-xls","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/octet-stream");
		
		//Inicio del proceso de importación
		if(!(in_array($_FILES['fichero_excel']['type'],$excel_types))){
		
			//Error ->
			?><span style="font-family:Arial, Helvetica, sans-serif; font-size:1em;">&nbsp;&nbsp;&nbsp;Copiando fichero excel: <strong style="color:#ee0000;">Se ha producido un error -> formato de fichero incorrecto &quot;<?php echo $_FILES['fichero_excel']['type']; ?>&quot; (recuerde que el fichero excel debe tener formato .xls).</strong></span><?php
		
		}else{
		
			//Copiar fichero ->
			
			$nombre_fichero = time();
			
			$result = copy($_FILES['fichero_excel']['tmp_name'], "./textos/".$nombre_fichero.".xls");
			
			if(!$result){
			
				?><span style="font-family:Arial, Helvetica, sans-serif; font-size:1em;">&nbsp;&nbsp;&nbsp;Copiando fichero excel: <strong style="color:#ee0000;">Se ha producido un error -> no se ha podido copiar el fichero excel.</strong></span><?php
		
			}else{
			
				?><span style="font-family:Arial, Helvetica, sans-serif; font-size:1em;">&nbsp;&nbsp;&nbsp;Copiando fichero excel: <strong style="color:#076702;">correcto</strong></span><?php
				
				//Descanso un par de segundos ->
				sleep(1);
				
				//Siguiente proceso ->
				?><script type="text/javascript">
				$('#result_importar').append('<div id="content_delete_sql_backup" name="content_delete_sql_backup" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:1em; margin-top:10px; display:block;">&nbsp;<img src="images/cargando_min.gif" width="36" height="12" border="0"/>&nbsp;&nbsp;&nbsp;Borrando copia de seguridad anterior</div>');
				$('#content_delete_sql_backup').load('ajax_textos_import.php?fichero_excel=<?php echo $nombre_fichero; ?>&step=1');
				</script><?php
			
			}						
		
		}						
	
	}else{
	
		//Error ->
		?><span style="font-family:Arial, Helvetica, sans-serif; font-size:1em;">&nbsp;&nbsp;&nbsp;Copiando fichero excel: <strong style="color:#ee0000;">Se ha producido un error -> no se encuentra el fichero excel.</strong></span><?php
	
	}

}else{

	$aux = (int)$_GET['aux'];
	$step = (int)$_GET['step'];
	$nombre_fichero = $_GET['fichero_excel'];
	
	switch($step){
		
		//Proceso inicial ->
		case 0:
		
			?><script type="text/javascript">
            $('#result_importar').append('<div id="content_copiando_fichero" name="content_copiando_fichero" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:1em; margin-top:10px; display:block;">&nbsp;<img src="images/cargando_min.gif" width="36" height="12" border="0"/>&nbsp;&nbsp;&nbsp;Copiando fichero excel</div>');
            </script><?php
		
		break;	

		//Borrar Backup ->
		case 1:
			
			$delete_bk_query = db_query($link,"DELETE FROM textos_bk") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
			$delete_bk_query_2 = db_query($link,"DELETE FROM textos_content_bk") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
			
			?>&nbsp;&nbsp;&nbsp;Borrando copia de seguridad anterior: <strong style="color:#076702;">correcto</strong><?php
			
			//Siguiente proceso ->
			?><script type="text/javascript">
			$('#result_importar').append('<div id="content_create_sql_backup" name="content_create_sql_backup" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:1em; margin-top:10px; display:block;">&nbsp;<img src="images/cargando_min.gif" width="36" height="12" border="0"/>&nbsp;&nbsp;&nbsp;Generando copia de seguridad</div>');
			$('#content_create_sql_backup').load('ajax_textos_import.php?fichero_excel=<?php echo $nombre_fichero; ?>&step=2');
			</script><?php
		
		break;
		
		//Crear nuevo Backup ->
		case 2:
			
			$c2 = db_query($link,"INSERT textos_bk SELECT * FROM textos") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
			$cc2 = db_query($link,"INSERT textos_content_bk SELECT * FROM textos_content") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
			
			?>&nbsp;&nbsp;&nbsp;Generando copia de seguridad: <strong style="color:#076702;">correcto</strong><?php
			
			//Siguiente proceso ->
			?><script type="text/javascript">
			$('#result_importar').append('<div id="content_process_xlsx_0" name="content_process_xlsx_0" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:1em; margin-top:10px; display:block;">&nbsp;<img src="images/cargando_min.gif" width="36" height="12" border="0"/>&nbsp;&nbsp;&nbsp;Procesando datos del fichero excel</div>');
			$('#content_process_xlsx_0').load('ajax_textos_import.php?fichero_excel=<?php echo $nombre_fichero; ?>&step=3&aux=0');
			</script><?php
			
		break;

		//Procesar datos del excel ->
		case 3:	
			
			//Idiomas ->
			$idiomas = db_query($link,"SELECT * FROM config_idiomes") or die("Se ha producido un error en la base de datos: " . mysqli_error($link)); 
			
			unset($array_idiomas);
			
			while($idioma = mysqli_fetch_array($idiomas)){
			
				$array_idiomas[] = $idioma;
			
			}
			
			//Ahora proceso los datos del excel ->
			$cantidad = 100;
			$fichero = "./textos/".$nombre_fichero.".xls";
			
			//Incluyo la clase ->
			require_once dirname(__FILE__) . '/Excel/PHPExcel.php';

			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
			$cacheSettings = array( ' memoryCacheSize ' => '8MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

			//Filtro para leer sólo algunas filas (problemas de memoria con ficheros grandes) ->
			class MyReadFilter implements PHPExcel_Reader_IReadFilter
			{
				public function readCell($column, $row, $worksheetName = '') {
					
					global $aux, $cantidad;
					
					if ($row > ($aux*$cantidad) && $row <= (($aux+1)*$cantidad)) {
						return true;
					}
			
					return false;
				}
			}
			
			//Cargo el fichero->
			$objReader = PHPExcel_IOFactory::createReaderForFile($fichero);
			$objReader->setReadFilter( new MyReadFilter() );
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($fichero);
			
			//Declaro la primera y única hoja como activa
			$objPHPExcel->setActiveSheetIndex(0);
			
			$cantidad_filas = $objPHPExcel->getActiveSheet()->getHighestRow();
			
			for ($i = (($cantidad*$aux)+1); $i <= $cantidad_filas; $i++) {
				
				//La fila inicial son las cabeceras ->
				if($i>1){
					
					?><div style="display:block; padding-bottom:2px;"><?php
					
					unset($array_excel);
					
					for($char = "A";$char!="Z";$char++){
						
						$array_excel[$char] = (trim($objPHPExcel->getActiveSheet()->getCell($char.''.$i)->getValue()," \t\n\r\0\x0B\xA0"));
						
						if(!(mb_detect_encoding($array_excel[$char], 'UTF-8', true))){
						
							$array_excel[$char] = utf8_encode($array_excel[$char]);
						
						}
					
					}
										
					unset($text_result_array);
					
					$text_nodisabled = '';
					
					$text_nodisabled = $array_excel["A"];

					$b = db_query($link,"SELECT * FROM textos WHERE text_nodisabled='".db_slashes($link,$text_nodisabled)."'") or die("Se ha producido un error en la base de datos: " . mysqli_error($link)); 
					
					//Actualizo ->
					if(mysqli_num_rows($b)>0){
					
						$text_row = mysqli_fetch_array($b);
						$id = $text_row['id'];
					
					//Inserto ->
					}else{
					
						$insert_key = db_query($link,"INSERT INTO textos (text_nodisabled) VALUES ('".db_slashes($link,$text_nodisabled)."')") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
						$id = mysqli_insert_id($link);
						
						$text_result_array[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong style="color:#076702;">[Fila '.$i.'| Clave '.$text_nodisabled.' a&ntilde;adida]</strong>';
					
					}

					//Ahora recorro los idiomas ->
					$aux_char = "B";
					
					foreach($array_idiomas as $value){
						
						$color = '076702';
						
						$textarea_titulo = '';
						
						$textarea_titulo = $array_excel[$aux_char];
						
						//Campo vacío ->
						if($textarea_titulo==''){
						
							$color = 'f70';
							
						}
						
						$c = db_query($link,"SELECT * FROM textos_content WHERE id='".(int)$id."' AND idioma = '".$value['nom_idioma']."'") or die("Se ha producido un error en la base de datos: " . mysqli_error($link)); 
						
						//Actualizo ->
						if(mysqli_num_rows($c)>0){
							
							$update_text = db_query($link,"UPDATE textos_content SET textarea_titulo = '".db_slashes($link,$textarea_titulo)."' WHERE id='".(int)$id."' AND idioma='".$value['nom_idioma']."'") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
							
							$text_result_array[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Fila '.$i.'| Registro '.$text_nodisabled.' - '.$value['nom_idioma'].'] <strong style="color:#'.$color.';">actualizado</strong>';
						
						//Inserto ->
						}else{
						
							$insert_text = db_query($link,"INSERT INTO textos_content (id, idioma, textarea_titulo) VALUES ('".(int)$id."','".$value['nom_idioma']."','".db_slashes($link,$textarea_titulo)."')") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
						
							$text_result_array[] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Fila '.$i.'| Registro '.$text_nodisabled.' - '.$value['nom_idioma'].'] <strong style="color:#'.$color.';">a&ntilde;adido</strong>';
						
						}
						
						$aux_char++;
					
					}
					
					if(is_array($text_result_array)){
					
						echo implode('<br />',$text_result_array);
					
					}
					
					?></div><?php
				
				}
				
			}
			
			//Libero memoria ->
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);
			
			//Si estoy procesando el fichero ->
			if($cantidad_filas==($cantidad*($aux+1))){

				$aux++;
				
				?><script type="text/javascript">
				$('#result_importar').append('<div id="content_process_xlsx_<?php echo $aux; ?>" name="content_process_xlsx_<?php echo $aux; ?>" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:1em; margin-top:10px; display:block;">&nbsp;<img src="images/cargando_min.gif" width="36" height="12" border="0"/>&nbsp;&nbsp;&nbsp;Procesando datos del fichero excel</div>');
				$('#content_process_xlsx_<?php echo $aux; ?>').load('ajax_textos_import.php?fichero_excel=<?php echo $nombre_fichero; ?>&step=3&aux=<?php echo $aux; ?>');
				</script><?php
			
			//Si ya he terminado ->
			}else{
				
				//Printo resultado correcto ->
				?><div style="display:block; padding-top:10px;">&nbsp;&nbsp;&nbsp;Procesando datos del fichero excel: <strong style="color:#076702;">correcto</strong></div><?php
				
				//Creo el registro de control ->
				$fecha = date("Y-m-d H:i:s");
				$u1 = db_query($link,"INSERT INTO txtcontrol(fecha,operacion) VALUES('".$fecha."','".utf8_encode('Inserción de textos')."')") or die("Se ha producido un error en la base de datos: " . mysqli_error($link));
				
				//Siguiente proceso ->
							
				?><script type="text/javascript">
				$('#result_importar').append('<div id="content_borrando_fichero" name="content_borrando_fichero" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:1em; margin-top:10px; display:block;">&nbsp;<img src="images/cargando_min.gif" width="36" height="12" border="0"/>&nbsp;&nbsp;&nbsp;Borrando fichero excel</div>');
				$('#content_borrando_fichero').load('ajax_textos_import.php?fichero_excel=<?php echo $nombre_fichero; ?>&step=4');
				</script><?php
			
			}						
		
		break;		
		
		//Borro el fichero ->
		case 4:
		
			$fichero = "./textos/".$nombre_fichero.".xls";
			
			if(unlink($fichero)){

				?>&nbsp;&nbsp;&nbsp;Borrando fichero excel: <strong style="color:#076702;">correcto</strong>
				<p align="center"><a href="textos.php">Continuar</a></p>
				<p>&nbsp;</p><?php
			
			}else{
			
				//Error ->
				?>&nbsp;&nbsp;&nbsp;Borrando fichero excel: <strong style="color:#ee0000;">Se ha producido un error -> no se ha podido borrar el fichero excel.</strong>
				<p>&nbsp;</p><?php
			
			}
			
		break;	

	}
	
	?><script type="text/javascript">
	$('#result_importar').scrollTop($('#result_importar')[0].scrollHeight);
	</script><?php

}

//Cierro conexión BBDD ->
mysqli_close($link);

?>