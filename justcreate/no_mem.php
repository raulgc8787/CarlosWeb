<?  
//Incluyo las funciones basicas
include "../files/config.php";
include "../files/funciones.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex,nofollow" />
<title>Zona Privada de Gestión</title>
<script language="JavaScript1.2">function validar_reg(){if (document.formulari.user.value.length==0){alert("¡Debes escribir un nombre de usuario!"); document.formulari.user.focus(); return 0;}if (document.formulari.pass.value.length==0){alert("¡Debes escribir una contraseña!"); document.formulari.pass.focus(); return 0;}document.formulari.submit();}</script>

<style>
	@font-face {
	    font-family: 'open_sanslight';
	    src: url('type/opensans-light-webfont.eot');
	    src: url('type/opensans-light-webfont.svg#open_sanslight') format('svg'),
			 url('type/opensans-light-webfont.eot?#iefix') format('embedded-opentype'),
	         url('type/opensans-light-webfont.woff') format('woff'),
	         url('type/opensans-light-webfont.ttf') format('truetype');
	    font-weight: normal;
	    font-style: normal;
	}
	@font-face {
	    font-family: 'open_sansregular';
	    src: url('../type/opensans-regular-webfont.eot');
	    src: url('../type/opensans-regular-webfont.svg#open_sansregular') format('svg'),
			 url('../type/opensans-regular-webfont.eot?#iefix') format('embedded-opentype'),
	         url('../type/opensans-regular-webfont.woff') format('woff'),
	         url('../type/opensans-regular-webfont.ttf') format('truetype');
	    font-weight: normal;
	    font-style: normal;
	}

	table.loginpanel{
		width:300px;
		margin:0 auto;
		margin-top:5em;
		font-family: 'open_sanslight', sans;
		font-size:14px;
	}
	table.loginpanel td{
		text-align:top;
		/*padding:5px;*/
	}
	table.loginpanel table{
		width:100%;
	}
	table.loginpanel table td {
		text-align:center;
	}
	table.loginpanel table td span{
		padding:0!important;
	}
	input, span{
		font-family: 'open_sanslight',sans-serif;
		font-size: 1.0em;
		display: block;
		float: left;
		width:98%;
		margin: 0 0 1em;
		padding:.3em 1%;
		border:1px solid #DDD;
		border-radius:3px;	
	}	
	span{
		border:0;	
	}	
	input.btn{	
		float:left;
		width:80px;
		height:35px;
		margin:1em 0;	
		padding:.3em;
		border:0;
		background:#232323 ;
		font-size:.8em;
		font-weight:bold;
		font-family: 'open_sansregular',sans-serif;
		text-align:center;
		color:#FFF;
		text-transform:uppercase;
		border-radius: 3px; 
		cursor:pointer;
	}
	input.btn:hover{	
		background:#000;
	}
	.logo { 
		padding:0 0 1em;
		text-align:center;
	}	
</style>

</head>
<body>
	<table cellpadding="0" cellspacing="0" border="0" class="loginpanel">
		<tr>
		<td>
		<form method="post" name="formulari" action="./index.php">
		<table cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="2" class="logo"><img src="../images/logos/logo.png" style="padding:1px; background-color: #ddd;" /></td>
		</tr>
		<tr>
		<td ><span>Usuario:</span></td>
		<td ><input name="user" type="text" id="user" maxlength="15" size="15"></td>
		</tr>
		<tr>
		<td ><span>Contrase&ntilde;a:</span></td>
		<td ><input name="pass" type="password" id="pass" maxlength="15" size="15"></td>
		</tr>
		<tr>
		<td></td><td ><input value="Entrar" type="button" onClick="validar_reg()" class="btn"></td>
		</tr>
		</table>
		</form></td>
		</tr>
	</table>
</body>
</html>