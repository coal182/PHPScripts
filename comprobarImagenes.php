<?php

/*
 *Autor: Cristian Martín
 *Descripción: Script que parsea un XML obtenido de una web, comprueba la existencia de unas imágenes o cualquier otro
 *recurso web, y si ese recurso no está disponible (Error 404) escribimos en un fichero de texto el código del (artículo en este caso).
 */

error_reporting(0);

//Array que almacena los grupos:

 $grupos = array("3542","3543","3544","3545","3546","3547","3549","3550","3551","3552","3553","3554","3555","3556","3960");

	
//Recorremos el array
for($i=0; $i<count($grupos); $i++){

	echo $i."-".$grupos[$i];
	
	  //Usamos cUrl para obtener la página web deseada (usamos cUrl para mayor seguridad)
	  //Iniciamos pasandole la ruta de la página deseada, con el parámetro pagina que aumentará en cada iteración del bucle for
	  $curl = curl_init("http://pagina.es/1/2.jsp?user=user&pass=pass&family=".$grupos[$i]."");
	  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');
		  
	  //Asignamos los datos obtenidos a la variable $xml
	  $xml = curl_exec($curl);
	  //Cerramos la operacion cUrl
	  curl_close($curl);
	  
	  //PARSEAR XML
	  //Cargamos el archivo XML
	  $xml = simplexml_load_string($xml) or die ("No se pudo cargar el archivo XML!");
		
	  //Recorremos los objetos del XML
	  foreach($xml->{'Product'} as $item) {	

		  //Asignamos los valores de los campos a variables
		  $PRO_CA_COD_REFERENCE = $item->PRO_CA_COD_REFERENCE;
		  $IMAGE_SMALL = $item->IMAGE_SMALL;
		  
		  //Le añadimos www a la URL debido a que puede dar error 301(Redirección) en lugar de 404.
		  $IMAGE_SMALL = str_replace( 'pagina', 'www.pagina', $IMAGE_SMALL );
		  //Las mostramos por pantalla
		  echo "$PRO_CA_COD_REFERENCE -- $IMAGE_SMALL\n";
		
		  //Intentamos abrir la foto con cUrl
		  $curl_handler = curl_init($IMAGE_SMALL);
		  $foto = curl_exec($curl_handler);
		  //Recogemos el código de error
		  $info = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE); 
		  //Cerramos el cUrl
		  curl_close($curl_handler);
	
		  //Si no está
		  if($info == "404"){
		  	echo "El artículo $PRO_CA_COD_REFERENCE no tiene foto";
			
			//Escribimos en un fichero de texto
			$texto = fopen("/var/scripts/comprobarImagenes.txt", "a");
			fwrite($texto, $PRO_CA_COD_REFERENCE.";");
			fclose($texto);
		  }

	  }

}

?>
