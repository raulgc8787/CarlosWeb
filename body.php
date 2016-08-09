<? include('cookies.php'); ?>

<div id="generalCont">

   <div class="bkgblack" onClick="volver();"></div>

<div id="wrapper">
	<?php
	if(!$detect->isMobile() || ($detect->isMobile() && $detect->isTablet() && MENU_TABLET_DESKTOP == 1)){
	?>
    <header id="header">
    	 <div class="logo"><a href="<?php echo $links['index.php'];?>" title="<?php echo METAS_LOGO_TIT;?>">


	        <img src="images/logos/logo.svg" alt="<?php echo METAS_LOGO_ALT;?>" />
	        </a></div>
	        <div id="language-nav">
	            <ul>
	            	<?php
					$i = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
					while($idiomas = mysqli_fetch_array($i)){
						echo '<li class="language_'.$idiomas['nom_idioma'].'">';
						$b = db_query($link,"SELECT * FROM textos_content WHERE idioma='".$idiomas['nom_idioma']."' AND id IN(SELECT id FROM textos WHERE texT_nodisabled = 'index.php' )");
						$busca = mysqli_fetch_array($b);
						if($idiomas['nom_idioma'] != IDIOMA){
							echo '<a href="'.$busca['textarea_titulo'].'">'.strtoupper($idiomas['nom_idioma']).'</a>';
						} else{
							echo '<span>'.strtoupper($idiomas['nom_idioma']).'</span>';
						}
						echo '</li>';
					}
					?>
	            </ul>
	        </div>

				<div class="social">
					<? include('social.php') ?>
	            </div>

			<nav id="nav"><?php include('nav.php'); ?></nav>
			
	    <!-- /NAV -->

    </header>

     <?php
	} 
	else {
	?>
    	<!-- menu mobile -->	
		<?php 
			$menuType = "menu1"; //CHOOSE NAV MENU - menu1
			$auxType = ""; //CHOOSE BAR MENU - aux1
			$btnType = "btn1"; //CHOOSE BOTON MENU - btn1
		?>
		
			<script type="text/javascript">
		      $(document).ready(function(){
		            var altura = $('.mainHeader').offset().top;

		            $(window).on('scroll', function(){
		                   if ( $(window).scrollTop() > altura ){
		                        $('.mainHeader').addClass('header');
		                        $('.header').removeClass('mainHeader')
		                   } else {
		                        $('.header').addClass('mainHeader');
		                        $('.mainHeader').removeClass('header')
		                        }
				            });
				      });
			</script>

		<header class="mainHeader">
			<?php include('mobile/nav_mobile.php');  ?>
		</header>
		<?php 
			include('mobile/aux_mobile.php');
			include('mobile/btn_mobile.php'); 
		?>
		<!-- end menu mobile -->

    <?php
	}
	?>

    <!-- BODY CONTENT -->
    <div id="content">
    
