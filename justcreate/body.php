<?php
// Construccion de la estructura en el body 
?>
<div id="wrapper"><div class="container">

    <header id="header">

        <div class="col03 first">
            <div class="logo"><a href="index.php" title="Inicio"><img src="../images/logos/logo.png" style="padding:10px; background-color: #ddd;" /></a></div>
        </div>
        
        <div class="col03">
        	<p><em><?php echo CLIENTE;?></em> <br />
	  	    <?php echo $backoffice['titulo'][$_SESSION['bo_idioma']];?></p>
        </div>
        
        <div class="col03">
                <ul>
                    <li><em><?php echo $backoffice['usuario'][$_SESSION['bo_idioma']];?></em>: <?php echo $_SESSION['username'];?> &#8212; <a href="secure/logout.php" class="btn-logout"><?php echo $backoffice['desconectar'][$_SESSION['bo_idioma']];?></a></li>
                    <li><em><?php echo $backoffice['fecha'][$_SESSION['bo_idioma']];?></em>: <?php setlocale(LC_ALL, 'es_ES'); date_default_timezone_set('Europe/Madrid'); echo strftime("%d %h %Y");?></li>
                </ul>
      	</div>
        
        <div class="col03 last">
			
            <form action="index.php" method="post">
            <select name="bo_idioma" onchange="this.form.submit()" style="width:50px; float:right;">
            <?php 
			
			$idiomas = db_query($link,"SELECT * FROM config_idiomesbackoffice");
			
			while($row_idioma = mysqli_fetch_array($idiomas)){
			
				?><option value="<?php echo $row_idioma['nom_idioma']; ?>" <?php if($row_idioma['nom_idioma']==$_SESSION['bo_idioma']){ echo "selected"; }?>><?php echo $row_idioma['nom_idioma']; ?></option><?php
			
			}	
		
			?>
            </select>
            </form>
        </div>
        </header> <!-- /HEADER -->
        <!-- NAV -->
        <nav id="nav">
        	<div id="menuResponsive" class="icon-menu"></div>
            <ul>

                <li><strong>Post</strong>
                  <ul>
                    <li><a href="noticias.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                    <li><a href="noticias.php?action=insert"><?php echo $backoffice['l_crear'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>

                <li><strong>Libros</strong>
                  <ul>
                    <li><a href="equipo.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                    <li><a href="equipo.php?action=insert"><?php echo $backoffice['l_crear'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>
                <li><strong>Slider Home</strong>
                  <ul>
                    <li><a href="slide.php?action=update&id=1">Editar</a></li>
                  </ul>
                </li>
                <li><strong><?php echo $backoffice['menu_textos'][$_SESSION['bo_idioma']]; ?></strong>
                  <ul>
                    <li><a href="textos.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>
                <?php
				if($_SESSION['textos'] == 1){
				?>
                <!-- PONER SECCION QUE NO SE QUIERA SEGUN EL ROL (antes estaban los textos) -->
                <?php
				}
				?>
                <?php
				if($_SESSION['rol'] == 0){
				?>
                <li><strong> <?php echo $backoffice['menu_configuracion_general'][$_SESSION['bo_idioma']]; ?></strong>
                  <ul>
                    <li><a href="config_general.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                    <li><a href="config_general.php?action=insert"><?php echo $backoffice['l_crear'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>
                <li><strong><?php echo $backoffice['menu_configuracion_thumbs'][$_SESSION['bo_idioma']]; ?></strong>
                  <ul>
                    <li><a href="thumbs.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                    <li><a href="thumbs.php?action=insert"><?php echo $backoffice['l_crear'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>
                <li><strong><?php echo $backoffice['menu_usuarios'][$_SESSION['bo_idioma']]; ?></strong>
                  <ul>
                    <li><a href="usuaris.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                    <li><a href="usuaris.php?action=insert"><?php echo $backoffice['l_crear'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>
                <li><strong><?php echo $backoffice['menu_textos_backoffice'][$_SESSION['bo_idioma']]; ?></strong>
                  <ul>
                    <li><a href="textos_backoffice.php"><?php echo $backoffice['l_listado'][$_SESSION['bo_idioma']];?></a></li>
                    <li><a href="textos_backoffice.php?action=insert"><?php echo $backoffice['l_crear'][$_SESSION['bo_idioma']];?></a></li>
                  </ul>
                </li>
                <?php
				}
				?>
            </ul>
            <div class="logoJust"><?php echo $backoffice['diseno_web'][$_SESSION['bo_idioma']]; ?>: <a href="http://www.justcreate.io/" target="_blank" title="Diseño web">Just Create</a></div>
        </nav>
        <!-- /NAV -->
   
    
    <!-- BODY CONTENT -->
    <div id="content">