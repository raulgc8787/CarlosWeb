<?php
// Cierre de toda la construccion de los elementos dentro del body, ademas de la estructura del footer
?>

<!-- Productos type -->

<?php
switch ($menuType) {
    case "menu1":
?> 

	<div class="menuBurgerRight">
		<input type="checkbox" id="menu-toggle1"/>
		<label id="trigger1" for="menu-toggle1"></label>
		<label id="burger1" class="burgerMenu" for="menu-toggle1"></label>
		<ul id="menu1" class="burgerList">
			<li id="language-nav">
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
	        </li>

			<?php include('mobile/nav_mobile_menu.php'); ?>
		</ul>
	</div>
	
		<div class="logo">
			<a href="<?php echo $links['index.php'];?>" title="<?php echo METAS_LOGO_TIT;?>">
				<img class="logoW" src="images/logos/logo.svg" alt="<?php echo METAS_LOGO_ALT;?>" />
				<img class="logoG" src="images/logos/logo2.svg" alt="<?php echo METAS_LOGO_ALT;?>" />
			</a>
		</div>
	

<?php
    break;
    case "menu2":
?>

	<div class="menuBurgerLeft">
		<input type="checkbox" id="menu-toggle2"/>
		<label id="trigger2" for="menu-toggle2"></label>
		<label id="burger2" class="burgerMenu" for="menu-toggle2"></label>
		<ul id="menu2" class="burgerList">
			<?php include('mobile/nav_mobile_menu.php'); ?>
		</ul>
	</div>
	
	<div class="logoCenter">
		<a href="<?php echo $links['index.php'];?>" title="<?php echo METAS_LOGO_TIT;?>">
			<img src="images/logos/logo.svg" alt="<?php echo METAS_LOGO_ALT;?>" />
		</a>
	</div>

	<div class="menuBurgerRight">
		<input type="checkbox" id="menu-toggle1"/>
		<label id="trigger1" for="menu-toggle1"></label>
		<label id="burger1" class="burgerUser" for="menu-toggle1"></label>
		<ul id="menu1" class="burgerList">
			<?php include('mobile/nav_mobile_user.php'); ?>
		</ul>
	</div>
	
	<script type="text/javascript">
	    $(document).ready(function(){
	        $('#burger2').click(function() {
                $( ".menuBurgerRight" ).toggleClass( 'iconBack' );
            });
	    }); 
	</script>


<?php
    break;
    case "menu3":
?>

	<div class="menuBurgerLeft">
		<input type="checkbox" id="menu-toggle2"/>
		<label id="trigger2" for="menu-toggle2"></label>
		<label id="burger2" class="burgerMenu" for="menu-toggle2"></label>
		<ul id="menu2" class="burgerList">
			<?php include('mobile/nav_mobile_menu.php'); ?>
		</ul>
	</div>
	
	<div class="logoCenter">
		<a href="<?php echo $links['index.php'];?>" title="<?php echo METAS_LOGO_TIT;?>">
			<img src="images/logos/logo.svg" alt="<?php echo METAS_LOGO_ALT;?>" />
		</a>
	</div>

	<div class="menuBurgerRight">
		<a class="iconCartHeader" href="#" title="">
			<img src="images/icons/cart.svg" alt="" />
		</a>
	</div>
	<?php //include('mobile/nav_mobile_carrito.php'); ?>

	<script type="text/javascript">
	    $(document).ready(function(){
	        $('#burger2').click(function() {
                $( ".menuBurgerRight" ).toggleClass( 'iconBack' );
            });
	    }); 
	</script>

<?php
    break;
    case "menu4":
?>

	<div class="menuBurgerLeft">
		<input type="checkbox" id="menu-toggle2"/>
		<label id="trigger2" for="menu-toggle2"></label>
		<label id="burger2" class="burgerMenu" for="menu-toggle2"></label>
		<ul id="menu2" class="burgerList">
			<?php include('mobile/nav_mobile_menu.php'); ?>
		</ul>
	</div>
	
	<div class="logoCenter">
		<a href="<?php echo $links['index.php'];?>" title="<?php echo METAS_LOGO_TIT;?>">
			<img src="images/logos/logo.svg" alt="<?php echo METAS_LOGO_ALT;?>" />
		</a>
	</div>

	<div class="menuBurgerCenter">
		<input type="checkbox" id="menu-toggle3"/>
		<label id="trigger3" for="menu-toggle3"></label>
		<label id="burger3" class="burgerSearch" for="menu-toggle3"></label>
		<div id="menu3">
			<?php include('mobile/nav_mobile_search.php'); ?>
		</div>
	</div>

	<div class="menuBurgerRight">
		<input type="checkbox" id="menu-toggle1"/>
		<label id="trigger1" for="menu-toggle1"></label>
		<label id="burger1" class="burgerUser" for="menu-toggle1"></label>
		<ul id="menu1" class="burgerList">
			<?php include('mobile/nav_mobile_user.php'); ?>
		</ul>
	</div>

	<?php //include('mobile/nav_mobile_search.php'); ?>
	<?php //include('mobile/nav_mobile_aux.php'); ?>
	
	<script type="text/javascript">
	    $(document).ready(function(){
	        $('#burger2').click(function() {
                $( ".menuBurgerRight" ).toggleClass( 'iconBack' );
                $( ".menuBurgerCenter" ).toggleClass( 'iconBack' );
            });
            $('#burger3').click(function() {
                $( ".menuBurgerRight" ).toggleClass( 'iconBack' );
                $( ".menuBurgerLeft" ).toggleClass( 'iconBack' );
            });
            $('#burger1').click(function() {
                $( ".menuBurgerCenter" ).toggleClass( 'iconBack' );
            });
	    }); 
	</script>

<?
        break;
}
?>
