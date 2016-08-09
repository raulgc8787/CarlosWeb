<?php

if($_COOKIE["accept_cookies"] != "yes"){

	?><div id="cookies_msg_subwrapper">
       <? echo COMMON_POLITICA_COOKIES_TEXTE; ?>
       <a href="<?php echo $links['politica-cookies.php'];?>" title="política de cookies"><? echo COMMON_POLITICA_COOKIES_INFO; ?></a>
       <span id="cookies" class="cookies_msg_button" ><? echo COMMON_POLITICA_COOKIES_ACEPTAR; ?></span>
    </div>
    <?php 

}

?>