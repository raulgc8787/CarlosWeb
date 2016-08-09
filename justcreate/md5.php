<link href="css/layout.css" rel="stylesheet" type="text/css" media="screen" />
<form method="post">
<input type="text" name="md5" id="md5" />
<input type="submit" value="Generar md5" />
</form>
<?php 
if(isset($_POST['md5'])){
	echo "El md5 de ".$_POST['md5']." es:<br />".md5($_POST['md5']);
}
//echo md5("ag3manteniment");
?>