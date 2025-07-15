<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cantidad'])) {
	foreach ($_POST['cantidad'] as $indice => $cantidad) {
		if (isset($_SESSION['carrito'][$indice])) {
			$_SESSION['carrito'][$indice]['cantidad'] = max(1,(int)$cantidad);
			$_SESSION['carrito'];
			/*max (1, ...) Compara el valor convertido con 1 y toma el maryor de ambos. (Garantiza que la cantidad nunca sea menor que 1)*/
		}
		
	}
}
header('Location: carrito.php');
exit()
?>