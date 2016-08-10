<?php 

ini_set('error_reporting', E_ALL ^ E_NOTICE);

//BBDD
$HOSTNAME = "db641681833.db.1and1.com"; //SERVIDOR
$USERNAME = "dbo641681833"; //USUARIO
$PASSWORD = "garcia25"; //CONTRASEÑA
$DATABASE = "db641681833"; //BASE DE DATOS

//Inicio la sesión
session_start();

//Directiva magic quotes activada ->
if (get_magic_quotes_gpc() === 1){

    function stripslashes_array(&$arr) {
        foreach ($arr as $k => &$v) {
            $nk = stripslashes($k);
            if ($nk != $k) {
                $arr[$nk] = &$v;
                unset($arr[$k]);
            }
            if (is_array($v)) {
                stripslashes_array($v);
            } else {
                $arr[$nk] = stripslashes($v);
            }
        }
    }

    stripslashes_array($_POST); 
    stripslashes_array($_GET);
    stripslashes_array($_REQUEST);
    stripslashes_array($_COOKIE);
}

//DEFINICIONES PARA EL SITIO
define("MAILTO"," "); //Email donde se envia el formulario de contacto
define("IDIOMADEFAULT","es"); //Idioma por defecto
define("PORPAGINA",5); //Numero de noticias a mostrar en la paginacion de noticias_xx.php
define("LATERAL",4); //Numero de noticias que aparecen en el historico en la pagina de noticia_xx.php
define("CLIENTE","carlosgarciacastilla.com"); //Nombre del cliente que pintamos en el backoffice, legal y contacto
define("BRAND","carlosgarciacastilla.com"); //Nombre del cliente que pintamos en el backoffice, legal y contacto
define("NIF"," "); //Legal
define("PHONE"," "); //Legal y contacto
define("TINY_IMG","/images/pics"); //Ruta DESDE LA RAIZ DEL DOMINIO (en este caso hosteum.com)

?>