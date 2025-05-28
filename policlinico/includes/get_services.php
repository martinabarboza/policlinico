<?php
// Obtener todos los servicios desde la base de datos
$conn = conectarDB();
$query = "SELECT * FROM servicios ORDER BY id ASC";
$resultado = $conn->query($query);

if ($resultado && $resultado->num_rows > 0) {
    // Hay servicios para mostrar
    while ($servicio = $resultado->fetch_assoc()) {
        ?>
        <div class="service-card">
            <div class="service-icon">
                <i class="fas <?php echo $servicio['icono']; ?>"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title" id="<?php echo strtolower(str_replace(' ', '', $servicio['nombre'])); ?>"><?php echo $servicio['nombre']; ?></h3>
                <p class="service-description"><?php echo $servicio['descripcion']; ?></p>
                <?php if ($servicio['precio']) { ?>
                    <p class="service-price"><?php echo formatearPrecio($servicio['precio']); ?></p>
                <?php } ?>
            </div>
        </div>
        <?php
    }
} else {
    // No hay servicios
    echo '<div class="empty-message">No hay servicios disponibles en este momento.</div>';
}

// Cerrar la conexión
$conn->close();
?>