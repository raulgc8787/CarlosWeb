<?php
// Inclusion de todos los elementos comunes del header: llamadas a css, js, etiquetas meta... 
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="ES"/>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1" name="viewport">

<link href="css/layout.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script> 
<script src="js/jquery-migrate-1.0.0.js" type="text/javascript"></script> 
<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {

	$("#menuResponsive").click(function(){
		$("#nav ul").toggle();
	});

});
</script>
<!--<script src="js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>-->


<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea.editme",
	menubar:false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste <?php echo $es_blog;?>"
    ],
	valid_elements : "*[*]",
    toolbar: "removeformat | insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link <?php echo $es_blog;?> | code ",
	theme:"modern" ,
	relative_urls:false,
	force_p_newlines : false,
	forced_root_block : ''
});
</script>

<script language="javascript">
function validar_borrar(){
	return confirm('<?php echo html_entity_decode($backoffice['alerta_borrar']['es'],ENT_COMPAT,'UTF-8');?>'); 
}
function seleccionar_todo(){ 
   for (i=0;i<document.form.elements.length;i++) 
      if(document.form.elements[i].type == "checkbox") 
         document.form.elements[i].checked=1 
}
function desseleccionar_todo(){ 
   for (i=0;i<document.form.elements.length;i++) 
      if(document.form.elements[i].type == "checkbox") 
         document.form.elements[i].checked=0 
}
function seleccionar(obj) {
	//window.alert(obj);
	obj2=document.getElementById(obj);
    if (obj2.options[0].value=='-'){ // si solo está la opción inicial borrarla
    	obj2.options[0]=null;
	}
	elem=document.getElementById(obj).options;
	for(i=0;i<elem.length;i++){
		elem[i].selected=true;
	}
}
function volcarSelects(emisori, receptori){ 
     
    // Accedemos a los 2 selects 
    emisor = document.getElementById(emisori); 
    receptor = document.getElementById(receptori); 
    
	seleccionado = emisor.selectedIndex;
	
	while (seleccionado != -1){ 
	
		if(emisor.options[seleccionado].selected){
		
			// Obtenemos algunos datos necesarios para mover
			posicion = receptor.options.length;  	 
			volcado = emisor.options[seleccionado]; 
			receptor.options[posicion] = new Option(volcado.text, volcado.value); 
			emisor.options[seleccionado] = null;
			
			//Lo mio ajax
			subcadena = emisori.substring(0,18);
			if(subcadena == "elem_no_destacados"){
				direccion = "poner";
			} else{
				direccion = "quitar";
			}
			//valor = emisor.options[seleccionado].value;
			
			//selectMultiple(tabla,id,direccion,valor)
		}
		
		seleccionado = emisor.selectedIndex;
	
    }
	largo = emisori.length;
	largo = largo - 18;
	elQue = emisori.substr(18,largo);
	//window.alert(elQue);
	seleccionar("elem_destacados"+elQue);

}

function pintaMensaje(donde,mensaje){	
	$('#'+donde+'').show();
	$('#'+donde+'').html(mensaje);    
	setTimeout(function(){
		$('#'+donde+'').fadeOut(300);	
	},3000);
}
function pintaMensajeMini(donde,mensaje){	
	$('#'+donde+'').show();
	$('#'+donde+'').html(mensaje);    
	setTimeout(function(){
		$('#'+donde+'').html('');
		$('#'+donde+'').fadeOut(300);	
	},1500);   
}

//DATE PICKER
/*
function DisableSunday(date) { 
	var day = date.getDay();
	// If day == 1 then it is MOnday, 0 sunday
	if (day == 0) {		 
		return [false] ; 		 
	} else { 		 
		return [true] ;
	}		  
}

function ponHorario(){
	var date = $(this).datepicker('getDate');		
	$("#lx").hide();
	$("#mj").hide();
	$("#vi").hide();
	$("#sa").hide();
	$("#labelhorario").show();
	var day = date.getDay();
	if(day == 1 || day == 3){
		$("#lx").show();
	} else if(day == 2 || day == 4){
		$("#mj").show();
	} else if(day == 5){
		$("#vi").show();
	} else if(day == 6){
		$("#sa").show();
	}
}
*/
$(document).ready(function(){
	$("input[id^='text_fecha']").datepicker({
		//beforeShowDay: DisableSunday,
		//onSelect: ponHorario,
		dateFormat: 'dd-mm-yy',
		firstDay: 1,
		dayNamesMin: [ "Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
		prevText: "Anterior",
		nextText: "Siguiente",
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		numberOfMonths: 1,
		minDate: null
	});
});
//END DATE PICKER
</script>

<script type="text/javascript" language="javascript">
	var arrayIdiomas = new Array();
	<?php
	$it = 0;
	$l = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
	while($arl = mysqli_fetch_array($l)){
		echo 'arrayIdiomas['.$it.'] = "'.$arl['nom_idioma'].'";'.chr(10);
		$it++;
	}
	?>
	function cambia_idioma(idioma){
		
		cambiaIdioma(idioma);
		
		for (i=0;i<arrayIdiomas.length;i++){
			document.getElementById("idioma"+arrayIdiomas[i]).style.display = "none";
			$("#idioma"+arrayIdiomas[i]).removeClass("idiomaActive");
			$("#idioma"+arrayIdiomas[i]).addClass("idiomaInactive");
			$("#pestanya"+arrayIdiomas[i]).removeClass("actiu");
		} 
		
		document.getElementById("idioma"+idioma).style.display = "block";
		$("#idioma"+idioma).removeClass("idiomaInactive");
		$("#idioma"+idioma).addClass("idiomaActive");
		$("#pestanya"+idioma).addClass("actiu");
	}
</script>
<script src="funciones_ajax.js"></script>

<script>
<?php	
$table_content = $table."_content";
$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
$tableExists = mysqli_num_rows($result) > 0;
if($tableExists){
	$cols = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table;
	$cols_content = "SHOW FULL COLUMNS FROM ".$DATABASE.".".$table_content;
	
	//Hare esto por cada idioma
	$i = db_query($link,"SELECT * FROM config_idiomes");
	while($ar_i = mysqli_fetch_array($i)){
		$idiom = $ar_i['nom_idioma'];
		
		$nt = "";
		$c = db_query($link,$cols_content);
		while($rc = mysqli_fetch_array($c)){
			$field = $rc[0];
			if( (substr($field,0,4) == "list") ){
				$nt = substr($field,5,strlen($field)-5); //Ya lo tengo
				?>
				$(document).ready(
				  function() {
					$("#sortme<?php echo $nt.$idiom;?>").Sortable({
					  accept : 'sortitem<?php echo $nt.$idiom;?>',
					  onchange : function (sorted) {
						serial = $.SortSerialize('sortme<?php echo $nt.$idiom;?>');
				
						$.ajax({
								  url: "sortdataidioma.php",
								  type: "POST",
								  data: serial.hash,
								  complete: function(){
									pintaMensajeMini('showmsg<?php echo $nt.$idiom;?>','<?php echo $backoffice['orden_guardado_ok']['es'];?>')
								  },
								  //success: function(feedback){ $('#data').html(feedback); }
								  // error: function(){}
						});
					  }
					});
				  }
				);
				<?php
			}//end if
		}//end while cols_content
	
	}//end while idiomas
}
?>
</script>

<style type="text/css">
<?php
$detect = new Mobile_Detect();
if ($detect->isMobile()) {
?>
.movil{
	display:block;
}
.tablet{
	display:none;
}
.desktop{
	display:none;
}
<?php
} else if($detect->isTablet()){
?>
.movil{
	display:none;
}
.tablet{
	display:block;
}
.desktop{
	display:none;
}
<?php
} else{
}
?>
</style>


<?php

// Google Analytics a continuación
?>