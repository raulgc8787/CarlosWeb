<?php 
include("head.php"); 
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_CONTACTO_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_CONTACTO_DESC;?>" />

<?php include("header.php");?>
<script type="text/javascript">
function validarForm(){
	
	error = 0;
	
	telefono = document.getElementById("Telefono").value;
	mail = document.getElementById("Email").value;
	personacontacto = document.getElementById("Nombre").value;
	observaciones = document.getElementById("Comentarios").value;

	if(telefono == "" || mail == "" || personacontacto == "" || observaciones == ""){
		error = 1;
		document.getElementById("errores").innerHTML = "<?php echo COMMON_VALIDA_CAMPOS; ?>";
	}


	if ($('.termsConditions').is(':checked')) {
		;
	}else{
		error = 1;
		document.getElementById("errores").innerHTML = "<?php echo COMMON_VALIDA_TERMS; ?>";
	}


	if(error == 0){
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
			;
		} else {
			error = 1;
			document.getElementById("errores").innerHTML = "<?php echo COMMON_VALIDA_EMAIL; ?>";
		}
	}
	
	if(error == 1){
		$('#errores').show();
		return false;
	} else{
		return true;
	}
}
</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

</head>

<body class="contacto <?php echo IDIOMA;?> flex">
<?php include("body.php");?>
<?php include 'mapas.php';?>
<div class="subcontainer">    
	<div class="row">

		<p><?php echo CONTACTO_TEXTO;?></p>

	     <form onSubmit="return validarForm();" action="send.php" class="form01" id="formulario" name="formulario" method="post" >
	     <input type="hidden" name="form" id="form" value="form" />
	     <input type="hidden" name="url" id="url" value="contacto-ok.php" />
	     <input type="hidden" name="subject" id="subject" value="<?php echo COMMON_MAILAUTORESPSUBJET.' '.BRAND; ?>" />
	      <ul>
	        <li class="full"><label><?php echo COMMON_LABELNOMBRE; ?>*</label><input name="Nombre" type="text" id="Nombre" required/></li>
	        <li><label><?php echo COMMON_LABELTELEFONO; ?>*</label><input name="Telefono" type="tel" id="Telefono" required/></li>
	        <li><label><?php echo COMMON_LABELMAIL; ?>*</label><input name="Email" type="email" id="Email" required/></li>
	        <li class="full"><label><?php echo COMMON_LABELCOMENTARIOS; ?>*</label> <textarea name="Comentarios" id="Comentarios" required></textarea> </li>
	        <li class="full"><input class="termsConditions" type="checkbox" name="terms" id="validateCheck" ><label  for='validateCheck'><span></span><?php echo COMMON_ACEPTOTERMINOS; ?>. <a href="<?php echo $links['legal.php']; ?>"><?php echo COMMON_VERTERMINOS; ?></a></label></li>
	        <li class="full"><span id="errores"></span></li>
            <li class="full">
	        	<input type="hidden" name="iso_idioma" id="iso_idioma" value="<?php echo IDIOMA;?>" />
	            <input value="<?php echo COMMON_ENVIAR; ?>" class="button link fullButton" name="button" type="submit" id="button">
	        </li>                                
	      </ul>
	    </form>
	    
    </div>     


</div>
<?php include("footer.php");?>
<script>

	$("input:text").focus(function(){

		if($(this).val() == $(this).attr('name')){

			$(this).val('');
		}

	});

	$("input:text").blur(function(){

		if($(this).val() == ""){

			$(this).val($(this).attr('name'));
		}

	});

</script>
</body>
</html>
<?php include("bottom.php");?>