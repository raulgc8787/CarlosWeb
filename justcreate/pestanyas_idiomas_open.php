<?php
// ***
// Pestañas idiomas
// ***
$l = db_query($link,"SELECT * FROM config_idiomes ORDER BY id_idioma ASC");
echo '<ul id="idiomas">';
while($arl = mysqli_fetch_array($l)){
	echo '<li';
	if($arl['nom_idioma'] == $_SESSION['idioma']){
		echo ' class="actiu"';
	}
	echo ' id="pestanya'.$arl['nom_idioma'].'"><a onclick="cambia_idioma(\''.$arl['nom_idioma'].'\')">'.$arl['nom_idioma'].'</a></li>';
}
echo '</ul>';

?>