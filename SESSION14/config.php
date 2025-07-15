<?php
//Configuaricon de conexion a la base de datos
$host = 'localhost';
$dbname = 'carrito_mcdonalds';
$username = 'root';
$password = '';

try{
	//Intentar establecer conexion con la base de datos usando PDO
	$conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);

	//Configuarar el modo de error para que PDO lance excepciones cuando ocuarran errores
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
	//Si ocurre un error en la conexion, mostrar el mensaje de error
	echo "Error de conexion:" . $e->getMessage();
}

//Verificar si la sesion no esta iniciada
if (session_status() == PHP_SESSION_NONE) {
	//Iniciar la sesion PHP para poder usar variables $_SESSION
	session_start();
	//Las varuables de sesion persisten mientras el navegador este abierto
}

// Verificar si no existe la variable 'carrito' en la sesion
if (!isset($_SESSION['carrito'])) {
	//Inicializar el carrito como un array vacio si no existe
	$_SESSION['carrito'] = array();
	// Este array almacenara los productos que el usuario agregue al carrito
}
?>
