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
    <title>Modificar Usuario</title>
</head>
<body>
    <h2>Modificar Usuario</h2>
    <form action="/usuarios/actualizarUsuario" method="POST">
        <input type="hidden" name="cedulaOld" value="<?php echo htmlspecialchars($usuarioEdit['cedula_Usuario']); ?>">
        <label for="cedula"> Cedula: </label>
        <input type="text" name="cedula" value="<?php echo htmlspecialchars($usuarioEdit['cedula_Usuario']); ?>" required><br>
        <label for="nombre"> Nombre: </label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuarioEdit['nombre_Usuario']); ?>" required><br>
        <label for="apellido"> Apellido: </label>
        <input type="text" name="apellido" value="<?php echo htmlspecialchars($usuarioEdit['apellido_Usuario']); ?>" required><br>
        <label for="rol"> Rol: </label>
        <select name="rol" id="rol" required>
            <option value="">SELECCIONE UN ROL</option>
            <?php $rolesUsuario = [
                'DIRECTOR' => 'Director',
                'VETERINARIO' => 'Veterinario',
                'RECEPCION' => 'Recepción'
            ];

            foreach($rolesUsuario as $i => $etiqueta){
                $selected =($usuarioEdit['rol_Usuario'] === $i) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$etiqueta</option>";
            } ?>
        </select><br>
        <!--<input type="text" name="rol" value="<?php// echo htmlspecialchars($usuarioEdit['rol_Usuario']); ?>" required><br>-->
        <label for="email"> Email: </label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($usuarioEdit['email_Usuario']); ?>" required><br>
        <label for="passw"> Contraseña: </label>
        <input type="password" name="passw"><br>
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>