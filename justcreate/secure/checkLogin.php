<?php

//Reviso si es login por sesiones o por formulario
if(!isset($_POST['user']) && !isset($_POST['pass'])){
	//Uso los valores de sesion
	$usuari = $_SESSION['user']; 
	$clau = $_SESSION['pass'];
} else {	
	//Borramos las sesiones por si existen
	unset($_SESSION['user']);
	unset($_SESSION['pass']);
	
	//Recogemos los datos del formulario
	
	$usuari = htmlentities(trim($_POST['user']), ENT_QUOTES);
	$clau = htmlentities(trim($_POST['pass']), ENT_QUOTES);
	
	//Los grabamos en la sesión
	$_SESSION['user']= $usuari; 
	$_SESSION['pass']= $clau;
	
	//echo "usuari: ".$usuari." - clau: ".$clau;

}
$cnx = conectar();

$userQuery = db_query($cnx, "SELECT * FROM usuaris WHERE text_codiuser = '".mysqli_real_escape_string($cnx,$usuari)."'") or die('Petición SQL fallida: '.mysqli_error($cnx));

//Revisamos el usuario y el password
if(mysqli_num_rows($userQuery)>0){
	
	//Usuario existe
	$userArray = mysqli_fetch_array($userQuery);
	if($usuari != $userArray['text_codiuser']){
	
		mysqli_close($cnx);
		//echo "1";
		header("Location: ./no_mem.php"); 
		exit();

	}

	//No tiene contraseña
	if(!$userArray['text_password']){
	
		mysqli_close($cnx);
		//echo "2";
		header("Location: ./no_mem.php"); 
		exit();
	
	}

	//La contraseña no es correcta
	if(stripslashes($userArray['text_password']) != md5($clau)){
		
		mysqli_close($cnx);
		//echo "3";
		header("Location: ./no_mem.php"); 
		exit();
	
	}
	
	$_SESSION['username'] = $userArray['text_titulo'];
	
	if($_SESSION['bo_idioma']==''){
	
		$_SESSION['bo_idioma'] = $userArray['idioma'];
	
	}
	
	$_SESSION['rol'] = $userArray['checkbox_nivell'];
	$_SESSION['textos'] = $userArray['checkbox_textos'];


} else {

	mysqli_close($cnx);
	//echo "4";
	header("Location: ./no_mem.php"); 
	exit();
}



//Si llegamos aqui entramos, pero antes cerramos la conexion con la base de datos
mysqli_close($cnx); 

?>