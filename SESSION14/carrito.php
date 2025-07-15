<?php include("config.php") ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>McDonald's - Carrito de Compras</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; }
.header { background-color: #ff0000; color: white; padding: 10px 20px; margin-bottom: 20px; border-radius: 5px; }
.menu-link { color: white; text-decoration: none; font-weight: bold; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: white; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
.total { font-weight: bold; font-size: 1.2em; text-align: right; }
.acciones { display: flex; justify-content: space-between; margin-top: 20px; }
button { background-color: #fc0; border: none; padding: 10px 20px; border-radius: 3px; cursor: pointer; }
button:hover { background-color: #e6b800; }
.vaciar { background-color: #ff3333; color: white; }
.vaciar:hover { background-color: #cc0000; }
.confirmar { background-color: #33cc33; color: white; }
.confirmar:hover { background-color: #29a329; }
input[type="number"] { width: 50px; text-align: center; }
</style>
</head>
<body>
<div class="container">
<div class="header">
<h1>Carrito de Compras</h1>
<a href="index.php" class="menu-link">Volver al Menú</a>
</div>

<?php if (empty($_SESSION['carrito'])): ?>
<p>Tu carrito está vacío.</p>
<?php else: ?>
<form method="post" action="actualizar_carrito.php">
<table>
<thead>
<tr>
<th>Producto</th>
<th>Precio Unitario</th>
<th>Cantidad</th>
<th>Subtotal</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php
$total = 0;
foreach ($_SESSION['carrito'] as $indice => $producto):
$subtotal = $producto['precio'] * $producto['cantidad'];
$total += $subtotal;
?>
<tr data-indice="<?= $indice ?>">
<td><?= htmlspecialchars($producto['nombre']); ?></td>
<td class="precio"><?= number_format($producto['precio'], 2); ?> €</td>
<td>
<input type="number" name="cantidad[<?= $indice ?>]" class="cantidad" value="<?= $producto['cantidad']; ?>" min="1">
</td>
<td class="subtotal"><?= number_format($subtotal, 2); ?> €</td>
<td><a href="eliminar_carrito.php?indice=<?= $indice; ?>">Eliminar</a></td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="3">Total:</td>
<td class="total-valor"><?= number_format($total, 2); ?> €</td>
</tr>
</tbody>
</table>

<div class="acciones">
<div>
<button type="button" class="vaciar" onclick="location.href='vaciar_carrito.php'">Vaciar Carrito</button>
<button type="button" class="confirmar" onclick="location.href='confirmar_pedido.php'">Confirmar Pedido</button>
</div>
</div>
</form>
<?php endif; ?>
</div>
<script src="carrito.js"></script>
</body>
</html>
