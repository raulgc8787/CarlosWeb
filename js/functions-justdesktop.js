

	// Scroll to
	//-------------------------------------------------------------//

		(function ( $ ) {
			$.fn.fullHeightImage = function( options ) {

				var screener =  window.innerHeight;
				var parenter = $(this);

				// Opciones default
				var settings = $.extend({
					screenheight: screener,
					btn: "btnDown",
					margintop: "0",
					speed: 'slow',
					HeightWindow: false
				}, options );

				// Greenify the collection based on the settings variable.

				if(settings.HeightWindow == true){
					this.css("margin-top",(settings.screenheight-settings.margintop));
				}
				

				$(settings.btn).on('click', function(){
					
					$('html,body').animate({
				        scrollTop: parenter.offset().top},
				        settings.speed);
						
				});

			};
		}( jQuery ));


		function heightAll(atributte){
			
			var screener =  window.innerHeight;
			$(atributte).css('height',screener)
		}


	// Scroll to
	//-------------------------------------------------------------//

		(function ( $ ) {
			$.fn.scroller = function( options ) {

				var parenter = $(this);

				// Opciones default
				var settings = $.extend({
					classadded: "btnDown",
					starton: "100"
				}, options );

				// Greenify the collection based on the settings variable.

				$(window).scroll(function(){

					scrolltoper = $(window).scrollTop();
					
					if(scrolltoper > settings.starton){

						parenter.addClass(settings.classadded);

					}else{

						parenter.removeClass(settings.classadded);

					}

				});

			};
		}( jQuery ));








	
						



