<?php

//$paginas es el numero de páginas que tiene el listado, este bucle aumentará en 1 la variable pagina, que a su vez
//se pasará como parametro a la url
for($paginas=1; $paginas<=31;$paginas++){

  //Usamos cUrl para obtener la página web deseada (usamos cUrl para mayor seguridad)
  //Iniciamos pasandole la ruta de la página deseada, con el parámetro pagina que aumentará en cada iteración del bucle for
  $curl = curl_init("http://www.paginaweb.es/primera/segunda/tercera/_listado1.asp?;P=".$paginas."");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');
  
  //Asignamos los datos obtenidos a la variable $html
  $html = curl_exec($curl);
  //Cerramos la operacion cUrl
  curl_close($curl);
  
  $oldSetting = libxml_use_internal_errors( true );
  libxml_clear_errors();
  
  //Creamos un nuevo DOMDocument
  $dom = new DOMDocument();
  
  //Cargamos en él los datos obtenidos con cUrl ($html)
  @$dom->loadHTML($html);
  
  //Usamos XPath para parsear el objeto DOM obtenido
  $xpath = new DOMXPath( $dom );
  
  //Hacemos las consultas XPath
  
  //Coge todos los <td> y <a href="mailto:..."> de la página
  $emails = $xpath->query( '//a[starts-with(@href,"mailto:")] | //td ');
  
  //Recorremos todos los datos obtenidos con la consulta Xpath
  foreach ( $emails as $email ) {
  
    //Comprobamos si el <td> obtenido está formado por 8 números
   if(preg_match('/^[0-9]{8,}/',$email->firstChild->nodeValue)){
  
      //Si es así asignamos a la variable $codigo, dicho contenido
    $codigo=$email->firstChild->nodeValue;
      
      //Con esto, le quitamos los 2 últimos caracteres, ya que en este caso, no nos sirven
    $codigo= substr($codigo, 0, -2);
      
      //Sacamos el valor por pantalla
    echo "CODIGO: ".$codigo." -> ";
  
   }
  
    //Comprobamos si el valor del atributo 'href:' del <td> o del <a href="mailto:...">  
    //obtenido empieza por 'mailto:' (así nos aseguramos de que trabajamos con el email)
    if(preg_match('/^mailto:/', $email->getAttribute( 'href' ))){
  
      //Comprobamos si el contenido del href, tiene un mail o está vacío, lo hacemos
      //comprobando si contiene una @
     if(preg_match('/@/', $email->getAttribute( 'href' ))){
    
        //Con el explode, separamos la parte 'mailto:' del email en si.
      $explosion= explode(":", $email->getAttribute( 'href' ));
        //Seleccionamos la 2ª parte del array obtenido (el email)  la asignamos a $direccion
      $direccion= $explosion[1];
    
        //Si $direccion no está vacía
        if($direccion!="" && $direccion!=" "){
           //La sacamos por pantalla con un salto de linea
        echo $direccion. "\n";        
      }

     /*Si no contiene una @, es que no tiene ningun Email, por lo que está vacio
      * Introducimos un salto de línea para que si no hay Email, aparezca de la siguiente forma en pantalla:
      *
      * CODIGO ->
      * CODIGO -> hayemail@php.com
      * CODIGO -> hayemail@php.com
      * CODIGO -> 
      * CODIGO -> hayemail@php.com
      */
     }else{echo "\n";}
    }
   
  }

  libxml_clear_errors();
  libxml_use_internal_errors( $oldSetting );
  libxml_use_internal_errors( $oldSetting );

}

?>