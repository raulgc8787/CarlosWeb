<?php 

error_reporting(0);

include("../files/config.php");
include("../files/funciones.php");

$link = conectar();

//Idiomas ->
$idiomas = db_query($link,"SELECT * FROM config_idiomes");

unset($array_idiomas);

while($idioma = mysqli_fetch_array($idiomas)){

	$array_idiomas[] = $idioma;

}

$letter_aux = '';
$letter_aux_2 = '';
$i = 1;

for($char = "A";$char!="Z";$char++){

	if($i == (count($array_idiomas)+1)){
	
		$letter_aux = $char;
	
	}
	
	if($i == (count($array_idiomas)+2)){
	
		$letter_aux_2 = $char;
	
	}
	
	$i++;

}

//Incluyo la clase ->
require_once dirname(__FILE__) . '/Excel/PHPExcel.php';

//Configuro el caché
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '512MB');

PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Creo el nuevo excel ->
$objPHPExcel = new PHPExcel();

//Marco la hoja activa ->
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Textos');

//Ancho columnas ->
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

for($char = "B";$char!=$letter_aux_2;$char++){
	
	//Ancho ->
	$objPHPExcel->getActiveSheet()->getColumnDimension($char)->setWidth(100);
	
	//Ajustar texto a celdas ->
	$objPHPExcel->getActiveSheet()->getStyle(''.$char.'1:'.$char.'999')->getAlignment()->setWrapText(true);
	
}

//Estilo de la cabecera ->
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letter_aux.'1')->applyFromArray(array('font'=> array('bold'=> true,'color'=> array('rgb'=> 'FFFFFF')),'fill' => array('type'=> PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '333333'))));	

//Cabeceras ->
$objPHPExcel->getActiveSheet()->setCellValue('A1', utf8_encode(html_entity_decode('KEY')));

$i = 0;

for($char = "B";$char!=$letter_aux_2;$char++){
	
	$objPHPExcel->getActiveSheet()->setCellValue(''.$char.'1', utf8_encode(html_entity_decode($array_idiomas[$i]['nom_idioma'])));
	$i++;

}

$j = 2;

$t = db_query($link,"SELECT * FROM textos ORDER BY text_nodisabled ASC");

while($textos = mysqli_fetch_array($t)){

	$objPHPExcel->getActiveSheet()->setCellValue('A'.$j.'', utf8_encode(html_entity_decode($textos['text_nodisabled'])));
	
	$i = 0;

	for($char = "B";$char!=$letter_aux_2;$char++){
		
		$tc = db_query($link,"SELECT * FROM textos_content WHERE id=".$textos['id']." AND idioma = '".$array_idiomas[$i]['nom_idioma']."'");
		$textosc = mysqli_fetch_array($tc);
		
		$objPHPExcel->getActiveSheet()->setCellValue(''.$char.''.$j.'', (html_entity_decode($textosc['textarea_titulo'])));
		$i++;
	
	}
	
	$j++;

}

//Cierro conexión BBDD ->
mysqli_close($link);

//Vuelvo a dejar activa la página de datos generales ->
$objPHPExcel->setActiveSheetIndex(0);

//Genero el excel a partir de las tablas en html ->
$filename = "text_export_" . time().".xls";
	
//Genero el excel y fuerzo la descarga ->
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"" . $filename . "\"" );
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');

exit;	

?>