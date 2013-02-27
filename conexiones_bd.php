<?

/**
* ORACLE
*/

function oracle_conexion(){

	/* Variables de entorno */

	putenv("NLS_LANG=AMERICAN_AMERICA.WE8ISO8859P1");
	/*putenv("/home/oracle/app/oracle/product/11.2.0/dbhome_1/lib");*/
	putenv("ORACLE_HOME=/usr/lib/oracle/11.2/client64");
	putenv("LD_LIBRARY_PATH=/usr/lib/oracle/11.2/client64/lib");
	putenv("TNS_ADMIN=/etc/oracle/");

	/* Conexión a Oracle */
	$oracle_conexion = oci_connect('BASEDATOS', 'contraseña', 'esquema');

	if (!$oracle_conexion) {

		$e = oci_error();
	    print htmlentities($e['message']);
	    exit;

	}

	return $oracle_conexion;

}

function oracle_desconexion($oracle_conexion){

	/*Cerramos conexion oracle*/
	oci_close($oracle_conexion);
	
}

function oracle_update($UPDATE, $oracle_conexion){

	/* Lanzamos Consulta MySQL */
	$consulta_oracle_insertar = $UPDATE;
		echo "\n".$consulta_oracle_insertar."\n";

	$sentencia_oracle_insertar = oci_parse($oracle_conexion, $consulta_oracle_insertar);

	/* Errores Oracle y Ejecucion */
	if (!$sentencia_oracle_insertar) {
	    $e = oci_error($conexion);
	    print htmlentities($e['message']);
	    exit;
	}

	$resultado_oracle_insertar = oci_execute($sentencia_oracle_insertar, OCI_DEFAULT);
	if (!$resultado_oracle_insertar) {
	    $e = oci_error($sentencia_oracle_insertar);
	    echo htmlentities($e['message']);
	    exit;
	}

}

function oracle_commit($oracle_conexion){

	/* Hacemos COMMIT Oracle */
	$consulta_oracle_commit = "commit";
		echo "\n\n<br /><br />".$consulta_oracle_commit."<br /><br />\n\n";

	$sentencia_oracle_commit = oci_parse($oracle_conexion, $consulta_oracle_commit);

	/* Errores Oracle y Ejecucion */
	if (!$sentencia_oracle_commit) {
		$e = oci_error($conexion);
		print htmlentities($e['message']);
		exit;
	}

	$resultado_oracle_commit = oci_execute($sentencia_oracle_commit, OCI_DEFAULT);

	if (!$resultado_oracle_commit) {
		$e = oci_error($sentencia_oracle_commit);
		echo htmlentities($e['message']);
		exit;
	}

}

function oracle_select($SELECT, $oracle_conexion){

	/* Preparamos Consulta Oracle */
	$consulta_oracle = $SELECT;

	$sentencia_oracle = oci_parse($oracle_conexion, $consulta_oracle);

	/* Errores Oracle */
	if (!$sentencia_oracle) {
	    $e = oci_error($conexion);
	    print htmlentities($e['message']);
	    exit;
	}

	$resultado_oracle = oci_execute($sentencia_oracle, OCI_DEFAULT);
	if (!$resultado_oracle) {
	    $e = oci_error($sentencia_oracle);
	    echo htmlentities($e['message']);
	    exit;
	}

	/* Resultados Oracle */
	return $sentencia_oracle;

}

/**
* MySQL
*/

function mysql_conexion(){

	/*Conexion a MySQL*/

	$mysql_conexion = mysql_connect("IP.DEL.SERVIDOR","root","contraseña") or
	  die("Problemas en la conexion");

	/* Seleccionamos la BD */

	mysql_select_db("dsf",$mysql_conexion) or
	  die("Problemas en la selección de la base de datos");

	  return $mysql_conexion;

}

function mysql_desconexion($mysql_conexion){

	mysql_close($mysql_conexion);

}

?>