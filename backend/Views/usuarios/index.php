<!DOCTYPE html>
<html lang="en">
<style>
    /* Ocultar el spinner */
    #spinner {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <h1>Gestión de Usuarios</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Usuario actualizado exitosamente.</p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">Error al actualizar el usuario.</p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['cedula_Usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre_Usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['apellido_Usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['rol_Usuario']); ?></td>
                        <td>
                            <a href="/usuarios/editar?cedula=<?php echo urlencode($usuario['cedula_Usuario']); ?>">Modificar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No hay usuarios registrados.</td>  
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>