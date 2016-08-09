<? include('cookies.php'); ?>

<?php 

//Menú tablet - desktop
if(MENU_TABLET_DESKTOP=='1'){

	if((($detect->isMobile()) && !($detect->isTablet())) || (DEBUG_MODE_MOBILE == '1')){

		?><div id="mp-menu">
		<?php include('nav.php'); ?>
        </div><?php

	}

//Menú tablet - móvil
}else{
	
	if(($detect->isMobile()) || (DEBUG_MODE_MOBILE == '1') || (DEBUG_MODE_TABLET=='1')){

		$menuType = "menu1"; //CHOOSE NAV MENU
		$auxType = "aux1"; //CHOOSE BAR MENU
		$btnType = "btn1"; //CHOOSE BOTON MENU

		include('mobile/nav_mobile.php'); 
		include('mobile/aux_mobile.php');
		include('mobile/bnt_mobile.php'); 
	
	}

}

?>

<div id="generalCont">

   <div class="bkgblack" onClick="volver();"></div>


<?php 

//Menú tablet - desktop
if(MENU_TABLET_DESKTOP=='1'){

	if((($detect->isMobile()) && !($detect->isTablet())) || (DEBUG_MODE_MOBILE == '1')){

		?><span id="menuResponsive" class="icon-menu" onclick='menuResponsive();'></span><?php 

	}

//Menú tablet - móvil
}else{

	if(($detect->isMobile()) || (DEBUG_MODE_MOBILE == '1') || (DEBUG_MODE_TABLET=='1')){
	
		?><span id="menuResponsive" class="icon-menu" onclick='menuResponsive();'></span><?php 
	
	}

}

?>
<div id="wrapper">
    <header id="header">
    	<div class="container">
	        <div class="logo"><a href="<?php echo $links['index.php'];?>" title="<?php echo METAS_LOGO_TIT;?>">

				<svg version="1.1" id="Capa_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="171.445px" height="55.138px" viewBox="0 -176.568 171.445 55.138" enable-background="new 0 -176.568 171.445 55.138" xml:space="preserve">
					<path d="M7.433-170.413h7.952v36.915h17.909v6.66H7.433V-170.413L7.433-170.413z"/>
					<path d="M75.447-149.077c0,14.611-8.859,22.955-20.883,22.955c-12.286,0-20.041-9.375-20.041-22.175 c0-13.382,8.403-22.825,20.688-22.825C67.945-171.124,75.447-161.491,75.447-149.077z M42.926-148.43	c0,8.855,4.461,15.905,12.09,15.905c7.692,0,12.024-7.113,12.024-16.229c0-8.209-4.073-15.971-12.024-15.971 C47.128-164.724,42.926-157.415,42.926-148.43z"/>
					<path d="M117.205-128.842c-2.777,1.041-8.207,2.457-14.025,2.457c-7.371,0-12.932-1.867-17.068-5.819 c-3.879-3.683-6.143-9.438-6.076-16.03c0-13.772,9.823-22.628,24.244-22.628c5.366,0,9.564,1.099,11.568,2.068l-1.741,6.4 c-2.394-1.035-5.303-1.875-9.896-1.875c-9.307,0-15.836,5.494-15.836,15.644c0,9.892,6.076,15.774,15.125,15.774 c2.847,0,4.978-0.389,6.014-0.905v-10.858h-7.691v-6.272h15.383V-128.842L117.205-128.842z"/>
					<path d="M164.012-149.077c0,14.611-8.859,22.955-20.887,22.955c-12.284,0-20.039-9.375-20.039-22.175 c0-13.382,8.402-22.825,20.691-22.825C156.51-171.124,164.012-161.491,164.012-149.077z M131.489-148.43 c0,8.855,4.461,15.905,12.089,15.905c7.697,0,12.027-7.113,12.027-16.229c0-8.209-4.072-15.971-12.027-15.971 C135.692-164.724,131.489-157.415,131.489-148.43z"/>
				</svg>
				
	        <!-- <img src="images/logos/logo.svg" alt="<?php echo METAS_LOGO_ALT;?>" /> -->
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

			<?php 

			if(!($detect->isMobile()) && (DEBUG_MODE_MOBILE == '0') && (DEBUG_MODE_TABLET=='0')){
			
				?><div class="social">
					<? include('social.php') ?>
	            </div><?php 
			
			}
			
			?>
		</div>

		
	    <?php 
		//Menú tablet - desktop
		if(MENU_TABLET_DESKTOP=='1'){
		
			if((($detect->isMobile()) && !($detect->isTablet())) || (DEBUG_MODE_MOBILE == '1')){
		
				;
		
			}else{
		
				?><nav id="nav"><div class="container"><?php include('nav.php'); ?></div></nav><?php
			
			}
		
		//Menú tablet - móvil
		}else{
		
			if(($detect->isMobile()) || (DEBUG_MODE_MOBILE == '1') || (DEBUG_MODE_TABLET=='1')){
			
				;
			
			}else{
		
				?><nav id="nav"><div class="container"><?php include('nav.php'); ?></div></nav><?php
			
			}
		
		}
		
		?>
	    <!-- /NAV -->

    </header>

    <!-- BODY CONTENT -->
    <div id="content">
    
