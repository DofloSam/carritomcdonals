document.addEventListener("DOMContentLoaded", function() {
    
    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById("modalEditar").style.display = "none";
        document.getElementById("preview").style.display = "none"; // Ocultar vista previa
    }

    // Detectar clic en los botones de edición
    document.querySelectorAll(".editar").forEach(boton => {
        boton.addEventListener("click", function(event) {
            event.preventDefault(); // Evitar recargas inesperadas

            let idProducto = this.getAttribute("data-id");
            let nombre = this.getAttribute("data-nombre");
            let descripcion = this.getAttribute("data-descripcion");
            let precio = this.getAttribute("data-precio");
            let categoria = this.getAttribute("data-categoria");
            let imagen = this.getAttribute("data-imagen");

            document.getElementById("id_producto_edit").value = idProducto;
            document.getElementById("nombre_edit").value = nombre;
            document.getElementById("descripcion_edit").value = descripcion;
            document.getElementById("precio_edit").value = precio;
            document.getElementById("categoria_edit").value = categoria;

            // Si hay una imagen asignada, mostrarla en la vista previa
            if (imagen) {
                document.getElementById("preview").style.display = "block";
                document.getElementById("preview").src = "imagenes/" + imagen;
            } else {
                document.getElementById("preview").style.display = "none";
            }

            document.getElementById("modalEditar").style.display = "flex";
        });
    });

    // Cerrar el modal al hacer clic en "X"
    let cerrarBoton = document.querySelector(".cerrar");
    if (cerrarBoton) {
        cerrarBoton.addEventListener("click", cerrarModal);
    }

    // Cerrar el modal al hacer clic en "Cancelar"
    let cancelarBoton = document.getElementById("cancelarModal");
    if (cancelarBoton) {
        cancelarBoton.addEventListener("click", cerrarModal);
    }

    // Cerrar el modal si el usuario hace clic fuera
    window.addEventListener("click", function(event) {
        let modal = document.getElementById("modalEditar");
        if (event.target === modal) {
            cerrarModal();
        }
    });

    // **Vista previa de imagen seleccionada**
    document.getElementById("imagen_edit").addEventListener("change", function(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("preview").style.display = "block";
                document.getElementById("preview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // **Corrección para evitar que el modal aparezca después de agregar un producto**
    if (window.location.href.includes("agregar_producto.php")) {
        cerrarModal(); // Asegura que el modal se cierre si se ha agregado un producto
    }
});
