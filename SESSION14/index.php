<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

	<title>McDonald's - Menú</title>
</head>
<body>
	<div class="container">
		<div class="header">
    <h1 style="text-align: left">McDonald's</h1>
    <a href="carrito.php" class="carrito-link">Carrito 🛒(<?php echo count($_SESSION['carrito']); ?>)</a>
    <a href="agregar_producto.php" class="boton-agregar">Añadir Producto ➕</a>
</div>
	


			<h2>Nuestro Menú todos los dias del año 😋</h2>
			<div class="productos">
		    <?php
		    $stmt = $conn->query("SELECT * FROM productos");
		    while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
		        echo '<div class="producto">';
		            echo '<div style="text-align:center;">';
		                echo '<img src="imagenes/' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '" style="width:200px; height:180px; object-fit:cover;">';
		            echo '</div>';

		            echo '<h3>' . htmlspecialchars($producto['nombre']) . '</h3>';
		            echo '<p>' . htmlspecialchars($producto['descripcion']) . '</p>';
		            echo '<div class="precio">$' . number_format($producto['precio'], 2) . '</div>';

		            echo '<form method="post" action="agregar_al_carrito.php">';
		                echo '<input type="hidden" name="id_producto" value="' . $producto['id_producto'] . '">';
		                echo '<button type="submit">Agregar al Carrito</button>';
		            echo '</form>';
		        echo '</div>'; // cierre de .producto
		    }
		    ?>
</div>

	</div>
</body>
</html>
