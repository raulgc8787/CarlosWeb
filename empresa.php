<?php 
include("head.php");
?>
<!DOCTYPE html>
<html lang="<?php echo IDIOMA;?>">
<head>
<title><?php echo METAS_SERVICIOS_TIT;?></title>
<meta name="keywords" content="<?php echo METAS_KW;?>" />
<meta name="description" content="<?php echo METAS_SERVICIOS_DESC;?>" />

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript" src="js/mapamultiple-js.js"></script>

<?php include("header.php");?>
</head>

<body class="empresa srvPage maServicio <?php echo IDIOMA;?> flex">

<?php include("body.php");?>
<div class="popContact"><?php echo COMMON_CALL; ?> <br/><span class="num"><?php echo PHONE; ?></span></div>


<?php
$prefijo = "";
if($detect->isMobile()) $prefijo = "mob_";
?>

<div class="outtercbp">	
	<ul id="cbp-bislideshow" class="cbp-bislideshow">
		<li>
			<img src="images/branding/<?php echo $prefijo;?>35.jpg" alt="<?php echo METAS_SERVICIOS_ALT; ?>">
				<div class="container">
					<em><h1><?php echo SERVICIOS_H1; ?></h1></em>
				</div>
		</li>
	</ul>
</div>

<div class="block1">
	<div class="intro">  

      <!-- <div class="columnsCSS">

        <div class="text"><? echo SERVICIOS_TEXTO;?></div>

      </div>
      <div class="clearfix"></div> -->


				<div class="columnsCSS"><div class="text"><? echo SERVICIOS_TEXTO;?></div></div>
        <div class="companyservices">
        <div class="row"> 
          <div class="half align-C"><img src="images/branding/servicio1.jpg" alt="<?php echo METAS_SERVICIOS_ALT2;?>"></div>
          <div class="half"><div class="text"><? echo SERVICIOS_TEXTO1B;?></div></div>
        </div>
        <div class="row"> 
            <div class="half align-C fright"><img src="images/branding/servicio2.jpg" alt="<?php echo METAS_SERVICIOS_ALT3;?>"></div>
           <div class="half fleft"><div class="text"><? echo SERVICIOS_TEXTO1C;?></div></div>
        </div>
        <div class="row"> 
            <div class="half align-C"><img src="images/branding/servicio3.jpg" alt="<?php echo METAS_SERVICIOS_ALT4;?>"></div>
            <div class="half"><div class="text"><? echo SERVICIOS_TEXTO1D;?></div></div>
        </div>
        </div>

	</div>
</div>

<!-- <div class="row2">
  <div class="container">
    <div class="imagen"><img src="images/branding/bkSlider07.jpg"></div>
    <div class="qSom2">
      <h2>1.</h2>
      <div class="text"><?php echo SERVICIOS_TEXTO2; ?> </div>
    </div>
    
  </div>
</div>


<div class="block1">
  <div class="container">
      <div class="columnsCSS">
        <div class="text"><? echo SERVICIOS_TEXTO3; ?></div>
      </div><div class="clearfix"></div>
  </div>
</div> -->


<?php include("footer.php");?>
<!-- ///// cbp-bislideshow ///// -->
<script src="js/modernizr.custom.js"></script>
<script src="js/jquery.imagesloaded.min.js"></script>
<script src="js/cbpBGSlideshow.js"></script>

<!-- ///// owl-carousel ///// -->
<!-- <script src="js/jquery-1.9.1.min.js"></script> -->
<script src="js/owl.carousel.min.js"></script>
<script>
$(document).ready(function() {
	var owl = $('.owl-carousel');
  owl.owlCarousel({
    margin: 15,
    loop: true,
    nav:true,
    navText: [
      "",
      ""
      ],
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      1000: {
        items: 3
      }
    }
  })

    $('#btnTarget').fullHeightImage({
		btn: "#btnDown",
		margintop: "130",
		speed:1000,
		HeightWindow: false
    });

    // heightAll('#cbp-bislideshow');
	
    heightAll('#cbp-bislideshow');

})


</script>
</body>
</html>
<?php include("bottom.php");?>