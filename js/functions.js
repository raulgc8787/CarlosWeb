	
	/////////////////////////////////////////////////////////////
	// GENERAL FUNCTIONS
	////////////////////////////////////////////////////////////


	// Init
	//-------------------------------------------------------------//

		$(document).ready(function(){

			// Slider Cycle
			//-------------------------------------------------------------//

			
			// Si hay slider cycle puesto ... Ejecutamos función

			// if (($(".sliderCorp").length > 0)){ 

			// 		$('#cycle_slideshow').cycle({
						
			// 			fx: 'fade',
			// 			speed: 1000,
			// 			timeout: 3000,
			// 			pager: '#nav_slider',
			// 			next: '#next', 
			// 			prev: '#prev',
			// 			slideExpr: 'div.slide',
						
			// 			// callback fn that creates a thumbnail to use as pager anchor 
			// 			pagerAnchorBuilder: function(idx, slide) { 
			// 				return '<li><a href="#"></a></li>'; 
			// 			}

			// 		});	
			// }


			// Slider Background
			//-------------------------------------------------------------//


			// Si hay cbp-bislideshow puesto ... Ejecutamos función

			if (($("#cbp-bislideshow").length > 0)){

				cbpBGSlideshow.init();

			}
		  	

		  	// Cookies
			//-------------------------------------------------------------//


			$("#cookies").click(function(){

				document.cookie="accept_cookies=yes; expires=Fri, 31 Dec 9999 23:59:59 GMT";
				$("#cookies_msg_subwrapper").hide();

			});
			

	}); // endInit
		
		
	
	// Same height
	//-------------------------------------------------------------//
	$.fn.equalCols = function(){ //Asignamos la nueva función equalCols
		var tallestHeight = 0; //Reinicia la variable que guarda la mayor altura
		$(this).css('padding-bottom','0');
		$(this).each(function(){ //Comprueba uno por uno los elementos con el selector indicado
			
			var thisHeight = $(this).height(); //Guarda la altura del elemento
			
			if (thisHeight > tallestHeight){ // Si la altura es mayor que la anterior altura guardada, se asigna a la variable tallesHeight
				tallestHeight = thisHeight;
			}
		});
		
		$(this).css('height',tallestHeight); //Se asigna la mayor altura a los elementos con el selector indicado
	}




	// 	Carrousel
	// ======================================================


	// Difinimos la constante global para el next y el prev

		globalPos = 0;

	// definimos una constante Global para los steps cuando hay

		globalSteps = 0;


	(function ( $ ) {

	    $.fn.powerCarousel = function(options){
	        
	        // Recojemos el id o la classe del input

	        objeto = $(this);

	        // Definimos los settings por defecto

	        settings = $.extend({

	           controls     : true,
	           isCover      : [false,400,500,"px"],
	           thumbsPrefix : "th",
	           bigImgPrefix : "big",
	           lightBox     : false,
	           table		: "noticias",
	           id_elem      : "",
	           thumbs       : [true,"steps",60],
	           nextPrev	 	: ["Prev","Next"],
	           transition   : "fade"

	
	        }, options );

	        // Hacemos la busqueda en la BBDD según nuestras preferencias

	        $.ajax({
		        type: 'POST',
		        url: 'js/carousel.php',
		        data:{
		        	controls 	 : settings.controls,
		            isCover 	 : settings.isCover,
					thumbsPrefix : settings.thumbsPrefix,
					bigImgPrefix : settings.bigImgPrefix,
					lightBox	 : settings.lightBox,
					table 		 : settings.table,
					id_elem 	 : settings.id_elem,
					thumbs	 	 : settings.thumbs,
					nextPrev	 : settings.nextPrev,
		        },
		        success: function(result){
		        	
		        	// Metemos el resultado dentro de nuestro selector

		            	$(objeto).html(result).imagesLoaded().then(function(){
						// Activamos el colorbox
	
							loadColorBox();
	
						// Activamos los steps si esta
	
							if(settings.thumbs[1] == 'steps'){
								controlsSteps();
							}
	
						// si no tenemos thumbs, los quitamos
	
							if(settings.thumbs[0] == false){
	
								$('#smallImg li').hide();
	
							}

						});

		        },
		    });
	    };
	}( jQuery ));
	

	// Funcion para cambiar de imagen si no tenemos lightbox

	function changeImages(este, image, islightbox, isCover, widthCover , heightCover, isPercent){
		
		// Miramos la imagen que tiene que cambiarse


			currentImg = $(este).attr('id');


		// ETIQUETAS -> Ponemos la etiqueta <a> dependiendo de si el lightbox esta activado y la etiqueta cover
		// ------------------------------------------------------------------------------------------------------

			// islightbox = false,  isCover = false

				if(islightbox == false && isCover == false){

					$('#bigImg').html('<img src="'+image+'">');

			// islightbox = true,  isCover = false

				}else if(islightbox != false && isCover == false){

					$('#bigImg').html('<a href="'+islightbox+'" rel="photo"><img src="'+image+'"></a>');

			// islightbox = false,  isCover = true

				}else if(islightbox == false && isCover == true){

					$('#bigImg').attr("style","background: url("+image+") no-repeat; background-size: cover; width:"+widthCover+""+isPercent+"; height:"+heightCover+"px;");

			// islightbox = true,  isCover = true

				}else if(islightbox != false && isCover == true){

					$('#bigImg').html('<a href="'+islightbox+'" rel="photo" style="background: url('+image+') no-repeat;display:block; background-size: cover; width:'+widthCover+''+isPercent+'; height:'+heightCover+'px;"></a>');
				}

			// Añadimos transición si es que tiene

				if(settings.transition != ''){
					$('#bigImg img').addClass("animated");
					$('#bigImg img').addClass("power"+settings.transition);
				}

			
				

		// RECORREMOS LI -> Recorremos todos los li y cuando sea = a current img, ponemos opacidad 1
		// ----------------------------------------------------------------------------------------------

			$('#smallImg li').each(function(){

				if(currentImg == $(this).attr('id')){

					$(this).addClass('currentThumb');

					// Extraemos el id de la imagen y la ponemos como globalPos

						idCurrent = currentImg.split('img');
						globalPos = parseInt(idCurrent[1]);

					// NEXT / PREV opacity
					// ----------------------------------------------------------------

						// Miramos si hay uno más, y si no, ponemos opacity

							if($('#img'+(globalPos+1)).length == 0){
								$('#nextPic').addClass('disabledControl');
							}else{
								$('#nextPic').removeClass('disabledControl');
							}

						// Miramos si Estamos en 0, Si es que si ponemos opacity

							if(globalPos == 0){
								$('#prevPic').addClass('disabledControl');
							}else{
								$('#prevPic').removeClass('disabledControl');
							}

				}else{

					$(this).removeClass('currentThumb'); // debe ser para las 

				}

			});

		// Activamos el colorbox para los nuevos elementos

			loadColorBox();
	}

	
	// controlsCarousel -> Cuando tocamos los controles
	// ---------------------------------------------------------------------------------

	function controlsCarousel(tipo){

		// Si es NEXT
		// ----------------------------------------------------------------

		if(tipo == 1){

			globalPos++;
			if($('#img'+globalPos).length > 0){

				// Hacemos que ejecute el onclick

				$('#img'+globalPos).triggerHandler('click');

				// Siempre cuando pulsemos next, el prevPic siempre sera opacity:1

				$('#prevPic').removeClass('disabledControl');

			}else{

				globalPos--;
				
			}

			// Miramos si hay uno más, y si no, ponemos opacity

			if($('#img'+(globalPos+1)).length == 0){
				$('#nextPic').addClass('disabledControl');
			}

		// Si es PREV
		// ----------------------------------------------------------------

		}else{

			if(globalPos > 0){

				// Hacemos que ejecute el onclick

					globalPos--;
					$('#img'+globalPos).triggerHandler('click');

				// Siempre cuando pulsemos prev, el nextPic siempre sera opacity:1

					$('#nextPic').removeClass('disabledControl');
			}

			// Miramos si Estamos en 0, Si es que si ponemos opacity

			if(globalPos == 0){
				$('#prevPic').addClass('disabledControl');
			}

		}

	}

	// loadColorBox -> Funcion que carga el colorbox
	// ---------------------------------------------------------------------------------

	function loadColorBox(){

		$("a[rel='photo']").colorbox({
			maxWidth:"75%",
			maxHeight:"75%",
			loop:false , 
			transition:"elastic", 
			speed:"100", 
			reposition:false
		});

	}

	// ControlsSteps -> Cuando tenemos el modo steps, mostramos los controles
	// ---------------------------------------------------------------------------------

	function controlsSteps(){


		// Primero miramos el width del container

			anchoSteps = $('.steps').width();
			

		// Miramos todas las imagenes que anchura tienen

			var anchoImgs = 0;

			$('#smallImg li').each(function(){

				currentWidth = $(this).width();
				anchoImgs = anchoImgs + parseInt(currentWidth);

			})

			$('.steps ul').attr('style','width:'+anchoImgs+'px; height:'+settings.thumbs[2]+'px;');


		// Miramos si el ul es más grande que el container de steps

			if(anchoImgs > anchoSteps){

				$('.steps').append('<span  class="prevStep"></span><span class="nextStep"></span>');
				$('.prevStep').hide();

			}



		// nextStep
		// ---------------------------------------------------------------------------------

		$('.nextStep').on('click',function(){

			globalSteps = globalSteps-anchoSteps;

			if(globalSteps > ((-anchoImgs)/2)){
				$('.steps ul').attr('style','transform:translateX('+globalSteps+'px);-webkit-transform:translateX('+globalSteps+'px);width:'+anchoImgs+'px');
				$('.prevStep').show();
			}else{
				globalSteps = -anchoImgs+anchoSteps;
				$('.steps ul').attr('style','transform:translateX('+globalSteps+'px);-webkit-transform:translateX('+globalSteps+'px);width:'+anchoImgs+'px');
				$('.nextStep').hide();
				$('.prevStep').show();
			}

		});

		// PrevStep
		// ---------------------------------------------------------------------------------

		$('.prevStep').on('click',function(){
			
			globalSteps = globalSteps+anchoSteps;

			if(globalSteps < 0){	
				$('.steps ul').attr('style','transform:translateX('+globalSteps+'px);width:'+anchoImgs+'px');
				$('.nextStep').show();
			}else{
				globalSteps = 0
				$('.steps ul').attr('style','transform:translateX('+globalSteps+'px);width:'+anchoImgs+'px');
				$('.prevStep').hide();
				$('.nextStep').show();
			}

		});
	}




	// Iframe
	//-------------------------------------------------------------//

		(function ( $ ) {
			$.fn.formats = function( options ){

				var format = $(this);
				var widthIframe = format.parent().width();

				// Opciones default
				var settings = $.extend({
				 	proporcion: "16:9"
				}, options );

				var proporcionResult = settings.proporcion.split(":");
				var proporcionResult = proporcionResult[1]/proporcionResult[0];

				format.css('width',widthIframe);
				format.css('height',widthIframe*proporcionResult);

				$(window).resize(function(){
					var widthIframe = format.parent().width()
					format.css('width',widthIframe);
					format.css('height',widthIframe*proporcionResult);
				});
			};
		}( jQuery ));

		// Fn to allow an event to fire after all images are loaded
		$.fn.imagesLoaded = function () {
		
			// Edit: in strict mode, the var keyword is needed
			var $imgs = this.find('img[src!=""]');
			// if there's no images, just return an already resolved promise
			if (!$imgs.length) {return $.Deferred().resolve().promise();}
		
			// for each image, add a deferred object to the array which resolves when the image is loaded (or if loading fails)
			var dfds = [];  
			$imgs.each(function(){
		
				var dfd = $.Deferred();
				dfds.push(dfd);
				var img = new Image();
				img.onload = function(){dfd.resolve();}
				img.onerror = function(){dfd.resolve();}
				img.src = this.src;
		
			});
		
			// return a master promise object which will resolve when all the deferred objects have resolved
			// IE - when all the images are loaded
			return $.when.apply($,dfds);
		
		}