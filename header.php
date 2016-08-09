<?php
// Inclusion de todos los elementos comunes del header: llamadas a css, js, etiquetas meta... 
?>

<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1" name="viewport">

<link href="css/layout.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" media="print" href="css/print.css">
<!-- <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /> -->

<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script> 
<script src="js/functions.js" type="text/javascript"></script> <!-- Cargamos nuestros scripts generales -->



<link rel="apple-touch-icon" sizes="57x57" href="/favico/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favico/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favico/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/favico/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/favico/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/favico/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/favico/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/favico/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/favico/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/favico/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favico/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favico/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favico/favicon-16x16.png">
<link rel="manifest" href="/favico/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">


<!--[if lt IE 9]>
    <script src="js/html5.js"></script>
<![endif]--> 
<?php

$detect = new Mobile_Detect();

if (($detect->isMobile()) || (DEBUG_MODE_MOBILE == '1') || (DEBUG_MODE_TABLET=='1')) {
	
	//Tablet ->
	if(($detect->isTablet()) || (DEBUG_MODE_TABLET=='1')){
		
		?>
        <link href="css/tablet.css" rel="stylesheet" type="text/css" media="screen" />
        <script src="js/navmobile.js" type="text/javascript"></script> <!-- en caso de menu tablet igual a movil -->
        <script type="text/javascript">
        
        $(window).load(function(){
        
            // Same height
            //-------------------------------------------------------------//
            
            $('.sameheight').equalCols();
            
            $('.sameheight_news').equalCols();
            $('.sameheight_products').equalCols();

            // Viewport tablet on init
            //-------------------------------------------------------------//


            if($(window).height() > $(window).width()){

                    $('body').addClass('portrait');
                    $('head').append('<meta name="viewport" content="width=device-width, initial-scale=1"/>');

            }else{

                   $('body').removeClass('portrait');
                   $('head').append('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
            } 
        
        });

        // Viewport tablet on changeoreintation
        //-------------------------------------------------------------//
        
        $(window).on('orientationchange', function() {
            if($(window).height() > $(window).width()){

                $('head').remove('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
                $('body').addClass('portrait');
                $('head').append('<meta class="metar" name="viewport" content="width=device-width, initial-scale=0.75/>');

            }else{
                $('head').remove('<meta name="viewport" content="width=device-width, initial-scale=0.75"/>');
                $('body').removeClass('portrait');
                $('head').append('<meta class="metar" name="viewport" content="width=device-width, initial-scale=1"/>');
            }
        }); 
        
        </script>
		<?php
		
		//Menú tablet - móvil
		if(MENU_TABLET_DESKTOP=='0'){
		
			?><script src="js/navmobile.js" type="text/javascript"></script><?php
		
		}
		
	//Móvil ->
	}else if(($detect->isMobile()) || (DEBUG_MODE_MOBILE == '1')){
		
		?>
        <link href="css/mobile.css" rel="stylesheet" type="text/css" media="screen" />
		<script src="js/navmobile.js" type="text/javascript"></script>
		<?php
	}
} else{
	
	//Escritorio ->
	?>
	<script src="js/functions-justdesktop.js" type="text/javascript"></script>
	<script type="text/javascript">
    
    $(window).load(function(){
    
        // Same height
        //-------------------------------------------------------------//
        $('.sameheight').equalCols();
        
        $('.sameheight_news').equalCols();
        $('.sameheight_products').equalCols();
    
    
	});
    
    </script>
	<?php
}
?>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', '<?php echo METAS_ANALYTICSCLIENTCODE ;?>', 'auto');
    ga('send', 'pageview');
</script>