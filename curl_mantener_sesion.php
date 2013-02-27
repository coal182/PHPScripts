<?php

/**
 * Este Script inicia sesión en una página web, mantiene la sesión y va a una subpágina manteniendo el usuario.
 * Para terminar, con cURL limpia la caché de artículos de Paloo
 * cURL
 */

/* Iniciamos sesión */

$url = "http://www.pagina.es/panel/procesaIdentificacion.php?password=contrasena&username=admin";  
$postData = array("username" => "admin", "password" => "contrasena");  

/*Convierte el array en el formato adecuado para cURL*/  

$elements = array();  
foreach ($postData as $name=>$value) {  
   $elements[] = "{$name}=".urlencode($value);  
}  

$url2 = "http://www.pagina.es/panel/admin/cache/cache.jsp?limpiar=productos";  

$handler = curl_init();  
curl_setopt($handler, CURLOPT_URL, $url);  
curl_setopt($handler, CURLOPT_POST,true);  
curl_setopt($handler, CURLOPT_POSTFIELDS, $elements);  
curl_setopt($handler, CURLOPT_COOKIEJAR,"cookie.txt");
curl_setopt($handler, CURLOPT_COOKIEFILE,"cookie.txt");
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handler, CURLOPT_FRESH_CONNECT, true);
curl_setopt($handler, CURLOPT_COOKIESESSION, "cookie.txt");


$response = curl_exec ($handler);  



curl_close($handler);


$url = "http://www.pagina.es/panel/admin/cache/cache.php?limpiar=cache";    

$handler = curl_init();  
curl_setopt($handler, CURLOPT_URL, $url);  
curl_setopt($handler, CURLOPT_POST,true);  
curl_setopt($handler, CURLOPT_POSTFIELDS, $elements);  
curl_setopt($handler, CURLOPT_COOKIEJAR,"cookie.txt");
curl_setopt($handler, CURLOPT_COOKIEFILE,"cookie.txt");
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handler, CURLOPT_FRESH_CONNECT, true);
curl_setopt($handler, CURLOPT_COOKIESESSION, "cookie.txt");


$response = curl_exec ($handler);  



curl_close($handler);




?>