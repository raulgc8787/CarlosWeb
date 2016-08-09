<?php

// Llamadas a otros php de configuracion, inicio de sesiones, etc
include("head.php");

if((isset($_POST['form'])) && ($_POST['form'] == "form")){
	
	$search  = array('Telefono');
	$replace = array('Teléfono');
	
	$autorespuesta = COMMON_MAILAUTORESPTEXTO;
	$autorespuesta = str_replace("{CLIENTE}",CLIENTE,$autorespuesta);
	$autorespuesta = str_replace("{MAILTO}",MAILTO,$autorespuesta);
	
	unset($_POST['form']);
	unset($_POST['button']);
	unset($_POST['iso_idioma']);
	
	$url = $_POST['url'];
	
	unset($_POST['url']);
	
	$subject = stripslashes($_POST['subject']);
	
	if(base64_encode($subject)){
	
		$subject = "=?UTF-8?B?".base64_encode($subject)."?=";	
	
	}
	
	unset($_POST['subject']);
	
	$body = "";
	
	foreach($_POST as $key => $value){
		
		if($value != ''){
		
			$body .= "<strong>".str_replace($search,$replace,$key).":</strong> ".stripslashes($value)."<br />";
	
		}
	
	}
	
	$mailto = MAILTO; 
	
	if(mail($mailto,$subject,$body,"From: ".$_POST['Email']."\r\n" . "MIME-Version: 1.0" . "\r\n" ."Content-type: text/html; charset=UTF-8" . "\r\n")){
		
		$error = 0;
		
		$body = "<strong>".stripslashes($_POST['Nombre'])."</strong><br /><br />".$autorespuesta."";		
		
		mail($_POST['Email'],CLIENTE,$body,"From: ".$mailto."\r\n" . "MIME-Version: 1.0" . "\r\n" ."Content-type: text/html; charset=UTF-8" . "\r\n");
	
	}else{
	
		$error = 1;
	
	}

	header ("Location: ".$url."?error=".$error."");

}else{

	header("Location: ./contacto-ok.php");

}

?>