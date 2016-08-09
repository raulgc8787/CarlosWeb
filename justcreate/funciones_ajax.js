<!--TEXTOS DEL FILE UPLOADER-->
var tituloCaja = "Subir Archivos";
var descCaja = "Elige los archivos que quieras y clica en el bot&oacute;n de 'Iniciar subida'";
var nombreFichero = "Nombre del fichero";
var status = "Status";
var tamanyo = "Tamaño";
var anadirFotos = "A&ntilde;adir";
var iniciarSubida = "Iniciar subida";
var guardado = "Guardado";
var creado = "Creado";

<!-- LISTA DE FUNCIONES-->
<!-- cambiaIdioma(idioma)
<!-- eliminarFotoUploader(tabla,id_elem,id,idioma)
<!-- eliminarFicheroUploader(tabla,id_elem,id,idioma)
<!-- eliminarFichero(tabla,id,idioma,nombre_fichero,donde)
<!-- eliminarFoto(tabla,id,idioma,nombre_fichero,donde)
<!-- guardaNombre(tabla,id,registro,idioma)
<!-- cargaUploader(tabla,id,idioma)
<!-- creaRegCheckM(tabla,campo,marcados)
<!-- cambiaStatus(tabla,campo,valor,id)
<!-- -->

function cambiaIdioma(idioma){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=cambiaIdioma&idioma="+idioma,
		success: function(a) {
		jQuery('#elidioma').html(a);
		}
	});
}

function eliminarFotoUploader(tabla,id_elem,id,idioma){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=eliminarFotoUploader"+"&tabla="+tabla+"&id_elem="+id_elem+"&id="+id+"&idioma="+idioma, // enviamos la cadena generada
		success: function(a) {
			jQuery('#sortme'+tabla+idioma).html(a);
		}
	});
}

function eliminarBannUploader(tabla,id_elem,id,idioma){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=eliminarBannUploader"+"&tabla="+tabla+"&id_elem="+id_elem+"&id="+id+"&idioma="+idioma, // enviamos la cadena generada
		success: function(a) {
			jQuery('#sortme'+tabla+idioma).html(a);
		}
	});
}

function eliminarFicheroUploader(tabla,id_elem,id,idioma){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=eliminarFicheroUploader"+"&tabla="+tabla+"&id_elem="+id_elem+"&id="+id+"&idioma="+idioma, // enviamos la cadena generada
		success: function(a) {
			//window.alert('#sortme'+tabla+idioma);
			jQuery('#sortme'+tabla+idioma).html(a);
		}
	});
}


function eliminarFichero(tabla,id,idioma,nombre_fichero,donde){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=eliminarFichero"+"&tabla="+tabla+"&id="+id+"&idioma="+idioma+"&nombre_fichero="+nombre_fichero, // enviamos la cadena generada
		success: function(a) {
			jQuery('#li'+donde+'').html(a);
		}
	});
}

function eliminarFoto(tabla,id,idioma,nombre_fichero,donde){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=eliminarFoto"+"&tabla="+tabla+"&id="+id+"&idioma="+idioma+"&nombre_fichero="+nombre_fichero, // enviamos la cadena generada
		success: function(a) {
			jQuery('#li'+donde+'').html(a);
		}
	});
}

function guardaNombre(tabla,id,registro,idioma){
	//window.alert("'"+tabla+registro+idioma+"'");
	valor = document.getElementById(tabla+registro+idioma).value
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=guardaNombre"+"&tabla="+tabla+"&id="+id+"&registro="+registro+"&idioma="+idioma+"&valor="+valor, // enviamos la cadena generada
		success: function(a) {
			pintaMensajeMini('msg_'+tabla+registro+idioma,guardado);  
		}
	});
}

function guardaLink(tabla,id,registro,idioma){
	//window.alert("'"+tabla+registro+idioma+"'");
	valor = document.getElementById(tabla+registro+idioma).value
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=guardaLink"+"&tabla="+tabla+"&id="+id+"&registro="+registro+"&idioma="+idioma+"&valor="+valor, // enviamos la cadena generada
		success: function(a) {
			pintaMensajeMini('msg_'+tabla+registro+idioma,guardado);  
		}
	});
}

function cargaUploader(tabla,id,idioma){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=cargaUploader"+"&tabla="+tabla+"&id="+id+"&idioma="+idioma, // enviamos la cadena generada
		success: function(a) {
			jQuery('#sortme'+tabla+idioma+'').html(a);
		}
	});
}

function creaRegCheckM(tabla,campo,marcados){
	//valor = document.getElementById(campo).value;
	valor = $('#'+campo).val();
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=creaRegCheckM"+"&tabla="+tabla+"&valor="+valor+"&marcados="+marcados, // enviamos la cadena generada
		success: function(a) {
			jQuery('#'+tabla+'').html(a);
			pintaMensajeMini('msg_'+tabla,creado);
			$('#'+campo).val('');
		}
	});
}

function cambiaStatus(tabla,campo,valor,id){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=cambiaStatus"+"&tabla="+tabla+"&campo="+campo+"&valor="+valor+"&id="+id, // enviamos la cadena generada
		success: function(a) {
			jQuery('#icons'+id+'').html(a);
		}
	});
}

function addItem(tabla,id,idioma){
	valor = document.getElementById(tabla+idioma).value
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=addItem"+"&tabla="+tabla+"&id="+id+"&valor="+valor+"&idioma="+idioma, // enviamos la cadena generada
		success: function(a) {
			jQuery('#sortme'+tabla+idioma).html(a);
		}
	});
}
function eliminarFicheroList(tabla,id_elem,id,idioma){
	jQuery.ajax({
		type: "POST", 
		url: "funciones_ajax.php",
		data: "funcion=eliminarFicheroList"+"&tabla="+tabla+"&id_elem="+id_elem+"&id="+id+"&idioma="+idioma, // enviamos la cadena generada
		success: function(a) {
			//window.alert('#sortme'+tabla+idioma);
			jQuery('#sortme'+tabla+idioma).html(a);
		}
	});
}