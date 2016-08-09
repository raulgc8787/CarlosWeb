<?php
// Cierre de toda la construccion de los elementos dentro del body, ademas de la estructura del footer
?>
	
    </div>
	<!-- BODY CONTENT -->
        
</div><!-- /WRAPPER -->
<div id="footMap">
<?php include 'mapas.php';?>
</div>
<div id="footer-wrapper">
        <!-- FOOTER -->
        <footer id="footer">
            
            <div class="container">
                <div class="colum4">
                        <? echo BRAND; ?>
                </div>

                <div class="colum4">
                    <div class="social">
                        <? include('social.php') ?>
                    </div>
                </div>

                <div class="colum4">
                    <ul>
                        <li><a href="<?php echo $links['legal.php']; ?>" title="<?php echo COMMON_NOTALEGAL;?>"><?php echo COMMON_NOTALEGAL;?></a></li>
                        <li><a href="<?php echo $links['politica-cookies.php']; ?>" title="<?php echo COMMON_COOKIES;?>"><?php echo COMMON_COOKIES;?></a></li>
                        <li><a href="http://www.latevaweb.com/" title="<?php echo COMMON_MARKETINGONLINE;?>" target="_blank"><?php echo COMMON_MARKETINGONLINE;?></a></li>
                    </ul>
                </div>
            </div>
            <?php 

			if(($detect->isMobile())){
			
				?><div id="socialFooter">
					<? include('social.php') ?>
				</div><?php 
			
			}
			
			?>
           
      	</footer>
        <!-- /FOOTER -->
    <!-- /FOOTER-CONTAINER-->
</div>
<!-- /FOOTER-WRAPPER-->

</div><!-- /GENERALCONT -->
<script>
    // $(document).ready(function() {
    //     $('#header-wrapper').scroller({
    //         classadded: "symplify",
    //         starton: "0"
    //     });
    // });
       $(document).ready(function() {
        mapLoad(<?php echo MAPS_COORDENADAS;?>, 1); //DEFINO MAPA ORIGEN
    })
</script>