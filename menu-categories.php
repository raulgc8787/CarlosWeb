	<div class="row">
		<div class="container">
		 	<div id="secondary-nav">
				<ul>
                	<?php
					$c = mysqli_query($link,"SELECT * FROM categorias_productos t, categorias_productos_content tc WHERE t.id=tc.id AND tc.idioma='".IDIOMA."' ORDER BY t.orden ASC");
					while($categorias = mysqli_fetch_array($c)){
						echo '<li><a href="'.urls_amigables($categorias['text_nombre']).'-t-'.$categorias['id'].'-'.IDIOMA.'" ';
						if($categorias['id'] == $current_category) echo ' class="current" ';
						echo '>'.$categorias['text_nombre'].'</a></li>';
					}
					?>
				</ul>
			</div><!-- /#secondary-nav -->
		</div><!-- /container -->
	</div><!-- /row-->