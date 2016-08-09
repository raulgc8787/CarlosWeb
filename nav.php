<ul>
    <li <?php if((basename($_SERVER['PHP_SELF']))=='matrimonis.php'){ echo 'class="current"';}?>><a href="<?php echo $links['matrimonis.php'];?>" title="<?php echo METAS_MENU_MATRIMONIOS;?>"><?php echo MENU_MATRIMONIOS;?></a></li>

    <li <?php if((basename($_SERVER['PHP_SELF']))=='herencia.php'){ echo 'class="current"';}?>><a href="<?php echo $links['herencia.php'];?>" title="<?php echo METAS_MENU_HERENCIAS;?>"><?php echo MENU_HERENCIAS;?></a></li>

    <li <?php if((basename($_SERVER['PHP_SELF']))=='bancario.php'){ echo 'class="current"';}?>><a href="<?php echo $links['bancario.php'];?>" title="<?php echo METAS_MENU_BANCARI;?>"><?php echo MENU_BANCARI;?></a></li>

    <li <?php if((basename($_SERVER['PHP_SELF']))=='inmobiliari.php'){ echo 'class="current"';}?>><a href="<?php echo $links['inmobiliari.php'];?>" title="<?php echo METAS_MENU_INMOBILIARI;?>"><?php echo MENU_INMOBILIARI;?></a></li>

    <li <?php if((basename($_SERVER['PHP_SELF']))=='empresa.php'){ echo 'class="current"';}?>><a href="<?php echo $links['empresa.php'];?>" title="<?php echo METAS_MENU_SERVICIOS;?>"><?php echo MENU_SERVICIOS;?></a></li>

    <li <?php if((basename($_SERVER['PHP_SELF']))=='listado_productos.php' || ((basename($_SERVER['PHP_SELF']))=='ficha_productos.php')){ echo 'class="current"';}?>><a href="<?php echo $links['listado_productos.php'];?>" title="<?php echo METAS_MENU_EMPRESA_TIT;?>"><?php echo MENU_EMPRESA;?></a>
    		<!-- <ul>
  				  <li <?php if(((basename($_SERVER['PHP_SELF']))=='listado_noticias.php') || ((basename($_SERVER['PHP_SELF']))=='ficha_noticias.php')){ echo 'class="current"';}?>><a href="<?php echo $links['listado_noticias.php'];?>" title="<?php echo METAS_MENU_NOTICIAS_TIT;?>"><?php echo MENU_NOTICIAS;?></a>
  				  </li> 
    	</ul> -->
    	</li>
    <li <?php if(((basename($_SERVER['PHP_SELF']))=='contacto.php') || ((basename($_SERVER['PHP_SELF']))=='contacto-ok.php')){ echo 'class="current"';}?>><a href="<?php echo $links['contacto.php'];?>" title="<?php echo METAS_MENU_CONTACTO_TIT;?>"><?php echo MENU_CONTACTO;?></a></li>
</ul>