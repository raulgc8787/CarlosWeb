<?php

$table_content = $table."_content";
$cols = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table;
$cols_content = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content;
$action = $_GET['action'];

if(!isset($_GET['action'])){
	$action = "list";
}

$_SESSION['action'] = $action;

include("auto_functions.php"); //Incluimos las funciones que hacen las cosas automaticamente con los campos

$fichero_actual = $_SERVER['PHP_SELF'];
$barra = strrpos($fichero_actual,"/");
$fichero_actual = substr($fichero_actual,$barra+1,strlen($fichero_actual)-$barra);

if($hay_fotouploader == 1){
	
	if($action == "update"){
		$c = db_query($link,$cols);
		while($rc = mysqli_fetch_array($c)){
			$field = $rc[0];
			//Busco el nombre de la tabla del fileuploader
			if(substr($field,0,12) == "fotouploader"){
				$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
					
				//Contare cuantas fotos hay ya subidas en el uploader con esta id
				$id = $_GET['id'];
				$c = db_query($link,"SELECT * FROM ".$nt." WHERE id_elem=".$id);
				$cuantas = mysqli_num_rows($c);
				
				//tengo el nombre de la tabla, miro cuantas fotos tiene el elemento en cuestion
				$quedanXfotos[$nt] = $max_fotos[$nt] - $cuantas; 
			}
		}		
		
		$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
		$tableExists = mysqli_num_rows($result) > 0;
		if($tableExists){
			
			//Hare esto por cada idioma
			$i = db_query($link,"SELECT * FROM config_idiomes");
			while($ar_i = mysqli_fetch_array($i)){
			$idioma_admin = $ar_i['nom_idioma'];
		
				$c = db_query($link,$cols_content) or die(mysqli_error($link));
				while($rc = mysqli_fetch_array($c)){
					$field = $rc[0];
					//Busco el nombre de la tabla del fileuploader
					if(substr($field,0,12) == "fotouploader" || substr($field,0,12) == "bannuploader"){
						$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
						
						//Contare cuantas fotos hay ya subidas en el uploader con esta id Y POR CADA IDIOMA
						$id = $_GET['id'];
						//echo "xroy: SELECT * FROM ".$nt." WHERE id_elem=".$id." AND idioma='".$idioma_admin."'";
						$c = db_query($link,"SELECT * FROM ".$nt." WHERE id_elem=".$id." AND idioma='".$idioma_admin."'");
						$cuantas = mysqli_num_rows($c);
		
						//tengo el nombre de la tabla, miro cuantas fotos tiene el elemento en cuestion
						$quedanXfotos[$nt][$idioma_admin] = $max_fotos[$nt] - $cuantas; 
					}//end if
				}//end while
			
			}//end while idiomes
		
		}
	
		//$quedanXfotos = $max_fotos;
		include("head_fileuploader.php");
		
	}// end if action
	
}
if($hay_locationmap == 1){
	//Se que en esta tabla tenemos un location asi que llamo a un archivo con los scripts propios de esto. Si no lo hay, comentamos esta linea
	include("head_location.php");
}
if($ordenable == 1){
	include("head_sort.php");
}

//ACCIONES DE FORMULARIO
if(isset($_POST['btn_insert'])){
	//Hacemos insercion
	$last_id = do_insert();
	//Vamos a la pagina de modificacion
	$action = 'update';
	$_GET['id'] = $last_id;
}
if(isset($_POST['btn_update'])){
	$id = $_GET['id'];
	do_update($id);
	$action = 'update';
}
//END ACCIONES DE FORMULARIO

//BORRAR REGISTROS DEL LISTADO
if(isset($_POST['borrar'])){
	if(!empty($_POST['seleccion'])){
		$llista=array_keys($_POST['seleccion']);

		$query="SELECT * FROM ".$table." WHERE id IN (".implode(',',$llista).")"; 
		$result=db_query($link,$query) or die(mysqli_error());

		//Borrar el mazacote de cosas
		
		$d1 = mysqli_query($link,"DELETE FROM ".$table_content." WHERE id IN (".implode(',',$llista).")");
		$d2 = mysqli_query($link,"DELETE FROM ".$table." WHERE id IN (".implode(',',$llista).")");
	}
}
//END BORRAR

// ****
// DUPE
// ****
if($action == "dupe"){
	
	$id = $_GET['id'];
	
	//$d = db_query($link,"INSERT INTO ".$table."(text_titulo, select_xproductos) SELECT text_titulo, select_xproductos FROM ".$table." WHERE id='".$id."'") or die(mysqli_error($link));
	$d = db_query($link,"SELECT text_titulo, select_xproductos FROM ".$table." WHERE id='".$id."'");
	$dupe = mysqli_fetch_array($d);
	$i = db_query($link,"INSERT INTO ".$table."(text_titulo, select_xproductos) VALUES('".$dupe['text_titulo']." - DUPLICADA','".$dupe['select_xproductos']."')");
	$nuevaid = mysqli_insert_id($link);	
	
	$d_es = db_query($link,"SELECT text_nombre FROM ".$table."_content WHERE id='".$id."' AND idioma='es'");
	$dupe_es = mysqli_fetch_array($d_es);
	$n_es = $dupe_es['text_nombre'];
	$i_es = db_query($link,"INSERT INTO ".$table."_content(id,idioma,text_nombre) VALUES(".$nuevaid.",'es','".$n_es."')");
	
	$d_ca = db_query($link,"SELECT text_nombre FROM ".$table."_content WHERE id='".$id."' AND idioma='ca'");
	$dupe_ca = mysqli_fetch_array($d_ca);
	$n_ca = $dupe_ca['text_nombre'];
	$i_ca = db_query($link,"INSERT INTO ".$table."_content(id,idioma,text_nombre) VALUES(".$nuevaid.",'ca','".$n_ca."')");
	
	$d_en = db_query($link,"SELECT text_nombre FROM ".$table."_content WHERE id='".$id."' AND idioma='en'");
	$dupe_en = mysqli_fetch_array($d_en);
	$n_en = $dupe_es['text_nombre'];
	$i_en = db_query($link,"INSERT INTO ".$table."_content(id,idioma,text_nombre) VALUES(".$nuevaid.",'en','".$n_en."')");
	
	$d_de = db_query($link,"SELECT text_nombre FROM ".$table."_content WHERE id='".$id."' AND idioma='de'");
	$dupe_de = mysqli_fetch_array($d_de);
	$n_de = $dupe_es['text_nombre'];
	$i_de = db_query($link,"INSERT INTO ".$table."_content(id,idioma,text_nombre) VALUES(".$nuevaid.",'de','".$n_de."')");
	
	$d_fr = db_query($link,"SELECT text_nombre FROM ".$table."_content WHERE id='".$id."' AND idioma='fr'");
	$dupe_fr = mysqli_fetch_array($d_fr);
	$n_fr = $dupe_fr['text_nombre'];
	$i_fr = db_query($link,"INSERT INTO ".$table."_content(id,idioma,text_nombre) VALUES(".$nuevaid.",'fr','".$n_fr."')");
	
	$_GET['id'] = $nuevaid;
}
// END DUPE

// ****
// SORT
// ****
if($action == "sort"){		
	?>
    
    <div class="section-header">
    	<h1><?php echo  $backoffice['tit_ordenar'][$_SESSION['bo_idioma']]." ".$sufijo_plural;?></h1>
	</div>
    <br /><br /><br /><br />
    <style type="text/css">
	.tempo{
		width:98%; padding:.6em 1%;background-color:#EEE; border-radius:3px; margin-left:0; margin-bottom:5px; cursor:pointer;
	}
	</style>
    <ul id="sortme<?php echo $table;?>">
	<?php
    $it = 1;
	$ci = db_query($link,"SELECT * FROM config_idiomes WHERE id_idioma=1");
	$cidioma = mysqli_fetch_array($ci);
    //Leo las fotos en el orden en que estan guardades en la DB
    $s = db_query($link,"SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".$cidioma['nom_idioma']."') ORDER BY t.orden ASC");
	while($sort = mysqli_fetch_array($s)){
		 echo '<li id="'.$sort['id'].'" class="sortitem'.$table.' tempo"> 
			   	   '.$sort[$pinta_listar].'	   
			   </li>';
		$it++;
	}

    ?>
    </ul>
    
    <div id="showmsg<?php echo $table;?>" class="mensajes"></div>
    
    <?php
}
// END SORT


// ****
// LIST
// ****
if($action == "list"){		

	?>
    <div class="section-header">
    	<h1><?php echo  $backoffice['tit_gestion_de'][$_SESSION['bo_idioma']]." ".$sufijo_plural;?></h1>
	</div>
    
    <?php

	// Si no hay idioma seteado en la url, cargo el primer idioma de la db
	if(!isset($_GET['idioma'])){
		$i = db_query($link,"SELECT * FROM config_idiomes WHERE id_idioma='1'");
		$ari = mysqli_fetch_array($i);
		$nom_idioma = $ari['nom_idioma'];
		$_SESSION['idioma'] = $nom_idioma;
	} else{
		$_SESSION['idioma'] = $_GET['idioma'];
	}	
	?>
    
    <?php
	if(!isset($_GET['pag']) && !isset($_POST['search']) && !isset($_GET['action'])){
		unset($_SESSION['search']);
	}
	if(isset($_POST['search']) || isset($_SESSION['search'])){
		
		if(isset($_POST['search'])){ $_SESSION['search'] = $_POST['search']; }
		
			$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
			$tableExists = mysqli_num_rows($result) > 0;
			
			if($tableExists){
				
				//Compruebo si el campo pertenece al content o no ->
				$result_cols = db_query($link,"SHOW FULL COLUMNS FROM ".$DATABASE.".".$table." LIKE '".$pinta_listar."'");
				$result_cols_content = db_query($link,"SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content."  LIKE '".$pinta_listar."'");
				
				//Pertenece a las dos ->
				if((mysqli_num_rows($result_cols)>0) && (mysqli_num_rows($result_cols_content)>0)){
					
					$subconsulta = " AND (t.".$pinta_listar." LIKE '%".$_SESSION['search']."%' OR tc.".$pinta_listar." LIKE '%".$_SESSION['search']."%')";
					$subconsulta2 = " WHERE (t.".$pinta_listar." LIKE '%".$_SESSION['search']."%' OR tc.".$pinta_listar." LIKE '%".$_SESSION['search']."%')";			
				
				}else{
				
					//Pertenece sólo al content ->
					if(mysqli_num_rows($result_cols_content)>0){
						
						$subconsulta = " AND tc.".$pinta_listar." LIKE '%".$_SESSION['search']."%'";
						$subconsulta2 = " WHERE tc.".$pinta_listar." LIKE '%".$_SESSION['search']."%'";
					
					//Pertenece sólo a la general ->
					}else{
						
						$subconsulta = " AND t.".$pinta_listar." LIKE '%".$_SESSION['search']."%'";
						$subconsulta2 = " WHERE t.".$pinta_listar." LIKE '%".$_SESSION['search']."%'";
	
					}
				
				}
			
			}else{
			
				$subconsulta = " AND ".$pinta_listar." LIKE '%".$_SESSION['search']."%'";
				$subconsulta2 = " WHERE ".$pinta_listar." LIKE '%".$_SESSION['search']."%'";
			
			}
		
		} else{
			$subconsulta = "";
			$subconsulta2 = "";
		}
	?>
    
	<form method="post" action=""><input type="text" name="search" id="search" value="<?php echo $_SESSION['search'];?>" /><input type="submit" name="buscar" value="<?php echo $backoffice['btn_buscar'][$_SESSION['bo_idioma']];?>" class="busca"></form>
    
    <?php
	if($ordenable == 1){
		echo '<h3><img src="images/icons/sort.png" class="sort-icon" /><a href="?action=sort">'.$backoffice['tit_ordenar'][$_SESSION['bo_idioma']]." ".$sufijo_plural.'</a></h3>';
	}
	?>
    
	<?php
	// ***
	// Pestañas idiomas
	// ***
	
	$nuevo = $_SESSION['idioma'];
	
	include("pestanyas_idiomas_open.php");	

	
	//Cargo el contenido para cada idioma
	$lo = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
	while($arlo = mysqli_fetch_array($lo)){
		$idioma_admin = $arlo['nom_idioma'];
		echo '<div id="idioma'.$idioma_admin.'"';
		if($arlo['nom_idioma'] == $nuevo){
			echo ' class="idiomaActive" ';
		} else{
			echo ' class="idiomaInactive" ';
		}
		echo '>';
			
		// ***
		// Paginacion
		// ***
	
		$este_fichero = $_SERVER['PHP_SELF'];
		$porpagina = 15;
		if ((isset($_GET["pag"])) && (!isset($_POST["pagina"]))) $pagina= $_GET["pag"];
		if (!isset($pagina)) $pagina=1;
		$limite = " LIMIT ".(($pagina-1)*$porpagina).",".$porpagina;
		// Consulta per saber el total de projectes que compleixen
		$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
		$tableExists = mysqli_num_rows($result) > 0;
		if($tableExists){
			$sql = "SELECT COUNT(*) FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id = tc.id AND tc.idioma = idioma='".$arlo['nom_idioma']."') WHERE t.id!='0' ".$subconsulta;
		} else{
			$sql = "SELECT COUNT(*) FROM ".$table." ".$subconsulta2;
		}
		$resultpagina = db_query($link,$sql);
		$linepagina = mysqli_fetch_array($resultpagina);
		$total_buscats_paginas = $linepagina["COUNT(*)"];
		// ***
		// END Paginacion
		// ***
		?>
		
        <form method="post" name="formBorra" id="formBorra" onsubmit="return validar_borrar()">
		<table border="0">
		<?php
		
		$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
		$tableExists = mysqli_num_rows($result) > 0;
		if($tableExists){
			$listar = "SELECT * FROM ".$table." t LEFT JOIN ".$table_content." tc ON (t.id=tc.id AND tc.idioma='".$idioma_admin."') WHERE t.id!='0' ".$subconsulta." ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;
		} else{
			$listar = "SELECT * FROM ".$table." t ORDER BY ".$campo_listar." ".$orden_listar." ".$limite;
		}
		
		$l = db_query($link,$listar);
		
		$is_icons = false;
		
		if($tableExists){
		
			$is_checkbox_visible = (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE 'checkbox_visible'")) + (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table_content." LIKE 'checkbox_visible'"));
			
			$is_checkbox_portada = (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE 'checkbox_portada'")) + (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table_content." LIKE 'checkbox_portada'"));
			
			$is_checkbox_destacado = (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE 'checkbox_destacado'")) + (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table_content." LIKE 'checkbox_destacado'"));
		
		}else{

			$is_checkbox_visible = (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE 'checkbox_visible'"));
			
			$is_checkbox_portada = (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE 'checkbox_portada'"));
			
			$is_checkbox_destacado = (int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE 'checkbox_destacado'"));
		
		}
		
		if(($is_checkbox_visible + $is_checkbox_portada + $is_checkbox_destacado)>0){
		
			$is_icons = true;
			
		}
		
		$is_images = false;
		
		if($tableExists){
		
			if((int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE '%file_foto%'"))+(int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table_content." LIKE '%file_foto%'"))+(int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE '%fotouploader%'"))+(int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table_content." LIKE '%fotouploader%'"))){
			
				$is_images = true;
				
			}
		
		}else{

			if((int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE '%file_foto%'"))+(int)mysqli_num_rows(db_query($link,"SHOW COLUMNS FROM ".$table." LIKE '%fotouploader%'"))){
			
				$is_images = true;
				
			}
		
		}

		if(mysqli_num_rows($l)>0){
			
			?><tr>
            
            <?php if($is_icons){ ?>
            <th width="66"></th>
            <?php } ?>
            
            <?php if($is_images){ ?>
            <th width="70"></th>
            <?php } ?>
            
            <th></th>
			<th></th>
            
			<?php if($no_borrar == 0){?>
            <th width="90" align="center">
            <input type="submit" name="borrar" value="<?php echo $backoffice['borrar'][$_SESSION['bo_idioma']];?>" class="form_submit"/>
            </th>
			<?php } ?>
            
            </tr><?php
			
			while($list = mysqli_fetch_array($l)){
				echo '<tr>';
				
				if($is_icons){
				
					echo '<td><div id="icons'.$list['id'].'" class="icons-actions">';
					if($list['checkbox_visible'] == "1"){
						echo '<img src="images/icons/visible.png" title="Visible" onclick="cambiaStatus(\''.$table.'\',\'checkbox_visible\',0,'.$list['id'].')" />&nbsp;';
					} else if(isset($list['checkbox_visible'])){
						echo '<img src="images/icons/visibleno.png" title="No visible" onclick="cambiaStatus(\''.$table.'\',\'checkbox_visible\',1,'.$list['id'].')" />&nbsp;';
					}
					if($list['checkbox_portada'] == "1"){
						echo '<img src="images/icons/portada.png" title="Destacado en portada" onclick="cambiaStatus(\''.$table.'\',\'checkbox_portada\',0,'.$list['id'].')" />&nbsp;';
					} else if(isset($list['checkbox_portada'])){
						echo '<img src="images/icons/portadano.png" title="No destacado en portada" onclick="cambiaStatus(\''.$table.'\',\'checkbox_portada\',1,'.$list['id'].')" />&nbsp;';
					}
					if($list['checkbox_destacado'] == "1"){
						echo '<img src="images/icons/destacado.png" title="Destacado" onclick="cambiaStatus(\''.$table.'\',\'checkbox_destacado\',0,'.$list['id'].')" />&nbsp;';
					} else if(isset($list['checkbox_destacado'])){
						echo '<img src="images/icons/destacadono.png" title="No destacado" onclick="cambiaStatus(\''.$table.'\',\'checkbox_destacado\',1,'.$list['id'].')" />&nbsp;';
					}				
					echo '</div></td>';
				
				}
				
				if($is_images){ 
				
					echo '<td class="pic-col"><div>';
					//Foto
					$result = db_query($link,"SHOW TABLES LIKE '".$table."_content'");
					$tableExists = mysqli_num_rows($result) > 0;
					if($tableExists){
						$r = db_query($link,"SELECT * FROM ".$table." t, ".$table."_content tc WHERE t.id=tc.id AND t.id=".$list['id']);
					} else{
						$r = db_query($link,"SELECT * FROM ".$table." WHERE id=".$list['id']);
					}	
					$registro = mysqli_fetch_array($r);
					if($registro['file_foto'] != ""){
						echo '<img src="../images/'.$table.'/th_'.$registro['file_foto'].'" style="height:60px;" />';
					} else{
						//O foto de uploader, averiguo si hay campo de fileuploader
						$list_cols = db_query($link,$cols);
						while($lista = mysqli_fetch_array($list_cols)){
							$field = $lista[0];
							if(substr($field,0,12) == "fotouploader"){						
								$tablafotos = substr($field,13,strlen($field)-13);						
								$ff = db_query($link,"SELECT * FROM ".$tablafotos." WHERE id_elem=".$list['id']." AND orden=0 LIMIT 1");
								$ffoto = mysqli_fetch_array($ff);
								if($ffoto['foto'] != ""){
									echo '<img src="../pics_'.$tablafotos.'/'.$list['id'].'/th_'.$ffoto['foto'].'" style="height:60px;" />';
								}
							}
						}
					}
					echo '</div></td>';
				
				}
				
				echo '<td>'.$list[$pinta_listar];
				if($table == "noticias"){
					$c = mysqli_query($link,"SELECT * FROM categorias t, categorias_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.id='".$list['select_categorias']."'");
					$categoria = mysqli_fetch_array($c);
					if($categoria['text_titulo'] == "") $categoria['text_titulo'] = "PENDIENTE DE ASIGNAR";
					echo " <i>(".$categoria['text_titulo'].")</i>";
				} else if($table == "servicios"){
					$c = mysqli_query($link,"SELECT * FROM categoriass t, categoriass_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.id='".$list['select_categoriass']."'");
					$categoria = mysqli_fetch_array($c);
					if($categoria['text_titulo'] == "") $categoria['text_titulo'] = "PENDIENTE DE ASIGNAR";
					echo " <i>(".$categoria['text_titulo'].")</i>";
				} else if($table == "faq"){
					$c = mysqli_query($link,"SELECT * FROM categoriass t, categoriass_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' AND t.id='".$list['select_categoriass']."'");
					$categoria = mysqli_fetch_array($c);
					if($categoria['text_titulo'] == "") $categoria['text_titulo'] = "PENDIENTE DE ASIGNAR";
					echo " <i>(".$categoria['text_titulo'].")</i>";
				}
				echo '</td><td align="center"><a href="'.$este_fichero.'?action=update&id='.$list['id'].'">'.$backoffice['btn_editar'][$_SESSION['bo_idioma']].'</a></td>';
				if($no_borrar == 0){
					echo '<td align="center"><input type="checkbox" name="seleccion['.$list['id'].']" /></td>';
				}
				echo '</tr>';
			}
		
			?><tr>
            <?php if($is_icons){ ?>
            <td></td>
            <?php } ?>
            
            <?php if($is_images){ ?>
            <td></td>
            <?php } ?>
            
            <td></td>
            <td></td>
            
            
			<?php if($no_borrar == 0){?>
            <td>
            <input type="submit" name="borrar" value="<?php echo $backoffice['borrar'][$_SESSION['bo_idioma']];?>" class="form_submit" onclick="validar_borrar()"/>
            </td>
			<?php } ?>

            </tr><?php
		
		}else{
		
			?><tr><td colspan="5">
			<p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p><?php echo $backoffice['l_no_results'][$_SESSION['bo_idioma']];?></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
			</td></tr><?php
		
		}
		?>
		</table>
        </form>
		
		<?php
		// ***
		// Paginacion
		// ***
		$total_paginas = (int)($total_buscats_paginas/$porpagina)+1;
		
		if($total_paginas > 1){
		
			$numpagina=1;
			$pag = $_GET['pag'];
			if(!isset($pag)) $pag = 1;
			// If de la primera p&aacute;gina 
			if ($pag == 1) {
				echo "<span class=\"current\">".$numpagina."</span>"; 
			}
			else {
				echo " <a href=\"".$este_fichero."?action=list&pag=".$numpagina."\" class=\"num\">".$numpagina."</a>";  
			}
			if($pagina >= 5) echo '<span>...</span>&nbsp;';
			
			$numpagina=1;
			
			for ($cont=2;$cont<$total_buscats_paginas;$cont++) { 
				if (($cont%$porpagina)==0){
					if ($cont==($pagina*$porpagina)) {
						if( ($numpagina >= $pagina-2) && ($numpagina <= $pagina + 2) && $numpagina > 1){	
							echo "&nbsp;<span class=\"current\">".$numpagina."</span>&nbsp;"; 						
						}
						$numpagina++;
					}
					else {
						if( ($numpagina >= $pagina-2) && ($numpagina <= $pagina + 2) && $numpagina > 1){
							echo "&nbsp;<a href=\"".$este_fichero."?action=list&pag=".$numpagina."\" class=\"num\">".$numpagina."</a>"; 
						}
						$numpagina++;
					}
				}
			}
			// If de la ultima p&aacute;gina 
			if($pagina <= ($numpagina-4)) echo '&nbsp;<span>...</span>';
			if ((($numpagina-1)*$porpagina)<=($cont-1) && ((($numpagina-1)*$porpagina)!=0)) {
					if ($numpagina==$pagina) {
						echo "&nbsp;<span class=\"current\">".$numpagina."</span>&nbsp;"; 
					}
					else {
						echo "&nbsp;<a href=\"".$este_fichero."?action=list&pag=".$numpagina."\" class=\"num\">".$numpagina."</a>"; 
					}
			
			}
			
			if($pagina > 1){
				$ant = $pagina-1;
				echo '<a class="prev" href="'.$este_fichero."?action=list&pag=".$ant.'" style="margin-left:15px;">'.$backoffice['anterior'][IDIOMA].'</a>';
	
			}
			if ((($numpagina-1)*$porpagina)<=($cont-1) && ((($numpagina-1)*$porpagina)!=0)) {
				if ($numpagina!=$pagina) {
					$sig = $pagina + 1;
					echo '<a class="next" href="'.$este_fichero."?action=list&pag=".$sig.'" style="margin-left:15px;">'.$backoffice['siguiente'][IDIOMA].'</a>';
				}
			}
		
		}
		// ***
		// END Paginacion
		// ***

			
		echo '</div>';
	}
	// ***
	// END Pestañas idiomas
	// ***

}
// END LIST


// ******
// INSERT
// ******
if($action == "insert"){
	unset($_SESSION['id']);
	?>    
    
    <div class="section-header">
    	<h1><?php echo $backoffice['tit_crear'][$_SESSION['bo_idioma']]." ".$sufijo_singular;?></h1>
	</div>

    
	<form method="post" enctype="multipart/form-data" name="insert" id="insert">
    
    	<div id="mensajeFormulario" class="mensajes"></div>
	
		<?php	
		$list_cols = db_query($link,$cols);
		while($list = mysqli_fetch_array($list_cols)){
			$field = $list[0];
			$type = $list[1];
			$comment = $list[8];
			$value = '';
			print_field($field,$comment,$value,"");
		}
		
		$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
		$tableExists = mysqli_num_rows($result) > 0;
		if($tableExists){
	
			// ***
			// Pestañas idiomas
			// ***
			include("pestanyas_idiomas_open.php");			
			//Cargo el contenido para cada idioma
			$lo = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
			while($arlo = mysqli_fetch_array($lo)){
				$idioma_admin = $arlo['nom_idioma'];
				echo '<div id="idioma'.$idioma_admin.'"';
				if($_SESSION['idioma'] == $idioma_admin){
					echo ' class="idiomaActive" ';
				} else{
					echo ' class="idiomaInactive" ';
				}
				echo '><ul>';				
						
				//Calculamos la id de este nuevo elemento
				$nid = db_query($link,"SELECT MAX(id) AS max FROM ".$table);
				$nuevaid = mysqli_fetch_array($nid);
				$max = $nuevaid['max'];
				$nueva_id = $max + 1;
				?>
			
				<li><span>&nbsp;</span><input type="hidden" name="id_<?php echo $idioma_admin;?>" id="id_<?php echo $idioma_admin;?>" value="<?php echo $nueva_id;?>">
				<input type="hidden" name="idioma_<?php echo $idioma_admin;?>" id="idioma_<?php echo $idioma_admin;?>" value="<?php echo $idioma_admin;?>" /></li>
				
				<?php	
				//echo $cols_content;
				$list_cols_content = db_query($link,$cols_content);
				while($list_content = mysqli_fetch_array($list_cols_content)){
					$field = $list_content[0];
					$type = $list_content[1];
					$comment = $list_content[8];
					$value = '';
					print_field($field,$comment,$value,$idioma_admin);
				}
				?>
			
				<?php                
				echo '</ul></div>';
			}
			// ***
			// END Pestañas idiomas
			// ***
		
		}
		?>
		
		<div class="btns-footer"><input type="submit" value="<?php echo $backoffice['btn_crear'][$_SESSION['bo_idioma']];?>" name="btn_insert" id="btn_insert" />
        
	        <?php
			if($hay_fotouploader == 1){
				echo '<span>'.$backoffice['siguiente_paso'][$_SESSION['bo_idioma']].'</span>';
			}
			?>

		</div>
        
	</form>
	
	<?php
}
// END INSERT


// ******
// UPDATE
// ******
if($action == "update"){	
	?>   
    
    <div class="section-header">
    	<h1><?php echo $backoffice['tit_modificar'][$_SESSION['bo_idioma']]." ".$sufijo_singular;?></h1>
	</div>
    
    <form method="post" enctype="multipart/form-data" name="update" id="update">     
    
    	<div id="mensajeFormularioUpdate" class="mensajes"></div>
        
		<?php
    
        $id = $_GET['id'];
        $_SESSION['id'] = $id;
        $r = db_query($link,"SELECT * FROM ".$table." WHERE id=".$id);
        $row = mysqli_fetch_array($r);
    
        $list_cols = db_query($link,$cols);
        while($list = mysqli_fetch_array($list_cols)){
            $field = $list[0];
            $type = $list[1];
            $comment = $list[8];
            $value = $row[$field];
            print_field($field,$comment,$value,"");
        }
    	
		$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
		$tableExists = mysqli_num_rows($result) > 0;
		if($tableExists){
		
			// ***
			// Pestañas idiomas
			// ***
			include("pestanyas_idiomas_open.php");		            
			//Cargo el contenido para cada idioma
			$lo = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
			while($arlo = mysqli_fetch_array($lo)){
				$idioma_admin = $arlo['nom_idioma'];
				echo '<div id="idioma'.$idioma_admin.'"';
				if($_SESSION['idioma'] == $idioma_admin){
					echo ' class="idiomaActive" ';
				} else{
					echo ' class="idiomaInactive" ';
				}
				echo '><ul>';
			
				//Linea de este idioma
				$r_content = db_query($link,"SELECT * FROM ".$table_content." WHERE id=".$id." AND idioma='".$idioma_admin."'");
				$row_content = mysqli_fetch_array($r_content);
				?>
			
				<input type="hidden" name="id_<?php echo $idioma_admin;?>" id="id_<?php echo $idioma_admin;?>" value="<?php echo $id;?>">
				<input type="hidden" name="idioma_<?php echo $idioma_admin;?>" id="idioma_<?php echo $idioma_admin;?>" value="<?php echo $idioma_admin;?>" />
				<?php	
				$list_cols_content = db_query($link,$cols_content);
				while($list_content = mysqli_fetch_array($list_cols_content)){
					$field = $list_content[0];
					$type = $list_content[1];
					$comment = $list_content[8];
					$value = $row_content[$field];
					print_field($field,$comment,$value,$idioma_admin);
				}
				?>
			
				<?php
				echo '</ul></div>';
			}
			// ***
			// END Pestañas idiomas
			// ***
		
		}
		?>
		
		<input type="submit" value="<?php echo $backoffice['btn_modificar'][$_SESSION['bo_idioma']];?>" name="btn_update" id="btn_update" />
        
	</form>
	
	<?php
}
// END UPDATE







//ACCIONES DE FORMULARIO
if(isset($_POST['btn_insert'])){
	?>
	<script>
	function redirection(){  
	  window.location = "<?php echo $fichero_actual;?>?action=<?php echo $action;?>&id=<?php echo $last_id;?>";		
	}  setTimeout ("redirection()", 0); //tiempo en milisegundos
    </script>
	<?php
}
if(isset($_POST['btn_update'])){
	?>
	<script>
	pintaMensaje('mensajeFormularioUpdate','<?php echo $backoffice['registro_modificado_ok'][$_SESSION['bo_idioma']];?>')	
    </script>
	<?php
}


if(strpos($_SERVER['HTTP_REFERER'],"insert") > 1){
	?>
    <script>
	pintaMensaje('mensajeFormularioUpdate','<?php echo $backoffice['registro_creado_ok'][$_SESSION['bo_idioma']];?>')  
    </script>
    <?php
}
//END ACCIONES DE FORMULARIO
?>
