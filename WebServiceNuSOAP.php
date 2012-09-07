<?php 

//Llamamos la librer�a NUSOAP
require_once('lib/nusoap.php');


//Inicializamos el cliente nusoap, pasandole la url del WebService y el tipo de wdsl
$cliente = new nusoap_client('https://ws.seur.com/webseur/services/WSConsultaExpediciones?wsdl', 'wdsl'); 

//Asignamos a $hoy la fecha actual
$hoy = date("d-m-Y");

//A la fecha actual le sumamos un d�a
$hoy_mas1 = date("d-m-Y",time()+(1*24*60*60));
echo $hoy_mas1."\n";

//A la fecha actual le restamos 3 d�as
$hoy_menos3 = date('d-m-Y',time()-(3*24*60*60));
echo $hoy_menos3."\n";

//Rellenamos un array con los par�metros para el WebService
$parametros = array('in0'=>'S', 'in5'=>$hoy_menos3, 'in6'=>$hoy_mas1, 'in12'=>'usuario', 'in13'=>'contrase�a', 'in14'=>'N'); 

//Enviamos los parametros al WebService 
$aRespuesta = $cliente->call("consultaListadoExpedicionesStr", array('parameters' => $parametros), '', '', false, true); 

//Recogemos la respuesta en una variable
$respuesta=htmlspecialchars_decode($cliente->response);

//Dividimos el String para eliminar la cabecera de la respuesta, y quedarnos solo con el XML
$pieces = explode("UTF-8", $respuesta);
$xml = simplexml_load_string($pieces[1]);

//Cargamos el XML para parsearlo
$DOM = new DOMDocument('1.0', 'utf-8');
$DOM->loadXML($pieces[1]);

//Asignamos las etiquetas "EXPEDICION" al array $expediciones
$expediciones = $DOM->getElementsByTagName('EXPEDICION');

//Abrimos o creamos un fichero de texto
$seur=fopen("seur.txt","w"); 

//Recorremos el fichero XML de EXPEDICION a EXPEDICION
foreach($expediciones as $expedicion) {

//Asignamos a $pedido el valor de la etiqueta REMITE_REF
$pedido=substr($expedicion->getElementsByTagName("REMITE_REF")->item(0)->nodeValue, 0, 6);


//Escribimos en el fichero de texto el valor de la etiqueta REMITE_REF y DESCRIPCION_PARA_CLIENTE
fputs($seur, $pedido.";".$expedicion->getElementsByTagName("DESCRIPCION_PARA_CLIENTE")->item(0)->nodeValue." \n"); 

}
//Ahora cerraremos el fichero 
fclose($seur);


//GUARDAR XML
//$xml->asXML('updated.xml');



?>


