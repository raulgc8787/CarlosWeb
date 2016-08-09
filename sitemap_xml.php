<?php

include("head.php");

$web_host = $_SERVER['HTTP_HOST'];	
$web_folder = dirname($_SERVER['SCRIPT_NAME']);

if($web_folder!='/'){

	$web_folder .= '/';

}

$url_prefix = "http://".$web_host."".$web_folder."";

//Cabecera
$datos = "<?xml-stylesheet type=\"text/xsl\" href=\"gss.xsl\"?>
<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";

//Páginas estáticas ->

//Idiomas ->
$idiomas = db_query($link,"SELECT * FROM config_idiomes");

while($idioma = mysqli_fetch_array($idiomas)){
	
	$urls_index = db_query($link,"SELECT * FROM textos t LEFT JOIN textos_content tc ON (t.id=tc.id AND tc.idioma='".$idioma['nom_idioma']."') WHERE t.text_nodisabled = 'index.php' AND textarea_titulo != ''");

	while($url = mysqli_fetch_array($urls_index)){
		
		$datos .= "<url><loc>".$url_prefix."".$url['textarea_titulo']."</loc><changefreq>daily</changefreq></url>\n";
	
	}
	
	$urls = db_query($link,"SELECT * FROM textos t LEFT JOIN textos_content tc ON (t.id=tc.id AND tc.idioma='".$idioma['nom_idioma']."') WHERE t.text_nodisabled LIKE '%.%' AND t.text_nodisabled != 'index.php' AND textarea_titulo != '' ORDER BY t.text_nodisabled ASC");
	
	while($url = mysqli_fetch_array($urls)){
		
		$datos .= "<url><loc>".$url_prefix."".$url['textarea_titulo']."</loc><changefreq>daily</changefreq></url>\n";
	
	}
	
}	

//Idiomas ->
$idiomas = db_query($link,"SELECT * FROM config_idiomes");

while($idioma = mysqli_fetch_array($idiomas)){
	
	//Equipo ->
	$urls = db_query($link,"SELECT * FROM productos n LEFT JOIN productos_content nc ON (n.id=nc.id AND nc.idioma='".$idioma['nom_idioma']."') WHERE nc.text_titulo != '' AND n.checkbox_visible='1' ORDER BY n.orden DESC");
	
	while($url = mysqli_fetch_array($urls)){
		
		$permalink = urls_amigables($url['text_url']);
    	$permalink = $permalink."-p-".$url['id']."-".$idioma['nom_idioma']."";
		
		$datos .= "<url><loc>".$url_prefix."".$permalink."</loc><changefreq>daily</changefreq></url>\n";

	}

	//Servicios ->
	$urls = db_query($link,"SELECT * FROM servicios n LEFT JOIN servicios_content nc ON (n.id=nc.id AND nc.idioma='".$idioma['nom_idioma']."') WHERE nc.text_titulo != ''");
	
	while($url = mysqli_fetch_array($urls)){
		
		$permalink = urls_amigables($url['text_url']);
    	$permalink = $permalink."-s-".$url['id']."-".$idioma['nom_idioma']."";
		
		$datos .= "<url><loc>".$url_prefix."".$permalink."</loc><changefreq>daily</changefreq></url>\n";

	}

	//Noticias ->
	$urls = db_query($link,"SELECT * FROM noticias n LEFT JOIN noticias_content nc ON (n.id=nc.id AND nc.idioma='".$idioma['nom_idioma']."') WHERE nc.text_titulo != '' AND n.checkbox_visible='1' ORDER BY n.text_fecha	DESC");
	
	while($url = mysqli_fetch_array($urls)){
		
		$permalink = urls_amigables($url['text_titulo']);
    	$permalink = $permalink."-n-".$url['id']."-".$idioma['nom_idioma']."";
		
		$datos .= "<url><loc>".$url_prefix."".$permalink."</loc><changefreq>daily</changefreq></url>\n";

	}
	
}

$datos .= "</urlset>";

header("Content-type: text/xml; charset=utf-8");

echo $datos;

?>