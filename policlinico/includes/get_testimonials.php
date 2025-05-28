<?php
// Obtener testimonios aprobados desde la base de datos
$conn = conectarDB();
$query = "SELECT * FROM testimonios WHERE aprobado = 1 ORDER BY id DESC LIMIT 6";
$resultado = $conn->query($query);

if ($resultado && $resultado->num_rows > 0) {
    // Hay testimonios para mostrar
    while ($testimonio = $resultado->fetch_assoc()) {
        ?>
        <div class="testimonial-card">
            <div class="testimonial-text">
                <?php echo $testimonio['texto']; ?>
            </div>
            <div class="testimonial-author">
                - <?php echo $testimonio['autor']; ?>
            </div>
            <div class="testimonial-rating">
                <?php echo mostrarEstrellas($testimonio['calificacion']); ?>
            </div>
        </div>
        <?php
    }
} else {
    // No hay testimonios
    echo '<div class="empty-message">No hay testimonios disponibles en este momento.</div>';
}

// Cerrar la conexión
$conn->close();
?>