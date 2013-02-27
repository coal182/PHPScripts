<?

function html_generico($title){

	echo '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
		<head>
	';	

	echo '<title>'.$title.'</title>';

	echo '
	<link href="/sistemas/css/estilos.css" rel="stylesheet" title="default" type="text/css" /></link>
		</head>
		<body>
	';
}

function html_generico_cierre(){

	echo '

		</body>
		</html>

	';	

}

?>