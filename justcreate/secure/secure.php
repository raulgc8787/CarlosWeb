<?php
//login o logout?
if(isset($logout) || isset($_GET["logout"]) || isset ($_POST["logout"])){

include ("./secure/logout.php");

} else {

include ("./secure/checkLogin.php");

}
//Que bonitos son los libros !!!!!!!! Sobretodo los de dibujitos
?>
