<?php

// Incluyo las funciones basicas
include "../../files/config.php";
include "../../files/funciones.php";

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

/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$link = conectar();

include "../SimpleImage.php";
include "../SimpleImage.class.php";

$tabla = $_REQUEST['tabla'];
$idiom = $_REQUEST['idiom'];


// Settings
//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
$id = $_SESSION['id'];
$targetDir = '../../pics/'.$id;
//$idioma = $_SESSION['idioma'];

//Busco el orden maximo que tiene una foto de esta propiedad
$max_orden = db_query($link,"SELECT MAX(orden) AS max FROM ".$tabla." WHERE id_elem=".$id." AND idioma='".$idiom."'");
$ar_max_orden = mysqli_fetch_array($max_orden);
$max = $ar_max_orden['max'];
if($max == ""){
	$nuevo_orden = 0;
} else{
	$nuevo_orden = $max + 1;
}

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
$fileName = clean_name_for_files($fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files	
if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
			@unlink($tmpfilePath);
		}
	}

	closedir($dir);
} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
} else {
	// Open temp file
	$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off 
	rename("{$filePath}.part", $filePath);
	
	
	//echo $targetDir . "/" . $fileName;

	//Busco las configuraciones de la tabla config_thumbs
	$c = db_query($link,"SELECT * FROM config_thumbs ct LEFT JOIN config_tipothumb ctt ON (ctt.id = ct.select_tipothumb) ORDER BY ct.id ASC");
	while($con = mysqli_fetch_array($c)){
		$prefix_thumbnail = $con['text_prefix'];
		$tipus_thumbnail = $con['text_titulo'];
		$mida_thumbnail = $con['text_mida'];
		
		$image = new SimpleImage();
		$image->load($targetDir . "/" . $fileName);
		//Segun el tipo, hago una llamada u otra
		if($tipus_thumbnail == "width"){
			$image->resizeToWidth($mida_thumbnail);
		} else if($tipus_thumbnail == "height"){
			$image->resizeToHeight($mida_thumbnail);
		} else if($tipus_thumbnail == "square"){
			$image->square($mida_thumbnail);
		} else if($tipus_thumbnail == "resize"){
			$mida_thumbnail = preg_split("/,/",$mida_thumbnail);
			$width_final = $mida_thumbnail[0];
			$height_final = $mida_thumbnail[1];							
			$image->resize($width_final,$height_final);
			
			//QUIERO HACER UN CROP, NO UN RESIZE!
			$image2 = new SimpleImage2("../../pics_".$tabla."/".$_SESSION['id']."/".$fileName);
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
			$image2->crop($x_inicial,$y_inicial,$x_final,$y_final)->save("../../pics_".$tabla."/".$_SESSION['id']."/".$prefix_thumbnail.$fileName);
		
		} else if($tipus_thumbnail == "fit"){
		
			//params
			$mida_thumbnail_array = preg_split("/,/",$mida_thumbnail);
			$width_final = $mida_thumbnail_array[0];
			$height_final = $mida_thumbnail_array[1];					
			$rutaFisicaFinal = "../../pics_".$tabla."/".$_SESSION['id']."/".$prefix_thumbnail.$fileName;
										
			// Source image
			$src = imagecreatefromjpeg("../../pics_".$tabla."/".$_SESSION['id']."/".$fileName);		
			 
			// Destination image with white background
			$dst = imagecreatetruecolor($width_final, $height_final);
			imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
			
			scale_image($src, $dst, 'fit');
			
			imagejpeg($dst,$rutaFisicaFinal);
		
		}
		if($tipus_thumbnail != "resize" && $tipus_thumbnail != "fit"){
		
			$image->save($targetDir . "/" .$prefix_thumbnail. $fileName);   
		
		}
		
	}

	//inserto la foto con su orden en la base de datos
	$inserta_foto = db_query($link,"INSERT INTO `".$tabla."`(id_elem,foto,orden,idioma) VALUES('".$id."','".$fileName."','".$nuevo_orden."','".$idiom."')");
}


// Return JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
?>