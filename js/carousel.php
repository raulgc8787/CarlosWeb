<?php 

	session_start();
	include('../head.php');

	// Recibimos los parametros establecidos

		$controls   	= $_POST['controls'];
		$thumbsPrefix   = $_POST['thumbsPrefix'];
		$bigImgPrefix 	= $_POST['bigImgPrefix'];
		$lightBox 		= $_POST['lightBox'];
		$table 			= $_POST['table'];
		$id_elem 		= $_POST['id_elem'];
		$isCover		= $_POST['isCover'];
		$thumbs			= $_POST['thumbs'];
		$nextPrev		= $_POST['nextPrev'];

	// Ponemos un display según tengamos o no thumbs

		if($thumbs[0] == "false"){
			$isDisplay = "none";
		}else{
			$isDisplay = "block";
		}

	// Hacemos la busqueda dentro del table seleccionada de la foto grande

		$count = 0;

		$q_carousel = mysqli_query($link,"SELECT * FROM ".$table." WHERE id_elem = '".$id_elem."' ORDER BY orden ASC");
		$numRows =  mysqli_num_rows($q_carousel); // Buscamos los resultados para
		
		while($r_carousel = mysqli_fetch_array($q_carousel)){


			// Imprmimos la imagen grande

				// Si no hay cover ni Lightbox

					if($count == 0 && $isCover[0] == "false" && $lightBox == "false"){

						echo "<div id='bigImg'><img src='pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto']."'></div>";

				// Si hay cover ni Lightbox

					}else if($count == 0 && $isCover[0] == "true" && $lightBox == "false"){

						echo "<div id='bigImg' style='background: url(pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto'].") no-repeat ;background-size: cover;width:".$isCover[1]."".$isCover[3].";height:".$isCover[2]."px;'></div>";

				// Si no hay cover pero si Lightbox

					}else if($count == 0 && $isCover[0] == "false" && $lightBox == "true"){

						echo "<div id='bigImg'><a href='pics_".$table."/".$id_elem."/".$r_carousel['foto']."' rel='photo'><img src='pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto']."'></a></div>";

				// Si hay cover pero si Lightbox

					}else if($count == 0 && $isCover[0] == "true" && $lightBox == "true"){

						echo "<div id='bigImg'><a href='pics_".$table."/".$id_elem."/".$r_carousel['foto']."' rel='photo' style='background:url(pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto'].") no-repeat;display:block;background-size: cover;width:".$isCover[1]."".$isCover[3].";height:".$isCover[2]."px;'></div></a>";
					}

			

				// Imprmimos las imagens pequeñas si hay más de una

				if($numRows > 1){


					// Si es mayor que 0 y tiene lightbox

					if($count > 0 && $lightBox == "true"){
					
						echo "<li id='img".$count."' onclick='changeImages(this,\"pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto']."\",\"pics_".$table."/".$id_elem."/".$r_carousel['foto']."\",".$isCover[0].",".$isCover[1].",".$isCover[2].",\"".$isCover[3]."\")'><img src='pics_".$table."/".$id_elem."/".$thumbsPrefix."_".$r_carousel['foto']."'></li>";
				

					}else if($count > 0 && $lightBox == "false"){

						echo "<li id='img".$count."'onclick='changeImages(this,\"pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto']."\",false,".$isCover[0].",".$isCover[1].",".$isCover[2].",\"".$isCover[3]."\")'><img src='pics_".$table."/".$id_elem."/".$thumbsPrefix."_".$r_carousel['foto']."'></li>";

					}else if($count == 0 && $lightBox == "true"){

						echo "<div class='".$thumbs[1]."' ><ul id='smallImg' style='display:".$isDisplay."'><li id='img".$count."' onclick='changeImages(this,\"pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto']."\",\"pics_".$table."/".$id_elem."/".$r_carousel['foto']."\",".$isCover[0].",".$isCover[1].",".$isCover[2].",\"".$isCover[3]."\")' class='currentThumb'><img src='pics_".$table."/".$id_elem."/".$thumbsPrefix."_".$r_carousel['foto']."'></li>";

					}else if($count == 0 && $lightBox == "false"){

						echo "<div  class='".$thumbs[1]."' ><ul id='smallImg' style='display:".$isDisplay."'><li id='img".$count."' onclick='changeImages(this,\"pics_".$table."/".$id_elem."/".$bigImgPrefix."_".$r_carousel['foto']."\",false,".$isCover[0].",".$isCover[1].",".$isCover[2].",\"".$isCover[3]."\")' class='currentThumb'><img src='pics_".$table."/".$id_elem."/".$thumbsPrefix."_".$r_carousel['foto']."'></li>";
					}

				} // EOF numRows
		

			// Subimos el count i si es = que el numero de rows, cerramos ul

				if($count == $numRows-1){

					if($numRows > 1){
						
						echo "</ul></div>";

						// Si las imagenes son más de una y estan activados los controles

						if($controls == "true"){

							echo '<div><span id="prevPic" onclick="controlsCarousel(0)" class="disabledControl">'.$nextPrev[0].'</span><span id="nextPic" onclick="controlsCarousel(1)">'.$nextPrev[1].'</span></div>';
						}
					}

				}else{

					$count++;

				}
				
		} // EOF while

?>