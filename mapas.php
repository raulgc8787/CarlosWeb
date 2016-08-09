<div class="row mapas">
        <div class="mapcontent">
                
                     <div class="ubicacion">
                    <div class="row"><img src="images/icons/ico-contact-01.svg" alt="<?php echo METAS_CONTACTO_ALT;?>"><h2><?php echo FOOTER_CONTACTO;?></h2></div>
                        <ul id="centros">
                            <li>
                                <p class="title">Barcelona</p>
                                <p>C/ Diputació, 304 Pral. 3º 08009 Barcelona</p>
                                <p>Tel 93 342 64 66</p>
                                <p>Fax 93 342 66 64</p>
                                <p><?php echo MAILTO; ?></p>
                                <a href="#map_canvas"><div  id="centro1" class="button smallest link" onclick="mapLoad(<?php echo MAPS_COORDENADAS;?>, 1);" ><?php echo COMMON_VERUEALMAPA;?></div></a>
                            </li>
                            <li>
                                <p class="title">Sant Sadurní d’Anoia</p>
                                <p>Plaça de l'Ajuntament 5, 2n 2a</p>
                                <p>08770 Sant Sadurní d’Anoia</p>
                                <p>Tel/Fax 93 818 37 29</p>
                                <p><?php echo MAILTO; ?></p>
                                <a href="#map_canvas"><div  id="centro2" class="button smallest link" onclick="mapLoad(<?php echo MAPS_COORDENADAS2;?>, 1);" ><?php echo COMMON_VERUEALMAPA;?></div></a>
                            </li>
                         </ul>
                </div>

                <div class="map" id="map_canvas"></div>
               
        </div>
</div>