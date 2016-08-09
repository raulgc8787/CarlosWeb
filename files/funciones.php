<?php

/*
This script should be included to emulate magic quotes on.
If magic quotes are already on, we don't apply any changes to the variables.
*/

//this will set all other input data (from databases etc) to have slashes.
//set_magic_quotes_runtime(TRUE);
function addslashesArray($array)
{
    foreach ($array as $key => $val)
    {
        if (is_array($val))
        {
            $array[$key] = addSlashesArray($val);
        }
        else
        {
            $array[$key] = addslashes($val);
        }
    }
    return $array;
}

//if (!get_magic_quotes_gpc()){
    /*
    All these global variables are not slash-encoded by default,
    because magic_quotes_gpc is not set by default!
    (And magic_quotes_gpc affects more than just $_GET, $_POST, and $_COOKIE)
    */
    $_GET = addslashesArray($_GET);
    $_POST = addslashesArray($_POST);

//}

/*
The $GLOBALS array is also slash-encoded, but when all the above are
changed, $GLOBALS is updated to reflect those changes.  (Therefore
$GLOBALS should never be modified directly).  $GLOBALS also contains
infinite recursion, so it's dangerous...
*/ 

/*******************************************************************************************/ 
// conectar
// Conexion a la base de datos
// Entra :	global $HOSTNAME,$USERNAME,$PASSWORD,$DATABASE
// Devuelve :	Indice link de mysql_connect
/*******************************************************************************************/ 
function conectar(){

	global $HOSTNAME,$USERNAME,$PASSWORD,$DATABASE;

	$idcnx = mysqli_connect($HOSTNAME,$USERNAME,$PASSWORD,$DATABASE) or die("Imposible conectar con la base de datos: " . mysqli_connect_error());	
	
	$query_utf8_first = mysqli_query($idcnx,"set character_set_client='utf8'") or die("Se ha producido un error en la base de datos: " . mysqli_error($idcnx));
	$query_utf8_second = mysqli_query($idcnx,"set character_set_results='utf8'") or die("Se ha producido un error en la base de datos: " . mysqli_error($idcnx));
	$query_utf8_third = mysqli_query($idcnx,"set collation_connection='utf8_general_ci'") or die("Se ha producido un error en la base de datos: " . mysqli_error($idcnx));
	$query_utf8_fourth = mysqli_query($idcnx,"set names 'utf8';") or die("Se ha producido un error en la base de datos: " . mysqli_error($idcnx));
	
	return $idcnx;
}

/*******************************************************************************************/ 
// db_query
// Ejecutar query en la base de datos
// Entra :	query, link de mysql_connect
// Devuelve :	query o error
/*******************************************************************************************/ 
function db_query($link,$query){
	
	if(DEBUG_MODE=='1'){
	
		$result = mysqli_query($link,$query) or die("Se ha producido un error en la base de datos: " . mysqli_error($link) ." [".$query."]");
	
	}else{
	
		$result = mysqli_query($link,$query);
	
	}
	return $result;
	
}

function urls_amigables($url) {
	
	// Tranformamos todo a minusculas
	$url = strtolower($url);
	
	if($url!='index.html'){
	
	
		$array_from = array("À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");
		$array_to   = array("a","a","a","a","a","a","a","a","a","a","a","a","o","o","o","o","o","o","o","o","o","o","o","o","e","e","e","e","e","e","e","e","c","c","i","i","i","i","i","i","i","i","u","u","u","u","u","u","u","u","y","n","n");
	
		$url = (str_replace($array_from,$array_to,($url)));
		$url = preg_replace("/\s+/","-",$url);
		$url = preg_replace('/[^A-Za-z0-9\-]/','',$url);
		$url = preg_replace('/-[A-Za-z]{1}-/','-',$url);
		$url = preg_replace('/-[A-Za-z]{1}\Z/','',$url);
		
		$url = preg_replace('/-+/','-',$url);
	
	}
	
	$url = trim($url,"-");
	
	//Fuerzo minúsculas ->
	$url = strtolower($url);
	
	return $url;
}

function fecha($d,$separador="/"){
	$fecha = substr($d,8,2).$separador.substr($d,5,2).$separador.substr($d,0,4);
	return $fecha;
}


//verificar si es navegador movil
function navegadorMovil(){
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
	 
	if ($iphone || $android || $palmpre || $ipod || $berry || $ipad == true){
		$navMovil = true;
	} else{
		$navMovil = false;
	}
	
	return $navMovil;
}

//Mostrar la fecha en formato español
function fechaEsp($fecha_entrada){
	$fecha_salida = substr($fecha_entrada,8,2)."-".substr($fecha_entrada,5,2)."-".substr($fecha_entrada,0,4);
	return $fecha_salida;
}

function fechaEspExt($fecha_entrada){
	$fecha_salida = substr($fecha_entrada,8,2)." ".traduce_mes(substr($fecha_entrada,5,2), IDIOMA)." ".substr($fecha_entrada,0,4);
	return $fecha_salida;
}

//Conertir la fecha a formato amigable DB
function fechaDb($fecha_entrada){
	$fecha_salida = substr($fecha_entrada,6,4)."-".substr($fecha_entrada,3,2)."-".substr($fecha_entrada,0,2);
	return $fecha_salida;
}

/************ PAGINACIÓN **************/

function paginacion($total_paginas,$pg,$url){	
	$pg--;
	if($total_paginas>1){
		echo '<div class="paginacion">';
		//Genero la opción de ir por paginas 
		if($total_paginas>6){
			if($pg ==0){
				echo "&nbsp;<span class=\"numHIGH\">1</span>";
			}else{
				echo "&nbsp;<a href='".$url."pag=1' class=\"num\">1</a>";
			}
			if($pg<4){	
				for($i=1;$i<=4;$i++){
					$aux = $i+1;
					if($i == $pg){
						echo "&nbsp;<span class=\"numHIGH\">".$aux."</span>";
					}
					else{
						echo "&nbsp;<a href='".$url."pag=".$aux."' class=\"num\">".$aux."</a>";
					}
				}
				echo "<span class=\"separator\">&nbsp;...</span>";
			}else if($pg>($total_paginas-5)){
				echo "<span class=\"separator\">&nbsp;...</span>";
				for($i=($total_paginas-5);$i<($total_paginas-1);$i++){
					$aux = $i+1;
					if($i == $pg){
						echo "&nbsp;<span class=\"numHIGH\">".$aux."</span>";
					}
					else{
						echo "&nbsp;<a href='".$url."pag=".$aux."' class=\"num\">".$aux."</a>";
					}
				}
			}else{
				echo "<span class=\"separator\">&nbsp;...</span>";
				for($i=($pg-2);$i<=($pg+2);$i++){
					$aux = $i+1;
					if($i == $pg){
						echo "&nbsp;<span class=\"numHIGH\">".$aux."</span>";
					}
					else{
						echo "&nbsp;<a href='".$url."pag=".$aux."' class=\"num\">".$aux."</a>";
					}
				}
				echo "<span class=\"separator\">&nbsp;...</span>";
			}
			$aux = $total_paginas;
			if($aux == ($pg+1)){
				echo "&nbsp;<span class=\"numHIGH\">".$total_paginas."</span>";
			}else{
				echo "&nbsp;<a href='".$url."pag=".$aux."' class=\"num\">".$total_paginas."</a>";
			}
		}else if(($total_paginas>1)&&($total_paginas<=6)){
			//Caso hay pocas páginas
			for($i=0;$i<=($total_paginas-1);$i++){
				$aux = $i+1;
				if($i == $pg){
					echo "&nbsp;<span class=\"numHIGH\">".$aux."</span>";
				}else{
					echo "&nbsp;<a href='".$url."pag=".$aux."' class=\"num\">".$aux."</a>";
				}
			}
		}
		echo '</div>';
	}
}
/***********************************/

/**
* Truncates text.
*
* Cuts a string to the length of $length and replaces the last characters
* with the ending if the text is longer than length.
*
* @param string  $text String to truncate.
* @param integer $length Length of returned string, including ellipsis.
* @param mixed $ending If string, will be used as Ending and appended to the trimmed string. Can also be an associative array that can contain the last three params of this method.
* @param boolean $exact If false, $text will not be cut mid-word
* @param boolean $considerHtml If true, HTML tags would be handled correctly
* @return string Trimmed string.
*/
/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param mixed $ending If string, will be used as Ending and appended to the trimmed string. Can also be an associative array that can contain the last three params of this method.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
	if (is_array($ending)) {
		extract($ending);
	}
	if ($considerHtml) {
		if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		$totalLength = mb_strlen($ending);
		$openTags = array();
		$truncate = '';
		preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
		foreach ($tags as $tag) {
			if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
				if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
					array_unshift($openTags, $tag[2]);
				} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
					$pos = array_search($closeTag[1], $openTags);
					if ($pos !== false) {
						array_splice($openTags, $pos, 1);
					}
				}
			}
			$truncate .= $tag[1];

			$contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
			if ($contentLength + $totalLength > $length) {
				$left = $length - $totalLength;
				$entitiesLength = 0;
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
					foreach ($entities[0] as $entity) {
						if ($entity[1] + 1 - $entitiesLength <= $left) {
							$left--;
							$entitiesLength += mb_strlen($entity[0]);
						} else {
							break;
						}
					}
				}

				$truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
				break;
			} else {
				$truncate .= $tag[3];
				$totalLength += $contentLength;
			}
			if ($totalLength >= $length) {
				break;
			}
		}

	} else {
		if (mb_strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = mb_substr($text, 0, $length - strlen($ending));
		}
	}
	if (!$exact) {
		$spacepos = mb_strrpos($truncate, ' ');
		if (isset($spacepos)) {
			if ($considerHtml) {
				$bits = mb_substr($truncate, $spacepos);
				preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
				if (!empty($droppedTags)) {
					foreach ($droppedTags as $closingTag) {
						if (!in_array($closingTag[1], $openTags)) {
							array_unshift($openTags, $closingTag[1]);
						}
					}
				}
			}
			$truncate = mb_substr($truncate, 0, $spacepos);
		}
	}

	$truncate .= $ending;

	if ($considerHtml) {
		foreach ($openTags as $tag) {
			$truncate .= '</'.$tag.'>';
		}
	}

	return $truncate;
}
	
/***********************************************************************************************/

//SANEAR NOMBRES ->
function clean_name($str){

	$array_from = array("À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");
	$array_to   = array("A","A","A","A","A","A","a","a","a","a","a","a","O","O","O","O","O","O","o","o","o","o","o","o","E","E","E","E","e","e","e","e","C","c","I","I","I","I","i","i","i","i","U","U","U","U","u","u","u","u","y","N","n");
	
	$str = (str_replace($array_from,$array_to,($str)));	
	$str = strtolower($str);	
	$str = preg_replace("/\s+/","-",$str);
	$str = preg_replace('/[^A-Za-z0-9\-]/','',$str);
	$str = preg_replace('/-+/','-',$str);	
	$str = trim($str,"-");
	
	return trim($str);

}

function clean_name_for_files($str){

	$array_from = array("À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");
	$array_to   = array("A","A","A","A","A","A","a","a","a","a","a","a","O","O","O","O","O","O","o","o","o","o","o","o","E","E","E","E","e","e","e","e","C","c","I","I","I","I","i","i","i","i","U","U","U","U","u","u","u","u","y","N","n");
	
	$str = (str_replace($array_from,$array_to,($str)));
	$str = strtolower($str);
	$str = preg_replace("/\s+/","-",$str);
	$str = preg_replace('/[^A-Za-z0-9\-\.]/','',$str);
	$str = preg_replace('/-+/','-',$str);
	$str = trim($str,"-");
	
	return trim($str);

}


// Returns a file size limit in bytes based on the PHP upload_max_filesize
// and post_max_size
function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $max_size = parse_size(ini_get('post_max_size'));

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}



/*******************************************************************************************/
// traduce_mes
// Traduccion del mes de numero a texto en varios idiomas
// Entra :    mes -> numero (formato XX)
//            idioma-> ES CA EN [Opcional: CA por defecto]
// Devuelve :    Mes en txt (CADENA)
/*******************************************************************************************/
function traduce_mes($mes,$idioma = "CA"){

    $mesosES = array(
                "01" => "ENE",    "02" => "FEB",    "03" => "MAR",    "04" => "ABR",
                "05" => "MAY",        "06" => "JUN",    "07" => "JUL",    "08" => "AGO",
                "09" => "SEP", "10" => "OCT",    "11" => "NOV","12" => "DIC");
    $mesosCA = array(
                "01" => "GEN",    "02" => "FEB",    "03" => "MARÇ",    "04" => "ABR",
                "05" => "MAIG",        "06" => "JUNY",    "07" => "JUL",    "08" => "AG",
                "09" => "SET", "10" => "OCT",    "11" => "NOV","12" => "DES");
    $mesosEN = array(
                "01" => "JAN",    "02" => "FEB",    "03" => "MAR",    "04" => "APR",
                "05" => "MAY",        "06" => "JUN",    "07" => "JUL",    "08" => "AUG",
                "09" => "SEP", "10" => "OCT",    "11" => "NOV","12" => "DEC");

    switch($idioma){
        case "ES":
        case "es":
            $mes = $mesosES[$mes];
            break;
        case "CA":
        case "ca":
            $mes = $mesosCA[$mes];
            break;
        case "EN":
        case "en":
            $mes = $mesosEN[$mes];
            break;
    }

    return $mes;
}


?>