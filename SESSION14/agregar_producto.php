<?php
include("config.php");

// Si se envía el formulario, procesar SOLO la inserción
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar"])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $categoria = $_POST["categoria"];
    $imagenNombre = null;

    // Procesar la imagen si se sube correctamente
    if (!empty($_FILES["imagen"]["name"])) {
        $directorioDestino = "imagenes/";
        $imagenNombre = basename($_FILES["imagen"]["name"]);
        $rutaImagen = $directorioDestino . $imagenNombre;

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen);
    }

    // Insertar datos en la base de datos
    try {
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) VALUES (:nombre, :descripcion, :precio, :categoria, :imagen)");
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":imagen", $imagenNombre);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Producto agregado correctamente.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// **Actualizar producto desde el modal**
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $idProducto = $_POST["id_producto"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $categoria = $_POST["categoria"];
    $imagenNombre = $_POST["imagen_actual"] ?? null;

    // Procesar nueva imagen si se sube
    if (!empty($_FILES["imagen"]["name"])) {
        $directorioDestino = "imagenes/";
        $imagenNombre = basename($_FILES["imagen"]["name"]);
        $rutaImagen = $directorioDestino . $imagenNombre;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen);
    }

    try {
        $stmt = $conn->prepare("UPDATE productos SET nombre=:nombre, descripcion=:descripcion, precio=:precio, categoria=:categoria, imagen=:imagen WHERE id_producto=:id");
        $stmt->bindParam(":id", $idProducto);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":imagen", $imagenNombre);

        if ($stmt->execute()) {
            // **Redirigir para evitar reenvío del formulario**
            header("Location: agregar_producto.php?mensaje=actualizado");
            exit;
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}



// **Eliminar producto**
if (isset($_GET["eliminar"])) {
    $idEliminar = $_GET["eliminar"];
    try {
        $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = :id");
        $stmt->bindParam(":id", $idEliminar);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Producto eliminado correctamente.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Obtener todos los productos
$productos = $conn->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrar Productos</title>
<link rel="stylesheet" href="styles_agregar_producto.css">
</head>
<body>
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado'): ?>
    <p id="mensajeActualizado" style="color:green; text-align:center; font-weight: bold;">Producto actualizado correctamente.</p>
    <script>
        setTimeout(function(){
            document.getElementById('mensajeActualizado').style.display = 'none';
        }, 1500);
    </script>
<?php endif; ?>
<div class="container">
<h2>Agregar Nuevo Producto</h2>
<form action="agregar_producto.php" method="post" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" rows="3" required></textarea>

    <label for="precio">Precio (€):</label>
    <input type="number" id="precio" name="precio" step="0.01" required>

    <label for="categoria">Categoría:</label>
    <select id="categoria" name="categoria" required>
        <option value="" disabled selected>Seleccione una categoría</option>
        <option value="Hamburguesas">Hamburguesas</option>
        <option value="Bebidas">Bebidas</option>
        <option value="Postres">Postres</option>
        <option value="Acompañamientos">Acompañamientos</option>
    </select>

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" name="imagen" accept="image/*">
    <div class="preview-container">
    <img id="previewAgregar" src="" alt="Vista previa" style="display:none; width: 100%; border-radius: 8px; margin-top: 10px;">
</div>

    <button type="submit" name="agregar">Agregar Producto</button>
</form>

<h2>Lista de Productos</h2>
<table>
<thead>
    <tr>
        <th>ID</th>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio (€)</th>
        <th>Categoría</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($productos as $producto): ?>
    <tr>
        <td><?= $producto['id_producto'] ?></td>
        <td><img src="imagenes/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>"></td>
        <td><?= htmlspecialchars($producto['nombre']) ?></td>
        <td><?= htmlspecialchars($producto['descripcion']) ?></td>
        <td><?= number_format($producto['precio'], 2) ?> €</td>
        <td><?= htmlspecialchars($producto['categoria']) ?></td>
        <td class="acciones">
            <button class="editar" data-id="<?= $producto['id_producto'] ?>" data-nombre="<?= htmlspecialchars($producto['nombre']) ?>" data-descripcion="<?= htmlspecialchars($producto['descripcion']) ?>" data-precio="<?= number_format($producto['precio'], 2) ?>" data-categoria="<?= htmlspecialchars($producto['categoria']) ?>">Editar</button>
            <a href="agregar_producto.php?eliminar=<?= $producto['id_producto'] ?>" class="eliminar">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
</div>
<!-- Modal -->
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <span class="cerrar">&times;</span>
        <h2>Editar Producto</h2>
        <form id="formEditarProducto" action="agregar_producto.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="id_producto_edit" name="id_producto">
            
            <label for="nombre_edit">Nombre:</label>
            <input type="text" id="nombre_edit" name="nombre" required>
            
            <label for="descripcion_edit">Descripción:</label>
            <textarea id="descripcion_edit" name="descripcion" rows="3" required></textarea>
            
            <label for="precio_edit">Precio (€):</label>
            <input type="number" id="precio_edit" name="precio" step="0.01" required>
            
            <label for="categoria_edit">Categoría:</label>
            <select id="categoria_edit" name="categoria" required>
                <option value="Hamburguesas">Hamburguesas</option>
                <option value="Bebidas">Bebidas</option>
                <option value="Postres">Postres</option>
                <option value="Acompañamientos">Acompañamientos</option>
            </select>

            <!-- Vista previa de la imagen -->
            <label for="imagen_edit">Imagen:</label>
            <input type="file" id="imagen_edit" name="imagen" accept="image/*">
            <div class="preview-container">
                <img id="preview" src="" alt="Vista previa" style="display:none; width: 100%; border-radius: 8px; margin-top: 10px;">
            </div>

            <div class="modal-botones">
                <button type="submit" name="editar">Guardar Cambios</button>
                <button type="button" id="cancelarModal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <a href="index.php" class="boton-volver">Volver al Menú</a>
</div>


<script src="script_agregar_producto.js"></script>
</body>
</html>
