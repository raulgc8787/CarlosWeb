<?php 
session_start();
unset($_SESSION['user']);
unset($_SESSION['pass']);
unset($_SESSION['categoria']);
$_SESSION = array();
session_destroy();
$sessionPath = session_get_cookie_params();
setcookie(session_name(), "", 0, $sessionPath["path"], $sessionPath["domain"]);
header("Location: ../no_mem.php");
?>