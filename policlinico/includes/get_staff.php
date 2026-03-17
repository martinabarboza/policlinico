<?php
// Obtener el personal médico activo desde la base de datos
$conn = conectarDB();
$query = "SELECT p.*, u.nombre_completo, u.email 
          FROM personal p 
          JOIN usuarios u ON p.usuario_id = u.id 
          WHERE p.activo = 1 
          ORDER BY p.id ASC";
$resultado = $conn->query($query);

if ($resultado && $resultado->num_rows > 0) {
    // Hay personal para mostrar
    while ($miembro = $resultado->fetch_assoc()) {
        ?>
        <div class="staff-card">
            <div class="staff-image">
                <?php if ($miembro['imagen_url']) { ?>
                    <img src="<?php echo $miembro['imagen_url']; ?>" alt="<?php echo $miembro['nombre_completo']; ?>">
                <?php } else { ?>
                    <img src="assets/placeholder-staff.jpg" alt="<?php echo $miembro['nombre_completo']; ?>">
                <?php } ?>
            </div>
            <div class="staff-content">
                <h3 class="staff-name"><?php echo $miembro['nombre_completo']; ?></h3>
                <p class="staff-specialty"><?php echo $miembro['especialidad']; ?></p>
                <p class="staff-bio"><?php echo $miembro['biografia']; ?></p>
            </div>
        </div>
        <?php
    }
} else {
    // No hay personal
    echo '<div class="empty-message">No hay información sobre el equipo médico disponible en este momento.</div>';
}

// Cerrar la conexión
$conn->close();
?>