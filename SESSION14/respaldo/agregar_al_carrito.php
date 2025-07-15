<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'])) {
	$id_producto = $_POST['id_producto'];

	// Obterner informacion del producto
	$stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
	$stmt->execute([$id_producto]);
	$producto = $stmt->fetch(PDO::FETCH_ASSOC); // PDO: PHP DATA Object - FETCH_ASSOC Obtiene una fila de resultados como un array asociativo

	if ($producto) {
		// Verificar si el producto ya esta en el carrito 
		$encontrado = false;
		foreach ($_SESSION['carrito'] as &$item) {
			if ($item['id_producto'] == $id_producto) {
				$item['cantidad'] +=1;
				$encintrado = true;
				break;
			}
		}
	
	if(!$encontrado) {
		// Agregar nuevo producto al carrito
		$_SESSION['carrito'][] = array(
			'id_producto' => $producto['id_producto'],
			'nombre' => $producto['nombre'],
			'precio' => $producto['precio'],
			'cantidad' => 1
			);
		}
	}
}

 header('Location: index.php');
 exit();
 ?>


