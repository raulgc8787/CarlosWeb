<?php
//require_once("head.php");

function htaccess() {

	global $link;

	$web_host = $_SERVER['HTTP_HOST'];
	$web_folder = str_replace("/justcreate", "", dirname($_SERVER['SCRIPT_NAME']));

	//REGLAS FIJAS (ESPECIFICAS DEL SITIO PERO CREADAS MANUALMENTE)
	$fp = fopen("../.htaccess", "w");

	fwrite($fp, "ErrorDocument 404 http://" . $web_host . "" . $web_folder . "/404.php\r\n");

	fwrite($fp, "Options +FollowSymLinks\r\n");
	fwrite($fp, "RewriteEngine On\r\n");

	if ($web_folder != '') {

		fwrite($fp, "RewriteBase " . $web_folder . "\r\n");

	} else {

		fwrite($fp, "RewriteBase / \r\n");

	}

	fwrite($fp, "RewriteCond %{HTTP_HOST} ^www.carlosgraciacastilla.com$ [NC] \r\n");
	fwrite($fp, "RewriteRule ^(.*)$ http://carlosgraciacastilla.com/$1 [R=301,L] \r\n"); 

	fwrite($fp, "RewriteRule ^(.*)-(n)-([0-9]+)-(.*)/? ficha_noticias.php?lang=$4&id=$3 [L] \r\n");
	fwrite($fp, "RewriteRule ^(.*)-(s)-([0-9]+)-(.*)/? ficha_servicios.php?lang=$4&id=$3 [L] \r\n");

	fwrite($fp, "RewriteRule ^(.*)-(t)-([0-9]+)-(.*)/? listado_productos.php?lang=$4&id=$3 [L]  \r\n");
	fwrite($fp, "RewriteRule ^(.*)-(p)-([0-9]+)-(.*)/? ficha_productos.php?lang=$4&id=$3 [L] \r\n");

	fclose($fp);

	//SITEMAP (para eludir los crons)
	$fp = fopen("../.htaccess", "a+");
	fwrite($fp, "RewriteRule ^sitemap\.xml$ sitemap_xml.php [L] \r\n");
	fclose($fp);

	//Ftpsync
	$fp = fopen("../.htaccess", "a+");
	fwrite($fp, "RewriteRule ^ftpsync\.settings$ index.php [L] \r\n");
	fclose($fp);

	//REGLAS CREADAS POR EL USUARIO DESDE EL BACKOFFICE
	$fp = fopen("../.htaccess", "a+");
	$urls = db_query($link, "SELECT * FROM textos t, textos_content tc WHERE t.id=tc.id AND t.text_nodisabled LIKE '%.%' AND textarea_titulo != ''");

	while ($url = mysqli_fetch_array($urls)) {

		$fichero = $url['text_nodisabled'];
		$linea = "RewriteRule ^" . $url['textarea_titulo'] . "$ " . $fichero . "?lang=" . $url['idioma'] . "&%{QUERY_STRING} [L] \r\n";
		fwrite($fp, $linea);

	}

	//SITEMAP (para eludir los crons)

	fwrite($fp, '
	#compress text, html, javascript, css, xml:
	AddOutputFilterByType DEFLATE text/plain
	AddOutputFilterByType DEFLATE text/html
	AddOutputFilterByType DEFLATE text/xml
	AddOutputFilterByType DEFLATE text/css
	AddOutputFilterByType DEFLATE application/xml
	AddOutputFilterByType DEFLATE application/xhtml+xml
	AddOutputFilterByType DEFLATE application/rss+xml
	AddOutputFilterByType DEFLATE application/javascript
	AddOutputFilterByType DEFLATE application/x-javascript
	AddOutputFilterByType DEFLATE image/svg+xml

	<ifmodule mod_mime.c>
	AddType font/opentype .otf
	AddType font/eot .eot
	AddType font/truetype .ttf
	</ifmodule>

	AddOutputFilterByType DEFLATE font/opentype
	AddOutputFilterByType DEFLATE font/truetype
	AddOutputFilterByType DEFLATE font/eot

	');

	fwrite($fp, "\r\n");

	fwrite($fp, '
	<IfModule mod_expires.c>
	  ExpiresActive on
	# Perhaps better to whitelist expires rules? Perhaps.
	  ExpiresDefault                          "access plus 1 month"
	# cache.appcache needs re-requests in FF 3.6 (thanks Remy ~Introducing HTML5)
	  ExpiresByType text/cache-manifest       "access plus 0 seconds"
	# Your document html
	  ExpiresByType text/html                 "access plus 0 seconds"
	# Data
	  ExpiresByType application/json          "access plus 0 seconds"
	  ExpiresByType application/xml           "access plus 0 seconds"
	  ExpiresByType text/xml                  "access plus 0 seconds"
	# Feed
	  ExpiresByType application/atom+xml      "access plus 1 hour"
	  ExpiresByType application/rss+xml       "access plus 1 hour"
	# Favicon (cannot be renamed)
	  ExpiresByType image/x-icon              "access plus 1 week"
	# Media: images, video, audio
	  ExpiresByType audio/ogg                 "access plus 1 month"
	  ExpiresByType image/gif                 "access plus 1 month"
	  ExpiresByType image/jpeg                "access plus 1 month"
	  ExpiresByType image/png                 "access plus 1 month"
	  ExpiresByType video/mp4                 "access plus 1 month"
	  ExpiresByType video/ogg                 "access plus 1 month"
	  ExpiresByType video/webm                "access plus 1 month"
	# HTC files  (css3pie)
	  ExpiresByType text/x-component          "access plus 1 month"
	# Webfonts
	  ExpiresByType application/vnd.ms-fontobject "access plus 1 month"
	  ExpiresByType application/x-font-ttf    "access plus 1 month"
	  ExpiresByType application/x-font-woff   "access plus 1 month"
	  ExpiresByType font/opentype             "access plus 1 month"
	  ExpiresByType image/svg+xml             "access plus 1 month"
	# CSS and JavaScript
	  ExpiresByType application/javascript    "access plus 1 week"
	  ExpiresByType text/css                  "access plus 1 week"
	</IfModule>');

	fwrite($fp, '
	# CACHE CONTROL

# 1 MONTH
<FilesMatch "\.(flv|ico|pdf|avi|mov|ppt|doc|mp3|wmv|wav|ttf|otf|eot|svg)$">
Header set Cache-Control "max-age=2592000, public"
</FilesMatch>

# 1 WEEK
<FilesMatch "\.(jpg|jpeg|png|gif|swf|js|css)$">
Header set Cache-Control "max-age=604800, public"
</FilesMatch>

# 1 HOUR
<FilesMatch "\.(txt|xml)$">
Header set Cache-Control "max-age=3600, public"
</FilesMatch>

# NEVER CACHE
<FilesMatch "\.(html|htm|php|cgi|pl)$">
Header set Cache-Control "max-age=0, private, no-store, no-cache, must-revalidate"
</FilesMatch>');

	fclose($fp);
}
?>

<?php //include("bottom.php");?>