<?php
//error_reporting(0);
function listarArchivos($path){

	//Abrimos el directorio
	$dir = opendir($path);

	//Declaramos el ARRAY $files
	$files = array();

	//Inicializamos $id a 0
	$id=0;

	//Conectamos a la base de datos
	$conn = mysql_connect("localhost", "root", "contrasena");

	//Seleccionamos la tabla
	mysql_select_db("punteos", $conn);
								
	//Recorremos la estructura de directorios
	while ($elemento = readdir($dir)){

		if( $elemento != "." && $elemento != ".." ){
			
			//Si el objeto es un direcorio
			if( is_dir($path.$elemento) ){
			listarArchivos( $path.$elemento.'/' );
			}
			
			//Si el objeto es un archivo, lo incluimos en el array, (lo que se incluye es el nombre del archivo)
			else{
			$files[] = $elemento;
			}

		}
	}

	//Recorremos los objetos del array
	for($x=0; $x<count( $files ); $x++){

		//Separamos el nombre de la extensión
		$dividido = explode(".", $files[$x]);

		//Si la extensión es .xml
		if($dividido[1]=="xml"){

			//Asigna a $file la ruta y el nombre de archivo, files[$x] es el archivo que estamos comprobando ahora con el bucle for
			$file = $path.$files[$x];

			//Carga el archivo XML
			$xml = simplexml_load_file($file) or die ("No se pudo cargar el archivo XML!");

			// Comprueba si la etiqueta 'tipo' del XML es "Producto"
			if($xml->tipo=="Producto"){
			
				//Comprueba si cierta etiqueta tiene cierto valor (53)
				if(!$xml->atributos->atributo->attributes("tipo", 53)){
	
						//Recorremos todos los atributos	
						foreach($xml->atributos->atributo->attributes() as $a => $b) {
							
							
							if($a == "tipo" && $b ==53){
							
							
							$rutaimagen=$xml->atributos->atributo;
							$nombreimagen=explode("/", $rutaimagen);
							$rutaimg = array($nombreimagen[2],$nombreimagen[3],$nombreimagen[4],$nombreimagen[5]);
							$rutalimpia = implode("/", $rutaimg);
								if(!fopen($rutalimpia, "r")){
									echo "\n----------------------------------------------------------\n";
															echo "PRODUCTO:\n";
															echo "Nombre:\n" . $xml->nombre . "\n";
															echo "Ruta:\n" . $path.$files[$x]."\n";
															echo "\nIMAGEN: ".$xml->atributos->atributo."\n";

									echo $nombreimagen[5]."\n";
									echo "Ruta IMAGEN: ".$rutalimpia."\n";	

									echo "***El articulo ' ".$xml->id." ' NO TIENE IMAGEN";
									

									$queEmp = "INSERT INTO imagenespaloo (idarticulopaloo) VALUES(".$xml->id.")";
									echo $queEmp;
									$id++;
									$resEmp = mysql_query($queEmp, $conn) or die(mysql_error());
				
													
								}
							}
						}				
				}
			}

		}

	}



}
echo "\n";

//LLAMAMOS A LA FUNCION, pasandole como parametro la ruta a explorar
listarArchivos("./");

mysql_close($conn);
?>
